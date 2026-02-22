<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--accounts">

    <style>
        .table__actions {
      position: fixed;
    top: 120px;
    right: 30px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btns-primary,
.btns-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
    font-family: 'Georgia', serif;
    letter-spacing: 0.5px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    white-space: nowrap;
}

.btns-primary         { background: #1a237e; color: #fff; }
.btns-primary:hover   { background: #283593; }
.btns-secondary       { background: #fff; color: #1a237e; border: 1px solid #1a237e; }
.btns-secondary:hover { background: #e8eaf6; }
    </style>

        <header class="page__header">
            <h1 class="page__title"><?= htmlspecialchars($page_title) ?></h1>
        </header>

        <div class="page__container page__container-accounts">

            <div class="table__actions">
                
                <button class="btns-primary">
                    <i class="fa-solid fa-file-export"></i> Export
                </button>
                <!-- <button class="btn__action btn__action--add" id="openCreateUser">
                    <i class="fa-solid fa-plus"></i> Add Account
                </button> -->

                <a href="/RMS/public/index.php?url=admin/createUser" class="btns-secondary">
                + Create Account
                </a>     

            </div>


           
                <table class="report-table">

                    <thead>
                        <tr class="report-table__row report-table__row--head">
                            <?php foreach ($columns as $col): ?>
                                <th class="report-table__cell"><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                            <th class="report-table__cell">Action</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php if (!empty($accounts)): ?>
                            
                            <?php foreach ($accounts as $account): ?>
                                <tr class="report-table__row">
                                    <?php foreach ($fields as $field): ?>
                                        <td class="report-table__cell"><?= htmlspecialchars($account[$field] ?? '') ?></td>
                                    <?php endforeach; ?>
                                    <td class="report-table__cell">
                                        <a class="table__action" href="/RMS/public/index.php?url=admin/editUser&id=<?= $account['user_id'] ?>">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>
                            
                            <tr>
                                <td colspan="<?= count($columns) + 1 ?>" class="report-table__cell">No accounts found</td>
                            
                            </tr>
                        <?php endif; ?>
                        
                    </tbody>

                </table>

        </div>

    </main>

</div>





<script>
document.addEventListener('DOMContentLoaded', () => {


    const openBtn = document.getElementById('openCreateUser');
    const closeBtn = document.getElementById('closeCreateUser');
    const modal = document.getElementById('createUserModal');


    const roleSelect = document.getElementById('roleSelect');
    const reporterFields = document.getElementById('reporterFields');
    const staffFields = document.getElementById('staffFields');
    const responderFields = document.getElementById('responderFields');


    openBtn.addEventListener('click', () => modal.classList.add('active'));
    closeBtn.addEventListener('click', () => modal.classList.remove('active'));


    roleSelect.addEventListener('change', () => {
    reporterFields.style.display = 'none';
    staffFields.style.display = 'none';
    responderFields.style.display = 'none';


    if (roleSelect.value === 'reporter') reporterFields.style.display = 'block';
    if (roleSelect.value === 'staff') staffFields.style.display = 'block';
    if (roleSelect.value === 'responder') responderFields.style.display = 'block';
    });

});
                                
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const exportBtn = document.querySelector('.btn__action--export');

    exportBtn.addEventListener('click', () => {
        const table = document.querySelector('.report-table');
        const rows = Array.from(table.querySelectorAll('tr'));
        let csvContent = '';

        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('th, td'));
            const rowData = cells.map(cell => `"${cell.textContent.replace(/"/g, '""')}"`);
            csvContent += rowData.join(',') + '\r\n';
        });

        // Download as CSV
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;

        // Dynamic filename based on page
        const pageTitle = document.querySelector('.page__title').textContent.trim().replace(/\s+/g, '_');
        a.download = `${pageTitle}.csv`;

        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
    
});
</script>