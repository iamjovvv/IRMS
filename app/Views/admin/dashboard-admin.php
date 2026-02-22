
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



<div class="with-sidebar">

    <?php require 
    BASE_PATH . '/app/Views/layouts/sidebar.php'; 
    ?>

    <main class="page page--gray page--summary">

        <header class="page__header">
            
            <h1 class="page__title">Admin Dashboard</h1>

        </header>

        <section class="grid grid--summary ">


                <a href="/RMS/public/index.php?url=admin/totalReports" class="card card--action">
                    <i class="card__icon fa-regular fa-file"></i>
                    <h3>Total Reports</h3>
                    
                </a>
            

                <a href="/RMS/public/index.php?url=admin/reportsTable" class="card card--action">
                    <i class="card__icon fa-regular fa-file"></i>
                    <h3>Reports</h3>
                    
                </a>


                <a href="/RMS/public/index.php?url=admin/reportsTable&status=resolved" class="card card--action">
                    <i class="card__icon fa-solid fa-clipboard-check"></i>
                    <h3>Served Reports</h3>
                    
                </a>


                <a href="/RMS/public/index.php?url=admin/accountsMgmt&role=staff" class="card card--action">
                    <i class="card__icon fa-solid fa-users"></i>
                    <h3>Staff</h3>
                    
                </a>


                <!-- <a href="/RMS/public/index.php?url=admin/accountsMgmt&role=responder" class="card card--action">
                    <i class="card__icon fa-solid fa-users"></i>
                    <h3>Responders</h3>
                    
                </a>


                <a href="/RMS/public/index.php?url=admin/accountsMgmt&role=reporter" class="card card--action">
                    <i class="card__icon fa-solid fa-handshake-angle"></i>
                    <h3>Reporter Org ID</h3>
                   
                </a>
                </a> -->
            
        </section>

    </main>
</div>