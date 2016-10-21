<?php
use Testgen\Application;

$allowedTypes = [
    'controllers',
    'models',
];

if (!in_array($argv[1], $allowedTypes)) {
    die("Argument must be one of allowed types, run 
php testgen.php controllers or
php testgen.php models");
}

// for phar
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once(__DIR__.'/vendor/autoload.php');
} elseif (file_exists(__DIR__.'/../../autoload.php')) {
    require_once __DIR__ . '/../../autoload.php';
}


if (!$config = require(__DIR__.'/../../../testgen.conf.php')) {
    die('First create config file');
}

spl_autoload_register(function($class) use ($config){

    if (array_key_exists($class, $config['namespaces'])){
        $class = str_replace($class, $config['namespaces'][$class], $class);
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        include $config['rootDir'] . DIRECTORY_SEPARATOR . $class . '.php';
    }

});

$app = new Application($config);
$app->run($argv[1]);
