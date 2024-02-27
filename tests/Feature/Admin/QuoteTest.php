<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use App\Models\FavoriteQuote;

class QuoteTest extends TestCase
{
    
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_favorite_quote_list_screen_for_a_user_can_be_rendered_by_admin()
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)
                         ->withSession(['active' => true])->get(route('admin.users.quotes', ['user' => $user]));

        $response->assertStatus(200);
    }

    public function test_admin_can_remove_favorite_quote_of_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $quoteResponse = Http::get('https://dummyjson.com/quotes?limit=1&skip='.mt_rand(0, 1449));
        $quotes = $quoteResponse->json('quotes');

        $favQuote = FavoriteQuote::create([
            'user_id' => $user->id,
            'quote_id' => $quotes[0]['id'],
            'quote' => 'test',
            'author' => 'test',
        ]);

        $response = $this->actingAs($admin)
                         ->withSession(['active' => true])->delete(route('admin.users.quotes.delete'), [
            'user_id' => $user->id,
            'quote_id' => $quotes[0]['id'] 
        ]);
        $response->assertStatus(200);
    }
}
