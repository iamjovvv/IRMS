<div class="with-sidebar">
    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--account-form">
        

        <header class="page__header">
            <h1 class="page__title">Create New Account</h1>
        </header>

        <section class="form-wrapper">

            <?php if (!empty($_SESSION['error'])): ?>
                <script>
                window.accountErrors = <?= json_encode([$_SESSION['error']]); ?>;
                <?php unset($_SESSION['error']); ?>
                </script>
                <?php endif; ?>


                <?php if (!empty($_SESSION['success'])): ?>
                <script>
                window.accountSuccess = <?= json_encode([$_SESSION['success']]); ?>;
                <?php unset($_SESSION['success']); ?>
                </script>
            <?php endif; ?>

            <form class="form form--compact" method="POST"
                  action="/RMS/public/index.php?url=admin/storeUser">

                <!-- Role -->
                <div class="form__field">
                    <label class="form__label" for="roleSelect">Role</label>
                    <select name="role" id="roleSelect" class="form__select" required>
                        <option value="">Select role</option>
                        <option value="reporter">Reporter</option>
                        <option value="staff">Staff</option>
                        <option value="responder">Responder</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Common fields -->
                <div class="form__field">
                    <label class="form__label">Username</label>
                    <input type="text" name="username" class="form__input" required>
                </div>

                <div class="form__field">
                    <label class="form__label">Password</label>
                    <input type="password" name="password" class="form__input" required>
                </div>

                <!-- Reporter fields -->
                <div id="reporterFields" class="role-fields hidden">
                    <div class="form__field">
                        <label class="form__label">ID Number</label>
                        <input type="text" name="id_number" class="form__input">
                    </div>
                    <div class="form__field">
                        <label class="form__label">Phone</label>
                        <input type="text" name="phone" class="form__input">
                    </div>
                </div>

                <!-- Staff fields -->
                <div id="staffFields" class="role-fields hidden">
                    <div class="form__field">
                        <label class="form__label">Position</label>
                        <input type="text" name="position" class="form__input">
                    </div>
                    <div class="form__field">
                        <label class="form__label">Office</label>
                        <input type="text" name="office" class="form__input">
                    </div>
                </div>

                <!-- Responder fields -->
                <div id="responderFields" class="role-fields hidden">
                    <div class="form__field">
                        <label class="form__label">Organization Name</label>
                        <input type="text" name="organization_name" class="form__input" required>
                    </div>
                    <div class="form__field">
                        <label class="form__label">Contact Email</label>
                        <input type="email" name="contact_email" class="form__input">
                    </div>
                    <div class="form__field">
                        <label class="form__label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form__input">
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">

                    <button type="submit" class="btn btn--primary btn--block">
                        Create Account
                    </button>

                    <a href="/RMS/public/index.php?url=admin/accountsMgmt" class="btn btn--secondary btn--block">
                        Cancel
                    </a>

                </div>

            </form>

        </section>
    </main>
</div>


<style>

    .toast {
    min-width: 260px;
    max-width: 320px;
    padding: 12px 14px;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    animation: toastSlideIn 0.25s ease;
}

.toast--error {
    border-left: 4px solid #dc3545;
    background: #fff4f4;
    color: #842029;
}

.toast--success {
    border-left: 4px solid #198754;
    background: #e9f7ef;
    color: #0f5132;
}

.toast__close {
    background: transparent;
    border: none;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    padding-left: 10px;
}

@keyframes toastSlideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

#toast-container {
    position: fixed;
    top: 16px;
    right: 16px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
}



</style>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('toast-container');
    if (!container) return;

    function createToast(message, type = 'error') {
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.innerHTML = `
            <span>${message}</span>
            <button class="toast__close" aria-label="Close">&times;</button>
        `;
        container.appendChild(toast);

        // Auto-remove after 5s
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-6px)';
            setTimeout(() => toast.remove(), 300);
        }, 5000);

        toast.querySelector('.toast__close').addEventListener('click', () => toast.remove());
    }

    // Display errors
    if (window.accountErrors) {
        window.accountErrors.forEach(msg => createToast(msg, 'error'));
    }

    // Display success
    if (window.accountSuccess) {
        window.accountSuccess.forEach(msg => createToast(msg, 'success'));
    }
});

</script>