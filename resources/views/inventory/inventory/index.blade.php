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
                        <div id="table-spinner-received-products" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>Product Name</th>
                    <th>Particulars</th>
                    <th>Delivered At</th>
                    <th>Expires At</th>
                    <th>Unit Price</th>
                    <th>Sale Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </thead>
                <tbody id="table-content-received-products"></tbody>
            </table>
        </div>

        <div id="table-links-received-products" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection

@section('modals')
<div class="modal fade" id="modal-receive-products" tabindex="-1" aria-labelledby="modal-label-receive-products" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_receive-products">Receive Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-receive-products" method="post" action="{{ url('/ajax/inventory/receive-products') }}">
                    @csrf

                    {{-- Supplier / Delivery Date --}}
                    <div class="form-group row mb-3">
                        {{-- Supplier --}}
                        <label for="input-form-receive-products-supplier" class="col-12 col-lg-2 col-form-label"> 
                            Supplier
                            <span class="text-danger ml-1">*</span>
                        </label>
                        <div class="col-12 col-lg-4">
                            {{-- Input --}}
                            <input type="text" class="form-control rp_supplier" id="input-form-receive-products-supplier" name="supplier">
                            {{-- Error --}}
                            <p id="error-form-receive-products-supplier" data-field="supplier" class="text-danger error error-rp_supplier col-12 mt-1 mb-0" style="display:none"></p>
                        </div>

                        {{-- Delivery Date --}}
                        <label for="input-form-receive-products-date" class="col-12 col-lg-2 col-form-label"> 
                            Delivery Date
                            <span class="text-danger ml-1">*</span>
                        </label>
                        <div class="col-12 col-lg-4">
                            {{-- Input --}}
                            <input type="date" class="form-control rp_date" id="input-form-receive-products-date" name="date" value="{{ now()->format('Y-m-d') }}">
                            {{-- Error --}}
                            <p id="error-form-receive-products-date" data-field="date" class="text-danger error error-rp_date col-12 mt-1 mb-0" style="display:none"></p>
                        </div>
                    </div>

                    {{-- N/A / Consign Order --}}
                    <div class="form-group row mb-3">
                        {{-- Input N/A --}}
                        <div class="col-12 col-lg-2"></div>
                        <div class="col-12 col-lg-4"></div>

                        {{-- Label --}}
                        <label for="input-form-receive-products-consign_order" class="col-12 col-lg-2 col-form-label"> 
                            Consign Order
                        </label>
                        <div class="col-12 col-lg-4">
                            {{-- Input --}}
                            <input type="text" class="form-control rp_consign_order" id="input-form-receive-products-consign_order" name="consign_order" disabled placeholder="To be added later">
                            {{-- Error --}}
                            <p id="error-form-receive-products-consign_order" data-field="consign_order" class="text-danger error error-rp_consign_order col-12 mt-1 mb-0" style="display:none"></p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <th></th>
                                <th>Product<span class="text-danger ml-1">*</span></th>
                                <th>Particulars<span class="text-danger ml-1">*</span></th>
                                <th>Expiration Date<span class="text-danger ml-1">*</span></th>
                                <th>Unit Price<span class="text-danger ml-1">*</span></th>
                                <th>Sale Price</th>
                                <th>Quantity<span class="text-danger ml-1">*</span></th>
                            </thead>
                            <tbody id="receive-product-items"></tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-icon btn-primary rp_add_item_entry" data-toggle="tooltip" data-placement="bottom" title="Edit">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                        </button>
                                    </td>
                                    <td colspan="6">
                                        <p class="pb-0 m-0 text-muted">Create New Row</p>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-receive-products" type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-receive-products" type="submit" class="btn btn-primary submit" form="form-receive-products">Create Product</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('app-scripts')
<script src="{{ url('/js/tagify/product.js') }}"></script>
<script src="{{ url('/js/tagify/supplier.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/receive-products.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/form-ajax-submit-receive-products.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/pagination-ajax-received-products.js') }}"></script>
<script>
    var receivedProductsTable;

    $(document).ready(function(){
        console.log("Creating a PaginationAjax instance.");;

        receivedProductsTable = new ReceivedProductsPaginationAjax({
            url: '/',
            ajaxUrl: '/ajax/inventory/receive-products',
            modelName: 'received-products',
            columns: [
                'name',
                'particulars',
                'order_delivered_at',
                'expiration_date',
                'unit_price',
                'sale_price',
                'quantity',
                'amount',
            ],
            actions: [
                // 'edit',
                // 'delete',
            ]
        });

        request = receivedProductsTable.requestData();
        processRequestReceivedProducts(request, receivedProductsTable);
    });  
</script>
@endpush