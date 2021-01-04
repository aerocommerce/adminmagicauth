<?php

return [
    'whitelisted_ips' => [
        '127.0.0.1',
    ],

    'whitelisted_domains' => [
        'techquity.co.uk',
        'aerocommerce.com',
    ],

    'permissions' => [
        'techquity.co.uk' => ['*'],
        'aerocommerce.com' => [
            'orders.view',
            'customers.view',
        ]
    ],

    'shared_password' => 'f0rdMust4ng',

    'token_timeout_in_hours' => 24,
];
