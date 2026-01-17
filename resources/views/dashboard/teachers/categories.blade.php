@extends('layouts.admin')

@section('title', 'Teacher Categories')
@section('page-title', 'শিক্ষক বিভাগ')
@section('page-description', 'Manage teacher categories and departments')

@section('content')
    <div class="bg-white rounded-xl shadow-md">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="text-lg font-semibold text-gray-900">Teacher Categories</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $categories->count() }} Categories
                </span>
            </div>
            <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Category
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Categories Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold text-lg"
                                 style="background-color: {{ $category->color ?? '#6366f1' }}">
                                {{ strtoupper(substr($category->name, 0, 1)) }}
                            </div>
                            <span class="text-sm text-gray-500">{{ $category->teachers_count }} teachers</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ $category->description ?? 'No description available' }}</p>
                        <div class="flex space-x-2">
                            <button onclick='openEditModal(@json($category))' class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                            <button onclick="openDeleteModal({{ $category->id }}, '{{ $category->name }}')" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new teacher category.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Add New Category</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('dashboard.teachers.categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-bd-green">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-bd-green"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <input type="color" name="color" value="#6366f1" class="w-full h-10 px-1 py-1 border border-gray-300 rounded-lg">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Category</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                    <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-bd-green">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="edit_description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-bd-green"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <input type="color" id="edit_color" name="color" class="w-full h-10 px-1 py-1 border border-gray-300 rounded-lg">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Delete Category</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-gray-600 mb-4">Are you sure you want to delete the category "<span id="delete_category_name" class="font-semibold"></span>"?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(category) {
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_description').value = category.description || '';
            document.getElementById('edit_color').value = category.color || '#6366f1';
            document.getElementById('editForm').action = `/dashboard/teachers/categories/${category.id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openDeleteModal(id, name) {
            document.getElementById('delete_category_name').textContent = name;
            document.getElementById('deleteForm').action = `/dashboard/teachers/categories/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCreateModal();
                closeEditModal();
                closeDeleteModal();
            }
        });
    </script>
@endsection