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
        Route::get('/account/', [\App\Http\Controllers\PersonnelModule\AccountController::class, 'index'])->name('index');
        
        // AJAX
        Route::group([
            'as' => 'ajax.',
            'prefix' => 'ajax'
        ], function()
        {
            Route::group([
                'as' => 'show.',
                'prefix' => 'account/show'
            ], function()
            {
                Route::post('/recoverycodes', [\App\Http\Controllers\PersonnelModule\AccountController::class, 'showRecoveryCodes'])->name('recovery-codes');
            });
            
            Route::group([
                'as' => 'update.',
                'prefix' => 'account/update'
            ], function()
            {
                Route::put('/username', [\App\Http\Controllers\PersonnelModule\AccountController::class, 'username']);
                Route::put('/password', [\App\Http\Controllers\PersonnelModule\AccountController::class, 'password']);
            });
        });
    
        // AJAX
    });
});