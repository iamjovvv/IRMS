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

require BASE_PATH . '/app/Controllers/IncidentController.php';

require BASE_PATH . '/app/Controllers/ReporterController.php';


$url = $_GET['url'] ?? 'home';

switch($url) 
{

    case 'home': 
        (new PublicController)->home();
        break;

    case 'login':
        (new PublicController)->login();
        break;



    case 'reporter/track':
        (new IncidentController)->track();
        break;

    // case 'reporter/addEvidence':
    //     (new PublicController)->addEvidence();
    //     break;

    // case 'reporter/incidentSummary':
    //     (new PublicController)->incidentSummary();
    //     break;

    case 'incident/track':
    (new IncidentController)->track();
    break;


    case 'incident/summary':
    (new ReporterController)->summary();
    break;

    case 'incident/summaryReport':
        (new IncidentController)->summary();
        break;



    // case 'reporter/remarks':
    //     (new PublicController)->reporterRemarks();
    //     break;

    // case 'reporter/reportStatus':
    //     (new PublicController)->reportStatus();
    //     break;



    case 'reporter/sendWith':
        (new ReporterController)->sendWith();
        break;




    // case 'reporter/reportDetails':
    //     (new ReporterController)->summary();
    //     break;
    
    case 'reporter/status':
        (new ReporterController)->status();

    case 'reporter/evidence':
        (new ReporterController)->addEvidence();
        break;

    case 'reporter/remarks':
        (new ReporterController)->remarks();
        break;





    case 'dashboard/staff':
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
    case 'staff/remarks':
        (new StaffController)->staffRemarks();
        break;
    case 'staff/assessment':
        (new StaffController)->assessment();
        break;
    case 'staff/assessment/submit':
        (new StaffController)->submitAssessment();
        break;
    case 'staff/reportEscalate':
        (new StaffController)->escalate();
        break;
    case 'staff/reportValidated':
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
        
    case 'admin/reporterMgmt':
        (new AdminController)->adminReporterMgmt();
        break;

    case 'admin/users':
        (new AdminController)->users();
        break;
    case 'admin/users/store':
        (new AdminController)->storeUser();
        break;


    case 'responder/dashboard':
        (new ResponderController)->responderDashboard();
        break;
    case 'responder/actionForm':
        (new ResponderController)->actionForm();
        break;


    // case 'incident/submit':
    //     (new IncidentController)->submit();
    //     break;
    // case 'track':
    //     (new IncidentController)->track();
    //     break;


    

    case 'reporter/saveIncident':
        (new ReporterController)->saveIncident();
        break;
    case 'reporter/submitReport':
        (new ReporterController)->submitReport();
        break;

    
    case 'reporter/confirmation':
        (new ReporterController)->confirmation();
        break;
    



      case 'reporter/reportForm':
        (new ReporterController)->reportForm();
        break;
        

    default:
        http_response_code(404);
        echo '404 - Page not found';
}



?>
