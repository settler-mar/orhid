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
        'assetManager'=>[
          'bundles'=>[
            'yii\bootstrap\BootstrapAsset' => [
              'css' => [],
            ],
          ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            //'cache' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                //закрываем пямой доступ к /user/user
                'user/user/<action>'=>'404',
                'user/user/<action>/<action2>'=>'404',
                '/chat/default/<action>'=>'404',
                '/mail/default/<action>'=>'404',
               // 'payment/default/<action>'=>'404',
                //'payment/default/<action>/<action2>'=>'404',
                //получение города по стране
                'city/get/<id:\d+>' => 'city/get',
                //Взаимодействия с пользователем на сайте
                '<action:(online|registration|logout|confirm|reset|profile|resetpassword|return-to-admin)>' => 'user/user/<action>',
                'user/<action:(fav)>' => 'user/user/<action>',

                //закрываем прямой доступ к базовому контроллеру
                'site'=>'404',
                'site/<action>'=>'404',
                'site/<action>/<action2>'=>'404',
                //базовые страницы в основном контроллере
                '<action:(top|shop|about|blog|legends|mans|competitions|onlinehelp|services)>' => 'site/<action>',
                //Страница пользователя
                '<action:(user)>/<id:\d+>' => 'site/user/',

                //страница сообщения
                '<action:(chat)>/<id:\d+>' => 'chat/default/<action>',
                'chat' => 'chat/default/index',
                'chat/<action:(get|send)>' => 'chat/default/<action>',

                '<action:(mail)>/<id:\d+>' => 'mail/default/<action>',
                'mail' => 'mail/default/index',
                'mail/<action:(send)>' => 'mail/default/<action>',

                //оплаты
                'payment/default/<action>/<id:\d+>' => 'payment/default/<action>',
                'payment/<action:(tariff|shop|view)>/<id:\d+>' => 'payment/default/<action>',
                'payment/<action:(finish)>' => 'payment/default/<action>',

                //магазин
                '<action:user-gift>/<id:\d+>'=>'shop/default/<action>', //выбор подарка для пользователя
                'user-gift/<id:\d+>/<code:\d+>'=>'shop/default/user-gift2', //выбор подарка для пользователя

                'staticPages/<action>' => 'staticPages/default/<action>',

              [ // правило для роутинга по статическим страницам с именами ЧПУ из БД
                'class' => 'app\components\staticPagesRules',
              ],
            ],
        ],
        'errorHandler'=>[
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
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
        'shop' => [
            'class' => 'app\modules\shop\Module',
        ],
        'chat' => [
            'class' => 'app\modules\chat\Module',
        ],
        'staticPages' => [
            'class' => 'app\modules\staticPages\Module',
        ],
        'orhidLegends' => [
            'class' => 'app\modules\orhidLegends\Module',
        ],
        'orhidBlog' => [
            'class' => 'app\modules\orhidBlog\Module',
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
        ],
        'slider' => [
            'class' => 'app\modules\slider\Module',
        ],
        'tarificator' => [
            'class' => 'app\modules\tarificator\Module',
        ],
        'tariff' => [
            'class' => 'app\modules\tariff\Module',
        ],
        'payment' => [
          'class' => 'app\modules\payment\Module',
          'clientId'     => $personal['paypal_client_id'],
          'clientSecret' => $personal['paypal_client_secret'],
          'baseUrl' => 'http://127.0.0.1:8080/payment/finish',
          //'isProduction' => false,
          // This is config file for the PayPal system
          'config'       => [
            'currency'=>"USD",
            'http.ConnectionTimeOut' => 30,
            'http.Retry'             => 1,
            'mode'                   => 'sandbox', // development (sandbox) or production (live) mode
            'log.LogEnabled'         => YII_DEBUG ? 1 : 0,
            'log.FileName'           => '@runtime/logs/paypal.log',
            'log.LogLevel'           => 'FINE', // 'FINE','INFO','WARN','ERROR';
          ]
        ],
        'logs' => [
            'class' => 'app\modules\logs\Module',
        ],
        'mail' => [
          'class' => 'app\modules\mail\Module',
        ],
        'fileupload' => [
          'class' => 'app\modules\fileupload\Module',
        ],
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
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.1.*','31.202.224.10'],
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
