<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordResets;
use App\Mail\ResetPasswordMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::guard('client')->attempt($credentials)) {
                $token = Auth::guard('client')->attempt($credentials);
                return $this->createNewToken($token);
            } else {
                return response()->json(['message' => 'Email or password is incorrect']);
            }
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function register(Request $request)
    {
        $userInfo = $request->all();

        $validatedData = Validator::make($userInfo, [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validatedData->fails())
        {
            $erros = $validatedData->errors()->all();

            $errorMeassges = [];

            foreach ($erros as $error)
            {
                $errorMeassges[] = $error;
            }

            return response()->json(['messages' => $errorMeassges]);
        }
        $user = User::create(array_merge($userInfo, ['password' =>  Hash::make($userInfo['password'])]));

        return response()->json(['message' => 'User successfully registered']);
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'User successfully signed out']);
        } catch (JWTException $e) {
            // Handle JWT exceptions
            return response()->json(['message' => 'Failed to logout'], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function userProfile()
    {

        try
        {
            $user = auth()->user();
        }
        catch (Exception $e)
        {
            return response()->json(['message' => 'user not found']);
        }

        return response()->json($user);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // $token = Str::random(7);
        $token = mt_rand(1000000, 9999999);


      $previousToken =  PasswordResets::where('email',$request->email);

     if($previousToken)
        {
         $previousToken->delete();
        }

        PasswordResets::Create([
            'email' => $request->email,
            'token' => $token]
        );


        Mail::to($request->email)->send(new ResetPasswordMail($token));

        return response()->json(['message' => 'Reset link sent to your email.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $currentToken =  PasswordResets::where('email',$request->email)->get()->first();



          if($currentToken->token ==$request->token)
          {

                $user = User::where('email',$currentToken->email)->first();


                if($user)
                {
                    $user->update(['password'=> Hash::make($request->password)]);
                    return response()->json(['message'=>'success']);
                }

          }

          return response()->json(['message'=>'failed']);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL(). " Minutes",
            'user' => JWTAuth::user()
        ]);
    }
}
