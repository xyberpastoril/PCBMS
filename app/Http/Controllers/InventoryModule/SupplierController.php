<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryModule\Supplier\StoreSupplierRequest;
use App\Http\Requests\InventoryModule\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Support\Facades\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('inventory.supplier.index');
    }

    public function showRowsAjax(Request $request)
    {
        $suppliers = Supplier::select(
            'uuid',
            'name',
            'physical_address',
            'email',
            'mobile_number',
        );

        // TODO: Add condition if offset needed

        return $suppliers->paginate(10);
    }

    public function storeAjax(StoreSupplierRequest $request)
    {
        $validated = $request->validated();

        Supplier::create([
            'name' => $validated['name'],
            'physical_address' => $validated['physical_address'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email_address'],
        ]);

        return 'Supplier successfully added.';
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
