<?php

$params = require(__DIR__ . '/params.php');
$personal = require(__DIR__ . '/personal.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    //'bootstrap' => ['log'],
    'layout' => 'main.jade',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'iIngk13xMUdKJNXDx3AVsfJDfWnv7Edw',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'lowbase\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login'],
            'on afterLogin' => function($event) {
                lowbase\user\models\User::afterLogin($event->identity->id);
            }
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //Взаимодействия с пользователем на сайте
                '<action:(login|logout|signup|confirm|reset|profile|remove|online)>' => 'lowbase-user/user/<action>',
                //Взаимодействия с пользователем в панели админстрирования
                'admin/user/<action:(index|update|delete|view|rmv|multidelete|multiactive|multiblock)>' => 'lowbase-user/user/<action>',
                //Авторизация через социальные сети
                'auth/<authclient:[\w\-]+>' => 'lowbase-user/auth/index',
                //Просмотр пользователя
                'user/<id:\d+>' => 'lowbase-user/user/show',
                //Взаимодействия со странами в панели админстрирования
                'admin/country/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/country/<action>',
                //Поиск населенного пункта (города)
                'city/find' => 'lowbase-user/city/find',
                //Взаимодействия с городами в панели администрирования
                'admin/city/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/city/<action>',
                //Работа с ролями и разделением прав доступа
                'admin/role/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/auth-item/<action>',
                //Работа с правилами контроля доступа
                'admin/rule/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/auth-rule/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'lb_auth_item',
            'itemChildTable' => 'lb_auth_item_child',
            'assignmentTable' => 'lb_auth_assignment',
            'ruleTable' => 'lb_auth_rule'
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
            'transport' => $personal['MailTransport'],
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
        'db' => require(__DIR__ . '/db.php'),
        'view' => [
            'defaultExtension' => 'jade',
            'renderers' => [
                'jade' => [
                    'class' => 'jacmoe\talejade\JadeViewRenderer',
                    'cachePath' => '@runtime/Jade/cache',
                    'options' => [
                        'pretty' => true,
                        'lifeTime' => 0,//3600 -> 1 hour
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'lowbase-user' => [
            'class' => '\lowbase\user\Module',
            'customViews' => [
                'login' => '@app/views/user/login',
                'signup' => '@app/views/user/signup',
                'profile' => '@app/views/user/profile',
                'repass' => '@app/views/user/repass',
                'show' => '@app/views/user/show',
                'confirmEmail' => '@app/mail/confirmEmail',
                'passwordResetToken' => '@app/mail/passwordResetToken',
            ]
        ],
       /*'user' => [
            'class' => 'budyaga\users\Module',
            'userPhotoUrl' => '/uploads/user-photo',
            'userPhotoPath' => '@app/web/uploads/user-photo',
            'customViews' => [
                //'login' => '@app/views/site/login'
            ],
            'customMailViews' => [
                'confirmChangeEmail' => '@app/mail/confirmChangeEmail', //in this case you have to create files confirmChangeEmail-html.php and confirmChangeEmail-text.php in mail folder
                'confirmNewEmail' => '@app/mail/confirmNewEmail',
                'confirmResetToken' => '@app/mail/confirmResetToken',
            ]
        ],*/
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.1.*'],
        'generators' => [
            'jadecrud' => [
                'class' => 'jacmoe\giijade\crud\Generator',
                'templates' => [
                    'myCrud' => '@jacmoe/giijade/crud/default',
                ]
            ]
        ],
    ];
}

return $config;
