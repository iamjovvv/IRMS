<?php

require_once BASE_PATH . '/app/core/BaseModel.php';

class UserModel extends BaseModel
{
    public function authenticate(string $username, string $password)
    {
        

        $stmt = $this->pdo->prepare("
            SELECT id, password_hash, role, status
            FROM users
            WHERE username = :username 
            LIMIT 1
        ");

        $stmt->execute([
            ':username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['status'] !== 'active') {
            return false;
        }

         if (!password_verify($password, $user['password_hash'])) {
            return false;
        }
        
         return [
            'id'   => $user['id'],
            'role' => $user['role']
        ];
    }


    
    public function createUser(array $data)
    {
       

        $stmt = $this->pdo->prepare("
            INSERT INTO users (
                username, password_hash, role

            ) VALUES (
                :username, :password_hash, :role

            )
        ");

        $stmt->execute([
            ':username' => $data['username'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role']
        ]);

        return $this->pdo->lastInsertId();
    }



    public function getAllUsers()
    {
      
        return $this->pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
    }



      public function create(array $data)
    {
       

        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, password_hash, role, status)
            VALUES (:u, :p, :r, 'active')
        ");

        $stmt->execute([
            ':u' => $data['username'],
            ':p' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':r' => $data['role']
        ]);

        return $this->pdo->lastInsertId();
    }

    public function exists(string $username): bool
    {
        

        $stmt = $this->pdo->prepare(
            "SELECT id FROM users WHERE username = :u"
        );
        $stmt->execute([':u' => $username]);

        return (bool) $stmt->fetch();
    }



    public function getResponders(): array
{
    $stmt = $this->pdo->prepare("
        SELECT id, username
        FROM users
        WHERE role= 'responder' AND status = 'active'
        ORDER BY username ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function findById(int $id)
{
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}




?>