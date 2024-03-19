<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function edit($user_id, Request $request)
    {
        try
        {
            $user = User::findOrFail($user_id);
        } 
        catch (Exception $e)
        {
            return response()->json(['message' => 'user not found']);
        }

        $validatedData = Validator::make($request->only(['first_name', 'last_name', 'email']), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|email|unique:users,email,' . $user_id,
            ],
            [
                'first_name.required' => 'The first name field is required',
                'first_name.string' => 'The first name cannot be a number',
                'first_name.size' => 'The first name must be between 2 and 100 characters',
                'last_name.required' => 'The last name field is required',
                'last_name.string' => 'The last name cannot be a number',
                'last_name.size' => 'The last name must be between 2 and 100 characters',
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a valid email',
                'email.unique' => 'This email is already registered',
            ]
        );


        if ($validatedData->fails())
        {
            $errors = $validatedData->errors()->all();

            $errorMeassges = [];

            foreach ($errors as $error)
            {
                $errorMeassges[] = $error;
            }

            return response()->json(['messages' => $errorMeassges]);
        }

        if ($request->hasFile('profile_picture'))
        {
            $validate_profile_picture = Validator::make([$request->profile_picture], [
                'profile_picture' => 'image|max:2097152'
            ], [
                'profile_picture.image' => 'The profile picture must be an image',
                'profile_picture.size'  => 'The profile picture must not exceed 2MB'       
            ]);


            if ($validate_profile_picture->fails()) {
                $errors = $validate_profile_picture->errors()->all();

                $errorMeassges = [];

                foreach ($errors as $error) {
                    $errorMeassges[] = $error;
                }

                return response()->json(['messages' => $errorMeassges]);
            }

            if ($user->profile_picture)
            {
                File::delete(storage_path('/app/public/profile_pictures/' . $user->profile_picture));
            }

            $file_name = time() . '_' . uniqid() . '_' . $user_id . '.' . $request->file('profile_picture')->extension();

            Storage::putFileAs('public/profile_pictures', $request->file('profile_picture'), $file_name);

            $user->profile_picture = $file_name;

            $user->update();
        }

        $update = $request->only(['date_of_birth', 'first_name', 'last_name', 'email', 'country']);

        $user->update($update);

        return response()->json(['message' => 'User info updated successfully']);
    }

    public function changePassword(Request $request)
    {
        
    }
    public function deletePhoto(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        try {
            if (Storage::exists('public/profile_pictures/' . $user->profile_picture)) {

                Storage::delete('public/profile_pictures/' . $user->profile_picture);

                $user->profile_picture="me.png";
                $user->update();
                return response()->json(['message' => 'Success']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete or upload the profile picture'], 404);
        }
        
    }
}