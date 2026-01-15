
<div class="with-sidebar">
    <?php $_SESSION['role']= 'external responder'; ?>

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

        <main class="page page--gray page--summary">

            <header class="page__header">

                <h1 class="page__title">Responder Dashboard</h1>

            </header>

                <!-- <h2 class="dashboard__greeting">Hello [Name]</h2> -->

            <section class="grid grid--summary">


                <div class="card card--action">

                    <i class="card__icon fa-regular fa-file"></i>

                    <p class="card__description">Assigned Incidents</p>

                </div>


                <div class="card card--action">

                    <i class="card__icon fa-solid fa-layer-group"></i>

                    <p class="card__description">In Progress</p>

                </div>

                

                <div class="card card--action">

                    <i class="card__icon fa-solid fa-user"></i>

                    <p class="card__description">Resolved</p>

                </div>

               

                
            </section>
            
        </main>
</div>