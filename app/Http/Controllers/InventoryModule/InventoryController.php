<?php

namespace App\Http\Controllers\InventoryModule;

use App\Actions\DecodeTagifyField;
use App\Http\Controllers\Controller;
use App\Models\ConsignOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.inventory.index');
    }

    public function receiveProducts(Request $request)
    {
        // TODO: Create Form Validation

        $supplier = DecodeTagifyField::run($request->supplier);
        $products = DecodeTagifyField::run($request->products);

        // Create Consign Order (if there is none), 
        // TODO: otherwise, update it.
        $consign_order = ConsignOrder::create([
            'supplier_id' => $supplier->id,
            'user_id' => Auth::id(),
            'order_delivered_at' => now(),
            'delivered_by' => null, // TODO: Include delivered by later.
        ]);

        // Loop around received products to create rows
        // for insertion into the database.
        for($i = 0; $i < count($products); $i++)
        {
            $received_products[] = [
                'uuid' => (string)Uuid::uuid4(),
                'product_id' => $products[$i]->id,
                'consign_order_id' => $consign_order->id,
                'particulars' => $request->particulars[$i],
                'expiration_date' => $request->expiration_dates[$i],
                'unit_price' => $request->unit_prices[$i],
                'sale_price' => $request->sale_prices[$i],
                'quantity' => $request->quantities[$i],
            ];
        }

        // Store array of received products to the database.
        DB::table('consigned_products')->insert($received_products);

        // TODO: Experiment this with AJAX.
        return redirect('/inventory/');
    }

    // TODO: Pay Supplier
    // TODO: Return Expired Products
}
