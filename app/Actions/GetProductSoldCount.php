<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetProductSoldCount
{
    use AsAction;

    public function handle($date_from, $date_to)
    {
        $sales = DB::table('sales')
            ->select(
                DB::raw('consigned_products.id'),
                DB::raw('sum(quantity_sold) as quantity_sold'),
                'consigned_products.id',
                DB::raw('CONCAT(products.name, " (", consigned_products.particulars, units.abbreviation, ")") as name'),
                DB::raw('consigned_products.unit_price'),
                DB::raw('consigned_products.unit_price * sum(quantity_sold) as total_sales'),
            )
            ->leftJoin('consigned_products', 'consigned_products.id', '=', 'sales.consigned_product_id')
            ->leftJoin('products', 'products.id', '=', 'consigned_products.product_id')
            ->leftJoin('units', 'units.id', '=', 'products.unit_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'sales.invoice_id')
            ->whereBetween('invoices.created_at', [$date_from, $date_to])
            ->groupBy('consigned_products.id')
            ->get();

        return $sales;
    }
}
