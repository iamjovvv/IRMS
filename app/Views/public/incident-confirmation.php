<main class="page page--confirmation">
    <section class="form-wrapper">
        <form class="form form--compact">
            <h2 class="form__title">Report Submitted Successfully</h2>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <p class="form__subtitle">Please save your tracking code:</p>
            <div class="form__field">
                <h1 class="form__title form__title-trackingCode">
                    <?= htmlspecialchars($trackingCode) ?>
                </h1>
            </div>

            <p class="form__subtitle">You can use this code to track your report.</p>

            <button href="/RMS/public/index.php?url=reporter/track" class="btn btn--primary">
                    TRACK STATUS
                </button>
            
        </form>
    </section>
</main>