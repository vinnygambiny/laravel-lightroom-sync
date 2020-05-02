<?php

namespace VinnyGambiny\LightroomSync\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    public function index(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ])) {
            $user = Auth::user();

            if (!$user->api_token) {
                $user->api_token = Str::random(60);
                $user->save();
            }

            return [
                'token' => $user->api_token,
            ];
        }

        return [
            'error' => 'Email or password incorrect',
        ];
    }
}
