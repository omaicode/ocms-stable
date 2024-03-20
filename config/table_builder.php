<?php

return [
    // Only support bootstrap (4, 5) at this version.
    'theme'   => 'bootstrap',

    //Assets required for table
    'assets'  => [
        'css'=> [
            'vendor/table-builder/css/table-builder.css'
        ],
        'js' => [
            // 'vendor/table-builder/js/vue.min.js',
            'vendor/table-builder/js/table-builder.js'
        ]
    ]
];