@extends('includes.pdf-template')
@section('page_title', 'Sales Report')

@section('content')

<p>Products Sold</p>
<table class="table-bordered">
    <thead>
        <th>Item ID</th>
        <th>Item Name</th>
        <th>Quantity Sold</th>
        <th style="text-align:right">Unit Price</th>
        <th style="text-align:right">Total Sales</th>
    </thead>
    <tbody>
        @if(count($products) > 0)
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}
                    <td>{{ $product->quantity_sold }}</td>
                    <td style="text-align:right">Php {{ number_format($product->unit_price, 2) }}</td>
                    <td style="text-align:right">Php {{ number_format($product->total_sales, 2) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">No records found.</td>
            </tr>
        @endif
    </tbody>
</table>

<p>Invoices</p>
<table class="table-bordered">
    <thead>
        <th>Invoice #</th>
        <th>Date</th>
        <th>Customer Name</th>
        <th>Products Sold</th>
        <th style="text-align:right">Total Sales</th>
    </thead>
    <tbody>
        @if(count($invoices) > 0)
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->date}}</td>
                    <td>{{ $invoice->customer_name }}</td>
                    <td>{{ $invoice->products_sold }}</td>
                    <td style="text-align:right">Php {{ number_format($invoice->total_sales, 2) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">No records found.</td>
            </tr>
        @endif
    </tbody>
</table>

@endsection