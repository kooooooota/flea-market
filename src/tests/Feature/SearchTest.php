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

class SearchTest extends TestCase
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
    
    public function test_can_search_items_by_name_partial_match()
    {
        $seller = User::factory()->create();

        $item1 = Item::create([
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
        $item1->categories()->attach($categoriesIds);

        $item2 = Item::create([
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
        $item2->categories()->attach($categoriesIds);

        $response = $this->get('/?keyword=時計');

        $response->assertStatus(200);

        $response->assertSee('腕時計');
        $response->assertDontSee('HDD');
    }

    public function test_search_keyword_is_maintained_in_mylist_tab()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $targetItem = Item::create([
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
        $targetItem->categories()->attach($categoriesIds);

        $otherItem = Item::create([
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
        $otherItem->categories()->attach($categoriesIds);

        $unmatchedItem = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/iLoveIMG+d.jpg',
            'name' => '玉ねぎ3束',
            'brand_name' => 'なし',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'condition' => Condition::Good->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::where('name', 'キッチン')->value('id');
        $otherItem->categories()->attach($categoriesIds);

        $user->favoriteItems()->attach([$targetItem->id, $unmatchedItem->id]);

        $response = $this->actingAs($user)->get('/?keyword=時計&tab=maylist');

        $response->assertStatus(200);

        $response->assertSee('腕時計');
        $response->assertDontSee('HDD');
        $response->assertDontSee('玉ねぎ3束');
    }
}
