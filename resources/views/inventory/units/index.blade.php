@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Units</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Inventory</li>
    <li class="breadcrumb-item active">Units</li>
</ol>

<div class="btn-group mb-4" role="group">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create-unit">New Unit</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-resposive mb-4">
            <table class="table table-bordered">
                <thead>
                    <th>
                        Actions
                        <div id="table-spinner-unit" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                </thead>
                <tbody id="table-content-unit"></tbody>
            </table>
        </div>

        <div id="table-links-unit" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection

@section('modals')
  
<!-- New Unit Modal -->
<div class="modal fade" id="modal-create-unit" tabindex="-1" aria-labelledby="modal-label-create-unit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_create-unit">New Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-unit" data-model="unit" class="ajax-submit" method="post" action="{{ url('/ajax/units') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-unit-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create-unit-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-unit-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Abbreviation --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create-unit-abbreviation" class="col-12 col-lg-6 col-form-label"> 
                            Abbreviation
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create-unit-abbreviation" name="abbreviation" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create-unit-abbreviation" data-field="abbreviation" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-create-unit" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-create-unit" type="submit" class="btn btn-primary" form="form-create-unit">Create Unit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Unit Modal -->
<div class="modal fade" id="modal-edit-unit" tabindex="-1" aria-labelledby="modal-label-edit-unit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label-edit-unit">Edit Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-spinner-edit-unit" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form id="form-edit-unit" data-model="unit" class="ajax-submit" method="post" action="">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-unit-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-edit-unit-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-unit-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Abbreviation --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-edit-unit-abbreviation" class="col-12 col-lg-6 col-form-label"> 
                            Abbreviation
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-edit-unit-abbreviation" name="abbreviation" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-edit-unit-abbreviation" data-field="abbreviation" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-edit-unit" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-edit-unit" type="submit" class="btn btn-primary" form="form-edit-unit">Update Unit</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete unit Modal -->
<div class="modal fade" id="modal-delete-unit" tabindex="-1" aria-labelledby="modal-label-delete-unit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label-delete-unit">Delete Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-spinner-delete-unit" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <form id="form-delete-unit" data-model="unit" class="ajax-submit" method="post" action="">
                    @csrf
                    @method('DELETE')
                    <p>Are you sure you want to delete <span id="form-delete-unit-name" class="text-danger"></span>?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-delete-unit" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Cancel</button>
                <button id="submit-form-delete-unit" type="submit" class="btn btn-danger" form="form-delete-unit">Yes, Delete unit</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('app-scripts')
<script src="{{ url('/js/form-ajax-submit.js') }}"></script>
<script src="{{ url('/js/pagination-ajax.js') }}"></script>
<script>
    var unitsTable;

    $(document).ready(function(){
        console.log("Creating a PaginationAjax instance.");;

        unitTable = new PaginationAjax({
            url: '/units',
            ajaxUrl: '/ajax/units',
            modelName: 'unit',
            columns: [
                'name',
                'abbreviation',
            ],
            actions: [
                'edit',
                'delete',
            ]
        });

        request = unitTable.requestData();
        processRequest(request, unitTable);
    });  
</script>
@endpush