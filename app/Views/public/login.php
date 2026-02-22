
<main class="page page-login">
    <section class="form-wrapper">

    <?php if (!empty($errors)): ?>
<script>
    window.toastErrors = <?= json_encode(array_values($errors)) ?>;
</script>
<?php endif; ?>

<div id="toast-container"></div>


    


        <form class="form form--compact"
              method="POST"
              action="/RMS/public/index.php?url=login">

            <h2 class="form__title">LOGIN</h2>

            <div id="toast-container"></div>

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

            <!-- <div class="form__redirect">
                <a href="#" class="form__subtitle">Forgot password?</a>
            </div> -->
        </form>
    </section>
</main>




<style>

#toast-container {
    position: fixed;
    top: 16px;
    right: 16px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Toast box */
.toast {
    min-width: 260px;
    max-width: 320px;
    background: #fff4f4;
    border-left: 4px solid #dc3545;
    color: #842029;
    padding: 12px 14px;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    animation: toastSlideIn 0.25s ease;
}

/* Close button */
.toast__close {
    background: transparent;
    border: none;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    color: #842029;
    padding-left: 12px;
}

/* Animations */
@keyframes toastSlideIn {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 480px) {
#toast-container {
top: auto;
bottom: 16px;
right: 50%;
transform: translateX(50%);
}
}

.toast--success { border-left-color: #198754; background: #e9f7ef; }
.toast--warning { border-left-color: #ffc107; background: #fff8e1; }

</style>

