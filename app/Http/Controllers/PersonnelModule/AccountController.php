<?php

namespace App\Http\Controllers\PersonnelModule;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountSettings\UpdateUsernameRequest;
use App\Http\Requests\AccountSettings\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Your Account Page
     */
    public function index()
    {
        return view('account.index');
    }

    /**
     * Showing Info
     */
    public function showRecoveryCodes()
    {
        foreach(Auth::user()->recoveryCodes() as $code)
            echo $code . '<br>';
    }

    /**
     * Updating Info from Account Settings
     */

    public function updateUsername(UpdateUsernameRequest $request)
    {
        $validated = $request->validated();

        return User::find(Auth::user()->id)->update([
            'username' => $validated['username'],
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        return User::find(Auth::user()->id)->update([
            'password' => Hash::make($validated['new_password']),
            'password_updated_at' => now(),
        ]);
    }
}