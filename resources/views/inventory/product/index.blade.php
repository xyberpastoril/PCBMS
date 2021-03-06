@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Products</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Inventory</li>
    <li class="breadcrumb-item active">Products</li>
</ol>

<div class="btn-group mb-4" role="group">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create-product">New Product</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-resposive mb-4">
            <table class="table table-bordered">
                <thead>
                    <th>
                        Actions
                        <div id="table-spinner-product" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>Name</th>
                    <th>Unit</th>
                </thead>
                <tbody id="table-content-product"></tbody>
            </table>
        </div>

        <div id="table-links-product" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection

@section('modals')
  
<!-- New Product Modal -->
<div class="modal fade" id="modal-create-product" tabindex="-1" aria-labelledby="modal-label-create-product" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_create-product">New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-product" data-model="product" class="ajax-submit-products" method="post" action="{{ url('/ajax/products') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-product-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create-product-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-product-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Unit --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-product-unit" class="col-12 col-lg-6 col-form-label"> 
                            Unit
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create-product-unit" name="unit">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-product-unit" data-field="unit" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Expiry Duration --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-product-unit" class="col-12 col-lg-6 col-form-label"> 
                            Expiry Duration
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            {{-- Input Group where Input is Numeric and Select for Type --}}
                            <div class="input-group">
                                <input type="number" class="form-control" id="input-form-create-product-expiry-duration" name="expiry_duration" required value="1" min="1">
                                <select class="form-control form-select" id="input-form-create-product-expiry-duration-type" name="expiry_duration_type" required>
                                    <option value="day" selected>Days</option>
                                    <option value="week">Weeks</option>
                                    <option value="month">Months</option>
                                    <option value="year">Years</option>
                                </select>
                            </div>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-product-expiry-duration" data-field="expiry-duration" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-create-product" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-create-product" type="submit" class="btn btn-primary" form="form-create-product">Create Product</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="modal-edit-product" tabindex="-1" aria-labelledby="modal-label-edit-product" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label-edit-product">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-spinner-edit-product" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form id="form-edit-product" data-model="product" class="ajax-submit-products" method="post" action="">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-product-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-edit-product-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-product-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Unit --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-product-unit" class="col-12 col-lg-6 col-form-label"> 
                            Unit
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-edit-product-unit" name="unit" data-tagify="true">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-product-unit" data-field="unit" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Expiry Duration --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-product-unit" class="col-12 col-lg-6 col-form-label"> 
                            Expiry Duration
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            {{-- Input Group where Input is Numeric and Select for Type --}}
                            <div class="input-group">
                                <input type="number" class="form-control" id="input-form-edit-product-expiry_duration" name="expiry_duration" required min="1">
                                <div class="input-group-append">
                                    <select class="form-control form-select" id="input-form-edit-product-expiry_duration_type" name="expiry_duration_type" required>
                                        <option id="form-edit-product-option-day" value="day">Days</option>
                                        <option id="form-edit-product-option-week" value="week">Weeks</option>
                                        <option id="form-edit-product-option-month" value="month">Months</option>
                                        <option id="form-edit-product-option-year" value="year">Years</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-product-expiry-duration" data-field="expiry-duration" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-edit-product" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-edit-product" type="submit" class="btn btn-primary" form="form-edit-product">Update Product</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="modal-delete-product" tabindex="-1" aria-labelledby="modal-label-delete-product" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label-delete-product">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-spinner-delete-product" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form id="form-delete-product" data-model="product" class="ajax-submit-products" method="post" action="">
                    @csrf
                    @method('DELETE')
                    <p>Are you sure you want to delete <span id="form-delete-product-name" class="text-danger"></span>?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-delete-product" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Cancel</button>
                <button id="submit-form-delete-product" type="submit" class="btn btn-danger" form="form-delete-product">Yes, Delete Product</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('app-scripts')
<script src="{{ url('/js/inventory/products/form-ajax-submit-products.js') }}"></script>
<script src="{{ url('/js/inventory/products/pagination-ajax-products.js') }}"></script>
<script src="{{ url('/js/tagify/unit.js') }}"></script>
<script src="{{ url('/js/inventory/products/products.js') }}"></script>
<script>
    var productsTable;

    $(document).ready(function(){
        console.log("Creating a ProductsPaginationAjax instance.");;

        productTable = new ProductsPaginationAjax({
            url: '/products',
            ajaxUrl: '/ajax/products',
            modelName: 'product',
            columns: [
                'name',
                'unit',
            ],
            actions: [
                'edit',
                'delete',
            ]
        });

        request = productTable.requestData();
        processProductRequest(request, productTable);
    });  
</script>
@endpush