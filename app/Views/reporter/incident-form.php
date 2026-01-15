<main class="page page--incident-form">

    <h3 class="page__title">Create Report</h3>

    <section class = "form-wrapper"> 

        <form 
            method="POST" 
            action="/RMS/public/index.php?url=staff/reporterDetails"
            enctype="multipart/form-data" 
            class="form form--incident">

            <h2 class = "form__title">Incident Form</h2>

            <!-- Subject -->

            <div class="form__field">

                <label class="form__label" for="subject">Subject</label>
                
                <input 
                    id="subject"
                    name="subject" 
                    class="form__input" type="text" >

            </div>

            <!-- Date & Time -->
        <div class="form__row form__row--two">

            <div class="form__field">

                <label          class="form__label">Date of Incident</label>

                <input
                    class="form__input" type="date" name="date_of_incident" >

            </div>

                
                <div class="form__field">

                    <label class="form__label">Time of Incident</label>

                    <input
                        class="form__input" 
                        type="time" name="time_of_incident" >

                </div>

        </div>

            <!-- Location & Category -->
        <div class="form__row form__row--two">

            <div class="form__field">
                
                    <label class="form__label">Location</label>

                    <input
                        class="form__input" 
                        type="text" name="location" >

            </div>

            <div class="form__field">

                    <label class="form__label">Category of Incident</label>

                    <select class="form__select" name="category_of_incident" >

                        <option value="">Category of Incidents</option>

                        <option value="theft">Theft</option>

                        <option value="accident">Accident</option>

                        <option value="harassment">Harassment</option>

                        <option value="other">Other</option>

                    </select>

            </div>

        </div>


            <!-- Description -->
            <div class="form__field">

                <label class="form__label">Description</label>

                <textarea
                    name="description"
                    class="form__textarea"  placeholder="Enter a short description here...">
                </textarea>

            </div>


            <!-- Upload & Type -->

        <div class="form__row form__row--two">

                <!-- Upload -->
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
        
                <!-- Type -->

            <div class="form__field">
                    
                <label class="form__label">Type of Incident</label>

                <div class="form__control form__control--radio">

                    <div class="form__radio-group">

                        <label class="form__radio">

                        <input type="radio" name="incident_type" value="fatal">Fatal
                        </label>

                        <label class="form__radio">

                        <input type="radio" name="incident_type" value="non-fatal">Non-fatal
                        </label>

                    </div>

                </div>

            </div>
            
        </div>

            
        <button type="submit" class="btn btn--primary btn--block">Save & Continue</button>

        </form>
    </section>
</main>