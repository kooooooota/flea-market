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

class PaymentMethodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    
    public function test_payment_method_is_reflected()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $paymentMethod = PaymentMethod::create([
            'method' => 'コンビニ払い'
        ]);
        $item = $item = Item::create([
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

        PurchasedItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method_id' => $paymentMethod->id,
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷'
        ]);

        $response = $this->actingAs($user)
                         ->withSession(['selected_payment_method_id' => $paymentMethod->id])
                         ->get(route('items.checkout', $item));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            'checkout-form__confirmation-title',
            $paymentMethod->method]);
    }
}
