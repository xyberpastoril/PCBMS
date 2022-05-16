@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Suppliers</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Inventory</li>
    <li class="breadcrumb-item active">Suppliers</li>
</ol>


<div class="card">
    <div class="card-body">
        <div class="table-resposive">
            <table class="table table-bordered">
                <thead>
                    <th>Name</th>
                    <th>Physical Address</th>
                    <th>Email Address</th>
                    <th>Mobile Number</th>
                </thead>
                <tbody id="table-content-suppliers">

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@endsection
