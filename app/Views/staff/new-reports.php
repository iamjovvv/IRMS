<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--new-reports">
        <header class= "page__header">
            <h1  class="page__title">New Reports</h1>
        </header>


        <div class="page__container page__container-reports">

        
            
            <div class="table__selection">

                <!-- <select class="table__select">

                    <option value="">Priority</option>
                    <option value="low">low</option>
                    <option value="medium">medium</option>
                    <option value="high">high</option>
                    <option value="critical">critical</option>

                </select> -->
                
                <select class="table__select"  id="filter-category">
                    <option value="all" selected>All</option>
                    <option value="theft">theft</option>
                    <option value="harassment">harassment</option>
                    <option value="accident">accident</option>
                    <option value="other">others, please specify</option>

                </select>

                <select class="table__select" id="filter-status">
                    <option value="">Status</option>
                    <option value="new">new</option>
                    <option value="under review">under review</option>
                    <option value="ongoing">ongoing</option>
                    <option value="resolved">resolved</option>

                </select>

            </div>




            <table class="report-table" id="reports-table">

                <thead>

                    <tr class="report-table__row report-table__row--head">

                        <th class="report-table__cell">ID</th>
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
                            <td colspan="7" class="report-table__cell">No new reports</td>
                        </tr>
                    <?php else: ?>

                        <?php foreach ($reports as $incident): ?>

                            <tr class="report-table__row <?= $incident['incident_type'] === 'fatal' ? 'report-table__row--fatal' : '' ?>">
                                <td class="report-table__cell"><?= (int) $incident['id'] ?></td>

                                <td class="report-table__cell"><?= htmlspecialchars($incident['subject']) ?></td>

                                <td class="report-table__cell"><?= htmlspecialchars($incident['category']) ?></td>

                                <td class="report-table__cell"><?= htmlspecialchars($incident['status'] ?? 'new') ?></td>

                                <td class="report-table__cell"><?= date('M d, Y', strtotime($incident['created_at'])) ?></td>

                                <td class="report-table__cell">
                                    <a class="table__action" href="/RMS/public/index.php?url=incident/summaryReport&code=<?= urlencode($incident['tracking_code']) ?>">View</a>
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
    const statusSelect = document.getElementById('filter-status');

    function applyFilters() {
        const category = categorySelect.value;
        const status = statusSelect.value;

        // Build URL with query parameters
        const params = new URLSearchParams();
        if(category) params.append('category', category);
        if(status) params.append('status', status);

        // Redirect page with filters
        window.location.href = `/RMS/public/index.php?url=staff/newReports&${params.toString()}`;
    }

    categorySelect.addEventListener('change', applyFilters);
    statusSelect.addEventListener('change', applyFilters);
</script>