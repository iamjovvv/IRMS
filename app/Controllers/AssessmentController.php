<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

require_once BASE_PATH . '/app/core/BaseController.php';

class AssessmentController extends BaseController
{
    // protected $pdo;

    // public function __construct(PDO $pdo)
    // {
    //     $this->pdo = $pdo;
    // }

    public function assessment($incidentId)
{
    $incidentId = (int)$incidentId;

   $stmt = $this->pdo->prepare(
    "SELECT id, tracking_code FROM incidents WHERE id = :id"
);
$stmt->execute([':id' => $incidentId]);
$incident = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$incident) {
        die('Incident not found');
    }

    // ✅ DEFINE VARIABLES FOR THE VIEW
    $trackingCode = $incident['tracking_code'];

    require BASE_PATH . '/app/Views/assessment/assessment.php';
}



//     public function submit()
// {
// // error_log(print_r($_POST, true));
// // var_dump($_POST);
// // exit;


//     // Ensure POST
//     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//         http_response_code(405);
//         echo "Method not allowed";
//         exit;
//     }


//     // 1. Sanitize inputs
//     $trackingCode = $_POST['tracking_code'] ?? null;
//     $staffId = $_SESSION['user_id'] ?? null;
//     $validity = $_POST['validity'] ?? null;
//     $priority = $_POST['priority'] ?? null;
//     $remarks = trim($_POST['invalid_reason'] ?? null);

//     // 2. Validate required fields
//     if (!$trackingCode || !$staffId || !$validity) {
//         $_SESSION['error'] = "Please complete the assessment.";
//         header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=$trackingCode");
//         exit;
//     }

//     // 3. Fetch incident by tracking code
//     // $stmt = $this->pdo->prepare("SELECT * FROM incidents WHERE tracking_code = :code");
//     // $stmt->execute([':code' => $trackingCode]);
//     // $incident = $stmt->fetch(PDO::FETCH_ASSOC);

//     // if (!$incident) {
//     //     $_SESSION['error'] = "Incident not found.";
//     //     header("Location: /RMS/public/index.php?url=staff/reviewIncident");
//     //     exit;
//     // }

//     // $incidentId = $incident['id'];

//     $incidentModel = new IncidentModel();
//     $incident = $incidentModel->findByTrackingCode($trackingCode);

//     if (!$incident) {
//         $_SESSION['error'] = "Incident not found.";
//         header("Location: /RMS/public/index.php?url=staff/reviewIncident");
//         exit;
//     }

//     $incidentId = $incident['id'];




//     // 4. If report is invalid, priority is null
//     if ($validity === 'invalid') {
//         $priority = null;
//     }

//     // 5. If report is valid but no priority selected → redirect back
//     if ($validity === 'valid' && !$priority) {
//         $_SESSION['error'] = "Please select a priority level.";
//         header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=$trackingCode");
//         exit;
//     }

//     // 6. Upsert assessment
//     $sql = "
//         INSERT INTO assessments (incident_id, staff_id, validity, priority, remarks)
//         VALUES (:incident_id, :staff_id, :validity, :priority, :remarks)
//         ON DUPLICATE KEY UPDATE
//             validity = VALUES(validity),
//             priority = VALUES(priority),
//             remarks = VALUES(remarks),
//             assessed_at = CURRENT_TIMESTAMP
//     ";

//     $stmt = $this->pdo->prepare($sql);
//     $stmt->execute([
//         ':incident_id' => $incidentId,
//         ':staff_id'    => $staffId,
//         ':validity'    => $validity,
//         ':priority'    => $priority,
//         ':remarks'     => $remarks,
//     ]);


    

    
   


//     // 7. If priority is high or critical → redirect to escalate page
//     if ($priority === 'high' || $priority === 'critical') {
//         header("Location: /RMS/public/index.php?url=staff/reportEscalate&code=$trackingCode");
//         exit;
//     }

//     if (headers_sent($file, $line)) {
//     die("Headers already sent in $file on line $line");
// }


//     // 8. Otherwise → back to reviewIncident step 3
//     // Example: after successfully saving assessment
//     $_SESSION['success'] = "Assessment saved successfully.";

//     // Example: if something went wrong
//     // $_SESSION['error'] = "Please complete the assessment.";

//     // Then redirect to the review page
//     header("Location: /RMS/public/index.php?url=staff/reviewIncident&code=$trackingCode&step=3");
//     exit;
// }



    

    
}
