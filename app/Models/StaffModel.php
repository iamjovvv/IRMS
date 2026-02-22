<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

class StaffModel extends BaseModel
{
    public function create(array $data)
    {
        

        $stmt = $this->pdo->prepare("
            INSERT INTO staff (user_id, position, office)
            VALUES (:uid, :pos, :off)
        ");

        $stmt->execute([
            ':uid' => $data['user_id'],
            ':pos' => $data['position'],
            ':off' => $data['office']
        ]);
    }

    public function updateByUserId(int $userId, string $position, string $office)
{
    $stmt = $this->pdo->prepare("
        UPDATE staff
        SET staff_id = :position, office = :office
        WHERE user_id = :userId
    ");
    return $stmt->execute([
        ':position' => $position,
        ':office'   => $office,
        ':userId'   => $userId
    ]);
}

    
}


?>