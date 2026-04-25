<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Questions</h1>
            </div>
            <div class="col-sm-6 d-flex justify-content-end align-items-center">
                @can('create questions')
                <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-file-import mr-1"></i> Bulk Import (Excel)
                </button>
                <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Add Single Question
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.questions.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Questions from Excel/CSV</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Instructions Section -->
                        <div class="alert alert-light border shadow-sm mb-4 import-instructions">
                            <h6 class="font-weight-bold text-primary"><i class="fas fa-info-circle mr-1"></i> এক্সেল ইমপোর্ট নির্দেশিকা:</h6>
                            <ul class="pl-3 mb-0 small text-dark">
                                <li>এক্সেল ফাইলের প্রথম সারিতে অবশ্যই হেডার থাকতে হবে।</li>
                                <li>ফাইলের ফরম্যাট অবশ্যই <strong>.xlsx</strong> হতে হবে।</li>
                                <li>হেডারগুলো হলো: <code class="text-danger bg-gray-light px-1">question_text</code>, <code class="text-danger bg-gray-light px-1">option_1</code>, <code class="text-danger bg-gray-light px-1">option_2</code>, <code class="text-danger bg-gray-light px-1">option_3</code>, <code class="text-danger bg-gray-light px-1">answer_text</code></li>
                                <li><code class="text-danger bg-gray-light px-1">answer_text</code> ফিল্ডের মান অবশ্যই উপরের ৩টি অপশনের যেকোনো একটির সাথে হুবহু মিলতে হবে।</li>
                                <li>ফাইলের সাইজ সর্বোচ্চ ৫ এমবি হতে পারবে।</li>
                            </ul>
                        </div>
                        <style>
                            .dark-mode .import-instructions { background-color: #343a40 !important; color: #fff !important; }
                            .dark-mode .import-instructions .text-dark { color: #dee2e6 !important; }
                            .dark-mode .import-instructions code { color: #ffc107 !important; background-color: rgba(255,255,255,0.1) !important; }
                        </style>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="import_category_id">Select Category</label>
                                    <select name="category_id" id="import_category_id" class="form-control" required>
                                        <option value="">Choose Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="import_level_id">Select Level</label>
                                    <select name="level_id" id="import_level_id" class="form-control" required>
                                        <option value="">Choose Level</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}" data-category="{{ $level->category_id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="excel_file">Excel/CSV File</label>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="excel_file" accept=".xlsx, .xls, .csv" required>
                                <label class="custom-file-label" for="excel_file">Choose file</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('admin.questions.index') }}" class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="category_id" class="mr-2 mb-0 text-nowrap">Category:</label>
                                <select name="category_id" id="category_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <label for="index_level_id" class="mr-2 mb-0 text-nowrap">Level:</label>
                                <select name="level_id" id="index_level_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">All Levels</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" data-category="{{ $level->category_id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            @if(request('category_id') || request('level_id'))
                                <a href="{{ route('admin.questions.index') }}" class="btn btn-xs btn-outline-danger">
                                    <i class="fas fa-times mr-1"></i> Clear
                                </a>
                            @endif
                        </div>
                        <div class="col-md-3 text-right d-flex align-items-center justify-content-end">
                            <span class="badge badge-info p-2 px-3 small">
                                Total: {{ $totalQuestions }} Qs
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="icon fas fa-ban"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px">SL</th>
                                    <th>Category</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th class="text-right" style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $question)
                                    <tr>
                                        <td>{{ $loop->iteration + ($questions->currentPage() - 1) * $questions->perPage() }}</td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $question->category->name ?? 'N/A' }}</span>
                                        </td>
                                        <td title="{{ $question->question_text }}">
                                            {{ Str::limit($question->question_text, 60) }}
                                        </td>
                                        <td title="{{ $question->answer_text }}">
                                            {{ Str::limit($question->answer_text, 30) }}
                                        </td>
                                        <td class="text-right">
                                            @can('edit questions')
                                            <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete questions')
                                            <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">No questions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $questions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Update the custom file input label when a file is selected
        document.getElementById('excel_file').onchange = function () {
            var fileName = this.value.split('\\').pop();
            this.nextElementSibling.innerText = fileName;
        };

        // Dynamic Level Filtering for Import Modal
        $(function() {
            const categorySelect = $('#import_category_id');
            const levelSelect = $('#import_level_id');
            const allLevels = levelSelect.find('option').clone();

            function filterLevels() {
                const categoryId = categorySelect.val();
                levelSelect.empty();
                levelSelect.append('<option value="">Choose Level</option>');

                if (categoryId) {
                    const filteredLevels = allLevels.filter(function() {
                        return $(this).data('category') == categoryId;
                    });
                    levelSelect.append(filteredLevels);
                }
            }

            categorySelect.on('change', filterLevels);
        });

        // Dynamic Level Filtering for Index Filter Form
        $(function() {
            const categorySelect = $('#category_id');
            const levelSelect = $('#index_level_id');
            const allLevels = levelSelect.find('option').clone();

            function filterLevels() {
                const categoryId = categorySelect.val();
                const currentLevelId = levelSelect.val();
                
                levelSelect.empty();
                levelSelect.append('<option value="">All Levels</option>');

                if (categoryId) {
                    const filteredLevels = allLevels.filter(function() {
                        return $(this).data('category') == categoryId;
                    });
                    levelSelect.append(filteredLevels);
                } else {
                    // Show all levels if no category is selected (since it's a filter)
                    levelSelect.append(allLevels.filter(function() {
                        return $(this).val() !== "";
                    }));
                }

                // Restore selection if it still exists in the filtered list
                if (currentLevelId) {
                    levelSelect.val(currentLevelId);
                }
            }

            // Note: We don't filter on change here because category change submits the form.
            // But we filter on page load if a category is already selected.
            if (categorySelect.val()) {
                filterLevels();
            }
        });
    </script>
    @endpush
</x-admin-layout>
