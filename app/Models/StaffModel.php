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
}


?>