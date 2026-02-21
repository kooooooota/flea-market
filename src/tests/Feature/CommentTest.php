<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Condition;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Database\Seeders\CategoriesTableSeeder;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategoriesTableSeeder::class);
    }
    
    public function test_user_can_comment()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/Armani+Mens+Clock.jpg',
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::whereIn('name', ['ファッション', 'メンズ'])->pluck('id')->toArray();
        $item->categories()->attach($categoriesIds);

        $commentData = ['body' => 'コメント送信テストです'];

        $response = $this->actingAs($user)
                         ->post(route('items.comment', $item), $commentData);

        $this->assertDatabaseHas('comments',[
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'コメント送信テストです',
        ]);

        $this->get(route('items.show', $item))
             ->assertStatus(200)
             ->assertSeeInOrder([
                'speech-bubble.png',
                '1'
             ])
             ->assertSee('コメント(1)')
             ->assertSee('コメント送信テストです');
    }

    public function test_guest_cannot_comment()
    {
        $seller = User::factory()->create();
        $item = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/Armani+Mens+Clock.jpg',
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::whereIn('name', ['ファッション', 'メンズ'])->pluck('id')->toArray();
        $item->categories()->attach($categoriesIds);

        $response = $this->post(route('items.comment', $item), [
            'body' => '未ログインのコメント送信テストです'
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'body' => '未ログインのコメント送信テストです'
        ]);
    }

    public function test_comment_requires_body()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/Armani+Mens+Clock.jpg',
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::whereIn('name', ['ファッション', 'メンズ'])->pluck('id')->toArray();
        $item->categories()->attach($categoriesIds);

        $commentData = ['body' => ''];

        $response = $this->actingAs($user)
                         ->from(route('items.show', $item))
                         ->post(route('items.comment', $item), $commentData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'body' => 'コメントを入力してください',
        ]);
    }

    public function test_comment_fails_if_body_is_too_long()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/Armani+Mens+Clock.jpg',
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::whereIn('name', ['ファッション', 'メンズ'])->pluck('id')->toArray();
        $item->categories()->attach($categoriesIds);

        $commentData = str_repeat('a', 256);

        $response = $this->actingAs($user)
                         ->from(route('items.show', $item))
                         ->post(route('items.comment', $item), [
                             'body' => $commentData,
                         ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('items.show', $item));

        $response->assertSessionHasErrors([
            'body' => 'コメントは255文字未満で入力してください'
        ]);
    }
}
