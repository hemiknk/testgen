# testgen
Instalation via composer
```
composer require hemink/testgen
```
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
        'paths' => [//set if controller not in module folder
        //            'module/Users/src/Users/Controller',
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
        
        'route' => [
            'type' => 'zf2',//or simple
            //if you use simple the path is not needed
            'paths' => [
                __DIR__ . '/module/Users/config/module.config.php',
                __DIR__ . '/module/Calendar/config/module.config.php',
            ],
        ],
        'route' => [
            'type' => 'zf2',//or 'simple'
            //if you use 'simple' the path is not needed
            'modules' => __DIR__ . DIRECTORY_SEPARATOR . 'module',
            'paths' => [//for define cusrom paths to routs config
                __DIR__ . DIRECTORY_SEPARATOR . 'module/Users/config/module.config.php',
                __DIR__ . DIRECTORY_SEPARATOR . 'module/Calendar/config/module.config.php',
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
