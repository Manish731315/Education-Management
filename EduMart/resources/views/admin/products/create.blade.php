<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Product</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input name="title" type="text" class="mt-1 block w-full border-gray-300 rounded-md" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug (optional)</label>
                            <input name="slug" type="text" class="mt-1 block w-full border-gray-300 rounded-md" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md" required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price</label>
                                <input name="price" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount Price</label>
                                <input name="discount_price" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Thumbnail URL</label>
                            <input name="thumbnail" type="text" class="mt-1 block w-full border-gray-300 rounded-md" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    <option value="course">Course</option>
                                    <option value="ebook">eBook</option>
                                    <option value="material">Material</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Content (optional)</label>
                            <textarea name="content" rows="4" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File Path (optional)</label>
                                <input name="file_path" type="text" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                <input name="duration" type="number" class="mt-1 block w-full border-gray-300 rounded-md" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Level</label>
                            <select name="level" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">Select level</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex items-center space-x-2">
                                <input id="is_featured" name="is_featured" type="checkbox" value="1" class="rounded border-gray-300" />
                                <label for="is_featured" class="text-sm text-gray-700">Featured</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300" checked />
                                <label for="is_active" class="text-sm text-gray-700">Active</label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                <input name="stock_quantity" type="number" class="mt-1 block w-full border-gray-300 rounded-md" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


