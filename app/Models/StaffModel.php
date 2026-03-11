<?php
require_once BASE_PATH . '/app/core/BaseModel.php';

class StaffModel extends BaseModel
{
    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO staff (user_id, staff_id, position, office)
            VALUES (:user_id, :staff_id, :position, :office)
        ");
        $stmt->execute([
            ':user_id'  => $data['user_id'],
            ':staff_id' => $data['staff_id'] ?? null,
            ':position' => $data['position'] ?? null,
            ':office'   => $data['office']   ?? null
        ]);
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM staff WHERE user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateByUserId(int $userId, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE staff
            SET staff_id = :staff_id,
                position = :position,
                office   = :office
            WHERE user_id = :user_id
        ");
        return $stmt->execute([
            ':staff_id' => $data['staff_id'] ?? null,
            ':position' => $data['position'] ?? null,
            ':office'   => $data['office']   ?? null,
            ':user_id'  => $userId
        ]);
    }
}
?>