<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsignOrderController extends Controller
{
    public function index()
    {
        return view('inventory.consign-order.index');
    }
}
