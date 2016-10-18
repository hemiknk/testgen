<?php
$allowedTypes = [
    'controllers',
    'models',
];

if (!in_array($argv[1], $allowedTypes)) {
    die("Argument must be one of allowed types, run 
php testgen.php controllers or
php testgen.php models");
}

if (file_exists(__DIR__.'/../../autoload.php')) {
    require_once __DIR__ . '/../../autoload.php';
}

if (!$config = require(__DIR__.'/../../../testgen.conf.php')) {
    die('First create config file');
}

spl_autoload_register(function($class) use ($config){

    if (array_key_exists($class, $config['namespaces'])){
        $class = str_replace($class, $config['namespaces'][$class], $class);
    }
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    include $config['rootDir'] . '/vendor/' . $class . '.php';
});

$app = new \Testgen\Application($config);
$app->run($argv[1]);
