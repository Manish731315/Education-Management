<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Orders
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Order #{{ $order->order_number }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900">
                                            ${{ number_format($order->final_amount, 2) }}
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <div class="border-t border-gray-200 pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Order Items ({{ $order->orderItems->count() }})</h4>
                                    <div class="space-y-2">
                                        @foreach($order->orderItems as $item)
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://via.placeholder.com/50x50?text=No+Image' }}" 
                                                     alt="{{ $item->product->title }}" 
                                                     class="w-12 h-12 object-cover rounded">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $item->product->title }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $item->product->category->name }} • Qty: {{ $item->quantity }}
                                                    </p>
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    ${{ number_format($item->total_price, 2) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order Actions -->
                                <div class="border-t border-gray-200 pt-4 mt-4 flex justify-between items-center">
                                    <div class="text-sm text-gray-500">
                                        Payment Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors text-sm">
                                            View Details
                                        </a>
                                        @if($order->status === 'completed')
                                            @foreach($order->orderItems as $item)
                                                @if($item->product->file_path)
                                                    <a href="{{ route('orders.download', [$order, $item->product]) }}" 
                                                       class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm">
                                                        Download
                                                    </a>
                                                    @break
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">No orders yet</h3>
                        <p class="mt-2 text-gray-500">Start shopping to see your orders here!</p>
                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" 
                               class="bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 transition-colors font-medium">
                                Browse Products
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
