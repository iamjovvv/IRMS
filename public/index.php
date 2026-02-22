<?php


define('BASE_PATH', dirname(__DIR__));


if (session_status() === PHP_SESSION_NONE) {
session_start();
}

$userRole = $_SESSION['user']['role'] ?? null;


require_once BASE_PATH . '/app/Middleware/Auth.php';


require_once BASE_PATH . '/app/core/BaseController.php';

require_once BASE_PATH . '/app/core/BaseModel.php';

require_once BASE_PATH . '/app/Models/IncidentModel.php';

require_once BASE_PATH . '/app/Models/LogInModel.php';

require_once BASE_PATH . '/app/Models/AdminModel.php';

require_once BASE_PATH . '/app/Models/ReporterModel.php';

require_once BASE_PATH . '/app/Models/UserModel.php';

require_once BASE_PATH . '/app/Models/IncidentMediaModel.php';

require_once BASE_PATH . '/app/Models/ResponderModel.php';

require_once BASE_PATH . '/app/core/IncidentPolicy.php';






require_once BASE_PATH . '/app/Controllers/PublicController.php';

require_once BASE_PATH . '/app/Controllers/StaffController.php';

require_once BASE_PATH . '/app/Controllers/AdminController.php';

require_once BASE_PATH . '/app/Controllers/ResponderController.php';

require_once BASE_PATH . '/app/Controllers/IncidentController.php';

require_once BASE_PATH . '/app/Controllers/EscalationController.php';

require_once BASE_PATH . '/app/Controllers/ReporterController.php';





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




    // case 'reporter/reportDetails':
    //     (new ReporterController)->summary();
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

    case 'reporter/reportFormGet':
        (new ReporterController)->reportFormGet();
        break;

     case 'reporter/sendWith':
        (new ReporterController)->sendWith();
        break;
    
    case 'reporter/status':
        (new ReporterController)->status();
        break;

    case 'reporter/evidence':
        (new ReporterController)->addEvidence();
        break;

    case 'reporter/remarks':
        (new ReporterController)->remarks();
        break;

    







    case 'staff/dashboard':
        (new StaffController)->dashboardStaff();
        break;

    // case 'staff/reportReview':
    //     (new StaffController)->reportReview();
    //     break;

    case 'staff/newReports':
        (new StaffController)->newReports();
        break;
        
    case 'staff/reporterDetails':
        (new StaffController)->reporterDetails();
        break;  
    case 'staff/remarks':
        (new StaffController)->staffRemarks();
        break;


    

    case 'staff/reviewIncident':
        (new StaffController)->reviewIncident();
        break;

    case 'staff/submitAssessment':
        (new StaffController)->submitAssessment();
        break;





    case 'staff/escalate':
        (new StaffController)->escalate();
        break;

    // case 'staff/submitEscalation':
    //     (new StaffController)->submitEscalation();
    //     break;
    



    // STAFF
// case 'staff/escalate':
//     (new StaffController)->escalate();
//     break;

case 'staff/submitEscalation':
    (new StaffController)->submitEscalation();
    break;

// case 'staff/escalateConfirm':
//     (new StaffController)->escalateConfirm();
//     break;

case 'staff/reportsEscalated':
    (new StaffController)->reportsEscalated();
    break;
        
case 'staff/reportsValidated':
    (new StaffController)->reportsValidated();
    break;

case 'staff/reportsInvalidated':
    (new StaffController)->reportsInvalidated();
    break;

case 'staff/reportsEscalated':
    (new StaffController)->reportsEscalated();
    break;
case 'staff/reportsOngoing':
    (new StaffController)->reportsOngoing();
    break;
case 'staff/reportsResolved':
    (new StaffController)->reportsResolved();
    break;
case 'staff/actionForm':
    (new StaffController)->actionForm();
break;

case 'staff/submitExternalAction':
    (new StaffController)->submitExternalAction();
    break;
    
case 'staff/responderAccts':
    (new StaffController)->responderAccts();
break;

case 'staff/finalReport':
    (new StaffController)->finalReport();
    break;
case 'staff/submitAction':
    (new StaffController)->submitAction();
    break;







    // REPORTER
case 'reporter/escalate':
    (new ReporterController)->escalate();
    break;

case 'reporter/submitEscalation':
    (new ReporterController)->submitEscalation();
    break;

case 'reporter/escalateConfirm':
    (new ReporterController)->escalateConfirm();
    break;


    // case 'escalation/submit':
    //     (new EscalationController)->submit();
    //     break;

    // case 'escalation/autoEscalate':
    //     (new EscalationController)->autoEscalate();
    //     break;

    case 'escalation/form':
        (new EscalationController)->form();
        break;

    // case 'staff/submitEscalation':
    //     (new StaffController)->submitEscalation();
    //     break;






    // case 'reporter/submitEscalation':
    //     (new ReporterController)->submitEscalation();
    //     break;

    // case 'reporter/escalate':
    //    (new ReporterController)->escalate();
    //    break; 



    case 'admin/dashboard':
        (new AdminController)->dashboardAdmin();
        break;
    case 'admin/staffMgmt':
        (new AdminController)->adminStaffMgmt();
        break;
    // case 'admin/reportsMgmt':
    //     (new AdminController)->reportsMgmt();
    //     break;

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

    case 'admin/reportsTable':
    (new AdminController($pdo))->reportsTable();
    break;

    case 'admin/staff':
        (new AdminController($pdo))->staffTable();
        break;

    case 'admin/responders':
        (new AdminController($pdo))->responderTable();
        break;

    case 'admin/reporters':
        (new AdminController($pdo))->reporterTable();
        break;

    case 'admin/createUser':
    (new AdminController())->createUser();
    break;

    case 'admin/storeUser':
    (new AdminController())->storeUser();
    break;


    case 'admin/accountsMgmt':
    $role = $_GET['role'] ?? 'reporter';
    (new AdminController($pdo))->accountsMgmt($role);
    break;

    case 'admin/editUser':
        (new AdminController)->editUser();
        break;

    case 'admin/updateUser':
        (new AdminController)->updateUser();
        break;

    case 'admin/viewIncident':
        (new AdminController)->viewIncident();
        break;

    case 'admin/finalReport':
        (new AdminController)->finalReport();
        break;







    case 'responder/dashboard':
        (new ResponderController)->responderDashboard();
        break;
    case 'responder/actionForm':
        (new ResponderController)->actionForm();
        break;
    case 'responder/assignedIncidents':
        (new ResponderController($pdo))->assignedIncidents();
    break;

    case 'responder/viewAssigned':
        (new ResponderController)->viewAssigned();
        break;

    case 'responder/report-form':
        (new ResponderController)->actionSummary();
        break;

    case 'responder/submitAction':
        (new ResponderController)->submitAction();
        break;

    case 'logout':
        (new PublicController)->logout();
        break;


    // case 'incident/submit':
    //     (new IncidentController)->submit();
    //     break;
    // case 'track':
    //     (new IncidentController)->track();
    //     break;

    

    
        

    // case 'responder/viewAssignedncident':
    //     (new ResponderController)->viewAssignedIncident();
    //     break;

    default:
        http_response_code(404);
        echo '404 - Page not found';
}







ob_end_flush();


?>
