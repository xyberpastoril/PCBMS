@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Sales</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Sales</li>
    <li class="breadcrumb-item active">Create Invoice</li>
</ol>

<div class="container-fluid">
    <div class="row">
        <div class="card col-12 col-xl-10 col-xxl-8">
            <div class="card-body">
                <form id="form-sell-products" data-model="sell-products" class="ajax-submit" method="post" action="{{ url('/ajax/sales') }}">
                    @csrf
                    {{-- customer / Delivery Date --}}
                    <div class="form-group row mb-3">
                        {{-- customer --}}
                        <label for="input-form-sell-products-customer" class="col-12 col-lg-2 col-form-label"> 
                            Customer
                            <span class="text-danger ml-1">*</span>
                        </label>
                        <div class="col-12 col-lg-4">
                            {{-- Input --}}
                            {{-- Create an input group with a input field then appended with a button --}}
                            <div class="input-group">
                                {{-- Input --}}
                                <input type="text" class="form-control sp_customer" id="input-form-sell-products-customer" name="customer">
                                {{-- Button --}}
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create-customer">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                </button>
                            </div>
                            {{-- Error --}}
                            <p class="text-danger error error-sp_customer col-12 mt-1 mb-0" style="display:none"></p>
                        </div>
        
                        {{-- Delivery Date --}}
                        <label for="input-form-sell-products-date" class="col-12 col-lg-2 col-form-label"> 
                            Date
                            <span class="text-danger ml-1">*</span>
                        </label>
                        <div class="col-12 col-lg-4">
                            {{-- Input --}}
                            <input type="date" class="form-control sp_date" id="input-form-sell-products-date" name="date" value="{{ now()->format('Y-m-d') }}">
                            {{-- Error --}}
                            <p class="text-danger error error-sp_date col-12 mt-1 mb-0" style="display:none"></p>
                        </div>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <th style="width:40px"></th>
                                <th style="width:50%">Product<span class="text-danger ml-1">*</span></th>
                                <th>Quantity<span class="text-danger ml-1">*</span></th>
                                <th style="width:150px" class="text-end">Sale Price</th>
                                <th style="width:150px" class="text-end">Amount</th>
                            </thead>
                            <tbody id="sold-product-items"></tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-icon btn-primary sp_add_item_entry" data-toggle="tooltip" data-placement="bottom" title="Edit">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                        </button>
                                    </td>
                                    <td colspan="2">
                                        <p class="pb-0 m-0 text-muted">Create New Row</p>
                                    </td>
                                    <td>
                                        <p class="pb-0 m-0"><strong>Total Amount</strong></p>
                                    </td>
                                    <td>
                                        <p class="pb-0 m-0 text-primary text-end"><strong id="sp_total_amount">0.00</strong></p>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button id="submit-form-sell-products" type="submit" class="btn btn-primary" form="form-sell-products">Checkout</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('modals')
<!-- New customer Modal -->
<div class="modal fade" id="modal-create-customer" tabindex="-1" aria-labelledby="modal-label-create-customer" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_create-customer">New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-customer" class="ajax-submit-customer" method="post" action="{{ url('/ajax/customer') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-customer-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create-customer-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-customer-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-create-customer" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-create-customer" type="submit" class="btn btn-primary" form="form-create-customer">Create Customer</button>
            </div>
        </div>
    </div>
</div>

<!-- PDF Modal -->
<div class="modal fade" id="modal-generate-receipt-pdf" tabindex="-1" aria-labelledby="modal-label-generate-receipt-pdf" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_generate-receipt-pdf">Successfully encoded an invoice.</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="iframe-generate-receipt-pdf" src="" frameborder="0" style="width:100%;height:65vh"></iframe>
            </div>
            <div class="modal-footer">
                <button id="close-form-generate-receipt-pdf" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button id="submit-form-generate-receipt-pdf" type="submit" class="btn btn-primary" form="form-generate-receipt-report">Download Report</button> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('app-scripts')
{{-- <script src="{{ url('/js/form-ajax-submit.js') }}"></script> --}}
<script src="{{ url('/js/tagify/customer.js') }}"></script>
<script src="{{ url('/js/sales/tagify-consigned-product.js') }}"></script>
<script src="{{ url('/js/sales/create-invoice.js') }}"></script>
<script src="{{ url('/js/sales/form-ajax-submit-sell-products.js') }}"></script>
<script src="{{ url('/js/sales/form-ajax-submit-customer.js') }}"></script>
@endpush