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
                            Please enter your authentication code to login.
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form method="POST" action="{{ url('/two-factor-challenge') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label id="code-label" class="form-label">Authentication Code</label>
                                        <input id="code" class="form-control form-control-lg" type="text" name="code"
                                            placeholder="Enter your authentication code" />
                                        <a id="toggle-code-methods" class="btn btn-link" href="javascript:void(0)">
                                            {{ __('Don\'t have access to authentication app?') }}
                                        </a>
                                        @error('code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        @error('recovery_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="text-center mt-3">
                                        {{-- <a href="index.html" class="btn btn-lg btn-primary">Sign in</a> --}}
                                        <button type="submit" class="btn btn-lg btn-primary w-100">
                                            {{ __('Submit Code') }}
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

@push('app-scripts')
<script>
    var isRecovery = 0;
    var codeLabelElement = document.getElementById('code-label');
    var codeInputElement = document.getElementById('code');
    var codeInputToggle = document.getElementById('toggle-code-methods');
    codeInputToggle.addEventListener('click', toggleCodeMethod)

    function toggleCodeMethod()
    {
        console.log(codeInputElement.name);
        if(isRecovery) {
            codeInputToggle.innerText = "Enter a recovery code instead?";
            codeInputElement.name = "code";
            codeLabelElement.innerText = "Authentication Code";
            codeInputElement.placeholder = "Enter your authentication code";
            isRecovery--;
        }
        else {
            codeInputToggle.innerText = "Have access to authentication app?";
            codeInputElement.name = "recovery_code";
            codeInputElement.placeholder = "Enter your recovery code";
            codeLabelElement.innerText = "Recovery Code";
            isRecovery++;
        }
    }

    // $("#form_two_factor").submit(function(e) {
    //     e.preventDefault();

    //     // Create request
    //     var request = $.ajax({
    //         url: `/two-factor-challenge`,
    //         method: "POST",
    //         data: $(`#form_two_factor`).serialize()
    //     });

    //     // If request has successfully processed.
    //     request.done(function(res, status, jqXHR) {
    //         console.log("Request successful.");
    //         console.log(res);
    //     });

    //     // If request has errors (including validation errors).
    //     request.fail(function(jqXHR, status, error){
    //         console.log("Request failed.");
    //         console.log(jqXHR);
    //     });

    //     // The following always executes regardless of status.
    //     request.always(function(){
            
    //     });
    // });
</script>
@endpush