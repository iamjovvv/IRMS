<?php

// require_once BASE_PATH . '/app/core/BaseController.php';

// require_once BASE_PATH . '/app/core/BaseModel.php';

// require_once BASE_PATH . '/app/Models/LogInModel.php';


class PublicController extends BaseController
{


    public function home() 
    {
       
       $this->view('public/home', [
            'page_title' => 'Welcome to IRS',
            'page_css' => [
                'home.css',
                'topnavbar.css'
            ]
       ]);
    }



    

  public function login()
{
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if($username === '' || $password === '') {
            $errors[] = 'Please fill in all required fields';
        } else {
            $logInModel = new LogInModel();
            $user = $logInModel->authenticate($username, $password);

            if ($user) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'role'     => $user['role'],
                ];

                $_SESSION['success'] = 'Login successful! Redirecting...';



                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header('Location: /RMS/public/index.php?url=admin/dashboard');
                        exit;
                    case 'staff':
                        header('Location: /RMS/public/index.php?url=staff/dashboard');
                        exit;
                    case 'responder':
                        header('Location: /RMS/public/index.php?url=responder/dashboard');
                        exit;
                }
            } else {
                $errors[] = 'Invalid username or password';
            }
        }

        // Save errors in session for the next page load
        if (!empty($errors)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['errors'] = $errors;

            // Redirect back to login page so toast can read session
            header('Location: /RMS/public/index.php?url=login');
            exit;
        }
    }

    // GET request: read errors from session
    if (session_status() === PHP_SESSION_NONE) session_start();
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    $this->view('public/login', [
        'page_title' => 'Login',
        'page_css' => [
            'topnavbar.css',
            'base/typography.css',
            'components/button.css',
            'components/form.css',
            'layouts/form-layout.css',
            'pages/send-with.css'
        ],
        'errors' => $errors,
        'page_js' => [
            'send-with.js'
        ]
    ]);
}



    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Clear all session data
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header('Location: /RMS/public/index.php?url=login');
        exit;
    }


  
    

    

    

}

?>