<x-admin-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Live Exams</h1>
            </div>
            <div class="col-sm-6 d-flex justify-content-end align-items-center">
                <a href="{{ route('admin.live-exams.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Create New Exam
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-check"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-outline card-primary shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Duration</th>
                                    <th>Schedule</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exams as $exam)
                                    <tr>
                                        <td>
                                            <strong>{{ $exam->title }}</strong>
                                        </td>
                                        <td>{{ $exam->duration_minutes }} mins</td>
                                        <td class="small">
                                            <i class="far fa-clock text-info"></i> {{ $exam->start_time->format('d M, h:i A') }} <br>
                                            <i class="fas fa-hourglass-end text-danger"></i> {{ $exam->end_time->format('d M, h:i A') }}
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $now = now();
                                            @endphp
                                            @if(!$exam->is_active)
                                                <span class="badge badge-secondary">Draft / Hidden</span>
                                            @elseif($now->isBefore($exam->start_time))
                                                <span class="badge badge-info">Upcoming</span>
                                            @elseif($now->isAfter($exam->end_time))
                                                <span class="badge badge-danger">Ended</span>
                                            @else
                                                <span class="badge badge-success px-3 animate-pulse">Live Now</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.live-exams.questions.manage', $exam->id) }}" class="btn btn-sm btn-outline-primary" title="Manage Questions">
                                                    <i class="fas fa-list"></i> <span class="badge badge-primary ml-1">{{ $exam->questions()->count() }}</span>
                                                </a>
                                                <a href="{{ route('admin.live-exams.results', $exam->id) }}" class="btn btn-sm btn-outline-success" title="View Results">
                                                    <i class="fas fa-trophy"></i>
                                                </a>
                                                <a href="{{ route('admin.live-exams.edit', $exam->id) }}" class="btn btn-sm btn-info" title="Edit Exam">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.live-exams.destroy', $exam->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this exam?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger rounded-left-0" title="Delete Exam">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">No live exams found. Click "Create New Exam" to start.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $exams->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
