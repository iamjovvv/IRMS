<?php

return 
[
    'staff' => 
    [
        [
            'label' => 'Dashboard',
            'icon'  => 'fa-solid fa-house',
            'link'  => '/RMS/public/index.php?url=dashboard-staff',

        ],

        [
            'label' => 'New Reports',
            'icon'  => 'fa-solid fa-file',
            'link'  => '/RMS/public/index.php?url=new-reports'
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