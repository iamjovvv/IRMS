

<div class="with-sidebar">

 <?php $_SESSION['role']= 'staff' ?>

    <?php require 
    BASE_PATH . '/app/Views/layouts/sidebar.php'; 
    ?>

    <main class="page page--gray page--summary">

        <header class="page__header">
            
            <h1 class="page__title">Staff Dashboard</h1>

        </header>

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

       





        <!-- <section class="grid grid--summary ">

            <div class="card card--action"  onclick="location.href='/RMS/public/index.php?url=staff/newReports'">
                

                <i class="card__icon fa-regular fa-file"></i>

                <p class="card__description">New Reports</p>

            </div>

           

            <div class="card card--action"
                onclick="location.href='/RMS/public/index.php?url=staff/reportsValidated'">

                <i class="card__icon fa-solid fa-clipboard-check"></i>

                <p class="card__description">Validated Reports</p>

                

            </div>


            <a class="card card--action"
               onclick="location.href='/RMS/public/index.php?url=staff/reportsInvalidated'">

                <i class="card__icon fa-solid fa-file-excel"></i>

                <p class="card__description">Invalidated Reports</p>
            </a>


            <a class="card card--action"
                onclick="location.href='/RMS/public/index.php?url=staff/reportsEscalated'">

                <i class="card__icon fa-solid fa-circle-exclamation"></i>

                <p class="card__description">Escalated Reports</p>
            </a>

            
        </section> -->

        <section class="grid grid--summary">

      
        
        <a class="card card--action" href="/RMS/public/index.php?url=staff/newReports">
            <i class="card__icon fa-regular fa-file"></i>
            <p class="card__description">New Reports</p>

</a>



  
        
        <a class= "card card--action" href="/RMS/public/index.php?url=staff/reportsValidated">
            <i class="card__icon fa-regular fa-file"></i>

            <p class="card__description">Validated Reports</p>
        </a>
  

 
        
        <a class="card card--action" href="/RMS/public/index.php?url=staff/reportsInvalidated">
            <i class="card__icon fa-solid fa-layer-group"></i>
            <p class="card__description">Invalidated Reports</p>
        </a>


   
        
        <a class="card card--action"  href="/RMS/public/index.php?url=staff/reportsEscalated">
            <i class="card__icon fa-solid fa-user"></i>
            <p class="card__description">Escalated Reports</p>
        </a>
  

</section>

    </main>
</div>