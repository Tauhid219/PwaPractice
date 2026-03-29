<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Questions') }}
            </h2>
            <div class="flex space-x-2">
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Bulk Import (Excel)
                </button>
                <a href="{{ route('admin.questions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add Single Question
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Modal -->
            <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Import Questions</h3>
                        <div class="mt-2 px-7 py-3">
                            <form action="{{ route('admin.questions.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4 text-left">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Select Category</label>
                                    <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                        <option value="">Choose a category...</option>
                                        @php
                                            $categories = \App\Models\Category::orderBy('order')->get();
                                        @endphp
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4 text-left">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Excel File</label>
                                    <input type="file" name="file" accept=".xlsx, .xls, .csv" required class="w-full">
                                    <p class="text-xs text-gray-500 mt-1">Columns required: question_text, answer_text</p>
                                </div>
                                <div class="items-center px-4 py-3">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        Import Data
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 p-4">
                <form method="GET" action="{{ route('admin.questions.index') }}" class="flex items-end space-x-4">
                    <div class="max-w-sm">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
                        <select name="category_id" id="category_id" class="shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm rounded-md" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(request('category_id'))
                        <div>
                            <a href="{{ route('admin.questions.index') }}" class="inline-flex items-center px-4 py-2 bg-red-100 border border-transparent rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-200 focus:outline-none focus:border-red-300 focus:ring ring-red-300 active:bg-red-200 transition ease-in-out duration-150">
                                Clear Filter
                            </a>
                        </div>
                    @endif
                    <div class="pb-1">
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-4 py-2 rounded-full border border-indigo-400">
                            Total: {{ $totalQuestions }} Questions
                        </span>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Answer</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($questions as $question)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $loop->iteration + ($questions->currentPage() - 1) * $questions->perPage() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $question->category->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ Str::limit($question->question_text, 50) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ Str::limit($question->answer_text, 30) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.questions.edit', $question->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="mt-4 p-4">
                        {{ $questions->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
