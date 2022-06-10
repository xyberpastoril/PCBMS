<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateSaleRows
{
    use AsAction;

    public function handle($date_from = null, $date_to = null)
    {
        // Convert this SQL query below into a query builder
        // SELECT invoices.*, customers.name as customer_name, SUM(total_price) as grand_total, COUNT(p.invoice_id) as no_products
        // FROM `invoices`
        // LEFT JOIN `customers` ON invoices.customer_id = customers.id
        // LEFT JOIN (
        //     SELECT (sales.quantity_sold * consigned_products.sale_price) as total_price, sales.quantity_sold, consigned_products.sale_price, invoice_id, sales.id as sales_id
        //     FROM `sales`
        //     LEFT JOIN consigned_products ON consigned_products.id = sales.consigned_product_id
        // ) AS p ON p.invoice_id = invoices.id
        // GROUP BY invoice_id;
        $invoices = DB::table('invoices')
            ->select(
                'invoices.id as id',
                DB::raw('DATE_FORMAT(invoices.created_at, "%M %d, %Y") as date'),
                'customers.name as customer_name',
                DB::raw('SUM(total_price) as total_sales'),
                DB::raw('SUM(p.quantity_sold) as products_sold')
            )
            ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin(
                DB::raw('(SELECT (sales.quantity_sold * consigned_products.sale_price) as total_price, sales.quantity_sold, consigned_products.sale_price, invoice_id, sales.id as sales_id FROM `sales` LEFT JOIN consigned_products ON consigned_products.id = sales.consigned_product_id) AS p'),
                'p.invoice_id',
                '=',
                'invoices.id'
            )
            ->groupBy('invoice_id');
        
        if($date_from && $date_to) {
            $invoices->whereBetween('invoices.created_at', [$date_from, $date_to]);
            return $invoices->get();
        }
        else {
            return $invoices->paginate(10);
        }
    }
}
