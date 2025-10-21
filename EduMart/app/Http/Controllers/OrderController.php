<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's orders
     */
    public function index()
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show specific order
     */
    public function show(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product', 'user');

        return view('orders.show', compact('order'));
    }

    /**
     * Create new order from cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'total_amount' => $cartItems->sum('total_price'),
                'discount_amount' => 0, // Can be implemented later
                'final_amount' => $cartItems->sum('total_price'),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->current_price,
                    'total_price' => $cartItem->total_price,
                ]);

                // Update product sold count
                $cartItem->product->increment('sold_count', $cartItem->quantity);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Checkout page
     */
    public function checkout()
    {
        $cartItems = Cart::with('product.category')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum('total_price');

        return view('orders.checkout', compact('cartItems', 'total'));
    }

    /**
     * Download product file (for purchased products)
     */
    public function download(Order $order, Product $product)
    {
        // Ensure user owns this order and product is in the order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $orderItem = $order->orderItems()->where('product_id', $product->id)->first();
        
        if (!$orderItem) {
            abort(404, 'Product not found in this order');
        }

        if (!$product->file_path || !file_exists(storage_path('app/' . $product->file_path))) {
            abort(404, 'File not available');
        }

        return response()->download(storage_path('app/' . $product->file_path));
    }
}
