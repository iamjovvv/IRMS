<?php

class NotificationModel extends BaseModel
{
    // Notify all users of a specific role
    public function notifyByRole(string $role, array $data): void
    {
        $users = $this->pdo->prepare("
            SELECT id FROM users WHERE role = :role AND status = 'active'
        ");
        $users->execute([':role' => $role]);

        $stmt = $this->pdo->prepare("
            INSERT INTO notifications (user_id, type, title, message, incident_id)
            VALUES (:user_id, :type, :title, :message, :incident_id)
        ");

        foreach ($users->fetchAll(PDO::FETCH_ASSOC) as $user) {
            $stmt->execute([
                ':user_id'     => $user['id'],
                ':type'        => $data['type'],
                ':title'       => $data['title'],
                ':message'     => $data['message'],
                ':incident_id' => $data['incident_id'],
            ]);
        }
    }

    // Notify a specific user
    public function notifyUser(int $userId, array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO notifications (user_id, type, title, message, incident_id)
            VALUES (:user_id, :type, :title, :message, :incident_id)
        ");
        $stmt->execute([
            ':user_id'     => $userId,
            ':type'        => $data['type'],
            ':title'       => $data['title'],
            ':message'     => $data['message'],
            ':incident_id' => $data['incident_id'],
        ]);
    }

    // Get unread notifications for a user
    public function getUnread(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT n.*, i.tracking_code
            FROM notifications n
            LEFT JOIN incidents i ON i.id = n.incident_id
            WHERE n.user_id = :user_id AND n.is_read = 0
            ORDER BY n.created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get unread count
    public function getUnreadCount(int $userId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM notifications
            WHERE user_id = :user_id AND is_read = 0
        ");
        $stmt->execute([':user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    // Mark all as read for a user
    public function markAllRead(int $userId): void
    {
        $this->pdo->prepare("
            UPDATE notifications SET is_read = 1 WHERE user_id = :user_id
        ")->execute([':user_id' => $userId]);
    }

    // Mark one as read
    public function markRead(int $notifId, int $userId): void
    {
        $this->pdo->prepare("
            UPDATE notifications SET is_read = 1
            WHERE id = :id AND user_id = :user_id
        ")->execute([':id' => $notifId, ':user_id' => $userId]);
    }
}