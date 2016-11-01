<?php
/**
 * Testgen Component
 *
 */

/**
 * @namespace
 */
namespace Testgen;

use Testgen\Generators\AbstractTest;
use Testgen\Parser\AbstractParser;
use Testgen\RouteManager\AbstractRouteCreator;

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
     * @param $testType
     */
    public function run($testType)
    {
        $fileManager = new FileManager($this->getConfig($testType), $this->getRootDir());
        $parser = AbstractParser::get($testType);
        $paths = $fileManager->getPaths();
        $components = $parser->getComponents($paths, $this->config[$testType]);

        foreach ($components as $component) {
            $settings = $this->getSettings($testType, $component);
            $testGenerator = AbstractTest::get($settings);
            $fileContent = $testGenerator->produce();
            $this->save($fileContent, $component);
        }

        $this->createLoginFile();
    }

    /**
     * Required settings for test file
     *
     * @param $type
     * @param $component
     * @return array
     */
    protected function getSettings($type, $component)
    {
        $namespaceAsArray = explode('\\', $component->name);
        $typeTest = array_shift($namespaceAsArray);
        $className = end($namespaceAsArray);

        $settings = [
            'actorName' => 'AcceptanceTester',
            'namespace' => $this->config['testsNamespace'] . "\\$typeTest",
            'AcceptanceTesterFullName' => $this->config['AcceptanceTesterFullName'],
            'actions' => $component->actions,
            'modelNamespace' => $component->name,
            'className' => $className,
            'type' => $type,
            'config' => $this->getConfig($type)['route'],
        ];
        return $settings;
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
     * Save generated test to file
     *
     * @param $fileContent Test
     * @param $file
     */
    protected function save($fileContent, $file)
    {
        $nameAsArray = explode('\\', $file->name);
        $filename = $this->buildTestFileName(array_pop($nameAsArray));
        $path = $this->buildPath(array_shift($nameAsArray));

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $filename = $path . $filename;

        if (!file_exists($filename)) {
            file_put_contents($filename, $fileContent);
            echo "File created $filename" . PHP_EOL;
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

    protected function createLoginFile()
    {
        $fileContent = Template::getLogin();
        $path = $this->buildPath('frontend');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $filename = $path . 'Login.php';

        if (!file_exists($filename)) {
            file_put_contents($filename, $fileContent);
            echo "File created $filename" . PHP_EOL;
        }
    }

}
