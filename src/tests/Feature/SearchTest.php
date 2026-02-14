<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Condition;
use App\Models\Item;
use App\Models\User;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function test_can_search_items_by_name_partial_match()
    {
        $seller = User::factory()->create();

        Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/Armani+Mens+Clock.jpg',
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'category_ids' => [1, 5],
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/HDD+Hard+Disk.jpg',
            'name' => 'HDD',
            'brand_name' => '西芝',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => Condition::VeryGood->value,
            'category_ids' => [2],
            'sold' => false,
        ]);

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
            'category_ids' => [1, 5],
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $otherItem = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/HDD+Hard+Disk.jpg',
            'name' => 'HDD',
            'brand_name' => '西芝',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => Condition::VeryGood->value,
            'category_ids' => [2],
            'sold' => false,
        ]);

        $unmatchedItem = Item::create([
            'user_id' => $seller->id,
            'image_path' => 'items/iLoveIMG+d.jpg',
            'name' => '玉ねぎ3束',
            'brand_name' => 'なし',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'category_ids' => [10],
            'condition' => Condition::Good->value,
            'sold' => false,
        ]);

        $user->favoriteItems()->attach([$targetItem->id, $unmatchedItem->id]);

        $response = $this->actingAs($user)->get('/?keyword=時計&tab=maylist');

        $response->assertStatus(200);

        $response->assertSee('腕時計');
        $response->assertDontSee('HDD');
        $response->assertDontSee('玉ねぎ3束');
    }
}
