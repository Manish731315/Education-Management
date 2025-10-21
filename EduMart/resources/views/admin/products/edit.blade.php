<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product</h2>
            <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800">← Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input name="title" type="text" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('title', $product->title) }}" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug (optional)</label>
                            <input name="slug" type="text" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('slug', $product->slug) }}" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md" required>{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price</label>
                                <input name="price" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('price', $product->price) }}" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount Price</label>
                                <input name="discount_price" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('discount_price', $product->discount_price) }}" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Thumbnail URL</label>
                            <input name="thumbnail" type="text" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('thumbnail', $product->thumbnail) }}" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    <option value="course" @selected(old('type', $product->type) === 'course')>Course</option>
                                    <option value="ebook" @selected(old('type', $product->type) === 'ebook')>eBook</option>
                                    <option value="material" @selected(old('type', $product->type) === 'material')>Material</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Content (optional)</label>
                            <textarea name="content" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('content', $product->content) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File Path (optional)</label>
                                <input name="file_path" type="text" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('file_path', $product->file_path) }}" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                <input name="duration" type="number" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('duration', $product->duration) }}" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Level</label>
                            <select name="level" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="" @selected(old('level', $product->level) === null)>Select level</option>
                                <option value="beginner" @selected(old('level', $product->level) === 'beginner')>Beginner</option>
                                <option value="intermediate" @selected(old('level', $product->level) === 'intermediate')>Intermediate</option>
                                <option value="advanced" @selected(old('level', $product->level) === 'advanced')>Advanced</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex items-center space-x-2">
                                <input id="is_featured" name="is_featured" type="checkbox" value="1" class="rounded border-gray-300" @checked(old('is_featured', $product->is_featured)) />
                                <label for="is_featured" class="text-sm text-gray-700">Featured</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300" @checked(old('is_active', $product->is_active)) />
                                <label for="is_active" class="text-sm text-gray-700">Active</label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                <input name="stock_quantity" type="number" class="mt-1 block w-full border-gray-300 rounded-md" value="{{ old('stock_quantity', $product->stock_quantity) }}" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


