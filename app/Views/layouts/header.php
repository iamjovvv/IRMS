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
    <div id="logoutModal" class="modal hidden">
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

            <li class="navbar__item">
                <a href="/RMS/public/index.php">Home</a>
            </li>
            

            <li class="navbar__item">|</li>


            <?php if ($user): ?>
                <!-- Logged-in state -->
                <li class="navbar__item">
                    <span class="navbar__welcome">
                        Welcome, <?= htmlspecialchars($user['username']) ?>!
                    </span>
                </li>


                <li class="navbar__item">|</li>

                <li class="navbar__item">
                    <a class="navbar__link navbar__link--bold" 
                       href="/RMS/public/index.php?url=logout"
                       id="logoutBtn">
                        Logout
                    </a>
                </li>
            <?php else: ?>
                <!-- Not logged-in state -->
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
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.modal:not(.hidden) {
    opacity: 1;
    pointer-events: all;
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

.modal:not(.hidden) .modal__content {
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
        logoutModal.classList.remove('hidden');
    });

    // Cancel logout
    cancelLogout.addEventListener('click', () => {
        logoutModal.classList.add('hidden');
    });

    // Confirm logout
    confirmLogout.addEventListener('click', () => {
        window.location.href = '/RMS/public/index.php?url=logout';
    });

    // Optional: close modal by clicking outside content
    logoutModal.addEventListener('click', (e) => {
        if (e.target === logoutModal) {
            logoutModal.classList.add('hidden');
        }
    });
});

</script>