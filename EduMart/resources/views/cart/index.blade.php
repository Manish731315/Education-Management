<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Shopping Cart
            </h2>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">
                ← Continue Shopping
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($cartItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cart Items ({{ $cartItems->count() }})</h3>
                                
                                <div class="space-y-4">
                                    @foreach($cartItems as $item)
                                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0">
                                                <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://via.placeholder.com/100x100?text=No+Image' }}" 
                                                     alt="{{ $item->product->title }}" 
                                                     class="w-20 h-20 object-cover rounded-md">
                                            </div>

                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-lg font-semibold text-gray-900 truncate">
                                                    {{ $item->product->title }}
                                                </h4>
                                                <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                                <p class="text-sm text-gray-500">{{ ucfirst($item->product->type) }}</p>
                                                
                                                <div class="flex items-center space-x-2 mt-2">
                                                    @if($item->product->isOnDiscount())
                                                        <span class="text-lg font-bold text-red-600">${{ number_format($item->product->current_price, 2) }}</span>
                                                        <span class="text-sm text-gray-500 line-through">${{ number_format($item->product->price, 2) }}</span>
                                                    @else
                                                        <span class="text-lg font-bold text-gray-900">${{ number_format($item->product->price, 2) }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Quantity Controls -->
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <label for="quantity_{{ $item->id }}" class="text-sm font-medium text-gray-700">Qty:</label>
                                                    <input type="number" name="quantity" id="quantity_{{ $item->id }}" 
                                                           value="{{ $item->quantity }}" min="1" max="10" 
                                                           class="w-16 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                                        Update
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Item Total -->
                                            <div class="text-right">
                                                <div class="text-lg font-bold text-gray-900">
                                                    ${{ number_format($item->total_price, 2) }}
                                                </div>
                                            </div>

                                            <!-- Remove Button -->
                                            <div>
                                                <form action="{{ route('cart.destroy', $item) }}" method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to remove this item?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Clear Cart -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <form action="{{ route('cart.clear') }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to clear your cart?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            Clear Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span class="font-medium">${{ number_format($total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tax</span>
                                        <span class="font-medium">$0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Shipping</span>
                                        <span class="font-medium">Free</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-3">
                                        <div class="flex justify-between">
                                            <span class="text-lg font-semibold text-gray-900">Total</span>
                                            <span class="text-lg font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 space-y-3">
                                    <a href="{{ route('orders.checkout') }}" 
                                       class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 transition-colors font-medium text-center block">
                                        Proceed to Checkout
                                    </a>
                                    <a href="{{ route('products.index') }}" 
                                       class="w-full bg-gray-600 text-white py-3 px-4 rounded-md hover:bg-gray-700 transition-colors font-medium text-center block">
                                        Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Your cart is empty</h3>
                        <p class="mt-2 text-gray-500">Add some products to get started!</p>
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
