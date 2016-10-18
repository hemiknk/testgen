<?php
namespace Testgen\Parser;

use Testgen\Generators\UriTest;
use Testgen\Test;

/**
 * Class ControllerParser
 *
 * @package Testgen\Generators
 */
class ControllerParser extends AbstractParser
{
    /**
     * @param $generatorName
     * @param $settings
     * @return UriTest
     */
    protected function getGenerator($generatorName, $settings)
    {
        return new UriTest($generatorName, $settings);
    }
    
    /**
     * Collect required components from files
     *
     * @param $files array
     * @return array
     */
    public function getFileComponents($files)
    {
        $controllerActions = [];
        foreach ($files as $file) {
            $controllerActions[] = $this->getFileActions($file);
        }
        return $controllerActions;
    }

    /**
     * @param $file
     * @return Test
     */
    protected function getFileActions($file)
    {
        $name = $this->getNamespace($file);
        $name = $name . '\\' . basename($file, '.php');
        $actions = $this->getControllerActions($file, $name);
        return new Test($file, $name, $actions);
    }

    /**
     * Return all actions for single controller
     *
     * @param $file
     * @param $name
     * @return array
     * @throws \Exception
     * @internal param $controller
     */
    protected function getControllerActions($file, $name)
    {
        require $file;
        if (!class_exists($name)) {
            throw new \Exception("Controller $file not exist");
        }
        $reflection = new \ReflectionClass($name);
        $methods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (!$this->isParentMethod($reflection, $method)
                && $this->isActionMethod($method)
                && $this->isAllowedMethod($method)
            ) {
                $methods[] = $method->name;
            }
        }
        return $methods;
    }

    /**
     * Check whether the parent method
     *
     * @param $reflection \ReflectionClass
     * @param $method
     * @return bool
     */
    private function isParentMethod($reflection, $method)
    {
        return $reflection->getName() != $method->class ? true : false;
    }

    /**
     * Checks whether the method of action
     *
     * @param $method
     * @return bool|int
     */
    private function isActionMethod($method)
    {
        return false !== stripos($method->name, 'action');
    }

    /**
     * Return false if method disallowed in config
     *
     * @param $method
     * @return bool
     */
    public function isAllowedMethod($method)
    {
        $disabledMethods = $this->config['exceptActions'];
        if (in_array($method->name, $disabledMethods)) {
            return false;
        }

        if (array_key_exists($method->class, $disabledMethods)) {
            if (in_array($method->name, $disabledMethods[$method->class])) {
                return false;
            }
        }
        return true;
    }
}
