<?php
define ('APP_STARTED', true);


$page_css= ['dashboard-admin.css', 'topnavbar.css', 'sidebar.css'];

require '../app/Views/layouts/header.php';


 ?>


<!-- Main Content -->
    <main class="dashboard">
        <h2 class="dashboard__greeting">Hello [Name]</h2>

        <section class="dashboard__cards">
            <article class="dashboard__card">
                <span class="dashboard__icon"><i class="fa-regular fa-file"></i></span>
                <h3 class="dashboard__title">Total Reports</h3>
            </article>

            <article class="dashboard__card">
                <span class="dashboard__icon"><i class="fa-solid fa-layer-group"></i></span>
                <h3 class="dashboard__title">Total Served Reporters</h3>
            </article>

            <article class="dashboard__card">
                <span class="dashboard__icon"><i class="fa-solid fa-user"></i></span>
                <h3 class="dashboard__title">Total Staff Accounts</h3>
            </article>

            <article class="dashboard__card">
                <span class="dashboard__icon"><i class="fa-solid fa-user"></i></span>
                <h3 class="dashboard__title">Total Reporter Accounts</h3>
            </article>
            
        </section>
    </main>

