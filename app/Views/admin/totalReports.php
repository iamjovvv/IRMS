
<style>
    .page-report__status a {
    text-decoration: none;
    color: inherit;
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.page-report__status li {
    cursor: pointer;
}

.page-report__status a:hover strong {
    text-decoration: underline;
}


.btn-action{
    gap: 8px;
    padding: 10px 20px;
    display: inline-flex;
    align-items: center;
    background: #1a237e;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-family: 'Georgia', serif;
    cursor: pointer;
    letter-spacing: 0.5px;
    transition: background 0.2s;
}

.btn-action:hover { background: #283593; }



</style>

<div class="with-sidebar">
    <?php require BASE_PATH . '../app/Views/layouts/sidebar.php'; ?>

    <main class="page page-total-reports">

   
        <button class="btn-action" onclick="openPrintReport()">Print/Download Report</button>
 

    

        <header class="page__header">
            <h1 class="page__title">Total Reports</h1>
        </header>


        <div class="page__container">
            
            <section class="page-reports">
                <div class="page-report__chart">
                    <canvas id="totalReportsChart"></canvas>
                </div>
            </section>

            <p class="page-report__subtitle">
                Incidents Submitted in the current month
            </p>

            <div class="page-report__summary">
                <h2 class="page-report__label">Total Reports</h2>
                <span class="page-report__count" id="totalReportsCount">0</span>
            </div>



          <ul class="page-report__status">
    <li>
        <a href="/RMS/public/index.php?url=admin/reportsTable&status=new&type=incident">
            <span>Pending</span><strong id="pendingCount">0</strong>
        </a>
    </li>
    <li>
        <a href="/RMS/public/index.php?url=admin/reportsTable&status=resolved&type=incident">
            <span>Resolved</span><strong id="resolvedCount">0</strong>
        </a>
    </li>
    <li>
        <a href="/RMS/public/index.php?url=admin/reportsTable&status=invalidated&type=incident">
            <span>Rejected</span><strong id="rejectedCount">0</strong>
        </a>
    </li>
    <li>
        <a href="/RMS/public/index.php?url=admin/reportsTable&status=escalated&type=incident">
            <span>Escalated</span><strong id="escalatedCount">0</strong>
        </a>
    </li>
</ul>

        </div>
    </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Pass PHP data to JS
    const reportData = <?= json_encode($reportStats) ?> || {
total: 0,
pending: 0,
resolved: 0,
rejected: 0,
escalated: 0
};

    // Update HTML counts
document.getElementById('totalReportsCount').textContent = reportData.total;
document.getElementById('pendingCount').textContent = reportData.pending;
document.getElementById('resolvedCount').textContent = reportData.resolved;
document.getElementById('rejectedCount').textContent = reportData.rejected;
document.getElementById('escalatedCount').textContent = reportData.escalated;
    // Chart.js
    const ctx = document.getElementById('totalReportsChart').getContext('2d');

    const total =
    reportData.pending +
    reportData.resolved +
    reportData.rejected +
    reportData.escalated;

document.getElementById('totalReportsCount').textContent = total;

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Resolved', 'Rejected', 'Escalated'],
datasets: [{
    label: 'Number of Reports',
    data: [
        reportData.pending,
        reportData.resolved,
        reportData.rejected,
        reportData.escalated
    ],
    backgroundColor: [
        '#f1c40f', // Pending
        '#3498db', // Resolved
        '#2ecc71', // Rejected
        '#e74c3c'  // Escalated
    ]
}]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>


<?php require BASE_PATH . '../app/Views/layouts/print-report.php'; ?>