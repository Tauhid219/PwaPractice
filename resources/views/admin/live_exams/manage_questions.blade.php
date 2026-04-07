<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark">Manage Questions: <span class="text-primary">{{ $liveExam->title }}</span></h1>
            </div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('admin.live-exams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Exams
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Info Box -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="info-box shadow-sm border-left border-primary">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-uppercase font-weight-bold tiny">Assigned Questions</span>
                    <span class="info-box-number h4 mb-0">{{ count($assignedQuestionIds) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <!-- Filter Selection -->
            <div class="card card-outline card-info shadow-sm">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('admin.live-exams.questions.manage', $liveExam->id) }}" class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="category_id" class="mr-2 d-inline-block small font-weight-bold">Category:</label>
                                <select name="category_id" id="category_id" class="form-control form-control-sm d-inline-block w-auto" onchange="this.form.submit()">
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
                            <div class="form-group mb-0">
                                <label for="level_id" class="mr-2 d-inline-block small font-weight-bold">Level:</label>
                                <select name="level_id" id="level_id" class="form-control form-control-sm d-inline-block w-auto" onchange="this.form.submit()">
                                    <option value="">All Levels</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            @if(request('category_id') || request('level_id'))
                                <a href="{{ route('admin.live-exams.questions.manage', $liveExam->id) }}" class="btn btn-xs btn-outline-danger mr-2">
                                    <i class="fas fa-times mr-1"></i> Clear Filters
                                </a>
                            @endif
                            <span class="badge badge-info p-2 px-3 small">
                                Showing: {{ $questions->total() }} Qs
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

    <!-- Question Bank Table -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.live-exams.questions.update', $liveExam->id) }}" method="POST">
                @csrf
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-database mr-1"></i> Question Bank</h3>
                        <div class="card-tools">
                            <button type="submit" name="action" value="add" class="btn btn-sm btn-success mr-2">
                                <i class="fas fa-plus mr-1"></i> Add Selected
                            </button>
                            <button type="submit" name="action" value="remove" class="btn btn-sm btn-danger mr-2" onclick="return confirm('Remove selected questions from exam?')">
                                <i class="fas fa-minus mr-1"></i> Remove Selected
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 40px">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="checkAll">
                                                <label for="checkAll" class="custom-control-label"></label>
                                            </div>
                                        </th>
                                        <th style="width: 120px">Status</th>
                                        <th style="width: 200px">Category / Level</th>
                                        <th>Question Text</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($questions as $question)
                                        @php
                                            $isAssigned = in_array($question->id, $assignedQuestionIds);
                                        @endphp
                                        <tr class="{{ $isAssigned ? 'table-primary-light' : '' }}" style="{{ $isAssigned ? 'background-color: rgba(0,123,255,0.05);' : '' }}">
                                            <td>
                                                <div class="custom-control custom-checkbox ml-1">
                                                    <input class="custom-control-input question-checkbox" type="checkbox" name="question_ids[]" value="{{ $question->id }}" id="q-{{ $question->id }}">
                                                    <label for="q-{{ $question->id }}" class="custom-control-label"></label>
                                                </div>
                                            </td>
                                            <td>
                                                @if($isAssigned)
                                                    <span class="badge badge-success px-3"><i class="fas fa-check mr-1"></i> Assigned</span>
                                                @else
                                                    <span class="badge badge-light border px-2 text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $question->category->name ?? 'N/A' }}</span> <br>
                                                <small class="text-muted font-italic">{{ $question->level->name ?? 'N/A' }}</small>
                                            </td>
                                            <td class="small pt-2" title="{{ $question->question_text }}">
                                                {{ Str::limit($question->question_text, 150) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center p-5">
                                                <i class="fas fa-folder-open text-gray-300 display-4 mb-3 d-block"></i>
                                                <p class="text-muted">No questions found matching your criteria.</p>
                                            </td>
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
            </form>
        </div>
    </div>

    @push('scripts')
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
    @endpush
</x-admin-layout>
