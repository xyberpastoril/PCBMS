@extends('includes.auth-template')

@section('content')
<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            {{-- <div class="d-none d-lg-flex col-lg-8">
                <img class="mb-3" src="{{ asset('img/VSU Logo.png') }}" width="auto" height="50px">
                <p class="m-0" style="font-size:24px">Office of the Vice President of Research, Extension, and Innovation</p>
                <p class="m-0 display-5">Pasalubong Center Business Management System</p>
            </div> --}}
            <div class="col-sm-10 col-md-8 col-lg-4 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4">
                        <img class="mb-3" src="{{ asset('img/VSU Logo.png') }}" width="auto" height="50px"><br>
                        <small>Office of the Vice President of Research, Extension, and Innovation</small>
                        <h1 class="h3">Pasalubong Center Business Management System</h1>
                        <br>
                        <p class="lead">
                            Please confirm your password before continuing.
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form method="POST" action="{{ url('/user/confirm-password/') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input class="form-control form-control-lg" type="password" name="password"
                                            placeholder="Enter your password" />
                                        {{-- <small>
                                            <a href="index.html">Forgot password?</a>
                                        </small> --}}
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="text-center mt-3">
                                        {{-- <a href="index.html" class="btn btn-lg btn-primary">Sign in</a> --}}
                                        <button type="submit" class="btn btn-lg btn-primary w-100">
                                            {{ __('Confirm Password') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</main>
@endsection
