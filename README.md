# testgen
Instalation via composer
composer require hemink/testgen

Create config file in root folder of project with name testgen.conf.php
Go to 

```
vendor/hemink/testgen
```
Run 
```
php testgen.php controllers 
```
or
```
php testgen.php models
```
for tests generation.

Config file example
testgen.conf.php
```
<?php
return [
    'rootDir' => __DIR__,//root dir of project
    'testsFolder' => __DIR__ . DIRECTORY_SEPARATOR . "tests/codeception",
    'testsNamespace' => 'tests\codeception',
    'namespaces' => [
        'dektrium\user\models\User' => 'dektrium/yii2-user/models/User',
        'dektrium\user\traits\ModuleTrait' => 'dektrium/yii2-user/traits/ModuleTrait',
    ],
    'controllers' => [
        'keyWord' => 'Controller',//contained in each file name
        'paths' => [
            'frontend/controllers',
            'backend/controllers',
            'console/controllers',
        ],
        'except' =>[//except controllers
            '.gitkeep',
            'SaleController.php',
            'BookingController.php',
        ],
        'exceptActions' => [
            'beforeActions',//for action in all controllers
            'frontend\controllers\ProductController' => [
                'actionIndex',//for actions in certain controller
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

];
