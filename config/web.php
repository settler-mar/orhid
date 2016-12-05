<?php

$params = require(__DIR__ . '/params.php');
$personal = require(__DIR__ . '/personal.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    //'bootstrap' => ['log'],
    'layout' => 'main.jade',
    'components' => [
        'request' => [
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'iIngk13xMUdKJNXDx3AVsfJDfWnv7Edw',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/'],
            'on afterLogin' => function($event) {
                app\modules\user\models\User::afterLogin($event->identity->id);
            }
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                //получение города по стране
                'city/get/<id:\d+>' => 'city/get',

                //Взаимодействия с пользователем на сайте
                '<action:(registration|logout|confirm|reset|profile|resetpassword)>' => 'user/user/<action>',


                /*//закрываем все прямые ссылки на модуль авторизации
                'lowbase-user/<alias:(user|auth|country|city|auth-rule)>/<dopalias>'=>'404',
                //Взаимодействия с пользователем на сайте
                '<action:(login|logout|signup|confirm|reset|profile|remove|online)>' => 'lowbase-user/user/<action>',
                //Взаимодействия с пользователем в панели админстрирования
                'admin/user/<action:(index|update|delete|view|rmv|multidelete|multiactive|multiblock)>' => 'lowbase-user/user/<action>',
                //Авторизация через социальные сети
                //'auth/<authclient:[\w\-]+>' => 'lowbase-user/auth/index',
                //Просмотр пользователя
                'user/<id:\d+>' => 'lowbase-user/user/show',
                //Взаимодействия со странами в панели админстрирования
                'admin/country/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/country/<action>',
                //Взаимодействия с городами в панели администрирования
                'admin/city/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/city/<action>',
                //Работа с ролями и разделением прав доступа
                'admin/role/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/auth-item/<action>',
                //Работа с правилами контроля доступа
                'admin/rule/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/auth-rule/<action>',
*/
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'transport' => $personal['MailTransport'],
            'messageConfig' => [
                //'from' => ['admin@website.com' => 'Admin'], // this is needed for sending emails
                'charset' => 'UTF-8',
            ]
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
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'rbac' =>  [
            'class' => 'johnitvn\rbacplus\Module',
            'userModelClassName'=>null,
            'userModelIdField'=>'id',
            'userModelLoginField'=>'username',
            'userModelLoginFieldLabel'=>null,
            'userModelExtraDataColumls'=>null,
            'beforeCreateController'=>function($route){
                return Yii::$app->user->can('rbac');
            },
            'beforeAction'=>null
        ]
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
        //'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.1.*'],
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
