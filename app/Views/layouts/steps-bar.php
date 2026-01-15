<?php

/**
 * Expected variables:
 * $currentStep (int) – 1-based index
 * $steps (array) – step labels
 */

if (!isset($steps, $currentStep)){
    return;
}

?>

<div class="stepper">
    <?php foreach ($steps as $index => $label):
        $stepNumber = $index + 1;

        $state =
            $stepNumber < $currentStep ? 'completed':
            ($stepNumber === $currentStep ? 'active' : 'upcoming');
        ?>

        <div class="step <?= $state?>">
            <div class="circle">
                <?= $stepNumber ?>
            </div>

            <div class="label">
                <?= $label  ?>
            </div>

        </div>

        <?php if ($stepNumber < count($steps)): ?>

            <div class="line <?= $stepNumber < $currentStep ? 'filled' : '' ?>"></div>

        <?php endif; ?>

    <?php endforeach; ?>
</div>

