<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use App\Models\ConsignedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsignedProductsController extends Controller
{
    public function searchTagifyAjax($query)
    {
        $units = ConsignedProduct::select(
            'consigned_products.uuid as value',
            'consigned_products.id',
            DB::raw("CONCAT(products.name, ' (', consigned_products.particulars, units.abbreviation, ')') as name"),
            'consigned_products.sale_price',
        )
        ->leftJoin('products', 'products.id', '=', 'consigned_products.product_id')
        ->leftJoin('units', 'units.id', '=', 'products.unit_id')
        ->where('products.name', 'LIKE', "%{$query}%")
        ->orWhere('consigned_products.particulars', 'LIKE', "%{$query}%")
        ->orWhere('consigned_products.id', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get();

        return $units;
    }
}
