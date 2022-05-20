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

    public function searchTagifyAjax($query)
    {
        $suppliers = Supplier::select(
            'suppliers.uuid as value',
            'suppliers.id',
            'suppliers.name',
            'suppliers.physical_address',
        )
        ->where('suppliers.name', 'LIKE', "%{$query}%")
        ->orWhere('suppliers.email', 'LIKE', "%{$query}%")
        ->orWhere('suppliers.physical_address', 'LIKE', "%{$query}%")
        ->orWhere('suppliers.mobile_number', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get();

        return $suppliers;
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
        return $supplier;
    }

    public function updateAjax(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $validated = $request->validated();

        $supplier->update([
            'name' => $validated['name'],
            'physical_address' => $validated['physical_address'],
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'],
        ]);

        return 'Supplier successfully updated.';
    }

    public function destroyAjax(Supplier $supplier)
    {
        $supplier->delete();
        
        return 'Supplier successfully deleted.';
    }
}
