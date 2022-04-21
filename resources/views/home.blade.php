@extends('includes.template')

@section('content')

<div class="d-lg-flex justify-content-between mb-4 mb-lg-2">
    <p>You have successfully logged in, <strong>{{ Auth::user()->name }}</strong>! Your designation is <strong>{{ ucfirst(Auth::user()->designation) }}</strong>.</p>
</div>

{{-- MODALS --}}
{{-- @include('forms.source.create') --}}

@endsection
