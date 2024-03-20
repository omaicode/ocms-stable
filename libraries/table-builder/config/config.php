<?php

return [
    // Only support bootstrap (4, 5) at this version.
    'theme'   => 'bootstrap',

    //Assets required for table
    'assets'  => [
        'css'=> [
           asset('vendor/table-builder/css/table-builder.css')
        ],
        'js' => [
            asset('vendor/table-builder/js/vue.min.js'),
            asset('vendor/table-builder/js/table-builder.js')
        ]
    ]
];