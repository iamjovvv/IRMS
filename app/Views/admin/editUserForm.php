<?php
$mode = $mode ?? 'edit';       // 'create' or 'edit'
$user = $user ?? [];           // common user info
$details = $details ?? [];     // role-specific details
$role = $role ?? ($user['role'] ?? '');
?>

<div class="with-sidebar">
    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--account-form">
        <div id="toast-container"></div>

        <header class="page__header">
            <h1 class="page__title"><?= $mode === 'edit' ? 'Edit Account' : 'Create New Account' ?></h1>
        </header>

        <section class="form-wrapper">

            <form class="form form--compact" method="POST"
                  action="/RMS/public/index.php?url=admin/<?= $mode === 'edit' ? 'updateUser&id=' . ($user['user_id'] ?? '') : 'storeUser' ?>">

                <!-- Role (disabled, with hidden input to submit) -->
                <div class="form__field">
                    <label class="form__label" for="roleSelect">Role</label>
                    <select name="role_display" id="roleSelect" class="form__select" disabled>
                        <option value="reporter" <?= $role === 'reporter' ? 'selected' : '' ?>>Reporter</option>
                        <option value="staff" <?= $role === 'staff' ? 'selected' : '' ?>>Staff</option>
                        <option value="responder" <?= $role === 'responder' ? 'selected' : '' ?>>Responder</option>
                        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <input type="hidden" name="role" value="<?= $role ?>">
                </div>

                <!-- Common fields -->
                <div class="form__field">
                    <label class="form__label">Username</label>
                    <input type="text" name="username" class="form__input" required
                           value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                </div>

                <div class="form__field">
                    <label class="form__label">
                        <?= $mode === 'edit' ? 'New Password (leave blank to keep current)' : 'Password' ?>
                    </label>
                    <input type="password" name="password" class="form__input" <?= $mode === 'create' ? 'required' : '' ?>>
                </div>

                <!-- Reporter fields -->
                <div id="reporterFields" class="role-fields hidden">
                    <div class="form__field">
                        <label class="form__label">ID Number</label>
                        <input type="text" name="id_number" class="form__input"
                               value="<?= htmlspecialchars($details['org_id_number'] ?? '') ?>">
                    </div>
                    <div class="form__field">
                        <label class="form__label">Phone</label>
                        <input type="text" name="phone" class="form__input"
                               value="<?= htmlspecialchars($details['phone'] ?? '') ?>">
                    </div>
                </div>

                <!-- Staff fields -->
                <div id="staffFields" class="role-fields hidden">
                    <div class="form__field">
                        <label class="form__label">Position</label>
                        <input type="text" name="position" class="form__input"
                               value="<?= htmlspecialchars($details['staff_id'] ?? '') ?>">
                    </div>
                    <div class="form__field">
                        <label class="form__label">Office</label>
                        <input type="text" name="office" class="form__input"
                               value="<?= htmlspecialchars($details['office'] ?? '') ?>">
                    </div>
                </div>

                <!-- Responder fields -->
                <div id="responderFields" class="role-fields hidden">
                    <div class="form__field">
                        <label class="form__label">Organization Name</label>
                        <input type="text" name="organization_name" class="form__input"
                               value="<?= htmlspecialchars($details['organization_name'] ?? '') ?>"
                               <?= $mode === 'create' ? 'required' : '' ?>>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn--primary btn--block">
                        <?= $mode === 'edit' ? 'Update Account' : 'Create Account' ?>
                    </button>

                    <a href="/RMS/public/index.php?url=admin/accountsMgmt" class="btn btn--secondary btn--block">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const roleSelect = document.getElementById('roleSelect');
    const sections = {
        reporter: document.getElementById('reporterFields'),
        staff: document.getElementById('staffFields'),
        responder: document.getElementById('responderFields')
    };

    const isEditMode = <?= $mode === 'edit' ? 'true' : 'false'; ?>;

    function hideAll() {
        Object.values(sections).forEach(section => {
            if (!section) return;
            section.classList.add('hidden');
            section.querySelectorAll('input').forEach(input => {
                input.required = false;
                if (!isEditMode) input.value = '';
            });
        });
    }

    function show(role) {
        hideAll();
        if (!sections[role]) return;
        sections[role].classList.remove('hidden');
        sections[role].querySelectorAll('input').forEach(input => input.required = true);
    }

    // Initial display of role-specific fields
    if (roleSelect.value) {
        show(roleSelect.value);
    }
});
</script>