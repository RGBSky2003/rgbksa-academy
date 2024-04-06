<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    public function edit( Request $request ) {

            $user = auth()->user();


        $validator = Validator::make( $request->only( [ 'first_name', 'last_name', 'email', 'date_of_birth', 'country' ] ), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'date_of_birth' => 'nullable|date',
            'country' => 'nullable|string|max:100',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'messages' => $validator->errors()->all() ] );
        }


        $updateData = $request->only( [ 'date_of_birth', 'first_name', 'last_name', 'email', 'country' ] );

        $user->update( $updateData );

        return response()->json( [ 'message' => 'User info updated successfully', 'user'=> $user ] );
    }

    public function changePassword( Request $request ) {
        $request->validate( [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ] );

        $user = auth()->user();

        if ( !Hash::check( $request->current_password, $user->password ) ) {
            return response()->json( [ 'message' => 'Current password is incorrect' ], 422 );
        }

        $user->password = Hash::make( $request->new_password );
        $user->update();

        return response()->json( [ 'message' => 'Password changed successfully' ] );
    }


    public function editPhoto(Request $request)
    {
        // Validate the request
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Store the new photo with a custom name
        $photoName = 'profile_' . time() . '.' . $request->photo->getClientOriginalExtension();
        $photoPath = $request->file('photo')->storeAs('profile_photos', $photoName, 'public');

        // Get the full path of the stored photo
        $fullPath = asset('storage/' . $photoPath);

        // Update user's photo and full path in the database
        $user->profile_picture = $photoPath;
        $user->profile_picture = $fullPath;
        $user->update();

        return response()->json(
            ['message'=>'success',
             'Photo'=>$user->profile_picture]);
    }

    public function deleteAccount(Request $request)
    {
        $user=auth()->user();

        if ( !Hash::check( $request->password, $user->password ) ) {
            return response()->json( [ 'message' => 'password is incorrect' ], 422 );
        }

        $user->delete();

        return response()->json( [ 'message' => 'user delted successfully' ], 200 );

    }

    public function destoyUserImg(Request $request)
    {

    $user = Auth::user();


    $urlParts = explode('/', $user->profile_picture);
    $filename = end($urlParts);


    Storage::delete('public/profile_photos/' . $filename);

    $user->profile_picture = null;
    $user->update();

    return response()->json(['message' => 'Profile picture deleted successfully.']);

    }
}
