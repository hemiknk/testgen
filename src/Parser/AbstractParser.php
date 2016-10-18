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

    /**
     * Collect required components from files
     *
     * @param $files array
     * @return array
     */
    abstract public function getFileComponents($files);

    /**
     * Must return test generator
     *
     * @param $generatorName
     * @param $settings
     * @return AbstractTest
     */
    abstract protected function getGenerator($generatorName, $settings);

    /**
     * Generate and save test to file
     *
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
     * Run test generation for file
     *
     * @param $element Test
     * @return string
     */
    public function generateForFile($element)
    {
        $namespaceAsArray = explode('\\', $element->name);
        $typeTest = array_shift($namespaceAsArray);
        $testType = end($namespaceAsArray);

        $settings = [
            'actorName' => 'AcceptanceTester',
            'namespace' => $this->config['testsNamespace'] . "\\$typeTest",
            'actions' => $element->actions,
            'modelNamespace' => $element->name,
        ];

        $generator = $this->getGenerator($testType, $settings);
        return $generator->produce();
    }

    /**
     * Save generated test to file
     *
     * @param $fileContent
     * @param $file
     */
    protected function saveToFile($fileContent, $file)
    {
        $nameAsArray = explode('\\', $file->name);
        $filename = $this->buildTestFileName(array_pop($nameAsArray));
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
     * Build path for saving file
     *
     * @param $typeTest
     * @return string
     */
    protected function buildPath($typeTest)
    {
        $projectRootDir = $this->config['testsFolder'];
        return $projectRootDir . DIRECTORY_SEPARATOR . $typeTest  . DIRECTORY_SEPARATOR . "acceptance" . DIRECTORY_SEPARATOR;
    }

    /**
     * Create name for test file
     *
     * @param $cestName
     * @return string
     */
    protected function buildTestFileName($cestName)
    {
        return $cestName . 'Cest.php';
    }

}