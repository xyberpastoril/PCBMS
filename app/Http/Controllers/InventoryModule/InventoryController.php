<?php

namespace App\Http\Controllers\InventoryModule;

use App\Actions\DecodeTagifyField;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryModule\Inventory\PaySupplierRequest;
use App\Http\Requests\InventoryModule\Inventory\ReceiveProductsRequest;
use App\Http\Requests\InventoryModule\Inventory\ReturnExpiredProductRequest;
use App\Models\ConsignOrder;
use App\Models\ConsignedProduct;
use App\Models\Supplier;
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

    public function showRowsAjax()
    {
        // $consigned_products = ConsignedProduct::select(
        //     'consigned_products.uuid',
        //     'consigned_products.id',
        //     'products.name',
        //     'consigned_products.particulars',
        //     'units.abbreviation as unit',
        //     DB::raw('DATE_FORMAT(consign_orders.order_delivered_at, "%Y-%m-%d") as order_delivered_at'),
        //     'consigned_products.expiration_date',
        //     'consigned_products.unit_price',
        //     'consigned_products.sale_price',
        //     'consigned_products.quantity',
        //     DB::raw('(consigned_products.unit_price * consigned_products.quantity) as amount')
        // )
        // ->leftJoin('products', 'consigned_products.product_id', 'products.id')
        // ->leftJoin('units', 'products.unit_id', 'units.id')
        // ->leftJoin('consign_orders', 'consigned_products.consign_order_id', 'consign_orders.id');

        $consign_orders = ConsignOrder::select(
            'consign_orders.uuid',
            'consign_orders.id',
            'suppliers.name as supplier',
            DB::raw('DATE_FORMAT(consign_orders.order_delivered_at, "%Y-%m-%d") as order_delivered_at'),
        )
        ->leftJoin('suppliers', 'consign_orders.supplier_id', 'suppliers.id');

        return $consign_orders->paginate(10);
    }

    public function receiveProductsAjax(ReceiveProductsRequest $request)
    {
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

        return 'Received Products successfully added.';
    }

    public function barcodePdf(ConsignOrder $consignOrder)
    {
        $consignOrder->consignedProducts;
        for($i = 0; $i < count($consignOrder->consignedProducts); $i++)
            $consignOrder->consignedProducts[$i]->product->unit;

        $pdf = \PDF::loadView('inventory.consign-order.pdf', [
            'consigned_products' => $consignOrder->consignedProducts,
        ]);

        return $pdf->stream("order-barcodes-{$consignOrder->id}.pdf", array("Attachment" => false));
    }

    // TODO: Pay Supplier
    public function showProductsToPayAjax(Supplier $supplier = null)
    {
        // SELECT x.*
        // FROM (
        //     select CONCAT(products.name, ' (', consigned_products.particulars, units.abbreviation, ')') as name, 
        //         `consigned_products`.`id`, 
        //         `consigned_products`.`quantity`, 
        //         `p`.`quantity_sold`, 
        //         `consigned_products`.`quantity_paid`, 
        //         `consigned_products`.`sale_price`, 
        //         `consigned_products`.`unit_price`, 
        //         ((consigned_products.sale_price - consigned_products.unit_price) * p.quantity_sold) as current_profit, 
        //         ((consigned_products.sale_price - consigned_products.unit_price) * consigned_products.quantity) as supposed_profit 
        //     from `consigned_products` 
        //     left join `products` on `consigned_products`.`product_id` = `products`.`id` 
        //     left join `units` on `products`.`unit_id` = `units`.`id` 
        //     left join `consign_orders` on `consigned_products`.`consign_order_id` = `consign_orders`.`id` 
        //     left join ( 
        //         SELECT (sales.quantity_sold * consigned_products.sale_price) as total_price, 
        //         sales.quantity_sold, 
        //         consigned_products.sale_price, 
        //         consigned_products.id 
        //         FROM `sales` 
        //         LEFT JOIN consigned_products ON consigned_products.id = sales.consigned_product_id 
        //     ) AS p on `p`.`id` = `consigned_products`.`id` 
        //     group by `consigned_products`.`id`
        // ) as x
        // WHERE x.quantity_paid < x.quantity

        $sub_consigned_products = DB::table('consigned_products')
            ->select(
                'consigned_products.uuid',
                DB::raw("CONCAT(products.name, ' (', consigned_products.particulars, units.abbreviation, ')') as name"),
                'consigned_products.id',
                'consigned_products.quantity',
                DB::raw('IFNULL(p.quantity_sold, 0) as quantity_sold'),
                DB::raw('IFNULL(consigned_products.quantity_paid, 0) as quantity_paid'),
                'consigned_products.sale_price',
                'consigned_products.unit_price',
                'consign_orders.supplier_id',
                DB::raw('IFNULL(((consigned_products.sale_price - consigned_products.unit_price) * p.quantity_sold), 0) as current_profit'),
                DB::raw('((consigned_products.sale_price - consigned_products.unit_price) * consigned_products.quantity) as supposed_profit'),
                DB::raw('(IFNULL(p.quantity_sold, 0) - IFNULL(consigned_products.quantity_paid, 0)) * consigned_products.unit_price as amount_to_pay'),
            )
            ->leftJoin('products', 'consigned_products.product_id', 'products.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('consign_orders', 'consigned_products.consign_order_id', 'consign_orders.id')
            ->leftJoin(
                DB::raw('(
                        SELECT (sales.quantity_sold * consigned_products.sale_price) as total_price, 
                            SUM(sales.quantity_sold) as quantity_sold, 
                            consigned_products.sale_price, 
                            consigned_products.id
                        FROM `sales` 
                        LEFT JOIN consigned_products ON consigned_products.id = sales.consigned_product_id
                        GROUP BY sales.consigned_product_id
                    ) AS p'),
                'p.id',
                '=',
                'consigned_products.id'
                );

        $consigned_products = DB::table(DB::raw("({$sub_consigned_products->toSql()}) as x"))
            ->select('x.*')
            ->whereRaw('x.quantity_sold > x.quantity_paid');           

        if($supplier) {
            $consigned_products->where('x.supplier_id', $supplier->id);
        }

        return $consigned_products->get();
    }

    public function paySupplierAjax(PaySupplierRequest $request)
    {
        for($i = 0; $i < count($request->products); $i++)
        {
            $consigned_product = ConsignedProduct::where('uuid', $request->products[$i])->first();
            
            // Get number of sold items
            $item = DB::table('sales')
                ->select(DB::raw('SUM(quantity_sold) as quantity_sold'))
                ->where('consigned_product_id', $consigned_product->id)
                ->groupBy('consigned_product_id')
                ->first();

            $consigned_product->quantity_paid = $item->quantity_sold;
            $consigned_product->save();
        }

        return 'Successfully paid supplier.';
    }

    // TODO: Return Expired Products
    public function showExpiredProductsToReturnAjax(Supplier $supplier = null)
    {
        $consigned_products = DB::table('consigned_products')
            ->select(
                'consigned_products.uuid',
                DB::raw("CONCAT(products.name, ' (', consigned_products.particulars, units.abbreviation, ')') as name"),
                'consigned_products.expiration_date',
                'consigned_products.id',
                'consigned_products.quantity',
                'p.quantity_sold',
                'consigned_products.sale_price',
                'consigned_products.unit_price',
                DB::raw('consigned_products.quantity - IFNULL(p.quantity_sold, 0) - consigned_products.quantity_returned as quantity_to_return'),
                DB::raw('IFNULL(((consigned_products.sale_price - consigned_products.unit_price) * p.quantity_sold), 0) as current_profit'),
                DB::raw('((consigned_products.sale_price - consigned_products.unit_price) * consigned_products.quantity) as supposed_profit')
            )
            ->leftJoin('products', 'consigned_products.product_id', 'products.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('consign_orders', 'consigned_products.consign_order_id', 'consign_orders.id')
            ->leftJoin(
                DB::raw('(
                        SELECT (sales.quantity_sold * consigned_products.sale_price) as total_price, 
                            IFNULL(SUM(sales.quantity_sold), 0) as quantity_sold, 
                            consigned_products.sale_price, 
                            consigned_products.id
                        FROM `sales` 
                        LEFT JOIN consigned_products ON consigned_products.id = sales.consigned_product_id
                        GROUP BY sales.consigned_product_id
                    ) AS p'),
                'p.id',
                '=',
                'consigned_products.id'
            )
            // where subquery
            ->havingRaw('quantity_to_return > 0 or quantity_to_return is null')
            ->whereRaw('DATEDIFF(consigned_products.expiration_date, CURDATE()) < 0');

        if($supplier) {
            $consigned_products->where('consign_orders.supplier_id', $supplier->id);
        }

        return $consigned_products->get();
    }

    public function returnExpiredProductsAjax(ReturnExpiredProductRequest $request)
    {
        // return $request;

        for($i = 0; $i < count($request->products); $i++)
        {
            $consigned_product = ConsignedProduct::where('uuid', $request->products[$i])->first();
            
            // Get number of sold items
            $item = DB::table('sales')
                ->select(DB::raw('SUM(quantity_sold) as quantity_sold'))
                ->where('consigned_product_id', $consigned_product->id)
                ->groupBy('consigned_product_id')
                ->first();

            // Get number of items to return

            $consigned_product->quantity_returned = $consigned_product->quantity - ((isset($item->quantity_sold) ? $item->quantity_sold : 0));
            $consigned_product->save();
        }

        return 'Successfully returned expired products.';
    }
}
