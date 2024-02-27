<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use App\Models\FavoriteQuote;
use Illuminate\Support\Facades\RateLimiter;

class QuoteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $limit = (int)$request->query('limit', 5)?:5;
        $quotes = [];

        $executed = RateLimiter::attempt(
            'quotes:get',
            $perMinute = 30,
            function () use ($limit, &$quotes, &$rateLimitExceeded) {
                $response = Http::get("https://dummyjson.com/quotes?limit=$limit&skip=".mt_rand(0, 1454-$limit));
                if ($response->successful()) {
                    $quotes = $response->json('quotes');
                }
            }
        );
        return Inertia::render('Dashboard', [
            'quotes' => $quotes,
            'ratelimit' => !$executed
        ]);
    }
}
