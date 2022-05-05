@extends('includes.template')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endpush

@section('content')

{{-- Alert Success --}}
<div id="alert-success" class="alert alert-success" style="display:none">
    <span id="alert-success-content" class="m-0"></span>
    <button type="button" class="btn-close" aria-label="Close"></button>
</div>

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
                                <p class="account-h2">Two Factor Authentication<span class="text-danger ml-3"><i>(Coming Soon)</i></span></p>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary w-100" disabled>Setup 2FA</button>
                            </div>
                        </div>
                        <p>
                            Two Factor Authentication adds an additional layer of protection to your account by asking for your password and a verification code from your authentication app of your choice.
                        </p>
                        
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
    </div>
</div>

<script src="/js/account/update_account.js"></script>

@endsection