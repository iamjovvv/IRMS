<?php

// if (session_status() === PHP_SESSION_NONE) session_start();



// Clear old error messages
// unset($_SESSION['error']);

// // Debug
// var_dump($_SESSION);
// exit;




// var_dump($_SESSION); exit;

// require_once BASE_PATH . '/app/core/BaseController.php';


// require_once BASE_PATH . '/app/Models/IncidentMediaModel.php';

// require_once BASE_PATH . '/app/Models/IncidentModel.php';

// require_once BASE_PATH . '/app/Middleware/Auth.php';


// require_once BASE_PATH . '/app/Models/ReporterModel.php';

// require_once BASE_PATH . '/app/core/BaseModel.php';


class StaffController extends BaseController
{


protected PDO $pdo;

private $incidentModel;

public function __construct()
{
    Auth::requireRole(['staff']);

    global $pdo;
        $this->pdo = $pdo;
}





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
            'layouts/grid.css',
            'pages/send-with.css'
        ],
        'page_js' => [
            'sidebar.js',
            'send-with.js'
        ]

    ]);

}





public function newReports()
{
    Auth::requireRole(['staff']);

    $filters = [
        'category' => trim($_GET['category'] ?? ''),
        'status'   => 'new', 
    ];

    $incidentModel = new IncidentModel();
    $reports = $incidentModel->getNewReports($filters);

     $this->view('staff/new-reports', [
            'reports' => $reports,
            'page_title' => 'New Reports',
            'mode' => 'edit',
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

   



public function reviewIncident()
{
    Auth::requireRole(['staff']);

    $trackingCode = $_GET['code'] ?? null;
    $currentStep  = (int) ($_GET['step'] ?? 1);

    if (!$trackingCode) {
        $_SESSION['error'] = 'Missing tracking code.';
        header("Location: /RMS/public/index.php?url=staff/newReports");
        exit;
    }

    $incidentModel = new IncidentModel();
    $mediaModel    = new IncidentMediaModel();

    $incident = $incidentModel->findByTrackingCode($trackingCode);
    

    if (!$incident) {
        $_SESSION['error'] = 'Incident not found.';
        header("Location: /RMS/public/index.php?url=staff/newReports");
        exit;
    }

    $media = $mediaModel->getByIncidentId($incident['id']);

    // Permissions via policy
    $canTakeAction  = IncidentPolicy::staffCanTakeAction($incident, $_SESSION['user']);
    $canViewActions = IncidentPolicy::canViewActions($incident, $_SESSION['user']);

    $previousActions = $canViewActions
        ? $incidentModel->getIncidentActions($incident['id'])
        : [];

   
    $reporter = null;
    if (!empty($incident['reporter_id'])) {
        $reporterModel = new ReporterModel();
        $reporter = $reporterModel->findById((int) $incident['reporter_id']);
    }

    $mode = 'view';
    $viewFile = $this->resolveStepView($incident, $currentStep, $mode);

    $isNew = $incident['status'] === 'new';

    $this->view($viewFile, [
        'incident'        => $incident,
        'media'           => $media,
        'reporter'        => $reporter,
        'previousActions' => $previousActions,
        'canTakeAction'   => $canTakeAction,
        'currentStep'     => $currentStep,
        'trackingcode'    => $trackingCode,
        'steps'           => $isNew ? ['Incident Details', 'Reporter Details', 'Assessment'] : [],
        'mode'            => $mode,
        'page_title'      => 'Review Incident',
        'page_css'        => [
            'topnavbar.css',
            'sidebar.css',
            'base/typography.css',
            'components/button.css',
            'components/form.css',
            'components/status.css',
            'pages/page.css',
            'layouts/form-layout.css',
            'components/steps-bar.css',
            'components/attachment.css'
        ],
        'page_js' => ['sidebar.js']
    ]);
}









// public function reporterDetails()
// {
//     $code = $_GET['code'] ?? null;
//     if (!$code) 
//     {
//         die('Missing tracking code');
//     }


//     $incidentModel = new IncidentModel();
//     $incident = $incidentModel->getByTrackingCode($code);


//     $reporter = null;
//     if (!empty($incident['reporter_id'])) 
//     {
//         $reporterModel = new ReporterModel();
//         $reporter = $reporterModel->getById((int) $incident['reporter_id']);
//     }

//     $this->view('staff/reporter-details', [
//         'reporter' => $reporter,
//         'incident' => $incident,
//         'page_title' => 'Reporter Details',
//         'page_css' => [
//             'topnavbar.css',
//             'sidebar.css',
//             'base/typography.css',
//             'components/form.css',
//             'layouts/form-layout.css',
//             'pages/page.css',
//             'components/steps-bar.css',
//             'components/button.css'
//         ],

//         'page_js' => [
//             'sidebar.js'
//         ]
//     ]);
// }

public function reporterDetails()
{
    Auth::requireRole(['staff']);

    $trackingCode = $_GET['code'] ?? '';
    if ($trackingCode === '') {
        http_response_code(400);
        exit('Missing tracking code.');
    }

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($trackingCode);

    if (!$incident) {
        http_response_code(404);
        exit('Incident not found.');
    }

    $reporter = null;

    if (!empty($incident['reporter_id'])) {
        $reporterModel = new ReporterModel();
        $reporter = $reporterModel->findById(
            (int) $incident['reporter_id']
        );
        var_dump($reporter);
        exit;
    }

    $this->view('staff/reporter-details', [
        'incident' => $incident,
        'reporter' => $reporter
    ]);
}






private function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method Not Allowed');
    }
}





  

  public function escalate()
{
    $trackingCode = $_GET['code'] ?? null;

    if (!$trackingCode) 
    {
        $_SESSION['error'] = 'Tracking code missing';
        header("Location: /RMS/public/index.php?url=staff/newReports");
        exit;
    }

    // Fetch incident by code
    // global $pdo; // or inject PDO
    // $stmt = $pdo->prepare("SELECT * FROM incidents WHERE tracking_code = :code");
    // $stmt->execute([':code' => $trackingCode]);
    // $incident = $stmt->fetch(PDO::FETCH_ASSOC);

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($trackingCode);


    if (!$incident) 
    {
        $_SESSION['error'] = 'Incident not found';
        header("Location: /RMS/public/index.php?url=staff/newReports");
        exit;

    }

        $userModel = new UserModel();
    $responders = $userModel->getResponders(); 

        $this->view('staff/escalate', [
            'incident' => $incident,
            'page_title' => 'Report Escalate',
            'responders' => $responders,
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


// public function escalateConfirm()
// {
//     $trackingCode = $_GET['code'] ?? null;
//     if (!$trackingCode) {
//         exit('Tracking code missing');
//     }

//     $incidentModel = new IncidentModel();
//     $incident = $incidentModel->findByTrackingCode($trackingCode);

//     if (!$incident) {
//         exit('Incident not found');
//     }

//     $this->view('staff/escalate-confirm', [
//         'incident' => $incident,
//         'page_title' => 'Escalation Complete'
//     ]);
// }






public function submitEscalation()
{
    Auth::requireRole(['staff']);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method Not Allowed');
    }

    $incidentId  = (int) ($_POST['incident_id'] ?? 0);
    $trackingCode = $_POST['tracking_code'] ?? null;
    $responderId = (int) ($_POST['external_responder_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $staffId     = $_SESSION['user']['id'] ?? null;

    if (!$incidentId || !$trackingCode || !$responderId || !$staffId) {
        $_SESSION['error'] = 'Incomplete escalation data.';
        header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=$trackingCode&step=3");
        exit;
    }

    $incidentModel = new IncidentModel();
    $userModel     = new UserModel();

    $incident = $incidentModel->findById($incidentId);
    if (!$incident) {
        $_SESSION['error'] = 'Incident not found.';
        header("Location: /RMS/public/index.php?url=staff/newReports");
        exit;
    }

    $responder = $userModel->findById($responderId);
    if (!$responder) {
        $_SESSION['error'] = 'Selected responder not found.';
        header("Location: /RMS/public/index.php?url=staff/escalate&code=$trackingCode");
        exit;
    }

    try {
        $incidentModel->escalateIncident([
            'incident_id'   => $incidentId,
            'responder_id'  => $responder['id'],
            'responder_name'=> $responder['username'],
            'description'   => $description,
            'staff_id'      => $staffId,
        ]);

        $_SESSION['success'] =
            "Incident successfully forwarded to {$responder['username']}.";

        header("Location: /RMS/public/index.php?url=staff/escalateConfirm&code=$trackingCode");
        exit;

    } catch (RuntimeException $e) {
    if ($e->getMessage() === 'Incident already escalated') {
        header("Location: /RMS/public/index.php?url=staff/escalateConfirm&code=$trackingCode");
        exit;
    }

    $_SESSION['error'] = $e->getMessage();
}

    header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=$trackingCode&step=3");
    exit;
}

    

public function submitAssessment()
{
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method Not Allowed');
    }

    $trackingCode = $_POST['tracking_code'] ?? null;
    $validity     = $_POST['validity'] ?? null;
    $priority     = $_POST['priority'] ?? null;
    $remarks      = trim($_POST['invalid_reason'] ?? '');
    $staffId      = $_SESSION['user']['id'] ?? null;

    if (!$trackingCode || !$validity || !$staffId) {
        $_SESSION['error'] = "Please complete the assessment.";
        header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=$trackingCode&step=3");
        exit;
    }

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($trackingCode);

    if (!$incident) {
        $_SESSION['error'] = "Incident not found.";
        header("Location: /RMS/public/index.php?url=staff/newReports");
        exit;
    }

    $incidentId = $incident['id'];

    // Determine status
    if ($validity === 'invalid') {
        $priority = null;
        $status = 'invalidated';
    } else {
        $status = 'validated';
    }

    // Save assessment
    $stmt = $this->pdo->prepare("
        INSERT INTO assessments (incident_id, staff_id, validity, priority, remarks)
        VALUES (:incident_id, :staff_id, :validity, :priority, :remarks)
        ON DUPLICATE KEY UPDATE
            validity = VALUES(validity),
            priority = VALUES(priority),
            remarks  = VALUES(remarks),
            assessed_at = NOW()
    ");
    $stmt->execute([
        ':incident_id' => $incidentId,
        ':staff_id'    => $staffId,
        ':validity'    => $validity,
        ':priority'    => $priority,
        ':remarks'     => $remarks
    ]);

    // Update incident table
    $this->pdo->prepare("
        UPDATE incidents
        SET status = :status
        WHERE id = :id
    ")->execute([
        ':status' => $status,
        ':id' => $incidentId
    ]);

    // Redirect depending on outcome
    if ($validity === 'valid' && in_array($priority, ['high','critical'], true)) {
        // High/critical -> forward to escalation form
        $_SESSION['success'] = "Incident requires escalation. Please forward to external responder.";
        header("Location: /RMS/public/index.php?url=staff/escalate&code=$trackingCode");
        exit;
    }

    if ($validity === 'valid') {
        $_SESSION['success'] = "Incident validated successfully.";
        header("Location: /RMS/public/index.php?url=staff/reportsValidated");
        exit;
    }

    if ($validity === 'invalid') {
        $_SESSION['success'] = "Incident marked as invalid.";
        header("Location: /RMS/public/index.php?url=staff/reportsInvalidated");
        exit;
    }
}







private function redirectAfterAction(string $status)
{
    $map = [
        'ongoing'     => 'staff/reportsOngoing',
        'pending'     => 'staff/reportsPending',
        'resolved'    => 'staff/reportsResolved',
        'validated'   => 'staff/reportsValidated',
        'invalidated' => 'staff/reportsInvalidated',
        'escalated'   => 'staff/reportsEscalated'
    ];

    $redirect = $map[$status] ?? 'staff/reportsValidated';
    header("Location: /RMS/public/index.php?url={$redirect}");
    exit;
}




private function renderReportPage(string $status, string $title, array $filters = [])
{
    $incidentModel = new IncidentModel();
    $reports = $incidentModel->getReportsByStatus($status, $filters);

    $this->view('staff/new-reports', [
        'reports' => $reports,
        'page_title' => $title,
        'mode' => 'view',
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


public function reportsValidated() {
    $this->renderReportPage('validated', 'Validated Reports', [
        'validity' => 'valid',
        'priority' => ['low','medium']
    ]);
}


public function reportsInvalidated() {
    $this->renderReportPage('invalidated', 'Invalidated Reports');
}


public function reportsOngoing() {
    $this->renderReportPage('ongoing', 'Ongoing Reports');
}


public function reportsResolved() {
    $this->renderReportPage('resolved', 'Resolved Reports');
}

public function reportsEscalated() {
    // $this->renderReportPage('escalated', 'Escalated Reports', [
    //     'validity' => 'valid',
    //     'priority' => ['high','critical']
    // ]);
     $this->renderReportPage('escalated', 'Escalated Reports');

}


public function responderAccts()
{
    $responderModel = new ResponderModel();

    $responders = $responderModel->getAllResponders();

    // require_once BASE_PATH . '/app/Views/staff/responders.php';

    $this->view('staff/responders', [
        'responders' => $responders,
        
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



// private function resolveMode(array $incident): string
// {
//     $final = ['validated', 'invalidated', 'resolved', 'escalated'];
//     return in_array($incident['status'], $final, true) ? 'view' : 'edit';
// }



protected function resolveStepView(array $incident, int $step, string $mode): string
{
    if ($incident['status'] === 'awaiting_escalation') {
        return 'staff/assessment'; // step 3 only
    }

    // if ($incident['status'] === 'escalated') {
    //     return 'staff/escalate-confirm';
    // }

    return match ($step) {
        1 => 'staff/incident-review',
        2 => 'staff/reporter-details',
        3 => 'staff/assessment',
        default => 'staff/incident-review',
    };
}



private function resolveMode(array $incident): string
{
    if ($incident['status'] === 'resolved') {
        return 'final';
    }

    $final = ['validated', 'invalidated', 'escalated'];
    return in_array($incident['status'], $final, true) ? 'view' : 'edit';
}




public function actionForm()
{
    Auth::requireLogin();

    $trackingCode = $_GET['code'] ?? '';
    if ($trackingCode === '') {
        http_response_code(400);
        exit('Missing tracking code.');
    }

    $incidentModel = new IncidentModel();
    $mediaModel = new IncidentMediaModel();
    $incident = $incidentModel->findByTrackingCode($trackingCode);

    if (!$incident) {
        http_response_code(404);
        exit('Incident not found.');
    }

    $user = $_SESSION['user'];

    // STAFF ACTION FORM
    if ($user['role'] === 'staff') {

        if (!IncidentPolicy::staffCanTakeAction($incident, $user)) {
            $_SESSION['error'] = 'You are not allowed to act on this incident.';
            header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=" . urlencode($trackingCode));
            exit;
        }

        $media               = $mediaModel->getByIncidentId($incident['id']);
        $canTakeAction       = IncidentPolicy::staffCanTakeAction($incident, $user);
        $canViewActions      = IncidentPolicy::canViewActions($incident, $user);
        $showActionFormFilled = in_array($incident['status'], ['resolved', 'escalated']);
        $previousActions     = $canViewActions
                                ? $incidentModel->getIncidentActions($incident['id'])
                                : [];


        $this->view('responder/action-form', [
                'incident'             => $incident,
                'media'                => $media,
                'mode'                 => 'staff', 
                'actionUrl'            => '/RMS/public/index.php?url=staff/submitAction',
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
        return;
    }

    // RESPONDER ACTION FORM
    if ($user['role'] === 'responder') {

        if (!IncidentPolicy::responderCanTakeAction($incident, $user)) {
            $_SESSION['error'] = 'You are not allowed to act on this incident.';
            header("Location: /RMS/public/index.php?url=responder/viewIncident&code=" . urlencode($trackingCode));
            exit;
        }

        if (IncidentPolicy::isFinalStatus($incident['status'])) {
            $_SESSION['error'] = 'This incident is already closed.';
            header("Location: /RMS/public/index.php");
            exit;
        }

        if (!$incidentModel->isIncidentAssignedToResponder(
            $incident['id'],
            $user['id']
        )) {
            $_SESSION['error'] = 'You are not assigned to this incident.';
            header("Location: /RMS/public/index.php?url=responder/dashboard");
            exit;
        }

        $this->view('responder/action-form', [
            'incident' => $incident,
        ]);
        return;
    }

    // FALLBACK — role not allowed
    http_response_code(403);
    exit('Unauthorized');
}




public function submitAction()
{
    Auth::requireRole(['staff']);
    $this->requirePost();

    // var_dump($_POST);
    // exit;
    
    $data = $this->sanitizeActionData($_POST);

    // var_dump($data);
    // var_dump($this->validateAction($data));
    // exit;



    $errors = $this->validateAction($data);
    if (!empty($errors)) {
        $_SESSION['error'] = implode(' ', $errors);
        header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=" . urlencode($_POST['tracking_code'] ?? ''));
        exit;
    }

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findById($data['incident_id']);

    if (!$incident) {
        $_SESSION['error'] = 'Incident not found.';
        header("Location: /RMS/public/index.php?url=staff/newReports");
        exit;
    }

    // Permission via policy
    if (!IncidentPolicy::staffCanTakeAction($incident, $_SESSION['user'])) {
        $_SESSION['error'] = 'You are not allowed to take action on this incident.';
        header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=" . urlencode($incident['tracking_code']));
        exit;
    }

    // Business rule: final states are locked
    if (IncidentPolicy::isFinalStatus($incident['status'])) {
        $_SESSION['error'] = 'This incident is already in a final state.';
        header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=" . urlencode($incident['tracking_code']));
        exit;
    }

    try {
        $incidentModel->beginTransaction();

        // Build involved parties array from parallel POST arrays
    $involvedParties = [];
    $owners       = $_POST['owner_of_property'] ?? [];
    $areas        = $_POST['affected_area']     ?? [];
    $descriptions = $_POST['party_description'] ?? [];

    foreach ($owners as $i => $owner) {
        if (trim($owner) === '' && trim($areas[$i] ?? '') === '') continue; // skip empty rows
        $involvedParties[] = [
            'owner'       => trim($owner),
            'area'        => trim($areas[$i] ?? ''),
            'description' => trim($descriptions[$i] ?? ''),
        ];
}

$incidentModel->addAction([
    'incident_id'             => $incident['id'],
    'action_taken'            => $data['action_taken'],
    'resolution_date'         => $_POST['resolution_date'] ?? date('Y-m-d'),
    'resolution_time'         => $_POST['resolution_time'] ?? date('H:i:s'),
    'status'                  => $data['status_update'],
    'acted_by'                => $_SESSION['user']['id'],
    'investigation_findings'  => trim($_POST['investigation_findings'] ?? ''),
    'resolution_disposition'  => trim($_POST['resolution_disposition'] ?? ''),
    'involved_parties'        => $involvedParties,
    'report_officer_first'  => trim($_POST['report_officer_first']  ?? ''),
    'report_officer_middle' => trim($_POST['report_officer_middle'] ?? ''),
    'report_officer_last'   => trim($_POST['report_officer_last']   ?? ''),
    'signatory_first'       => trim($_POST['signatory_first']       ?? ''),
    'signatory_middle'      => trim($_POST['signatory_middle']      ?? ''),
    'signatory_last'        => trim($_POST['signatory_last']        ?? ''),
    'report_officer_position' => trim($_POST['report_officer_position'] ?? ''),
    'signatory_position'      => trim($_POST['signatory_position']      ?? ''),

]);

if (!empty($_FILES['incident_image']['name'][0])) {
    $mediaModel = new IncidentMediaModel($this->pdo);
    $uploadDir  = BASE_PATH . '/public/uploads/';

    foreach ($_FILES['incident_image']['tmp_name'] as $i => $tmpName) {
        if ($_FILES['incident_image']['error'][$i] !== UPLOAD_ERR_OK) continue;

        $ext      = strtolower(pathinfo($_FILES['incident_image']['name'][$i], PATHINFO_EXTENSION));
        $safeName = uniqid('inc_', true) . '.' . $ext;

        if (move_uploaded_file($tmpName, $uploadDir . $safeName)) {
            $fileType = str_starts_with(
                $_FILES['incident_image']['type'][$i], 'video/'
            ) ? 'video' : 'image';

            $mediaModel->save([
                'incident_id' => $incidentId,
                'action_id'   => $actionId,   // ✅ links to the specific action
                'file_path'   => 'public/uploads/' . $safeName,
                'file_type'   => $fileType,
                'uploaded_by' => $responderId,
            ]);
        }
    }
}

        if ($_POST['status_update'] !== '') {
            $incidentModel->updateStatus($incident['id'], $_POST['status_update']);
        }

        $incidentModel->commit();

        $_SESSION['success'] = 'Action submitted successfully.';
    } catch (Exception $e) {
        $incidentModel->rollBack();
        $_SESSION['error'] = 'Failed to submit action.';
    }

    header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=" . urlencode($incident['tracking_code']));
    exit;
}


private function sanitizeActionData(array $post): array
{
    return [
        'incident_id'   => (int) trim($post['incident_id']   ?? 0),
        'action_taken'  => trim($post['action_taken']        ?? ''),
        'status_update' => trim($post['status_update']       ?? ''),
    ];
}

private function validateAction(array $data): array
{
    $errors = [];

    if 
        (empty($data['incident_id']))   $errors[] = 'Incident ID is missing.';
    if 
        (empty($data['action_taken']))  $errors[] = 'Action taken is required.';
    if 
        (empty($data['status_update'])) $errors[] = 'Status update is required.';

    return $errors;
}




public function submitExternalAction()
{
    Auth::requireRole(['responder']);
    $this->requirePost();

    $data = $this->sanitizeExternalAction($_POST);

    $errors = $this->validateExternalAction($data);
    if (!empty($errors)) {
        $_SESSION['error'] = implode(' ', $errors);
        header("Location: /RMS/public/index.php?url=responder/viewIncident&code=" . urlencode($data['tracking']));
        exit;
    }

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findById($data['incident_id']);

    if (!$incident) {
        $_SESSION['error'] = 'Incident not found.';
        header("Location: /RMS/public/index.php?url=responder/dashboard");
        exit;
    }

    // Check assignment
    if (!$incidentModel->isIncidentAssignedToResponder(
        $incident['id'],
        $_SESSION['user']['id']
    )) {
        $_SESSION['error'] = 'You are not assigned to this incident.';
        header("Location: /RMS/public/index.php?url=responder/dashboard");
        exit;
    }

    // Check status permissions
    if (!IncidentPolicy::responderCanTakeAction($incident, $_SESSION['user'])) {
        $_SESSION['error'] = 'You cannot take action on this incident.';
        header("Location: /RMS/public/index.php?url=responder/viewIncident&code=" . urlencode($incident['tracking_code']));
        exit;
    }

    try {
        $incidentModel->beginTransaction();

        $incidentModel->addAction([
            'incident_id' => $incident['id'],
            'action_taken'=> $data['action_taken'],
            'status'      => $data['status'],
            'acted_by'    => $_SESSION['user']['id'],
        ]);

        // Responders may update status (usually ongoing / resolved)
        if ($data['status']) {
            $incidentModel->updateStatus($incident['id'], $data['status']);
        }

        $incidentModel->commit();

        $_SESSION['success'] = 'Action recorded successfully.';
    } catch (Exception $e) {
        $incidentModel->rollBack();
        $_SESSION['error'] = 'Failed to submit action.';
    }

    header("Location: /RMS/public/index.php?url=responder/viewIncident&code=" . urlencode($incident['tracking_code']));
    exit;
}





private function sanitizeExternalAction(array $post): array
{
    return [
        'incident_id' => (int) ($post['incident_id'] ?? 0),
        'action_taken'=> trim($post['action_taken'] ?? ''),
        'status'      => trim($post['status'] ?? ''),
        'tracking'    => trim($post['tracking_code'] ?? ''),
    ];
}


private function validateExternalAction(array $data): array
{
    $errors = [];

    if ($data['incident_id'] <= 0) {
        $errors[] = 'Invalid incident.';
    }

    if ($data['action_taken'] === '') {
        $errors[] = 'Action taken is required.';
    }

    return $errors;
}


public function finalReport()
{
    Auth::requireRole(['staff']);

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
    $response        = !empty($actions) ? end($actions) : [];
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
        'incident'        => $incidentFull,
        'assessment'      => $incidentFull,
        'response'        => $response,
        'reporter'        => $reporter,
        'escalation'      => $escalation,   // ✅ was hardcoded []
        'involvedParties' => $involvedParties,
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


public function previewReport()
{
    Auth::requireRole(['staff']);

    $code = $_GET['code'] ?? null;
    if (!$code) {
        $_SESSION['error'] = 'Missing tracking code.';
        header("Location: /RMS/public/index.php?url=staff/dashboard");
        exit;
    }

    $incidentModel = new IncidentModel();
    $mediaModel    = new IncidentMediaModel();

    $incident     = $incidentModel->findByTrackingCode($code);
    $incidentFull = $incidentModel->findById($incident['id']);

    if (!$incident) {
        $_SESSION['error'] = 'Incident not found.';
        header("Location: /RMS/public/index.php?url=staff/dashboard");
        exit;
    }

    $media      = $mediaModel->getByIncidentId($incident['id']);
    $actions    = $incidentModel->getIncidentActions($incident['id']);
    $response   = !empty($actions) ? end($actions) : null;
    $assessment = $incidentModel->findById($incident['id']);

    $reporter = null;
    if (!empty($incident['reporter_id'])) {
        $reporterModel = new ReporterModel();
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
        'incident'        => $incident,
        'assessment'      => $assessment,
        'media'           => $media,
        'reporter'        => $reporter,
        'response'        => $response,
        'escalation'      => $escalation,  // ✅ added
        'involvedParties' => [],           // ✅ was missing entirely
        'page_title'      => 'Preview Final Report',
        'page_css'        => [
            'topnavbar.css',
            'sidebar.css',
            'base/typography.css',
            'components/button.css'
        ],
        'page_js' => ['sidebar.js']
    ]);
}


}

?>