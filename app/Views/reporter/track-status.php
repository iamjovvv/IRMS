<main class="page page-track-status">

   <section class="form-wrapper">

      <form class="form form--compact"
      method="POST"
      action="/RMS/public/index.php?url=reporter/track">
    
        <h2 class="form__title">TRACK STATUS</h2>

        <?php if (!empty($error)): ?> 
            <div class="form__error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="form__field">
            <input 
                type="text"
                class="form__input" 
                placeholder="Enter your code"
                name="tracking_code"
                value="<?= htmlspecialchars($_POST['tracking_code'] ?? '') ?>"
                required>
        </div>

        <button type="submit" class="btn btn--primary">Submit</button>

        <p class="form__subtitle">forgot code? <a href="#">Retrieve</a></p>

      </form>

   </section>

</main>