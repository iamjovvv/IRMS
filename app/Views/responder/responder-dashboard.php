


    
    <?php $_SESSION['role']= 'external responder'; ?>

    

        <main class="page page--gray page--summary">

            <?php
            $success = $_SESSION['success'] ?? null;
            unset($_SESSION['success']);
            ?>

            <?php if ($success): ?>
            <script>
                window.toastSuccess = <?= json_encode($success) ?>;
            </script>
            <?php endif; ?>

            <div id="toast-container"></div>

            

            <header class="page__header">

                <h1 class="page__title">Responder Dashboard</h1>

            </header>

                <!-- <h2 class="dashboard__greeting">Hello [Name]</h2> -->

            <section class="grid grid--summary">

                <a class="card card--action" href="/RMS/public/index.php?url=responder/reportsEscalated">
                    <i class="card__icon fa-regular fa-file"></i>
                    <p class="card__description">New Incidents</p>
                </a>

                <!-- <a class="card card--action" href="/RMS/public/index.php?url=responder/assignedIncidents">
                    <i class="card__icon fa-regular fa-file"></i>
                    <p class="card__description">All Incidents</p>
                </a> -->


                <!-- <a class="card card--action" href="/RMS/public/index.php?url=responder/assignedIncidents&status=ongoing">
                    <i class="card__icon fa-solid fa-layer-group"></i>
                
                        <p class="card__description">On Progress</p>
                
                </a> -->

                 <a class="card card--action" href="/RMS/public/index.php?url=responder/reportsOngoing">
                    <i class="card__icon fa-solid fa-layer-group"></i>
                
                        <p class="card__description">On Progress</p>
                
                </a>

                <a class="card card--action" href="/RMS/public/index.php?url=responder/reportsResolved">
                    <i class="card__icon fa-solid fa-user"></i>
                
                        <p class="card__description">Resolved</p>
                
                </a>
               

                
            </section>
            
        </main>
