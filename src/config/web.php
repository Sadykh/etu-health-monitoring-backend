<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'ehn',
    'name' => 'EHM',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '6z1jMjeuDKNLahhMCJbp4MuQBJhz09Uu',
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'sms' => [
            'class'    => 'ladamalina\smsc\Smsc',
            'login'     => 'NmcPay',  // login
            'password'   => 'terpilo56@', // plain password or lowercase password MD5-hash
            'post' => true, // use http POST method
            'https' => true,    // use secure HTTPS connection
            'charset' => 'utf-8',   // charset: windows-1251, koi8-r or utf-8 (default)
            'debug' => false,    // debug mode
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*', '127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*','127.0.0.1', '::1'],
    ];
}

return $config;
