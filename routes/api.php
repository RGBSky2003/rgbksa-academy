<?php

# Including API Routes

// $directory = base_path('routes\\API');

// $files = glob($directory . '\\*.php');

// foreach ($files as $file) {
//     require_once $file;
// }


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ServiceRequestController;
use App\Models\ServiceRequest;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });




## Auth Routes ##

Route::group(['prefix' => 'auth'], function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout'])/* ->middleware('jwt.verify') */;
    
    Route::post('/refresh',[AuthController::class, 'refresh'])/* ->middleware('jwt.verify') */;
});


## User Routes ##

Route::group(['prefix' => 'user'/* , 'middleware' => 'jwt.verify' */], function () {

    Route::get('/profile/{id}', [AuthController::class, 'userProfile']);

    Route::post('/edit/{id}', [UserController::class, 'edit']);
    
     Route::post('/delete-myphoto/{user_id}',[UserController::class, 'deletePhoto']);

    Route::post('/password/change', [UserController::class, 'changePassword']);
});


## Service Request Routes ##

Route::group(['prefix' => 'request'/* , 'middleware' => 'jwt.verify' */], function () {

    Route::post('/new/{user_id}', [ServiceRequestController::class, 'store']);
    
    Route::get('/all', [ServiceRequestController::class, 'getAll']);

    Route::get('/{id}', [ServiceRequestController::class, 'get']);

    Route::post('/update/{id}', [ServiceRequestController::class, 'update']);

    Route::post('/delete/{id}', [ServiceRequestController::class, 'delete']);

});


## Services ##

Route::group(['prefix' => 'services'/* , 'middleware' => 'jwt.verify' */], function () {

    Route::get('all', [ServiceController::class , 'getAll']);
});


## Auth with Social Accounts ##

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->middleware('web');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callBack'])->middleware('web');