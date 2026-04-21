<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User Progress: {{ $user->name }}</h1>
            </div>
            <div class="col-sm-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Summary Stats -->
    <div class="row">
        <div class="col-md-2 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-fire"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-uppercase font-weight-bold tiny">Current Streak</span>
                    <span class="info-box-number h4 mb-0">{{ $user->current_streak }} Days</span>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book-open"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-uppercase font-weight-bold tiny">Questions Read</span>
                    <span class="info-box-number h4 mb-0">{{ $totalRead }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-double"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-uppercase font-weight-bold tiny">Completed</span>
                    <span class="info-box-number h4 mb-0">{{ $progressStats['total_completed_levels'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-uppercase font-weight-bold tiny">Active Levels</span>
                    <span class="info-box-number h4 mb-0 text-dark">{{ $progressStats['total_active_levels'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-purple elevation-1" style="background-color: #6f42c1 !important; color: white;"><i class="fas fa-history"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-uppercase font-weight-bold tiny">Quiz Attempts</span>
                    <span class="info-box-number h4 mb-0">{{ $quizAttempts->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quiz History -->
        <div class="col-lg-7">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header border-transparent">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-1"></i> Quiz Attempt History</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Level</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizAttempts as $attempt)
                                    <tr>
                                        <td class="text-nowrap small text-muted">
                                            {{ $attempt->created_at->format('M d, Y') }}<br>
                                            <span class="tiny">{{ $attempt->created_at->format('h:i A') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $attempt->category->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="font-weight-bold">{{ $attempt->level->name ?? 'Level' }}</td>
                                        <td>
                                            <strong>{{ $attempt->score }} / {{ $attempt->total_questions }}</strong>
                                            @if($attempt->total_questions > 0)
                                                <div class="progress progress-xxs mt-1">
                                                    <div class="progress-bar {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}" 
                                                         style="width: {{ ($attempt->score / $attempt->total_questions) * 100 }}%"></div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attempt->passed)
                                                <span class="badge badge-success px-2 py-1">PASSED</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1">FAILED</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">No quiz attempts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right small">
                        {{ $quizAttempts->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Level Unlocks -->
        <div class="col-lg-5">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-map-marked-alt mr-1"></i> Category & Level Progress</h3>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @forelse($userProgress->groupBy('category_id') as $categoryId => $progresses)
                        <div class="mb-4">
                            <h5 class="text-info border-bottom pb-1 font-weight-bold mb-2">
                                {{ $progresses->first()->category->name ?? 'Category' }}
                            </h5>
                            <div class="list-group list-group-flush">
                                @foreach($progresses as $progress)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 bg-transparent">
                                        <span>{{ $progress->level->name ?? 'Level' }}</span>
                                        @if($progress->status == 'completed')
                                            <span class="badge badge-success rounded-pill"><i class="fas fa-check mr-1"></i> Completed</span>
                                        @elseif($progress->status == 'active')
                                            <span class="badge badge-warning rounded-pill text-dark"><i class="fas fa-spinner mr-1"></i> Active</span>
                                        @else
                                            <span class="badge badge-light rounded-pill"><i class="fas fa-lock mr-1"></i> Locked</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">No level progress found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
