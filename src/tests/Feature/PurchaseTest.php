<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\PaymentMethodsTableSeeder;
use App\Enums\Condition;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\PaymentMethod;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    use RefreshDatabase;

    public function test_user_can_purchase_items_and_see_sold_label_on_index()
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
            'category_ids' => [1, 5],
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'image_path' => null,
            'user_name' => 'テスト太郎',
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
        ]);

        $paymentMethod = PaymentMethod::create([
            'method' => 'コンビニ払い'
        ]);
        
        $response = $this->actingAs($user)
                         ->post(route('purchase.checkout', $item), [
                            'payment_method' => $paymentMethod->id,
                         ]);

        if ($response->isRedirect()) {
            $response = $this->followRedirects($response);
        }

        $this->assertDatabaseHas('purchased_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
            'payment_method_id' => $paymentMethod->id,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $item->fresh()->sold);

        $this->get(route('items.index'))
             ->assertStatus(200)
             ->assertSeeInOrder([$item->name, 'Sold']);
    }
}
