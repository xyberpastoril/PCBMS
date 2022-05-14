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
    'middleware' => ['auth']
], function()
{
    /**
     * Homepage
     */
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    /**
     * Account Settings
     */
    Route::group([
        'as' => 'account.'
    ], function()
    {
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
    
    });

    /**
     * Inventory Module [1 & 2]
     */
    Route::group([
        'as' => 'inventory.'
    ], function() 
    {
        // Suppliers Controller
        Route::group([
            'as' => 'supplier.'
        ], function() {
            // HTTP
            Route::get('/suppliers', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'index'])->name('index');

            // AJAX
            Route::group([
                'as' => 'ajax.',
                'prefix' => 'ajax'
            ], function()
            {
                Route::get('/suppliers/{supplier}', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'editAjax'])->name('edit');
                Route::put('/suppliers/{supplier}', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'updateAjax'])->name('update');
                Route::delete('/suppliers/{supplier}', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'destroyAjax'])->name('destroy');
            });
        });

        // Products Controller
        Route::group([
            'as' => 'product.'
        ], function() 
        {
            // HTTP
            Route::get('/products', [\App\Http\Controllers\InventoryModule\ProductController::class, 'index'])->name('index');
            
            // AJAX
            Route::group([
                'as' => 'ajax.',
                'prefix' => 'ajax'
            ], function()
            {
                Route::get('/products/{product}', [\App\Http\Controllers\InventoryModule\ProductController::class, 'editAjax'])->name('edit');
                Route::put('/products/{product}', [\App\Http\Controllers\InventoryModule\ProductController::class, 'updateAjax'])->name('update');
                Route::delete('/products/{product}', [\App\Http\Controllers\InventoryModule\ProductController::class, 'destroyAjax'])->name('destroy');
            });
        });
    });

    /**
     * Sales Module [3]
     */
    Route::group([
        'as'=> 'sales.'
    ], function() 
    {
        // TODO: Add sales routes later when necessary.
    });

    /**
     * Personnel Module [4]
     */
    Route::group([
        'as' => 'personnel.'
    ], function() 
    {
        // HTTP
        Route::get('/personnel', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'index'])->name('index');

        // AJAX
        Route::group([
            'as' => 'ajax.',
            'prefix' => 'ajax'
        ], function()
        {
            Route::get('/personnel/{user}', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'editAjax'])->name('edit');
            Route::put('/personnel/{user}', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'updateAjax'])->name('update');
            Route::delete('/personnel/{user}', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'destroyAjax'])->name('destroy');
        });
    });

    /**
     * Reports Module [5]
     */
    Route::group([
        'as' => 'reports.'
    ], function() 
    {
        // HTTP
        Route::get('/reports', [\App\Http\Controllers\ReportsModule\ReportsController::class, 'index'])->name('index');

        // AJAX
        // TODO: Add reports ajax routes later when necessary.
    });
});