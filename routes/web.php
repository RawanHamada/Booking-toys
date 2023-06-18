<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
//Route::get('/', function () {
//    return url('assets/avatar.png');
//});
Route::get('clear',function (){
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('route:cache');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    dd('test');
});


Route::get('migrate',function (){
    \Illuminate\Support\Facades\Artisan::call('migrate');
    dd('test');
});

Route::get('seed',function (){
    \Illuminate\Support\Facades\Artisan::call('db:seed');
    dd('test');
});


Route::get('migrate/rollback',function (){
    \Illuminate\Support\Facades\Artisan::call('migrate:rollback');
    dd('test');
});

Route::get('storage/link',function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    dd('test');
});
