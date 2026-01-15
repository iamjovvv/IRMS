<?php


$_SESSION['role'] = 'staff';

require_once BASE_PATH . '/app/core/auth.php';

$menus = require BASE_PATH . '/app/config/sidebar.menu.php';
$role = $_SESSION['role'] ?? null;

if(!$role || !isset($menus[$role])){
    return; //no sidebar
}

$currentPage = basename($_SERVER['PHP_SELF']);
$roleMenu = $menus[$role];

?>

<aside class="sidebar" id="sidebar">

    <button type="button" class="sidebar__toggle" id="sidebarToggle" aria-label= "Toggle sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

   <ul class="sidebar__menu">
        <?php foreach ($roleMenu as $item):  ?>

            
            <li class="sidebar__item">

            <a class="sidebar__link" href= "<?= $item['link'] ?>" >

                <i class= "<?= $item['icon'] ?>"></i>

                <span class="sidebar__label"><?= $item['label'] ?></span>

            </a>

            </li>

        <?php endforeach; ?>

   </ul>
    
</aside>




<!-- ASIDE, creates sidebar navigation menu -->