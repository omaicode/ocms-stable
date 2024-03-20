<?php
return [
    [
        'id' => 'cms-menu-dashboard',
        'priority' => 0,
        'parent_id' => null,
        'name' => 'menu.dashboard',
        'icon' => 'fas fa-tachometer',
        'url' => route('admin.dashboard')
    ],
    [
        'id' => 'cms-menu-appearance-menu',
        'priority' => 1,
        'parent_id'=> null,
        'name' => 'messages.menus',
        'icon' => 'fas fa-list',
        'url'  => route('admin.appearance.menus.index'),
        'permissions' => ['appearance.view']
    ],
    [
        'id' => 'cms-menu-page',
        'priority' => 2,
        'parent_id' => null,
        'name' => 'messages.pages',
        'icon' => 'fas fa-file-edit',
        'url' => route('admin.pages.index'),
        'permissions' => ['pages.view']
    ],
    [
        'id' => 'cms-menu-blog',
        'priority' => 3,
        'parent_id' => null,
        'name' => 'messages.blog',
        'icon' => 'fas fa-newspaper',
        'url' => '#',
        'permissions' => ['blog.view'],
        'children' => [
            [
                'id' => 'cms-menu-blog-categories',
                'priority' => 0,
                'name' => 'messages.categories',
                'url' => route('admin.blog.categories.index'),
                'permissions' => ['blog.posts.view'],
            ],
            [
                'id' => 'cms-menu-blog-post',
                'priority' => 1,
                'name' => 'messages.posts',
                'url' => route('admin.blog.posts.index'),
                'permissions' => ['blog.categories.view'],
            ],
        ]
    ],
    [
        'id' => 'cms-menu-clients',
        'priority' => 4,
        'parent_id' => null,
        'name' => 'messages.clients',
        'icon' => 'fas fa-users',
        'url' => route('admin.clients.index'),
        'permissions' => ['clients.view']
    ],
    [
        'id' => 'cms-menu-media',
        'priority' => 94,
        'parent_id' => null,
        'name' => 'messages.media',
        'icon' => 'fas fa-image',
        'url' => route('admin.media.index'),
        'permissions' => ['media.view']
    ],
    [
        'id' => 'cms-menu-contact',
        'priority' => 95,
        'parent_id' => null,
        'name' => 'messages.contacts',
        'icon' => 'fas fa-headset',
        'url' => route('admin.contacts.index'),
        'permissions' => ['contact.view']
    ],
    [
        'id' => 'cms-menu-partials',
        'priority' => 96,
        'parent_id' => null,
        'name' => 'messages.partials',
        'icon' => 'fas fa-cubes',
        'url' => route('admin.appearance.partials.index'),
        'permissions' => ['appearance.partials.view']
    ],
    [
        'id' => 'cms-menu-appearance-theme-options',
        'priority' => 97,
        'parent_id'=> null,
        'name' => 'messages.theme',
        'icon' => 'fas fa-globe',
        'url'  => route('admin.appearance.theme-options'),
        'permissions' => ['appearance.view']
    ],
    [
        'id' => 'cms-menu-popup',
        'priority' => 98,
        'parent_id' => null,
        'name' => 'menu.popup',
        'icon' => 'fas fa-ad',
        'url' => route('admin.popup.index'),
        'permissions' => ['popup.view']
    ],
    [
        'id' => 'cms-menu-settings',
        'priority' => 99,
        'parent_id' => null,
        'name' => 'menu.settings',
        'icon' => 'fas fa-cog',
        'url' => '#',
        'permissions' => ['setting.view'],
        'children' => [
            [
                'id' => 'cms-menu-settings-theme',
                'priority' => 0,
                'name' => 'messages.themes',
                'url' => route('admin.appearance.themes'),
                'permissions' => ['appearance.themes.view'],
            ],            
            [
                'id' => 'cms-menu-settings-general',
                'priority' => 0,
                'name' => 'menu.settings.general',
                'url' => route('admin.settings.general'),
                'permissions' => ['setting.general'],
            ],
            [
                'id' => 'cms-menu-settings-email',
                'priority' => 1,
                'name' => 'menu.settings.email',
                'url' => route('admin.settings.email'),
                'permissions' => ['setting.email'],
            ],
            [
                'id' => 'cms-menu-settings-sms',
                'priority' => 2,
                'name' => 'menu.settings.sms',
                'url' => route('admin.settings.sms'),
                'permissions' => ['setting.sms'],
            ],
            [
                'id' => 'cms-menu-settings-social-login',
                'priority' => 3,
                'name' => 'menu.settings.social-login',
                'url' => route('admin.settings.social-login'),
                'permissions' => ['setting.social-login'],
            ],
        ]
    ],
    [
        'id' => 'cms-menu-system',
        'priority' => 99,
        'parent_id' => null,
        'name' => 'menu.system',
        'icon' => 'fas fa-server',
        'url' => '#',
        'permissions' => ['system.view'],
        'children' => [
            [
                'id' => 'cms-menu-system-administrators',
                'priority' => 0,
                'name' => 'menu.system.administrators',
                'url' => route('admin.system.administrators.index'),
                'permissions' => ['system.admins.view'],
            ],
            [
                'id' => 'cms-menu-system-roles',
                'priority' => 1,
                'name' => 'menu.system.roles',
                'url' => route('admin.system.roles.index'),
                'permissions' => ['system.roles.view'],
            ],
            [
                'id' => 'cms-menu-system-information',
                'priority' => 2,
                'name' => 'menu.system.information',
                'url' => route('admin.system.information'),
                'permissions' => ['system.information.view'],
            ],
           [
               'id' => 'cms-menu-system-activities',
               'priority' => 3,
               'name' => 'menu.system.activities',
               'url' => route('admin.system.activities'),
               'permissions' => ['system.activity.view'],
           ],
           [
               'id' => 'cms-menu-system-log',
               'priority' => 4,
               'name' => 'menu.system.logs',
               'url' => route('admin.system.logs'),
               'permissions' => ['system.error_log.view'],
           ],
        ]
    ],
];
