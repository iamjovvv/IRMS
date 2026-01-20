<?php

return 
[
    'staff' => 
    [
        [
            'label' => 'Home',
            'icon'  => 'fa-solid fa-house',
            'link'  => '/RMS/public/index.php?url=dashboard/staff',

        ],

        [
            'label' => 'New Reports',
            'icon'  => 'fa-solid fa-file',
            'link'  => '/RMS/public/index.php?url=staff/newReports'
        ],

        [
            'label' => 'Resolved',
            'icon'  => 'fa-solid fa-check',
            'link'  => '/RMS/public/index.php?url=new-reports'
        ],

        [
            'label' => 'Escalated',
            'icon'  => 'fa-solid fa-circle-exclamation',
            'link'  => '/RMS/public/index.php?url=staff/reportEscalate'
        ]

             
     ],

     'admin' =>
     [
        [
            'label' => 'Dashboard',
            'icon'  => 'fa-solid fa-file',
            'link'  => '/RMS/public/index.php?url=admin/dashboard'
        ],

        // [
        //     'label' => 'Reports',
        //     'icon'  => 'fa-solid fa-file',
        //     'link'  =>  '/RMS/public/index.php?url='
        // ]
     ]


];

?>