<?php

class EscalationController extends BaseController
{

    public function form()
{
    $trackingCode = $_GET['code'] ?? null;
    if (!$trackingCode) die('Tracking code missing');

    $incidentModel = new IncidentModel();
    $incident = $incidentModel->findByTrackingCode($trackingCode);
    if (!$incident) die('Incident not found');


    

    // Fetch responders
    $userModel = new UserModel();
    $responders = $userModel->getResponders();

    $this->view('layouts/escalate', [
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


// REPORTER
    //     public function autoEscalate()
    // {
    //     $trackingCode = $_GET['code'] ?? null;
    //     if (!$trackingCode) die('Missing tracking code');

    //     $incidentModel = new IncidentModel();
    //     $incident = $incidentModel->findByTrackingCode($trackingCode);

    //     if (!$incident) die('Incident not found');

    //     // 🔒 Rule
    //     if ($incident['incident_type'] !== 'fatal') {
    //         die('Escalation allowed only for fatal incidents.');
    //     }

    //     if ($incident['status'] === 'escalated') {
    //         header('Location: reporter/confirmation&code=' . urlencode($trackingCode));
    //         exit;
    //     }

       
    //     // For reporter auto-escalation (fatal)
    //     $incidentModel->createEscalation([
    //         'incident_id'   => $incident['id'],
    //         'description'   => 'Automatic escalation for fatal incident',
    //         'escalated_by'  => 0,   // 0 = system
    //         'responder'     => 'System'
    //     ]);




    //     $incidentModel->updateStatus($incident['id'], 'escalated');

    //     header(
    //         'Location: reporter/confirmation&code=' . urlencode($trackingCode)
    //     );
    //     exit;
    // }



    // // STAFF

    // public function submit()
    // {

    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //     http_response_code(405);
    //     exit('Invalid request method');
    // }  
    //     $trackingCode = $_POST['tracking_code'] ?? null;
    //     $responderId  = $_POST['external_responder_id'] ?? null;
    //     $description  = trim($_POST['description'] ?? '');
    //     $userId = $_SESSION['user']['id'];
    // $userRole = $_SESSION['user']['role'];

    //     if (!$trackingCode || !$responderId || $description === '') {
    //         $_SESSION['error'] = 'Missing escalation data.';
    //         header('Location: /RMS/public/index.php?url=staff/newReports');
    //         exit;
    //     }

    //     $incidentModel = new IncidentModel();
    //     $incident = $incidentModel->findByTrackingCode($trackingCode);

    //     if (!$incident) {
    //         $_SESSION['error'] = 'Incident not found.';
    //         header('Location: /RMS/public/index.php');
    //         exit;
    //     }

    //      if (!in_array($_SESSION['user']['role'] ?? '', ['staff','admin'])) {
    //     die('Unauthorized');
    // }

    //     if ($incident['status'] === 'escalated') {
    //         $_SESSION['error'] = 'Incident already escalated.';
    //         header('Location: /RMS/public/index.php');
    //         exit;
    //     }

    //     // 🔥 Save escalation
    //    // For staff
    //     $incidentModel = new IncidentModel();
    //     $incidentModel->createEscalation([
    //         'incident_id'   => $incident['id'],
    //         'responder_id'  => $responder['id'],
    //         'responder'     => $responder['username'],
    //         'description'   => $description,
    //         'escalated_by'  => $userId
    //     ]);



    //     // 🔁 Update incident status
    //     $incidentModel->updateStatus($incident['id'], 'escalated');

    //     // ✅ Redirect
    //     if ($userRole === 'staff' || $userRole === 'admin') {
    //     header(
    //         'Location: /RMS/public/index.php?url=staff/viewIncident'
    //         . '&code=' . urlencode($trackingCode)
    //         );
    //     } else {
    //         header(
    //             'Location: /RMS/public/index.php?url=reporter/confirmation'
    //             . '&code=' . urlencode($trackingCode)
    //         );
    //     }
    //     exit;
    // }


}


?>