<?php

date_default_timezone_set('Asia/Jakarta');

const SIMRS_DB = 'app_bihealth';
const EMR_DB   = 'app_bihealth_emr';

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => '101.50.2.213',
            'database' => 'app_bihealth_online',
            'username' => 'bihealth',
            'password' => 'Xsw21q@z',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict'    => false
        ],
        'mysql_emr' => [
            'driver' => 'mysql',
            'host' => '101.50.2.213',
            'database' => 'app_bihealth_emr',
            'username' => 'bihealth',
            'password' => 'Xsw21q@z',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict'    => false
        ],
        'mysql_online' => [
            'driver' => 'mysql',
            'host' => '101.50.2.213',
            'database' => 'app_bihealth_online',
            'username' => 'bihealth',
            'password' => 'Xsw21q@z',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict'    => false
        ],
        'mysql_inacbg' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_INACBG'),
            'database' => env('DB_DATABASE_INACBG'),
            'username' => env('DB_USERNAME_INACBG'),
            'password' => env('DB_PASSWORD_INACBG'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict'    => false
        ],
        'postgres' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_PG_HOST'),
            'database' => env('DB_PG_DATABASE'), // This seems to be ignored
            'port'     => env('DB_PG_PGSQL_PORT', 5432),
            'username' => env('DB_PG_USERNAME'),
            'password' => env('DB_PG_PASSWORD'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public'
        ]
    ]
];
