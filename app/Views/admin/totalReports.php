<style>
.page-report__status a {
    text-decoration: none;
    color: inherit;
    display: flex;
    justify-content: space-between;
    width: 100%;
}
.page-report__status li { cursor: pointer; }
.page-report__status a:hover strong { text-decoration: underline; }

.btn-action {
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

<!-- ✅ Chart.js loaded FIRST before any chart code -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ✅ PHP data injected BEFORE the JS that uses it -->
<script>
    const reportData = <?= json_encode($reportStats) ?>;
    const monthlyStats = <?= json_encode(array_values($monthlyStats)) ?>;
    const selectedYear = <?= (int) $selectedYear ?>;
</script>

<div class="with-sidebar">
    <?php require BASE_PATH . '../app/Views/layouts/sidebar.php'; ?>

    <main class="page page-total-reports">

        <button class="btn-action" onclick="openPrintReport()">Print/Download Report</button>

        <header class="page__header">
            <h1 class="page__title">Total Reports</h1>
        </header>

        <div class="page__container">

            <!-- Overall Bar Chart -->
            <section class="page-reports">
                <div class="page-report__chart">
                    <canvas id="totalReportsChart"></canvas>
                </div>
            </section>

            <p class="page-report__subtitle">Incidents Submitted in the current month</p>

            <div class="page-report__summary">
                <h2 class="page-report__label">Total Reports</h2>
                <span class="page-report__count" id="totalReportsCount">0</span>
            </div>

            <ul class="page-report__status">
                <li>
                    <a href="/RMS/public/index.php?url=admin/reportsTable&status=ongoing&type=incident">
                        <span>Ongoing</span><strong id="ongoingCount">0</strong>
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

            <!-- Year Switcher -->
            <div style="display:flex; align-items:center; gap:12px; margin:2rem 0 0.5rem;">
                <h2 class="page-report__label">Monthly Breakdown</h2>
                <form method="GET" style="display:flex; align-items:center; gap:8px;">
                    <input type="hidden" name="url" value="admin/totalReports">
                    <select name="year" onchange="this.form.submit()"
                            style="padding:6px 12px; border-radius:6px; border:1px solid #ccc;">
                        <?php for ($y = (int)date('Y'); $y >= 2023; $y--): ?>
                            <option value="<?= $y ?>" <?= $y === $selectedYear ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </form>
            </div>

            <!-- Monthly Line Chart -->
            <section class="page-reports" style="margin-top:1rem;">
                <div class="page-report__chart">
                    <canvas id="monthlyReportsChart"></canvas>
                </div>
            </section>

            <!-- Monthly Table -->
            <section style="margin-top:1.5rem; overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
                    <thead>
                        <tr style="background:#1a237e; color:#fff;">
                            <th style="padding:10px;">Month</th>
                            <th style="padding:10px;">Total</th>
                            <th style="padding:10px;">Ongoing</th>
                            <th style="padding:10px;">Resolved</th>
                            <th style="padding:10px;">Rejected</th>
                            <th style="padding:10px;">Escalated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $monthNames = ['Jan','Feb','Mar','Apr','May','Jun',
                                       'Jul','Aug','Sep','Oct','Nov','Dec'];
                        foreach ($monthlyStats as $row):
                            $mn = (int)$row['month_num'] - 1;
                        ?>
                        <tr style="border-bottom:1px solid #eee; <?= $row['total'] == 0 ? 'color:#bbb;' : '' ?>">
                            <td style="padding:9px 10px;"><?= $monthNames[$mn] ?></td>
                            <td style="padding:9px 10px; font-weight:bold;"><?= $row['total'] ?></td>
                            <td style="padding:9px 10px;"><?= $row['ongoing'] ?></td>
                            <td style="padding:9px 10px;"><?= $row['resolved'] ?></td>
                            <td style="padding:9px 10px;"><?= $row['rejected'] ?></td>
                            <td style="padding:9px 10px;"><?= $row['escalated'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

        </div>
    </main>
</div>

<script>
// ── Overall bar chart ──
const ctx = document.getElementById('totalReportsChart').getContext('2d');

const total = reportData.pending + reportData.resolved +
              reportData.rejected + reportData.escalated;

document.getElementById('totalReportsCount').textContent = total;
document.getElementById('ongoingCount').textContent = reportData.ongoing;
document.getElementById('resolvedCount').textContent  = reportData.resolved;
document.getElementById('rejectedCount').textContent  = reportData.rejected;
document.getElementById('escalatedCount').textContent = reportData.escalated;

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Ongoing', 'Resolved', 'Rejected', 'Escalated'],
        datasets: [{
            label: 'Number of Reports',
            data: [
                reportData.ongoing,
                reportData.resolved,
                reportData.rejected,
                reportData.escalated
            ],
            backgroundColor: ['#f1c40f', '#3498db', '#2ecc71', '#e74c3c']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// ── Monthly line chart ──
const monthLabels = ['Jan','Feb','Mar','Apr','May','Jun',
                     'Jul','Aug','Sep','Oct','Nov','Dec'];

const ctx2 = document.getElementById('monthlyReportsChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: monthLabels,
        datasets: [
            {
                label: 'Total',
                data: monthlyStats.map(r => r.total),
                borderColor: '#1a237e',
                backgroundColor: 'rgba(26,35,126,0.08)',
                tension: 0.4, fill: true, pointRadius: 4
            },
            {
                label: 'Ongoing',
                data: monthlyStats.map(r => r.ongoing),
                borderColor: '#f1c40f', tension: 0.4, pointRadius: 3
            },
            {
                label: 'Resolved',
                data: monthlyStats.map(r => r.resolved),
                borderColor: '#3498db', tension: 0.4, pointRadius: 3
            },
            {
                label: 'Rejected',
                data: monthlyStats.map(r => r.rejected),
                borderColor: '#2ecc71', tension: 0.4, pointRadius: 3
            },
            {
                label: 'Escalated',
                data: monthlyStats.map(r => r.escalated),
                borderColor: '#e74c3c', tension: 0.4, pointRadius: 3
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>

<?php require BASE_PATH . '../app/Views/layouts/print-report.php'; ?>