<?php
// actionForm.php

// Ensure $incident is passed from the controller via reviewIncident or actionForm method
$mode = 'view';          // Report is readonly
$isReadOnly = true;      
$readonly = 'readonly';
$disabled = 'disabled';
$priority = $incident['priority'] ?? 'low';
?>



<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page-action">

       

        <section class="form__wrapper action-form-wrapper">
            <h2>Take Action</h2>

            <form class="form form-action"
                method="POST"
                action="/RMS/public/index.php?url=staff/submitAction"
                enctype="multipart/form-data">

                <input type="hidden" name="priority" value="<?= htmlspecialchars($priority) ?>">

                <!-- Hidden IDs -->
                <input type="hidden" name="incident_id" value="<?= $incident['id'] ?>">
                <input type="hidden" name="tracking_code" value="<?= htmlspecialchars($incident['tracking_code']) ?>">x

                <!-- Action Taken -->
                <div class="form__field">
                    <label class="form__label">Action Taken</label>
                    <textarea name="action_taken" class="form__textarea" required></textarea>
                </div>

                <!-- Resolution Date -->
                <div class="form__field">
                    <label class="form__label">Resolution Date</label>
                    <input type="date" name="resolution_date" class="form__input" required>
                </div>

                <!-- Resolution Time -->
                <div class="form__field">
                    <label class="form__label">Resolution Time</label>
                    <input type="time" name="resolution_time" class="form__input" required>
                </div>

                <!-- Status Update -->
                <div class="form__field">
                    <label class="form__label">Status Update</label>
                    <select name="status_update" class="form__select" required>
                        <option value="">Select Status</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>



                <!-- High/Critical priority fields -->
                <?php if (in_array($priority, ['high','critical'])): ?>
                    <div class="form__field">
                        <label class="form__label">External Responder</label>
                        <select name="external_responder_id" class="form__select" required>
                            <option value="">Select Responder</option>
                            <option value="1">Police</option>
                            <option value="2">Bureau of Fire</option>
                        </select>
                    </div>

                    <div class="form__field">
                        <label class="form__label">Notes</label>
                        <textarea name="notes" class="form__textarea"></textarea>
                    </div>
                <?php endif; ?>




                <!-- Optional image/video -->
                <div class="form__field">

                <label class="form__label">Upload Image / Video</label>

                <div class="uploader">

                    <label class="uploader__dropzone" for="incident_image">
                        
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Upload files here</span>
                        <small>(Images & Videos, max 500MB)</small>

                    </label>

                    <input

                        type="file"
                        id="incident_image"
                        name="incident_image[]"
                        accept="image/*,video/*"
                        multiple
                        hidden
                    >

                    <div id="preview-container" class="uploader__preview"></div>
                </div>
            </div>

            

            

              
                    <a href="/RMS/public/index.php?url=staff/reviewIncident&code=<?= htmlspecialchars($incident['tracking_code']) ?>"
   class="btn btn--secondary">
   Back
</a>
               

                <button type=   "submit" class="btn btn--primary" id ="submitActionBtn">Send</button>

            </form>
        </section>

        
    <!-- Lightbox for enlarging images/videos -->
    
        <div id="lightbox" class="lightbox">
            <span class="lightbox__close">&times;</span>
            <img id="lightbox-img" class="lightbox__media" src="" alt="Preview">
            <video id="lightbox-video" class="lightbox__media" controls style="display:none;"></video>
        </div>
   

    </main>
</div>


<script>
document.getElementById('submitActionBtn').addEventListener('click', function(e) {
    // Show confirmation dialog
    const confirmSave = confirm("Are you sure you want to save this action?");
    
    if (!confirmSave) {
        // Prevent form submission if user clicks "Cancel"
        e.preventDefault();
    }
});
</script>
