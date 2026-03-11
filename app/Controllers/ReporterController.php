<?php

// require_once BASE_PATH . '/app/Models/IncidentModel.php';

// require_once BASE_PATH . '/app/Models/RemarkModel.php';

// require_once BASE_PATH . '/app/Models/ReporterModel.php';

// require_once BASE_PATH . '/app/Models/UserModel.php';

// require_once BASE_PATH . '/app/Models/IncidentMediaModel.php';

// require_once BASE_PATH . '/app/Core/BaseController.php';

class ReporterController extends BaseController
{

 protected PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }
    

    
    public function reportForm() 
    {
      $this->view('reporter/report-form', [
        'mode' => 'create',
        'page_title' => 'Report Form',
        'page_css' => [
            'topnavbar.css',
            'base/typography.css',
            'components/button.css',
            'components/form.css',
            'layouts/form-layout.css',
            'pages/page.css',
            'components/attachment.css'
        ],
        'page_js' => [
            'attachment.js'
        ]
      ]);

    }





  public function submitReport()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo "Invalid request method";
        exit;
    }

    if (!isset($_SESSION['incident'])) {
        die('Session expired. Please resubmit the incident.');
    }

    $errors = [];
    $userId = null;
    $authMethod = $_POST['auth_method'] ?? null;

    if (!$authMethod) {
        $errors[] = 'Please choose an authentication method.';
    }

    // Institutional authentication
    if ($authMethod === 'org_id') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $errors[] = 'Institutional username and password are required.';
        } else {
            $userModel = new UserModel();
            $user = $userModel->authenticate($username, $password);

            if (!$user) {
                $errors[] = 'Invalid institutional username or password.';
            } else {
                $userId = $user['id'];
            }
        }
    }

    // Phone authentication
    if ($authMethod === 'phone' && empty($_POST['phone'])) {
        $errors[] = 'Phone number is required.';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: /RMS/public/index.php?url=reporter/sendWith');
        exit;
    }

    // Generate tracking code
    $trackingCode = strtoupper(bin2hex(random_bytes(4)));

    // Save incident
    try {
        $incidentModel = new IncidentModel();
        $incidentId = $incidentModel->createIncident([
            ...$_SESSION['incident'],
            'tracking_code' => $trackingCode
        ]);

        if (!$incidentId) {
            throw new Exception("Failed to save incident in database.");
        }
    } catch (Exception $e) {
        die("Error saving incident: " . $e->getMessage());
    }

    // Save reporter
    try {
        $reporterModel = new ReporterModel();

        if ($authMethod === 'org_id' && $userId) {



       $existing = $reporterModel->findByUserId((int) $userId);
        if (!$existing) {
            $reporterId = $reporterModel->createReporter([
                'auth_method'   => 'org_id',
                'user_id'       => $userId,
                'org_id_number' => $user['org_id_number'] ?? null,
                'phone'         => null
            ]);
        } else {
            $reporterId = $existing['id'];
        }

        } else {
            // Phone auth — anonymous reporter
            $reporterId = $reporterModel->createReporter([
                'auth_method'   => 'phone',
                'user_id'       => null,
                'org_id_number' => null,
                'phone'         => $_POST['phone'] ?? null
            ]);
        }

    } catch (Exception $e) {
        die("Error saving reporter info: " . $e->getMessage());
    }

    $incidentModel->updateReporterId($incidentId, $reporterId);

    // Save media files
    if (!empty($_SESSION['incident']['files'])) {
        $mediaModel = new IncidentMediaModel();
        foreach ($_SESSION['incident']['files'] as $file) {
            $mediaModel->create([
                'incident_id' => $incidentId,
                'file_path'   => $file['file_path'],
                'file_type'   => $file['file_type'],
                'uploaded_by' => $userId
            ]);
        }
    }

    $incidentType = $_SESSION['incident']['incident_type'] ?? 'non-fatal';
    unset($_SESSION['incident']);

    // ── Notify staff & admin of new report ──
    $notifModel = new NotificationModel();
    $isFatal    = ($incidentType === 'fatal');

    $notifModel->notifyByRole('staff', [
        'type'        => 'new_report',
        'title'       => $isFatal ? '🚨 FATAL Incident Reported' : 'New Incident Report',
        'message'     => "Incident [{$trackingCode}] has been submitted and requires review.",
        'incident_id' => $incidentId,
    ]);

    $notifModel->notifyByRole('admin', [
        'type'        => 'new_report',
        'title'       => $isFatal ? '🚨 FATAL Incident Reported' : 'New Incident Report',
        'message'     => "Incident [{$trackingCode}] has been submitted.",
        'incident_id' => $incidentId,
    ]);

if ($isFatal) {
    $_SESSION['pending_escalation'] = [
        'incident_id'   => $incidentId,
        'tracking_code' => $trackingCode,
    ];
    header('Location: /RMS/public/index.php?url=reporter/escalate&code=' . urlencode($trackingCode));
    exit;
}

    header('Location: /RMS/public/index.php?url=reporter/confirmation&code=' . urlencode($trackingCode));
    exit;
}







    public function reportFormGet()
    {
        $trackingCode = $_GET['code'] ?? null;
        $preview = (int)($_GET['preview'] ?? 0);

        if (!$trackingCode) {
            die('Missing tracking code');
        }

        $incidentModel = new IncidentModel();
        $incident = $incidentModel->findByTrackingCode($trackingCode);

        if (!$incident) {
            die('Incident not found');
        }

        $mediaModel = new IncidentMediaModel();
        $media = $mediaModel->getByIncidentId($incident['id']);

        $this->view('reporter/report-form', [
            'incident' => $incident,
            'media'    => $media,
            'mode'     => 'view',
            'currentStep' => 1,
            'steps' => ['Step 1', 'Step 2'], // optional if used
            'page_title' => $preview ? 'Incident Preview' : 'Report Incident',
            'page_css' => [

                'topnavbar.css',
                'sidebar.css',
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



     public function sendWith()
    {
        $this->view('reporter/send-with', [
            'page_title' => 'Send with',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/form.css',
                'layouts/form-layout.css',
                'pages/page.css'
            ],
            'page_js' => [
                'send-with.js'
            ]
        ]);

    }


    

    public function saveIncident()
    {
       

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /RMS/public/index.php?url=reporter/incidentForm');
            exit;
        }

        $allowedDepartments = [
            '(CAFNR) College of Agriculture, Fisheries and Natural Resources',
            '(CAC) College of Arts and Communication',
            '(CBA) College of Business Administration',
            '(CE) College of Engineering',
            '(CNAH) College of Nursing and Allied Health Sciences',
            '(CS) College of Science',
            '(CVM) College of Veterinary Medicine',
            '(CL) College of Law',
            'N/A'
            ];

                if (!in_array($_POST['location_department'], $allowedDepartments, true)) {
                die('Invalid department selected');
                }

            $lat = isset($_POST['latitude']) && $_POST['latitude'] !== ''
                ? (float) $_POST['latitude']
                : null;

            $lng = isset($_POST['longitude']) && $_POST['longitude'] !== ''
                ? (float) $_POST['longitude']
                : null;

            if ($lat === null || $lng === null) {
                die("GPS location is required.");
            }

            if (empty($_POST['readable_address'])) {
                error_log('Readable address missing on submit');
                 $readable_address = "Approximate location";
            }
                

        // MATCH DB + MODEL KEYS EXACTLY
        $_SESSION['incident'] = [
            'subject'           => $_POST['subject'],
            'date_of_incident'  => $_POST['date_of_incident'],
            'time_of_incident'  => $_POST['time_of_incident'],
            'category'          => $_POST['category_of_incident'],
            'description'       => $_POST['description'],
            'incident_type'     => $_POST['incident_type'] ?? null,
            'location_building' => $_POST['location_building'],
            'location_department' => $_POST['location_department'],
            'readable_address'  => $_POST['readable_address'] ?? null,
            'location_landmark' => $_POST['location_landmark'],
            'latitude'          => $lat,
            'longitude'         => $lng,

        ];

        

       $_SESSION['incident']['files'] = [];

        // 1️⃣ Handle captured camera image
        if (!empty($_POST['captured_image_data'])) {
            $dataURL = $_POST['captured_image_data'];

            // Extract base64
            if (preg_match('/^data:image\/(\w+);base64,/', $dataURL, $type)) {
                $data = substr($dataURL, strpos($dataURL, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc.

                $data = base64_decode($data);
                if ($data === false) {
                    die('Base64 decode failed');
                }
            } else {
                die('Invalid image data');
            }

            $filename = uniqid('camera_', true) . '.' . $type;
            file_put_contents(BASE_PATH . '/public/uploads/' . $filename, $data);

            $_SESSION['incident']['files'][] = [
                'file_path' => $filename,
                'file_type' => 'image',
                'latitude'  => $_POST['latitude'] ?? null,
                'longitude' => $_POST['longitude'] ?? null
            ];
        }

        // 2️⃣ Handle uploaded files
        if (!empty($_FILES['incident_image']['name'][0])) {
            foreach ($_FILES['incident_image']['tmp_name'] as $i => $tmp) {
                if ($_FILES['incident_image']['size'][$i] > 500 * 1024 * 1024) continue;

                $ext = pathinfo($_FILES['incident_image']['name'][$i], PATHINFO_EXTENSION);
                $filename = uniqid('upload_', true) . '.' . $ext;

                move_uploaded_file($tmp, BASE_PATH . '/public/uploads/' . $filename);

                $fileType = in_array(strtolower($ext), ['mp4','mov','avi']) ? 'video' : 'image';

                $_SESSION['incident']['files'][] = [
                    'file_path' => $filename,
                    'file_type' => $fileType,
                    'latitude'  => $_POST['latitude'] ?? null,
                    'longitude' => $_POST['longitude'] ?? null
                ];
            }
        }
        

        // Redirect to next step or confirmation
        header('Location: /RMS/public/index.php?url=reporter/sendWith');
        exit;
    }


    

    public function confirmation(){
        $trackingCode = $_GET['code'] ?? '';
    
            if (!$trackingCode) {
                header('Location: /RMS/public/index.php');
                exit;
            }

            $incidentModel = new IncidentModel();
            $incident = $incidentModel->findByTrackingCode($trackingCode);

        $this->view('public/incident-confirmation', [
            'incident'     => $incident,
            'trackingCode' => $trackingCode,
            'page_title' => 'Incident Confirmation and Tracking code',
            'page_css'   => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/form.css',
                'layouts/form-layout.css',
                'pages/page.css'

            ]

        ]);
        
    }





 




private function getIncidentByCode()
{
    $code = $_GET['code'] ?? null;

    if(!$code) {
        die('Tracking code is required.');
    }

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($code);

    if(!$incident){
        die('Invalid or unknown tracking code.');
    }
    return $incident;


}


public function summary()
{
    $incident = $this->getIncidentByCode();

    $this->view('reporter/incident-summary', [
        'incident' => $incident,
        'page_title' => 'Incident Summary',
        'page_css'   => [
            'topnavbar.css',
            'components/card.css',
            'layouts/grid.css',
            'pages/page.css',
            'base/typography.css'
        ]
    ]);
    exit;
}





public function addEvidence()
{
    $incident = $this->getIncidentByCode();

    $this->view('reporter/add-evidence', [
        'incident' => $incident,
        'page_title' => 'Add Evidence',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/card.css',
                'pages/page.css'
            ]

    ]);
    exit;
}




public function remarks()
{
    $code = $_GET['code'] ?? '';

    if ($code === '') {
        die('Invalid tracking code.');
    }

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($code);

    if (!$incident) {
        die('Incident not found.');
    }

    $remarkModel = new RemarkModel();
    $remarks = $remarkModel->getByIncident($incident['id']);

    $this->view('reporter/reporter-remarks', [
        'incident' => $incident,
        'remarks'  => $remarks,
        'page_title' => 'Reporter Remarks',
        'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/remarks.css',
                'pages/page.css'

            ]
    ]);
}




public function statusIncident()
{
    $trackingCode = $_GET['code'] ?? null;


    if (!$trackingCode) {
    http_response_code(400);
    exit('Invalid tracking code');
    }


    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findIncidentSummaryByTrackingCode($trackingCode);


    if (!$incident) {
    http_response_code(404);
    exit('Incident not found');
    }


    require __DIR__ . '/../Views/reporter/incident-summary.php';
}


public function status()
{
   $incident = $this->getIncidentByCode();
    $incidentModel = new IncidentModel();

    $actions  = $incidentModel->getIncidentActions($incident['id']);
    $response = !empty($actions) ? end($actions) : null;

   
    $escalation = null;
    if ($incident['status'] === 'escalated') {
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
    }
    // Get officer who acted
    // $officer = null;
    // if (!empty($response['responder_name'])) {
    //     // responder_name is already joined in getIncidentActions()
    //     $officer = ['username' => $response['responder_name']];
    // }


    // If escalated and no action yet, pull from escalations
    // if ($incident['status'] === 'escalated' && empty($officer)) {
    //     $stmt = $this->pdo->prepare("
    //         SELECT e.responder AS username
    //         FROM escalations e
    //         WHERE e.incident_id = :id
    //         LIMIT 1
    //     ");
    //     $stmt->execute([':id' => $incident['id']]);
    //     $officer = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    // }

    $this->view('reporter/report-status', [
        'incident'   => $incident,
        'response'   => $response,
        'escalation'=> $escalation,
        // 'officer'    => $officer,
        'page_title' => 'Report Status',
        'page_css'   => [
            'topnavbar.css',
            'base/typography.css',
            'components/form.css',
            'layouts/form-layout.css',
            'pages/page.css'
        ]
    ]);
}


public function reportStatus()
{
    $trackingCode = $_GET['code'] ?? null;
    if (!$trackingCode) exit('Invalid tracking code');

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($trackingCode);
    if (!$incident) exit('Incident not found');

    $latestAction = $incidentModel->findIncidentSummaryByTrackingCode($trackingCode) ?? [];

    // If escalated and no action yet, pull responder from escalations
if ($incident['status'] === 'escalated' && empty($latestAction['responder_name'])) {
    $stmt = $this->pdo->prepare("
        SELECT e.responder AS responder_name, 'responder' AS responder_role
        FROM escalations e
        WHERE e.incident_id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $incident['id']]);
    $escalation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($escalation) {
        $latestAction['responder_name'] = $escalation['responder_name'];
        $latestAction['responder_role'] = $escalation['responder_role'];
    }
}

    $assessment = $incidentModel->getAssessmentByIncidentId($incident['id']);
    if (!$assessment) $assessment = [];

    require __DIR__ . '/../Views/reporter/report-status.php';
}




public function submitEscalation()
{
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed');
    }

    $incidentId           = (int) ($_POST['incident_id'] ?? 0);
    $trackingCode         = $_POST['tracking_code'] ?? null;
    $externalResponderId  = (int) ($_POST['external_responder_id'] ?? 0);
    $description          = trim($_POST['description'] ?? '');
    $reporterId           = $_SESSION['user']['id'] ?? null;

    /* =========================
       Validate Required Fields
    ========================= */
    // if (!$incidentId || !$trackingCode || !$externalResponderId || !$reporterId) {
    //     $_SESSION['error'] = 'Incomplete escalation data.';
    //     header("Location: /RMS/public/index.php?url=reporter/escalate&code={$trackingCode}");
    //     exit;
    // }

    if (!$incidentId || !$trackingCode || !$externalResponderId) {
    $_SESSION['error'] = 'Incomplete escalation data.';
    header("Location: /RMS/public/index.php?url=reporter/escalate&code={$trackingCode}");
    exit;
}

    /* =========================
       Validate Responder
    ========================= */
    $userModel = new UserModel();
    $responder = $userModel->findById($externalResponderId);

    if (!$responder || $responder['role'] !== 'responder') {
        $_SESSION['error'] = 'Selected responder not found.';
        header("Location: /RMS/public/index.php?url=reporter/escalate&code={$trackingCode}");
        exit;
    }

    /* =========================
       Validate Incident
    ========================= */
    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($trackingCode);

    if (!$incident) {
        exit('Incident not found.');
    }

    $pendingEscalation = $_SESSION['pending_escalation'] ?? null;
    $isAutoEscalation  = $pendingEscalation && 
                        (int)$pendingEscalation['incident_id'] === $incidentId;

    if (!$isAutoEscalation && !$reporterId) {
        http_response_code(403);
        exit('Unauthorized escalation attempt.');
    }


    
    if ($incident['status'] === 'escalated') {
        $_SESSION['error'] = 'This incident has already been escalated.';
        header("Location: /RMS/public/index.php?url=reporter/myReports");
        exit;
    }

    /* =========================
    Insert Escalation
    ========================= */
    $sql = "
        INSERT INTO escalations
        (incident_id, responder_id, responder, description, escalated_by)
        VALUES
        (:incident_id, :responder_id, :responder, :description, :reporter_id)
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':incident_id'   => $incidentId,
        ':responder_id'  => $responder['id'],
        ':responder'     => $responder['username'],
        ':description'   => $description,
        ':reporter_id'   => $reporterId ?? null
    ]);

    /* =========================
    Update Incident Status
    ========================= */
    $this->pdo->prepare("
        UPDATE incidents
        SET status = 'escalated'
        WHERE id = :id
    ")->execute([':id' => $incidentId]);

    unset($_SESSION['pending_escalation']); // ← moved here, after both DB operations

    /* =========================
    Send Notifications
    ========================= */
    $notifModel = new NotificationModel();

    $notifModel->notifyUser((int)$responder['id'], [
        'type'        => 'escalation',
        'title'       => '🚨 Fatal Incident Assigned to You',
        'message'     => "You have been selected as responder for fatal incident [{$trackingCode}].",
        'incident_id' => $incidentId,
    ]);

    $notifModel->notifyByRole('staff', [
        'type'        => 'escalation',
        'title'       => '⚠️ Incident Escalated to External Responder',
        'message'     => "Fatal incident [{$trackingCode}] was escalated to {$responder['username']}.",
        'incident_id' => $incidentId,
    ]);

    $_SESSION['success'] = "Incident successfully forwarded to {$responder['username']}";
    header("Location: /RMS/public/index.php?url=reporter/confirmation&code=" . urlencode($trackingCode));
    exit;
}



// public function escalate()
// {
//     if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
//         http_response_code(405);
//         exit('Method not allowed');
//     }

//     $trackingCode = $_GET['code'] ?? null;
//     if (!$trackingCode) {
//         exit('Tracking code missing');
//     }

//     $incidentModel = new IncidentModel();
//     $incident = $incidentModel->findByTrackingCode($trackingCode);

//     if (!$incident) {
//         exit('Incident not found');
//     }

//     // // (Optional security check)
//     // if ((int)$incident['reporter_id'] !== (int)$_SESSION['user']['id']) {
//     //     http_response_code(403);
//     //     exit('Unauthorized access');
//     // }

//     $responders = (new UserModel())->getResponders();

//     $this->view('public/incident-confirmation', [
//         'trackingCode' => $trackingCode,
//         'incident' => $incident,
//         'page_title' => 'Report Escalate',
//         'responders' => $responders,
//         'page_css' => [
//             'topnavbar.css',
//             'base/typography.css',
//             'components/button.css',
//             'components/form.css',
//             'layouts/form-layout.css',
//             'pages/page.css',
//         ]
//     ]);
// }


public function escalate()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        exit('Method not allowed');
    }

    $trackingCode = $_GET['code'] ?? null;
    if (!$trackingCode) {
        exit('Tracking code missing');
    }

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($trackingCode);

    if (!$incident) {
        exit('Incident not found');
    }

    $responders = (new UserModel())->getResponders();

    $this->view('public/escalate', [       
        'trackingCode' => $trackingCode,
        'incident'     => $incident,
        'responders'   => $responders,
        'page_title'   => 'Select Emergency Responder',
        'page_css'     => [
            'topnavbar.css',
            'base/typography.css',
            'components/button.css',
            'components/form.css',
            'layouts/form-layout.css',
            'pages/page.css',
        ]
    ]);
}



}



