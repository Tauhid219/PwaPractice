<x-admin-layout>
    <x-slot name="header">
        <h1 class="m-0">Admin Analytics Dashboard</h1>
    </x-slot>

    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 shadow-sm">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Students</span>
                    <span class="info-box-number">{{ number_format($userCount) }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 shadow-sm">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-question-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Questions</span>
                    <span class="info-box-number">{{ number_format($questionCount) }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 shadow-sm">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-pen-nib"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Attempts</span>
                    <span class="info-box-number">{{ number_format($totalAttempts) }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 shadow-sm">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pass Rate</span>
                    <span class="info-box-number">{{ $passRate }}%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Chart -->
        <div class="col-md-6">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Activity Last 7 Days</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="activity-chart" height="200"></canvas>
                    </div>
                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> Quizzes
                        </span>
                        <span>
                            <i class="fas fa-square text-gray"></i> Live Exams
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donut Chart: Category -->
        <div class="col-md-3">
            <div class="card card-outline card-success shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">By Category</h3>
                </div>
                <div class="card-body">
                    <canvas id="category-chart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Donut Chart: Difficulty -->
        <div class="col-md-3">
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">By Difficulty</h3>
                </div>
                <div class="card-body">
                    <canvas id="level-chart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row -->
    <div class="row">
        <!-- Recent Quiz Attempts -->
        <div class="col-md-6">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Recent Quiz Activity</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Category</th>
                                    <th>Score</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentQuizAttempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->user->name }}</td>
                                    <td>{{ $attempt->category->name }}</td>
                                    <td>{{ $attempt->score }}/{{ $attempt->total_questions }}</td>
                                    <td>
                                        @if($attempt->passed)
                                            <span class="badge badge-success">Passed</span>
                                        @else
                                            <span class="badge badge-danger">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent activity</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Live Exam Attempts -->
        <div class="col-md-6">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Recent Live Exam Activity</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Exam</th>
                                    <th>Score</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentExamAttempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->user->name }}</td>
                                    <td>{{ $attempt->exam->title }}</td>
                                    <td>{{ $attempt->score }}</td>
                                    <td>
                                        @if($attempt->passed)
                                            <span class="badge badge-success">Passed</span>
                                        @else
                                            <span class="badge badge-danger">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent activity</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function () {
            'use strict'

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true

            // Activity Chart
            var $activityChart = $('#activity-chart')
            var activityChart = new Chart($activityChart, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($days) !!},
                    datasets: [
                        {
                            label: 'Quizzes',
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            data: {!! json_encode($quizData) !!}
                        },
                        {
                            label: 'Live Exams',
                            backgroundColor: '#ced4da',
                            borderColor: '#ced4da',
                            data: {!! json_encode($examData) !!}
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .05)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                precision: 0
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })

            // Category Chart
            var $categoryChart = $('#category-chart')
            var categoryChart = new Chart($categoryChart, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($categoryNames) !!},
                    datasets: [{
                        data: {!! json_encode($categoryQuestionCounts) !!},
                        backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#605ca8', '#f012be']
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    }
                }
            })

            // Level Chart
            var $levelChart = $('#level-chart')
            var levelChart = new Chart($levelChart, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($levelNames) !!},
                    datasets: [{
                        data: {!! json_encode($levelQuestionCounts) !!},
                        backgroundColor: ['#00c0ef', '#3c8dbc', '#d2d6de']
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    }
                }
            })
        })
    </script>
    @endpush
</x-admin-layout>
