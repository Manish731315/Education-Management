<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Access denied. Admin privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('final_amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'active_products' => Product::where('is_active', true)->count(),
        ];

        // Recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top selling products
        $topProducts = Product::with('category')
            ->orderBy('sold_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly revenue chart data (MySQL optimized)
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(final_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'monthlyRevenue'));
    }

    /**
     * Users management
     */
    public function users()
    {
        $users = User::withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Analytics page
     */
    public function analytics()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('final_amount'),
        ];

        // Sales by category
        $salesByCategory = Category::withCount(['products as total_sales' => function ($query) {
            $query->join('order_items', 'products.id', '=', 'order_items.product_id')
                  ->join('orders', 'order_items.order_id', '=', 'orders.id')
                  ->where('orders.payment_status', 'paid');
        }])->get();

        // Monthly sales data (MySQL optimized)
        $monthlySales = Order::where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as orders, SUM(final_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.analytics', compact('stats', 'salesByCategory', 'monthlySales'));
    }
}
