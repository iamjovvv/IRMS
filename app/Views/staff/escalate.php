<?php

$incidentId = $incident['id'] ?? 0;

if (!$incidentId) {
    echo "Invalid incident ID";
    exit;
}

?>



<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php' ?>

    <main class="page page-escalate">

        <div class="form__wrapper">

            <form class="form form-escalate"
                    method="POST"
                    action="/RMS/public/index.php?url=staff/submitEscalation"
            >

            
           

             <input type="hidden" name="tracking_code" value="<?= htmlspecialchars($incident['tracking_code']) ?>">

             <input type="hidden" name="incident_id" value="<?= $incidentId ?>">


                <h2 class="form__title">Incident Escalation</h2>
                
                <p class="form__subtitle">This incident may require assistance from external responder</p>
                
                <h3 class="form__title--start">External Responder</h3>

                <div class="form__field">

                    <select id="external_responder_id" name="external_responder_id" class="form__select" required>
    <option value="">Select Responder</option>
    <?php foreach ($responders as $responder): ?>
        <option value="<?= $responder['id'] ?>"><?= htmlspecialchars($responder['username']) ?></option>
    <?php endforeach; ?>
</select>

                </div>


            
               

                <div class="form__field">
                    <label class="form__label">Description</label>

                    <textarea name="description" class="form__textarea" placeholder="you can add here additional information if you want..." required>
                        
                    </textarea>

                </div>

                <button type="button" class="btn btn-view-preview"
                onclick="window.location.href='/RMS/public/index.php?url=reporter/reportFormGet&code=<?= urlencode($incident['tracking_code']) ?>&preview=1'">
                    [View Full Preview]
                </button>


                <button type="submit" class="btn btn--primary"
                onclick="return confirm('Are you sure you want to forward this incident?');">
                Forward
                </button>
                
            </form>

        </div>

    </main>

</div>