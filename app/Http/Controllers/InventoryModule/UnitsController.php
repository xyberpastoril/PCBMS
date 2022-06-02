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
    
    public function searchTagifyAjax($query)
    {
        $units = Unit::select(
            'units.uuid as value',
            'units.id',
            'units.name',
            'units.abbreviation',
        )
        ->where('units.name', 'LIKE', "%{$query}%")
        ->orWhere('units.abbreviation', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get();

        return $units;
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

    public function editAjaxById($id)
    {
        return Unit::where('id', $id)->firstOrFail();
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
