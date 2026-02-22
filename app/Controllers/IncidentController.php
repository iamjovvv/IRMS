<?php

require_once BASE_PATH . '/app/Models/IncidentModel.php';

require_once BASE_PATH . '/app/Models/IncidentMediaModel.php';

require_once BASE_PATH . '/app/core/BaseController.php';



class IncidentController extends BaseController
{


    public function track()
    {
        // show form(get)

        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            
            $this->view('reporter/track-status', [
                'page_title' => 'Track Status',
                'page_css'   => [
                    'topnavbar.css',
                    'base/typography.css',
                    'components/button.css',
                    'components/form.css',
                    'layouts/form-layout.css'

                ]

            ]);
            return;
        }


        // handle form submission(post)

        $code = trim($_POST['tracking_code'] ?? '');

        if($code === ''){
            $this->view('reporter/track-status', [
                'error' => 'Tracking code is required.'
            ]);
            return;
        }

            $model = new IncidentModel();
            $incident = $model->findByTrackingCode($code);

           if(!$incident) {
            $this->view('reporter/track-status', [
                'error' => 'No incident found for this tracking.'
            ]);
            return;
           }

        //    success->redirect to summary page

    //    header('Location: /RMS/public/index.php?url=incident/summary&code=' . urlencode($code));
     header('Location: /RMS/public/index.php?url=incident/summary&code=' . $code);
    exit;

      
    }


    

    public function summary()
    {
        $code = $_GET['code'] ?? '';

        if($code === ''){
            die('Invalid tracking code.');
        }

        $incidentModel = new IncidentModel();
        $incident = $incidentModel->findByTrackingCode($code);

        if (!$incident) {
            die('Incident not found.');
        }


        $mediaModel = new IncidentMediaModel();
        $media = $mediaModel->getByIncidentId($incident['id']);

         $this->view('reporter/report-form', [
        'mode'       => 'view',
        'incident'   => $incident,
        'media'      => $media,
        'page_title' => 'Incident Summary',
         'page_css' => [
            'topnavbar.css',
            'base/typography.css',
            'components/button.css',
            'components/form.css',
            'layouts/form-layout.css',
            'pages/page.css',
            'components/attachment.css'
        ]
    ]);

    }



    

    


}



?>