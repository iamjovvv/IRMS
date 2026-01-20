<div class="with-sidebar">
   
    <?php require BASE_PATH . '../app/Views/layouts/sidebar.php' ?>

    <main class="page page-total-reports">

        <header class="page__header">

        <h1 class="page__title">Total Reports</h1>
        
        </header>

        <div class="page__container">
                    
            <section  class="page-reports">


                

                    <div class="page-report__chart">
                        <canvas id="totalReportsChart"></canvas>

                    </div>

            </section>
                    <p class="page-report__subtitle">
                        Incidents Submitted in the month of May
                    </p>
            

            
                <div class="page-report__summary">

                    <h2 class="page-report__label">Total Reports</h2>
                    <span class="page-report__count">234</span>
                    
                </div>

            <ul class="page-report__status">

                    <li><span>Pending</span><strong>-</strong></li>
                    <li><span>In Progress</span><strong>-</strong></li>
                    <li><span>Resolved</span><strong>-</strong></li>
                    <li><span>Rejected</span><strong>-</strong></li>

                </ul>
            
        </div>

    </main>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/assets/js/charts/totalReports.chart.js"></script>


<script>
    window.totalReportData = <?= json_encode($reportStats) ?>;
</script>