<?php

return [
    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be stored
    | for your application. Typically, this is within the storage directory. But
    | in serverless environments like Vercel, the writable directory must be a
    | tmp path instead of a read-only project directory.
    |
    */

    'compiled' => env('VIEW_COMPILED_PATH', realpath(base_path('storage/framework/views')) ?: '/tmp/views'),
];
