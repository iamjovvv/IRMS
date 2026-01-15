<?php
session_start();

define('BASE_PATH', dirname(__DIR__));

// if (session_status() === PHP_SESSION_NONE){
//         session_start();
//     }
require BASE_PATH . '/app/Controllers/PublicController.php';

require BASE_PATH . '/app/Controllers/StaffController.php';

require BASE_PATH . '/app/Controllers/AdminController.php';

require BASE_PATH . '/app/Controllers/ResponderController.php';


$url = $_GET['url'] ?? 'home';

switch($url) 
{

    case 'home': 
        (new PublicController)->home();
        break;

    case 'login':
        (new PublicController)->login();
        break;

    case 'report':
        (new PublicController)->reportForm();
        break;

    case 'track':
        (new PublicController)->trackStatus();
        break;

    case 'add-evidence':
        (new PublicController)->addEvidence();
        break;

    case 'incident-summary':
        (new PublicController)->incidentSummary();
        break;

    case 'reporter-remarks':
        (new PublicController)->reporterRemarks();
        break;

    case 'report-status':
        (new PublicController)->reportStatus();
        break;

    case 'send-with':
        (new PublicController)->sendWith();
        break;

    case 'dashboard-staff':
        (new StaffController)->dashboardStaff();
        break;

    case 'staff/reportReview':
        (new StaffController)->reportReview();
        break;

    case 'staff/newReports':
        (new StaffController)->newReports();
        break;
    case 'staff/reporterDetails':
        (new StaffController)->reporterDetails();
        break;
    case 'staff-remarks':
        (new StaffController)->staffRemarks();
        break;
    case 'staff/assessment':
        (new StaffController)->assessment();
        break;
    case 'assessment/submit':
        (new StaffController)->submitAssessment();
        break;
    case 'escalate':
        (new StaffController)->escalate();
        break;
    case 'reports/validated':
        (new StaffController)->reportsValidated();
        break;




    case 'admin/dashboard':
        (new AdminController)->dashboardAdmin();
        break;
    case 'admin/staffMgmt':
        (new AdminController)->adminStaffMgmt();
        break;
    case 'admin/reportsMgmt':
        (new AdminController)->reportsMgmt();
        break;

    case 'admin/totalReports':
        (new AdminController)->totalReports();
        break;


    case 'responder/dashboard':
        (new ResponderController)->responderDashboard();
        break;
    case 'responder/actionForm':
        (new ResponderController)->actionForm();
        break;

    default:
        http_response_code(404);
        echo '404 - Page not found';
}



?>
