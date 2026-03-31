<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Questions: <span class="text-indigo-600">{{ $liveExam->title }}</span>
            </h2>
            <a href="{{ route('admin.live-exams.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                <i class="fa fa-arrow-left mr-2"></i>Back to Exams
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                    <span class="block sm:inline whitespace-pre-line">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Info Bar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4 flex flex-col md:flex-row justify-between items-center border-l-4 border-indigo-500">
                <div>
                    <span class="text-gray-600 uppercase text-xs font-bold tracking-wider">Total Assigned Questions:</span> 
                    <span class="text-2xl font-bold text-indigo-600">{{ count($assignedQuestionIds) }}</span>
                </div>
            </div>

            <!-- Filter Selection -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4 border border-gray-100">
                <form method="GET" action="{{ route('admin.live-exams.questions.manage', $liveExam->id) }}" class="flex flex-wrap items-end space-x-4">
                    <div class="w-full sm:w-1/3 mb-4 sm:mb-0">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
                        <select name="category_id" id="category_id" class="shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm rounded-md transition duration-150" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="w-full sm:w-1/3 mb-4 sm:mb-0">
                        <label for="level_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Level</label>
                        <select name="level_id" id="level_id" class="shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm rounded-md transition duration-150" onchange="this.form.submit()">
                            <option value="">All Levels</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(request('category_id') || request('level_id'))
                        <div class="mb-4 sm:mb-0">
                            <a href="{{ route('admin.live-exams.questions.manage', $liveExam->id) }}" class="inline-flex items-center px-4 py-2 bg-red-100 border border-transparent rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-200 focus:outline-none focus:border-red-300 focus:ring ring-red-300 active:bg-red-200 transition ease-in-out duration-150">
                                <i class="fa fa-times mr-1"></i> Clear Filters
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Question Bank Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.live-exams.questions.update', $liveExam->id) }}" method="POST">
                    @csrf
                    
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Select questions by checking the boxes, then add or remove them.</span>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" name="action" value="add" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-sm transition-colors shadow-sm focus:ring-2 focus:ring-green-400 focus:ring-opacity-50">
                                <i class="fa fa-plus mr-1"></i> Add Selected
                            </button>
                            <button type="submit" name="action" value="remove" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded text-sm transition-colors shadow-sm focus:ring-2 focus:ring-red-400 focus:ring-opacity-50" onclick="return confirm('Remove selected questions from exam?')">
                                <i class="fa fa-minus mr-1"></i> Remove Selected
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-indigo-50">
                                <tr>
                                    <th class="px-6 py-3 text-left w-12">
                                        <input type="checkbox" id="checkAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Category / Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Question Text</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($questions as $question)
                                    @php
                                        $isAssigned = in_array($question->id, $assignedQuestionIds);
                                    @endphp
                                    <tr class="{{ $isAssigned ? 'bg-indigo-50/50' : '' }} hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" class="question-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($isAssigned)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 shadow-sm">
                                                    Assigned
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-600 shadow-sm border border-gray-200">
                                                    Unassigned
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="font-bold text-gray-900">{{ $question->category->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $question->level->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 leading-relaxed">
                                            {{ Str::limit($question->question_text, 120) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fa fa-folder-open text-gray-300 text-4xl mb-3"></i>
                                                <p>No questions found matching your criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
                
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $questions->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.question-checkbox');
            
            if(checkAll) {
                checkAll.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }
        });
    </script>
</x-app-layout>
