<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryModule\Product\StoreProductRequest;
use App\Http\Requests\InventoryModule\Product\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('inventory.product.index');
    }

    public function storeAjax(StoreProductRequest $request)
    {
        //
    }

    public function editAjax(Product $product)
    {
        //
    }

    public function updateAjax(UpdateProductRequest $request, Product $product)
    {
        //
    }

    public function destroyAjax(Product $product)
    {
        //
    }
}
