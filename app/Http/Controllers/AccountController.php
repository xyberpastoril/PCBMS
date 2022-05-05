<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountSettings\UpdateUsernameRequest;
use App\Http\Requests\AccountSettings\UpdatePasswordRequest;
use App\Models\User;

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