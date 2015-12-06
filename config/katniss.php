<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-12-06
 * Time: 19:13
 */

return [
    'use_localized_url' => false,

    'admin_theme' => 'AdminLte',
    'home_theme' => 'Egret',
    'paths_use_admin_theme' => [
        'admin', 'auth', 'documents', 'password'
    ],

    'widgets' => [

    ],

    'extensions' => [

    ],

    'static_extensions' => [

    ],

    'disks' => [

        'upload' => [
            'driver' => 'local',
            'root' => storage_path('../public/upload'),
        ],
    ],
];