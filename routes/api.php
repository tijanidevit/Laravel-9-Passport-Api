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

Route::post('register', [App\Http\Controllers\AuthController::class, 'register'])->name('artist_register');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('artist_login');
Route::get('check/login', function(){
    return response([
        'status' => 0,
        'message' => 'User not logged in',
    ], 403);
})->name('check_login');
Route::middleware('auth:api')->post('/email/verify', [App\Http\Controllers\AuthController::class, 'verify_code'])->name('check_login');
Route::middleware('auth:api')->post('/email/code/request', [App\Http\Controllers\AuthController::class, 'request_new_code'])->name('check_login');



Route::middleware(['auth:api', App\Http\Middleware\UserEmailVerification::class,])->prefix('artists/')->group(function () {
    Route::post('projects/', [App\Http\Controllers\UserProjectController::class, 'store'])->name('artist_add_project');

    Route::get('projects/', [App\Http\Controllers\UserProjectController::class, 'index_for_artist'])->name('artist_view_projects');

    Route::get('projects/{userProject}', [App\Http\Controllers\UserProjectController::class, 'show_for_artist'])->name('artist_view_project');

    Route::put('projects/{userProject}', [App\Http\Controllers\UserProjectController::class, 'update'])->name('artist_update_project');

    Route::delete('projects/{userProject}', [App\Http\Controllers\UserProjectController::class, 'destroy'])->name('artist_delete_project');


    Route::post('projects/{userProject}/links', [App\Http\Controllers\ProjectLinkController::class, 'store'])->name('artist_add_project_life');

    Route::put('projects/{userProject}/links/{projectLink}', [App\Http\Controllers\ProjectLinkController::class, 'update'])->name('artist_update_project_link');

    Route::delete('projects/{userProject}/links/{projectLink}', [App\Http\Controllers\ProjectLinkController::class, 'destroy'])->name('artist_delete_project_link');
});




Route::get('{userSlug}', [App\Http\Controllers\UserController::class, 'show']);
