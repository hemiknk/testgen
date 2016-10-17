<?php
return [
    'rootDir' => dirname(dirname(dirname(__DIR__))),
    'controllers' => [
        'keyWord' => 'Controller',//contained in each file name
        'paths' => [//path to folder with controllers
            /**
             * 'frontend/controllers',
             * 'backend/controllers',
             */
        ],
        'testsFolder' => dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . "tests/codeception",
        'testsNamespace' => 'tests\codeception',
        'except' => [//except controllers
            /**
             * '.gitkeep',
             * 'SaleController.php',
             */
        ],
        'exceptActions' => [
            /**
             * 'beforeActions',
             * 'frontend\controllers\ProductController' => [
             *      'actionIndex',
             * ],
             */
        ],

    ],

    'models' => [
        'keyWord' => '.',//contained in each file name
        'paths' => [//path to folder with models
            /**
             * 'common/models/table',
             */
        ],
        'testsFolder' => dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . "tests/codeception",
        'except' => [
            /**
             * 'ProductSearch.php',
             * 'UserSearch.php',
             */
        ],
        'testsNamespace' => 'tests\codeception',
    ],

    'namespaces' => [//used for autoload classes
        'dektrium\user\models\User' => 'dektrium/yii2-user/models/User',
        'dektrium\user\traits\ModuleTrait' => 'dektrium/yii2-user/traits/ModuleTrait',
    ],

];
