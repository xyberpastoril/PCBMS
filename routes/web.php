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
                Route::put('/username', [\App\Http\Controllers\PersonnelModule\AccountController::class, 'username'])->name('username');
                Route::put('/password', [\App\Http\Controllers\PersonnelModule\AccountController::class, 'password'])->name('password');
            });
        });
    
    });

    /**
     * Accessible only to MANAGER
     */
    Route::group([
        'middleware' => ['manager'],
    ], function() 
    {
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
                    'prefix' => 'ajax/suppliers'
                ], function()
                {
                    Route::get('/', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'showRowsAjax'])->name('show-rows');
                    Route::post('/', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'storeAjax'])->name('store');
                    Route::get('/{supplier}', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'editAjax'])->name('edit');
                    Route::put('/{supplier}', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'updateAjax'])->name('update');
                    Route::delete('/{supplier}', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'destroyAjax'])->name('destroy');
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
                    'prefix' => 'ajax/products'
                ], function()
                {
                    Route::get('/', [\App\Http\Controllers\InventoryModule\ProductController::class, 'showRowsAjax'])->name('show-rows');
                    Route::post('/', [\App\Http\Controllers\InventoryModule\ProductController::class, 'storeAjax'])->name('store');
                    Route::get('/{product}', [\App\Http\Controllers\InventoryModule\ProductController::class, 'editAjax'])->name('edit');
                    Route::put('/{product}', [\App\Http\Controllers\InventoryModule\ProductController::class, 'updateAjax'])->name('update');
                    Route::delete('/{product}', [\App\Http\Controllers\InventoryModule\ProductController::class, 'destroyAjax'])->name('destroy');
                });
            });

            // Inventory Controller
            Route::group([
                'as' => 'inventory.',
            ], function()
            {
                // HTTP
                Route::get('/inventory', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'index'])->name('index');

                // AJAX
                Route::group([
                    'as' => 'ajax.',
                    'prefix' => 'ajax/inventory'
                ], function()
                {
                    Route::get('/receive-products', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'showRowsAjax'])->name('show-rows');
                    Route::post('/receive-products', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'receiveProductsAjax'])->name('receive-products');
                });
            });

            // Consign Orders Controller
            Route::group([
                'as' => 'consign-order.',
            ], function()
            {
                // HTTP
                Route::get('/orders', [\App\Http\Controllers\InventoryModule\ConsignOrderController::class, 'index'])->name('index');
            });
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
                'prefix' => 'ajax/personnel'
            ], function()
            {
                Route::get('/', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'showRowsAjax'])->name('show-rows');
                Route::post('/', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'storeAjax'])->name('store');
                Route::get('/{user}', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'editAjax'])->name('edit');
                Route::put('/{user}', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'updateAjax'])->name('update');
                Route::delete('/{user}', [\App\Http\Controllers\PersonnelModule\PersonnelController::class, 'destroyAjax'])->name('destroy');
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

    /**
     * Accessible only to CASHIER
     */
    Route::group([
        'middleware' => ['cashier']
    ], function()
    {
        /**
         * Sales Module [3]
         */
        Route::group([
            'as'=> 'sales.'
        ], function() 
        {
            // TODO: Add sales routes later when necessary.
        });
    });

    /**
     * AJAX Search
     */
    Route::group([
        'as' => 'search.',
        'prefix' => 'ajax/search',
    ], function()
    {
        // AJAX
        Route::get('/suppliers/{query}', [\App\Http\Controllers\InventoryModule\SupplierController::class, 'searchTagifyAjax'])->name('suppliers');
        Route::get('/products/{query}', [\App\Http\Controllers\InventoryModule\ProductController::class, 'searchTagifyAjax'])->name('products');
    });
});