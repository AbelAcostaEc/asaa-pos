<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('inventory::suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('inventory::suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier = Supplier::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'supplier' => $supplier]);
        }

        return redirect()->route('inventory.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        return view('inventory::suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        if (request()->ajax()) {
            return response()->json(['supplier' => $supplier]);
        }

        return view('inventory::suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'supplier' => $supplier]);
        }

        return redirect()->route('inventory.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('inventory.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}