<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Condition;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

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

        Profile::create([
            'user_id' => $user->id,
            'image_path' => null,
            'user_name' => 'テスト太郎',
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
        ]);

        $this->actingAs($user)
             ->get(route('items.to_address_form', $item))
             ->assertStatus(200);

        $formData = [
            'zip_code' => '222-2222',
            'address' => '渋谷区渋谷',
            'building' => '渋谷マンション101',
        ];

        $response = $this->post(route('items.change_address', $formData));

        $response->assertRedirect('items.checkout', $item);

        $response->assertSee($formData->zip_code)
                 ->assertSee($formData->address)
                 ->assertSee($formData->building);
    }
}
