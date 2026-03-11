<?php

// require_once BASE_PATH . '/app/Middleware/Auth.php';

// require_once BASE_PATH . '/app/core/BaseController.php';

// require_once BASE_PATH . '/app/Models/AdminModel.php';

// require_once BASE_PATH . '/app/Models/ResponderModel.php';


// require_once BASE_PATH . '/app/Models/IncidentModel.php';

class AdminController extends BaseController
{

protected PDO $pdo;


public function __construct()
{
    Auth::requireRole(['admin']);
    global $pdo;
    $this->pdo = $pdo;
}



   
public function dashboardAdmin()
{
    $adminModel = new AdminModel(); 

    $totals = [
        'total_reports' => $adminModel->getTotalReports(),
        'total_served_reports' => $adminModel->getTotalServedReports(),
        'total_staff' => $adminModel->getTotalStaff(),
        'total_responders' => $adminModel->getTotalResponders(),
        'total_reporter_org' => $adminModel->getTotalReporterOrgId()
    ];

    $this->view('admin/dashboard-admin', [
        'page_title' => "Admin Dashboard",
        'totals' => $totals,
        'page_css' => [
            'topnavbar.css',
            'sidebar.css',
            'base/typography.css',
            'components/card.css',
            'layouts/grid.css',
            'pages/page.css',
            'pages/send-with.css'
        ],
        'page_js' => [
            'sidebar.js',
            'send-with.js'
        ]
    ]);
}


 public function adminStaffMgmt(){
        $this->view('admin/staffMgmt', [
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'components/table.css',
                'base/typography.css',
                'components/button.css',
                'pages/page.css'
            ],
            'page_js' => [
                'sidebar.js'
            ]
            
        ]);
    }






public function storeUser()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /RMS/public/index.php');
        exit;
    }

    $role     = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $allowedRoles = ['reporter', 'staff', 'admin', 'responder'];
    if (!in_array($role, $allowedRoles)) {
        $_SESSION['error'] = 'Invalid role selected';
        header('Location: /RMS/public/index.php?url=admin/createUser');
        exit;
    }

    if (!$username || !$password) {
        $_SESSION['error'] = 'Username and password are required';
        header('Location: /RMS/public/index.php?url=admin/createUser');
        exit;
    }

    $userModel = new UserModel();

    if ($userModel->exists($username)) {
        $_SESSION['error'] = 'Username already exists';
        header('Location: /RMS/public/index.php?url=admin/createUser');
        exit;
    }

    try {
        $this->pdo->beginTransaction();

        $userId = $userModel->create([
            'username' => $username,
            'password' => $password,
            'role'     => $role
        ]);

        // Role-based required validation
        switch ($role) {
            case 'reporter':
                if (empty($_POST['id_number']) && empty($_POST['phone'])) {
                    $_SESSION['error'] = 'Reporter requires ID Number or Phone';
                    header('Location: /RMS/public/index.php?url=admin/createUser');
                    exit;
                }
                (new ReporterModel())->createReporter([
                    'user_id'       => $userId,
                    'auth_method'   => $_POST['id_number'] ? 'org_id' : 'phone',
                    'org_id_number' => $_POST['id_number'] ?? null,
                    'phone'         => $_POST['phone'] ?? null
                ]);
                break;

            case 'staff':
                if (empty($_POST['position']) || empty($_POST['office'])) {
                    $_SESSION['error'] = 'Staff requires Position and Office';
                    $this->pdo->rollBack();
                    header('Location: /RMS/public/index.php?url=admin/createUser');
                    exit;
                }
                (new StaffModel())->create([
                    'user_id'  => $userId,
                    'staff_id' => 'STF-' . strtoupper(substr(uniqid(), -6)), // ← auto-generate
                    'position' => $_POST['position'] ?? null,
                    'office'   => $_POST['office']   ?? null
                ]);
            break;

            case 'responder':

            if (empty($_POST['organization_name'])) {
                $_SESSION['error'] = 'Responder requires an organization name';
                header('Location: /RMS/public/index.php?url=admin/createUser');
                exit;
            }

            (new ResponderModel())->create([
            'user_id' => $userId,
            'organization_name' => $_POST['organization_name'],
            'contact_email' => $_POST['contact_email'] ?? null,
            'contact_phone' => $_POST['contact_phone'] ?? null
            ]);
            break;

            case 'admin':
            
                break;
        }

        // Commit AFTER role creation
        $this->pdo->commit();

        $_SESSION['success'] = 'Account created successfully';
        header("Location: /RMS/public/index.php?url=admin/accountsMgmt&role={$role}");
        exit;

    } catch (Exception $e) {
        $this->pdo->rollBack();
        $_SESSION['error'] = 'Failed to create account';
        header('Location: /RMS/public/index.php?url=admin/createUser');
        exit;
    }
}




   


    public function adminReporterMgmt(){
    $category = $_GET['category'] ?? '';
    $status = $_GET['status'] ?? '';

    $incidentModel = new IncidentModel();
    $records = $incidentModel->getFilteredIncidents($category, $status);
        $this->view('admin/reporterMgmt', [
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'components/table.css',
                'base/typography.css',
                'components/button.css',
                'pages/page.css',
                'components/modal.css'
            ],
            'page_js' => [
                'sidebar.js'
            ]
            
        ]);
    }




   


    public function staffTable()
{
        $model = new AdminModel();
        $records = $model->getAllStaff();


        $this->view('admin/reportsMgmt', [
        'records' => $records,
        'columns' => ['ID', 'Staff ID', 'Created At'],
        'fields' => ['id', 'staff_id', 'created_at'],
        'type' => 'staff',
        'page_title' => 'All Staff'
        ]);
}



public function responderTable()
{   
    $model = new AdminModel();
    $records = $model->getAllResponders();

    $this->view('admin/reportsMgmt', [
        'records' => $records,
        'columns' => ['ID', 'Organization', 'Email', 'Phone', 'Created At'],
        'fields'  => ['id', 'organization_name', 'contact_email', 'contact_phone', 'created_at'],
        'type' => 'responder',
        'page_title' => 'All Responders'
    ]);
}



public function reporterTable()
{
       
    $model = new AdminModel();
    $records = $model->getAllReporters();

    $this->view('admin/reportsMgmt', [
        'records' => $records,
        'columns' => ['ID', 'Org ID', 'Phone', 'Created At'],
        'fields'  => ['id', 'org_id_number', 'phone', 'created_at'],
        'type' => 'reporter',
        'page_title' => 'All Reporters'
    ]);
}


  public function users()
    {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        require BASE_PATH . '/app/Views/admin/reporterMgmt.php';
    }



    public function createUser()
    {
        $this->view('admin/createUser', [
            'page_title' => 'Create Account',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/form.css',
                'layouts/form-layout.css',
                'sidebar.css',
                'pages/page.css',
                'components/attachment.css'
                ],
                'page_js' => ['createUser.js']
                ]);
    }




    public function accountsMgmt($role = 'reporter')
{
    $adminModel = new AdminModel(); 

    // Fetch accounts depending on role
   switch (strtolower($role)) {
    case 'staff':
        $accounts = $adminModel->getAllStaff();
        $columns = ['ID', 'Username', 'Status', 'Staff ID', 'Created At'];
        $fields  = ['user_id', 'username', 'status', 'staff_id', 'created_at'];
        break;

    case 'responder':
        $accounts = $adminModel->getAllResponders();
        $columns = ['ID', 'Username', 'Status', 'Organization', 'Email', 'Phone', 'Created At'];
        $fields  = ['user_id', 'username', 'status', 'organization_name', 'contact_email', 'contact_phone', 'created_at'];
        break;

    case 'reporter':
    default:
        $accounts = $adminModel->getAllReporters();
        $columns = ['ID', 'Username', 'Status', 'Auth Method', 'ID Number', 'Phone', 'Created At'];
        $fields  = ['user_id', 'username', 'status', 'auth_method', 'org_id_number', 'phone', 'created_at'];
        break;
    }

    $this->view('admin/reporterMgmt', [
        'role'       => $role,
        'accounts'   => $accounts,
        'columns'    => $columns,
        'fields'     => $fields,
        'page_title' => ucfirst($role) . ' Accounts',
        'page_css'   => [
            'topnavbar.css',
            'sidebar.css',
            'components/table.css',
            'base/typography.css',
            'components/button.css',
            'pages/page.css'
          
        ],
        'page_js' => ['sidebar.js']
    ]);
}


public function editUser()
{
    $userId = $_GET['id'] ?? null;
    if (!$userId) {
        $_SESSION['error'] = 'User ID is missing';
        header('Location: /RMS/public/index.php?url=admin/accountsMgmt');
        exit;
    }

    $adminModel = new AdminModel();
    $account = $adminModel->findUserById((int) $userId); 
    $accountRole = strtolower($account['role'] ?? '');

    if (!$account) {
        $_SESSION['error'] = 'User not found';
        header('Location: /RMS/public/index.php?url=admin/accountsMgmt');
        exit;
    }

    $role = strtolower($account['role'] ?? '');

    $this->view('admin/editUser', [
        'account'    => $account,  
        'accountRole' => $accountRole,
        'role'       => $role,
        'details'    => $account,
        'page_title' => 'Edit ' . ucfirst($role) . ' Account',
        'page_css'   => [
            'topnavbar.css', 'sidebar.css',
            'components/form.css', 'components/button.css', 'pages/page.css', 'base/typography.css'
        ],
        'page_js' => ['sidebar.js']
    ]);
}



public function updateUser()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /RMS/public/index.php');
        exit;
    }

    $userId   = (int) ($_GET['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $role     = strtolower($_POST['role'] ?? '');

    if (!$userId || !$username) {
        $_SESSION['error'] = 'Invalid data';
        header('Location: /RMS/public/index.php?url=admin/accountsMgmt');
        exit;
    }

    $adminModel = new AdminModel();

    try {
        $this->pdo->beginTransaction();

        // Build users table update
    $updateData = ['username' => $username];

    if (!empty($_POST['status'])) {
        $updateData['status'] = $_POST['status'];
    }

    if (!empty($_POST['password'])) {
        $updateData['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $adminModel->updateUser($userId, $updateData);

        // Update role-specific table
        switch ($role) {
            case 'reporter':
                (new ReporterModel())->updateByUserId($userId, [
                    'org_id_number' => $_POST['id_number'] ?? null,
                    'phone'         => $_POST['phone']     ?? null
                ]);
                break;

            case 'staff':
                (new StaffModel())->updateByUserId($userId, [
                    'staff_id' => $_POST['staff_id'] ?? null,
                    'position' => $_POST['position'] ?? null,
                    'office'   => $_POST['office']   ?? null
                ]);
                break;

            case 'responder':
                (new ResponderModel())->updateByUserId($userId, [
                    'organization_name' => $_POST['organization_name'] ?? null,
                    'contact_email'     => $_POST['contact_email']     ?? null,
                    'contact_phone'     => $_POST['contact_phone']     ?? null
                ]);
                break;

            case 'admin':
           
                break;
        }

        $this->pdo->commit();
        $_SESSION['success'] = 'Account updated successfully';
        header("Location: /RMS/public/index.php?url=admin/accountsMgmt&role={$role}");
        exit;

    } catch (Exception $e) {
        $this->pdo->rollBack();
        $_SESSION['error'] = 'Failed to update: ' . $e->getMessage();
        header("Location: /RMS/public/index.php?url=admin/editUser&id={$userId}");
        exit;
    }
}






//  public function reportsMgmt(){
//     $category = $_GET['category'] ?? '';
//     $status = $_GET['status'] ?? '';

//     $incidentModel = new IncidentModel();
//     $records = $incidentModel->getFilteredIncidents($category, $status);

//         $this->view('admin/reportsMgmt', [
//             'records' => [],
//             'columns' => [],
//             'fields' => [],
//             'type' => '',
//             'page_title' => 'Reports Management',
//             'page_css' => [
//                 'topnavbar.css',
//                 'sidebar.css',
//                 'components/table.css',
//                 'base/typography.css',
//                 'components/button.css',
//                 'pages/page.css'

//             ],
//             'page_js' => [
//                 'sidebar.js'
//             ]
//         ]);
//     }



public function totalReports()
{
    $adminModel  = new AdminModel();
    $year        = (int) ($_GET['year'] ?? date('Y'));

    $reportStats = [
        'total'     => $adminModel->getTotalReports(),
        'pending'   => $adminModel->getReportsByCategory('ongoing'),
        'resolved'  => $adminModel->getReportsByCategory('resolved'),
        'rejected'  => $adminModel->getReportsByCategory('rejected'),
        'escalated' => $adminModel->getReportsByCategory('escalated'),
    ];

    $this->view('admin/totalReports', [
        'page_title'    => 'Total Reports',
        'reportStats'   => $reportStats,
        'monthlyStats'  => $adminModel->getMonthlyReports($year), // ✅ added
        'selectedYear'  => $year,                                  // ✅ for year switcher
        'page_css'      => [
            'topnavbar.css',
            'sidebar.css',
            'pages/page.css',
            'base/typography.css'
        ],
        'page_js' => [
            'sidebar.js',
            '/assets/js/charts/totalReports.chart.js'
        ]
    ]);
}



  

    public function reportsTable()
    {
        $category = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';


        $incidentModel = new IncidentModel();
        $records = $incidentModel->getFilteredIncidents($category, $status, $dateFrom, $dateTo);

        

        $this->view('admin/reportsMgmt', [
                'records' => $records,
                'columns' => ['ID', 'Title', 'Category', 'Status', 'Date', 'Type', 'Reporter', 'Location'],
                'fields' => ['id', 'subject', 'category', 'status', 'created_at', 'incident_type', 'reporter_name', 'location'],
                'type' => 'incident',
                'page_title' => $status === 'resolved' ? 'Served Reports' : 'All Reports',
                'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'components/table.css',
                'base/typography.css',
                'components/button.css',
                'pages/page.css'
            ],
                'page_js' => ['sidebar.js']
            ]);
    }




  
  
public function viewIncident()
{
    Auth::requireRole(['admin']);

    $trackingCode = $_GET['code'] ?? null;

    if (!$trackingCode) {
        $_SESSION['error'] = 'Missing tracking code.';
        header("Location: /RMS/public/index.php?url=admin/reportsTable");
        exit;
    }

    $incidentModel = new IncidentModel();
    $mediaModel    = new IncidentMediaModel();

    $incident = $incidentModel->findByTrackingCode($trackingCode);

    if (!$incident) {
        $_SESSION['error'] = 'Incident not found.';
        header("Location: /RMS/public/index.php?url=admin/reportsTable");
        exit;
    }

    $media           = $mediaModel->getByIncidentId($incident['id']);
    $actions         = $incidentModel->getIncidentActions($incident['id']);
    $response        = !empty($actions) ? end($actions) : null;  // ← was missing

    $reporter = null;
    if (!empty($incident['reporter_id'])) {
        $reporterModel = new ReporterModel();
        $reporter = $reporterModel->findById((int) $incident['reporter_id']);
    }

    $this->view('layouts/final-report', [
        'incident'   => $incident,
        'media'      => $media,
        'reporter'   => $reporter,
        'response'   => $response,
        'assessment' => $incidentModel->findById($incident['id']),
        'page_title' => 'View Incident Report',
        'page_css'   => [
            'topnavbar.css', 'sidebar.css',
            'base/typography.css', 'components/button.css'
        ],
        'page_js' => ['sidebar.js']
    ]);
}

public function finalReport()
{
    Auth::requireRole(['admin']);

    $code = $_GET['code'] ?? null;
    if (!$code) die('Invalid report code.');

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($code);

    if (!$incident) die('Report not found.');

    if ($incident['status'] !== 'resolved') {
        die('Final report is only available for resolved incidents.');
    }

    $incidentFull    = $incidentModel->findById($incident['id']);
    $actions         = $incidentModel->getIncidentActions($incident['id']);
    $response        = !empty($actions) ? end($actions) : null;
    $involvedParties = !empty($response)
        ? $incidentModel->getInvolvedParties($response['id'])
        : [];

    $incidentMediaModel = new IncidentMediaModel();
    $media = $incidentMediaModel->getByIncidentId($incident['id']);

    $reporterModel = new ReporterModel();
    $reporter = null;
    if (!empty($incident['reporter_id'])) {
        $reporter = $reporterModel->findById((int) $incident['reporter_id']);
    }

    // ✅ Fetch escalation
    $escalation = null;
   $stmt = $this->pdo->prepare("
    SELECT e.responder AS responder_name, u.role AS responder_role,
           e.description, e.escalated_at   -- ✅ was e.created_at
    FROM escalations e
    LEFT JOIN users u ON u.id = e.responder_id
    WHERE e.incident_id = :id
    LIMIT 1
");
    $stmt->execute([':id' => $incident['id']]);
    $escalation = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    $this->view('layouts/final-report', [
        'incident'        => $incidentFull,  // ✅ was $incident (non-full)
        'assessment'      => $incidentFull,
        'response'        => $response,
        'reporter'        => $reporter,
        'escalation'      => $escalation,    // ✅ was missing entirely
        'involvedParties' => $involvedParties, // ✅ was missing entirely
        'media'           => $media,
        'page_title'      => 'Final Report',
        'page_css'        => [
            'topnavbar.css',
            'base/typography.css',
            'sidebar.css',
            'components/button.css'
        ]
    ]);
}


}



?>
