<?php

namespace Testgen\Parser;

use Testgen\Generators\AbstractTest;
use Testgen\Test;

/**
 * Class AbstractParser
 *
 * @package Testgen\Parser
 */
abstract class AbstractParser
{
    /**
     * Contains config 
     * 
     * @var array
     */
    protected $config = [];

    abstract protected function getActions($file, $name);

    /**
     * Return array with file components (actions/table fields)
     *
     * @param $files
     * @param $config
     * @return array
     */
    public function getComponents($files, $config)
    {
        $components = [];
        $this->config = $config;
        foreach ($files as $file) {
            $components[] = $this->getFileComponents($file);
        }
        return $components;
    }

    /**
     * Return component Test containing path to file
     *
     * @param $file
     * @return Test
     */
    protected function getFileComponents($file)
    {
        $name = $this->getNamespace($file);
        $name = $name . '\\' . basename($file, '.php');
        $actions = $this->getActions($file, $name);
        return new Test($file, $name, $actions);
    }

    /**
     * Read namespace from file
     * 
     * @param $file
     * @return string
     * @throws \Exception
     */
    protected function getNamespace($file)
    {
        $handle = fopen($file, "r");
        if (!$handle) {
            throw new \Exception("Cund't read file $file");
        }
        $namespace = '';
        while (false !== ($line = fgets($handle))) {
            if (0 === strpos($line, 'namespace')) {
                $parts = explode(' ', $line);
                $namespace = rtrim(trim($parts[1]), ';');
                break;
            }
        }
        fclose($handle);
        return $namespace;
    }

    /**
     * Return required generator, depends of test type
     *
     * @param $type
     * @return AbstractParser
     */
    public static function get($type)
    {
        if ('controllers' === $type) {
            return new ControllerParser();
        }
        return new ModelParser();
    }
}