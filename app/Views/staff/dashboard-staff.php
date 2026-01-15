<?php $_SESSION['role']= 'staff'; ?>

<div class="with-sidebar">

    <?php require 
    BASE_PATH . '/app/Views/layouts/sidebar.php'; 
    ?>

    <main class="page page--gray page--summary">

        <header class="page__header">
            
            <h1 class="page__title">Staff Dashboard</h1>

        </header>

        <section class="grid grid--summary ">

            <div class="card card--action">

                <i class="card__icon fa-regular fa-file"></i>

                <p class="card__description">New Reports</p>

            </div>

            <div class="card card--action">

                <i class="card__icon fa-solid fa-comment"></i>

                <p class="card__description">Remarks</p>

            </div>

            <div class="card card--action">
                <h2 class="card__status">Pending</h2>

            </div>

            <div class="card card--action">

                <i class="card__icon fa-solid fa-clipboard"></i>

                <p class="card__description">Reports Validated</p>
            </div>

            <div class="card card--action">

                <i class="card__icon fa-solid fa-check"></i>

                <p class="card__description">Resolved</p>
            </div>

            <div class="card card--action">

                <i class="card__icon fa-solid fa-circle-exclamation"></i>

                <p class="card__description">Escalated</p>
            </div>

        </section>

    </main>
</div>