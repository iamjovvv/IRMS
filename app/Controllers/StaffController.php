<?php

require_once BASE_PATH . '/app/Core/BaseController.php';

class StaffController extends BaseController
{

    public function dashboardStaff()
    {
        $this->view('staff/dashboard-staff', [
            'page_title' => 'Staff Dashboard',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/card.css',
                'pages/page.css',
                'layouts/grid.css'
            ],
            'page_js' => [
                'sidebar.js'
            ]

        ]);

    }

    public function assessment()
    {
        $this->view('staff/assessment', [
            'page_title' => 'Assessment',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/button.css',
                'components/steps-bar.css',
                'pages/page.css',
                'components/form.css',
                'layouts/form-layout.css'
            ],

            'page_js' => [
                'sidebar.js',
                'assessment.js'
            ]
        ]);
    }


    public function escalate()
    {
        $this->view('staff/escalate', [
            'page_title' => 'Report Escalate',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/button.css',
                'pages/page.css',
                'components/form.css',
                'layouts/form-layout.css'

            ],
            'page_js' => [
                'sidebar.js'
            ]

        ]);
    }



    public function newReports()
    {
        // 🔐 (later) check staff role here

        $filters = [];

        if(!empty($_GET['category']))
            {
                $filters['category'] = $_GET['category'];
            }

            if(!empty($_GET['status']))
                {
                    $filters['status'] =$_GET['status'];
                }

        $incidentModel = new IncidentModel();
        $reports = $incidentModel->getNewReports($filters);



        $this->view('staff/new-reports', [
            'reports' => $reports,
            'page_title' => 'New Reports',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/table.css',
                'pages/page.css'
            ],
            'page_js' => [
                'sidebar.js'
            ]
        ]);
        exit;
    }



    public function reportReview()
    {
        $this->view('staff/report-review', [
            'page_title' => 'Report Review',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/button.css',
                'components/form.css',
                'components/status.css',
                'pages/page.css',
                'layouts/form-layout.css',
                'components/steps-bar.css'
            ],
            'page_js' => [
                'sidebar.js'
            ]
        ]);
        exit;
 
    }

    

    public function reporterDetails()
    {
        $this->view('staff/reporter-details', [
            'page_title' => 'Reporter Details',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/form.css',
                'layouts/form-layout.css',
                'pages/page.css',
                'components/steps-bar.css',
                'components/button.css'
            ],

            'page_js' => [
                'sidebar.js'
            ]
        ]);
    }



     public function reportsValidated(){
        $this->view('staff/reports-validated', [
            'page_title' => 'Reports Validated',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/table.css',
                'pages/page.css'
            ],
            'page_js' => [
                'sidebar.js'
            ]

        ]);
    }


    public function staffRemarks()
    {
        $this->view('staff/staff-remarks', [
            'page_title' => 'Staff Remarks',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/remarks.css',
                'pages/page.css'
            ],
                'page_js' => [
                    'sidebar.js'
                ]
        ]);
    }

    

    public function submitAssessment(){

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        $validity = $_POST['validity'] ?? null;
        $priority = $_POST['priority'] ?? null;
        $invalid_reason = trim($_POST['invalid_reason'] ?? '');

        $errors = [];

        if($validity === 'valid' && empty($priority)){
            $errors[] = 'Please provide a reason for invalidation.';
        }

        if(!empty($errors)){
            $this->view('staff/assessment', [
            'page_title' => 'Assessment',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/button.css',
                'pages/page.css',
                'components/form.css',
                'layouts/form-layout.css',
                
            ],

            'page_js' => [
                'sidebar.js',
                'assessment.js'
            ],
            'errors' => $errors,
            'old' => $_POST
        ]);
        return;
        }

        // Save assessment later (model)
        // AssessmentModel::save(...);
    }

    

   

    // public function submitIncidentDetails(){
        
    // // validate if needed
    // // save later
    //     header('Location: /RMS/public/index.php?url=staff/reporterDetails');
    // }

    // public function reviewReporterDetails(){
    //     header('Location: /RMS/public/index.php?url=staff/assessment');
    // }


    

}

?>