
<?php
$steps = [
    'Incident Details',
    'Reporter Details',
    'Assessment'
];

$currentStep = 2;

// Safety fallback
$reporter = $reporter ?? [];
?>

<div class="with-sidebar">
    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php' ?>

    <main class="page page-reporter-details">
        <section class="form-wrapper">
            <form method="POST" class="form form-reporter-details"
                  action="/RMS/public/index.php?url=staff/assessment"
                  enctype="multipart/form-data">

                <h2 class="form__title">Report Details</h2>


                
                <!-- REPORTER IDENTITY -->
                <label class="form__label">Reporter Identity</label>

                <div class="form__row form__row--three">

                    <div class="form__field">
                        <label class="form__label--italic">Username</label>
                        <input type="text" class="form__input"
                               value="<?= htmlspecialchars($reporter['username'] ?? 'Unknown') ?>"
                               readonly>
                    </div>

                    <div class="form__field">
                        <label class="form__label--italic">Authentication Method</label>
                        <input type="text" class="form__input"
                               value="<?= htmlspecialchars($reporter['auth_method'] ?? 'Unknown') ?>"
                               readonly>
                    </div>

                    <?php if (!empty($reporter) && $reporter['auth_method'] === 'org_id'): ?>
                        <div class="form__field">
                            <label class="form__label--italic">Organization ID</label>
                            <input type="text" class="form__input"
                                   value="<?= htmlspecialchars($reporter['org_id_number'] ?? '') ?>"
                                   readonly>
                        </div>
                    <?php elseif (!empty($reporter) && $reporter['auth_method'] === 'phone'): ?>
                        <div class="form__field">
                            <label class="form__label--italic">Phone Number</label>
                            <input type="text" class="form__input"
                                   value="<?= htmlspecialchars($reporter['phone'] ?? '') ?>"
                                   readonly>
                        </div>
                    <?php endif; ?>
                </div>


                <!-- LOCATION -->
                <label class="form__label">Reporter Location</label>

                    <div class="form__field">
                        <label class="form__label--italic">Address</label>
                        <input type="text" class="form__input"
                               value="<?= htmlspecialchars($incident['readable_address']) ?>"
                               readonly>
                    </div>
                

                <!-- SUBMISSION INFO -->
                <label class="form__label">Submission Info</label>
                <div class="form__row form__row--two">
                    <div class="form__field">
                        <label class="form__label--italic">Date Submitted</label>
                        <input type="text" class="form__input"
                               value="<?= htmlspecialchars($incident['created_at']) ?>"
                               readonly>
                    </div>

                    <div class="form__field">
                        <label class="form__label--italic">Tracking Code</label>
                        <input type="text" class="form__input"
                               value="<?= htmlspecialchars($incident['tracking_code']) ?>"
                               readonly>
                    </div>
                </div>

                <!-- REPORT METADATA -->
                <label class="form__label">Report Metadata</label>
                <div class="form__row form__row--two">
                    <div class="form__field">
                        <label class="form__label--italic">Incident Severity</label>
                        <input type="text" class="form__input"
                               value="<?= htmlspecialchars($incident['incident_type']) ?>"
                               readonly>
                    </div>

                    <div class="form__field">
                        <label class="form__label--italic">Current Status</label>
                        <input type="text" class="form__input"
                               value="<?= htmlspecialchars($incident['status']) ?>"
                               readonly>
                    </div>
                </div>

                <!-- <button type="submit" class="btn btn--primary">Save & Continue</button> -->

            </form>
        </section>

        <!-- STEPPER -->
        <?php if (!empty($steps)): ?>
            <?php require BASE_PATH . '/app/Views/layouts/steps-bar.php'; ?>
        <?php endif; ?>
    </main>
</div>
