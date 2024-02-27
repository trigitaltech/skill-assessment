<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_user_can_update_own_profile()
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->withSession(['active' => true])->post(route('profile.update.info', $user), [
            'name' => 'Updated Name',
        ]);
        $response->assertRedirect('profile');
    }

    public function test_user_can_update_own_password()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('profile.update.password', $user), [
            'password' => 'UpdatedPassword',
            'password_confirmation' => 'UpdatedPassword'
        ]);
        $response->assertRedirect('profile');
    }
}
