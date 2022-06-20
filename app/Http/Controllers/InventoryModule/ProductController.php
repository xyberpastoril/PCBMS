<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryModule\Product\StoreProductRequest;
use App\Http\Requests\InventoryModule\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('inventory.product.index', [
            'suppliers' => Supplier::get(), // TODO: Use tagify later
        ]);
    }

    public function showRowsAjax(Request $request)
    {
        $products = Product::select(
            'products.uuid',
            'products.name',
            'units.name as unit',
        )->leftJoin('units', 'units.id', '=', 'products.unit_id');

        return $products->paginate(10);
    }

    public function searchTagifyAjax($query)
    {
        $products = Product::select(
            'products.uuid as value',
            'products.id',
            'products.name',
            'units.name as unit',
            'products.expiry_duration',
            'products.expiry_duration_type',
        )
        ->leftJoin('units', 'units.id', '=', 'products.unit_id')
        ->where('products.name', 'LIKE', "%{$query}%")
        ->orWhere('units.name', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get();

        return $products;
    }

    public function storeAjax(StoreProductRequest $request)
    {
        $validated = $request->validated();

        Product::create([
            'name' => $validated['name'],
            'unit_id' => $validated['unit'][0]->id,
            'expiry_duration' => $validated['expiry_duration'],
            'expiry_duration_type' => $validated['expiry_duration_type'],
        ]);

        return 'Product successfully added.';
    }

    public function editAjax(Product $product)
    {
        return $product;
    }

    public function updateAjax(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->update([
            'name' => $validated['name'],
            'unit_id' => $validated['unit'][0]->id,
            'expiry_duration' => $validated['expiry_duration'],
            'expiry_duration_type' => $validated['expiry_duration_type'],
        ]);

        return 'Product successfully updated.';
    }

    public function destroyAjax(Product $product)
    {
        $product->delete();

        return 'Product successfully deleted.';
    }
}
