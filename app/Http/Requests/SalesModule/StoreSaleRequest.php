<?php

namespace App\Http\Requests\SalesModule;

use App\Actions\DecodeTagifyField;
use App\Http\Requests\Api\FormRequest;
use App\Models\ConsignedProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->designation == 'cashier';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer' => ['sometimes'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'products' => ['required', 'array'],
            'products.*' => ['sometimes'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'numeric'],
        ];
    }

    protected function prepareForValidation()
    {        
        $products = [];
        $quantities = [];

        // exit(var_dump($this->products));

        for($i = 0; $i < count($this->products); $i++) {
            if($this->products[$i] != null) {
                $products[] = DecodeTagifyField::run($this->products[$i]);
            }
        }

        if(isset($this->quantities)) {
            for($i = 0; $i < count($this->quantities); $i++) {
                $quantities[] = intval($this->quantities[$i]);
            }
        }

        $this->merge([
            'customer' => isset($this->customer) ? [DecodeTagifyField::run($this->customer)] : null,
            'products' => $products,
            'quantities' => $quantities,
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function($validator){
            $sub = DB::table('consigned_products')
                ->select(
                    'consigned_products.id',
                    DB::raw('SUM(sales.quantity_sold) as quantity_sold'),
                    DB::raw('(consigned_products.quantity - SUM(sales.quantity_sold)) as quantity_available'),
                )
                ->leftJoin('sales', 'sales.consigned_product_id', '=', 'consigned_products.id')
                ->groupBy('sales.consigned_product_id');

            $products = $this->get('products');
            $quantities = $this->get('quantities');

            // exit(var_dump($products));

            for($i = 0; $i < count($products); $i++)
            {
                // exit(var_dump($products[$i]->value));
                $consigned_product = DB::table('consigned_products')
                    ->leftJoinSub($sub, 'transactions', function($join) {
                        $join->on('transactions.id', '=', 'consigned_products.id');
                    })
                    ->where('consigned_products.id', $products[$i]->id)
                    ->first();

                if(!$consigned_product) {
                    $validator->errors()->add('products', "Product ('{$products[$i]->name}') does not exist.");
                }
                else if($consigned_product->quantity_available < $quantities[$i]) {
                    $validator->errors()->add('products', "Quantity of product ('{$products[$i]->name}') is not enough. Stocks remaining: {$consigned_product->quantity_available}.");
                }
            }
        });
    }
}
