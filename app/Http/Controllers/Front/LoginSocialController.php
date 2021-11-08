<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginSocialController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
//        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();
        dd($user);
        // $user->token;
    }

}
