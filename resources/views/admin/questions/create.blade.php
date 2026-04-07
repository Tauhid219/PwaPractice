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
                                            <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
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

                        <div class="form-group">
                            <label for="question_text">Question Text</label>
                            <textarea name="question_text" id="question_text" rows="3" class="form-control @error('question_text') is-invalid @enderror" placeholder="Enter the question here..." required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="option_1">Option 1</label>
                                    <input type="text" name="option_1" id="option_1" class="form-control @error('option_1') is-invalid @enderror" placeholder="Option 1" value="{{ old('option_1') }}" required>
                                    @error('option_1')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="option_2">Option 2</label>
                                    <input type="text" name="option_2" id="option_2" class="form-control @error('option_2') is-invalid @enderror" placeholder="Option 2" value="{{ old('option_2') }}" required>
                                    @error('option_2')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="option_3">Option 3</label>
                                    <input type="text" name="option_3" id="option_3" class="form-control @error('option_3') is-invalid @enderror" placeholder="Option 3" value="{{ old('option_3') }}" required>
                                    @error('option_3')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="answer_text">Correct Answer</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check-circle text-success"></i></span>
                                </div>
                                <input type="text" name="answer_text" id="answer_text" class="form-control @error('answer_text') is-invalid @enderror" placeholder="Correct Answer (must match one of the options)" value="{{ old('answer_text') }}" required>
                                @error('answer_text')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Ensure the correct answer exactly matches one of the three options provided above.</small>
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
