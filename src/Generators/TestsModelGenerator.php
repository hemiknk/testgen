<?php

namespace Testgen\Generators;

use Testgen\Test;

/**
 * Class TestsModelGenerator
 *
 * @package Testgen\Generators
 */
class TestsModelGenerator extends TestsControllerGenerator
{
    /**
     * @param $cestName
     * @param $settings
     * @return CestModel
     */
    protected function getCest($cestName, $settings)
    {
        return new CestModel($cestName, $settings);
    }

    /**
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