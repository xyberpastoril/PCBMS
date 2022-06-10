<?php

namespace App\Http\Controllers\ReportsModule;

use App\Actions\GenerateSaleRows;
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
        $pdf = PDF::loadView('reports.sales.pdf', [
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'invoices' => GenerateSaleRows::run($request->date_from, $request->date_to),
        ]);
        return $pdf->stream("sales_report.pdf", array("Attachment" => false));
    }

    public function showRowsAjax()
    {
        return GenerateSaleRows::run();
    }
}
