<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class QuoteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_quotes_screen_can_be_rendered()
    {
        $user = User::factory()->create();
 
        $response = $this->actingAs($user)
                         ->withSession(['active' => true])->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_guest_users_can_not_render_quote_screen()
    {
        $response = $this->get('/quotes');
        $response->assertStatus(302);
    }
}
