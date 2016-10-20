<?php
//example
return [
    'rootDir' => __DIR__,
    'testsFolder' => __DIR__ . DIRECTORY_SEPARATOR . "tests/codeception",
    'testsNamespace' => 'tests\codeception',
    'namespaces' => [
        'Testgen\Application' => 'vendor/work/testgen/src/Application',
        'Scheduleme\Model\DbHelper' => 'library/Scheduleme/src/Scheduleme/Model/DbHelper',
        'Zend\Mvc\Controller\AbstractActionController' => 'vendor/zendframework/zend-mvc/src/Controller/AbstractActionController',
        'Zend\Mvc\Controller\AbstractController' => 'vendor/zendframework/zend-mvc/src/Controller/AbstractController',
    ],
    'controllers' => [
        'keyWord' => 'Controller',
        'paths' => [
            'module/Users/src/Users/Controller',
        ],
        'except' =>[
            'ProfileController.php',
        ],
        'exceptActions' => [
            'logoutActions',
        ],
        'route' => [
            'type' => 'zf2',
            'paths' => [
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
