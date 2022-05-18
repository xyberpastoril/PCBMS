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
        );

        return $products->paginate(10);
    }

    public function storeAjax(StoreProductRequest $request)
    {
        $validated = $request->validated();

        Product::create([
            'name' => $validated['name'],
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
        ]);
    }

    public function destroyAjax(Product $product)
    {
        $product->delete();

        return 'Product successfully deleted.';
    }
}
