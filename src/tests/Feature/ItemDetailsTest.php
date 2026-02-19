<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\ItemsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use App\Enums\Condition;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Profile;

class ItemDetailsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_can_display_item_details()
    {
        $this->seed(CategoriesTableSeeder::class);

        User::factory()->create();

        $this->seed(ItemsTableSeeder::class);

        $item = Item::with('categories')->first();

        $likers = User::factory()->count(2)->create();
        foreach ($likers as $liker) {
            $liker->favoriteItems()->attach($item->id);
        }

        $userWithImage = User::factory()->create(['name' => '画像あり太郎']);
        Profile::create([
            'user_id' => $userWithImage->id,
            'image_path' => 'profiles/test-avatar.jpg',
            'user_name' => '画像あり太郎',
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
        ]);

        Comment::factory()->create([
            'user_id' => $userWithImage->id,
            'item_id' => $item->id,
            'body' => '画像ありのコメントです'
        ]);

        $userNoImage = User::factory()->create(['name' => '画像なし太郎']);
        Profile::create([
            'user_id' => $userNoImage->id,
            'image_path' => null,
            'user_name' => '画像なし太郎',
            'zip_code' => '222-2222',
            'address' => '渋谷区渋谷',
        ]);

        Comment::factory()->create([
            'user_id' => $userNoImage->id,
            'item_id' => $item->id,
            'body' => '画像なしのコメントです'
        ]);

        $response = $this->get(route('items.show', $item));

        $response->assertStatus(200)
                 ->assertSee('storage/' . $item->image_path)
                 ->assertSee($item->name)
                 ->assertSee($item->brand_name)
                 ->assertSee('15,000')
                 ->assertSee('2')
                 ->assertSee('3')
                 ->assertSee($item->description);
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
        $response->assertSee(Condition::LikeNew->label())
                 ->assertSee('コメント(2)')
                 ->assertSee('storage/' . $userWithImage->image_path)
                 ->assertSee('画像あり太郎')
                 ->assertSee('画像ありのコメントです')
                 ->assertSee('is-empty')
                 ->assertSee('画像なし太郎')
                 ->assertSee('画像なしのコメントです');
    }
}
