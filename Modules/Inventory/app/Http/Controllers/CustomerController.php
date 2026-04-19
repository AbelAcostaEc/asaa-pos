<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('inventory::customers.index', compact('customers'));
    }

    public function create()
    {
        return view('inventory::customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'customer' => $customer]);
        }

        return redirect()->route('inventory.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('inventory::customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        if (request()->ajax()) {
            return response()->json(['customer' => $customer]);
        }

        return view('inventory::customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $customer->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'customer' => $customer]);
        }

        return redirect()->route('inventory.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('inventory.customers.index')->with('success', 'Customer deleted successfully.');
    }
}