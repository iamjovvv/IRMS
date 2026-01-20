<main class="page page-login">
    <section class="form-wrapper">
        <form class="form form--compact"
              method="POST"
              action="/RMS/public/index.php?url=dashboard/staff">

            <h2 class="form__title">LOGIN</h2>

            <?php if (!empty($errors)): ?>
                <div class="form__errors">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="form__field">
                <input
                    type="text"
                    class="form__input"
                    name="username"
                    placeholder="Username"
                    required>
            </div>

            <div class="form__field">
                <input
                    type="password"
                    class="form__input"
                    name="password"
                    placeholder="Password"
                    required>
            </div>

            <button type="submit" class="btn btn--primary">Login</button>

            <div class="form__redirect">
                <a href="#" class="form__subtitle">Forgot password?</a>
            </div>
        </form>
    </section>
</main>
