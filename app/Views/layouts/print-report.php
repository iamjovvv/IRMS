

<!-- ===================== PRINT REPORT STYLES ===================== -->
<style>
/* ---- Screen: Modal Trigger ---- */
.btn-print-report {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
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
.btn-print-report:hover { background: #283593; }

/* ---- Print Report Modal (screen preview) ---- */
#printReportModal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 9999;
    justify-content: center;
    align-items: flex-start;
    padding: 30px 16px;
    overflow-y: auto;
}
#printReportModal.open {
    display: flex;
}
#printReportModal .modal-close {
    position: fixed;
    top: 18px;
    right: 24px;
    background: #fff;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    font-size: 20px;
    cursor: pointer;
    z-index: 10001;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
}
#printReportModal .modal-actions {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
    z-index: 10001;
}
#printReportModal .modal-actions button {
    padding: 10px 28px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    font-family: 'Georgia', serif;
}
.btn-do-print  { background: #1a237e; color: #fff; }
.btn-do-download { background: #fff; color: #1a237e; border: 1px solid #1a237e !important; }

/* ===================== FORMAL REPORT DOCUMENT ===================== */
#formalReport {
    background: #fff;
    width: 210mm;            /* A4 width */
    min-height: 297mm;
    margin: 0 auto;
    padding: 22mm 20mm 28mm;
    box-shadow: 0 4px 32px rgba(0,0,0,0.18);
    font-family: 'Georgia', 'Times New Roman', serif;
    color: #111;
    position: relative;
    box-sizing: border-box;
}

/* Header */
.rpt-header {
    display: flex;
    align-items: center;
    gap: 18px;
    border-bottom: 3px solid #1a237e;
    padding-bottom: 16px;
    margin-bottom: 10px;
}
.rpt-logo {
    width: 64px;
    height: 64px;
    object-fit: contain;
    flex-shrink: 0;
}
.rpt-logo-placeholder {
    width: 64px;
    height: 64px;
    border: 2px solid #1a237e;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: #1a237e;
    text-align: center;
    flex-shrink: 0;
    font-family: sans-serif;
}
.rpt-org {
    flex: 1;
}
.rpt-org-name {
    font-size: 17px;
    font-weight: bold;
    color: #1a237e;
    margin: 0 0 2px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.rpt-org-sub {
    font-size: 12px;
    color: #555;
    margin: 0;
    font-style: italic;
}
.rpt-doc-label {
    text-align: right;
    font-size: 11px;
    color: #888;
    font-family: 'Courier New', monospace;
    line-height: 1.6;
}

/* Title block */
.rpt-title-block {
    text-align: center;
    margin: 20px 0 8px;
}
.rpt-title-block h1 {
    font-size: 20px;
    color: #1a237e;
    margin: 0 0 4px;
    text-transform: uppercase;
    letter-spacing: 2px;
}
.rpt-title-block .rpt-period {
    font-size: 13px;
    color: #444;
    font-style: italic;
}
.rpt-divider {
    border: none;
    border-top: 1px solid #ccc;
    margin: 14px 0;
}

/* Summary row */
.rpt-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
    gap: 16px;
    margin: 18px 0;
}
.rpt-summary-box {
    flex: 1;
    border: 1.5px solid #1a237e;
    border-radius: 6px;
    padding: 14px 12px;
    text-align: center;
}
.rpt-summary-box.total {
    background: #1a237e;
    color: #fff;
}
.rpt-summary-box .rpt-box-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 6px;
    opacity: 0.85;
}
.rpt-summary-box .rpt-box-count {
    font-size: 32px;
    font-weight: bold;
    line-height: 1;
}
.rpt-summary-box.pending  .rpt-box-count { color: #b8860b; }
.rpt-summary-box.resolved .rpt-box-count { color: #1565c0; }
.rpt-summary-box.rejected .rpt-box-count { color: #2e7d32; }
.rpt-summary-box.escalated .rpt-box-count { color: #c62828; }

/* Chart section */
.rpt-section-title {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #1a237e;
    border-left: 4px solid #1a237e;
    padding-left: 10px;
    margin: 22px 0 12px;
}
.rpt-chart-wrap {
    width: 100%;
    max-height: 240px;
}

/* Status breakdown table */
.rpt-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    margin-top: 10px;
}
.rpt-table thead tr {
    background: #1a237e;
    color: #fff;
}
.rpt-table thead th {
    padding: 9px 12px;
    text-align: left;
    font-size: 12px;
    letter-spacing: 0.5px;
    font-weight: normal;
    text-transform: uppercase;
}
.rpt-table tbody tr:nth-child(even) { background: #f5f6fa; }
.rpt-table tbody td {
    padding: 9px 12px;
    border-bottom: 1px solid #e0e0e0;
}
.rpt-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 0.5px;
}
.badge-pending   { background: #fff8e1; color: #b8860b; border: 1px solid #f1c40f; }
.badge-resolved  { background: #e3f2fd; color: #1565c0; border: 1px solid #3498db; }
.badge-rejected  { background: #e8f5e9; color: #2e7d32; border: 1px solid #2ecc71; }
.badge-escalated { background: #ffebee; color: #c62828; border: 1px solid #e74c3c; }
.rpt-pct {
    font-size: 12px;
    color: #888;
}

/* Signature / footer */
.rpt-footer {
    margin-top: 36px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}
.rpt-sig-block {
    text-align: center;
    width: 200px;
}
.rpt-sig-line {
    border-top: 1px solid #333;
    margin-bottom: 4px;
    margin-top: 36px;
}
.rpt-sig-name { font-size: 13px; font-weight: bold; }
.rpt-sig-title { font-size: 11px; color: #666; }
.rpt-footer-note {
    font-size: 10px;
    color: #aaa;
    font-style: italic;
    text-align: right;
    line-height: 1.6;
}

/* Watermark */
.rpt-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-35deg);
    font-size: 80px;
    color: rgba(26,35,126,0.04);
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 10px;
    pointer-events: none;
    user-select: none;
    white-space: nowrap;
}

/* ===================== @PRINT STYLES ===================== */
@media print {
    /* Hide everything on the page */
    body > *:not(#printReportModal) { display: none !important; }

    /* Show only the report */
    #printReportModal {
        display: block !important;
        position: static !important;
        background: none !important;
        padding: 0 !important;
        overflow: visible !important;
    }
    .modal-close,
    .modal-actions { display: none !important; }

    #formalReport {
        box-shadow: none !important;
        margin: 0 !important;
        padding: 15mm 15mm 20mm !important;
        width: 100% !important;
    }

    @page {
        size: A4 portrait;
        margin: 0;
    }
}
</style>


<!-- ===================== PRINT REPORT MODAL ===================== -->
<div id="printReportModal">
    <button class="modal-close" onclick="closePrintReport()" title="Close">&#10005;</button>

    <!-- FORMAL REPORT DOCUMENT -->
    <div id="formalReport">
        <div class="rpt-watermark">OFFICIAL</div>

        <!-- Header -->
        <div class="rpt-header">
            <!-- Replace src with your actual logo path, or remove img and use placeholder div -->
            <div class="rpt-logo-placeholder">
                <img src="/RMS/assets/img/uep_logo.png" class="header-logo" alt="UEP Logo">
            </div>
            <div class="rpt-org">
                <p class="rpt-org-name">University of Eastern Philippines (UEP)</p>
                <p class="rpt-org-sub">University Town, Catarman, Northern Samar</p>
                <p class="rpt-org-sub">Website: uep.edu.ph &mdash; Email: uepnsofficial@gmail.com</p>
                
            </div>
            <div class="rpt-doc-label">
                <div>Doc No: RMS-<span id="rpt-docno"></span></div>
                <div>Generated: <span id="rpt-generated"></span></div>
                <div>Prepared by: Admin</div>
            </div>
        </div>

        <!-- Title -->
        <div class="rpt-title-block">
            <h1>Monthly Incident Report</h1>
            <div class="rpt-period">Reporting Period: <span id="rpt-period"></span></div>
        </div>
        <hr class="rpt-divider">

        <!-- Summary Boxes -->
        <div class="rpt-section-title">Summary</div>
        <div class="rpt-summary-row">
            <div class="rpt-summary-box total">
                <div class="rpt-box-label">Total Reports</div>
                <div class="rpt-box-count" id="rpt-total">0</div>
            </div>
            <div class="rpt-summary-box pending">
                <div class="rpt-box-label">Pending</div>
                <div class="rpt-box-count" id="rpt-pending">0</div>
            </div>
            <div class="rpt-summary-box resolved">
                <div class="rpt-box-label">Resolved</div>
                <div class="rpt-box-count" id="rpt-resolved">0</div>
            </div>
            <div class="rpt-summary-box rejected">
                <div class="rpt-box-label">Rejected</div>
                <div class="rpt-box-count" id="rpt-rejected">0</div>
            </div>
            <div class="rpt-summary-box escalated">
                <div class="rpt-box-label">Escalated</div>
                <div class="rpt-box-count" id="rpt-escalated">0</div>
            </div>
        </div>

        <!-- Chart -->
        <div class="rpt-section-title">Visual Breakdown</div>
        <div class="rpt-chart-wrap">
            <canvas id="printReportChart"></canvas>
        </div>

        <!-- Status Table -->
        <div class="rpt-section-title" style="margin-top:24px;">Status Breakdown</div>
        <table class="rpt-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage of Total</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody id="rpt-table-body">
                <!-- Populated by JS -->
            </tbody>
        </table>

        <!-- Signature / Footer -->
        <div class="rpt-footer">
            <div class="rpt-sig-block">
                <div class="rpt-sig-line"></div>
                <div class="rpt-sig-name">Report Officer</div>
                <div class="rpt-sig-title">Prepared by</div>
            </div>
            <div class="rpt-sig-block">
                <div class="rpt-sig-line"></div>
                <div class="rpt-sig-name">Authorized Signatory</div>
                <div class="rpt-sig-title">Approved by</div>
            </div>
            <div class="rpt-footer-note">
                This is a system-generated report.<br>
                Report Management System &copy; <span id="rpt-year"></span>
            </div>
        </div>
    </div><!-- /#formalReport -->

    <!-- Action buttons -->
    <div class="modal-actions">
        <button class="btn-do-download" onclick="downloadReport()">&#8595; Download PDF</button>
        <button class="btn-do-print" onclick="window.print()">&#128438; Print</button>
    </div>
</div><!-- /#printReportModal -->


<!-- Required for Download PDF button — must load before the script block below -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- ===================== JS ===================== -->
<script>
let printChart = null;

function openPrintReport() {
    // Pull data from the existing reportData variable on the page
    const data = (typeof reportData !== 'undefined') ? reportData : {
        total: 0, pending: 0, resolved: 0, rejected: 0, escalated: 0
    };

    const total = (data.pending || 0) + (data.resolved || 0) + (data.rejected || 0) + (data.escalated || 0);
    const pct = (n) => total > 0 ? ((n / total) * 100).toFixed(1) + '%' : '0%';

    // Date info
    const now = new Date();
    const monthName = now.toLocaleString('default', { month: 'long' });
    const year = now.getFullYear();
    const dateStr = now.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });

    document.getElementById('rpt-period').textContent = `${monthName} ${year}`;
    document.getElementById('rpt-generated').textContent = dateStr;
    document.getElementById('rpt-docno').textContent = `${year}${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
    document.getElementById('rpt-year').textContent = year;

    // Counts
    document.getElementById('rpt-total').textContent    = total;
    document.getElementById('rpt-pending').textContent  = data.pending   || 0;
    document.getElementById('rpt-resolved').textContent = data.resolved  || 0;
    document.getElementById('rpt-rejected').textContent = data.rejected  || 0;
    document.getElementById('rpt-escalated').textContent= data.escalated || 0;

    // Table
    const rows = [
        { label: 'Pending',   cls: 'pending',   val: data.pending   || 0, remark: 'Awaiting action'      },
        { label: 'Resolved',  cls: 'resolved',  val: data.resolved  || 0, remark: 'Closed / Completed'   },
        { label: 'Rejected',  cls: 'rejected',  val: data.rejected  || 0, remark: 'Invalidated'          },
        { label: 'Escalated', cls: 'escalated', val: data.escalated || 0, remark: 'Forwarded to higher authority' },
    ];
    document.getElementById('rpt-table-body').innerHTML = rows.map(r => `
        <tr>
            <td><span class="rpt-badge badge-${r.cls}">${r.label}</span></td>
            <td><strong>${r.val}</strong></td>
            <td class="rpt-pct">${pct(r.val)}</td>
            <td>${r.remark}</td>
        </tr>
    `).join('');

    // Show modal
    document.getElementById('printReportModal').classList.add('open');

    // Draw chart (destroy old one first)
    if (printChart) { printChart.destroy(); }
    const ctx = document.getElementById('printReportChart').getContext('2d');
    printChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Resolved', 'Rejected', 'Escalated'],
            datasets: [{
                label: 'Number of Reports',
                data: [data.pending || 0, data.resolved || 0, data.rejected || 0, data.escalated || 0],
                backgroundColor: ['#f1c40f', '#3498db', '#2ecc71', '#e74c3c'],
                borderRadius: 4,
                barThickness: 48,
            }]
        },
        options: {
            responsive: true,
            animation: false,       // no animation so it renders for print
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, precision: 0 }
            }
        }
    });
}

function closePrintReport() {
    document.getElementById('printReportModal').classList.remove('open');
}

async function downloadReport() {
    const btn = document.querySelector('.btns-secondary');  // ← correct class for final-report
    const noprint = document.querySelector('.no-print');
   const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Generating PDF...';

    noprint.style.display = 'none';  // hide floating buttons from PDF
    btn.innerHTML = '⏳ Generating...';
    btn.disabled = true;

    try {
        const { jsPDF } = window.jspdf;
        const element = document.querySelector('.report-document');

        const canvas = await html2canvas(element, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            scrollX: 0,
            scrollY: -window.scrollY,
            windowWidth: document.documentElement.scrollWidth,
            windowHeight: document.documentElement.scrollHeight
        });

        const imgData = canvas.toDataURL('image/png');
        const pdf     = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        const pageW   = pdf.internal.pageSize.getWidth();
        const pageH   = pdf.internal.pageSize.getHeight();
        const imgH    = (canvas.height * pageW) / canvas.width;

        let remaining = imgH;
        let yOffset   = 0;

        while (remaining > 0) {
            pdf.addImage(imgData, 'PNG', 0, yOffset === 0 ? 0 : -(imgH - remaining), pageW, imgH);
            remaining -= pageH;
            if (remaining > 0) { pdf.addPage(); yOffset -= pageH; }
        }

        pdf.save('Incident_Report_<?= htmlspecialchars($incident['tracking_code'] ?? 'report') ?>.pdf');

    } catch (err) {
        console.error('PDF generation failed:', err);
        alert('PDF download failed. Please try the Print button instead.');
    } finally {
        noprint.style.display = 'flex';  // restore buttons
        btn.innerHTML = originalText;
        btn.disabled  = false;
    }
}

// Close modal when clicking outside the document
document.getElementById('printReportModal').addEventListener('click', function(e) {
    if (e.target === this) closePrintReport();
});
</script>