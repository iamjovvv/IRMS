<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- ICONS -->
        <link
                rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
                integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
                crossorigin="anonymous"
                referrerpolicy="no-referrer"
            />

        <link rel="stylesheet" 
              href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
              <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


        <!-- Pages CSS -->
        <?php if (!empty($page_css)): ?>

            <?php foreach ($page_css as $css): ?>
                <link rel="stylesheet" href= "/RMS/assets/css/<?=$css ?>">
            <?php endforeach; ?>

        <?php endif; ?>

    </head>



<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get logged-in user if any
$user = $_SESSION['user'] ?? null;
?>

<body class="<?= $body_class ?? 'layout' ?>">

<!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal__content">
            <h3 class="modal__title">Confirm Logout</h3>
            <p>Are you sure you want to log out?</p>
            
            <div class="modal__actions">
            <button id="confirmLogout" class="btn btn--yes  ">Yes, Log Out</button>
            <button id="cancelLogout" class="btn btn--cancel">Cancel</button>
            </div>
        </div>
    </div>

    <nav class="navbar navbar--top">


        <div class="navbar__logo">
    <a href="/RMS/public/index.php">
        <img src="/RMS/assets/img/uep_logo.png" alt="UEP Logo">
    </a>
</div>

        <ul class="navbar__menu">
            <?php
            $homeUrl = '/RMS/public/index.php'; // default (not logged in)
            if ($user) {
                $role = $_SESSION['user']['role'] ?? null;
                switch ($role) {
                    case 'staff':
                        $homeUrl = '/RMS/public/index.php?url=staff/dashboard';
                        break;
                    case 'responder':
                        $homeUrl = '/RMS/public/index.php?url=responder/dashboard';
                        break;
                    case 'admin':
                        $homeUrl = '/RMS/public/index.php?url=admin/dashboard';
                        break;
                }
            }
            ?>

            <li class="navbar__sep">
                <a href="<?= $homeUrl ?>">Home</a>
            </li>
            
            <li class="navbar__item">|</li>

            <?php if ($user): ?>
                <li class="navbar__item">
                    <span class="navbar__welcome">
                        Welcome, <?= htmlspecialchars($user['username']) ?>!
                    </span>
                </li>
                <li class="navbar__item">|</li>

                <!-- 🔔 Bell goes here, only for logged-in users -->
                <?php if (!empty($_SESSION['user']['id'])): ?>
                <li class="navbar__item">
                    <?php
                    global $pdo;
                    $notifModel  = new NotificationModel($pdo);
                    $userId      = (int) $_SESSION['user']['id'];
                    $unreadCount = $notifModel->getUnreadCount($userId);
                    $notifs      = $notifModel->getUnread($userId);
                    ?>
                    <div class="notif-bell" id="notifBell">
                        <span class="notif-icon">🔔</span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="notif-badge"><?= $unreadCount ?></span>
                        <?php endif; ?>
                        <div class="notif-dropdown" id="notifDropdown">
                            <?php if (empty($notifs)): ?>
                                <p class="notif-empty">No new notifications</p>
                            <?php else: ?>
                                <?php foreach ($notifs as $n): ?>
                                    <a class="notif-item <?= $n['is_read'] ? '' : 'unread' ?>"
                                    href="/RMS/public/index.php?url=notifications/markRead&id=<?= (int)$n['id'] ?>&incident=<?= htmlspecialchars($n['tracking_code'] ?? '') ?>">
                                        <strong><?= htmlspecialchars($n['title']) ?></strong>
                                        <span><?= htmlspecialchars($n['message']) ?></span>
                                        <small><?= $n['created_at'] ?></small>
                                    </a>
                                <?php endforeach; ?>
                                <a href="/RMS/public/index.php?url=notifications/markAllRead" class="notif-mark-all">
                                    ✓ Mark all as read
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                <li class="navbar__item">|</li>
                <?php endif; ?>

                <li class="navbar__item">
                    <a class="navbar__link navbar__link--bold"
                    href="/RMS/public/index.php?url=logout"
                    id="logoutBtn">
                        Logout
                    </a>
                </li>
            <?php else: ?>
                <li class="navbar__item">
                    <a class="navbar__link navbar__link--bold"
                    href="/RMS/public/index.php?url=login">
                        Login
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        

    </nav>


<style>
/* Modal overlay */
.modal {
    display: flex;        
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 10000;

    /* Hidden state */
    opacity: 0;
    pointer-events: none;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.modal.visible {
    opacity: 1;
    pointer-events: all;
    visibility: visible;
}


/* Modal content card */
.modal__content {
    background: #ffffff;
    padding: 40px 30px;
    border-radius: 14px;
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    transform: translateY(-20px);
    transition: transform 0.3s ease;
}

.modal.visible .modal__content {
    transform: translateY(0);
}


/* Icon at top */
.modal__icon {
    font-size: 36px;
    color: #dc3545; /* red for logout */
    margin-bottom: 15px;
}

/* Title */
.modal__title {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 8px;
}

/* Text */
.modal__text {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
}

/* Actions (buttons) */
/* Actions (buttons) */
.modal__actions {
display: flex;
justify-content: center;
gap: 15px;
margin-top: 30px; /* <-- add this line to push buttons down */
}

/* Buttons */
.btn {
    padding: 10px 30px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn--yes {
    background-color: #dc3545;
    color: #fff;
}

.btn--yes:hover {
    background-color: #c82333;
}

.btn--cancel {
    background-color: #f1f1f1;
    color: #333;
}

.btn--cancel:hover {
    background-color: #e2e2e2;
}

.notif-bell     { position: relative; cursor: pointer; padding: 8px 12px; display: inline-block; }
.notif-badge    { background: #e53935; color: #fff; border-radius: 50%; padding: 1px 6px; font-size: 11px; position: absolute; top: 2px; right: 4px; font-weight: bold; }
.notif-dropdown { display: none; position: absolute; right: 0; top: 44px; width: 320px; background: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 6px 24px rgba(0,0,0,0.15); z-index: 9999; max-height: 420px; overflow-y: auto; }
.notif-item     { display: block; padding: 12px 16px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: #333; font-size: 13px; line-height: 1.5; }
.notif-item.unread { background: #e8eaf6; border-left: 3px solid #1a237e; }
.notif-item strong { display: block; margin-bottom: 2px; font-size: 13px; }
.notif-item span   { display: block; color: #555; font-size: 12px; }
.notif-item small  { color: #aaa; font-size: 11px; }
.notif-empty    { padding: 20px; text-align: center; color: #999; margin: 0; }
.notif-mark-all { display: block; text-align: center; padding: 10px; font-size: 12px; color: #1a237e; text-decoration: none; border-top: 1px solid #eee; }
.notif-mark-all:hover { background: #f5f6fa; }
</style>


<script>

    document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogout = document.getElementById('cancelLogout');
    const confirmLogout = document.getElementById('confirmLogout');

    if (!logoutBtn || !logoutModal) return;

    // Show modal
    logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        logoutModal.classList.add('visible');      // ← was: remove('hidden')
    });

    // Cancel logout
    cancelLogout.addEventListener('click', () => {
        logoutModal.classList.remove('visible');   // ← was: add('hidden')
    });

    // Confirm logout
    confirmLogout.addEventListener('click', () => {
        window.location.href = '/RMS/public/index.php?url=logout';
    });

    // Optional: close modal by clicking outside content
    logoutModal.addEventListener('click', (e) => {
        if (e.target === logoutModal) {
            logoutModal.classList.remove('visible');    
        }
    });
});

</script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const bell = document.getElementById('notifBell');
    const drop = document.getElementById('notifDropdown');

    if (bell && drop) {
        bell.addEventListener('click', function(e) {
            e.stopPropagation();
            drop.style.display = drop.style.display === 'flex' ? 'none' : 'flex';
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#notifBell')) {
                drop.style.display = 'none';
            }
        });
    }
});
</script>