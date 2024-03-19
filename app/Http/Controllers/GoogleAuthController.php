<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callBack()
    {
        try
        {
            $google_user = Socialite::driver('google')->user();

            $user = User::where('google_id', $google_user->getId())->first();

            if(!$user)
            {
                $new_user = User::create([
                    'name'      => $google_user->getName(),
                    'email'     => $google_user->getEmail(),
                    'google_id' => $google_user->getId()
                ]);

                // $token = JWTAuth::attempt($new_user);

                return  response()->json(['message' => 'User Registered Successfully']);
            }
            else
            {
                // $token = JWTAuth::attempt($user);

                return  response()->json(['message' => 'User Logged in Successfully']);

            }
        }
        catch (Exception $e)
        {
            dd('Incorrect Email or password');
        }
    }
}
