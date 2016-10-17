<?php
return [
    'rootDir' => dirname(__DIR__),
    'namespaces' => [
        'dektrium\user\models\User' => 'dektrium/yii2-user/models/User',
        'dektrium\user\traits\ModuleTrait' => 'dektrium/yii2-user/traits/ModuleTrait',
    ],
    'controllers' => [
        'keyWord' => 'Controller',
        'paths' => [
            'frontend/controllers',
            'backend/controllers',
            'console/controllers',
        ],
        'except' =>[
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
        'testsFolder' => dirname(__DIR__) . DIRECTORY_SEPARATOR . "tests/codeception",
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
        'testsFolder' => dirname(__DIR__) . DIRECTORY_SEPARATOR . "tests/codeception",
    ],

];
