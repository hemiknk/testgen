<?php

namespace Testgen;

class FileManager
{
    /**
     * Contains paths to controllers or models
     *
     * @var array
     */
    protected $config = [];

    protected $rootDir = '';

    public function __construct(array $paths, $rootDir)
    {
        $this->config = $paths;
        $this->rootDir = $rootDir;
    }

    /**
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
     * @param $fileName
     * @return bool
     */
    protected function isValidFile($fileName)
    {
        return false != preg_match("/{$this->config['keyWord']}/", $fileName);
    }

}