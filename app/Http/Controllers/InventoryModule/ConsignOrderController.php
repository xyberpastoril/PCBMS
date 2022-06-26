<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use App\Models\ConsignOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsignOrderController extends Controller
{
    public function index()
    {
        return view('inventory.consign-order.index');
    }

    public function searchTagifyAjax($query = null)
    {
        $consign_orders = ConsignOrder::select(
            'consign_orders.uuid as value',
            'consign_orders.id',
            'consign_orders.supplier_id',
            'consign_orders.order_delivered_at',
            'suppliers.name',
            DB::raw('CONCAT(consign_orders.id, " - ", suppliers.name) as label')
        )->leftJoin('suppliers', 'suppliers.id', '=', 'consign_orders.supplier_id');

        if($query) {
            $consign_orders->where('suppliers.name', 'LIKE', "%{$query}%")
                ->orWhere('consign_orders.id', 'LIKE', "%{$query}%");
        }

        return $consign_orders->limit(5)->get();
    }
}
