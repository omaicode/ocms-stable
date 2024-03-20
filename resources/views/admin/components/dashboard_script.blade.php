<script>
    function initSessionChart() {
        const ctx = document.getElementById('session-chart');
        new Chart(ctx, {
            type: 'line',
            data: {
            labels: {!! json_encode($analytics->pluck('date')->map(fn($x) => \Carbon\Carbon::parse($x)->format('Y-m-d'))->toArray()) !!},
            datasets: [{
                label: 'Views',
                data: {!! $analytics->pluck('screenPageViews')->toJson() !!},
                borderWidth: 1
            }, {
                label: 'Users',
                data: {!! $analytics->pluck('totalUsers')->toJson() !!},
                borderWidth: 1
            }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        });
    }

    initSessionChart();
</script>
