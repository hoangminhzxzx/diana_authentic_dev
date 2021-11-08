<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Front\AccountClient;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginSocialController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
//        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        $user = Socialite::driver('facebook')->user();
//        dd($user);
        if ($user) {
            $account_client = AccountClient::query()->where('fb_id', '=', $user->getId())->first();
            if (!$account_client) {
                //chưa ghi vào hệ thống Diana Authentic account fb của khách hàng này
                $account_client = new AccountClient();
            }
            $account_client->name = $user->getName();
            $account_client->email = $user->getEmail();
            $account_client->is_fb = 1;
            $account_client->token_fb = $user->token;
            $account_client->fb_id = $user->getId();
            $account_client->save();
            // dd($account_client);
            $request->session()->put([
                'client_login' => [
                    'username' => $account_client->username,
                    'email' => $account_client->email,
                    'id' => $account_client->id,
                    'is_login' => true
                ]
            ]);
            return redirect()->route('homeFront');
        } else {
            dd('chưa đăng nhập fb đc !!!');
        }
        // $user->token;
    }

}
