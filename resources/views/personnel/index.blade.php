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
@push('app-scripts')
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
                // 'edit',
                // 'delete',
            ]
        });

        request = personnelTable.requestData();
        processRequest(request, personnelTable);
    });
</script>
@endpush