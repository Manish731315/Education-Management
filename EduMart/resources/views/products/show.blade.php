<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->title }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">
                ← Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Product Image -->
                        <div>
                            <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : 'https://via.placeholder.com/500x400?text=No+Image' }}" 
                                 alt="{{ $product->title }}" 
                                 class="w-full h-96 object-cover rounded-lg">
                        </div>

                        <!-- Product Details -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ ucfirst($product->type) }}
                                </span>
                                @if($product->isOnDiscount())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        {{ $product->discount_percentage }}% OFF
                                    </span>
                                @endif
                            </div>

                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->title }}</h1>
                            
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="flex items-center space-x-2">
                                    @if($product->isOnDiscount())
                                        <span class="text-3xl font-bold text-red-600">${{ number_format($product->current_price, 2) }}</span>
                                        <span class="text-xl text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                            </div>

                            <!-- Product Info -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Category</span>
                                    <p class="text-sm text-gray-900">{{ $product->category->name }}</p>
                                </div>
                                @if($product->level)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Level</span>
                                        <p class="text-sm text-gray-900">{{ ucfirst($product->level) }}</p>
                                    </div>
                                @endif
                                @if($product->duration)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Duration</span>
                                        <p class="text-sm text-gray-900">{{ $product->duration }} minutes</p>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Sold</span>
                                    <p class="text-sm text-gray-900">{{ $product->sold_count }} copies</p>
                                </div>
                            </div>

                            <!-- Add to Cart Form -->
                            @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="mb-6">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    
                                    <div class="flex items-center space-x-4 mb-4">
                                        <label for="quantity" class="text-sm font-medium text-gray-700">Quantity:</label>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" 
                                               class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <button type="submit" 
                                            class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 transition-colors font-medium">
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <div class="mb-6">
                                    <a href="{{ route('login') }}" 
                                       class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 transition-colors font-medium inline-block text-center">
                                        Login to Purchase
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Product Content -->
                    @if($product->content)
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Course Content</h3>
                            <div class="prose max-w-none">
                                {!! nl2br(e($product->content)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ $relatedProduct->thumbnail ? asset('storage/' . $relatedProduct->thumbnail) : 'https://via.placeholder.com/300x200?text=No+Image' }}" 
                                         alt="{{ $relatedProduct->title }}" 
                                         class="w-full h-48 object-cover">
                                </div>
                                
                                <div class="p-4">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                        {{ $relatedProduct->title }}
                                    </h4>

                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-2">
                                            @if($relatedProduct->isOnDiscount())
                                                <span class="text-lg font-bold text-red-600">${{ number_format($relatedProduct->current_price, 2) }}</span>
                                                <span class="text-sm text-gray-500 line-through">${{ number_format($relatedProduct->price, 2) }}</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-900">${{ number_format($relatedProduct->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <a href="{{ route('products.show', $relatedProduct) }}" 
                                       class="w-full bg-indigo-600 text-white text-center py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors inline-block">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
