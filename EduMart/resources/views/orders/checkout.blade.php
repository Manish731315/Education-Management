<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Checkout
            </h2>
            <a href="{{ route('cart.index') }}" class="text-indigo-600 hover:text-indigo-800">
                ← Back to Cart
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Items -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                            
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://via.placeholder.com/100x100?text=No+Image' }}" 
                                                 alt="{{ $item->product->title }}" 
                                                 class="w-16 h-16 object-cover rounded-md">
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $item->product->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500">{{ $item->product->category->name }}</p>
                                            
                                            <div class="flex items-center space-x-2 mt-1">
                                                @if($item->product->isOnDiscount())
                                                    <span class="text-sm font-bold text-red-600">${{ number_format($item->product->current_price, 2) }}</span>
                                                    <span class="text-xs text-gray-500 line-through">${{ number_format($item->product->price, 2) }}</span>
                                                @else
                                                    <span class="text-sm font-bold text-gray-900">${{ number_format($item->product->price, 2) }}</span>
                                                @endif
                                                <span class="text-xs text-gray-500">x {{ $item->quantity }}</span>
                                            </div>
                                        </div>

                                        <!-- Item Total -->
                                        <div class="text-right">
                                            <div class="text-sm font-bold text-gray-900">
                                                ${{ number_format($item->total_price, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Complete Your Order</h3>
                            
                            <form action="{{ route('orders.store') }}" method="POST">
                                @csrf
                                
                                <!-- Payment Method -->
                                <div class="mb-6">
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                        Payment Method
                                    </label>
                                    <select name="payment_method" id="payment_method" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select Payment Method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                    </select>
                                    @error('payment_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Order Notes -->
                                <div class="mb-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Order Notes (Optional)
                                    </label>
                                    <textarea name="notes" id="notes" rows="3" 
                                              placeholder="Any special instructions or notes..."
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>

                                <!-- Order Summary -->
                                <div class="border-t border-gray-200 pt-4 mb-6">
                                    <div class="space-y-2">
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
                                        <div class="border-t border-gray-200 pt-2">
                                            <div class="flex justify-between">
                                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                                <span class="text-lg font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="mb-6">
                                    <label class="flex items-start">
                                        <input type="checkbox" name="terms" required
                                               class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">
                                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-800">Terms and Conditions</a> 
                                            and <a href="#" class="text-indigo-600 hover:text-indigo-800">Privacy Policy</a>
                                        </span>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 transition-colors font-medium">
                                    Place Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
