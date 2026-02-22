const reportData = window.totalReportData || {
    total: 0,
    pending: 0,
    in_progress: 0,
    resolved: 0,
    rejected: 0
};

document.getElementById('totalReportsCount').textContent = reportData.total;
document.getElementById('pendingCount').textContent = reportData.pending;
document.getElementById('inProgressCount').textContent = reportData.in_progress;
document.getElementById('resolvedCount').textContent = reportData.resolved;
document.getElementById('rejectedCount').textContent = reportData.rejected;

// Chart.js
const ctx = document.getElementById('totalReportsChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Pending', 'In Progress', 'Resolved', 'Rejected'],
        datasets: [{
            label: 'Number of Reports',
            data: [
                reportData.pending,
                reportData.in_progress,
                reportData.resolved,
                reportData.rejected
            ],
            backgroundColor: ['#f1c40f','#3498db','#2ecc71','#e74c3c']
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});