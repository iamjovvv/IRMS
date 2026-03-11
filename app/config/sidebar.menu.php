<?php

return 
[
    'staff' => 
    [
        [
            'label' => 'Home',
            'icon'  => 'fa-solid fa-house',
            'link'  => '/RMS/public/index.php?url=staff/dashboard',

        ],

        [
            'label' => 'New Reports',
            'icon'  => 'fa-solid fa-file',
            'link'  => '/RMS/public/index.php?url=staff/newReports'
        ],

        [
            'label' => 'Ongoing',
            'icon'  => '<fa-solid fa-rotate',
            'link'  => '/RMS/public/index.php?url=staff/reportsOngoing'
        ],
        [
            'label' => 'Resolved',
            'icon'  => 'fa-solid fa-check',
            'link'  => '/RMS/public/index.php?url=staff/reportsResolved'
        ],
         [
            'label' => 'Responder',
            'icon'  => 'fa-solid fa-user',
            'link'  => '/RMS/public/index.php?url=staff/responderAccts'
        ]

             
     ],

     'admin' =>
     [
        [
            'label' => 'Dashboard',
            'icon'  => 'fa-solid fa-file',
            'link'  => '/RMS/public/index.php?url=admin/dashboard'
        ],
        [
            
            'label' => 'Responders',
            'icon'  => 'fa-solid fa-users',
            'link'  => '/RMS/public/index.php?url=admin/accountsMgmt&role=responder'
        ],
        [
            'label' => 'Reporters',
            'icon'  => 'fa-solid fa-users',
            'link'  => '/RMS/public/index.php?url=admin/accountsMgmt&role=reporter'
        ],
        [
            'label' => 'Staff',
            'icon'  => 'fa-solid fa-users',
            'link'  => '/RMS/public/index.php?url=admin/accountsMgmt&role=staff'
        ]

     ]
     
    //  ,
    //   'responder' =>
    //  [
    //     [
    //         'label' => 'Dashboard',
    //         'icon'  => 'fa-solid fa-file',
    //         'link'  => '/RMS/public/index.php?url=responder/dashboard'
    //     ],

    //  ]
     




];

?>