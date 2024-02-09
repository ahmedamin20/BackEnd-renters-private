<?php

return [
    'enable_captcha' => false,
    'enable_forgot_password' => true,
    'include_mobile_login' => true,
    'include_spa_login' => true,
    'include_register' => true,
    /*
     * This Variable Used By Spatie Media To Store User Avatar
     *
     * if not value passed , collection name will be `default`
     * */
    'avatar' => [
        'enabled' => true,
        'users_media_collection' => 'users',
    ],

    'routes' => [
        'names' => [
            'register' => 'register',
            'login.mobile' => 'login.mobile',
            'login.web' => 'login.web',
            'profile' => 'profile',
            'verify_user' => 'verify_user.verify',
            'resend_verify_user' => 'verify_user.resend',
            'reset_password' => 'reset_password',
            'change_password' => 'change_password',
            'logout' => 'logout',
            'resend_email_verify' => 'resend_email_verify',
        ],
    ],
    'middleware' => [
        'verify_user' => 'must_be_verified',
    ],
];
