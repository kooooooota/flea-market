<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Condition;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Database\Seeders\CategoriesTableSeeder;

class MylistTest extends TestCase
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

    public function test_mylist_displays_only_liked_items()
    {
        $user = User::factory()->create();

        $seller = User::factory()->create();

        $likedItem = Item::create([
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
        $likedItem->categories()->attach($categoriesIds);

        $notLikedItem = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/HDD+Hard+Disk.jpg',
            'name' => 'HDD',
            'brand_name' => '西芝',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => Condition::VeryGood->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::where('name', '家電')->value('id');
        $notLikedItem->categories()->attach($categoriesIds);

        $user->favoriteItems()->attach($likedItem->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('腕時計');

        $response->assertDontSee('HDD');
    }

    public function test_mylist_displays_sold_label_on_purchased_items()
    {
        $user = User::factory()->create();

        $seller = User::factory()->create();

        $soldItem = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/Armani+Mens+Clock.jpg',
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => Condition::LikeNew->value,
            'sold' => true,
        ]);

        $categoriesIds = Category::whereIn('name', ['ファッション', 'メンズ'])->pluck('id')->toArray();
        $soldItem->categories()->attach($categoriesIds);

        $onSaleItem = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/HDD+Hard+Disk.jpg',
            'name' => 'HDD',
            'brand_name' => '西芝',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => Condition::VeryGood->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::where('name', '家電')->value('id');
        $onSaleItem->categories()->attach($categoriesIds);

        $user->favoriteItems()->attach([$soldItem->id, $onSaleItem->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('腕時計');
        $response->assertSee('Sold');

        $response->assertSee('HDD');
    }

    public function test_guest_cannot_see_items_in_mylist()
    {
        $seller = User::factory()->create();

        $soldItem = Item::create([
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
        $soldItem->categories()->attach($categoriesIds);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertDontSee('腕時計');
    }
}
