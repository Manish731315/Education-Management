<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Details</h2>
            <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800">← Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div class="flex items-start space-x-6">
                        <img src="{{ $product->thumbnail }}" alt="{{ $product->title }}" class="w-40 h-40 object-cover rounded" />
                        <div class="flex-1">
                            <h3 class="text-2xl font-semibold text-gray-900">{{ $product->title }}</h3>
                            <p class="text-sm text-gray-500">Slug: {{ $product->slug }}</p>
                            <p class="mt-2 text-gray-700">{{ $product->description }}</p>
                            <div class="mt-4 flex items-center space-x-4">
                                <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @if($product->discount_price)
                                    <span class="text-sm text-green-600">Discount: ${{ number_format($product->discount_price, 2) }}</span>
                                @endif
                                <span class="text-sm">Type: {{ ucfirst($product->type) }}</span>
                                <span class="text-sm">Category: {{ optional($product->category)->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Content</h4>
                            <p class="mt-1 text-gray-700">{{ $product->content ?? '—' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">File Path</h4>
                            <p class="mt-1 text-gray-700">{{ $product->file_path ?? '—' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Duration</h4>
                            <p class="mt-1 text-gray-700">{{ $product->duration ? $product->duration . ' min' : '—' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Level</h4>
                            <p class="mt-1 text-gray-700">{{ $product->level ? ucfirst($product->level) : '—' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Status</h4>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Featured</h4>
                            <p class="mt-1">{{ $product->is_featured ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


