document.addEventListener('DOMContentLoaded', () =>{
    const ctx = document.getElementById('totalReportsChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'In Progress', 'Resolved', 'Rejected'],
            datasets: [{
                label: 'Reports',
                data: [40, 65, 110, 19],
                borderRadius: 6
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

