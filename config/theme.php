<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Path to lookup theme
    |--------------------------------------------------------------------------
    |
    | The root path contains themes collections.
    |
    */
    'directory' => env('THEMES_DIR', 'themes'),

    /*
    |--------------------------------------------------------------------------
    | Symbolic link path
    |--------------------------------------------------------------------------
    |
    | you can change the public themes path used for assets.
    |
    */
    'symlink_path' => 'themes',

    /*
    |--------------------------------------------------------------------------
    | Symbolic link relative
    |--------------------------------------------------------------------------
    |
    | Determine if relative symlink should be used instead of absolute one.
    |
    */
    'symlink_relative' => false,

    /*
    |--------------------------------------------------------------------------
    | Current Theme
    |--------------------------------------------------------------------------
    */
    'currentTheme' => 'default/clean-blog',

    /*
    |--------------------------------------------------------------------------
    | Fallback Theme
    |--------------------------------------------------------------------------
    |
    | If you don't set a theme at runtime (through middleware for example)
    | the fallback theme will be used automatically.
    |
    */
    'fallbackTheme' => 'default/clean-blog',

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Config for caching feature.
    |
    */
    'cache' => [
        'enabled' => false,
        'key' => 'themes-manager',
        'lifetime' => 86400,
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Config for composer.json file, generated for new theme
    | If null then information will be asked at generation process
    | If not null, values will be used at generation process
    |
    */
    'composer' => [
        'vendor' => null,
        'author' => [
            'name' => null,
            'email' => null,
        ],
    ],

    'theme' => [
        'site_name'        => 'OCMS',
        'site_title'       => 'Content Management, Theme System',
        'site_description' => 'We are website outsourcing team. With many years of experience, we make sure every project done very fast, in time with high quality, flexible and easy to scale.',
        'site_keywords'    => '',
        'seo_title'        => '',
        'seo_description'  => '',
        'seo_og_image'     => '',
        'address'          => 'Robert Robertson, 1234 NW Bobcat Lane, St. Robert, MO',
        'website'          => 'https://omaicode.com',
        'email'            => 'support@omaicode.com',
        'copyright'        => '©2024 OMAICODE. All right reserved.',
        'phone'            => '(+1)555-555-555',
        'logo'             => '',
        'logo_light'       => '',
        'favicon'          => '',
        'footer_description' => 'OCMS of Companies consists of a number of entities, each specializing in sectors of immigration, investment, education, and foreign worker recruitment.',
        'page_background'       => '',
        'title_background' => '',
        'footer_background'=> '',
        'thumbnail_background'=> '',
        'facebook' => '#',
        'twitter' => '#',
        'youtube' => '#',
        'instagram' => '#',
        'linkedin' => '#',
    ]
];
