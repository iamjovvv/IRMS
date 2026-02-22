<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

class LogInModel extends BaseModel
{

    function authenticate(string $username, string $password)
    {
        $stmt = $this->pdo->prepare("
            SELECT id,
                    username,
                    password_hash,
                    role,
                    status
                FROM users
                WHERE username = :username
                LIMIT 1          
        ");
        
        $stmt->execute([
            ':username' => $username
           
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        //  var_dump($username);
        //  var_dump($password);
        //  var_dump($user);

        // if ($user) {
        //         var_dump(password_verify($password, $user['password_hash']));
        //     }

        // exit;
        // }

        if (!$user) {
            return false; // user not found
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false; // wrong password
        }

        return $user; // login success
    }


}


?>