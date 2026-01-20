<?php
class BaseController 
{
     protected function view(string $view, array $data = [])
    {
        extract($data);

        require BASE_PATH . '/app/Views/layouts/header.php';
        require BASE_PATH . '/app/Views/' . $view . '.php';
        require BASE_PATH . '/app/Views/layouts/footer.php';
    }
}

?>