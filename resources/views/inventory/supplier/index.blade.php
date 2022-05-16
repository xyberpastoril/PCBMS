@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Suppliers</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Inventory</li>
    <li class="breadcrumb-item active">Suppliers</li>
</ol>

<div class="btn-group mb-4" role="group">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create_supplier">New Supplier</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-resposive mb-4">
            <table class="table table-bordered">
                <thead>
                    <th>Actions
                        <div id="table-spinner-suppliers" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>Name</th>
                    <th>Physical Address</th>
                    <th>Email Address</th>
                    <th>Mobile Number</th>
                </thead>
                <tbody id="table-content-suppliers"></tbody>
            </table>
        </div>

        <div id="table-links-suppliers" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection

@section('modals')
  
<!-- New Supplier Modal -->
<div class="modal fade" id="modal-create_supplier" tabindex="-1" aria-labelledby="modal-label_create_supplier" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_create_supplier">New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-create_supplier" class="ajax-submit" method="post" action="{{ url('/ajax/suppliers') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create_supplier-name" class="col-12 col-lg-6 col-form-label"> 
                            Name
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="input-form-create_supplier-name" name="name" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create_supplier-name" data-field="name" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Physical Address --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create_supplier-physical_address" class="col-12 col-lg-6 col-form-label"> 
                            Location
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-create_supplier-physical_address" name="physical_address" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create_supplier-physical_address" data-field="physical_address" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Mobile Number --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create_supplier-mobile_number" class="col-12 col-lg-6 col-form-label"> 
                            Mobile Number
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-create_supplier-mobile_number" name="mobile_number" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create_supplier-mobile_number" data-field="mobile_number" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Email --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-create_supplier-email_address" class="col-12 col-lg-6 col-form-label"> 
                            Email Address
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="email" class="form-control" id="input-form-create_supplier-email_address" name="email_address">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-create_supplier-email_address" data-field="email_address" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-create_supplier" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-create_supplier" type="submit" class="btn btn-primary" form="form-create_supplier">Create Supplier</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('app-scripts')
<script src="{{ url('/js/form-ajax-submit.js') }}"></script>
<script>
    $(document).ready(function(){
        console.log(`Creating a GET request to load suppliers.`);
        var request = requestSupplierList('/ajax/suppliers/');
    });

    function requestSupplierList(url)
    {
        $("#table-spinner-suppliers").show();
        $(".btn-paginate").attr('disabled', true);

        var request =  $.ajax({
            url: url,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            method: 'GET',
        });

        request.done(function(res, status, jqXHR) {
            console.log(`GET request to load suppliers has successfully been made.`);
            console.log(res);

            // Print data to table
            displaySupplierTableRows(res.data);
            displaySupplierPaginationButtons(res.links);

            $(".btn-paginate").click(function(e){
                console.log("Pagintate button has been clicked.");
                console.log(e.target.dataset.href);

                var request = requestSupplierList(e.target.dataset.href);
            });
        });

        request.fail(function(jqXHR, status, error) {
            console.log(`GET request to load suppliers has failed.`);
            console.log(jqXHR);

            generateToast(jqXHR.responseJSON.message, 'bg-danger');
        });

        request.always(function(){
            $("#table-spinner-suppliers").hide();
        });

        return request;
    }

    function displaySupplierTableRows(rows)
    {
        $(`#table-content-suppliers`).html("");

        var inner = '';
        rows.forEach(function(row){           
            inner += `
            <tr>
                <td>
                    ${row.uuid}
                </td>
                <td>${row.name}</td>
                <td>${row.physical_address}</td>
                <td>${row.email}</td>
                <td>${row.mobile_number}</td>
            </tr>
            `;
        });

        $(`#table-content-suppliers`).append(inner);
    }

    function displaySupplierPaginationButtons(links)
    {
        $("#table-links-suppliers").html("");

        var inner = '';
        links.forEach(function(link) {
            inner += `
                <button type="button" class="btn btn-${link.active ? '' : 'outline-'}primary btn-paginate" data-href="${link.url}" ${link.url == null ? 'disabled=true' : ''}>${link.label}</button>
            `;
        });

        $("#table-links-suppliers").append(inner);
    }
</script>
@endpush