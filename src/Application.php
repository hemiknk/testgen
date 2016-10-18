<?php
/**
 * Testgen Component
 *
 */

/**
 * @namespace
 */
namespace Testgen;

use Testgen\Parser\AbstractParser;
use Testgen\Parser\ControllerParser;
use Testgen\Parser\ModelParser;

/**
 * Class Application
 * Collects file paths according to a config and run application
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
     * Run test generation
     *
     * @param $type
     */
    public function run($type)
    {
        $fileManager = new FileManager($this->getConfig($type), $this->getRootDir());
        $paths = $fileManager->getPaths();
        $parser = $this->getParser($type);
        $parser->generate($paths, $this->config[$type]);
    }

    /**
     * Return config response to test type
     *
     * @param $type
     * @return array
     */
    protected function getConfig($type)
    {
        return $this->config[$type];
    }

    /**
     * Return root dir from config
     *
     * @return array
     */
    protected function getRootDir()
    {
        return $this->config['rootDir'];
    }

    /**
     * Return required getGenerator, depends of test type
     *
     * @param $type
     * @return AbstractParser
     */
    private static function getParser($type)
    {
        if ('controllers' === $type) {
            return new ControllerParser();
        }
        return new ModelParser();
    }
}
