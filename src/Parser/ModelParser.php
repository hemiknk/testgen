<?php

namespace Testgen\Parser;

use Testgen\Generators\ModelTest;
use Testgen\Test;

/**
 * Class ModelParser
 *
 * @package Testgen\Generators
 */
class ModelParser extends AbstractParser
{
    /**
     * Return test generator for model
     *
     * @param $generatorName
     * @param $settings
     * @return ModelTest
     */
    protected function getGenerator($generatorName, $settings)
    {
        return new ModelTest($generatorName, $settings);
    }

    /**
     * Collect required components from files
     * 
     * @param $files
     * @return array
     */
    function getFileComponents($files)
    {
        $tableFields = [];
        foreach ($files as $file) {
            $tableFields[] = $this->getTableFields($file);
        }
        return $tableFields;
    }

    /**
     * Get fields name from annotation
     * 
     * @param $file
     * @return Test|int
     */
    public function getTableFields($file)
    {
        $name = $this->getNamespace($file);
        $name = $name . '\\' . basename($file, '.php');
        include $file;
        try {
            $r = new \ReflectionClass($name);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return 1;
        }
        $doc = $r->getDocComment();
        preg_match_all('#@property(.*?)\n#s', $doc, $annotations);
        $model = new Test($file, $name, $annotations[1]);
        return $model;
    }
}
