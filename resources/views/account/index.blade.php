@extends('includes.template')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endpush

@section('content')

{{-- Content --}}
<div class="card" class="content-card">
    <div class="card-body">
        <h1>Account Settings</h1>
        
        <hr>
        
        <div class="row">
            <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="card-content-grid-header-3">
                            <div class="card-content-header">
                                <p class="account-h2 m-0">Username</p>
                            </div>
                            <div class="card-content-value">
                                <p id="value_current-username" class="mt-1 mb-0">{{ Auth::user()->username }}</p>
                            </div>
                            <div class="card-content-btn">
                                <button type="button" class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modal-form_username">Edit Username</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="card-content-grid-header-3">
                            <div class="card-content-header">
                                <p class="account-h2 m-0">Password</p>
                            </div>
                            <div class="card-content-value">
                                <p id="value_current-password" class="mt-1 mb-0">Last updated: {{ (Auth::user()->password_updated_at ? \Carbon\Carbon::create(Auth::user()->password_updated_at)->diffForHumans() : 'Never') }}</p>
                            </div>
                            <div class="card-content-btn">
                                <button type="button" class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modal-form_password">Edit Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                
                
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="card-content-grid-header-2">
                            <div>
                                <p class="account-h2">Two Factor Authentication</p>
                            </div>
                            <div>
                                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                                    @csrf
                                    @if(!Auth::user()->two_factor_secret)
                                        <button type="submit" class="btn btn-sm btn-primary w-100">Setup 2FA</button>
                                    @else
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">Disable 2FA</button>
                                    @endif
                                </form>
                            </div>
                        </div>
                        @if(Auth::user()->two_factor_confirmed)
                            <p>You have Two Factor Authentication enabled.</p>
                            <button id="show-recovery-codes" type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-form_recovery_codes">
                                Show Recovery Codes
                            </button>
                        @elseif(auth()->user()->two_factor_secret)
                            <p>Validate 2FA by scanning the following QR Code and entering the authentication code</p>
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            <form action="{{ route('two-factor.confirm') }}" method="post" class="mt-3">
                                @csrf
                                <div class="input-group">
                                    <input name="code" class="form-control" required/>
                                    <button type="submit" class="btn btn-primary">Validate 2FA</button>
                                </div>
                            </form>
                        @else
                            <p>
                                Two Factor Authentication adds an additional layer of protection to your account by asking for your password and a verification code from your authentication app of your choice.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
{{-- New Username --}}
<div class="modal fade" id="modal-form_username" tabindex="-1" role="dialog" aria-labelledby="modal-form_username-label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form_username-label">Edit Username</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-form_username-spinner" class="spinner-border text-center p-5" role="status" style="display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <form id="form_username" data-update="username" class="account-update" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group row mb-3">
                        <label for="u_username" class="col-12 col-lg-6 col-form-label">New Username<span class="text-danger ml-1">*</span></label>
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="u_username" name="username" data-field="username" required>
                        </div>
                        <p id="err-form_username-username" data-field="username" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="u_username_confirm" class="col-12 col-lg-6 col-form-label">Confirm Username<span class="text-danger ml-1">*</span></label>
                        <div class="col-12 col-lg-6">
                            <input type="text" class="form-control" id="u_username_confirm" name="confirm_username" data-field="confirm_username" required>
                        </div>
                        <p id="err-form_username-confirm_username" data-field="confirm_username"  class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="u_username_password" class="col-12 col-lg-6 col-form-label">Confirm Password<span class="text-danger ml-1">*</span></label>
                        <div class="col-12 col-lg-6">
                            <input type="password" class="form-control" id="u_username_password" name="confirm_password" data-field="confirm_password" required>
                        </div>
                        <p id="err-form_username-confirm_password" data-field="confirm_password"  class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btn_close-form_username" form="form_username" data-close="1" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btn_submit-form_username" form="form_username" data-submit="1">Update Username</button>
            </div>
        </div>
    </div>
</div>

{{-- Update Password --}}
<div class="modal fade" id="modal-form_password" tabindex="-1" role="dialog" aria-labelledby="modal-form_password-label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form_password-label">Edit Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-form_password-spinner" class="spinner-border text-center p-5" role="status" style="display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <form id="form_password" data-update="password" data-issensitive="1" class="account-update" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group row mb-3">
                        <label for="u_password_old" class="col-12 col-lg-6 col-form-label">Old Password<span class="text-danger ml-1">*</span></label>
                        <div class="col-12 col-lg-6">
                            <input type="password" class="form-control" id="u_password_old" name="old_password" data-field="old_password" required>
                        </div>
                        <p id="err-form_password-old_password" data-field="old_password"  class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="u_password_new" class="col-12 col-lg-6 col-form-label">New Password<span class="text-danger ml-1">*</span></label>
                        <div class="col-12 col-lg-6">
                            <input type="password" class="form-control" id="u_password_new" name="new_password" data-field="new_password" required>
                        </div>
                        <p id="err-form_password-new_password" data-field="new_password"  class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="u_password_confirm" class="col-12 col-lg-6 col-form-label">Confirm New Password<span class="text-danger ml-1">*</span></label>
                        <div class="col-12 col-lg-6">
                            <input type="password" class="form-control" id="u_password_confirm" name="confirm_new_password" data-field="confirm_new_password" required>
                        </div>
                        <p id="err-form_password-confirm_new_password" data-field="confirm_new_password"  class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btn_close-form_password" data-bs-dismiss="modal" data-close="1" form="form_password">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btn_submit-form_password" form="form_password" data-submit="1">Update Password</button>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Password --}}
<div class="modal fade" id="modal-form_recovery_codes" tabindex="-1" role="dialog" aria-labelledby="modal-form_recovery_codes-label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-form_recovery_codes-label">Recovery Codes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modal-form_recovery_codes-body" class="modal-body">
                <div id="modal-form_recovery_codes-spinner" class="spinner-border text-center p-5" role="status" style="display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <form id="form_recovery_codes" method="post">
                    @csrf
                    <p>{{ __('Please confirm your password before continuing.') }}</p>
                    <div class="form-group row mb-3">
                        <label for="c_password_confirm" class="col-12 col-lg-6 col-form-label">Confirm Password<span class="text-danger ml-1">*</span></label>
                        <div class="col-12 col-lg-6">
                            <input type="password" class="form-control" id="c_password_confirm" name="confirm_password" required>
                        </div>
                        <p id="err-form_password-confirm_password" class="text-danger col-12 mt-1 mb-0" style="display:none"></p>
                    </div>
                    
                </form>
            </div>
            <div id="modal-form_recovery_codes-footer" class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btn_close-form_recovery_codes" data-bs-dismiss="modal" form="form_recovery_codes">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btn_submit-form_recovery_codes" form="form_recovery_codes">Confirm Password</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="/js/account/update_account.js"></script>
<script>
    $("#form_recovery_codes").submit(function(e){
        e.preventDefault();

        btn_submit = $("#btn_submit-form_recovery_codes");

        disableButton(btn_submit);
        $("#err-form_password-confirm_password").hide();

        // Create request
        var request = $.ajax({
            url: `/ajax/account/show/recoverycodes`,
            method: "POST",
            data: $(`#form_recovery_codes`).serialize()
        });

        // If request has successfully processed.
        request.done(function(res, status, jqXHR) {
            console.log("Request successful.");
            console.log(res);
            $("#form_recovery_codes").hide();
            $("#modal-form_recovery_codes-footer").hide();

            let inner = '<p>Kindly save these codes to a secure location.</p>';
            inner += res;
            $("#modal-form_recovery_codes-body").append(inner);
        });

        // If request has errors (including validation errors).
        request.fail(function(jqXHR, status, error){
            console.log("Request failed.");
            console.log(jqXHR);

            $("#err-form_password-confirm_password").show().html(jqXHR.responseJSON.errors.confirm_password[0]);
        });
    });
</script>
@endpush