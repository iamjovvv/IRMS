<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

class RemarkModel extends BaseModel
{
   

    // Get all remarks for an incident
    public function getByIncident(int $incidentId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.role
            FROM remarks r
            JOIN users u ON u.id = r.sender_id
            WHERE r.incident_id = :incident_id
            ORDER BY r.created_at ASC
        ");

        $stmt->execute([
            ':incident_id' => $incidentId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new remark
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO remarks (
                incident_id, sender_id, 
                message
                )

            VALUES (
                :incident_id, :sender_id, 
                :message
                )
        ");

        return $stmt->execute([
            ':incident_id' => $data['incident_id'],
            ':sender_id'   => $data['sender_id'],
            ':message'     => $data['message']
        ]);
    }
}
