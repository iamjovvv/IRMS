<?php

require_once BASE_PATH . '/app/Middleware/AdminMiddleware.php';

require_once BASE_PATH . '/app/Core/BaseController.php';

class AdminController extends BaseController
{


    public function dashboardAdmin(){
        requireAdmin();
        $this->view('admin/dashboard-admin', [
            'page_title' => "Admin Dashboard",
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/card.css',
                'layouts/grid.css',
                'pages/page.css'
            ],
            'page_js' => [
                'sidebar.js'
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


    public function adminReporterMgmt(){
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




    public function reportsMgmt(){
        $this->view('admin/reportsMgmt', [
            'page_title' => 'Reports Management',
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

    public function totalReports(){
        $this->view('admin/totalReports', [
            'page_title' => 'Total Reports',
            'page_css'   => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'pages/page.css'

            ],
            'page_js' => [
                'sidebar.js'
            ]
        ]);
    }

    public function users()
    {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        require BASE_PATH . '/app/Views/admin/reporterMgmt.php';
    }

  


   public function storeUser()
{
    // 1️⃣ Allow POST only
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /RMS/public/index.php');
        exit;
    }

    // 2️⃣ Get & sanitize basic inputs
    $role     = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // 3️⃣ Validate role
    $allowedRoles = ['reporter', 'staff', 'admin', 'responder'];
    if (!in_array($role, $allowedRoles)) {
        die('Invalid role');
    }

    // 4️⃣ Validate required fields
    if (!$username || !$password) {
        die('Missing required fields');
    }

    // 5️⃣ Create USER account
    $userModel = new UserModel();

    // Check duplicate username
    if ($userModel->exists($username)) {
        die('Username already exists');
    }

    $userId = $userModel->create([
        'username' => $username,
        'password' => $password,
        'role'     => $role
    ]);

    // 6️⃣ Role-specific profile creation

    // REPORTER (students / staff / external)
    if ($role === 'reporter') {

        $reporterModel = new ReporterModel();

        $reporterModel->createReporter([
            'user_id'   => $userId,
            'id_number' => $_POST['id_number'] ?? null, // org ID (optional)
            'email'     => $_POST['email'] ?? null,     // external
            'phone'     => $_POST['phone'] ?? null      // external
        ]);
    }

    // STAFF
    if ($role === 'staff') {

        $staffModel = new StaffModel();

        $staffModel->create([
            'user_id'  => $userId,
            'position' => $_POST['position'] ?? null,
            'office'   => $_POST['office'] ?? null
        ]);
    }

    // RESPONDER (example placeholder)
    if ($role === 'responder') {

        $responderModel = new ResponderModel();

        $responderModel->create([
            'user_id' => $userId,
            'agency'  => $_POST['agency'] ?? null
        ]);
    }

    // 7️⃣ Redirect back to admin page
    header('Location: /RMS/public/index.php?url=admin/reporterMgmt');
    exit;
}


    public function login(){

        //  $this->view('admin/login', [
        //     'page_title' => 'Login',
        //     'page_css' => [
        //         'topnavbar.css',
        //         'base/typography.css',
        //         'components/button.css',
        //         'components/form.css',
        //         'layouts/form-layout.css'
        //     ]
        // ]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
            require BASE_PATH . '/app/Views/auth/login.php';
            return;
        }
        $userModel = new UserModel();
        $user = $userModel->authenticate(
            $_POST['username'],
            $_POST['password']
        );

        if (!user) {
            $error = 'Invalid credentials';
            require BASE_PATH . '/app/Views/auth/login.php';
            return;
        }

         session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];

        // Redirect by role
        switch ($user['role']) {
            case 'admin':
                header('Location: /RMS/public/index.php?url=admin/dashboard');
                break;
            case 'staff':
                header('Location: /RMS/public/index.php?url=staff/dashboard');
                break;
            case 'responder':
                header('Location: /RMS/public/index.php?url=responder/dashboard');
                break;
            default:
                header('Location: /RMS/public/index.php');
        }
        exit;
        
    }



    



      // public function createUser()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    //         header('Location: /RMS/public/index.php?url=admin/users');
    //         exit;
    //     }
    //     $data = [
    //         'username' => trim($_POST['username']),
    //         'password' => $_POST['password']

    //     ];

    //     $userModel = new UserModel();
    //     $userModel->createUser($data);

    //     header('Location: /RMS/public/index.php?url=admin/users');
    //     exit;
    // }



    // public function createReporter()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    //     $userModel = new UserModel();
    //     $userId = $userModel->createUser([
    //         'username' => $_POST['username'],
    //         'password' => $_POST['password'],
    //         'role'     => 'reporter'
    //     ]);

    //     $reporterModel = new ReporterModel();
    //     $reporterModel->create([
    //         'user_id'    => $userId,
    //         'first_name' => $_POST['first_name'],
    //         'last_name'  => $_POST['last_name'],
    //         'id_number'  => $_POST['id_number'],
    //         'birthday'   => $_POST['birthday'],
    //         'department' => $_POST['department']
    //     ]);
    // }

    // public function createStaff()
    // {
    //     $userModel = new UserModel();
    //     $userId = $userModel->createUser([
    //         'username' => $_POST['username'],
    //         'password' => $_POST['password'],
    //         'role'     => 'staff'
    //     ]);

    //     $staffModel = new StaffModel();
    //     $staffModel->create([
    //         'user_id' => $userId,
    //         'position'=> $_POST['position'],
    //         'office'  => $_POST['office'],
    //         'department' => $_POST['department']
    //     ]);
    // }



    }



?>
