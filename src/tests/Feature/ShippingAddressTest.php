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

class ShippingAddressTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    use RefreshDatabase;

    public function test_the_changed_address_is_reflected()
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
        
        $formData = [
            'zip_code' => '222-2222',
            'address' => '渋谷区渋谷',
            'building' => '渋谷マンション101',
            ];

        $response = $this->actingAs($user)
                ->from(route('items.to_address_form', $item))
                ->post(route('items.change_address', $item), $formData);
            
        $response->assertRedirect(route('items.checkout', $item));

        $finalResponse = $this->get(route('items.checkout', $item));

        $finalResponse->assertStatus(200)
                      ->assertSee($formData['zip_code'])
                      ->assertSee($formData['address'])
                      ->assertSee($formData['building']);

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
        
        $response->assertStatus(200);

        $this->assertDatabaseHas('purchased_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'zip_code' => $formData['zip_code'],
            'address' => $formData['address'],
            'building' => $formData['building'],
            'payment_method_id' => $paymentMethod->id,
        ]);
    }
}
