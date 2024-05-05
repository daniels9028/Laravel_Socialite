<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OauthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();

            $newUser = User::updateOrCreate(
                ['email' => empty($user->email) ? '' : $user->email],
                [
                    'name' => $user->name,
                    'gauth_id' => $user->id,
                    'gauth_type' => $provider,
                    'password' => Hash::make('admin@123')
                ]
            );

            Auth::login($newUser);

            return redirect('/dashboard');
        } catch (Exception $e) {
            return redirect()->back();
        }
    }
}
