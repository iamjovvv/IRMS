<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php' ?>

    <main class="page page-action">

        <div class="form__wrapper">

            <form class="form form-action"
                method="POST"
                action="/RMS/public/index.php?url=staff/submitExternalAction"
                enctype= "multipart/multi-data">

                <h2 class="form__title">Action Taken</h2>
                
                <p class="form__subtitle">This page will document the process undergo in order to solve the situation.</p>
                
                <h3 class="form__title--start">Resolution Panel</h3>

                <div class="form__field">

                    <label class="form__label">Action Taken</label>

                    <textarea name="action_taken" class="form__textarea" placeholder="Describe actions taken by the responder... " required></textarea>

                </div> 


                <div class="form__field">

                    <label class="form__label">Resolution Date</label>

                    <input name="resolution_date" type="date" class="form__input" required>

                </div>


                <div class="form__field">

                    <label class="form__label">Resolution Time</label>

                    <input name="resolution_time" type="time" class="form__input" required>

                </div>


                <div class="form__field">

                    <label class="form__label">Status Update</label>

                    <select name="status" class="form__select">

                        <option value="">Select Status</option>

                        <option value="ongoing">Ongoing</option>
                        
                        <option value="pending">Pending</option>

                        <option value="resolved">Resolved</option>

                    </select>

                </div>


               <div class="form__field">

                    <label class="form__label">Upload Image/ Video </label>

                    <div class="form__control form__control--upload">

                        <label class="upload" for="incident_image">

                        <i class="fa-solid fa-cloud-arrow-up upload__icon"></i>

                        <span class="form__text">Click or drag files here</span>

                        <small class="form__hint">(max 500mb)</small>
                        
                    </label>

                        <input class="upload__input" type="file" id="incident_image" name="incident_image" accept="image/*,video/*">

                    </div>


                </div>



                <button type="button" class="btn btn-view-preview">[View Full Preview]
                </button>

                <button type="submit" class="btn btn--primary">Send</button>
                

                
            </form>

        </div>

    </main>

</div>