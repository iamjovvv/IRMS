<main class="page page--gray page--summary">
        <header class="page__header">
                <h1 class="page__title">Incident Summary</h1>
        </header>

   <section class="grid grid--summary">
        
        <a class="card card--action"
           href="/RMS/public/index.php?url=incident/summaryReport&code=<?= $incident['tracking_code'] ?>">
            
                <i class="card__icon fa-regular fa-newspaper" ></i>
                <p class="card__description">Incident Details</p>

        </a>

        

        <a class="card card--action"
           href="/RMS/public/index.php?url=reporter/status&code=<?= $incident['tracking_code'] ?>">
           
                <h2 class="card__status card__status--pending">Pending</h2>
                
                <p class="card__description">Current Status</p>
          
        </a>



        <a class="card card--action"
         href="/RMS/public/index.php?url=reporter/evidence&code=<?= $incident['tracking_code'] ?>">
            
                <i class="card__icon fa-solid fa-plus "></i>
                <p class="card__description">Add Evidence</p>
           
        </a>



        <a class="card card--action"
         href="/RMS/public/index.php?url=reporter/remarks&code=<?= $incident['tracking_code'] ?>">
            
                <i class="card__icon fa-regular fa-comment"></i>
                <p class="card__description">Remarks</p>

        </a>

   
   </section>


</main>