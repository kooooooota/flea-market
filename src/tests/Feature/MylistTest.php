<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Condition;
use App\Models\Item;
use App\Models\User;

class MylistTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
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
            'category_ids' => [1, 5],
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $notLikedItem = Item::create([
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
            'category_ids' => [1, 5],
            'condition' => Condition::LikeNew->value,
            'sold' => true,
        ]);

        $onSaleItem = Item::create([
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
            'category_ids' => [1, 5],
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertDontSee('腕時計');
    }
}
