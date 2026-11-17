import Chart from 'chart.js/auto';

const renderScreeningTrend = () => {
    const trendCanvas = document.querySelector('#screening-trend-chart');

    if (!trendCanvas || trendCanvas.dataset.chartReady) {
        return;
    }

    const labels = JSON.parse(trendCanvas.dataset.labels || '[]');
    const values = JSON.parse(trendCanvas.dataset.values || '[]');

    new Chart(trendCanvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: values,
                borderColor: '#5eead4',
                backgroundColor: 'rgba(45, 212, 191, 0.16)',
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#99f6e4',
                pointBorderColor: '#0f172a',
                pointBorderWidth: 2,
                pointRadius: 4,
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => ` Score: ${Number(context.raw).toFixed(1)}/100`,
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' },
                },
                y: {
                    min: 0,
                    max: 100,
                    grid: { color: 'rgba(255,255,255,0.08)' },
                    ticks: { color: '#64748b' },
                },
            },
        },
    });

    trendCanvas.dataset.chartReady = 'true';
};

renderScreeningTrend();
document.addEventListener('livewire:navigated', renderScreeningTrend);
