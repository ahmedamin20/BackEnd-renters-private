<?php

use Modules\Auth\Enums\UserTypeEnum;

return [
    'name' => 'Role', // Module Name

    /*
    |--------------------------------------------------------------------------
    | permissions
    |--------------------------------------------------------------------------
    |
    | all permissions that are allowed in our application
    |
    */
    'permissions' => [
        'role_management',
        'user_management',
        'category_management',
        'terms_and_condition_management',
        'contact_us_management',
        'about_us_management',
        'ad_management',
        'driver_management',
        'employee_management',
        'settings_management',
        'service_management',
        'blogs_management',
        'who_are_we_management',
        'city_management',
        'drivers_orders_management',
        'employees_orders_management',
    ],

    /*
    |--------------------------------------------------------------------------
    | roles
    |--------------------------------------------------------------------------
    |
    | all default roles that the application starts with
    | assign all permission to role except in the array
    |
    */
    'roles' => [
        UserTypeEnum::ADMIN => [],
        UserTypeEnum::ADMIN_EMPLOYEE => [],
    ],

    'middleware' => [
        'spatie' => [
            'register_middlewares' => true,
            'role' => 'has_role',
            'permission' => 'permission',
            'role_or_permission' => 'has_role_or_permission',
        ],
        'auth' => '',
    ],
];

/*
 * admin, admin employee
 * seller, seller employee
 *
 * i have permission called role_management
 * */
