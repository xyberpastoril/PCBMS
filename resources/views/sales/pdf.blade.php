@extends('includes.pdf-template')
@section('page_title', 'Receipt')

@section('content')

<table>
    <tr>
        <td>Invoice #</td>
        <td><strong>{{ $invoice->id }}</strong></td>
    </tr>
    <tr>
        <td>Customer</td>
        <td><strong>
            @if($invoice->customer_id != null)
                {{ $invoice->customer->name }}
            @else
                {{ 'Anonymous' }}
            @endif
        </strong></td>
    </tr>
    <tr>
        <td>Date</td>
        <td>
            @php
                $dt = new DateTime($invoice->date);
                $sum_amount = 0;
            @endphp
            <strong>{{ $dt->format('Y-m-d') }}</strong>
        </td>
    </tr>
</table>


<table class="table-bordered">
    <thead>
        <th>Product</th>
        <th>Quantity</th>
        <th>Sale Price</th>
        <th style="text-align:right">Amount</th>
    </thead>
    <tbody>
        @if(count($invoice->sales) > 0)
            @foreach($invoice->sales as $sale)
                <tr>
                    <td>{{ $sale->consignedProduct->product->name . ' (' . $sale->consignedProduct->particulars . $sale->consignedProduct->product->unit->abbreviation . ')' }}</td>
                    <td>{{ $sale->quantity_sold}}</td>
                    <td style="text-align:right">Php {{ number_format($sale->consignedProduct->sale_price, 2) }}</td>
                    <td style="text-align:right">Php {{ number_format($sale->consignedProduct->sale_price * $sale->quantity_sold, 2) }}</td>
                    @php
                        $sum_amount += $sale->consignedProduct->sale_price * $sale->quantity_sold;
                    @endphp
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">No records found.</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
            </td>
            <td>
                <p class="pb-0 m-0"><strong>Total Amount</strong></p>
            </td>
            <td>
                <p class="pb-0 m-0 text-primary text-end"><strong id="sp_total_amount">Php {{ number_format($sum_amount, 2) }}</strong></p>
            </td>
        </tr>
    </tfoot>
</table>

@endsection