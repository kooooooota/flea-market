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

class LikeTest extends TestCase
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
    
    public function test_user_can_like_and_unlike_item()
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
        
        $this->actingAs($user)
             ->post(route('items.favorite', $item));

        $this->get(route('items.show', $item))
             ->assertStatus(200)
             ->assertSeeInOrder([
                'item-detail__likes-img',
                '1'
             ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user)
             ->post(route('items.favorite', $item));
        
        $this->get(route('items.show', $item))
             ->assertStatus(200)
             ->assertSeeInOrder([
                'item-detail__likes-img',
                '0'
             ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_like_icon_image_toggles_after_liking()
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

        $this->actingAs($user)
             ->get(route('items.show', $item))
             ->assertStatus(200)
             ->assertSee('heart-logo-default.png');

        $this->actingAs($user)
             ->post(route('items.favorite', $item));

        $user->load('favoriteItems');

        $this->get(route('items.show', $item))
             ->assertStatus(200)
             ->assertSee('heart-logo-default_pink.png')
             ->assertDontSee('heart-logo-default.png');
    }
}
