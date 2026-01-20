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

            <a class="card card--action"
                href="/RMS/public/index.php?url=staff/newReports">

                <i class="card__icon fa-regular fa-file"></i>

                <p class="card__description">New Reports</p>

            </a>

            <a class="card card--action"
                href="/RMS/public/index.php?url=staff/remarks">

                <i class="card__icon fa-solid fa-comment"></i>

                <p class="card__description">Remarks</p>

            </a>


            <a class="card card--action"
                href="/RMS/public/index.php?url=staff/reportValidated">

                <i class="card__icon fa-solid fa-clipboard"></i>

                <p class="card__description">Reports Validated</p>
            </a>


            <a class="card card--action"
                href="/RMS/public/index.php?url=staff/reportEscalate">

                <i class="card__icon fa-solid fa-circle-exclamation"></i>

                <p class="card__description">Reports Escalated</p>
            </a>

            
        </section>

    </main>
</div>