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
                Route::get('/inventory/pdf/{consignOrder}', [App\Http\Controllers\InventoryModule\InventoryController::class, 'barcodePdf'])->name('barcode-pdf');

                // AJAX
                Route::group([
                    'as' => 'ajax.',
                    'prefix' => 'ajax/inventory'
                ], function()
                {
                    Route::get('/order/{consignOrder}', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'showAjax'])->name('show-ajax');
                    Route::get('/count/orders', function(){ return \App\Models\ConsignOrder::count(); })->name('count-order');

                    Route::get('/consigned-products', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'showInventoryRowsAjax'])->name('show-inventory-rows');

                    Route::get('/receive-products', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'showRowsAjax'])->name('show-rows');
                    Route::post('/receive-products', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'receiveProductsAjax'])->name('receive-products');

                    Route::get('/pay-supplier/{supplier?}', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'showProductsToPayAjax'])->name('show-products-to-pay');
                    Route::post('/pay-supplier/{supplier?}', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'paySupplierAjax'])->name('pay-supplier');

                    Route::get('/return-expired-products/{supplier?}', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'showExpiredProductsToReturnAjax'])->name('show-expired-products-to-return');
                    Route::post('/return-expired-products/{supplier?}', [\App\Http\Controllers\InventoryModule\InventoryController::class, 'returnExpiredProductsAjax'])->name('return-expired-products');
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

            // Units Controller
            Route::group([
                'as' => 'unit.',
            ], function()
            {
                // HTTP
                Route::get('/units', [\App\Http\Controllers\InventoryModule\UnitsController::class, 'index'])->name('index');

                // AJAX
                Route::group([
                    'as' => 'ajax.',
                    'prefix' => 'ajax/units'
                ], function()
                {
                    Route::get('/', [\App\Http\Controllers\InventoryModule\UnitsController::class, 'showRowsAjax'])->name('show-rows');
                    Route::post('/', [\App\Http\Controllers\InventoryModule\UnitsController::class, 'storeAjax'])->name('store');
                    Route::get('/id/{id}', [\App\Http\Controllers\InventoryModule\UnitsController::class, 'editAjaxById'])->name('editId');
                    Route::get('/{unit}', [\App\Http\Controllers\InventoryModule\UnitsController::class, 'editAjax'])->name('edit');
                    Route::put('/{unit}', [\App\Http\Controllers\InventoryModule\UnitsController::class, 'updateAjax'])->name('update');
                    Route::delete('/{unit}', [\App\Http\Controllers\InventoryModule\UnitsController::class, 'destroyAjax'])->name('destroy');
                });
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
            Route::get('/reports', [\App\Http\Controllers\ReportsModule\SalesController::class, 'index'])->name('index');
            Route::get('/reports/sales', [\App\Http\Controllers\ReportsModule\SalesController::class, 'index']);
            Route::get('/reports/sales/pdf', [\App\Http\Controllers\ReportsModule\SalesController::class, 'pdf'])->name('pdf');

            // AJAX
            // TODO: Add reports ajax routes later when necessary.
            Route::group([
                'as' => 'ajax.',
                'prefix' => 'ajax/reports'
            ], function()
            {
                Route::get('/sales', [\App\Http\Controllers\ReportsModule\SalesController::class, 'showRowsAjax'])->name('show-rows');
            });
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
            Route::get('/sales', [\App\Http\Controllers\SalesModule\SalesController::class, 'index'])->name('index');
            Route::get('/sales/create', [\App\Http\Controllers\SalesModule\SalesController::class, 'create'])->name('create');
            Route::get('/sales/pdf/{invoice}', [\App\Http\Controllers\SalesModule\SalesController::class, 'pdf'])->name('pdf');

            // AJAX
            Route::group([
                'as' => 'ajax.',
                'prefix' => 'ajax/sales'
            ], function()
            {
                Route::post('/', [\App\Http\Controllers\SalesModule\SalesController::class, 'storeAjax'])->name('store');
            });
        });
    });

    Route::group([
        
    ], function()
    {
        Route::group([
            'as' => 'ajax.',
            'prefix' => 'ajax/customer'
        ], function()
        {
            Route::post('/', [\App\Http\Controllers\SalesModule\CustomerController::class, 'storeAjax'])->name('store');
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
        Route::get('/units/{query}', [\App\Http\Controllers\InventoryModule\UnitsController::class, 'searchTagifyAjax'])->name('units');
        Route::get('/consigned-products/{query}', [App\Http\Controllers\InventoryModule\ConsignedProductsController::class, 'searchTagifyAjax'])->name('consigned-products');
        Route::get('/customers/{query}', [\App\Http\Controllers\SalesModule\CustomerController::class, 'searchTagifyAjax'])->name('customers');
    });
});