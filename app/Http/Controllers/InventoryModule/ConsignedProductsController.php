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
        $sub = DB::table('consigned_products')
                ->select(
                    'consigned_products.id',
                    DB::raw('SUM(sales.quantity_sold) as quantity_sold'),
                    DB::raw('(consigned_products.quantity - SUM(sales.quantity_sold)) as quantity_available'),
                )
                ->leftJoin('sales', 'sales.consigned_product_id', '=', 'consigned_products.id')
                ->groupBy('sales.consigned_product_id');
                
        $units = ConsignedProduct::select(
            'consigned_products.uuid as value',
            'consigned_products.id',
            DB::raw("CONCAT(consigned_products.id, ' - ', products.name, ' (', consigned_products.particulars, units.abbreviation, ')') as name"),
            'consigned_products.sale_price',
        )
        ->leftJoinSub($sub, 'transactions', function($join) {
            $join->on('transactions.id', '=', 'consigned_products.id');
        })
        ->leftJoin('products', 'products.id', '=', 'consigned_products.product_id')
        ->leftJoin('units', 'units.id', '=', 'products.unit_id')
        ->where(function($q) use ($query) {
            $q->where('products.name', 'LIKE', "%{$query}%")
                ->orWhere('consigned_products.particulars', 'LIKE', "%{$query}%")
                ->orWhere('consigned_products.id', 'LIKE', "%{$query}%");
        })
        // ->where(function($q){
        //     $q->where('transactions.quantity_available', '>', 0)
        //         ->orWhere('transactions.quantity_available', '=', null);
        // })
        ->limit(5)
        ->get();

        return $units;
    }
}
