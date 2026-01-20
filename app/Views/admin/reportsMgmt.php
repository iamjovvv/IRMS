<?php $_SESSION['role']= 'admin'; ?>

<div class="with-sidebar">

    <?php require BASE_PATH . '../app/Views/layouts/sidebar.php' ?>
    
    <main class="page page--new-reports">

        <header class="page__header">

            <h1 class="page__title">Reports Management</h1>

        </header>


    
        <div class="page__container page__container-reports">

            <!-- <p >This is the reports page content.</p> -->

            <div class= "table__selection">

                <div class="table__search">
                    <i class="fa-solid fa-magnifying-glass  table__search-icon"></i>

                    <input 
                    type= "text",
                    class= "table__search-input",
                    placeholder= "search..."
                    >
                </div>

                <select class="table__select" >
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>

            </div>



                
                <table class="report-table">

                    <thead>

                        <tr class="report-table__row report-table__row--head">
                            <th class="report-table__cell">Report ID</th>
                            <th class="report-table__cell">Reporter</th>
                            <th class="report-table__cell">Status</th>
                            <th class="report-table__cell">Date</th>
                            <th class="report-table__cell">Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr class="report-table__row">

                            <td class="report-table__cell">001</td>
                            <td class="report-table__cell">Juan Dela Cruz</td>
                            <td class="report-table__cell">Pending</td>
                            <td class="report-table__cell">2025-01-01</td>
                            <td class="report-table__cell report-table__cell--view">View</td>
                        </tr>

                    </tbody>

                </table>
            

        </div>
    </main>
</div>