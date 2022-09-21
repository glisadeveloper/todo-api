<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\Auth\UserAuthController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * Users' routes
 */
Route::group(
    [
        'prefix' => '/user'
    ],
    function () {

        // endpoint that retrieves all user's data
        Route::get('/', [UserAuthController::class,'index'])->middleware(['auth:api']);

        // endpoint that retrieves user's data
        Route::get('/me', [UserAuthController::class, 'me'])->middleware(['auth:api']);

        // endpoint that logs out user
        Route::post('/logout', [UserAuthController::class, 'logOut'])->middleware(['auth:api']);

        // endpoint for registering a user
        Route::post('/register', [UserAuthController::class, 'register']);

        // endpoint for logging in a user
        Route::post('/login', [UserAuthController::class,"login"]);
       
        //Delete user
        Route::delete('/delete/{id}', [UserAuthController::class, 'delete'])->middleware(['auth:api']);
    }
);


/**
 * TODOs' routes
 */

Route::group(["prefix"=>"todo"],function(){
	Route::get("/",[TodoController::class,"getAll"])->middleware(['auth:api']);
    Route::get("/{id}",[TodoController::class,"getSingle"])->middleware(['auth:api']);   
    Route::post("/store",[TodoController::class,"store"])->middleware(['auth:api']);
    Route::put("/update/{id}",[TodoController::class,"update"])->middleware(['auth:api']);
    Route::delete("/delete/{id}",[TodoController::class,"delete"])->middleware(['auth:api']);
});
