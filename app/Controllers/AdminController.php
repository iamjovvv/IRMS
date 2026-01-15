<?php

class AdminController
{

    function view($view, $data){
        extract($data);

        require BASE_PATH . '/app/Views/layouts/header.php';
        require BASE_PATH . '/app/Views/' . $view . '.php';
        require BASE_PATH . '/app/Views/layouts/footer.php';
    }

    public function dashboardAdmin(){
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

    
    




}



?>
