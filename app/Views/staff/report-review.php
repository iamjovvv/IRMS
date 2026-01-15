
<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php' ?>

        <main class="page page-incident-review">
        
            <div class="status-current">
                <p>
                    <strong>Current Status:</strong>

                    <span class="status-badge status--review">
                        Under Review
                    </span>

                </p>    
            </div>
                

                    <?php require BASE_PATH . '/app/Views/reporter/incident-form.php'; ?>

                   

      
                    <!-- STEPPER -->

                    <?php
                        $steps = [
                            'Incident Details',
                            'Reporter Details',
                            'Assessment',
                            'Status'
                        ];

                        $currentStep= 1;

                        require BASE_PATH . '/app/Views/layouts/steps-bar.php';

                    ?>
        
        </main>

       

</div>



