<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('HelloWorld');
// });

Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('loginAction');
Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', [App\Http\Controllers\PunchTimeController::class, 'index'])->name('home');
    // Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [App\Http\Controllers\UsersController::class, 'index'])->name('users.index');
        Route::get('/{userId}/edit', [App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit');
        Route::patch('/{userId}/update', [App\Http\Controllers\UsersController::class, 'update'])->name('users.update');
        Route::get('/create', [App\Http\Controllers\UsersController::class, 'create'])->name('users.create');
        Route::post('/store', [App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
    });

    Route::group(['prefix' => 'punch_time'], function() {
        Route::get('/', [App\Http\Controllers\PunchTimeController::class, 'index'])->name('punch_time.index');
        Route::post('/on', [App\Http\Controllers\PunchTimeController::class, 'createOn'])->name('punch_time.on');
        Route::patch('/off', [App\Http\Controllers\PunchTimeController::class, 'createOff'])->name('punch_time.off');
        Route::get('/export', [App\Http\Controllers\PunchTimeController::class, 'export'])->name('punch_time.export');
    });
});
