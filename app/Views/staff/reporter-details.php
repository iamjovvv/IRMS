<div class="with-sidebar">
    
    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'?>


    <main class="page page-reporter-details">

    <section class="form-wrapper">

        <form 
            method="POST" 
            class="form form-reporter-details" 
            action="/RMS/public/index.php?url=staff/assessment"
            enctype="multipart/form-data">

            <h2 class="form__title">Reporter Details</h2>

                <label class="form__label form__label">Reporter Identity</label>

            <div class="form__row form__row--three">

                <div class="form__field">

                    <label class="form__label--italic" for="last_name">last name</label>

                    <input type="text" class="form__input" id="last_name" >

                </div>

                <div class="form__field">

                    <label class="form__label--italic">first name</label>

                    <input type="text" class="form__input">

                </div>

                <div class="form__field">

                    <label class="form__label--italic">M.I.</label>

                    <input type="text" class="form__input">

                </div>

            </div>


            <div class="form__row form__row--two">

                <div class="form__field">
                    <label class="form__label--italic">reporter I.D.</label>

                    <input type="text" class= "form__input">

                </div>

                <div class="form__field">
                    <label class="form__label--italic">role</label>

                    <input type="text" class= "form__input" >
                </div>

            </div>


            <label class="form__label form__label">Submission Info</label>

            <div class="form__row">

                <div class="form__field">

                    <label class="form__label--italic">Submitted Using</label>

                    <input type= "text" class="form__input">

                </div>

            </div>


            <div class="form__row form__row--two">

                <div class="form__field">

                    <label class="form__label--italic">Date Submitted</label>

                    <input type="text" class="form__input">

                </div>

                <div class="form__field">

                    <label class="form__label--italic">Time Submitted</label>

                    <input type="text" class= "form__input">

                </div>


            </div>


             <label class="form__label form__label">Report Metadata</label>

            <div class="form__row form__row--two">

                <div class="form__field">

                    <label class="form__label--italic">Report Urgency</label>

                    <input type="text" class="form__input">

                </div>

                <div class="form__field">

                    <label class="form__label--italic">Tracking Code</label>

                    <input type="text" class="form__input">

                </div>

            </div>

            <button type="submit" class="btn btn--primary">Save & Continue</button>



        </form>

    </section>

                            <!-- STEPPER -->

                            <?php
                                $steps = [
                                    'Incident Details',
                                    'Reporter Details',
                                    'Assessment',
                                    'Status'
                                ];

                                $currentStep= 2;

                                require BASE_PATH . '/app/Views/layouts/steps-bar.php';

                            ?>


</main>
</div>