<?php



// INCIDENT FORM (READ-ONLY / REVIEW MODE)
$mode        = $mode ?? 'view';      // use controller value if exists
$isReadOnly  = ($mode === 'view');
$readonly    = $isReadOnly ? 'readonly' : '';
$disabled    = $isReadOnly ? 'disabled' : '';

?>


<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page-incident-review">

        <!-- CURRENT STATUS -->
        <div class="status-current">
            <p>
                <strong>Current Status:</strong>
                <?php
                $statusClass = match($incident['status']) {
                    'new'         => 'status--new',
                    'validated'   => 'status--validated',
                    'invalidated' => 'status--invalidated',
                    'ongoing'     => 'status--ongoing',
                    'escalated'   => 'status--escalated',
                    'resolved'    => 'status--resolved',
                    default       => 'status--new'
                };
                ?>

                <span class="status-badge <?= $statusClass ?>">
                    <?= htmlspecialchars($incident['status']) ?>
                </span>
            </p>
        </div>

        <!-- INCIDENT FORM (READ-ONLY / REVIEW MODE) -->
        <?php
           

            require BASE_PATH . '/app/Views/reporter/report-form.php';
        ?>

       

        
    <!-- STEPPER -->
    <?php if  (!empty($steps)): ?>
       <?php require BASE_PATH . '/app/Views/layouts/steps-bar.php'; ?>
    <?php endif; ?>

    


    </main>
</div>


