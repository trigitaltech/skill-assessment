<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Inertia::render('Auth/Profile');
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'name' => ['required'],
        ]);
        $user = $request->user();
        $user->name = $request->input('name');
        $user->save();
        return redirect('profile')->with('success', 'Name updated');
    }

    public function password_update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = $request->user();
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return redirect('profile')->with('success', 'Password updated');
    }
}
