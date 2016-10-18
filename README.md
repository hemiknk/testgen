# testgen
Instalation via composer
composer require hemink/testgen

Create config file in root folder of project with name testgen.conf.php
Go to vendor/hemink/testgen
Run php testgen.php controllers or php testgen.php models for tests generation

Config file example
testgen.conf.php
```
<?php
return [
    'rootDir' => __DIR__,//root dir of project
    'controllers' => [
        'keyWord' => 'Controller',//contained in each file name
        'paths' => [//path to folder with controllers
            /**
             * 'frontend/controllers',
             * 'backend/controllers',
             */
        ],
        'testsFolder' => __DIR__ . DIRECTORY_SEPARATOR . "tests/codeception",
        'testsNamespace' => 'tests\codeception',
        'except' => [//except controllers
            /**
             * '.gitkeep',
             * 'SaleController.php',
             */
        ],
        'exceptActions' => [
            /**
             * 'beforeActions',//for action in all controllers
             * 'frontend\controllers\ProductController' => [//for actions in certain controller
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
        'testsFolder' => __DIR__ . DIRECTORY_SEPARATOR . "tests/codeception",
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
