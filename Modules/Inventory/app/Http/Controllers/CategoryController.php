<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('inventory::categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['category' => null]);
        }
        return view('inventory::categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        $category = Category::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'category' => $category]);
        }

        return redirect()->route('inventory.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category, Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['category' => $category]);
        }
        return view('inventory::categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        $category->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'category' => $category]);
        }

        return redirect()->route('inventory.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category, Request $request)
    {
        $category->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('inventory.categories.index')->with('success', 'Category deleted successfully.');
    }
}