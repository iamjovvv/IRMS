<div class="with-sidebar">
    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--account-form">
        <header class="page__header">
            <h1 class="page__title"><?= htmlspecialchars($page_title) ?></h1>
        </header>

        <section class="form-wrapper">

            <?php if (!empty($_SESSION['error'])): ?>
                <script>window.accountErrors = <?= json_encode([$_SESSION['error']]) ?>;</script>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <script>window.accountSuccess = <?= json_encode([$_SESSION['success']]) ?>;</script>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form class="form form--compact" method="POST"
                  action="/RMS/public/index.php?url=admin/updateUser&id=<?= (int)$account['id'] ?>">

                <input type="hidden" name="role" value="<?= htmlspecialchars($accountRole) ?>">

                <!-- Role (read-only display) -->
                <div class="form__field">
                    <label class="form__label">Role</label>
                    <input type="text" class="form__input" value="<?= ucfirst(htmlspecialchars($accountRole)) ?>" disabled>
                </div>

                <!-- Common fields -->
                <div class="form__field">
                    <label class="form__label">Username</label>
                    <input type="text" name="username" class="form__input"
                           value="<?= htmlspecialchars($account['username']) ?>" required>
                </div>

                <div class="form__field">
                    <label class="form__label">New Password <small>(leave blank to keep current)</small></label>
                    <input type="password" name="password" class="form__input">
                </div>

                <!-- Status -->
                <div class="form__field">
                    <label class="form__label">Status</label>
                    <select name="status" class="form__select">
                        <?php foreach (['active', 'inactive', 'banned'] as $s): ?>
                            <option value="<?= $s ?>" <?= ($account['status'] ?? '') === $s ? 'selected' : '' ?>>
                                <?= ucfirst($s) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Reporter fields -->
                <?php if ($accountRole === 'reporter'): ?>
                <div class="form__field">
                    <label class="form__label">ID Number</label>
                    <input type="text" name="id_number" class="form__input"
                           value="<?= htmlspecialchars($account['org_id_number'] ?? '') ?>">
                </div>
                <div class="form__field">
                    <label class="form__label">Phone</label>
                    <input type="text" name="phone" class="form__input"
                           value="<?= htmlspecialchars($account['phone'] ?? '') ?>">
                </div>
                <?php endif; ?>

                <!-- Staff fields -->
                <?php if ($accountRole === 'staff'): ?>
                <div class="form__field">
                    <label class="form__label">Staff ID</label>
                    <input type="text" name="staff_id" class="form__input"
                           value="<?= htmlspecialchars($account['staff_id'] ?? '') ?>">
                </div>
                <div class="form__field">
                    <label class="form__label">Position</label>
                    <input type="text" name="position" class="form__input"
                           value="<?= htmlspecialchars($account['position'] ?? '') ?>">
                </div>
                <div class="form__field">
                    <label class="form__label">Office</label>
                    <input type="text" name="office" class="form__input"
                           value="<?= htmlspecialchars($account['office'] ?? '') ?>">
                </div>
                <?php endif; ?>

                <!-- Responder fields -->
                <?php if ($accountRole === 'responder'): ?>
                <div class="form__field">
                    <label class="form__label">Organization Name</label>
                    <input type="text" name="organization_name" class="form__input"
                           value="<?= htmlspecialchars($account['organization_name'] ?? '') ?>" required>
                </div>
                <div class="form__field">
                    <label class="form__label">Contact Email</label>
                    <input type="email" name="contact_email" class="form__input"
                           value="<?= htmlspecialchars($account['contact_email'] ?? '') ?>">
                </div>
                <div class="form__field">
                    <label class="form__label">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form__input"
                           value="<?= htmlspecialchars($account['contact_phone'] ?? '') ?>">
                </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn--primary btn--block">Save Changes</button>
                    <a href="/RMS/public/index.php?url=admin/accountsMgmt&role=<?= $accountRole ?>"
                       class="btn btn--secondary btn--block">Cancel</a>
                </div>

            </form>
        </section>
    </main>
</div>