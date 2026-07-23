@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold m-0">Performance Rankings</h2>
            <p class="text-secondary m-0">Real-time leaderboard & metrics comparison</p>
        </div>
        
        <!-- Filters Form -->
        <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap bg-secondary bg-opacity-10 p-2 rounded-3 border border-secondary border-opacity-10">
            <div>
                <label class="form-label small text-secondary mb-1">Department</label>
                <select name="department_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $selectedDeptId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($selectedDeptId)
                <div class="align-self-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-danger" title="Clear Filters">
                        <i class="fa-solid fa-filter-circle-xmark"></i>
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <!-- Top Performer -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase">Top Performer</span>
                            <h4 class="fw-bold mt-1 text-success text-truncate" style="max-width: 170px;">
                                {{ $topPerformer ? $topPerformer->name : 'N/A' }}
                            </h4>
                        </div>
                        <div class="bg-success bg-opacity-20 text-success p-2 rounded-3 card-box">
                            <img src="{{('assets/img/top-performer.png')}}" class="img-fluid">
                        </div>
                    </div>
                    @if($topPerformer)
                        <div class="small">
                            <div class="d-flex justify-content-between text-secondary mb-1">
                                <span>Target:</span>
                                <span class="text-white">${{ number_format($topPerformer->target_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-secondary mb-1">
                                <span>Sales:</span>
                                <span class="text-white">${{ number_format($topPerformer->total_sales, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-secondary">
                                <span>Achieved:</span>
                                <span class="fw-bold text-success">{{ $topPerformer->performance_percentage }}%</span>
                            </div>
                        </div>
                    @else
                        <div class="text-secondary small">No records found.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Lowest Performer -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase">Lowest Performer</span>
                            <h4 class="fw-bold mt-1 text-danger text-truncate" style="max-width: 170px;">
                                {{ $lowestPerformer ? $lowestPerformer->name : 'N/A' }}
                            </h4>
                        </div>
                        <div class="bg-danger bg-opacity-20 text-danger p-2 rounded-3  card-box">
                          <img src="{{('assets/img/low-performer.png')}}" class="img-fluid">
                        </div>
                    </div>
                    @if($lowestPerformer)
                        <div class="small">
                            <div class="d-flex justify-content-between text-secondary mb-1">
                                <span>Target:</span>
                                <span class="text-white">${{ number_format($lowestPerformer->target_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-secondary mb-1">
                                <span>Sales:</span>
                                <span class="text-white">${{ number_format($lowestPerformer->total_sales, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-secondary">
                                <span>Achieved:</span>
                                <span class="fw-bold text-danger">{{ $lowestPerformer->performance_percentage }}%</span>
                            </div>
                        </div>
                    @else
                        <div class="text-secondary small">No records found.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Total Monthly Sales -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-start border-indigo border-4" style="border-color: var(--accent-primary) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase">Total Sales</span>
                            <h3 class="fw-bold mt-1 text-white">${{ number_format($totalSales, 2) }}</h3>
                        </div>
                        <div class="bg-indigo bg-opacity-20 text-indigo p-2 rounded-3" style="color: var(--accent-primary); background-color: rgba(99, 102, 241, 0.2) !important;">
                            <i class="fa-solid fa-coins fs-4"></i>
                        </div>
                    </div>
                    <div class="small">
                        <div class="d-flex justify-content-between text-secondary mb-1">
                            <span>Total Target:</span>
                            <span class="text-white">${{ number_format($totalTarget, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-secondary">
                            <span>Total Reps:</span>
                            <span class="text-white">{{ $ranked->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Performance -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-secondary small fw-medium text-uppercase">Avg. Achieved</span>
                            <h3 class="fw-bold mt-1 text-warning">{{ $averagePerformance }}%</h3>
                        </div>
                        <div class="bg-warning bg-opacity-20 text-warning p-2 rounded-3 card-box">
                             <img src="{{('assets/img/avg-achive.png')}}" class="img-fluid">
                        </div>
                    </div>
                    <div class="progress mb-2 bg-dark" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min($averagePerformance, 100) }}%"></div>
                    </div>
                    <span class="text-secondary small">Average of all ranked team targets</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart and Ranking Table -->
    <div class="row g-4">
        <!-- Performance Bar Chart -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="m-0"><i class="fa-solid fa-chart-column me-2 text-primary"></i>Performance Comparison (%)</span>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 350px;">
                    @if($ranked->count() > 0)
                        <canvas id="performanceChart" style="max-height: 330px; width: 100%;"></canvas>
                    @else
                        <div class="text-secondary">No data available to display chart.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ranking Table -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <span><i class="fa-solid fa-list-ol me-2 text-primary"></i>Leaderboard Rankings</span>
                </div>
                <div class="card-body">
                    @if($ranked->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="leaderboardTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 70px;">Rank</th>
                                        <th>Name</th>
                                        <th>Target</th>
                                        <th>Sales</th>
                                        <th>% Achieved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ranked as $index => $sp)
                                        @php
                                            $rank = $index + 1;
                                            $rowClass = '';
                                            if ($topPerformer && $sp->id === $topPerformer->id) {
                                                $rowClass = 'table-success bg-success bg-opacity-10';
                                            } elseif ($lowestPerformer && $sp->id === $lowestPerformer->id) {
                                                $rowClass = 'table-danger bg-danger bg-opacity-10';
                                            }
                                            
                                            // Progress bar color
                                            $barColor = 'bg-danger';
                                            if ($sp->performance_percentage >= 100) {
                                                $barColor = 'bg-success';
                                            } elseif ($sp->performance_percentage >= 75) {
                                                $barColor = 'bg-info';
                                            } elseif ($sp->performance_percentage >= 50) {
                                                $barColor = 'bg-warning';
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td class="text-center fw-bold">
                                                @if($rank === 1)
                                                    <span class="badge badge-gold rounded-pill px-2.5 py-1">🥇 1</span>
                                                @elseif($rank === 2)
                                                    <span class="badge badge-silver rounded-pill px-2.5 py-1">🥈 2</span>
                                                @elseif($rank === 3)
                                                    <span class="badge badge-bronze rounded-pill px-2.5 py-1">🥉 3</span>
                                                @else
                                                    <span class="text-secondary">#{{ $rank }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $sp->name }}</div>
                                                <div class="small text-secondary">{{ $sp->department->name ?? 'No Department' }}</div>
                                            </td>
                                            <td>${{ number_format($sp->target_amount, 2) }}</td>
                                            <td>${{ number_format($sp->total_sales, 2) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-between mb-1 small">
                                                    <span class="fw-bold">{{ $sp->performance_percentage }}%</span>
                                                </div>
                                                <div class="progress bg-dark" style="height: 6px; width: 100px;">
                                                    <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ min($sp->performance_percentage, 100) }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-folder-open fs-2 mb-3"></i>
                            <p class="mb-0">No sales or targets records found for the selected filter criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#leaderboardTable').DataTable({
            "order": [], // Respect PHP sort order
            "pageLength": 10,
            "lengthChange": false,
            "searching": false,
            "info": false
        });

        @if($ranked->count() > 0)
        // ChartJS Setup
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const names = {!! json_encode($ranked->pluck('name')) !!};
        const percentages = {!! json_encode($ranked->pluck('performance_percentage')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: names,
                datasets: [{
                    label: 'Performance Achieved (%)',
                    data: percentages,
                    backgroundColor: percentages.map(val => {
                        if (val >= 100) return 'rgba(16, 185, 129, 0.7)'; // success
                        if (val >= 75) return 'rgba(99, 102, 241, 0.7)'; // accent
                        if (val >= 50) return 'rgba(245, 158, 11, 0.7)'; // warning
                        return 'rgba(239, 68, 68, 0.7)'; // danger
                    }),
                    borderColor: percentages.map(val => {
                        if (val >= 100) return 'rgb(16, 185, 129)';
                        if (val >= 75) return 'rgb(99, 102, 241)';
                        if (val >= 50) return 'rgb(245, 158, 11)';
                        return 'rgb(239, 68, 68)';
                    }),
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y}% Achieved`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#9ca3af',
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });
        @endif
    });
</script>
@endsection
