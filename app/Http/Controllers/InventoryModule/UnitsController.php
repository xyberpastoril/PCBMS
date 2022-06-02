<?php

namespace App\Http\Controllers\InventoryModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryModule\Unit\StoreUnitRequest;
use App\Http\Requests\InventoryModule\Unit\UpdateUnitRequest;
use App\Models\Unit;

class UnitsController extends Controller
{
    public function index()
    {
        return view('inventory.units.index');
    }

    public function showRowsAjax()
    {
        $units = Unit::select(
            'uuid',
            'name',
            'abbreviation',
        );

        return $units->paginate(10);
    }

    public function storeAjax(StoreUnitRequest $request)
    {
        $validated = $request->validated();

        Unit::create([
            'name' => $validated['name'],
            'abbreviation' => $validated['abbreviation'],
        ]);

        return 'Unit successfully added.';
    }

    public function editAjax(Unit $unit)
    {
        return $unit;
    }

    public function updateAjax(UpdateUnitRequest $request, Unit $unit)
    {
        $validated = $request->validated();

        $unit->update([
            'name' => $validated['name'],
            'abbreviation' => $validated['abbreviation'],
        ]);

        return 'Unit successfully updated.';
    }

    public function destroyAjax(Unit $unit)
    {
        $unit->delete();

        return 'Unit successfully deleted.';
    }
}
