<?php

namespace App\Http\Controllers\SalesModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesModule\StoreSaleRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SalesController extends Controller
{
    public function showRowsAjax()
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
                'invoices.*',
                'customers.name as customer_name',
                DB::raw('SUM(total_price) as grand_total'),
                DB::raw('COUNT(p.invoice_id) as no_products')
            )
            ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin(
                DB::raw('(SELECT (sales.quantity_sold * consigned_products.sale_price) as total_price, sales.quantity_sold, consigned_products.sale_price, invoice_id, sales.id as sales_id FROM `sales` LEFT JOIN consigned_products ON consigned_products.id = sales.consigned_product_id) AS p'),
                'p.invoice_id',
                '=',
                'invoices.id'
            )
            ->groupBy('invoice_id')
            ->get();

        return $invoices;
    }

    public function create()
    {
        return view('sales.create');
    }

    public function storeAjax(StoreSaleRequest $request)
    {
        $validated = $request->validated();

        // return $validated['customer'][0]->id;

        // Create an invoice
        $invoice = Invoice::create([
            'customer_id' => isset($validated['customer'][0]->id) ? $validated['customer'][0]->id : null,
            'date' => $validated['date'],
            'user_id' => Auth::id(),
        ]);

        $invoice->customer;

        // return $invoice;

        // Create sales
        for($i = 0; $i < count($validated['products']); $i++) {
            $sales[] = [
                'uuid' => Uuid::uuid4(),
                'invoice_id' => $invoice->id,
                'consigned_product_id' => $validated['products'][$i]->id,
                'quantity_sold' => $validated['quantities'][$i],
            ];
        }

        DB::table('sales')->insert($sales);

        return 'Successfully created an invoice for ' . (isset($invoice->customer->name) ? $invoice->customer->name . '.' : 'a customer.');
    }
}
