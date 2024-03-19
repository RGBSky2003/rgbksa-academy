<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credintials = $request->all();

        $validatedData = Validator::make($credintials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validatedData->fails())
        {

            $erros = $validatedData->errors()->all();

            $errorMeassges = [];

            foreach ($erros as $error) 
            {
                $errorMeassges[] = $error;
            }

            return response()->json(['messages' => $validatedData->errors()]);
        }

        $token = JWTAuth::attempt($validatedData->validated());

        if (!$token) 
        {
            return response()->json(['message' => 'Email or password is incorrect']);
        }

        return $this->createNewToken($token);
    }


    public function register(Request $request)
    {
        $userInfo = $request->all();

        $validatedData = Validator::make($userInfo, [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ], [
            'first_name.required' => 'The first name field is required',
            'first_name.string' => 'The first name cannot be a number',
            'first_name.size' => 'The first name must be between 2 and 100 characters',
            'last_name.required' => 'The last name field is required',
            'last_name.string' => 'The last name cannot be a number',
            'last_name.size' => 'The last name must be between 2 and 100 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email',
            'email.unique' => 'This email is already registered',
            'password.required' => 'The password field is required',
            'password.string' => 'The password cannot consist of numbers only',
            'password.confirmed' => 'The password is not correctly confirmed',
            'password.size' => 'The password must be at least 6 characters'
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

        $user = User::create(array_merge($userInfo, ['password' => bcrypt($userInfo['password'])]));

        return response()->json(['message' => 'User successfully registered']);
    }


    public function logout()
    {
        JWTAuth::parseToken()->authenticate();

        JWTAuth::invalidate();
        
        return response()->json(['message' => 'User successfully signed out']);
    }


    public function refresh()
    {
        JWTAuth::parseToken()->authenticate();
        
        return $this->createNewToken(JWTAuth::refresh());
    }


    public function userProfile($user_id)
    {
        // Log::info('user inside usr fun'.JWTAuth::user());

        // $token = JWTAuth::parseToken();

        // return $token;

        // return JWTAuth::user();

        try
        {
            $user = User::findOrFail($user_id);
        }
        catch (Exception $e)
        {
            return response()->json(['message' => 'user not found']);
        }

        return response()->json($user);
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