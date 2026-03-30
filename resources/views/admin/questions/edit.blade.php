<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Question') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.questions.update', $question->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                                <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $question->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="level_id" class="block text-gray-700 text-sm font-bold mb-2">Level</label>
                                <select name="level_id" id="level_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ $question->level_id == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="question_text" class="block text-gray-700 text-sm font-bold mb-2">Question</label>
                            <textarea name="question_text" id="question_text" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ $question->question_text }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="option_1" class="block text-gray-700 text-sm font-bold mb-2">Option 1</label>
                                <input type="text" name="option_1" id="option_1" value="{{ $question->option_1 }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="Option 1">
                            </div>
                            <div>
                                <label for="option_2" class="block text-gray-700 text-sm font-bold mb-2">Option 2</label>
                                <input type="text" name="option_2" id="option_2" value="{{ $question->option_2 }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="Option 2">
                            </div>
                            <div>
                                <label for="option_3" class="block text-gray-700 text-sm font-bold mb-2">Option 3</label>
                                <input type="text" name="option_3" id="option_3" value="{{ $question->option_3 }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="Option 3">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="answer_text" class="block text-gray-700 text-sm font-bold mb-2">Correct Answer (Must match one of the options above)</label>
                            <input type="text" name="answer_text" id="answer_text" value="{{ $question->answer_text }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="Correct Answer">
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Update Question
                            </button>
                            <a href="{{ route('admin.questions.index', ['category_id' => $question->category_id]) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
