<?php
// $reports is expected to be passed from the controller


$pageTitle = $page_title ?? 'Reports';


?>



<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--new-reports">
        
        <header class="page__header">
            <h1 class="page__title"><?= htmlspecialchars($pageTitle) ?></h1>
        </header>

        <div class="page__container page__container-reports">

            <div class="table__selection">

                <?php $selectedCategory = $_GET['category'] ?? ''; ?>


                <select class="table__select" id="filter-category">

                    <option value="">All</option>

                    <option value="theft" <?= $selectedCategory === 'theft' ? 'selected' : '' ?>>theft</option>

                    <option value="harassment" <?= $selectedCategory === 'harassment' ? 'selected' : '' ?>>harassment</option>

                    <option value="accident" <?= $selectedCategory === 'accident' ? 'selected' : '' ?>>accident</option>

                </select>

            </div>

            <table class="report-table" id="reports-table">
                <thead>
                    <tr class="report-table__row report-table__row--head">
                        <th class="report-table__cell">ID</th>
                        <th class="report-table__cell">Tracking Code</th>
                        <th class="report-table__cell">Title</th>
                        <th class="report-table__cell">Category</th>
                        <th class="report-table__cell">Status</th>
                        <th class="report-table__cell">Date Reported</th>
                        <th class="report-table__cell">Action</th>
                        <th class="report-table__cell">Type</th>
                    </tr>
                </thead>
                

                <tbody>

                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="8" class="report-table__cell">No reports</td>
                        </tr>
                        
                        <?php else: ?>

                            <?php foreach ($reports as $incident): ?>
                                
                            <?php
                                // Determine mode based on status
                                $finalStatuses = ['validated','invalidated','escalated','resolved'];
                                $mode = in_array($incident['status'], $finalStatuses) ? 'view' : 'edit';
                            ?>
                            
                            <tr class="report-table__row <?= $incident['incident_type'] === 'fatal' ? 'report-table__row--fatal' : '' ?>">
                                
                                <td class="report-table__cell"><?= (int) $incident['id'] ?></td>

                                <td class="report-table__cell"><?= htmlspecialchars($incident['tracking_code']) ?></td>

                                <td class="report-table__cell"><?= htmlspecialchars($incident['subject']) ?></td>

                                <td class="report-table__cell"><?= htmlspecialchars($incident['category']) ?></td>

                                <td class="report-table__cell"><?= htmlspecialchars($incident['status'] ?? 'new') ?></td>

                                <td class="report-table__cell"><?= date('M d, Y', strtotime($incident['created_at'])) ?></td>
                    

                            <td class="report-table__cell">

                                <?php if ($role === 'responder'): ?>

                                    <a class="table__action"
                                    href="/RMS/public/index.php?url=responder/report-form&code=<?= urlencode($incident['tracking_code']) ?>">
                                        Update Status
                                    </a>

                                <?php else: ?>

                                    <?php if ($incident['status'] === 'resolved'): ?>

                                        <!-- When resolved → Open Final Report -->
                                        <a class="table__action"
                                        href="/RMS/public/index.php?url=staff/finalReport&code=<?= urlencode($incident['tracking_code']) ?>">
                                            View
                                        </a>

                                    <?php else: ?>

                                        <!-- Keep original behavior -->
                                        <a class="table__action"
                                        href="/RMS/public/index.php?url=staff/reviewIncident&code=<?= urlencode($incident['tracking_code']) ?>&mode=<?= $mode ?>">
                                            View
                                        </a>

                                    <?php endif; ?>

                            <?php endif; ?>




                            </td>
                                
                            <td class="report-table__cell"><?= htmlspecialchars($incident['incident_type']) ?></td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    
    </main>

</div>



<script>
    const categorySelect = document.getElementById('filter-category');


    function applyFilters() {
    const category = categorySelect.value;
    const params = new URLSearchParams();


    if (category !== '') {
    params.append('category', category);
    }


    const query = params.toString();
    const baseUrl = '/RMS/public/index.php?url=staff/newReports';


    window.location.href = query ? `${baseUrl}&${query}` : baseUrl;
    }


    categorySelect.addEventListener('change', applyFilters);
</script>
