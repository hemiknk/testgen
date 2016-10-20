<?php
//example
return [
    'rootDir' => __DIR__,
    'testsFolder' => __DIR__ . DIRECTORY_SEPARATOR . "tests/codeception",
    'testsNamespace' => 'tests\codeception',
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
    ],


    'route' => [
        'type' => 'zf2',
        'paths' => [
            __DIR__ . DIRECTORY_SEPARATOR . 'module/Users/config/module.config.php',
            __DIR__ . DIRECTORY_SEPARATOR . 'module/Calendar/config/module.config.php',
        ],
    ],

];