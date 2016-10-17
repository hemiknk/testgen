<?php
namespace Testgen\Generators;

use Testgen\Test;

/**
 * Class TestsControllerGenerator
 *
 * @package Testgen\Generators
 */
class TestsControllerGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param $files
     * @param $config
     */
    public function generate($files, $config)
    {
        $this->config = $config;
        $components = $this->getFileComponents($files);
        foreach ($components as $component) {
            $fileContent = $this->generateForFile($component);
            $this->saveToFile($fileContent, $component);
        }
    }

    /**
     * @param $files
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
     * @param $reflection \ReflectionClass
     * @param $method
     * @return bool
     */
    private function isParentMethod($reflection, $method)
    {
        return $reflection->getName() != $method->class ? true : false;
    }

    /**
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

    /**
     * @param $element Test
     * @return string
     */
    public function generateForFile($element)
    {
        $namespaceAsArray = explode('\\', $element->name);
        $typeTest = array_shift($namespaceAsArray);
        $cestName = end($namespaceAsArray);

        $settings = [
            'actorName' => 'AcceptanceTester',
            'namespace' => $this->config['testsNamespace'] . "\\$typeTest",
            'actions' => $element->actions,
            'modelNamespace' => $element->name,
        ];

        $cest = $this->getCest($cestName, $settings);
        return $cest->produce();
    }

    /**
     * @param $cestName
     * @param $settings
     * @return CestAction
     */
    protected function getCest($cestName, $settings)
    {
        return new CestAction($cestName, $settings);
    }

    /**
     * @param $fileContent
     * @param $file
     */
    protected function saveToFile($fileContent, $file)
    {
        $nameAsArray = explode('\\', $file->name);
        $filename = $this->buildCestFileName(array_pop($nameAsArray));
        $path = $this->buildPath(array_shift($nameAsArray));

        if (!is_dir($path)) {
            mkdir($path);
        }
        $filename = $path . $filename;

        if (!file_exists($filename)) {
            file_put_contents($filename, $fileContent);
        }
    }

    /**
     * @param $typeTest
     * @return string
     */
    protected function buildPath($typeTest)
    {
        $projectRootDir = $this->config['testsFolder'];
        return $projectRootDir . DIRECTORY_SEPARATOR . $typeTest  . DIRECTORY_SEPARATOR . "acceptance" . DIRECTORY_SEPARATOR;
    }

    /**
     * @param $cestName
     * @return string
     */
    protected function buildCestFileName($cestName)
    {
        return $cestName . 'Cest.php';
    }
}