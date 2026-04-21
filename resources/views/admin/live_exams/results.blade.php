<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark">Exam Results: <span class="text-primary">{{ $liveExam->title }}</span></h1>
            </div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('admin.live-exams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Exams
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $attempts->total() }}</h3>
                    <p>Total Participants</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ round($attempts->max('score') ?? 0) }}</h3>
                    <p>Highest Score</p>
                </div>
                <div class="icon">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3>{{ round($attempts->avg('score') ?? 0, 1) }}</h3>
                    <p>Average Score</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-list-ol mr-1"></i> Student Ranking</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 80px">Rank</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Tab Switches</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attempts as $index => $attempt)
                                    <tr>
                                        <td>
                                            @php
                                                $rank = $loop->iteration + ($attempts->currentPage() - 1) * $attempts->perPage();
                                            @endphp
                                            @if($rank == 1)
                                                <span class="badge badge-warning p-2 px-3"><i class="fas fa-trophy mr-1"></i> 1st</span>
                                            @elseif($rank == 2)
                                                <span class="badge badge-silver p-2 px-3 text-white" style="background-color: #C0C0C0;"><i class="fas fa-medal mr-1"></i> 2nd</span>
                                            @elseif($rank == 3)
                                                <span class="badge badge-bronze p-2 px-3 text-white" style="background-color: #CD7F32;"><i class="fas fa-medal mr-1"></i> 3rd</span>
                                            @else
                                                <span class="ml-3 font-weight-bold text-muted">{{ $rank }}</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $attempt->user->name ?? 'Unknown' }}</strong></td>
                                        <td class="small">{{ $attempt->user->email ?? 'N/A' }}</td>
                                                                                    <td class="text-center">
                                            <span class="badge badge-success p-2 px-3 h6 mb-0 shadow-sm border border-success">
                                                {{ round($attempt->score) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($attempt->tab_switches > 0)
                                                <span class="badge badge-danger p-2 px-3 shadow-sm" title="User switched tabs during exam">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ $attempt->tab_switches }}
                                                </span>
                                            @else
                                                <span class="text-muted">–</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ $attempt->created_at->format('d M Y, h:i A') }} <br>
                                            <span class="tiny italic">{{ $attempt->created_at->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-5">
                                            <i class="fas fa-inbox text-gray-300 display-4 mb-3 d-block"></i>
                                            <p class="text-muted">No results found for this exam.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $attempts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
