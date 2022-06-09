<?php

namespace App\Http\Controllers\SalesModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesModule\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function searchTagifyAjax($query)
    {
        $customers = Customer::select(
            'customers.uuid as value',
            'customers.id',
            'customers.name',
        )
        ->where('customers.name', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get();

        return $customers;
    }

    public function storeAjax(StoreCustomerRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
        ]);

        return $customer;
    }
}
