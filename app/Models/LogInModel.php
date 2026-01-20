<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

class LogInModel extends BaseModel
{

    function authenticate(string $username, string $password)
    {
        $stmt = $this->pdo->prepare("
            SELECT id,
                username,
                password,
                role
                FROM users
                WHERE username = :username
                LIMIT 1          
        ");

        $stmt->execute([
            ':username' => $username
           
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false; // user not found
        }

        if (!password_verify($password, $user['password'])) {
            return false; // wrong password
        }

        return $user; // login success
    }


}


?>