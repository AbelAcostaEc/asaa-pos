<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Customer;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Transaction;
use Modules\Inventory\Models\TransactionDetail;
use Modules\Inventory\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PosController extends Controller
{
    private function normalizeCart(array $cart): array
    {
        $normalizedCart = [];

        foreach ($cart as $key => $item) {
            $normalizedItem = $this->normalizeCartItem($key, $item);

            if ($normalizedItem !== null) {
                $normalizedCart[$normalizedItem['cart_key']] = $normalizedItem;
            }
        }

        return $normalizedCart;
    }

    private function normalizeCartItem($key, $item): ?array
    {
        if (!is_array($item)) {
            return null;
        }

        $productId = $item['product_id'] ?? null;
        $name = $item['name'] ?? null;
        $price = isset($item['price']) ? (float) $item['price'] : null;
        $quantity = isset($item['quantity']) ? (float) $item['quantity'] : 0;
        $description = $item['description'] ?? null;

        if ((!$productId || !$name || $price === null) && isset($item['product'])) {
            $legacyProduct = $item['product'];

            if (is_array($legacyProduct)) {
                $productId = $productId ?? ($legacyProduct['id'] ?? null);
                $name = $name ?? ($legacyProduct['name'] ?? null);
                $price = $price ?? (isset($legacyProduct['price']) ? (float) $legacyProduct['price'] : null);
            } elseif (is_object($legacyProduct)) {
                $productId = $productId ?? ($legacyProduct->id ?? null);
                $name = $name ?? ($legacyProduct->name ?? null);
                $price = $price ?? (isset($legacyProduct->price) ? (float) $legacyProduct->price : null);
            }
        }

        $productId = $productId ?: (is_numeric($key) ? (int) $key : null);
        $hasProduct = $productId !== null;

        if (!$name || $price === null || $quantity <= 0) {
            return null;
        }

        $cartKey = $hasProduct
            ? $productId
            : 'service-' . md5($name . '|' . $price . '|' . ($description ?? ''));

        return [
            'cart_key' => $cartKey,
            'product_id' => $productId ? (int) $productId : null,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => isset($item['subtotal']) ? (float) $item['subtotal'] : $price * $quantity,
        ];
    }

    public function index()
    {
        $customers = Customer::all();
        $products = Product::with('category', 'unit')->where('stock', '>', 0)->get();
        $cart = $this->normalizeCart(Session::get('cart', []));
        Session::put('cart', $cart);

        return view('inventory::pos.index', compact('customers', 'products', 'cart'));
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = $this->normalizeCart(Session::get('cart', []));
        $quantity = (float) $request->quantity;
        $existingQuantity = isset($cart[$product->id]) ? (float) $cart[$product->id]['quantity'] : 0;
        $newQuantity = $existingQuantity + $quantity;

        if ($newQuantity > (float) $product->stock) {
            return redirect()
                ->route('inventory.pos.index')
                ->withErrors([
                    'cart' => [__('inventory::ui.pos.error_insufficient_stock', ['product' => $product->name, 'stock' => (float) $product->stock])],
                ])
                ->withInput();
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'quantity' => $newQuantity,
            'subtotal' => (float) $product->price * $newQuantity,
        ];

        Session::put('cart', $cart);

        return redirect()->route('inventory.pos.index')->with('success', __('inventory::ui.pos.success_added'));
    }

    public function addService(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'service_description' => 'nullable|string|max:500',
            'service_price' => 'required|numeric|min:0.01',
            'quantity' => 'required|numeric|min:1',
        ]);

        $cart = $this->normalizeCart(Session::get('cart', []));
        $serviceName = $request->service_name;
        $servicePrice = (float) $request->service_price;
        $quantity = (float) $request->quantity;
        $description = $request->service_description;
        $cartKey = 'service-' . md5($serviceName . '|' . $servicePrice . '|' . ($description ?? ''));
        $existingQuantity = isset($cart[$cartKey]) ? (float) $cart[$cartKey]['quantity'] : 0;
        $newQuantity = $existingQuantity + $quantity;

        $cart[$cartKey] = [
            'cart_key' => $cartKey,
            'product_id' => null,
            'name' => $serviceName,
            'description' => $description,
            'price' => $servicePrice,
            'quantity' => $newQuantity,
            'subtotal' => $servicePrice * $newQuantity,
        ];

        Session::put('cart', $cart);

        return redirect()->route('inventory.pos.index')->with('success', __('inventory::ui.pos.success_service_added'));
    }

    public function removeProduct(Request $request)
    {
        $cart = $this->normalizeCart(Session::get('cart', []));
        $cartKey = $request->input('cart_key', $request->input('product_id'));

        if ($cartKey !== null && isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
        }

        Session::put('cart', $cart);

        return redirect()->route('inventory.pos.index')->with('success', __('inventory::ui.pos.success_removed'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_mode' => ['required', Rule::in(['final', 'existing', 'new'])],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string'],
        ]);

        if ($request->customer_mode === 'existing' && !$request->filled('customer_id')) {
            throw ValidationException::withMessages([
                'customer_id' => [__('inventory::ui.pos.error_select_customer')],
            ]);
        }

        if ($request->customer_mode === 'new' && !$request->filled('customer_name')) {
            throw ValidationException::withMessages([
                'customer_name' => [__('inventory::ui.pos.error_customer_name_required')],
            ]);
        }

        $cart = $this->normalizeCart(Session::get('cart', []));
        Session::put('cart', $cart);

        if (empty($cart)) {
            return redirect()->route('inventory.pos.index')->with('error', __('inventory::ui.pos.error_cart_empty'));
        }

        try {
            DB::transaction(function () use ($request, $cart) {
                $customerId = null;

                if ($request->customer_mode === 'existing') {
                    $customerId = (int) $request->customer_id;
                }

                if ($request->customer_mode === 'new') {
                    $customer = Customer::create([
                        'name' => $request->customer_name,
                        'phone' => $request->customer_phone,
                        'email' => $request->customer_email,
                        'address' => $request->customer_address,
                    ]);

                    $customerId = $customer->id;
                }

                $total = array_sum(array_column($cart, 'subtotal'));
                $transaction = Transaction::create([
                    'type' => 'sale',
                    'customer_id' => $customerId,
                    'date' => now(),
                    'total' => $total,
                    'taxes' => 0,
                    'subtotal' => $total,
                    'invoice_number' => 'INV-' . time(),
                ]);

                foreach ($cart as $item) {
                    if (isset($item['product_id']) && $item['product_id'] !== null) {
                        $product = Product::findOrFail($item['product_id']);
                        $remainingQuantity = (float) $item['quantity'];

                        $batches = Batch::where('product_id', $product->id)
                            ->where('current_stock', '>', 0)
                            ->orderByRaw('purchase_date IS NULL')
                            ->orderBy('purchase_date')
                            ->orderBy('id')
                            ->lockForUpdate()
                            ->get();

                        $availableStock = (float) $batches->sum('current_stock');

                        if ($availableStock < $remainingQuantity) {
                            throw ValidationException::withMessages([
                                'cart' => [__('inventory::ui.pos.error_insufficient_stock', ['product' => $product->name, 'stock' => $availableStock])],
                            ]);
                        }

                        foreach ($batches as $batch) {
                            if ($remainingQuantity <= 0) {
                                break;
                            }

                            $takenQuantity = min($remainingQuantity, (float) $batch->current_stock);

                            if ($takenQuantity <= 0) {
                                continue;
                            }

                            TransactionDetail::create([
                                'transaction_id' => $transaction->id,
                                'product_id' => $product->id,
                                'batch_id' => $batch->id,
                                'quantity' => $takenQuantity,
                                'unit_price' => $product->price,
                                'subtotal' => $takenQuantity * (float) $product->price,
                                'name' => $product->name,
                            ]);

                            $batch->current_stock = (float) $batch->current_stock - $takenQuantity;
                            $batch->save();

                            $remainingQuantity -= $takenQuantity;
                        }

                        $product->stock = (float) $product->stock - (float) $item['quantity'];
                        $product->save();
                    } else {
                        TransactionDetail::create([
                            'transaction_id' => $transaction->id,
                            'product_id' => null,
                            'batch_id' => null,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['price'],
                            'subtotal' => $item['subtotal'],
                            'name' => $item['name'],
                            'description' => $item['description'] ?? null,
                        ]);
                    }
                }
            });
        } catch (ValidationException $e) {
            return redirect()
                ->route('inventory.pos.index')
                ->withErrors($e->errors())
                ->withInput();
        }

        Session::forget('cart');

        return redirect()->route('inventory.pos.index')->with('success', __('inventory::ui.pos.success_checkout'));
    }
}
