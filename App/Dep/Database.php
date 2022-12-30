<?php

return [
    "default" => [
        'host' => denv('DEVLIE_HOST'),
        'base' => denv('DEVLIE_BASE'),
        'user' => denv('DEVLIE_USER'),
        'pass' => denv('DEVLIE_PASS'),
    ],
    "wordpress" => [
        'host' => denv('DEVLIE_HOST'),
        'base' => denv('DEVLIE_ADMIN_DB'),
        'user' => denv('DEVLIE_USER'),
        'pass' => denv('DEVLIE_PASS'),
    ],
    "prod" => [
        'host' => denv('DEVLIE_HOST'),
        'base' => denv('DEVLIE_BASE'),
        'user' => denv('DEVLIE_USER'),
        'pass' => denv('DEVLIE_PASS'),
    ]
];
