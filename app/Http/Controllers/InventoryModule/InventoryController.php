<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.inventory.index');
    }

    // TODO: Order Products
    // TODO: Pay Supplier
    // TODO: Receive Products
    // TODO: Return Expired Products
}
