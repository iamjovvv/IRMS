<?php

class PublicController 
{

    function view($view, $data = []){
        extract($data);

        require BASE_PATH . '/app/Views/layouts/header.php';
        require BASE_PATH . '/app/Views/' . $view . '.php';
        require BASE_PATH . '/app/Views/layouts/footer.php';
}

    public function home() {
       
       $this->view('reporter/home', [
            'page_title' => 'Welcome to IRS',
            'page_css' => [
                'landingpage.css',
                'topnavbar.css'
            ]
       ]);
    }

    public function login()
    {
        $this->view('reporter/login', [
            'page_title' => 'Login',
            'page_css' => [
                'topnavbar.css',
                'login.css'
            ]
        ]);
    }

    public function reportForm() 
    {
      $this->view('reporter/incident-form', [
        'page_title' => 'Incident Form',
        'page_css' => [
            'topnavbar.css',
            'base/typography.css',
            'components/button.css',
            'components/form.css',
            'layouts/form-layout.css',
            'pages/page.css'
        ]
      ]);

    }

    public function trackStatus()
    {
        $this->view('reporter/track-status', [
            'page_title' => 'Track Status',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/form.css',
                'layouts/form-layout.css'
            ]
        ]);
           
    }

    public function sendWith()
    {
        $this->view('reporter/send-with', [
            'page_title' => 'Send with',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/form.css',
                'layouts/form-layout.css',
                'pages/page.css'
            ]
        ]);
    }

    public function addEvidence()
    {
        $this->view('reporter/add-evidence', [
            'page_title' => 'Add Evidence',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/button.css',
                'components/card.css',
                'pages/page.css'
            ]
        ]);
    }

    public function incidentSummary()
    {
        $this->view('reporter/incident-summary', [
            'page_title' => 'Incident Summary',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/card.css',
                'layouts/grid.css',
                'pages/page.css'
            ]
        ]);
    }

    public function reporterRemarks()
    {
        $this->view('reporter/reporter-remarks', [
            'page_title' => 'Reporter Remarks',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/remarks.css',
                'pages/page.css'

            ]
        ]);
    }

    public function reportStatus()
    {
        $this->view('reporter/report-status', [
            'page_title' => 'Report Status',
            'page_css' => [
                'topnavbar.css',
                'base/typography.css',
                'components/form.css',
                'layouts/form-layout.css',
                'pages/page.css'
            ]
        ]);
    }

    

    

}

?>