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

class DisplayTest extends TestCase
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
    
    public function test_can_display_item_list()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'user_id' => $user->id,
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

        $response = $this->get('/');

        $response->assertStatus(200)->assertSee('腕時計');
    }

    public function test_display_sold_label_when_item_is_sold()
    {
        $user = User::factory()->create();

        $soldItem = Item::create([
            'user_id' => $user->id,
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

        $onsaleItem = Item::create([
            'user_id' => $user->id,
            'image_path' => 'items/HDD+Hard+Disk.jpg',
            'name' => 'HDD',
            'brand_name' => '西芝',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => Condition::VeryGood->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::where('name', '家電')->value('id');
        $onsaleItem->categories()->attach($categoriesIds);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('腕時計');
        $response->assertSee('Sold');

        $response->assertSee('HDD');
    }

    public function test_own_items_are_not_displayed_in_list()
    {
        $me = User::factory()->create();

        $otherUser = User::factory()->create();

        $ownItem = Item::create([
            'user_id' => $me->id,
            'image_path' => 'items/Armani+Mens+Clock.jpg',
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $categoriesIds = Category::whereIn('name', ['ファッション', 'メンズ'])->pluck('id')->toArray();
        $ownItem->categories()->attach($categoriesIds);

        $otherItem = Item::create([
            'user_id' => $otherUser->id,
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

        $response = $this->actingAs($me)->get('/');

        $response->assertStatus(200);

        $response->assertSee('HDD');

        $response->assertDontSee('腕時計');
    }
}
