<?php

// require_once BASE_PATH . '/app/core/BaseController.php';
// require_once BASE_PATH . '/app/Models/IncidentModel.php';
// require_once BASE_PATH . '/app/Models/ResponderModel.php';
// require_once BASE_PATH . '/app/Models/IncidentMediaModel.php';


class ResponderController extends BaseController
{
    protected PDO $pdo;
    
    protected ResponderModel $responderModel;

    public function __construct()
    {
        
        global $pdo;
        $this->pdo = $pdo;

        $this->responderModel = new ResponderModel($this->pdo);
    }

   
    public function responderDashboard()
    {
        $this->view('responder/responder-dashboard', [
            'page_title' => 'External Responder Dashboard',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'pages/page.css',
                'components/card.css',
                'layouts/grid.css',
                'pages/send-with.css'
            ],
            'page_js' => [
                'sidebar.js',
                'send-with.js'
            ]
        ]);
    }

   

public function actionForm()
{
    Auth::requireRole(['responder']);

    $trackingCode = $_GET['code'] ?? null;
    if (!$trackingCode) die('Error: No incident selected');

    $incidentModel = new IncidentModel($this->pdo);
    $mediaModel    = new IncidentMediaModel($this->pdo);

    $incident = $incidentModel->findByTrackingCode($trackingCode);
    if (!$incident) die('Incident not found');

    $responderId = $_SESSION['user']['id'];

    if (!$incidentModel->isIncidentAssignedToResponder((int)$incident['id'], (int)$responderId)) {
        http_response_code(403);
        exit('Unauthorized access');
    }

    // Get full incident with priority joined
    $incidentFull = $incidentModel->findById($incident['id']);

    $canTakeAction  = IncidentPolicy::responderCanTakeAction($incident, $_SESSION['user']);
    $canViewActions = IncidentPolicy::canViewActions($incident, $_SESSION['user']);

    $showActionFormFilled = in_array(strtolower($incident['status']), ['resolved']);

    $previousActions = $canViewActions
        ? $incidentModel->getIncidentActions($incident['id'])
        : [];

    $media = $mediaModel->getByIncidentId($incident['id']);

    $this->view('responder/action-form', [
        'incident'             => $incidentFull,  // ✅ has priority
        'media'                => $media,
        'mode'                 => 'responder',
        'canTakeAction'        => $canTakeAction,
        'showActionFormFilled' => $showActionFormFilled,
        'previousActions'      => $previousActions,
        'page_title'           => 'Take Action',
        'tracking_code'        => $trackingCode,
        'page_css'             => [
            'topnavbar.css',
            'sidebar.css',
            'components/form.css',
            'pages/page.css',
            'components/attachment.css',
            'base/typography.css',
            'components/button.css'
        ],
        'page_js' => ['sidebar.js', 'attachment.js']
    ]);
}


public function submitAction()
{
    Auth::requireRole(['responder']);

    $responderId  = $_SESSION['user']['id'];
    $incidentId   = $_POST['incident_id']   ?? null;
    $trackingCode = $_POST['tracking_code'] ?? null;
    $status       = $_POST['status_update'] ?? null;

    if (!$incidentId || !$status || !$trackingCode) {
        http_response_code(400);
        die('Invalid submission');
    }

    $incidentModel = new IncidentModel($this->pdo);

    if (!$incidentModel->isIncidentAssignedToResponder((int)$incidentId, (int)$responderId)) {
        http_response_code(403);
        exit('Unauthorized action');
    }

    // Build involved parties
    $involvedParties = [];
    $owners       = $_POST['owner_of_property'] ?? [];
    $areas        = $_POST['affected_area']     ?? [];
    $descriptions = $_POST['party_description'] ?? [];

    foreach ($owners as $i => $owner) {
        if (trim($owner) === '' && trim($areas[$i] ?? '') === '') continue;
        $involvedParties[] = [
            'owner'       => trim($owner),
            'area'        => trim($areas[$i] ?? ''),
            'description' => trim($descriptions[$i] ?? ''),
        ];
    }

    $incidentModel->addAction([
        'incident_id'             => $incidentId,
        'action_taken'            => trim($_POST['action_taken'] ?? ''),
        'resolution_date'         => $_POST['resolution_date'] ?? date('Y-m-d'),
        'resolution_time'         => $_POST['resolution_time'] ?? date('H:i:s'),
        'status'                  => $status,
        'acted_by'                => $responderId,
        'investigation_findings'  => trim($_POST['investigation_findings']  ?? ''),
        'resolution_disposition'  => trim($_POST['resolution_disposition']  ?? ''),
        'involved_parties'        => $involvedParties,
        'report_officer_first'    => trim($_POST['report_officer_first']    ?? ''),
        'report_officer_middle'   => trim($_POST['report_officer_middle']   ?? ''),
        'report_officer_last'     => trim($_POST['report_officer_last']     ?? ''),
        'report_officer_position' => trim($_POST['report_officer_position'] ?? ''),
        'signatory_first'         => trim($_POST['signatory_first']         ?? ''),
        'signatory_middle'        => trim($_POST['signatory_middle']        ?? ''),
        'signatory_last'          => trim($_POST['signatory_last']          ?? ''),
        'signatory_position'      => trim($_POST['signatory_position']      ?? ''),
    ]);

    $incidentModel->updateStatusByTrackingCode($trackingCode, $status);

    header('Location: /RMS/public/index.php?url=responder/assignedIncidents');
    exit;
}



    public function assignedIncidents()
    {
        Auth::requireRole(['responder']);

        $responderId = $_SESSION['user']['id'] ?? null;
        if (!$responderId) {
            die('Error: Responder is not logged in');
        }

        $status = $_GET['status'] ?? null;

        $incidentModel = new IncidentModel($this->pdo);
        $reports = $incidentModel->getAssignedIncidents($responderId, $status);

        $this->view('staff/new-reports', [
            'reports' => $reports,
            'page_title' => 'My Assigned Incidents',
            'role' => 'responder',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/table.css',
                'pages/page.css'
            ],
            'page_js' => ['sidebar.js']
        ]);
    }

   

    public function updateIncidentStatus()
    {
        Auth::requireRole(['responder']);

        $responderId = $_SESSION['user']['id'] ?? null;
        if (!$responderId) {
            die('Error: Responder is not logged in');
        }

        $trackingCode = $_POST['tracking_code'] ?? null;
        $newStatus = $_POST['status'] ?? null;

        if (!$trackingCode || !$newStatus) {
            http_response_code(400);
            die('Invalid request');
        }

        $incidentModel = new IncidentModel($this->pdo);

        
        if (!$incidentModel->isIncidentAssignedToResponder($trackingCode, $responderId)) {
            http_response_code(403);
            die('Unauthorized action');
        }

        $incidentModel->updateStatusByTrackingCode($trackingCode, $newStatus);

        header('Location: /RMS/public/index.php?url=responder/assignedIncidents');
        exit;
    }










private function renderTable(array $reports, string $title)
{
    $this->view('staff/new-reports', [
    'reports' => $reports,
    'page_title' => $title,
    'viewer_role' => 'responder',
    'page_css' => [
    'topnavbar.css',
    'sidebar.css',
    'components/table.css',
    'pages/page.css'
    ],
    'page_js' => ['sidebar.js']
    ]);
}
        




public function viewAssigned()
{
    Auth::requireRole(['responder']);

    

    $trackingCode = $_GET['code'] ?? null;
    if (!$trackingCode) {
        die('Error: No incident selected');
    }

    $responderId = $_SESSION['user']['id'];

    $incidentModel = new IncidentModel($this->pdo);
    $mediaModel    = new IncidentMediaModel($this->pdo);

    $incident = $incidentModel->getByTrackingCode($trackingCode);
    if (!$incident) {
        die('Incident not found');
    }

    // ✅ Only allow if this responder is assigned
    if (!$incidentModel->isIncidentAssignedToResponder((int)$incident['id'], $responderId)) {
        http_response_code(403);
        exit('Unauthorized access to this incident');
    }

    $media = $mediaModel->getByIncidentId($incident['id']);

    // Load the **report-form** but in read-only mode
    $this->view('reporter/report-form', [
        'page_title'   => 'Assigned Incident',
        'incident'     => $incident,
        'media'        => $media,
        'mode'         => 'view', // important: form fields will be readonly
        'currentStep'  => 1,
        'steps'        => [],
        'page_css'     => [
            'topnavbar.css',
            'sidebar.css',
            'base/typography.css',
            'components/button.css',
            'pages/page.css',
            'components/form.css',
            'layouts/form-layout.css',
            'components/attachment.css'
        ],
        'page_js'      => ['sidebar.js', 'attachment.js']
    ]);
}


public function actionSummary()
{
    Auth::requireRole(['responder']);

    $code = $_GET['code'] ?? '';
    if ($code === '') {
        die('Invalid tracking code.');
    }

    $incidentModel = new IncidentModel($this->pdo);
    $incident = $incidentModel->findByTrackingCode($code);

    if (!$incident) {
        die('Incident not found.');
    }

    $responderId = $_SESSION['user']['id'];

    // Ensure this responder is assigned to the incident
    if (!$incidentModel->isIncidentAssignedToResponder((int)$incident['id'], (int)$responderId)) {
        http_response_code(403);
        exit('Unauthorized access to this incident');
    }

    // Fetch associated media
    $mediaModel = new IncidentMediaModel($this->pdo);
    $media = $mediaModel->getByIncidentId($incident['id']);


    $userRole = $_SESSION['user']['role'] ?? null;
    $userId   = $_SESSION['user']['id'] ?? null;

    $canTakeAction = false;

    if (($incident['status'] ?? '') === 'validated') {
        if ($userRole === 'staff') {
            //staff can always take action on validated reports
            $canTakeAction = true;
        }

        if ($userRole === 'responder') {
            //responder only if assigned
            $canTakeAction = $incidentModel->isIncidentAssignedToResponder(
                (int)$incident['id'],
                (int)$userId
            );
        }
}


    

    // Load the same report form view in readonly mode
    $this->view('reporter/report-form', [
        'mode'       => 'view',      
        'incident'   => $incident,   
        'media'      => $media,     
        'page_title' => 'Incident Details',
        'page_css'  => [
            'topnavbar.css',
            'base/typography.css',
            'components/button.css',
            'components/form.css',
            'layouts/form-layout.css',
            'pages/page.css',
            'components/attachment.css'
        ],
        'page_js' => ['sidebar.js', 'attachment.js']
    ]);
}






    // public function assigned()
    // {
    //     $responderId = $_SESSION['responder_id'];


    //     $reports = $this->responderModel
    //     ->getAssignedIncidents($responderId);


    //     $this->renderTable($reports, 'Assigned Incidents');
    //     }


    //     public function ongoing()
    //     {
    //     $responderId = $_SESSION['responder_id'];


    //     $reports = $this->responderModel
    //     ->getAssignedIncidents($responderId, 'ongoing');


    //     $this->renderTable($reports, 'Ongoing Incidents');
    //     }


    //     public function resolved()
    //     {
    //     $responderId = $_SESSION['responder_id'];


    //     $reports = $this->responderModel
    //     ->getAssignedIncidents($responderId, 'resolved');


    //     $this->renderTable($reports, 'Resolved Incidents');
    //     }


    //     /**
    //     * Shared table renderer (REUSES new-reports.php)
    //     */
    //     private function renderTable(array $reports, string $title)
    //     {
    //     $this->view('staff/new-reports', [
    //     'reports' => $reports,
    //     'page_title' => $title,
    //     'viewer_role' => 'responder', // IMPORTANT
    //     'page_css' => [
    //     'topnavbar.css',
    //     'sidebar.css',
    //     'components/table.css',
    //     'pages/page.css'
    //     ],
    //     'page_js' => [
    //     'sidebar.js'
    //     ]
    //     ]);
    // }


    // public function updateIncidentStatus()
    // {
    //     $responderId = $_SESSION['user_id'];
    //     $trackingCode = $_POST['tracking_code'];
    //     $newStatus = $_POST['status'];

    //     $incidentModel = new IncidentModel($this->pdo);

    //     // Verify assignment
    //     if (!$incidentModel->isIncidentAssignedToResponder($trackingCode, $responderId)) {
    //         http_response_code(403);
    //         die('Unauthorized action');
    //     }

    //     $incidentModel->updateStatusByTrackingCode($trackingCode, $newStatus);

    //     header('Location: /RMS/public/index.php?url=responder/assignedIncidents');
    //     exit;
    // }
    



}

?>