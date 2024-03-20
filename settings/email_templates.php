<?php
return [
    'reset_password' => [
        'title' => __('email.reset_password.title'),
        'description' => __('email.reset_password.description'),
        'subject' => __('email.reset_password.subject'), //Path: resources/lang/{locale}/email.php
        'content' => 'admin.email_templates.reset_password' //Path: resources/views/admin/email_templates/reset_password.blade.php
    ]
];