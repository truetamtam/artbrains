<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'artbrains.ru - Веб — студия',
    'homeUrl' => '/items/index',

    'language' => 'ru',
    'defaultController' => 'items',

    // preloading 'log' component
    'preload' => array(
        //'log',
        'bootstrap',
    ),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.blog.models.*',
        'application.modules.blog.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
    ),

    'aliases' => array(
        'xupload' => 'ext.xupload',
    ),

    'modules' => array(

        'blog' => array(
            'recentComments' => false,
            'tagCloud' => true,
            'indexPageSize' => 10,
        ),

        'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii'
            ),
            'class' => 'system.gii.GiiModule',
            'password' => 'lucky7',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'user' => array(
            // encrypting method (php hash function)
            'hash' => 'md5',
            // send activation email
            'sendActivationMail' => true,
            // allow access for non-activated users
            'loginNotActiv' => false,
            // activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => false,
            // automatically login from registration
            'autoLogin' => true,
            // registration path
            'registrationUrl' => array('/user/registration'),
            // recovery password path
            'recoveryUrl' => array('/user/recovery'),
            // login form path
            'loginUrl' => array('/user/login'),
            // page after login
            'returnUrl' => array('/items/admin'),
            // page after logout
            'returnLogoutUrl' => array('/user/login'),
        ),
    ),

    // application components
    'components' => array(
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
            'responsiveCss' => true,
        ),

        'cache' => array(
            //          'class' => 'CApcCache',
            'class' => 'CFileCache',
        ),
        'session' => array(
            'class' => 'CCacheHttpSession',
        ),


        'user' => array(
            // enable cookie-based authentication
            'class' => 'WebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/login'),
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),

        /*'db'=>array(
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
        ),*/
        // uncomment the following to use a MySQL database

        'db' => array(
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'ptf_',

            'connectionString' => 'mysql:host=localhost;dbname=artbrains5',
            'schemaCachingDuration' => 86400,
            // 'enableProfiling'=>true,
            // 'enableParamLogging'=>true,
            // 'emulatePrepare' => true,
        ),

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'enabled' => YII_DEBUG,
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'application',
                    'ignoreAjaxInFireBug' => false,
                    'showInFireBug' => true,
                    'levels' => 'error, warning, trace, profile, info',
                ),
                array(
                    'class' => 'CProfileLogRoute',
                    'enabled' => true,
                ),
                array(
                    'class' => 'ext.yii-debug-toolbar.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters' => array('127.0.0.1', '188.123.230.181'),
                ),
            ),
        ),
        'phpThumb' => array(
            'class' => 'ext.EPhpThumb.EPhpThumb',
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'author' => 'Уляшев Роман Сергеевич',
        'indexDesc' => 'Главная страница веб-студии artbrains.ru. Здесь можно сделать заказ на создание сайта.
                Просмотреть наше портфолио онлайн. Узнать больше о создании сайтов.',
        'blogIndexDesc' => 'Все записи блога artbrains веб-студии. Интересные статьи и переводы, современные направления
                в создании сайтов. Современные направления веба.',
        'siteContactText' => 'Страница контактов веб студии artbrains.ru. Можно обратиться через
                форму обратной связи. Также можно обратиться по телефону или электронной почте',
        'adminEmail' => 'hub@artbrains.ru',
        'my-ml' => 'hub@artbrains.ru',
        'my-ph' => '+7(985)285-65-41',
        // blog comments
        'commentsNeedApproval' => true,
        // images thumbnails sizes (str)
        'thumbSmall' => '40',
        'thumbMedium' => '240',
        'thumbLarge' => '300',
    ),
);