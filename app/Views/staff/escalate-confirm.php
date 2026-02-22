<div class="with-sidebar">
    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php' ?>
    <main class="page page-escalate-confirm">
        <div class="form__wrapper">
            <h2>Escalation Complete ✅</h2>
            <p>The incident <strong><?= htmlspecialchars($incident['tracking_code']) ?></strong> has been forwarded successfully.</p>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <a href="/RMS/public/index.php?url=staff/reportsEscalated" class="btn btn--primary">
                Back to New Reports
            </a>
        </div>
    </main>
</div>
