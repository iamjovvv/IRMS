<?php
$statusClass = match ($incident['status']) {
    'pending'     => 'card__status--pending',
    'validated'   => 'card__status--validated',
    'ongoing'     => 'card__status--ongoing',
    'resolved'    => 'card__status--resolved',
    'invalidated' => 'card__status--invalid',
    default       => 'card__status--pending'
};
?>

<main class="page page--gray">
    <header class="page__header">
        <h1 class="page__title">Report Status</h1>
    </header>

    <section class="card" style="max-width:680px; margin: 2rem auto; padding: 2rem;">

        <!-- Current Status -->
        <div class="status-row">
            <strong>Current Status:</strong>
            <span class="card__status <?= $statusClass ?>">
                <?= ucfirst($incident['status']) ?>
            </span>
        </div>

        <div class="status-row">
            <strong>Submitted Date:</strong>
            <?= !empty($incident['created_at'])
                ? date('F d, Y', strtotime($incident['created_at']))
                : '—' ?>
        </div>

        <div class="status-row">
            <strong>Submitted Time:</strong>
            <?= !empty($incident['created_at'])
                ? date('h:i A', strtotime($incident['created_at']))
                : '—' ?>
        </div>

        <hr style="margin: 1.5rem 0;">

        <h3>Response &amp; Action Taken</h3>

        <?php if (!empty($escalation)): ?>
        <hr style="margin: 1.5rem 0;">

        <h3>⚠️ Fatal Incident — External Responder Assigned</h3>

        <div class="status-row">
            <strong>Forwarded To:</strong>
            <span><?= htmlspecialchars($escalation['responder_name']) ?></span>
        </div>

        <div class="status-row">
            <strong>Responder Role:</strong>
            <span><?= htmlspecialchars(ucfirst($escalation['responder_role'] ?? 'External Responder')) ?></span>
        </div>

        <div class="status-row">
            <strong>Forwarded On:</strong>
            <span>
                <?= !empty($escalation['escalated_at'])
                    ? date('F d, Y h:i A', strtotime($escalation['escalated_at']))
                    : '—' ?>
            </span>
        </div>

        <?php if (!empty($escalation['description'])): ?>
        <div class="status-row">
            <strong>Notes:</strong>
            <span><?= nl2br(htmlspecialchars($escalation['description'])) ?></span>
        </div>
        <?php endif; ?>

        <?php endif; ?>


        <div class="status-row">
            <strong>Assigned Officer / Personnel:</strong>
            <?php
            $officerName = trim(
                ($response['report_officer_first']  ?? '') . ' ' .
                ($response['report_officer_middle'] ?? '') . ' ' .
                ($response['report_officer_last']   ?? '')
            );
            echo !empty($officerName) ? htmlspecialchars($officerName) : '—';
            ?>
        </div>

        <div class="status-row">
            <strong>Description of Scene / Remarks:</strong>
            <?= !empty($response['action_taken'])
                ? nl2br(htmlspecialchars($response['action_taken']))
                : '—' ?>
        </div>

        <div class="status-row">
            <strong>Investigation Findings:</strong>
            <?= !empty($response['investigation_findings'])
                ? nl2br(htmlspecialchars($response['investigation_findings']))
                : '—' ?>
        </div>

        <div class="status-row">
            <strong>Resolution &amp; Disposition:</strong>
            <?= !empty($response['resolution_disposition'])
                ? nl2br(htmlspecialchars($response['resolution_disposition']))
                : '—' ?>
        </div>

        <div class="status-row">
            <strong>Resolution Date:</strong>
            <?= !empty($response['resolution_date'])
                ? date('F d, Y', strtotime($response['resolution_date']))
                : '—' ?>
        </div>

        <div class="status-row">
            <strong>Resolution Time:</strong>
            <?= !empty($response['resolution_time'])
                ? date('h:i A', strtotime($response['resolution_time']))
                : '—' ?>
        </div>

    </section>

    <a href="javascript:history.back()" class="btn btn--ghost">← Back</a>

</main>



<style>
.status-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.8rem;
    font-size: 0.95rem;
    align-items: flex-start;
}
.status-row strong {
    min-width: 220px;
    color: #444;
}
</style>