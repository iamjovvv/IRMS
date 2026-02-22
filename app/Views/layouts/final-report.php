<div class="with-sidebar">

<?php require_once BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>
<?php
$incident        = $incident ?? [];
$reporter        = $reporter ?? [];
$response        = $response ?? [];
$assessment      = $assessment ?? [];
$escalation      = $escalation ?? [];
$involvedParties = $involvedParties ?? [];
$media           = $media ?? [];

function e($value) {
    return htmlspecialchars($value ?? '');
}

$today = date('F d, Y');
$docNo = 'RMS-' . date('Ymd') . '-' . strtoupper(substr($incident['tracking_code'] ?? 'RPT', 0, 6));
?>

<style>
/* ======================
   GENERAL
====================== */
body {
    font-family: 'Georgia', 'Times New Roman', serif;
    margin: 0;
    background: #f0f0f0;
    color: #111;
}

.page {
    display: flex;
    justify-content: center;
    padding: 20px 0 60px;
}

/* ======================
   BUTTONS
====================== */
.no-print {
      position: fixed;
    top: 120px;
    right: 30px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btns-primary,
.btns-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
    font-family: 'Georgia', serif;
    letter-spacing: 0.5px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    white-space: nowrap;
}

.btns-primary         { background: #1a237e; color: #fff; }
.btns-primary:hover   { background: #283593; }
.btns-secondary       { background: #fff; color: #1a237e; border: 1px solid #1a237e; }
.btns-secondary:hover { background: #e8eaf6; }


/* ======================
   REPORT DOCUMENT
====================== */
.report-document {
    width: 210mm;
    min-height: 297mm;
    background: #fff;
    padding: 20mm 20mm 35mm;
    position: relative;
    box-shadow: 0 4px 32px rgba(0,0,0,0.18);
    box-sizing: border-box;
}

/* ======================
   WATERMARK
====================== */
.rpt-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-35deg);
    font-size: 80px;
    color: rgba(26, 35, 126, 0.04);
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 10px;
    pointer-events: none;
    user-select: none;
    white-space: nowrap;
    z-index: 0;
}

/* ======================
   HEADER
====================== */
.report-header {
    display: flex;
    align-items: center;
    gap: 18px;
    border-bottom: 3px solid #1a237e;
    padding-bottom: 16px;
    margin-bottom: 6px;
    position: relative;
    z-index: 1;
}

.header-logo {
    width: 64px;
    height: 64px;
    object-fit: contain;
    flex-shrink: 0;
}

.header-text {
    flex: 1;
}

.univ-name {
    font-size: 15px;
    font-weight: bold;
    color: #1a237e;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin: 0 0 3px;
}

.header-text p {
    margin: 2px 0;
    font-size: 12px;
    color: #555;
    font-style: italic;
}

.rpt-doc-label {
    text-align: right;
    font-size: 11px;
    color: #888;
    font-family: 'Courier New', monospace;
    line-height: 1.7;
    flex-shrink: 0;
}

/* ======================
   MAIN TITLE
====================== */
.report-main-title {
    text-align: center;
    margin: 18px 0 4px;
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 2px;
    color: #1a237e;
    text-transform: uppercase;
    position: relative;
    z-index: 1;
}

.rpt-divider {
    border: none;
    border-top: 1px solid #ccc;
    margin: 12px 0 18px;
}

/* ======================
   SECTION TITLES
====================== */
.section-title {
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #1a237e;
    border-left: 4px solid #1a237e;
    padding-left: 10px;
    margin: 22px 0 10px;
    position: relative;
    z-index: 1;
}

/* ======================
   CASE INFO
====================== */
.case-info {
    margin: 10px 0 18px;
    font-size: 13px;
    line-height: 1.9;
    position: relative;
    z-index: 1;
}

.case-info div { margin-bottom: 3px; }

.paragraph {
    margin: 10px 0 18px;
    text-align: justify;
    line-height: 1.8;
    font-size: 13px;
    position: relative;
    z-index: 1;
}

/* ======================
   TABLES
====================== */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 8px 0 18px;
    table-layout: fixed;
    font-size: 13px;
    position: relative;
    z-index: 1;
}

th, td {
    border: 1px solid #c5c5c5;
    padding: 7px 10px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

thead tr th {
    background: #1a237e;
    color: #fff;
    text-align: left;
    font-size: 12px;
    font-weight: normal;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

tbody tr:nth-child(even) { background: #f5f6fa; }

/* ======================
   MEDIA
====================== */
.media__submitted {
    margin: 12px 0 20px;
    font-size: 13px;
    position: relative;
    z-index: 1;
}

.media-grid { margin-top: 8px; }

.incident-image {
    width: 200px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    padding: 3px;
}

/* ======================
   SIGNATURE SECTION
====================== */
.signature-section {
    margin-top: 50px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    position: relative;
    z-index: 1;
}

.signature-block {
    text-align: center;
    width: 220px;
}

.signature-line-bar {
    border-top: 1px solid #333;
    margin-top: 44px;
    margin-bottom: 5px;
}

.sig-name  { font-size: 13px; font-weight: bold; color: #111; }
.sig-title { font-size: 11px; color: #666; }

/* ======================
   FOOTER
====================== */
.report-footer {
    position: absolute;
    bottom: 10mm;
    left: 20mm;
    right: 20mm;
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    border-top: 1px solid #1a237e;
    padding-top: 5px;
    color: #555;
    z-index: 1;
}

/* ======================
   PRINT
====================== */
@media print {
    body * { visibility: hidden; }

    .report-document,
    .report-document * { visibility: visible; }

    .report-document {
        position: absolute;
        left: 0; top: 0;
        width: 100%;
        box-shadow: none;
        padding: 15mm 15mm 20mm;
    }

    body { background: white; }

    @page {
        size: A4 portrait;
        margin: 0;
    }

    table { page-break-inside: avoid; }
    tr    { page-break-inside: avoid; }
}

@media print {
    .page-number::after { content: counter(page); }
}
</style>

<!-- Buttons -->
<div class="no-print">
    <button class="btns-primary"  onclick="window.print()">🖨 Print Report</button>
    <button class="btns-secondary" onclick="downloadReport()">⬇ Download PDF</button>
</div>

<main class="page">
    <div class="report-document">

        <!-- Watermark -->
        <div class="rpt-watermark">OFFICIAL</div>

        <!-- ===== HEADER ===== -->
        <div class="report-header">
            <img src="/RMS/assets/img/uep_logo.png" class="header-logo" alt="UEP Logo">
            <div class="header-text">
                <p class="univ-name">University of Eastern Philippines</p>
                <p>University Town, Catarman, Northern Samar</p>
                <p>Website: uep.edu.ph &mdash; Email: uepnsofficial@gmail.com</p>
            </div>
            <div class="rpt-doc-label">
                <div>Doc No: <?= $docNo ?></div>
                <div>Generated: <?= $today ?></div>
                <div>Status: <?= e(ucfirst($incident['status'] ?? 'N/A')) ?></div>
            </div>
        </div>

        <h2 class="report-main-title">Incident Resolution Report</h2>
        <hr class="rpt-divider">

        <!-- ===== CASE INFORMATION ===== -->
        <div class="section-title">Case Information</div>
        <div class="case-info">
            <div><strong>Subject:</strong> <?= e($incident['subject'] ?? 'N/A') ?></div>
            <div><strong>Tracking Code:</strong> <?= e($incident['tracking_code'] ?? 'N/A') ?></div>
            <div><strong>Date Filed:</strong> <?= e($incident['date_of_incident'] ?? 'N/A') ?></div>
            <div><strong>Date Resolved:</strong> <?= e($response['resolution_date'] ?? 'N/A') ?></div>
            <div><strong>Priority:</strong> <?= e($incident['priority'] ?? 'N/A') ?></div>
            <div><strong>Status:</strong> <?= e(ucfirst($incident['status'] ?? 'N/A')) ?></div>
        </div>

        <p class="paragraph">
            This report pertains to Incident Tracking Code <strong><?= e($incident['tracking_code']) ?></strong> and presents the verified facts, actions undertaken, and final disposition of the case.
        </p>

        <!-- ===== INCIDENT DETAILS ===== -->
        <div class="section-title">Incident Details</div>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= e($incident['subject']) ?></td>
                    <td><?= e($incident['date_of_incident']) ?></td>
                    <td><?= e($incident['time_of_incident']) ?></td>
                    <td><?= e(ucfirst($incident['category'])) ?></td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Building</th>
                    <th>Department</th>
                    <th>Landmark</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= e($incident['location_building']) ?></td>
                    <td><?= e($incident['location_department']) ?></td>
                    <td><?= e($incident['location_landmark']) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Media -->
        <div class="media__submitted">
            <strong>Media:</strong>
            <?php if (!empty($media)) : ?>
                <div class="media-grid">
                    <?php foreach ($media as $item) : ?>
                        <?php if ($item['file_type'] === 'image') : ?>
                            <img src="/RMS/public/uploads/<?= e(basename($item['file_path'])) ?>"
                                 alt="Incident Image" class="incident-image">
                        <?php else : ?>
                            <p><a href="/RMS/public/uploads/<?= e(basename($item['file_path'])) ?>" target="_blank">View Attachment</a></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p>N/A</p>
            <?php endif; ?>
        </div>

        <!-- ===== REPORTER ===== -->
        <div class="section-title">Reporting Party Information</div>
        <table>
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Username</th>
                    <th>Authentication</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reporter)): ?>
                    <tr>
                        <td><?= e($reporter['org_id_number'] ?? 'N/A') ?></td>
                        <td><?= e($reporter['username'] ?? $reporter['phone'] ?? 'N/A') ?></td>
                        <td><?= e($reporter['auth_method'] ?? 'N/A') ?></td>
                        <td><?= e($reporter['role'] ?? 'Anonymous / Phone Reporter') ?></td>
                        <td><?= e($reporter['phone'] ?? 'N/A') ?></td>
                        <td><?= e($incident['readable_address'] ?? 'N/A') ?></td>
                    </tr>
                <?php else: ?>
                    <tr><td colspan="6">No reporter information available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- ===== RESPONSE ===== -->
        <div class="section-title">Response &amp; Action Taken</div>
        <table>
            <thead>
                <tr>
                    <th>Assigned Officer</th>
                    <th>Description of Scene</th>
                    <th>Resolution Date</th>
                    <th>Resolution Time</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= e($response['organization_name'] ?? 'N/A') ?></td>
                    <td><?= e($response['action_taken'] ?? 'N/A') ?></td>
                    <td><?= e($response['resolution_date'] ?? 'N/A') ?></td>
                    <td><?= e($response['resolution_time'] ?? 'N/A') ?></td>
                </tr>
            </tbody>
        </table>

        <!-- ===== INVOLVED PARTIES ===== -->
        <div class="section-title">Involved Parties</div>
        <table>
            <thead>
                <tr>
                    <th>Owner of Property</th>
                    <th>Affected Area</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($involvedParties)): ?>
                    <?php foreach ($involvedParties as $party): ?>
                        <tr>
                            <td><?= e($party['owner_of_property'] ?? $party['owner'] ?? 'N/A') ?></td>
                            <td><?= e($party['affected_area']) ?></td>
                            <td><?= e($party['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No involved parties recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- ===== INVESTIGATION ===== -->
        <div class="section-title">Investigation Findings</div>
        <p class="paragraph">
           <?= e($response['investigation_findings'] ?? 'No investigation remarks recorded.') ?>
        </p>
        <p class="paragraph">
            Based on the findings and actions undertaken, the incident has been officially resolved and closed as of <strong><?= e($response['resolution_date'] ?? $today) ?></strong>.
        </p>

        <!-- ===== RESOLUTION ===== -->
        <div class="section-title">Resolution and Disposition</div>
        <p class="paragraph">
           <?= e($response['resolution_disposition'] ?? 'No disposition recorded.') ?>
        </p>

        <!-- ===== SIGNATURES ===== -->
         <?php
        // Helper to build full name, middle becomes initial if present
        function fullName($first, $middle, $last) {
            $mid = $middle ? strtoupper(substr(trim($middle), 0, 1)) . '.' : '';
            return trim("$first $mid $last") ?: 'N/A';
        }

        $reportOfficer = fullName(
            $response['report_officer_first']  ?? '',
            $response['report_officer_middle'] ?? '',
            $response['report_officer_last']   ?? ''
        );

        $signatory = fullName(
            $response['signatory_first']  ?? '',
            $response['signatory_middle'] ?? '',
            $response['signatory_last']   ?? ''
        );
        ?>

        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line-bar"></div>
                <div class="sig-name"><?= e($reportOfficer) ?></div>
                <div class="sig-title"><?= e($response['report_officer_position'] ?? 'Prepared by') ?></div>
            </div>
            <div class="signature-block">
                <div class="signature-line-bar"></div>
                <div class="sig-name"><?= e($signatory) ?></div>
                <div class="sig-title"><?= e($response['signatory_position'] ?? 'Approved by') ?></div>
            </div>
        </div>

        



        <!-- <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line-bar"></div>
                <div class="sig-name">Report Officer</div>
                <div class="sig-title">Prepared by</div>
            </div>
            <div class="signature-block">
                <div class="signature-line-bar"></div>
                <div class="sig-name">Authorized Signatory</div>
                <div class="sig-title">Approved by</div>
            </div>
        </div> -->

        <!-- ===== FOOTER ===== -->
        <footer class="report-footer">
            <div>Tracking Code: <?= e($incident['tracking_code']) ?></div>
            <div>Generated on <?= $today ?></div>
            <div>Page <span class="page-number"></span></div>
        </footer>

    </div>
</main>

<!-- PDF Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
async function downloadReport() {
    const btn = document.querySelector('.btns-secondary');
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Generating PDF...';
    btn.disabled = true;

    try {
        const { jsPDF } = window.jspdf;
        const element = document.querySelector('.report-document');

        const canvas = await html2canvas(element, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            scrollX: 0,
            scrollY: 0,
            windowWidth: element.scrollWidth,
            windowHeight: element.scrollHeight
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
        btn.innerHTML = originalText;
        btn.disabled  = false;
    }
}
</script>

</div>