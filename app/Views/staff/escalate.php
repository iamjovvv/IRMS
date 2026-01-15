<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php' ?>

    <main class="page page-escalate">

        <div class="form__wrapper">

            <form class="form form-escalate">

                <h2 class="form__title">Incident Escalation</h2>
                
                <p class="form__subtitle">This incident may require assistance from external responder</p>
                
                <h3 class="form__title--start">External Responder</h3>

                <div class="form__field">

                    <select id="external_responder" name="external_responder" class="form__select">

                        <option value="">Select Responder</option>

                        <option value="police">Police</option>
                        
                        <option value="bureau of fire">Bureau of Fire</option>

                    </select>

                </div>


            
                <h3 class="form__title--start">Information to Share</h3>

                <div class="form__field">

                    <label class="form__label">Incident Type</label>

                    <input name="incident_type" type="text" class="form__input">

                </div>

                <div class="form__field">

                    <label class="form__label">Location</label>

                    <input name="location" type="text" class="form__input">

                </div>

                <div class="form__field">

                    <label class="form__label">Urgency</label>

                    <input name="urgency" type="text" class="form__input">

                </div>

                <div class="form__field">
                    <label class="form__label">Description</label>

                    <textarea name="description" class="form__textarea"></textarea>

                </div>

                <button type="button" class="btn btn-view-preview">[View Full Preview]
                </button>

                <button type="submit" class="btn btn--primary">Forward</button>
                
            </form>

        </div>

    </main>

</div>