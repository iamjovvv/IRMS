<?php
if (!isset($steps, $currentStep)) return;

$code = $_GET['code'] ?? '';
?>

<div class="stepper">
    <?php foreach ($steps as $index => $label): 
        $stepNumber = $index + 1;

        $state =
            $stepNumber < $currentStep ? 'completed' :
            ($stepNumber === $currentStep ? 'active' : 'upcoming');

        $url = "/RMS/public/index.php?url=staff/reviewIncident&code={$code}&step={$stepNumber}";
    ?>

        <?php if ($stepNumber === $currentStep): ?>
            <!-- CURRENT STEP (not clickable) -->
            <div class="step <?= $state ?>">
                <div class="circle"><?= $stepNumber ?></div>
                <div class="label"><?= $label ?></div>
            </div>
        <?php else: ?>
            <!-- CLICKABLE STEP -->
            <a href="<?= $url ?>" class="step <?= $state ?>">
                <div class="circle"><?= $stepNumber ?></div>
                <div class="label"><?= $label ?></div>
            </a>
        <?php endif; ?>

        <?php if ($stepNumber < count($steps)): ?>
            <div class="line <?= $stepNumber < $currentStep ? 'filled' : '' ?>"></div>
        <?php endif; ?>

    <?php endforeach; ?>
</div>
