<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    
    public function test_the_initial_value_is_set()
    {
        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'image_path' => 'profiles/test-avatar.jpg',
            'user_name' => 'テスト太郎',
            'zip_code' => '111-1111',
            'address' => '渋谷区千駄ヶ谷',
            'building' => '千駄ヶ谷マンション101'
        ]);

        $response = $this->actingAs($user)
                         ->get(route('profile.edit'));
        $response->assertStatus(200)
                 ->assertSee('storage/' . $profile->image_path)
                 ->assertSee('value="' . $profile->user_name . '"', false)
                 ->assertSee('value="' . $profile->zip_code . '"', false)
                 ->assertSee('value="' . $profile->address . '"', false)
                 ->assertSee('value="' . $profile->building . '"', false);
    }
}
