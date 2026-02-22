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

     protected function debugLog(string $title, array $context = []): void
    {
        $log = "[" . date('Y-m-d H:i:s') . "] {$title}\n";
        foreach ($context as $key => $value) {
            $log .= "{$key}: " . print_r($value, true) . "\n";
        }
        $log .= "--------------------------\n";

        error_log($log);
    }

    
}



?>