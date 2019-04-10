<?php

return [
    'components' => [
        'db' => [
            'dsn' => 'sqlite:' . __DIR__ . '/../../_data/test.db',
        ],
    ],
    'id' => 'my-test',
    'basePath' => dirname(__DIR__),
];
