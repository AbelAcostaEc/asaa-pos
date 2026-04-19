<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::all();
        return view('inventory::units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['unit' => null]);
        }
        return view('inventory::units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:10',
        ]);

        $unit = Unit::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'unit' => $unit]);
        }

        return redirect()->route('inventory.units.index')->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        return view('inventory::units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit, Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['unit' => $unit]);
        }
        return view('inventory::units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:10',
        ]);

        $unit->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'unit' => $unit]);
        }

        return redirect()->route('inventory.units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit, Request $request)
    {
        $unit->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('inventory.units.index')->with('success', 'Unit deleted successfully.');
    }
}