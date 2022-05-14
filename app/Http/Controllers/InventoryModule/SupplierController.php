<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryModule\Supplier\StoreSupplierRequest;
use App\Http\Requests\InventoryModule\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        return view('inventory.supplier.index');
    }

    public function storeAjax(StoreSupplierRequest $request)
    {
        //
    }

    public function editAjax(Supplier $supplier)
    {
        //
    }

    public function updateAjax(UpdateSupplierRequest $request, Supplier $supplier)
    {
        //
    }

    public function destroyAjax(Supplier $supplier)
    {
        //
    }
}
