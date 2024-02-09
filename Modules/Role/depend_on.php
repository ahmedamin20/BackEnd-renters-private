<?php

use App\Http\Middleware\AlwaysAcceptJson;
use App\Models\User;

/*
 * Files That Outside of module and module depends on
 *
 * */

return [
    'models' => [
        User::class,
    ],
    'middleware' => [
        /*
         * Always Accept Json Middleware
         *
         * This Middleware Set Accept => application/json Header Inside incoming request for simplicity
         *
         * it's optional if you want to header manually
         *
         * */

        AlwaysAcceptJson::class,
    ],

    'trait' => [
        /*
         * Http Response Trait
         *
         * This Trait Responsible For Json Responses
         * Like  Success Response , Validation Errors,  ...etc
         *
         * */

        \App\Traits\HttpResponse::class,
    ],
    'helpers' => [
        /*
         * Helper.php
         *
         * This file contain helpers functions like translate_success_message and more !
         *
         * */

        'app/Http/Helpers/helpers.php',
    ],
];
