@extends('layouts.layout')

@section('title', 'Dashboard - WACS System')

@section('content')
    <div class="header">
        <h2>Dashboard Overview</h2>
    </div>
    <div class="stats-box">
       <h4>Total Candidates: {{ $totalCandidates }}</h4>

    <table class="table">
        <thead>
            <tr>
                <th>Faculty</th>
                <th>Number of Candidates</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facultyStats as $stat)
                <tr>
                    <td>{{ $stat->faculty_name }}</td>
                    <td>{{ $stat->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="chart-container">
        <h4>Candidates by Faculty</h4>
        <canvas id="facultyChart" height="100"></canvas>
    </div>
@endsection

@section('scripts')
<script>
    const facultyData = {!! json_encode($facultyStats->pluck('total')) !!};
    const facultyLabels = {!! json_encode($facultyStats->pluck('faculty_name')) !!};

    const ctx = document.getElementById('facultyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: facultyLabels,
            datasets: [{
                label: 'Number of Candidates',
                data: facultyData,
                backgroundColor: '#3498db',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
