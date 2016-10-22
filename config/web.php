<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mS9BRbT_mCre8t4NEnJJbw1TgoeJ7nnn',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//        'user' => [
//            'identityClass' => 'app\models\User',
//            'enableAutoLogin' => true,
//        ],
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
        'view' => [
            'class' => 'yii\web\View',
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
        'i18n'=> [
            'translations' => [
                'user'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@app/messages",
                    'sourceLanguage' => 'en_US',
                    'fileMap' => [
                        'app' => 'user.php'
                    ]
                ],
            ]
        ],

        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'authClientCollection' => [
            'class'   => \yii\authclient\Collection::className(),
            'clients' => [
                'vkontakte' => [
                    'class'        => 'dektrium\user\clients\VKontakte',
                    'clientId'     => $params['vk_client_id'],
                    'clientSecret' => $params['vk_secret'],
                ],
                'facebook' => [
                    'class'        => 'dektrium\user\clients\Facebook',
                    'clientId'     => $params['fb_app_id'],
                    'clientSecret' => $params['fb_client_secret'],
                ],
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => \dektrium\user\Module::className(),
            'controllerMap' => [
                'security'      => 'app\controllers\user\SecurityController',
                'registration'  => 'app\controllers\user\RegistrationController',
                'settings'      => 'app\controllers\user\SettingsController',
            ],
            'modelMap' => [
                'User'             => 'app\models\User',
                'RegistrationForm' => 'app\models\RegistrationForm',
                'Profile'          => 'app\models\Profile',
                'Account'          => 'app\models\Account'
            ],
            'urlPrefix' => '',
            'enableConfirmation' => false,
            'enablePasswordRecovery' => false,
            'admins' => $params['admin_account_username'],
        ],
    ],
    'params' => $params,
    'defaultRoute' => 'site/index',
    'layout' => 'layout.php',
    'language' => 'ru_RU',
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
    ];
}

return $config;
