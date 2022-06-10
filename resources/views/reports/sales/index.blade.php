@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Reports</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Administration</li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">Sales</li>
</ol>

<div class="btn-group mb-4" role="group">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-generate-sales-report">Generate Sales Report</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-resposive mb-4">
            <table class="table table-bordered">
                <thead>
                    <th>
                        Invoice ID
                        <div id="table-spinner-sales" style="display:none" class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Products Sold</th>
                    <th class="text-end">Total Sales</th>
                </thead>
                <tbody id="table-content-sales"></tbody>
            </table>
        </div>

        <div id="table-links-sales" class="btn-group mb-4" role="group"></div>
    </div>
</div>

@endsection

@section('modals')

<!-- Generate Sales Report Modal -->
<div class="modal fade" id="modal-generate-sales-report" tabindex="-1" aria-labelledby="modal-label-generate-sales-report" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_generate-sales-report">New Personnel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-generate-sales-report" method="get" action="{{ url('/reports/sales/pdf') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-generate-sales-report-date_from" class="col-12 col-lg-6 col-form-label"> 
                            Date From
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="date" class="form-control" id="input-form-generate-sales-report-date_from" name="date_from" value="{{ now()->subWeek()->format('Y-m-d') }}" required>
                        </div>
                        {{-- Error --}}
                        <p id="error-form-generate-sales-report-date_from" data-field="date_from" class="error text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>

                    {{-- Username --}}
                    <div class="form-group row mb-3">
                        {{-- Label --}}
                        <label for="input-form-generate-sales-report-date_to" class="col-12 col-lg-6 col-form-label"> 
                            Date To
                            <span class="text-danger ml-1">*</span>
                        </label>
                        {{-- Input --}}
                        <div class="col-12 col-lg-6">
                            <input type="date" class="form-control" id="input-form-generate-sales-report-date_to" name="date_to" required value="{{ now()->format('Y-m-d') }}">
                        </div>
                        {{-- Error --}}
                        <p id="error-form-generate-sales-report-date_to" data-field="date_to" class="error text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="close-form-generate-sales-report" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submit-form-generate-sales-report" type="submit" class="btn btn-primary" form="form-generate-sales-report">Generate Report</button>
            </div>
        </div>
    </div>
</div>

<!-- PDF Modal -->
<div class="modal fade" id="modal-generate-sales-pdf" tabindex="-1" aria-labelledby="modal-label-generate-sales-pdf" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-label_generate-sales-pdf">Generated Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="iframe-generate-sales-pdf" src="" frameborder="0" style="width:100%;height:65vh"></iframe>
            </div>
            <div class="modal-footer">
                <button id="close-form-generate-sales-pdf" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button id="submit-form-generate-sales-pdf" type="submit" class="btn btn-primary" form="form-generate-sales-report">Download Report</button> --}}
            </div>
        </div>
    </div>
</div>

@endsection

@push('app-scripts')
<script src="{{ url('/js/form-ajax-submit.js') }}"></script>
<script src="{{ url('/js/reports/pagination-ajax-sales.js') }}"></script>
<script>
    var salesTable;

    $(document).ready(function(){
        console.log("Creating a PaginationAjax instance.");;

        salesTable = new PaginationAjaxSales({
            url: '/reports/sales',
            ajaxUrl: '/ajax/reports/sales',
            modelName: 'sales',
            columns: [
                'id',
                'date',
                'customer_name',
                'products_sold',
                'total_sales',
            ],
            actions: []
        });

        request = salesTable.requestData();
        processSalesRequest(request, salesTable);
    });

    $(document).on('submit', '#form-generate-sales-report', function(e){
        e.preventDefault();
        $('.error').hide();
        $('#iframe-generate-sales-pdf').attr('src', 'about:blank');

        $('#submit-form-generate-sales-report').prop('disabled', true);

        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');

        request = $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
        });

        request.done(function(response){
            $('#modal-generate-sales-report').modal('hide');
            $('#iframe-generate-sales-pdf').attr('src', `${url}?${form.serialize()}`);
            $('#modal-generate-sales-pdf').modal('show');
        });

        request.fail(function(response){
            console.log(response);
            var errors = response.responseJSON.errors;
            $.each(errors, function(key, value){
                $('#error-form-generate-sales-report-' + key).text(value);
                $('#error-form-generate-sales-report-' + key).show();
            });
        });

        request.always(function(){
            $('#submit-form-generate-sales-report').removeAttr('disabled');
        });
    });
</script>
@endpush