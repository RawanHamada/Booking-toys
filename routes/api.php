<?php

use App\Http\Controllers\Api\AuthController;
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
    Route::delete('/access-tokens/{token?}', [AccessTokensController::class, 'destroy'])
                 ->middleware('auth:sanctum');

    Route::post('/profile/update', [AuthController::class, 'update'])
                ->middleware('auth:sanctum');

    Route::delete('/delete-account', [AccessTokensController::class, 'deleteAccount'])
                 ->middleware('auth:sanctum');

    Route::post('/logout', [AccessTokensController::class, 'logout'])
            ->middleware('auth:sanctum');



});
