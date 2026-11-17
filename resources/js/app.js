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

const renderRiskDistribution = () => {
    const riskCanvas = document.querySelector('#risk-distribution-chart');

    if (!riskCanvas || riskCanvas.dataset.chartReady) {
        return;
    }

    new Chart(riskCanvas, {
        type: 'doughnut',
        data: {
            labels: JSON.parse(riskCanvas.dataset.labels || '[]'),
            datasets: [{
                data: JSON.parse(riskCanvas.dataset.values || '[]'),
                backgroundColor: ['#34d399', '#fbbf24', '#fb7185', '#a78bfa'],
                borderColor: '#0f172a',
                borderWidth: 4,
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#cbd5e1', padding: 18 } },
            },
        },
    });

    riskCanvas.dataset.chartReady = 'true';
};

renderScreeningTrend();
renderRiskDistribution();
document.addEventListener('livewire:navigated', () => {
    renderScreeningTrend();
    renderRiskDistribution();
});
