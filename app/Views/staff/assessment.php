<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php' ?>

    <main class="page page-assessment">

    <div class="form__wrapper">

        <form 
            class="form form-assessment" 
            method="POST"
            action="/RMS/public/index.php?url=assessment/submit">

            <h2 class="form__title">Assessment</h2>
            <p class="form__subtitle">Use this section to validate the report, assign urgency, and document your initial assessment.</p>

            <fieldset class="form__section">
                <legend><strong>Report Validity</strong></legend>

                <label>
                    <input type="radio" name="validity" value="valid" id="validOption">
                    Valid - Sufficient information to proceed.
                </label>

                <label>
                    <input type="radio" name="validity" value="invali"
                    id="invalidOption">
                    Invalid - Missing or unclear information.
                </label>

            </fieldset>


            <fieldset class="form__section" id="prioritySection">
                <legend><strong>Priority Level</strong></legend>

                <label>
                    <input type="radio" name="priority" value="low">
                    Low - No immediate risk
                </label>

                <label>
                    <input type="radio" name="priority" value="medium">
                    Medium - Requires attention
                </label>

                <label>
                    <input type="radio" name="priority" value="high">
                    High -  Time-sensitive
                </label>

                <label>
                    <input type="radio" name="priority" value="critical">
                    Critical - Immediate action required.
                </label>

            </fieldset>


            <div class="form__section" id="invalidReasonSection">

                <label for="invalid_reason">
                    Reason for invalidation
                </label>

                <textarea 
                    name="invalid_reason" 
                    id="invalid_reason"
                    class="form__textarea-assessment"
                    placeholder="Explain why the report is invalid or what information is missing">

                </textarea>

                

            </div>

            <button type="submit" class="btn btn--primary">
                    Save & Continue
                </button>

        </form>

    </div>

    <!-- STEPPER -->

                <?php
                    $steps = [
                        'Incident Details',
                        'Reporter Details',
                        'Assessment',
                        'Status'
                    ];

                    $currentStep= 3;

                    require BASE_PATH . '/app/Views/layouts/steps-bar.php';

                ?>


                        

</main>

</div>