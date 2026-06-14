<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create Question</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Question Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level_id">Level</label>
                                    <select name="level_id" id="level_id" class="form-control @error('level_id') is-invalid @enderror" required>
                                        <option value="">Select Level</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}" 
                                                data-category="{{ $level->category_id }}"
                                                {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('level_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @push('scripts')
                        <script>
                            $(function() {
                                const categorySelect = $('#category_id');
                                const levelSelect = $('#level_id');
                                const allLevels = levelSelect.find('option').clone();

                                function filterLevels() {
                                    const categoryId = categorySelect.val();
                                    const currentLevelId = levelSelect.val();
                                    
                                    levelSelect.empty();
                                    levelSelect.append('<option value="">Select Level</option>');

                                    if (categoryId) {
                                        const filteredLevels = allLevels.filter(function() {
                                            return $(this).data('category') == categoryId;
                                        });
                                        levelSelect.append(filteredLevels);
                                    } else {
                                        // If no category selected, maybe show nothing or all? 
                                        // The requirement says "select category then levels". 
                                        // So if no category, keep it empty.
                                    }

                                    // Restore selection if it still exists in the filtered list
                                    if (currentLevelId) {
                                        levelSelect.val(currentLevelId);
                                    }
                                }

                                categorySelect.on('change', filterLevels);

                                // Initial filter on page load (for old input)
                                if (categorySelect.val()) {
                                    filterLevels();
                                }
                            });
                        </script>
                        @endpush

                        <div class="form-group">
                            <label for="question_text">Question Text</label>
                            <textarea name="question_text" id="question_text" rows="3" class="form-control @error('question_text') is-invalid @enderror" placeholder="Enter the question here..." required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="answer_text">Correct Answer (Main)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check-circle text-success"></i></span>
                                </div>
                                <input type="text" name="answer_text" id="answer_text" class="form-control @error('answer_text') is-invalid @enderror" placeholder="e.g. কাঁঠাল" value="{{ old('answer_text') }}" required>
                                @error('answer_text')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                এটি হলো প্রধান সঠিক উত্তর যা শিক্ষার্থীদের নিকট সঠিক সমাধান হিসেবে প্রদর্শিত হবে।
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="acceptable_answers">Acceptable Alternative Spellings (Optional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-spell-check text-info"></i></span>
                                </div>
                                <input type="text" name="acceptable_answers" id="acceptable_answers" class="form-control @error('acceptable_answers') is-invalid @enderror" placeholder="e.g. কাঠাল|kathal" value="{{ old('acceptable_answers') }}">
                                @error('acceptable_answers')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                বিকল্প সঠিক উত্তরসমূহ (যেমন বানান ভিন্নতা) পাইপ (|) দিয়ে আলাদা করে লিখুন। এগুলো সঠিক উত্তর হিসেবে গণ্য হবে, কিন্তু শিক্ষার্থীদের দেখানো হবে না।
                            </small>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Question
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</x-admin-layout>
