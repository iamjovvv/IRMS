<?php
class NotificationController extends BaseController
{
    public function markRead()
    {
        $notifId      = (int) ($_GET['id'] ?? 0);
        $trackingCode = $_GET['incident'] ?? null;
        $userId       = $_SESSION['user']['id'] ?? null;

        if (!$userId) {
            header("Location: /RMS/public/index.php?url=login");
            exit;
        }

        $model = new NotificationModel(); // ← removed $this->pdo
        $model->markRead($notifId, $userId);

        if ($trackingCode) {
        $role = $_SESSION['user']['role'] ?? 'staff';

        $redirectMap = [
            'staff'     => "staff/reviewIncident&code={$trackingCode}",
            'admin'     => "admin/viewIncident&code={$trackingCode}",
            'responder' => "responder/viewAssigned&code={$trackingCode}",
        ];

        $url = $redirectMap[$role] ?? "staff/viewReport&code={$trackingCode}";
        header("Location: /RMS/public/index.php?url={$url}");
        } else {
            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/RMS/public/index.php'));
        }
        exit;
    }

    public function markAllRead()
    {
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$userId) {
            header("Location: /RMS/public/index.php?url=login");
            exit;
        }

        $model = new NotificationModel(); // ← removed $this->pdo
        $model->markAllRead($userId);

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/RMS/public/index.php'));
        exit;
    }
}