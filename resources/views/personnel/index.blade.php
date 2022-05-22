@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Personnel</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Administration</li>
    <li class="breadcrumb-item active">Personnel</li>
</ol>

<div class="btn-group mb-4" role="group">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create-personnel">New Personnel</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-resposive mb-4">
            <table class="table table-bordered">
                <thead>
                    <th>
                        Actions
                        <div id="table-spinner-personnel" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Designation</th>
                </thead>
                <tbody id="table-content-personnel"></tbody>
            </table>
        </div>

        <div id="table-links-personnel" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection

@section('modals')

<!-- New Personnel Modal -->
<div class="modal fade" id="modal-create-personnel" tabindex="-1" aria-labelledby="modal-label-create-personnel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_create-personnel">New Personnel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-personnel" data-model="personnel" class="ajax-submit" method="post" action="{{ url('/ajax/personnel') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-personnel-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create-personnel-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-personnel-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Username --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-personnel-username" class="col-12 col-lg-6 col-form-label"> 
                            Username
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create-personnel-username" name="username" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-personnel-username" data-field="username" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Password --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-personnel-password" class="col-12 col-lg-6 col-form-label"> 
                            Password
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="password" class="form-control" id="input-form-create-personnel-password" name="password" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-personnel-password" data-field="password" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-personnel-confirm_password" class="col-12 col-lg-6 col-form-label"> 
                            Confirm Password
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="password" class="form-control" id="input-form-create-personnel-confirm_password" name="confirm_password" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-personnel-confirm_password" data-field="confirm_password" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Designation --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-personnel-designation" class="col-12 col-lg-6 col-form-label"> 
                            Designation
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <select class="form-control" id="input-form-create-personnel-designation" name="designation" required>
                                <option value="" hidden selected>Select Designation ...</option>
                                <option value="manager">Manager</option>
                                <option value="cashier">Cashier</option>
                            </select>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-personnel-designation" data-field="designation" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-create-personnel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-create-personnel" type="submit" class="btn btn-primary" form="form-create-personnel">Create personnel</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Personnel Modal -->
<div class="modal fade" id="modal-edit-personnel" tabindex="-1" aria-labelledby="modal-label-edit-personnel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_edit-personnel">Edit Personnel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-spinner-edit-personnel" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form id="form-edit-personnel" data-model="personnel" class="ajax-submit" method="post" action="">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-personnel-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-edit-personnel-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-personnel-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Username --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-personnel-username" class="col-12 col-lg-6 col-form-label"> 
                            Username
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-edit-personnel-username" name="username" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-personnel-username" data-field="username" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Designation --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-personnel-designation" class="col-12 col-lg-6 col-form-label"> 
                            Designation
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <select class="form-control" id="input-form-edit-personnel-designation" name="designation" required>
                                <option id="form-edit-personnel-designation-none" value="" hidden selected>Select Designation ...</option>
                                <option id="form-edit-personnel-designation-manager" value="manager">Manager</option>
                                <option id="form-edit-personnel-designation-cashier" value="cashier">Cashier</option>
                            </select>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-personnel-designation" data-field="designation" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-edit-personnel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-edit-personnel" type="submit" class="btn btn-primary" form="form-edit-personnel">Update Personnel</button>
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

        personnelTable = new PaginationAjax({
            url: '/personnel',
            ajaxUrl: '/ajax/personnel',
            modelName: 'personnel',
            columns: [
                'name',
                'username',
                'designation',
            ],
            actions: [
                'edit',
                // 'delete',
            ]
        });

        request = personnelTable.requestData();
        processRequest(request, personnelTable);
    });
</script>
@endpush