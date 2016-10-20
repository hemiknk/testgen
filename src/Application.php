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

    protected static $routeCreator = null;

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
        self::$routeCreator = AbstractRouteCreator::get(/*$type*/'simple');
        self::$routeCreator->init($this->config['route']);

        $fileManager = new FileManager($this->getConfig($type), $this->getRootDir());
        $paths = $fileManager->getPaths();
        $parser = AbstractParser::get($type);
        $components = $parser->getComponents($paths, $this->config[$type]);

        foreach ($components as $component) {
            $settings = $this->getSettings($component);
            $testGenerator = AbstractTest::get($type);
            $testGenerator->init($settings);
            $fileContent = $testGenerator->produce();
            $this->save($fileContent, $component);
        }
    }

    /**
     * Required settings for test file
     *
     * @param $component
     * @return array
     */
    protected function getSettings($component)
    {
        $namespaceAsArray = explode('\\', $component->name);
        $typeTest = array_shift($namespaceAsArray);
        $className = end($namespaceAsArray);

        $settings = [
            'actorName' => 'AcceptanceTester',
            'namespace' => $this->config['testsNamespace'] . "\\$typeTest",
            'actions' => $component->actions,
            'modelNamespace' => $component->name,
            'className' => $className,
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
     * @param $fileContent
     * @param $file
     */
    protected function save($fileContent, $file)
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

    /**
     * @return null | AbstractRouteCreator
     */
    public static function getRouteCreator()
    {
        return self::$routeCreator;
    }

}
