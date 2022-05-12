@extends('includes.new-template')

@section('content')

<h1 class="mt-4">Dashboard</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
</ol>

<div class="d-lg-flex justify-content-between mb-4 mb-lg-2">
    <p>You have successfully logged in, <strong>{{ Auth::user()->name }}</strong>! Your designation is <strong>{{ ucfirst(Auth::user()->designation) }}</strong>.</p>
</div>

{{-- MODALS --}}
{{-- @include('forms.source.create') --}}

@endsection
