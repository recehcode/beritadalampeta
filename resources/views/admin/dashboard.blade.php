@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="card stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,0.15);color:var(--accent);">
            <i class="ri-calendar-check-line"></i>
        </div>
        <div class="stat-value">{{ $todayCount }}</div>
        <div class="stat-label">Event Hari Ini</div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,0.15);color:var(--success);">
            <i class="ri-calendar-line"></i>
        </div>
        <div class="stat-value">{{ $weekCount }}</div>
        <div class="stat-label">Event 7 Hari Terakhir</div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon" style="background:rgba(168,85,247,0.15);color:#a855f7;">
            <i class="ri-map-pin-2-fill"></i>
        </div>
        <div class="stat-value">{{ $totalCount }}</div>
        <div class="stat-label">Total Event</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom:20px;font-size:16px;font-weight:600;">Event per Kategori</h3>
    <canvas id="categoryChart" height="100"></canvas>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categoryCounts->pluck('name')) !!},
            datasets: [{
                label: 'Jumlah Event',
                data: {!! json_encode($categoryCounts->pluck('events_count')) !!},
                backgroundColor: {!! json_encode($categoryCounts->pluck('color')->map(fn($c) => $c . '66')) !!},
                borderColor: {!! json_encode($categoryCounts->pluck('color')) !!},
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#8b8b9e', stepSize: 1 },
                    grid: { color: 'rgba(255,255,255,0.05)' }
                },
                x: {
                    ticks: { color: '#8b8b9e' },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
