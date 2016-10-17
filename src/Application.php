<?php

namespace Testgen;

use Testgen\Generators\TestsControllerGenerator;
use Testgen\Generators\TestsModelGenerator;

/**
 * Class Application
 *
 * @package Testgen
 */
class Application
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * Application constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $type
     */
    public function run($type)
    {
        $fileManager = new FileManager($this->getConfig($type), $this->getRootDir());
        $paths = $fileManager->getPaths();
        $generator = $this->createGenerator($type);
        $generator->generate($paths, $this->config[$type]);
    }

    /**
     * @param $type
     * @return mixed
     */
    protected function getConfig($type)
    {
        return $this->config[$type];
    }

    /**
     * @return mixed
     */
    protected function getRootDir()
    {
        return $this->config['rootDir'];
    }

    /**
     * @param $type
     * @return TestsControllerGenerator|TestsModelGenerator
     */
    private static function createGenerator($type)
    {
        if ('controllers' === $type) {
            return new TestsControllerGenerator();
        }
        return new TestsModelGenerator();
    }
}
