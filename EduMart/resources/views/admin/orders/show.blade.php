<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->order_number }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Details</h3>
                            
                            <!-- Order Status -->
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Order Status</h4>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Payment Status</h4>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h4>
                                <div class="space-y-4">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0">
                                                <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://via.placeholder.com/100x100?text=No+Image' }}" 
                                                     alt="{{ $item->product->title }}" 
                                                     class="w-20 h-20 object-cover rounded-md">
                                            </div>

                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">
                                                <h5 class="text-lg font-semibold text-gray-900">
                                                    {{ $item->product->title }}
                                                </h5>
                                                <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                                <p class="text-sm text-gray-500">{{ ucfirst($item->product->type) }}</p>
                                                
                                                <div class="flex items-center space-x-4 mt-2">
                                                    <span class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</span>
                                                    <span class="text-sm text-gray-600">Unit Price: ${{ number_format($item->unit_price, 2) }}</span>
                                                </div>
                                            </div>

                                            <!-- Item Total -->
                                            <div class="text-right">
                                                <div class="text-lg font-bold text-gray-900">
                                                    ${{ number_format($item->total_price, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Notes -->
                            @if($order->notes)
                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Order Notes</h4>
                                    <p class="text-gray-700">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary & Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                            
                            <!-- Order Info -->
                            <div class="space-y-3 mb-6">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Order Number</span>
                                    <p class="text-sm text-gray-900">{{ $order->order_number }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Customer</span>
                                    <p class="text-sm text-gray-900">{{ $order->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Order Date</span>
                                    <p class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Payment Method</span>
                                    <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                </div>
                                @if($order->transaction_id)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Transaction ID</span>
                                        <p class="text-sm text-gray-900">{{ $order->transaction_id }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Pricing Breakdown -->
                            <div class="border-t border-gray-200 pt-4 mb-6">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="font-medium">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    @if($order->discount_amount > 0)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Discount</span>
                                            <span class="font-medium text-green-600">-${{ number_format($order->discount_amount, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tax</span>
                                        <span class="font-medium">$0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Shipping</span>
                                        <span class="font-medium">Free</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-2">
                                        <div class="flex justify-between">
                                            <span class="text-lg font-semibold text-gray-900">Total</span>
                                            <span class="text-lg font-bold text-gray-900">${{ number_format($order->final_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Update Form -->
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Update Status</h4>
                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                                            <select name="payment_status" id="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors">
                                            Update Status
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
