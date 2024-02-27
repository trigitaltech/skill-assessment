<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use App\Models\FavoriteQuote;

class FavoriteQuoteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_favorite_quotes_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
                         ->withSession(['active' => true])->get(route('favorite-quotes'));

        $response->assertStatus(200);
    }

    public function test_users_can_add_quote_to_favorite_list()
    {
        $user = User::factory()->create();
        $response = Http::get('https://dummyjson.com/quotes?limit=5&skip='.mt_rand(0, 1449));
        $quotes = $response->json('quotes');
        $response = $this->actingAs($user)
                         ->withSession(['active' => true])->post('/favorite-quotes', [
            'user_id' => $user->id,
            'id' => $quotes[0]['id'],
            'quote' => 'test',
            'author' => 'test',
        ]);
        $response->assertStatus(200);
    }

    public function test_users_can_delete_quote_from_favorite_list()
    {
        $user = User::factory()->create();
        $quoteResponse = Http::get('https://dummyjson.com/quotes?limit=1&skip='.mt_rand(0, 1449));
        $quotes = $quoteResponse->json('quotes');

        $favQuote = FavoriteQuote::create([
            'user_id' => $user->id,
            'quote_id' => $quotes[0]['id'],
            'quote' => 'test',
            'author' => 'test',
        ]);
        $response = $this->actingAs($user)
                         ->withSession(['active' => true])->delete('/favorite-quotes', [
            'id' => $favQuote->id,
        ]);
        $response->assertStatus(200);
    }
}
