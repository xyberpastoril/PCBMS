<?php

namespace App\Http\Controllers\PersonnelModule;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonnelModule\StorePersonnelRequest;
use App\Http\Requests\PersonnelModule\UpdatePersonnelRequest;

class PersonnelController extends Controller
{
    public function index()
    {
        return view('personnel.index');
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
