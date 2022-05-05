@extends('includes.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Two Factor Authentication') }}</div>

                <div class="card-body">
                    {{ __('Please enter your authentication code to login.') }}

                    <form method="POST" action="{{ route('two-factor.login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label id="code-label" for="code" class="col-md-4 col-form-label text-md-end">{{ __('Code') }}</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autocomplete="code">

                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>

                                <a id="toggle-code-methods" class="btn btn-link" href="javascript:void(0)">
                                    {{ __('Don\'t have access to authentication app?') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
            isRecovery--;
        }
        else {
            codeInputToggle.innerText = "Have access to authentication app?";
            codeInputElement.name = "recovery_code";
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