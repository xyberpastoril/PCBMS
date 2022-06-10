<?php

namespace App\Http\Controllers\ReportsModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportsModule\GenerateSalesReportRequest;
use App\Http\Requests\SalesModule\StoreSaleRequest;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Barryvdh\DomPDF\Facade as PDF;

class SalesController extends Controller
{
    public function index()
    {
        return view('reports.sales.index');
    }

    public function pdf(GenerateSalesReportRequest $request)
    {
        $pdf = PDF::loadView('reports.sales.pdf');
        return $pdf->stream("sales_report.pdf", array("Attachment" => false));
    }

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
            ->groupBy('invoice_id')
            ->paginate(10);

        return $invoices;
    }
}
