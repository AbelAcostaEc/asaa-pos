<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Inventory\Models\Batch;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Models\Transaction;
use Modules\Inventory\Models\TransactionDetail;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'unit')->orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $entries = Transaction::with(['supplier', 'details.product', 'details.batch'])
            ->where('type', 'purchase')
            ->latest()
            ->take(12)
            ->get();

        return view('inventory::index', compact('products', 'suppliers', 'entries'));
    }

    public function reportPurchases()
    {
        $purchases = Transaction::with(['supplier', 'details.product', 'details.batch'])
            ->where('type', 'purchase')
            ->latest()
            ->get();

        $total = $purchases->sum('total');

        return view('inventory::reports.purchases', compact('purchases', 'total'));
    }

    public function reportSales()
    {
        $sales = Transaction::with(['customer', 'details.product', 'details.batch'])
            ->where('type', 'sale')
            ->latest()
            ->get();

        $total = $sales->sum('total');

        return view('inventory::reports.sales', compact('sales', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id', 'required_without:create_supplier'],
            'create_supplier' => ['nullable', 'boolean'],
            'supplier_name' => ['nullable', 'string', 'max:255', 'required_if:create_supplier,1'],
            'supplier_phone' => ['nullable', 'string', 'max:20'],
            'supplier_email' => ['nullable', 'email', 'max:255'],
            'supplier_address' => ['nullable', 'string'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'purchase_date' => ['required', 'date'],
            'invoice_number' => ['nullable', 'string', 'max:255'],
            'batch_code' => ['nullable', 'string', 'max:255', Rule::unique('batches', 'code')],
            'observations' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $supplier = null;

            if (!empty($validated['create_supplier'])) {
                $supplier = Supplier::create([
                    'name' => $validated['supplier_name'],
                    'phone' => $validated['supplier_phone'] ?? null,
                    'email' => $validated['supplier_email'] ?? null,
                    'address' => $validated['supplier_address'] ?? null,
                ]);
            } elseif (!empty($validated['supplier_id'])) {
                $supplier = Supplier::find($validated['supplier_id']);
            }

            $product = Product::findOrFail($validated['product_id']);
            $quantity = (float) $validated['quantity'];
            $costPrice = (float) $validated['cost_price'];
            $subtotal = $quantity * $costPrice;

            $transaction = Transaction::create([
                'type' => 'purchase',
                'supplier_id' => $supplier?->id,
                'date' => $validated['purchase_date'],
                'total' => $subtotal,
                'taxes' => 0,
                'subtotal' => $subtotal,
                'observations' => $validated['observations'] ?? null,
                'invoice_number' => $validated['invoice_number'] ?? null,
            ]);

            $batch = Batch::create([
                'product_id' => $product->id,
                'code' => $validated['batch_code'] ?: $this->generateBatchCode($product),
                'initial_stock' => $quantity,
                'current_stock' => $quantity,
                'purchase_date' => $validated['purchase_date'],
                'cost_price' => $costPrice,
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'batch_id' => $batch->id,
                'quantity' => $quantity,
                'unit_price' => $costPrice,
                'subtotal' => $subtotal,
            ]);

            $product->increment('stock', $quantity);
        });

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Ingreso registrado correctamente. El stock se actualizo automaticamente.');
    }

    private function generateBatchCode(Product $product): string
    {
        return 'LOT-' . $product->id . '-' . now()->format('YmdHis') . '-' . str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
    }

    public function create() {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
