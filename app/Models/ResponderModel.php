<?php
// If you have namespaces, add it here, e.g., namespace App\Models;

require_once BASE_PATH . '/app/core/BaseModel.php';

class ResponderModel Extends BaseModel
{
   

    // Create a new responder
   public function create(array $data) {
$stmt = $this->pdo->prepare("
INSERT INTO responders (organization_name, contact_email, contact_phone, user_id)
VALUES (:organization_name, :contact_email, :contact_phone, :user_id)
");
return $stmt->execute([
':organization_name' => $data['organization_name'] ?? 'N/A',
':contact_email' => $data['contact_email'] ?? null,
':contact_phone' => $data['contact_phone'] ?? null,
':user_id' => $data['user_id'] ?? null
]);
}

    // Get a responder by ID
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM responders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Optionally, get all responders
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM responders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAssignedIncidents(int $responderId): array
{
   $stmt = $this->pdo->prepare("
    INSERT INTO escalations (
        incident_id,
        responder_id,
        responder,
        description,
        escalated_by
    )
    VALUES (
        :incident_id,
        :responder_id,
        :responder,
        :description,
        :staff_id
    )
");

$stmt->execute([
    ':incident_id'  => $incidentId,
    ':responder_id' => $responderUser['id'],      // ✅ CRITICAL
    ':responder'    => $responderUser['username'],// optional (denormalized)
    ':description'  => $description,
    ':staff_id'     => $staffId
]);
}   


public function updateByUserId(int $userId, string $organizationName)
{
    $stmt = $this->pdo->prepare("
        UPDATE responders
        SET organization_name = :orgName
        WHERE user_id = :userId
    ");
    return $stmt->execute([
        ':orgName' => $organizationName,
        ':userId'  => $userId
    ]);
}

public function getAllResponders(): array
{
    $stmt = $this->pdo->prepare("
        SELECT 
            r.id,
            r.organization_name,
            r.contact_email,
            r.contact_phone,
            r.created_at,
            u.username,
            u.status
        FROM responders r
        LEFT JOIN users u ON r.user_id = u.id
        ORDER BY r.created_at DESC
    ");

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}