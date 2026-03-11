<?php
require_once BASE_PATH . '/app/core/BaseModel.php';

class AdminModel extends BaseModel
{
    // FIX: was (int $userId, string $username, string $password, string $role)
    // Controller calls updateUser($userId, ['username' => ..., 'password_hash' => ...])
    // Now accepts an array of only the fields that need updating
    public function updateUser(int $userId, array $data): bool
    {
        $allowed = ['username', 'password_hash', 'status', 'role'];
        $fields  = array_filter(
            $data,
            fn($key) => in_array($key, $allowed),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($fields)) return false;

        $setPart        = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
        $fields['id']   = $userId;

        $stmt = $this->pdo->prepare("UPDATE users SET $setPart WHERE id = :id");
        return $stmt->execute($fields);
    }


    // public function findUserById(int $id)
    // {
    //     $stmt = $this->pdo->prepare("
    //         SELECT 
    //             u.id AS user_id,
    //             u.username,
    //             u.status,
    //             u.role,
    //             u.created_at,

    //             s.staff_id,
    //             s.position,
    //             s.office,

    //             r.org_id_number,
    //             r.phone,
    //             r.auth_method,

    //             re.organization_name,
    //             re.contact_email,
    //             re.contact_phone

    //         FROM users u
    //         LEFT JOIN staff      s  ON s.user_id  = u.id
    //         LEFT JOIN reporters  r  ON r.user_id  = u.id
    //         LEFT JOIN responders re ON re.user_id = u.id
    //         WHERE u.id = :id
    //         LIMIT 1
    //     ");
    //     $stmt->execute(['id' => $id]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }


    public function findUserById(int $userId): ?array
{
    //  var_dump('findUserById called with: ' . $userId);; 

    // Base user query
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) return null;

    // Join role-specific data
    switch ($user['role']) {
        case 'reporter':
            $stmt = $this->pdo->prepare("SELECT * FROM reporters WHERE user_id = ?");
            $stmt->execute([$userId]);
            $details = $stmt->fetch(PDO::FETCH_ASSOC);
            break;

        case 'staff':
            $stmt = $this->pdo->prepare("SELECT * FROM staff WHERE user_id = ?");
            $stmt->execute([$userId]);
            $details = $stmt->fetch(PDO::FETCH_ASSOC);
            break;

        case 'responder':
            $stmt = $this->pdo->prepare("SELECT * FROM responders WHERE user_id = ?");
            $stmt->execute([$userId]);
            $details = $stmt->fetch(PDO::FETCH_ASSOC);
            break;

        default:
            $details = [];
    }

    return array_merge($user, $details ?? []);
}




    public function getFilteredIncidents(string $category = '', string $status = ''): array
    {
        $query  = "SELECT * FROM incidents WHERE 1=1";
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

    public function getAccountsByRole(string $role): array
    {
        switch (strtolower($role)) {
            case 'staff':     return $this->getAllStaff();
            case 'responder': return $this->getAllResponders();
            default:          return $this->getAllReporters();
        }
    }

    public function getAllStaff(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                u.id AS user_id,
                u.username,
                u.status,
                s.staff_id,
                s.position,
                s.office,
                u.created_at
            FROM staff s
            JOIN users u ON s.user_id = u.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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
                SELECT MIN(r2.id) FROM reporters r2 WHERE r2.user_id = r.user_id
            )
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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
        return (int) $this->pdo->query("SELECT COUNT(*) FROM incidents")->fetchColumn();
    }

    public function getTotalServedReports(): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM incidents WHERE status IN ('resolved', 'validated')
        ");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getTotalStaff(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'staff'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getTotalResponders(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM responders")->fetchColumn();
    }

    public function getTotalReporterOrgId(): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM reporters
            WHERE auth_method = 'org_id' AND org_id_number IS NOT NULL
        ");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getAllReports(?string $status = null): array
    {
        if ($status) {
            $stmt = $this->pdo->prepare("
                SELECT * FROM incidents WHERE status = :status ORDER BY created_at DESC
            ");
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM incidents ORDER BY created_at DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportsByStatus(string $status): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM incidents WHERE status = :status");
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    public function getReportsByCategory(string $category): int
    {
        $statusMap = [
            'pending'   => ['new', 'under-review', 'ongoing'],
            'resolved'  => ['resolved', 'validated'],
            'rejected'  => ['invalidated'],
            'escalated' => ['escalated']
        ];

        if (!isset($statusMap[$category])) return 0;

        $placeholders = implode(',', array_fill(0, count($statusMap[$category]), '?'));
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM incidents WHERE status IN ($placeholders)
        ");
        $stmt->execute($statusMap[$category]);
        return (int) $stmt->fetchColumn();
    }


    public function getMonthlyReports(int $year = null): array
{
    $year = $year ?? (int) date('Y');

    $stmt = $this->pdo->prepare("
        SELECT 
            MONTH(created_at)  AS month_num,
            COUNT(*)           AS total,
            SUM(status = 'ongoing')     AS ongoing,
            SUM(status = 'resolved')    AS resolved,
            SUM(status = 'invalidated') AS rejected,
            SUM(status = 'escalated')   AS escalated
        FROM incidents
        WHERE YEAR(created_at) = :year
        GROUP BY MONTH(created_at)
        ORDER BY MONTH(created_at) ASC
    ");
    $stmt->execute([':year' => $year]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fill all 12 months (so months with 0 reports still appear)
    $months = [];
    for ($m = 1; $m <= 12; $m++) {
        $months[$m] = [
            'month_num' => $m,
            'total'     => 0,
            'ongoing'   => 0,
            'resolved'  => 0,
            'rejected'  => 0,
            'escalated' => 0,
        ];
    }

    foreach ($rows as $row) {
        $months[(int)$row['month_num']] = $row;
    }

    return array_values($months);
}

}