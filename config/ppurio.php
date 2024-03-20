<?php

return [
    'base_url' => env('PPURIO_BASE_URL', 'https://message.ppurio.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | Phone From
    |--------------------------------------------------------------------------
    |
    | The phone number using for send SMS
    | You can get the phone number from this link https://www.ppurio.com/send-api/info
    |
    */
    'phone_from' => env('PPURIO_PHONE_FROM', null),

    /*
    |--------------------------------------------------------------------------
    | Phone To
    |--------------------------------------------------------------------------
    |
    | The phone number using for receive SMS
    | Enter your real phone number
    |
    */
    'phone_to' => env('PPURIO_PHONE_TO', null),

    /*
    |--------------------------------------------------------------------------
    | Account
    |--------------------------------------------------------------------------
    |
    | Your ppurio.com ID
    |
    */
    'account' => env('PPURIO_ACCOUNT', null),

    /*
    |--------------------------------------------------------------------------
    | Access Key
    |--------------------------------------------------------------------------
    |
    | You can get the access key from this link https://www.ppurio.com/send-api/info
    |
    */
    'access_key' => env('PPURIO_ACCESS_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Default message
    |--------------------------------------------------------------------------
    |
    */
    'default_message' => '컨설팅 요청이 도착했습니다.'
];
