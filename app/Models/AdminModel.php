<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

class AdminModel extends BaseModel
{
    // protected PDO $pdo;


// public function __construct(PDO $pdo)
// {
// $this->pdo = $pdo;
// }

// public function getAllStaff()
// {
//     return $this->pdo->query("SELECT s.*, u.id as user_id, u.status, u.username, 'staff' as role FROM staff s JOIN users u ON u.id = s.user_id")->fetchAll();
// }

// public function getAllReporters()
// {
//     return $this->pdo->query("SELECT r.*, u.id as user_id, u.status, u.username, 'reporter' as role FROM reporters r JOIN users u ON u.id = r.user_id")->fetchAll();
// }

// public function getAllResponders()
// {
//     return $this->pdo->query("SELECT rs.*, u.id as user_id, u.status, u.username, 'responder' as role FROM responders rs JOIN users u ON u.id = rs.user_id")->fetchAll();
// }

// Get all staff


public function getFilteredIncidents(string $category = '', string $status = ''): array
{
    $query = "SELECT * FROM incidents WHERE 1=1";
    $params = [];

    if ($category) {
        $query .= " AND category = :category";
        $params[':category'] = $category;
    }

    if ($status) {
        $query .= " AND status = :status";
        $params[':status'] = $status;
    }

    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function findUserById(int $id)
{
    $stmt = $this->pdo->prepare("
        SELECT 
            u.id AS user_id,
            u.username,
            u.status,
            u.role,
            u.created_at,

            s.staff_id,

            r.org_id_number,
            r.phone,
            r.auth_method,

            re.organization_name,
            re.contact_email,
            re.contact_phone

        FROM users u
        LEFT JOIN staff s ON s.user_id = u.id
        LEFT JOIN reporters r ON r.user_id = u.id
        LEFT JOIN responders re ON re.user_id = u.id
        WHERE u.id = :id
        LIMIT 1
    ");

    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



public function getReportsByStatus(string $status): int
{
$stmt = $this->pdo->prepare("SELECT COUNT(*) FROM incidents WHERE status = :status");
$stmt->execute(['status' => $status]);
return (int) $stmt->fetchColumn();
}



public function getAccountsByRole(string $role): array
{
    switch (strtolower($role)) {
    case 'staff':
    return $this->getAllStaff();
    case 'responder':
    return $this->getAllResponders();
    case 'reporter':
    default:
    return $this->getAllReporters();
    }
}


public function getAllStaff()
{
    $stmt = $this->pdo->prepare("
        SELECT 
            u.id AS user_id, 
            u.username, 
            u.status,
            s.staff_id,
            u.created_at
        FROM staff s
        JOIN users u ON s.user_id = u.id
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Get all reporters
// public function getAllReporters()
// {
//     $stmt = $this->pdo->prepare("
//     SELECT
//     r.id AS user_id,
//     r.user_id AS account_id,
//     r.auth_method,
//     r.org_id_number,
//     r.phone,
//     u.username,
//     u.status
//     FROM reporters r
//     JOIN users u ON r.user_id = u.id
//     ");
//     $stmt->execute();
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }





// public function getAllReporters(): array
// {
//     $stmt = $this->pdo->prepare("
//         SELECT
//             r.user_id,
//             r.auth_method,
//             r.org_id_number,
//             r.phone,
//             u.username,
//             u.status
//         FROM reporters r
//         JOIN users u ON r.user_id = u.id
//         WHERE r.id = (
//             SELECT MIN(r2.id)
//             FROM reporters r2
//             WHERE r2.user_id = r.user_id
//             )
       
//     ");
//     $stmt->execute();
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }


public function getAllReporters(): array
{
    $stmt = $this->pdo->prepare("
        SELECT
            r.user_id,
            u.username,
            u.status,
            r.auth_method,
            r.org_id_number,
            r.phone,
            u.created_at
        FROM reporters r
        JOIN users u ON r.user_id = u.id
        WHERE r.id = (
            SELECT MIN(r2.id)
            FROM reporters r2
            WHERE r2.user_id = r.user_id
        )
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}








// public function getAllResponders(): array
// {
//     $stmt = $this->pdo->prepare("
//     SELECT
//         u.id AS user_id,
//         u.username,
//         u.status,
//         res.organization_name,
//         res.contact_email,
//         res.contact_phone
//     FROM users u
//     INNER JOIN responders res ON u.id = res.user_id
//     WHERE u.role = 'responder'
//     ");
//     $stmt->execute();
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

public function getAllResponders(): array
{
    $stmt = $this->pdo->prepare("
        SELECT
            u.id AS user_id,
            u.username,
            u.status,
            res.organization_name,
            res.contact_email,
            res.contact_phone,
            u.created_at
        FROM users u
        INNER JOIN responders res ON u.id = res.user_id
        WHERE u.role = 'responder'
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function getTotalReports(): int
{
    return (int) $this->pdo
    ->query("SELECT COUNT(*) FROM incidents")
    ->fetchColumn();
}





public function getTotalServedReports(): int
{
    $stmt = $this->pdo->prepare("
    SELECT COUNT(*)
    FROM incidents
    WHERE status IN ('resolved', 'validated')
    ");
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}




public function getTotalStaff(): int
{
    $stmt = $this->pdo->prepare("
    SELECT COUNT(*)
    FROM users
    WHERE role = 'staff'
    ");
    $stmt->execute();
    return (int) $stmt->fetchColumn();
}


public function getTotalResponders(): int
{
return (int) $this->pdo
->query("SELECT COUNT(*) FROM responders")
->fetchColumn();
}


public function getTotalReporterOrgId(): int
{
    $stmt = $this->pdo->prepare("
    SELECT COUNT(*)
    FROM reporters
    WHERE auth_method = 'org_id'
    AND org_id_number IS NOT NULL
");
$stmt->execute();
return (int) $stmt->fetchColumn();
}


public function getAllReports(?string $status = null): array
{
    if ($status) {
    $stmt = $this->pdo->prepare("
    SELECT *
    FROM incidents
    WHERE status = :status
    ORDER BY created_at DESC
    ");
    $stmt->execute(['status' => $status]);
    } else {
    $stmt = $this->pdo->query("SELECT * FROM incidents ORDER BY created_at DESC");
    }


    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function getReportsByCategory(string $category): int
{
    $statusMap = [
        'pending' => ['new', 'under-review', 'ongoing'],
        'resolved' => ['resolved', 'validated'],
        'rejected' => ['invalidated'],
        'escalated' => ['escalated']
    ];

    if (!isset($statusMap[$category])) {
        return 0;
    }

    $placeholders = implode(',', array_fill(0, count($statusMap[$category]), '?'));
    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) FROM incidents WHERE status IN ($placeholders)
    ");
    $stmt->execute($statusMap[$category]);
    return (int) $stmt->fetchColumn();
}



public function updateUser(int $userId, string $username, string $password = '', string $role = '')
{
    $fields = ['username' => $username];
    
    if ($password !== '') {
        $fields['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
    }

    if ($role !== '') {
        $fields['role'] = $role;
    }

    $setPart = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
    $fields['id'] = $userId;

    $stmt = $this->pdo->prepare("UPDATE users SET $setPart WHERE id = :id");
    return $stmt->execute($fields);
}


// public function getAllReports(?string $status = null)
// {
// if ($status) {
// $stmt = $this->pdo->prepare("SELECT * FROM incidents WHERE status = :status ORDER BY created_at DESC");
// $stmt->execute(['status' => $status]);
// } else {
// $stmt = $this->pdo->query("SELECT * FROM incidents ORDER BY created_at DESC");
// }
// return $stmt->fetchAll();
// }



}


?>