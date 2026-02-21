<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Condition;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\PaymentMethod;
use App\Models\PurchasedItem;
use App\Models\Category;
use Database\Seeders\CategoriesTableSeeder;

class PurchaseTest extends TestCase
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

    public function test_user_can_purchase_items_and_see_sold_label_on_index()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $paymentMethod = PaymentMethod::firstOrCreate(['method' => 'コンビニ払い']);
        $paymentMethodId = $paymentMethod->id;

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

        Profile::create([
            'user_id' => $user->id,
            'image_path' => null,
            'user_name' => 'テスト太郎',
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
        ]);

        $response = $this->actingAs($user)
                         ->post(route('purchase.checkout', $item), [
                            'payment_method' => $paymentMethodId,
                         ]);

        if ($response->isRedirect()) {
            $response = $this->followRedirects($response);
        }

        $this->assertDatabaseHas('purchased_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
            'payment_method_id' => $paymentMethodId,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $item->fresh()->sold);

        $this->get(route('items.index'))
             ->assertStatus(200)
             ->assertSeeInOrder([$item->name, 'Sold']);
    }

    public function test_purchased_item_is_displayed_in_mypage()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $paymentMethod = PaymentMethod::firstOrCreate(['method' => 'コンビニ払い']);
        $paymentMethodId = $paymentMethod->id;

        $item = Item::create([
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
        $item->categories()->attach($categoriesIds);

        PurchasedItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
            'payment_method_id' => $paymentMethodId,
        ]);

        $otherUser = User::factory()->create();
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

        PurchasedItem::create([
            'user_id' => $otherUser->id,
            'item_id' => $otherItem->id,
            'zip_code' => '222-2222',
            'address' => '渋谷区渋谷',
            'payment_method_id' => $paymentMethodId,
        ]);

        $response = $this->actingAs($user)
                         ->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertDontSee($otherItem->name);
    }
}
