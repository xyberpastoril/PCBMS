@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Inventory</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Inventory</li>
    <li class="breadcrumb-item active">Consign Orders</li>
</ol>

<div class="tab-pane fade show active" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
    <div class="btn-group mb-4" role="group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-order-products">Order Products</button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-resposive mb-4">
            <table class="table table-bordered">
                <thead>
                    <th>
                        Actions
                        <div id="table-spinner-consign-order" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>Order</th>
                    <th>Supplier</th>
                    <th>Personnel</th>
                    <th>Date</th>
                    <th>Status</th>
                </thead>
                <tbody id="table-content-consign-order"></tbody>
            </table>
        </div>

        <div id="table-links-consign-order" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection
