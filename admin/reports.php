<?php
define('APP_STARTED', true);
session_start ();
$_SESSION['role'] = 'admin';

$page_css = [
    'topnavbar.css',
    'reports.css'

];

require '../app/Views/layouts/header.php';
?>


 <h2 class="reports__title">Reports</h2>
<main class="reports">
   
<section class= "reports__searchbar">
    <div class="reports__search">
        <i class="fa-solid fa-magnifying-glass  reports__search-icon"></i>

        <input 
        type= "text",
        class= "reports__search-input",
        placeholder= "search..."
        >
    </div>

    <select class="reports__filter" >
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
    </select>

</section>


    <section class="reports__content">
        <p>This is the reports page content.</p>

        
        <table class="reports__table">
            <thead>
                <tr>
                    <th class= "reports__tableheader">Report ID</th>
                    <th class= "reports__tableheader">Reporter</th>
                    <th class= "reports__tableheader">Status</th>
                    <th class= "reports__tableheader">Date</th>
                    <th class= "reports__tableheader " >Action</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class= "reports__tabledata">001</td>
                    <td class= "reports__tabledata">Juan Dela Cruz</td>
                    <td class="reports__tabledata reports__tabledata--pending">Pending</td>
                    <td class= "reports__tabledata">2025-01-01</td>
                    <td class= "reports__tabledata reports__tabledata--view">View</td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<?php require '../app/Views/layouts/footer.php'; ?>
