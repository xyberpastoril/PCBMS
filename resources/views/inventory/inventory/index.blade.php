@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Inventory</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Inventory</li>
    <li class="breadcrumb-item active">Inventory</li>
</ol>


<div class="btn-toolbar mb-4" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group me-2" role="group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-consigned-products">Check Current Inventory</button>
    </div>

    <div class="btn-group" role="group">
        <button id="rp_modal_open" type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-receive-products">Receive Products</button>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-pay-supplier">Pay Suppliers</button>
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-return-expired-products">Return Expired Products</button>
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
                    <th>ID</th>
                    <th>Supplier Name</th>
                    <th>Date Delivered</th>
                </thead>
                <tbody id="table-content-received-products"></tbody>
            </table>
        </div>

        <div id="table-links-received-products" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection

@section('modals')
{{-- Current Inventory --}}
<div class="modal fade" id="modal-consigned-products" tabindex="-1" aria-labelledby="modal-label-consigned-products" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_consigned-products">Current Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-resposive mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <th>
                                ID
                                <div id="table-spinner-consigned-products" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Delivered At</th>
                            <th>Quantity</th>
                            <th>Quantity Sold</th>
                            <th>Expiration Date</th>
                            <th>Unit Price</th>
                            <th>Sale Price</th>
                            <th>Inventory Value</th>
                        </thead>
                        <tbody id="table-content-consigned-products"></tbody>
                    </table>
                </div>
        
                <div id="table-links-consigned-products" class="btn-group mb-4" role="group"></div>
            </div>
            <div class="modal-footer">
                <button id="close-form-consigned-products" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Receive Products --}}
<div class="modal fade" id="modal-receive-products" tabindex="-1" aria-labelledby="modal-label-receive-products" aria-hidden="true">
    <div id="rp_modal_size" class="modal-dialog">
        <div class="modal-content">
            <form id="form-receive-products" data-model="receive-products" method="post" data-model="ConsignOrders" action="{{ url('/ajax/inventory/receive-products') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label_receive-products">Receive Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="rp_body_1" class="modal-body" style="display:block">
                    <h5 class="mb-3">Enter Consign Order Details</h5>
                    {{-- Supplier / Delivery Date --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-receive-products-supplier" class="col-12 col-lg-6 col-form-label"> 
                            Supplier
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control rp_supplier input-supplier" id="input-form-receive-products-supplier" name="supplier">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-receive-products-supplier" data-field="supplier" class="text-danger error error-rp_supplier col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Delivery Date --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-receive-products-date" class="col-12 col-lg-6 col-form-label"> 
                            Delivery Date
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="date" class="form-control rp_date" id="input-form-receive-products-date" name="date" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-receive-products-date" data-field="date" class="text-danger error error-rp_date col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                </div>
                <div id="rp_body_2" class="modal-body" style="display:none">
                    <h5 class="mb-3">Encode Received Products</h5>
                    <p>Supplier: <strong id="rp_supplier_text"></strong></p>
                    <p>Date: <strong id="rp_date_text"></strong></p>
                    <p>Possible Consign Order #: <strong id="rp_order_text"></strong></p>
                    {{-- <p>Possible Consign Order #: <strong id="rp_consign_order_id"></strong></p> --}}
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <th></th>
                                <th>Product<span class="text-danger ml-1">*</span></th>
                                <th>Particulars<span class="text-danger ml-1">*</span></th>
                                <th>Expiration Date<span class="text-danger ml-1">*</span></th>
                                <th>Unit Price<span class="text-danger ml-1">*</span></th>
                                <th>Sale Price<span class="text-danger ml-1">*</span></th>
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
                </div>
                <div id="rp_footer_1" class="modal-footer" style="display:flex">
                    <button id="close-form-receive-products" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="next-form-receive-products" type="button" class="btn btn-primary">Next</button>
                </div>
                <div id="rp_footer_2" class="modal-footer" style="display:none">
                    <button id="back-form-receive-products" type="button" class="btn btn-secondary">Back</button>
                    <button id="submit-form-receive-products" type="submit" class="btn btn-success" form="form-receive-products">Receive Products</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Show Consign Order --}}
<div class="modal fade" id="modal-show-consign-order" tabindex="-1" aria-labelledby="modal-label-show-consign-order" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_show-consign-order"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Quantity Sold</th>
                            <th>Expiration Date</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Sale Price</th>
                            <th class="text-end">Inventory Value</th>
                        </thead>
                        <tbody id="show-consign-order-items"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button id="close-form-show-consign-order" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Generate Barcode --}}
<div class="modal fade" id="modal-generate-barcode-pdf" tabindex="-1" aria-labelledby="modal-label-generate-barcode-pdf" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_generate-barcode-pdf">Barcodes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="iframe-generate-barcode-pdf" src="" frameborder="0" style="width:100%;height:65vh"></iframe>
            </div>
            <div class="modal-footer">
                <button id="close-form-generate-barcode-pdf" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button id="submit-form-generate-barcode-pdf" type="submit" class="btn btn-primary" form="form-generate-barcode-report">Download Report</button> --}}
            </div>
        </div>
    </div>
</div>

{{-- Pay Supplier --}}
<div class="modal fade" id="modal-pay-supplier" tabindex="-1" aria-labelledby="modal-label-pay-supplier" aria-hidden="true">
    <div id="ps_modal_size" class="modal-dialog">
        <div class="modal-content">
            <form id="form-pay-supplier" method="post" action="{{ url('/ajax/inventory/pay-supplier') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label_pay-supplier">Pay Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="ps_body_1" class="modal-body" style="display:block">
                    <h5 class="mb-3">Encode Consign Order</h5>
                    {{-- Supplier / Delivery Date --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-pay-supplier-consign_order" class="col-12 col-lg-6 col-form-label"> 
                            Consign Order
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control ps_consign_order input-consign_order" id="input-form-pay-supplier-consign_order" data-action="pay-supplier" name="consign_order">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-receive-products-consign_order" data-field="consign_order" class="text-danger error error-ps_consign_order col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                </div>
                <div id="ps_body_2" class="modal-body" style="display:none">
                    <h5 class="mb-3">Select Products to Pay</h5>
                    <p>Consign Order: <strong id="ps_consign-order_text" ></strong></p>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <th></th>
                                <th>Product</th>
                                <th class="text-end">Quantity Bought</th>
                                <th class="text-end">Quantity Sold</th>
                                <th class="text-end">Quantity Paid</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Amount to Pay</th>
                            </thead>
                            <tbody id="pay-supplier-items"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <p class="pb-0 m-0 text-muted text-end">Grand Total</p>
                                    </td>
                                    <td>
                                        <p class="pb-0 m-0 text-end">
                                            Php 
                                            <span id="pay-supplier-grand-total">0.00</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <p class="pb-0 m-0 text-muted text-end">Selected Total</p>
                                    </td>
                                    <td>
                                        <p class="pb-0 m-0 text-end">
                                            <strong>
                                                Php 
                                                <span id="pay-supplier-selected-total">0.00</span>
                                            </strong>
                                        </p>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div id="ps_footer_1" class="modal-footer" style="display:flex">
                    <button id="close-form-pay-supplier" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="next-form-pay-supplier" type="button" class="btn btn-primary">Next</button>
                </div>
                <div id="ps_footer_2" class="modal-footer" style="display:none">
                    <button id="back-form-pay-supplier" type="button" class="btn btn-secondary">Back</button>
                    <button id="submit-form-pay-supplier" type="submit" class="btn btn-primary" form="form-pay-supplier">Pay Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Return Expired --}}
<div class="modal fade" id="modal-return-expired-products" tabindex="-1" aria-labelledby="modal-label-return-expired-products" aria-hidden="true">
    <div id="rep_modal_size" class="modal-dialog">
        <form id="form-return-expired-products" method="post" action="{{ url('/ajax/inventory/return-expired-products') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label_return-expired-products">Return Expired Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="rep_body_1" class="modal-body" style="display:block">

                    <h5 class="mb-3">Encode Consign Order</h5>
                    {{-- Supplier / Delivery Date --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-return-expired-products-consign_order" class="col-12 col-lg-6 col-form-label"> 
                            Consign Order
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control ps_consign_order input-consign_order" id="input-form-return-expired-products-consign_order" data-action="return-expired-products" name="consign_order">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-receive-products-consign_order" data-field="consign_order" class="text-danger error error-rep_consign_order col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </div>
                <div id="rep_body_2" class="modal-body" style="display:none">
                    <h5 class="mb-3">Select Expired Products to Return</h5>
                    <p>Consign Order: <strong id="rep_consign-order_text"></strong></p>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <th></th>
                                <th>Product</th>
                                <th class="text-end">Expiration Date</th>
                                <th class="text-end">Quantity Bought</th>
                                <th class="text-end">Quantity Sold</th>
                                <th class="text-end">Quantity To Return</th>
                            </thead>
                            <tbody id="return-expired-product-items"></tbody>
                        </table>
                    </div>
                </div>
                <div id="rep_footer_1" class="modal-footer" style="display:flex">
                    <button id="close-form-return-expired-products" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="next-form-return-expired-products" type="button" class="btn btn-primary">Next</button>
                </div>
                <div id="rep_footer_2" class="modal-footer" style="display:none">
                    <button id="back-form-return-expired-products" type="button" class="btn btn-secondary">Back</button>
                    <button id="submit-form-return-expired-products" type="submit" class="btn btn-primary" form="form-return-expired-products">Return Expired Products</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('app-scripts')
<script src="{{ url('/js/tagify/product.js') }}"></script>
<script src="{{ url('/js/tagify/supplier.js') }}"></script>
<script src="{{ url('/js/tagify/consign-order.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/inventory.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/receive-products.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/pay-supplier.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/return-expired-products.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/form-ajax-submit-receive-products.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/pagination-ajax-received-products.js') }}"></script>
<script src="{{ url('/js/inventory/inventory/pagination-ajax-consigned-products.js') }}"></script>
<script>
    var receivedProductsTable;

    $(document).ready(function(){
        console.log("Creating a PaginationAjax instance.");;

        receivedProductsTable = new ReceivedProductsPaginationAjax({
            url: '/',
            ajaxUrl: '/ajax/inventory/receive-products',
            modelName: 'received-products',
            columns: [
                'id',
                'supplier',
                'order_delivered_at',
            ],
            actions: [
                'view',
                'barcode',
                // 'edit',
                // 'delete',
            ]
        });

        request = receivedProductsTable.requestData();
        processRequestReceivedProducts(request, receivedProductsTable);

        console.log("Creating a PaginationAjax instance.");;

        consignedProducts = new ConsignedProductsPaginationAjax({
            url: '/',
            ajaxUrl: '/ajax/inventory/consigned-products',
            modelName: 'consigned-products',
            columns: [
                'id',
                'name',
                'order_delivered_at',
                'quantity',
                'quantity_sold',
                'expiration_date',
                'unit_price',
                'sale_price',
                'inventory_value',
            ],
            actions: []
        });

        request2 = consignedProducts.requestData();
        processRequestConsignedProducts(request2, consignedProducts);
    });  
</script>
@endpush