<?php
$incidentId = $incident['id'] ?? 0;
if (!$incidentId) {
    echo "Invalid incident ID";
    exit;
}
?>

<main class="page page-escalate">
    <div class="form__wrapper">

        <form class="form form-escalate"
              method="POST"
              action="/RMS/public/index.php?url=reporter/submitEscalation">

            <input type="hidden" name="tracking_code" value="<?= htmlspecialchars($incident['tracking_code']) ?>">
            <input type="hidden" name="incident_id" value="<?= $incidentId ?>">

            <h2 class="form__title">🚨 Fatal Incident Reported</h2>
            <p class="form__subtitle">Please select an emergency responder for this incident.</p>

            <div class="form__field">
                <label class="form__label">Select External Responder</label>
                <select name="external_responder_id" class="form__select" required>
                    <option value="">-- Select Responder --</option>
                    <?php foreach ($responders as $responder): ?>
                        <option value="<?= (int)$responder['id'] ?>">
                            <?= htmlspecialchars($responder['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form__field">
                <label class="form__label">Additional Description (optional)</label>
                <textarea name="description" class="form__textarea"
                          placeholder="Add any additional information..."></textarea>
            </div>

            <button type="submit" class="btn btn--primary"
                    onclick="return confirm('Forward this incident to the selected responder?');">
                Forward to Responder
            </button>

        </form>
    </div>
</main>