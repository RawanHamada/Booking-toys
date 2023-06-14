<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CafeController;
use App\Http\Controllers\Api\CodeCheckController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\GamesController;
use App\Http\Controllers\Api\NewPasswordController;
// use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthController::class, 'login'])
                ->middleware('guest:sanctum');

    Route::post('/register', [AuthController::class, 'register']);
    Route::delete('/access-tokens/{token?}', [AuthController::class, 'destroy'])
                 ->middleware('auth:sanctum');

    // Route::post('/profile/show', [AuthController::class, 'userProfile'])
    //             ->middleware('auth:sanctum');

    Route::post('/profile/update', [AuthController::class, 'update'])
                ->middleware('auth:sanctum');

    Route::delete('/delete-account', [AuthController::class, 'deleteAccount'])
                 ->middleware('auth:sanctum');

    Route::post('/logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum');

            Route::post('password/email',  ForgotPasswordController::class);
            Route::post('password/code/check', CodeCheckController::class);
            Route::post('password/reset', ResetPasswordController::class);

    Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [NewPasswordController::class, 'resetPassword']);

});

    Route::get('cafes', [CafeController::class, 'index']);
    Route::get('search/{name}', [CafeController::class, 'search']);

    Route::get('games', [GamesController::class, 'index']);


