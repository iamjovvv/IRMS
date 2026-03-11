<?php

$sessionUser =$_SESSION['user'] ?? [];
$mode     = $mode ?? 'responder';
$priority = $incident['priority'] ?? 'low';
$status   = strtolower($incident['status'] ?? '');

// Permissions
if ($mode === 'responder') {
    $canTakeAction        = in_array($status, ['validated', 'ongoing', 'escalated']);
    $showActionFormFilled = $status === 'resolved';
} elseif ($mode === 'staff') {
    $canTakeAction        = in_array( $status, ['validated', 'ongoing']);    
    // $showActionFormFilled = in_array($status, ['resolved', 'escalated']);
    $showActionFormFilled = $status === 'resolved';
}

$previousActions = $showActionFormFilled
    ? (new IncidentModel($this->pdo))->getIncidentActions($incident['id'])
    : [];

$media = $media ?? [];
?>

<div class="with-sidebar">
<?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

<main class="page page-action">
    <section class="form__wrapper action-form-wrapper">
        <h2><?= $canTakeAction ? 'Take Action' : 'View Submitted Actions' ?></h2>

        

        <?php if ($canTakeAction): ?>
        <!-- ==================== EDITABLE FORM ==================== -->
            <form class="form form-action"
                  method="POST"
                  action="<?= $mode === 'staff'
                             ? '/RMS/public/index.php?url=staff/submitAction'
                             : '/RMS/public/index.php?url=responder/submitAction' ?>"
                  enctype="multipart/form-data">

                <input type="hidden" name="priority"       value="<?= htmlspecialchars($priority) ?>">
                <input type="hidden" name="incident_id"    value="<?= $incident['id'] ?>">
                <input type="hidden" name="tracking_code"  value="<?= htmlspecialchars($incident['tracking_code']) ?>">

                <div class="form__field">
                    <label class="form__label">Action Taken
                        <small class="form__hint">Describe the immediate action done upon responding to the incident.</small>
                    </label>
                    <textarea name="action_taken" class="form__textarea" required></textarea>
                </div>

                <div class="form__field">
                    <label class="form__label">Resolution Date</label>
                    <input type="date" name="resolution_date" class="form__input" required>
                </div>

                <div class="form__field">
                    <label class="form__label">Resolution Time</label>
                    <input type="time" name="resolution_time" class="form__input" required>
                </div>

                <div class="form__field">
                    <label class="form__label">Status Update</label>
                    <select name="status_update" class="form__select" required>
                        <option value="">Select Status</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>

               

                <!-- <div class="form__field">
                    <label class="form__label">Upload Image / Video</label>
                    <div class="uploader">
                        <label class="uploader__dropzone" for="incident_image">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Upload files here</span>
                            <small>(Images & Videos, max 500MB)</small>
                        </label>
                        <input type="file" id="incident_image" name="incident_image[]"
                               accept="image/*,video/*" multiple hidden>
                        <div id="preview-container" class="uploader__preview"></div>
                    </div>
                </div> -->

                <!-- INVOLVED PARTIES -->
                <div class="form__field">
                    <label class="form__label">Involved Parties</label>
                    <div id="involved-parties-container">
                        <div class="involved-party-row" style="display:flex; gap:8px; margin-bottom:8px;">
                            <input type="text" name="owner_of_property[]" class="form__input" placeholder="Owner of Property">
                            <input type="text" name="affected_area[]"     class="form__input" placeholder="Affected Area">
                            <input type="text" name="party_description[]" class="form__input" placeholder="Description">
                            <button type="button" class="btn btn--secondary remove-party" style="white-space:nowrap">Remove</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn--secondary" id="addPartyBtn">+ Add Party</button>
                </div>

                <!-- INVESTIGATION FINDINGS -->
                <div class="form__field">
                    <label class="form__label">Investigation Findings
                        <small class="form__hint">Describe the findings regarding the incident while responding to it.</small>
                    </label>
                    <textarea name="investigation_findings" class="form__textarea" placeholder="Remarks recorded..."></textarea>
                </div>

                <!-- RESOLUTION AND DISPOSITION -->
                <div class="form__field">
                    <label class="form__label">Resolution and Disposition
                        <small class="form__hint">Describe the final outcome — what was decided, resolved, or closed.</small>
                    </label>
                    <textarea name="resolution_disposition" class="form__textarea" placeholder="Remarks..."></textarea>
                </div>

                <!-- REPORT OFFICER -->
                <div class="form__field">
                    <label class="form__label">Report Officer
                        <small class="form__hint">The officer who prepared and filed this action report.</small>
                    </label>
                    <div style="display:flex; gap:8px;">
                        <input type="text" name="report_officer_first"  class="form__input" placeholder="First Name"
                            value="<?= htmlspecialchars($sessionUser['first_name'] ?? '') ?>">
                        <input type="text" name="report_officer_middle" class="form__input" placeholder="Middle Name"
                            value="<?= htmlspecialchars($sessionUser['middle_name'] ?? '') ?>">
                        <input type="text" name="report_officer_last"   class="form__input" placeholder="Last Name"
                            value="<?= htmlspecialchars($sessionUser['last_name'] ?? '') ?>">
                    </div>
                    <input type="text" name="report_officer_position" class="form__input" style="margin-top:6px;"
                        placeholder="Position / Designation"
                        value="<?= htmlspecialchars($sessionUser['position'] ?? '') ?>">
                </div>

                <!-- AUTHORIZED SIGNATORY -->
                <div class="form__field">
                    <label class="form__label">Authorized Signatory
                        <small class="form__hint">The authority who reviewed and approved this report.</small>
                    </label>
                    <div style="display:flex; gap:8px;">
                        <input type="text" name="signatory_first"  class="form__input" placeholder="First Name">
                        <input type="text" name="signatory_middle" class="form__input" placeholder="Middle Name">
                        <input type="text" name="signatory_last"   class="form__input" placeholder="Last Name">
                    </div>
                    <input type="text" name="signatory_position" class="form__input" style="margin-top:6px;"
                           placeholder="Position / Designation (e.g. Chief Security Officer)">
                </div>

                <button type="submit" class="btn btn--primary" id="submitActionBtn">Send</button>
            </form>

        <?php elseif ($showActionFormFilled): ?>
        <!-- ==================== READONLY VIEW ==================== -->
            <?php foreach ($previousActions as $action): ?>
            <div class="form form-action readonly-action">

                <!-- SUBMITTED BY (audit record) -->
                <div class="form__field">
                    <label class="form__label">Submitted By</label>
                    <input type="text" class="form__input" readonly
                        value="<?= htmlspecialchars($action['actor_username'] ?? 'Unknown') ?> 
                                — <?= htmlspecialchars(ucfirst($action['actor_role'] ?? '')) ?>">
                </div>

                <div class="form__field">
                    <label class="form__label">Action Taken</label>
                    <textarea class="form__textarea" readonly><?= htmlspecialchars($action['action_taken']) ?></textarea>
                </div>

                <div class="form__field">
                    <label class="form__label">Resolution Date / Time</label>
                    <input type="text" class="form__input" readonly
                           value="<?= htmlspecialchars($action['resolution_date'] . ' ' . $action['resolution_time']) ?>">
                </div>

                <div class="form__field">
                    <label class="form__label">Status</label>
                    <input type="text" class="form__input" readonly
                           value="<?= htmlspecialchars($action['status_update']) ?>">
                </div>

                <?php if (!empty($action['investigation_findings'])): ?>
                <div class="form__field">
                    <label class="form__label">Investigation Findings</label>
                    <textarea class="form__textarea" readonly><?= htmlspecialchars($action['investigation_findings']) ?></textarea>
                </div>
                <?php endif; ?>

                <?php if (!empty($action['resolution_disposition'])): ?>
                <div class="form__field">
                    <label class="form__label">Resolution and Disposition</label>
                    <textarea class="form__textarea" readonly><?= htmlspecialchars($action['resolution_disposition']) ?></textarea>
                </div>
                <?php endif; ?>

                <?php if (!empty($action['involved_parties'])): ?>
                <div class="form__field">
                    <label class="form__label">Involved Parties</label>
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding:6px;">Owner of Property</th>
                                <th style="text-align:left; padding:6px;">Affected Area</th>
                                <th style="text-align:left; padding:6px;">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($action['involved_parties'] as $party): ?>
                            <tr>
                                <td style="padding:6px;"><?= htmlspecialchars($party['owner']) ?></td>
                                <td style="padding:6px;"><?= htmlspecialchars($party['area']) ?></td>
                                <td style="padding:6px;"><?= htmlspecialchars($party['description']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <!-- REPORT OFFICER -->
                <div class="form__field">
                    <label class="form__label">Report Officer</label>
                    <div style="display:flex; gap:8px;">
                        <input type="text" name="report_officer_first"  class="form__input" placeholder="First Name">
                        <input type="text" name="report_officer_middle" class="form__input" placeholder="Middle Name">
                        <input type="text" name="report_officer_last"   class="form__input" placeholder="Last Name">
                    </div>
                    <input type="text" class="form__input" readonly style="margin-top:6px;"
                           value="<?= htmlspecialchars($action['report_officer_position'] ?? '') ?>">
                </div>

                <!-- AUTHORIZED SIGNATORY -->
                <div class="form__field">
                    <label class="form__label">Authorized Signatory</label>
                    <div style="display:flex; gap:8px;">
                        <input type="text" class="form__input" readonly value="<?= htmlspecialchars($action['signatory_first']  ?? '') ?>">
                        <input type="text" class="form__input" readonly value="<?= htmlspecialchars($action['signatory_middle'] ?? '') ?>">
                        <input type="text" class="form__input" readonly value="<?= htmlspecialchars($action['signatory_last']   ?? '') ?>">
                    </div>
                    <input type="text" class="form__input" readonly style="margin-top:6px;"
                           value="<?= htmlspecialchars($action['signatory_position'] ?? '') ?>">
                </div>

                <?php if (!empty($media)): ?>
                <div class="form__field">
                    <label class="form__label">Attached Media</label>
                    <div class="media-gallery">
                        <?php foreach ($media as $file): ?>
                            <a href="/<?= htmlspecialchars($file['file_path']) ?>" target="_blank">
                                <?= htmlspecialchars(basename($file['file_path'])) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>
            <!-- ^^^^ ONE endforeach. Nothing after it. -->

        <?php else: ?>
            <p>No actions allowed for this incident.</p>
        <?php endif; ?>

        <?php
        $backUrl = $mode === 'staff'
            ? '/RMS/public/index.php?url=staff/reviewIncident&code=' . htmlspecialchars($incident['tracking_code'])
            : '/RMS/public/index.php?url=responder/assignedIncidents';
        ?>
        <a href="<?= $backUrl ?>" class="btn btn--secondary">Back</a>

    </section>
</main>
</div>

<script>
document.getElementById('submitActionBtn')?.addEventListener('click', function(e) {
    if (!confirm("Are you sure you want to save this action?")) e.preventDefault();
});
</script>

<script>
document.getElementById('addPartyBtn')?.addEventListener('click', function () {
    const container = document.getElementById('involved-parties-container');
    const row = document.createElement('div');
    row.className = 'involved-party-row';
    row.style = 'display:flex; gap:8px; margin-bottom:8px;';
    row.innerHTML = `
        <input type="text" name="owner_of_property[]" class="form__input" placeholder="Owner of Property">
        <input type="text" name="affected_area[]"     class="form__input" placeholder="Affected Area">
        <input type="text" name="party_description[]" class="form__input" placeholder="Description">
        <button type="button" class="btn btn--secondary remove-party">Remove</button>
    `;
    container.appendChild(row);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-party')) {
        const rows = document.querySelectorAll('.involved-party-row');
        if (rows.length > 1) e.target.closest('.involved-party-row').remove();
    }
});
</script>