<div class="with-sidebar">

    <?php require_once BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>
    <main class="page page--accounts">
        <header class="page__header">
            <h1 class="page__title">Responder Accounts</h1>
        </header>

        <div class="page__container page__container-accounts">
            <table class="report-table">
                <thead>
                    <tr class="report-table__row report-table__row--head">
                        <th class="report-table__cell">ID</th>
                        <th class="report-table__cell">Organization</th>
                        <th class="report-table__cell">Username</th>
                        <th class="report-table__cell">Email</th>
                        <th class="report-table__cell">Phone</th>
                        <th class="report-table__cell">Status</th>
                        <th class="report-table__cell">Created</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if(!empty($responders)): ?>

                        <?php foreach ($responders as $responder): ?>
                        <tr>
                            <td class="report-table__cell"><?= htmlspecialchars($responder['id']) ?></td>
                            <td class="report-table__cell"><?= htmlspecialchars($responder['organization_name']) ?></td>
                            <td class="report-table__cell"><?= htmlspecialchars($responder['username']) ?></td>
                            <td class="report-table__cell"><?= htmlspecialchars($responder['contact_email']) ?></td>
                            <td class="report-table__cell"><?= htmlspecialchars($responder['contact_phone']) ?></td>
                            <td class="report-table__cell"><?= htmlspecialchars($responder['status']) ?></td>
                            <td class="report-table__cell"><?= htmlspecialchars($responder['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="report-table__cell">
                                No responders found.
                            </td>
                        </tr>
                    
                    <?php endif; ?>


                        

                </tbody>

            </table>

        </div>

    </main>
</div>