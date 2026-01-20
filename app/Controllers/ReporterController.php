<?php

require_once BASE_PATH . '/app/Models/IncidentModel.php';

require_once BASE_PATH . '/app/Models/RemarkModel.php';

require_once BASE_PATH . '/app/Models/ReporterModel.php';

require_once BASE_PATH . '/app/Models/UserModel.php';

require_once BASE_PATH . '/app/Models/IncidentMediaModel.php';

require_once BASE_PATH . '/app/Core/BaseController.php';

class ReporterController extends BaseController
{
    
    
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
        ]
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

    public function incidentConfirmation()
    {
        $this->view('public/incident-confirmation', [
            'page_title' => 'Incident Confirmation', 
            'page_css' => [
                'topnavbar.css',
                'components/form.css',
                'components/button.css',
                'pages/page.css',
                'layouts/form-layout.css',
                'base/typography.css'
                
            ]
        ]);
    }
    

    public function saveIncident()
    {
        // session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /RMS/public/index.php?url=reporter/incidentForm');
            exit;
        }

        // ✅ MATCH DB + MODEL KEYS EXACTLY
        $_SESSION['incident'] = [
            'subject'           => $_POST['subject'],
            'date_of_incident'  => $_POST['date_of_incident'],
            'time_of_incident'  => $_POST['time_of_incident'],
            'category'          => $_POST['category_of_incident'],
            'description'       => $_POST['description'],
            'incident_type'     => $_POST['incident_type'] ?? null,
            'location_building' => $_POST['location_building'],
            'location_department' => $_POST['location_department'],
            'location_landmark' => $_POST['location_landmark']
        ];

       if (!empty($_FILES['incident_image']['name'][0])) {

    $_SESSION['incident']['files'] = [];

    foreach ($_FILES['incident_image']['tmp_name'] as $i => $tmp) {

        if ($_FILES['incident_image']['size'][$i] > 500 * 1024 * 1024) {
            continue;
        }

        $ext = pathinfo($_FILES['incident_image']['name'][$i], PATHINFO_EXTENSION);
        $filename = uniqid('evidence_', true) . '.' . $ext;

        move_uploaded_file(
            $tmp,
            BASE_PATH . '/public/uploads/' . $filename
        );

        $_SESSION['incident']['files'][] = $filename;
    }
}


        header('Location: /RMS/public/index.php?url=reporter/sendWith');
        exit;
    }


    

    public function confirmation(){
        $trackingCode = $_GET['code'] ?? '';
        // require BASE_PATH . '/app/Views/public/incident-confirmation.php';


        $this->view('public/incident-confirmation', [
            'trackingCode' => $trackingCode,
            'page_title' => 'Incident Confirmation and Tracking code',
            'page_css'   => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/form.css',
                'layouts/form-layout.css'

            ]

        ]);
        
    }





   public function submitReport()
{
    // ✅ Start session safely
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // ✅ Only allow POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        echo "Invalid request method";
        exit;
    }

    // ✅ Make sure incident data exists in session
    if (!isset($_SESSION['incident'])) {
        die('Session expired. Please resubmit the incident.');
    }

    // ✅ Initialize variables
    $errors = [];
    $userId = null;
    $authMethod = $_POST['auth_method'] ?? null;

    // ✅ Check authentication method
    if (!$authMethod) {
        $errors[] = 'Please choose an authentication method.';
    }

    // 🔹 Institutional authentication
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

    // 🔹 Phone authentication
    if ($authMethod === 'phone' && empty($_POST['phone'])) {
        $errors[] = 'Phone number is required.';
    }

    // ✅ If there are validation errors, save them and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: /RMS/public/index.php?url=reporter/sendWith');
        exit;
    }

    // ✅ Generate a tracking code for this incident
    $trackingCode = strtoupper(bin2hex(random_bytes(4)));

    // ✅ Save the incident in the database
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

    // ✅ Save reporter info in the database
    try {
        $reporterModel = new ReporterModel();
        $reporterModel->createReporter([
            // 'incident_id' => $incidentId,
            'auth_method' => $authMethod,
            'user_id'     => $userId,
            'phone'       => $_POST['phone'] ?? null,
            'email'       => null
        ]);
    } catch (Exception $e) {
        die("Error saving reporter info: " . $e->getMessage());
    }



    if (!empty($_SESSION['incident']['files'])) {

    $mediaModel = new IncidentMediaModel();

    foreach ($_SESSION['incident']['files'] as $fileName) {

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileType = in_array($ext, ['mp4','mov','avi']) ? 'video' : 'image';

        $mediaModel->create([
            'incident_id' => $incidentId,
            'file_path'   => $fileName,
            'file_type'   => $fileType,
            'uploaded_by' => $userId
        ]);
    }
}

    // $incidentMediaModel =new IncidentMediaModel();
    // $media = $incidentMediaModel->getByIncidentId($incidentId);

    // $this->view('incident/form', [
    //     'mode' => 'view',
    //     'incident' => $incident,
    //     'media' => $media
    // ]);


   
    unset($_SESSION['incident']);

    //  Redirect to confirmation page with tracking code
    header('Location: /RMS/public/index.php?url=reporter/confirmation&code=' . $trackingCode);
    exit;
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
            'pages/page.css'
        ]
    ]);
    exit;
}


public function status()
{
    $incident = $this->getIncidentByCode();

    $this->view('reporter/report-status', [
        'incident' => $incident,
         'page_title' => 'Report Status',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/form.css',
                'layouts/form-layout.css',
                'pages/page.css'
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


// public function remarks()
// {
//     $incident = $this->getIncidentByCode();

//     $remarksModel = new RemarkModel();
//     $remarks = $remarksModel->getByIncident($incident['id']);

//     $this->view('reporter/reporter-remarks', [
//         'incident' => $incident,
//         'remarks'  => $remarks,
//         'page_title' => 'Reporter Remarks',
//             'page_css' => [
//                 'topnavbar.css',
//                 'base/typography.css',
//                 'components/remarks.css',
//                 'pages/page.css'

//             ]
//     ]);
// }

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



public function sendRemark()
{
    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        exit;
    }

    $incidentId = $_POST['incident_id'];
    $message    = trim($_POST['message']);

    if ($message === '') {
        die('Message cannot be empty');
    }

    $remarkModel = new RemarkModel();
    $remarkModel->create([
        'incident_id' => $incidentId,
        'sender_id'   => $_SESSION['user_id'],
        'message'     => $message
    ]);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}



}
