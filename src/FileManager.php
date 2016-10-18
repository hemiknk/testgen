<?php
/**
 * Testgen Component
 *
 */

/**
 * @namespace
 */
namespace Testgen;

/**
 * Class FileManager
 * Collect files on the specified paths
 *
 * @package Testgen
 */
class FileManager
{
    /**
     * Contains settings for controllers/models test generation
     *
     * @var array
     */
    protected $config = [];

    /**
     * Root folder project
     *
     * @var string
     */
    protected $rootDir = '';

    /**
     * FileManager constructor.
     *
     * @param array $config
     * @param $rootDir
     */
    public function __construct(array $config, $rootDir)
    {
        $this->config = $config;
        $this->rootDir = $rootDir;
    }

    /**
     * Return array with paths to controllers/models
     *
     * @return array
     */
    public function getPaths()
    {
        $files = [];
        foreach ($this->config['paths'] as $path) {
            $directory = $this->rootDir . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR;
            $directoryFiles = $this->exceptFiles($directory);
            foreach ($directoryFiles as $file) {
                if (!$this->isValidFile($file)) {
                    continue;
                }
                $files[] = $directory . $file;
            }
        }
        return $files;
    }

    /**
     * Exclude files according to settings
     *
     * @param $directory
     * @return array
     * @throws \Exception
     */
    protected function exceptFiles($directory)
    {
        if (!is_dir($directory)) {
            throw new \Exception("Directory not exist $directory");
        }
        return array_diff(scandir($directory), $this->config['except'], ['..', '.']);
    }

    /**
     * Return true if file contains required world
     *
     * @param $fileName
     * @return bool
     */
    protected function isValidFile($fileName)
    {
        return false != preg_match("/{$this->config['keyWord']}/", $fileName);
    }
}
