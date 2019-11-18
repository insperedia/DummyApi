<?php
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            '/runtime',
            '/web/assets',
        ],
        'setExecutable' => [
            'yii'
        ],
        'setCookieValidationKey' => [
            '/config/web.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            '/runtime',
            '/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            '/config/web.php',
        ],
    ],
];
