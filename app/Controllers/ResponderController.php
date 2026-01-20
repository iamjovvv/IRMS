<?php

require_once BASE_PATH . '/app/Core/BaseController.php';

class ResponderController extends BaseController
{
  
    public function responderDashboard(){
        $this->view('responder/responder-dashboard', [
            'page_title' => 'External Responder Dashboard',
            'page_css' => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'pages/page.css',
                'components/card.css',
                'layouts/grid.css'
            ],
            'page_js' => [
                'sidebar.js'
            ]
        ]);
    
    }

    public function actionForm(){
        $this->view('responder/action-form', [
            'page_title' => 'Action Form',
            'page_css'   => [
                'topnavbar.css',
                'sidebar.css',
                'base/typography.css',
                'components/button.css',
                'pages/page.css',
                'components/form.css',
                'layouts/form-layout.css'

            ],
            'page_js' => [
                'sidebar.js'
            ]
        ]);
    }



}

?>