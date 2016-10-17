<?php

namespace Testgen;

use Testgen\Generators\TestsControllerGenerator;
use Testgen\Generators\TestsModelGenerator;

class Application
{
    protected $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function run($type)
    {
        $fileManager = new FileManager($this->getConfig($type), $this->config['rootDir']);
        $paths = $fileManager->getPaths();
        $generator = $this->createGenerator($type);
        $generator->generate($paths, $this->config[$type]);
    }

    protected function getConfig($type)
    {
        return $this->config[$type];
    }

    private static function createGenerator($type)
    {
        if ('controllers' === $type) {
            return new TestsControllerGenerator();
        }
        return new TestsModelGenerator();
    }
}