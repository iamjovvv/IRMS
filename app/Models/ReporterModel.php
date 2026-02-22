<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

class ReporterModel extends BaseModel
{
    public function createReporter(array $data): int
    {
      

        $stmt = $this->pdo->prepare("
            INSERT INTO reporters (
                org_id_number,
                auth_method,
                user_id,
                phone
            ) VALUES (
                :org_id_number,
                :auth_method,
                :user_id,
                :phone
            )
        ");

        $stmt->execute([
            ':org_id_number' => $data['org_id_number'],
            ':auth_method' => $data['auth_method'],
            ':user_id' => $data['user_id'],
            ':phone' => $data['phone'],
        ]);
        return (int) $this->pdo->lastInsertId();

    }


  
    public function getByUserId(int $userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM reporters
            WHERE user_id = :user_id
            LIMIT 1
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getById(int $id)
{
    $stmt = $this->pdo->prepare("
        SELECT * FROM reporters
        WHERE id = :id
        LIMIT 1
        ");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



//     public function create(array $data)
// {
//     global $pdo;

//     $stmt= $pdo->prepare("
//         INSERT INTO reporters
//         (user_id, first_name, last_name, id_number, birthday, department)
//         VALUES
//         (:user_id, :first_name, :last_name, :id_number, birthday, :department)
    
//     ");
//     $stmt->execute([
//         ':user_id'    => $data['user_id'],
//         ':first_name' => $data['first_name'],
//         ':last_name'  => $data['last_name'],
//         ':id_number'  => $data['id_number'],
//         ':birthday'   => $data['birthday'],
//         ':department' => $data['department']
//     ]);

// }


    public function create(array $data)
    {
      

        $stmt = $this->pdo->prepare("
            INSERT INTO reporters (user_id, first_name, last_name, id_number)
            VALUES (:uid, :fn, :ln, :id)
        ");

        $stmt->execute([
            ':uid' => $data['user_id'],
            ':fn'  => $data['first_name'],
            ':ln'  => $data['last_name'],
            ':id'  => $data['id_number']
        ]);
    }


    public function getAssessmentByIncidentId(int $incidentId)
{
    $stmt = $this->pdo->prepare("
        SELECT 
            a.remarks,
            a.assessed_at,
            u.username AS staff_name
        FROM assessments a
        JOIN users u ON u.id = a.staff_id
        WHERE a.incident_id = :id
          AND a.validity = 'invalid'
        LIMIT 1
    ");
    $stmt->execute([':id' => $incidentId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function getIncidentActions(int $incidentId)
{
    $stmt = $this->pdo->prepare("
        SELECT 
            ia.action_taken,
            ia.status_update,
            ia.created_at,
            u.username,
            u.role
        FROM incident_actions ia
        LEFT JOIN users u ON u.id = ia.responder_id
        WHERE ia.incident_id = :id
        ORDER BY ia.created_at ASC
    ");
    $stmt->execute([':id' => $incidentId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getLatestIncidentAction(int $incidentId)
{
    $stmt = $this->pdo->prepare("
        SELECT 
            ia.created_at,
            ia.action_taken,
            u.username,
            u.role
        FROM incident_actions ia
        LEFT JOIN users u ON u.id = ia.responder_id
        WHERE ia.incident_id = :id
        ORDER BY ia.created_at DESC
        LIMIT 1
    ");
    $stmt->execute([':id' => $incidentId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateByUserId(int $userId, array $data): bool
{
    $stmt = $this->pdo->prepare("
        UPDATE reporters
        SET org_id_number = :org_id_number, phone = :phone
        WHERE user_id = :userId
    ");
    return $stmt->execute([
        ':org_id_number' => $data['org_id_number'] ?? null,
        ':phone'    => $data['phone'] ?? null,
        ':userId'   => $userId
    ]);
}


//  public function findByUserId(int $userId): ?array
//     {
//         $stmt = $this->pdo->prepare("
//             SELECT
//                 r.auth_method,
//                 r.org_id_number,
//                 r.phone,
//                 r.created_at
//             FROM reporters r
//             WHERE r.user_id = :uid
//             LIMIT 1
//         ");

//         $stmt->execute([':uid' => $userId]);
//         return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
//     }

public function findByUserId(int $userId)
{
    $stmt = $this->pdo->prepare("
        SELECT
            r.*,
            u.username,
            u.role
        FROM reporters r
        LEFT JOIN users u on u.id = r.user_id
        WHERE r.user_id = :user_id
        ORDER BY r.id ASC
        LIMIT 1
    ");
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}



public function findById(?int $id)
{
    if (!$id){
        return null;
    }

    $stmt = $this->pdo->prepare("
        SELECT
            r.*,
            u.username,
            u.role
        FROM reporters r
        LEFT JOIN users u ON u.id = r.user_id
        WHERE r.id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



}

?>