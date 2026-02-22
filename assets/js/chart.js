const ctx = document.getElementById('totalReportsChart').getContext('2d');

const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Pending', 'In Progress', 'Resolved', 'Rejected'],
        datasets: [{
            label: 'Number of Reports',
            data: [
                window.totalReportData.pending,
                window.totalReportData.in_progress,
                window.totalReportData.resolved,
                window.totalReportData.rejected
            ],
            backgroundColor: [
                '#f1c40f',
                '#3498db',
                '#2ecc71',
                '#e74c3c'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});