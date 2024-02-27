<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use App\Models\FavoriteQuote;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_list_screen_can_be_rendered_by_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)
                         ->withSession(['active' => true])->get(route('admin.users'));

        $response->assertStatus(200);
    }

    public function test_user_list_screen_can_not_be_rendered_by_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
                         ->withSession(['active' => true])->get(route('admin.users'));

        $response->assertRedirect(route('dashboard'));
    }



    public function test_admin_can_ban_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $response = $this->actingAs($admin)
                         ->withSession(['active' => true])->post(route('admin.users.ban'), [
            'user_id' => $user->id,
            'active' => false 
        ]);

        $response->assertStatus(200)->assertJson(['status' => 'success']);
    }

    public function test_admin_can_not_ban_himself()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)
                         ->withSession(['active' => false])->post(route('admin.users.ban'), [
            'user_id' => $admin->id,
            'active' => false 
        ]);
        $response->assertStatus(200)->assertJson(['status' => 'failed']);
    }
}
