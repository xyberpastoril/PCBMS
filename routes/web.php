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

Route::post('/2fa-confirm', [\App\Http\Controllers\TwoFactorAuthController::class, 'confirm'])->name('two-factor.confirm');

Route::group([
], function(){
    'middleware' => ['auth']
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
        Route::get('/account/', [\App\Http\Controllers\AccountController::class, 'index'])->name('index');
    
        // AJAX
        Route::post('/ajax/account/show/recoverycodes', [\App\Http\Controllers\AccountController::class, 'showRecoveryCodes']);
        Route::put('/ajax/account/update/username', [\App\Http\Controllers\AccountController::class, 'updateUsername']);
        Route::put('/ajax/account/update/password', [\App\Http\Controllers\AccountController::class, 'updatePassword']);
    });
});