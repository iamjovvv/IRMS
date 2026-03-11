<?php
require_once BASE_PATH . '/app/core/BaseModel.php';

class ResponderModel extends BaseModel
{
    public function create(array $data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO responders (organization_name, contact_email, contact_phone, user_id)
            VALUES (:organization_name, :contact_email, :contact_phone, :user_id)
        ");
        return $stmt->execute([
            ':organization_name' => $data['organization_name'] ?? 'N/A',
            ':contact_email'     => $data['contact_email']     ?? null,
            ':contact_phone'     => $data['contact_phone']     ?? null,
            ':user_id'           => $data['user_id']           ?? null
        ]);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM responders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // FIX: was missing entirely — controller calls this on editUser
    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM responders WHERE user_id = :user_id LIMIT 1
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM responders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // FIX: was only updating organization_name and had wrong signature (string, not array)
    public function updateByUserId(int $userId, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE responders
            SET organization_name = :organization_name,
                contact_email     = :contact_email,
                contact_phone     = :contact_phone
            WHERE user_id = :user_id
        ");
        return $stmt->execute([
            ':organization_name' => $data['organization_name'] ?? null,
            ':contact_email'     => $data['contact_email']     ?? null,
            ':contact_phone'     => $data['contact_phone']     ?? null,
            ':user_id'           => $userId
        ]);
    }

    public function getAllResponders(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                r.id,
                r.organization_name,
                r.contact_email,
                r.contact_phone,
                r.created_at,
                u.username,
                u.status
            FROM responders r
            LEFT JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}