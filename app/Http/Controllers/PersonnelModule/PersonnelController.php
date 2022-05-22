<?php

namespace App\Http\Controllers\PersonnelModule;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonnelModule\StorePersonnelRequest;
use App\Http\Requests\PersonnelModule\UpdatePersonnelRequest;
use Illuminate\Support\Facades\Auth;

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
        //
    }

    public function editAjax(User $user)
    {
        //
    }

    public function updateAjax(UpdatePersonnelRequest $request)
    {
        //
    }

    public function destroyAjax(User $user)
    {
        //
    }
}
