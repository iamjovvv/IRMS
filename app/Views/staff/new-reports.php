<div class="with-sidebar">

    <?php require BASE_PATH . '/app/Views/layouts/sidebar.php'; ?>

    <main class="page page--new-reports">
        <header class= "page__header">
            <h1  class="page__title">New Reports</h1>
        </header>


        <div class="page__container page__container-reports">
            
            <div class="table__selection">

                <select class="table__select">

                    <option value="">Priority</option>
                    <option value="low">low</option>
                    <option value="medium">medium</option>
                    <option value="high">high</option>
                    <option value="critical">critical</option>

                </select>
                
                <select class="table__select">
                    <option value="">Category</option>
                    <option value="theft">theft</option>
                    <option value="harassment">harassment</option>
                    <option value="accident">accident</option>
                    <option value="other">others, please specify</option>

                </select>

                <select class="table__select">
                    <option value="">Status</option>
                    <option value="new">new</option>
                    <option value="under review">under review</option>
                    <option value="ongoing">ongoing</option>
                    <option value="resolved">resolved</option>

                </select>

            </div>




            <table class="report-table">

                <thead>

                    <tr class="report-table__row report-table__row--head">

                        <th class="report-table__cell">ID</th>
                        <th class="report-table__cell">Title</th>
                        <th class="report-table__cell">Category</th>
                        <th class="report-table__cell">Priority</th>
                        <th class="report-table__cell">Status</th>
                        <th class="report-table__cell">Date Reported</th>
                        <th class="report-table__cell">Action</th>

                    </tr>

                </thead>

                <tbody>

                    <tr class="report-table__row">

                        <td class="report-table__cell"></td>
                        <td class="report-table__cell"></td>
                        <td class="report-table__cell"></td>
                        <td class="report-table__cell"></td>
                        <td class="report-table__cell"></td>
                        <td class="report-table__cell"></td>
                        <td class="report-table__cell">
                            <a class="table__action" href="/RMS/public/index.php?url=report-review">View</a>
                        </td>

                    </tr>

                </tbody>

            </table>
        </div>
    
    </main>

</div>
