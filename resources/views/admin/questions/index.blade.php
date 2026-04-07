<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Questions</h1>
            </div>
            <div class="col-sm-6 d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-file-import mr-1"></i> Bulk Import (Excel)
                </button>
                <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Add Single Question
                </a>
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
                        <div class="form-group">
                            <label for="import_category_id">Select Category</label>
                            <select name="category_id" id="import_category_id" class="form-control" required>
                                <option value="">Choose a category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="excel_file">Excel/CSV File</label>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="excel_file" accept=".xlsx, .xls, .csv" required>
                                <label class="custom-file-label" for="excel_file">Choose file</label>
                            </div>
                            <small class="form-text text-muted mt-2">Required Columns: <code>question_text</code>, <code>answer_text</code>, <code>option_1</code>, <code>option_2</code>, <code>option_3</code></small>
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
                            <div class="form-group mb-0">
                                <label for="category_id" class="mr-2 d-inline-block">Filter by Category:</label>
                                <select name="category_id" id="category_id" class="form-control d-inline-block w-auto" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if(request('category_id'))
                                <a href="{{ route('admin.questions.index') }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times mr-1"></i> Clear Filter
                                </a>
                            @endif
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="badge badge-info p-2 px-3">
                                Total: {{ $totalQuestions }} Questions
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
                                            <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
    </script>
    @endpush
</x-admin-layout>
