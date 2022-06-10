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

@push('app-scripts')
<script src="{{ url('/js/form-ajax-submit.js') }}"></script>
<script src="{{ url('/js/reports/pagination-ajax-sales.js') }}"></script>
<script>
     var suppliersTable;

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
</script>
@endpush