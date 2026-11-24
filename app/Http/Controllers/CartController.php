<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CartItem;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $this->getCartItems($request);

        $subtotal = 0;
        $totalWeight = 0;
        $suppliersCount = [];

        foreach ($cartItems as $item) {
            $unitPrice = $item->product->getEffectivePriceForQuantity($item->quantity);
            $subtotal += $unitPrice * $item->quantity;
            $totalWeight += ($item->product->weight_kg ?? 1) * $item->quantity;
            $suppliersCount[$item->product->supplier_id] = true;
        }

        // Estimated international freight based on weight and number of supplier origins
        $estimatedShipping = count($suppliersCount) * 150 + ($totalWeight * 2.5);
        $estimatedDuty = $subtotal * 0.025; // average 2.5% industrial duty
        $estimatedVat = ($subtotal + $estimatedShipping + $estimatedDuty) * 0.20; // UK VAT 20%
        $estimatedTotal = $subtotal + $estimatedShipping + $estimatedDuty + $estimatedVat;

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'totalWeight',
            'suppliersCount',
            'estimatedShipping',
            'estimatedDuty',
            'estimatedVat',
            'estimatedTotal'
        ));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->price_on_request || !$product->direct_purchase_enabled) {
            return back()->with('error', 'This product requires a quotation or enquiry and cannot be added directly to cart.');
        }

        $qty = max($validated['quantity'], $product->moq);
        $unitPrice = $product->getEffectivePriceForQuantity($qty);

        if (Auth::check()) {
            $existing = CartItem::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($existing) {
                $existing->quantity += $qty;
                $existing->unit_price = $product->getEffectivePriceForQuantity($existing->quantity);
                $existing->save();
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                ]);
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $qty;
                $cart[$product->id]['unit_price'] = $product->getEffectivePriceForQuantity($cart[$product->id]['quantity']);
            } else {
                $cart[$product->id] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                ];
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', "{$product->name} added to your procurement cart.");
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
            $cartItem->quantity = max($validated['quantity'], $cartItem->product->moq);
            $cartItem->unit_price = $cartItem->product->getEffectivePriceForQuantity($cartItem->quantity);
            $cartItem->save();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $validated['quantity'];
                session()->put('cart', $cart);
            }
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request, $id)
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->where('id', $id)->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    protected function getCartItems(Request $request)
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->with(['product.company', 'product.supplier'])->get();
        }

        $sessionCart = session()->get('cart', []);
        $items = collect();

        foreach ($sessionCart as $pId => $data) {
            $product = Product::with(['company', 'supplier'])->find($pId);
            if ($product) {
                $item = new CartItem();
                $item->id = $pId;
                $item->product_id = $product->id;
                $item->quantity = $data['quantity'];
                $item->unit_price = $data['unit_price'];
                $item->setRelation('product', $product);
                $items->push($item);
            }
        }

        return $items;
    }
}
