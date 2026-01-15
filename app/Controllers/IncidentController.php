<?php

class IncidentController
{
   public function create()
   {
    $page_title = 'Incident Form';
    $page_css = [
        'topnavbar.css',
        'base/typography.css',
        'components/button.css',
        'components/form.css',
        'layouts/form.css',
        'layouts/form-layout.css',
        'pages/page.css'
    ];

    require '../app/Views/layouts/header.php';
    require '../app/Views/public/incident-form.php';
    require '../app/Views/layouts/footer.php';
   }

   public function store(){
        // handle POST submission later
   }

}

?>