<?php
$type = $_GET['type'] ?? ($type ?? '');
$columns = $columns ?? [];
$records = $records ?? [];
$fields = $fields ?? [];
$page_title = $page_title ?? 'Records';

$currentCategory = $_GET['category'] ?? '';
$currentStatus = $_GET['status'] ?? '';


$currentDateFrom = $_GET['date_from'] ?? '';
$currentDateTo = $_GET['date_to'] ?? '';

?>

<style>
  /* Filter Bar Container */
.filter-bar {
    display: flex;
    gap: 20px;
    align-items: flex-end;
    flex-wrap: wrap;
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

/* Each Filter Group */
.filter-group {
    display: flex;
    flex-direction: column;
    font-size: 14px;
}

.filter-group label {
    margin-bottom: 5px;
    font-weight: 600;
    color: #444;
}

/* Inputs & Select */
.filter-group input,
.filter-group select {
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
    min-width: 150px;
}

/* Date inline layout */
.filter-inline {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-inline span {
    font-size: 13px;
    color: #777;
}
</style>

<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--new-reports">
        <header class="page__header">
            <h1 class="page__title"><?= htmlspecialchars($page_title) ?></h1>
        </header>

        <div class="page__container page__container-reports">

            

            <!-- Filters only for incidents -->
           <?php if ($type === 'incident'): ?>
<div class="filter-bar">

    <div class="filter-group">
        <label>Date Range</label>
        <div class="filter-inline">
            <input type="date"
                   id="filter-date-from"
                   value="<?= htmlspecialchars($currentDateFrom) ?>">
            <span>to</span>
            <input type="date"
                   id="filter-date-to"
                   value="<?= htmlspecialchars($currentDateTo) ?>">
        </div>
    </div>

    <div class="filter-group">
        <label>Category</label>
        <select id="filter-category">
            <option value="" <?= $currentCategory === '' ? 'selected' : '' ?>>All</option>
            <option value="theft" <?= $currentCategory === 'theft' ? 'selected' : '' ?>>Theft</option>
            <option value="harassment" <?= $currentCategory === 'harassment' ? 'selected' : '' ?>>Harassment</option>
            <option value="accident" <?= $currentCategory === 'accident' ? 'selected' : '' ?>>Accident</option>
            <option value="other" <?= $currentCategory === 'other' ? 'selected' : '' ?>>Others</option>
        </select>
    </div>

    <div class="filter-group">
        <label>Status</label>
        <select id="filter-status">
            <option value="" <?= $currentStatus === '' ? 'selected' : '' ?>>All</option>
            <option value="new" <?= $currentStatus === 'new' ? 'selected' : '' ?>>New</option>
            <option value="under-review" <?= $currentStatus === 'under-review' ? 'selected' : '' ?>>Under Review</option>
            <option value="ongoing" <?= $currentStatus === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
            <option value="resolved" <?= $currentStatus === 'resolved' ? 'selected' : '' ?>>Resolved</option>
        </select>
    </div>

</div>
<?php endif; ?>

            <table class="report-table" id="reports-table">
                <thead>
                    <tr class="report-table__row report-table__row--head">
                        <?php foreach ($columns as $col): ?>
                            <th class="report-table__cell"><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>

                         <?php if ($type === 'incident'): ?>
                            <th class="report-table__cell">Action</th>
                        <?php endif; ?>


                    </tr>
                </thead>


                <tbody>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="<?= count($columns) ?>" class="report-table__cell">No records found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($records as $record): ?>
    <tr class="report-table__row">
        <?php foreach ($fields as $field): ?>
            <td class="report-table__cell">
                <?= htmlspecialchars($record[$field] ?? '') ?>
            </td>
        <?php endforeach; ?>

        <?php if ($type === 'incident'): ?>
            <td class="report-table__cell">
                <?php if (($record['status'] ?? '') === 'resolved'): ?>
                    <a class="table__action"
                       href="/RMS/public/index.php?url=admin/finalReport&code=<?= urlencode($record['tracking_code']) ?>">
                        View
                    </a>
                <?php else: ?>
                    <a class="table__action"
                       href="/RMS/public/index.php?url=admin/viewIncident&code=<?= urlencode($record['tracking_code']) ?>">
                        View
                    </a>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </main>
</div>

<?php if ($type === 'incident'): ?>
<script>
const categorySelect = document.getElementById('filter-category');
const statusSelect = document.getElementById('filter-status');
const dateFromInput = document.getElementById('filter-date-from');
const dateToInput = document.getElementById('filter-date-to');

function applyFilters() {
    const params = new URLSearchParams(window.location.search);

    if (categorySelect.value) params.set('category', categorySelect.value);
    else params.delete('category');

    if (statusSelect.value) params.set('status', statusSelect.value);
    else params.delete('status');

    if (dateFromInput.value) params.set('date_from', dateFromInput.value);
    else params.delete('date_from');

    if (dateToInput.value) params.set('date_to', dateToInput.value);
    else params.delete('date_to');

    // Preserve type so JS runs after reload
    params.set('type', 'incident');

    // Redirect to reportsTable
    window.location.href = `/RMS/public/index.php?url=admin/reportsTable&${params.toString()}`;
}

categorySelect.addEventListener('change', applyFilters);
statusSelect.addEventListener('change', applyFilters);
dateFromInput.addEventListener('change', applyFilters);
dateToInput.addEventListener('change', applyFilters);
</script>
<?php endif; ?>