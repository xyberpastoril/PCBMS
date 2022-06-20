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

    public function pdf(Invoice $invoice)
    {
        $invoice->sales;
        $invoice->customer;
        for($i = 0; $i < count($invoice->sales); $i++) {
            $invoice->sales[$i]->consignedProduct->product->unit;
        }

        $pdf = \PDF::loadView('sales.pdf', [
            'invoice' => $invoice,
        ]);
        return $pdf->stream("receipt_" . $invoice->id . ".pdf", array("Attachment" => false));
    }
}
