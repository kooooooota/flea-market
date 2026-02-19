<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Enums\Condition;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\CategoriesTableSeeder;

class ListingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    
    public function test_user_can_list_items()
    {
        $user = User::factory()->create();
        $this->seed(CategoriesTableSeeder::class);
        $response = $this->actingAs($user)
                         ->get(route('items.exhibit'))
                         ->assertStatus(200);

        $formData = [
            'user_id' => $user->id,
            'image' => new UploadedFile(
                base_path('tests/Fixtures/test.jpg'),
                'item.jpg',
                'image/jpeg',
                null,
                true
            ),
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'category' => [1, 5],
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ];

        $response = $this->actingAs($user)->post(route('items.sell'), $formData);
              
        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => Condition::LikeNew->value,
            'sold' => false,
        ]);

        $item = Item::latest('id')->first();
        $this->assertNotNull($item->image_path);
        $this->assertStringContainsString('items/', $item->image_path);

        $this->assertCount(2, $item->categories);
        $this->assertTrue($item->categories->contains(1));
        $this->assertTrue($item->categories->contains(5));
    }
}
