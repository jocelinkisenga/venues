<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialController extends Controller
{
    // Redirect to provider
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Handle callback
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Unable to login using ' . $provider);
        }

        // Check if user exists
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Create a new user
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(str()->random(16)), // random password
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended('/dashboard'); // or wherever
    }
}