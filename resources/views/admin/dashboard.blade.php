<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Category Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-2">Total Categories</h3>
                        <p class="text-3xl text-indigo-600">{{ $categoryCount }}</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.categories.index') }}" class="text-sm text-blue-500 hover:underline">Manage Categories &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Chapter Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-2">Total Chapters</h3>
                        <p class="text-3xl text-indigo-600">{{ $chapterCount }}</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.chapters.index') }}" class="text-sm text-blue-500 hover:underline">Manage Chapters &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Question Stats -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-2">Total Questions</h3>
                        <p class="text-3xl text-indigo-600">{{ $questionCount }}</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.questions.index') }}" class="text-sm text-blue-500 hover:underline">Manage Questions &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
