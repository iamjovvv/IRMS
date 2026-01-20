<?php

require_once BASE_PATH . '/app/core/BaseController.php';

require_once BASE_PATH . '/app/Models/LogInModel.php';

class PublicController extends BaseController
{

    

    public function home() {
       
       $this->view('public/home', [
            'page_title' => 'Welcome to IRS',
            'page_css' => [
                'landingpage.css',
                'topnavbar.css'
            ]
       ]);
    }

    public function login()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                session_start();

                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');

       
              if($usename === '' || $password === '')
                {
                    $errors[] = 'Please fill in all required fields';

                } else {

                    $logInModel = new LogInModel($this->pdo);
                    $user = $logInModel->authenticate($username, $password);

                    if(!$user)
                        {
                            $errors[] = 'Invalid username or password';

                        }else{

                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['role'] = $user['role'];

                            switch($user['role'])
                            {
                                case 'admin':
                                    header('Location: /RMS/app/Views/admin/dashboard-admin.php');
                                    exit;

                                case 'staff':
                                    header('Location: /RMS/app/Views/staff/dashboard-staff.php');
                                    exit;

                                case 'responder':
                                    header('Location: /RMS/app/Views/responder/responder-dashboar.php');
                                    exit;
                            }

                        }

                }
            }


        $this->view('public/login', [
            'page_title' => 'Login',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/form.css',
                'layouts/form-layout.css'
            ]
        ]);
    }

  

    // public function trackStatus()
    // {
    //     $this->view('reporter/track-status', [
    //         'page_title' => 'Track Status',
    //         'page_css' => [
    //             'topnavbar.css',
    //             'base/typography.css',
    //             'components/button.css',
    //             'components/form.css',
    //             'layouts/form-layout.css'
    //         ]
    //     ]);
           
    // }


    // public function addEvidence()
    // {
    //     $this->view('reporter/add-evidence', [
    //         'page_title' => 'Add Evidence',
    //         'page_css' => [
    //             'topnavbar.css',
    //             'base/typography.css',
    //             'components/button.css',
    //             'components/card.css',
    //             'pages/page.css'
    //         ]
    //     ]);
    // }

    // public function incidentSummary()
    // {
    //     $this->view('reporter/incident-summary', [
    //         'page_title' => 'Incident Summary',
    //         'page_css' => [
    //             'topnavbar.css',
    //             'base/typography.css',
    //             'components/card.css',
    //             'layouts/grid.css',
    //             'pages/page.css'
    //         ]
    //     ]);
    // }

    // public function reporterRemarks()
    // {
    //     $this->view('reporter/reporter-remarks', [
    //         'page_title' => 'Reporter Remarks',
    //         'page_css' => [
    //             'topnavbar.css',
    //             'base/typography.css',
    //             'components/remarks.css',
    //             'pages/page.css'

    //         ]
    //     ]);
    // }

    // public function reportStatus()
    // {
    //     $this->view('reporter/report-status', [
    //         'page_title' => 'Report Status',
    //         'page_css' => [
    //             'topnavbar.css',
    //             'base/typography.css',
    //             'components/form.css',
    //             'layouts/form-layout.css',
    //             'pages/page.css'
    //         ]
    //     ]);
    // }


    

    

    

}

?>