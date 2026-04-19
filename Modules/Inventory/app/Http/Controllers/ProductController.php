<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'unit')->get();
        $categories = Category::all()->pluck('name', 'id');
        $units = Unit::all()->pluck('name', 'id');
        return view('inventory::products.index', compact('products', 'categories', 'units'));
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        $units = Unit::all();
        if ($request->ajax()) {
            return response()->json(['product' => null, 'categories' => $categories, 'units' => $units]);
        }
        return view('inventory::products.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|url',
        ]);

        $product = Product::create($request->only([
            'name',
            'code',
            'category_id',
            'unit_id',
            'price',
            'image',
        ]));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'product' => $product]);
        }

        return redirect()->route('inventory.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product, Request $request)
    {
        $categories = Category::all();
        $units = Unit::all();
        if ($request->ajax()) {
            return response()->json(['product' => $product, 'categories' => $categories, 'units' => $units]);
        }
        return view('inventory::products.edit', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|url',
        ]);

        $product->update($request->only([
            'name',
            'code',
            'category_id',
            'unit_id',
            'price',
            'image',
        ]));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'product' => $product]);
        }

        return redirect()->route('inventory.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product, Request $request)
    {
        $product->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('inventory.products.index')->with('success', 'Product deleted successfully.');
    }

    public function catalog()
    {
        $products = Product::with('category', 'unit')->where('stock', '>', 0)->get();
        return view('inventory::products.catalog', compact('products'));
    }
}
