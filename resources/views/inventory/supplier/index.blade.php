@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Suppliers</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Inventory</li>
    <li class="breadcrumb-item active">Suppliers</li>
</ol>

<div class="btn-group mb-4" role="group">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create-supplier">New Supplier</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-resposive mb-4">
            <table class="table table-bordered">
                <thead>
                    <th>
                        Actions
                        <div id="table-spinner-supplier" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>Name</th>
                    <th>Physical Address</th>
                    <th>Email Address</th>
                    <th>Mobile Number</th>
                </thead>
                <tbody id="table-content-supplier"></tbody>
            </table>
        </div>

        <div id="table-links-supplier" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection

@section('modals')
  
<!-- New Supplier Modal -->
<div class="modal fade" id="modal-create-supplier" tabindex="-1" aria-labelledby="modal-label-create-supplier" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_create-supplier">New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-supplier" data-model="supplier" class="ajax-submit" method="post" action="{{ url('/ajax/suppliers') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-supplier-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create-supplier-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-supplier-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Physical Address --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-supplier-physical_address" class="col-12 col-lg-6 col-form-label"> 
                            Location
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-create-supplier-physical_address" name="physical_address" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-supplier-physical_address" data-field="physical_address" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Mobile Number --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-supplier-mobile_number" class="col-12 col-lg-6 col-form-label"> 
                            Mobile Number
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-create-supplier-mobile_number" name="mobile_number" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-supplier-mobile_number" data-field="mobile_number" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Email --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-supplier-email_address" class="col-12 col-lg-6 col-form-label"> 
                            Email Address
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-create-supplier-email_address" name="email_address">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-supplier-email_address" data-field="email_address" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-create-supplier" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-create-supplier" type="submit" class="btn btn-primary" form="form-create-supplier">Create Supplier</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="modal-edit-supplier" tabindex="-1" aria-labelledby="modal-label-edit-supplier" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label-edit-supplier">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-spinner-edit-supplier" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form id="form-edit-supplier" data-model="supplier" class="ajax-submit" method="post" action="">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-supplier-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-edit-supplier-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-supplier-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Physical Address --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-supplier-physical_address" class="col-12 col-lg-6 col-form-label"> 
                            Location
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-edit-supplier-physical_address" name="physical_address" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-supplier-physical_address" data-field="physical_address" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Mobile Number --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-supplier-mobile_number" class="col-12 col-lg-6 col-form-label"> 
                            Mobile Number
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-edit-supplier-mobile_number" name="mobile_number" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-supplier-mobile_number" data-field="mobile_number" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Email --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-supplier-email" class="col-12 col-lg-6 col-form-label"> 
                            Email
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-edit-supplier-email" name="email">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-supplier-email" data-field="email" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-edit-supplier" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-edit-supplier" type="submit" class="btn btn-primary" form="form-edit-supplier">Update Supplier</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Supplier Modal -->
<div class="modal fade" id="modal-delete-supplier" tabindex="-1" aria-labelledby="modal-label-delete-supplier" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label-delete-supplier">Delete Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-spinner-delete-supplier" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form id="form-delete-supplier" data-model="supplier" class="ajax-submit" method="post" action="">
                    @csrf
                    @method('DELETE')
                    <p>Are you sure you want to delete <span id="form-delete-supplier-name" class="text-danger"></span>?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-delete-supplier" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Cancel</button>
                <button id="submit-form-delete-supplier" type="submit" class="btn btn-danger" form="form-delete-supplier">Yes, Delete Supplier</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('app-scripts')
<script src="{{ url('/js/form-ajax-submit.js') }}"></script>
<script src="{{ url('/js/pagination-ajax.js') }}"></script>
<script>
    var suppliersTable;

    $(document).ready(function(){
        console.log("Creating a PaginationAjax instance.");;

        supplierTable = new PaginationAjax({
            url: '/suppliers',
            ajaxUrl: '/ajax/suppliers',
            modelName: 'supplier',
            columns: [
                'name',
                'physical_address',
                'email',
                'mobile_number',
            ],
            actions: [
                'edit',
                'delete',
            ]
        });

        request = supplierTable.requestData();
        processRequest(request, supplierTable);
    });  
</script>
@endpush