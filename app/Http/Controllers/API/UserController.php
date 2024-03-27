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
        try {
            $user = auth()->user();
            // Fetch the authenticated user
        } catch ( Exception $e ) {
            return response()->json( [ 'message' => 'User not found' ] );
        }

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

        if ( $request->hasFile( 'profile_picture' ) ) {
            $validate_profile_picture = Validator::make( $request->all(), [
                'profile_picture' => 'image|max:2048', // Adjusted maximum file size
            ], [
                'profile_picture.image' => 'The profile picture must be an image',
                'profile_picture.max' => 'The profile picture must not exceed 2MB',
            ] );

            if ( $validate_profile_picture->fails() ) {
                return response()->json( [ 'messages' => $validate_profile_picture->errors()->all() ] );
            }

            if ( $user->profile_picture ) {
                Storage::delete( 'public/profile_pictures/' . $user->profile_picture );
            }

            $file_name = time() . '_' . uniqid() . '_' . $user->id . '.' . $request->file( 'profile_picture' )->extension();

            $request->file( 'profile_picture' )->storeAs( 'public/profile_pictures', $file_name );

            $user->profile_picture = $file_name;
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

    public function deletePhoto( Request $request, $user_id ) {
        $user = User::findOrFail( $user_id );
        try {
            if ( Storage::exists( 'public/profile_pictures/' . $user->profile_picture ) ) {

                Storage::delete( 'public/profile_pictures/' . $user->profile_picture );

                $user->profile_picture = 'me.png';
                $user->update();
                return response()->json( [ 'message' => 'Success' ] );
            }
        } catch ( Exception $e ) {
            return response()->json( [ 'message' => 'Failed to delete or upload the profile picture' ], 404 );
        }

    }

    public function deleteAccount()
    {
        $user=auth()->user();

        $user->delete();

        return response()->json( [ 'message' => 'user delted successfully' ], 200 );

    }
}
