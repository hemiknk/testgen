<?php
return [
    'rootDir' => dirname(dirname(dirname(__DIR__))),
    'namespaces' => [//in some clasess was not found
        'dektrium\user\models\User' => 'dektrium/yii2-user/models/User',
        'dektrium\user\traits\ModuleTrait' => 'dektrium/yii2-user/traits/ModuleTrait',
    ],
    'controllers' => [
        'keyWord' => 'Controller',
        'paths' => [//path to folder with controllers
            'frontend/controllers',
            'backend/controllers',
        ],
        'except' =>[//except controllers
            '.gitkeep',
            'SaleController.php',
            'BookingController.php',
        ],
        'exceptActions' => [
            'beforeActions',
            'frontend\controllers\ProductController' => [
                'actionIndex',
            ],
        ],
        'testsFolder' => dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . "tests/codeception",
        'testsNamespace' => 'tests\codeception',
    ],

    'models' => [
        'keyWord' => '.',
        'paths' => [
            'common/models/table',
        ],
        'except' => [
            'BookingSearch.php',
            'CategorySearch.php',
            'ProductSearch.php',
            'UserSearch.php',
        ],
        'testsNamespace' => 'tests\codeception',
        'testsFolder' => dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . "tests/codeception",
    ],

];
