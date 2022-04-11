<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [App\Http\Controllers\AuthController::class, 'register' ])->name('post_register');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login' ])->name('post_login');
Route::get('check/login', function(){
    return response([
        'status' => 0,
        'message' => 'User not logged in',
    ], 403);
})->name('check_login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api', App\Http\Middleware\UserEmailVerification::class,])->group(function () {
    Route::post('projects/', [App\Http\Controllers\UserProjectController::class, 'store' ]);
});




Route::get('{userSlug}', [App\Http\Controllers\UserController::class, 'show' ]);
