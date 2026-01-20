<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

class ReporterModel extends BaseModel
{
    public function createReporter(array $data)
    {
      

        $stmt = $this->pdo->prepare("
            INSERT INTO reporters (
                auth_method,
                user_id,
                phone
            ) VALUES (
                :auth_method,
                :user_id,
                :phone
            )
        ");

        $stmt->execute([
            ':auth_method' => $data['auth_method'],
            ':user_id' => $data['user_id'],
            ':phone' => $data['phone'],
        ]);

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
}

?>