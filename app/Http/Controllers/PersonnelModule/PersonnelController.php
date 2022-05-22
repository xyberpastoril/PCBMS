<?php

namespace App\Http\Controllers\PersonnelModule;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonnelModule\StorePersonnelRequest;
use App\Http\Requests\PersonnelModule\UpdatePersonnelRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PersonnelController extends Controller
{
    public function index()
    {
        return view('personnel.index');
    }

    public function showRowsAjax()
    {
        $personnel = User::select(
            'users.uuid',
            'users.name',
            'users.username',
            'users.designation',
        )
        ->where('users.id', '!=', Auth::id()) // Current admin excluded.
        ->where('users.id', '!=', 1); // Super Admin excluded.

        return $personnel->paginate(10);
    }

    public function storeAjax(StorePersonnelRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'username' => $validated['name'],
            'password' => Hash::make($validated['password']),
            'designation' => $validated['designation'],
        ]);

        return 'Personnel successfully added.';
    }

    public function editAjax(User $user)
    {
        return $user;
    }

    public function updateAjax(UpdatePersonnelRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'designation' => $validated['designation'],
        ]);

        return 'Personnel successfully updated.';
    }

    public function destroyAjax(User $user)
    {
        //
    }
}
