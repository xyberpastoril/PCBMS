@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Inventory</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Inventory</li>
    <li class="breadcrumb-item active">Inventory</li>
</ol>

<div class="tab-pane fade show active" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
    <div class="btn-group mb-4" role="group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-receive-products">Receive Products</button>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-pay-suppliers">Pay Suppliers</button>
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-expired-products">Return Expired Products</button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-resposive mb-4">
            <table class="table table-bordered">
                <thead>
                    <th>
                        Actions
                        <div id="table-spinner-inventory" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Particulars</th>
                    <th>Delivered At</th>
                    <th>Expires At</th>
                    <th>Unit Price</th>
                    <th>Sale Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </thead>
                <tbody id="table-content-inventory"></tbody>
            </table>
        </div>

        <div id="table-links-inventory" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection
