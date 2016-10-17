<?php
// for phar
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once(__DIR__.'/vendor/autoload.php');
} elseif (file_exists(__DIR__.'/../../autoload.php')) {
    require_once __DIR__ . '/../../autoload.php';
}

$config = require('testgen.conf.php');

spl_autoload_register(function($class) use ($config){

    if (array_key_exists($class, $config['namespaces'])){
        $class = str_replace($class, $config['namespaces'][$class], $class);
    }
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    include $config['rootDir'] . '/vendor/' . $class . '.php';
});

$app = new \Testgen\Application($config);
$app->run('controllers');
$app->run('models');
