<?php
// Ensure $latestAction is always defined
$latestAction = $latestAction ?? [];

// Safely fetch fields with defaults
$actionDate      = $latestAction['action_date'] ?? 'N/A';
$trackingCode    = $incident['tracking_code'] ?? 'N/A';
$incidentStatus  = $incident['status'] ?? 'Unknown';
$incidentDate    = $incident['created_at'] ?? 'N/A';
$priority        = $assessment['priority'] ?? 'N/A';
$validity        = $assessment['validity'] ?? 'N/A';
$remarks         = $assessment['remarks'] ?? 'N/A';
?>

<main class="page page-report-status">

    <section class="form-wrapper">

        <form class="form form-report-status">

            <h2 class="form__title">Report Status</h2>

            <div class="form__row form__row--two">

                <div class="form__field">
                    <label class="form__label">Current Status</label>
                    <input 
                        type="text"
                        class="form__input"
                        value="<?= htmlspecialchars(ucfirst($incidentStatus)) ?>"
                        readonly>
                </div>

                <div class="form__field">
                        <label class="form__label">Last Updated Date & Time</label>
                        <input
                        type="text"
                        class="form__input"
                        value="<?= !empty($latestAction['created_at'])
                        ? date('M d, Y h:i A', strtotime($latestAction['created_at']))
                        : date('M d, Y h:i A', strtotime($incidentDate))
                        ?>"
                        readonly>
                </div>

            </div>

            <div class="form__field">
                <label class="form__label">Assigned Office/ Personnel</label>
                <input
                    type="text"
                    class="form__input"
                    value="<?= !empty($latestAction['role']) && !empty($latestAction['username'])
                        ? ucfirst($latestAction['role']) . ' - ' . $latestAction['username']
                        : 'Unassigned'
                    ?>"
                    readonly>
            </div>

        
           



<?php
if ($incidentStatus === 'invalidated' && !empty($assessment['remarks'])) {
    echo $assessment['remarks'];
} elseif (!empty($latestAction['action_taken'])) {
    echo $latestAction['action_taken'];
} else {
    echo 'No remarks available.';
}
?>
                </textarea>

<?php if (in_array($incidentStatus, ['invalidated', 'pending'])): ?>
    <button type="submit" class="remarks-send__btn">Submit</button>
<?php endif; ?>

            </div>

        </form>

    </section>

</main>