<?php
$statusClass = match ($incident['status']) {
'pending' => 'card__status--pending',
'validated' => 'card__status--validated',
'ongoing' => 'card__status--ongoing',
'resolved' => 'card__status--resolved',
'invalidated' => 'card__status--invalid',
default => 'card__status--pending'
};
?>


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
           
                <h2 class="card__status <?= $statusClass ?>">
<?= ucfirst($incident['status']) ?>
</h2>
<p class="card__description">Current Status</p>
          
        </a>



   
   </section>


</main>


<?php if (!empty($incident['action_taken'])): ?>
    <div class="incident-action">
        <h3>Latest Action Taken</h3>

        <p>
            <strong>Status Update:</strong>
            <?= ucfirst($incident['status_update']) ?>
        </p>

        <p>
            <strong>Action:</strong><br>
            <?= nl2br(htmlspecialchars($incident['action_taken'])) ?>
        </p>

        <p class="meta">
            Handled by:
            <?= htmlspecialchars($incident['responder_name'] ?? 'System') ?>
            (<?= $incident['responder_role'] ?? 'N/A' ?>)
        </p>

        <small>
            <?= date('M d, Y h:i A', strtotime($incident['action_date'])) ?>
        </small>
    </div>
<?php else: ?>
    <p class="muted">No action has been taken yet.</p>
<?php endif; ?>