<?php

# Including API Routes

// $directory = base_path('routes\\API');

// $files = glob($directory . '\\*.php');

// foreach ($files as $file) {
//     require_once $file;
// }


use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\ServiceRequestController;

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


});


## User Routes ##

  Route::middleware(['auth:client'])->group(function () {

        Route::get('/test-auth', function (Request $request){
            return "success";
        });
   Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'user'], function () {

        Route::get('/profile', [AuthController::class, 'userProfile']);

        Route::post('/edit', [UserController::class, 'edit']);

        Route::post('/delete-account', [UserController::class, 'deleteAccount']);


        Route::post('/delete-myphoto/{user_id}',[UserController::class, 'deletePhoto']);

        Route::post('/change-password', [UserController::class, 'changePassword']);
    });
 ## Service Request Routes ##
    Route::group(['prefix' => 'request'], function () {

        Route::post('/new/{user_id}', [ServiceRequestController::class, 'store']);

        Route::get('/all', [ServiceRequestController::class, 'getAll']);

        Route::get('/{id}', [ServiceRequestController::class, 'get']);

        Route::post('/update/{id}', [ServiceRequestController::class, 'update']);

        Route::post('/delete/{id}', [ServiceRequestController::class, 'delete']);

    });

    });

## Services ##

Route::group(['prefix' => 'services'], function () {

    Route::get('all', [ServiceController::class , 'getAll']);
});

Route::post('contact-us', [ContactUsController::class , 'index']);

## Auth with Social Accounts ##

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->middleware('web');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callBack'])->middleware('web');
