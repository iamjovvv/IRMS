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


        <!-- Pages CSS -->
        <?php if (!empty($page_css)): ?>

            <?php foreach ($page_css as $css): ?>
                <link rel="stylesheet" href= "/RMS/assets/css/<?=$css ?>">
            <?php endforeach; ?>

        <?php endif; ?>

    </head>

    
    
    <body class="<?= $body_class ?? 'layout' ?>">

        <nav class="navbar navbar--top">

            <div class="navbar__logo">LOGO</div>

            <ul class="navbar__menu">

                <li class="navbar__item">

                    <a href="/RMS/public/index.php">Home</a>

                </li>


                <li class="navbar__item">|</li>

                <li class="navbar__item">

                   <a class="navbar__link navbar__link--bold"
                    href="/RMS/public/index.php?url=login" >Login</a>
                    
                </li>

            </ul>

        </nav>

    
    
