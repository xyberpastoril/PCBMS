<?php

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

// Authentication Routes
Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes...
Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Password Reset Routes...
// Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Route::group([/*'middleware' => 'auth'*/], function() {
    // Route::view('/', '')->name('home');
    // Route::resource('/source', \App\Http\Controllers\SourceController::class)->except('create', 'edit');
    // Route::view('/settings', 'settings.index')->name('settings');
// });


Route::group([
    'middleware' => ['auth']
], function(){
    /**
     * Homepage
     */
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    /**
     * Account Settings
     */
    Route::group([
        'as' => 'account.'
    ], function(){
        // HTTP
        Route::get('/account/', [\App\Http\Controllers\AccountController::class, 'index'])->name('account');
    
        // AJAX
        Route::put('/ajax/account/update/username', [\App\Http\Controllers\AccountController::class, 'updateUsername']);
        Route::put('/ajax/account/update/password', [\App\Http\Controllers\AccountController::class, 'updatePassword']);
    });
});