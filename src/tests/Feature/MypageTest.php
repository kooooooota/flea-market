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

class MypageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    
    public function test_can_display_user_information()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $paymentMethod = PaymentMethod::create([
            'method' => 'コンビニ払い'
        ]);
        $boughtItem = Item::create([
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
            'item_id' => $boughtItem->id,
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
            'payment_method_id' => $paymentMethod->id,
        ]);
        $sellItem = Item::create([
            'user_id' => $user->id,
            'image_path' => 'items/HDD+Hard+Disk.jpg',
            'name' => 'HDD',
            'brand_name' => '西芝',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => Condition::VeryGood->value,
            'category_ids' => [2],
            'sold' => false,
        ]);
        $profile = Profile::create([
            'user_id' => $user->id,
            'image_path' => 'profiles/test-avatar.jpg',
            'user_name' => 'テスト太郎',
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
        ]);

        $response = $this->actingAs($user)
                         ->get(route('profile.show'));
        $response->assertStatus(200)
                 ->assertSee('storage/' . $profile->image_path)
                 ->assertSee($profile->user_name)
                 ->assertSee('storage/' . $sellItem->image_path)
                 ->assertSee($sellItem->name)
                 ->assertDontSee($boughtItem->name);
        
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200)
                 ->assertSee('storage/' . $profile->image_path)
                 ->assertSee($profile->user_name)
                 ->assertSee('storage/' . $boughtItem->image_path)
                 ->assertSee($boughtItem->name)
                 ->assertSee('Sold')
                 ->assertDontSee($sellItem->name);
        
        $response = $this->get('/mypage?page=sell');
        $response->assertStatus(200)
                 ->assertSee('storage/' . $profile->image_path)
                 ->assertSee($profile->user_name)
                 ->assertSee('storage/' . $sellItem->image_path)
                 ->assertSee($sellItem->name)
                 ->assertDontSee($boughtItem->name);
    }
}
