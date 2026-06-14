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

    <!-- Info & Upload Section -->
    <div class="row mb-3">
        <div class="col-md-4">
            <!-- Stats Box -->
            <div class="info-box shadow-sm border-left border-primary mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-question-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-uppercase font-weight-bold tiny">Total Questions</span>
                    <span class="info-box-number h4 mb-0">{{ $questions->total() }}</span>
                </div>
            </div>

            <!-- Excel Bulk Import Card -->
            <div class="card card-outline card-success shadow-sm mb-3">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-sm"><i class="fas fa-file-excel mr-1 text-success"></i> Bulk Upload Excel</h3>
                </div>
                <form action="{{ route('admin.live-exams.questions.import', $liveExam->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body py-3">
                        <!-- Instructions Section -->
                        <div class="alert alert-light border shadow-sm mb-3 import-instructions">
                            <h6 class="font-weight-bold text-primary mb-2"><i class="fas fa-info-circle mr-1"></i> এক্সেল ইমপোর্ট নির্দেশিকা:</h6>
                            <ul class="pl-3 mb-0 small text-dark">
                                <li>এক্সেল ফাইলের প্রথম সারিতে অবশ্যই হেডার থাকতে হবে।</li>
                                <li>ফাইলের ফরম্যাট অবশ্যই <strong>.xlsx</strong> হতে হবে।</li>
                                <li>হেডারগুলো হলো: <code class="text-danger bg-gray-light px-1">question_text</code>, <code class="text-danger bg-gray-light px-1">option_1</code>, <code class="text-danger bg-gray-light px-1">option_2</code>, <code class="text-danger bg-gray-light px-1">option_3</code>, <code class="text-danger bg-gray-light px-1">option_4</code>, <code class="text-danger bg-gray-light px-1">answer_text</code></li>
                                <li>যেহেতু এটি MCQ পরীক্ষা, তাই ৪টি অপশনই প্রদান করা বাধ্যতামূলক।</li>
                                <li>সঠিক উত্তরটি অবশ্যই ৪টি অপশনের যেকোনো একটির সাথে হুবহু মিলতে হবে।</li>
                                <li>ফাইলের সাইজ সর্বোচ্চ ৫ এমবি হতে পারবে।</li>
                            </ul>
                            <div class="mt-3">
                                <a href="{{ route('admin.live-exams.questions.template') }}" class="btn btn-xs btn-primary px-3">
                                    <i class="fas fa-download mr-1"></i> নমুনা ফাইল (.xlsx) ডাউনলোড করুন
                                </a>
                            </div>
                        </div>
                        <style>
                            .dark-mode .import-instructions { background-color: #343a40 !important; color: #fff !important; }
                            .dark-mode .import-instructions .text-dark { color: #dee2e6 !important; }
                            .dark-mode .import-instructions code { color: #ffc107 !important; background-color: rgba(255,255,255,0.1) !important; }
                        </style>

                        <div class="form-group mb-2">
                            <label for="excel_file" class="small font-weight-bold">Select Excel/CSV File:</label>
                            <input type="file" name="file" id="excel_file" class="form-control-file form-control-sm" required>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right">
                        <button type="submit" class="btn btn-xs btn-success px-3">
                            <i class="fas fa-upload mr-1"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Add Single Question Form -->
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-sm"><i class="fas fa-plus mr-1"></i> Add Single Question</h3>
                </div>
                <form action="{{ route('admin.live-exams.questions.store', $liveExam->id) }}" method="POST">
                    @csrf
                    <div class="card-body py-3">
                        <div class="form-group">
                            <label for="question_text" class="small font-weight-bold">Question Text</label>
                            <textarea name="question_text" id="question_text" class="form-control form-control-sm" rows="2" placeholder="Enter question text" required>{{ old('question_text') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="option_1" class="small font-weight-bold">Option 1 (ক)</label>
                                    <input type="text" name="option_1" id="option_1" class="form-control form-control-sm" placeholder="Option 1" value="{{ old('option_1') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="option_2" class="small font-weight-bold">Option 2 (খ)</label>
                                    <input type="text" name="option_2" id="option_2" class="form-control form-control-sm" placeholder="Option 2" value="{{ old('option_2') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="option_3" class="small font-weight-bold">Option 3 (গ)</label>
                                    <input type="text" name="option_3" id="option_3" class="form-control form-control-sm" placeholder="Option 3" value="{{ old('option_3') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="option_4" class="small font-weight-bold">Option 4 (ঘ)</label>
                                    <input type="text" name="option_4" id="option_4" class="form-control form-control-sm" placeholder="Option 4" value="{{ old('option_4') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0 mt-2">
                            <label for="answer_text" class="small font-weight-bold">Correct Answer</label>
                            <input type="text" name="answer_text" id="answer_text" class="form-control form-control-sm" placeholder="Must match one of the 4 options exactly" value="{{ old('answer_text') }}" required>
                            <small class="form-text text-muted">Enter the correct answer. It must exactly match one of the options above.</small>
                        </div>
                    </div>
                    <div class="card-footer py-2 text-right">
                        <button type="submit" class="btn btn-sm btn-primary px-3">
                            <i class="fas fa-save mr-1"></i> Save Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check text-white"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="icon fas fa-ban text-white"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="icon fas fa-ban text-white"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Current Questions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-list mr-1"></i> Questions in this Exam</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Question Text</th>
                                    <th>Options</th>
                                    <th>Correct Answer(s)</th>
                                    <th style="width: 160px" class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $index => $question)
                                    <tr>
                                        <td>{{ $questions->firstItem() + $index }}</td>
                                        <td class="font-weight-bold">{{ $question->question_text }}</td>
                                        <td>
                                            <ul class="list-unstyled mb-0 small text-muted">
                                                <li><strong>ক.</strong> {{ $question->option_1 }}</li>
                                                <li><strong>খ.</strong> {{ $question->option_2 }}</li>
                                                <li><strong>গ.</strong> {{ $question->option_3 }}</li>
                                                <li><strong>ঘ.</strong> {{ $question->option_4 }}</li>
                                            </ul>
                                        </td>
                                        <td>
                                            @if($question->correct_answers)
                                                @foreach($question->correct_answers as $ans)
                                                    <span class="badge badge-success">{{ $ans }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-success">{{ $question->answer_text }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right align-middle">
                                            <button type="button" class="btn btn-xs btn-warning edit-btn mr-1 text-dark" 
                                                data-id="{{ $question->id }}"
                                                data-text="{{ $question->question_text }}"
                                                data-opt1="{{ $question->option_1 }}"
                                                data-opt2="{{ $question->option_2 }}"
                                                data-opt3="{{ $question->option_3 }}"
                                                data-opt4="{{ $question->option_4 }}"
                                                data-answers="{{ is_array($question->correct_answers) ? implode(', ', $question->correct_answers) : $question->answer_text }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.live-exams.questions.destroy', [$liveExam->id, $question->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question? This will permanently delete the question record.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-5">
                                            <i class="fas fa-folder-open text-gray-300 display-4 mb-3 d-block"></i>
                                            <p class="text-muted mb-0">No questions found in this Live Exam yet. Create one or upload via Excel above.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right mb-0">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Question Modal -->
    <div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title font-weight-bold" id="editQuestionModalLabel"><i class="fas fa-edit mr-1"></i> Edit Question</h5>
                    <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editQuestionForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_question_text" class="small font-weight-bold">Question Text</label>
                            <textarea name="question_text" id="edit_question_text" class="form-control form-control-sm" rows="2" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_option_1" class="small font-weight-bold">Option 1 (ক)</label>
                                    <input type="text" name="option_1" id="edit_option_1" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_option_2" class="small font-weight-bold">Option 2 (খ)</label>
                                    <input type="text" name="option_2" id="edit_option_2" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_option_3" class="small font-weight-bold">Option 3 (গ)</label>
                                    <input type="text" name="option_3" id="edit_option_3" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_option_4" class="small font-weight-bold">Option 4 (ঘ)</label>
                                    <input type="text" name="option_4" id="edit_option_4" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="edit_answer_text" class="small font-weight-bold">Correct Answer</label>
                            <input type="text" name="answer_text" id="edit_answer_text" class="form-control form-control-sm" placeholder="Must match one of the 4 options exactly" required>
                            <small class="form-text text-muted">Enter the correct answer. It must exactly match one of the options above.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm text-dark font-weight-bold px-3">
                            <i class="fas fa-save mr-1"></i> Update Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function() {
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const text = $(this).data('text');
                const opt1 = $(this).data('opt1');
                const opt2 = $(this).data('opt2');
                const opt3 = $(this).data('opt3');
                const opt4 = $(this).data('opt4');
                const correctAnswers = $(this).data('answers');
                
                const actionUrl = `{{ url('admin/live-exams/' . $liveExam->id . '/questions') }}/${id}/update`;
                
                $('#editQuestionForm').attr('action', actionUrl);
                $('#edit_question_text').val(text);
                $('#edit_option_1').val(opt1);
                $('#edit_option_2').val(opt2);
                $('#edit_option_3').val(opt3);
                $('#edit_option_4').val(opt4);
                $('#edit_answer_text').val(correctAnswers);
                
                $('#editQuestionModal').modal('show');
            });
        });
    </script>
    @endpush
</x-admin-layout>
