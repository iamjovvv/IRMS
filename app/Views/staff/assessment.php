<?php
$incidentId   = $incident['id'];
$trackingCode = $incident['tracking_code'];
?>

<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page-assessment">

        <div class="form__wrapper">

            <form 
                class="form form-assessment" 
                method="POST"
                action="/RMS/public/index.php?url=staff/submitAssessment"
                id="assessmentForm">

                <h2 class="form__title">Assessment</h2>
                <p class="form__subtitle">
                    Use this section to validate the report, assign urgency, and document your initial assessment.
                </p>

                <!-- 🔹 Report Validity -->
                <fieldset class="form__section">
                    <legend><strong>Report Validity</strong></legend>

                    <input type="hidden" name="tracking_code" value="<?= htmlspecialchars($trackingCode) ?>">

                    <label>
                        <input type="radio" name="validity" value="valid" id="validOption">
                        Valid - Sufficient information to proceed.
                    </label>

                    <label>
                        <input type="radio" name="validity" value="invalid" id="invalidOption">
                        Invalid - Missing or unclear information.
                    </label>

                </fieldset>

                <!-- 🔹 Priority -->
                <fieldset class="form__section" id="prioritySection">
                    <legend><strong>Priority Level</strong></legend>

                    <label><input type="radio" name="priority" value="low"> Low - No immediate risk</label>
                    <label><input type="radio" name="priority" value="medium"> Medium - Requires attention</label>
                    <label><input type="radio" name="priority" value="high"> High - Time-sensitive</label>
                    <label><input type="radio" name="priority" value="critical"> Critical - Immediate action required.</label>

                </fieldset>

                <!-- 🔹 Reason for invalidation -->
                <div class="form__section" id="invalidReasonSection">
                    <label for="invalid_reason">Reason for invalidation</label>
                    <textarea 
                        name="invalid_reason" 
                        id="invalid_reason"
                        class="form__textarea-assessment"
                        placeholder="Explain why the report is invalid or what information is missing"></textarea>
                </div>

                <button type="submit" class="btn btn--primary">
                    Save & Continue
                </button>

            </form>

        </div>

        <!-- 🔹 STEPPER -->
        <?php if (!empty($steps)): ?>
            <?php require BASE_PATH . '/app/Views/layouts/steps-bar.php'; ?>
        <?php endif; ?>

    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const validOption = document.getElementById('validOption');
    const invalidOption = document.getElementById('invalidOption');
    const prioritySection = document.getElementById('prioritySection');
    const invalidReasonSection = document.getElementById('invalidReasonSection');
    const form = document.getElementById('assessmentForm');

    // 🔹 Toggle sections
    function toggleSections() {
        if (validOption.checked) {
            prioritySection.style.display = 'block';
            invalidReasonSection.style.display = 'none';
        } else if (invalidOption.checked) {
            prioritySection.style.display = 'none';
            invalidReasonSection.style.display = 'block';
        } else {
            prioritySection.style.display = 'none';
            invalidReasonSection.style.display = 'none';
        }
    }

    validOption.addEventListener('change', toggleSections);
    invalidOption.addEventListener('change', toggleSections);
    toggleSections(); // initial toggle on load

    // 🔹 Prevent accidental JS interference & force POST
    form.addEventListener('submit', (e) => {
        // Optional: client-side validation
        if (validOption.checked && !form.priority.value) {
            e.preventDefault();
            alert('Please select a priority level.');
        }
    });

});
</script>
