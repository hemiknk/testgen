<?php

namespace Testgen\Generators;

use Testgen\Test;

class TestsModelGenerator extends TestsControllerGenerator
{
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

    public function getTableFields($file)
    {
        $name = $this->getNamespace($file);
        $name = $name . '\\' . basename($file, '.php');
        include $file;
        try {
            $r = new \ReflectionClass($name);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return;
        }
        $doc = $r->getDocComment();
        preg_match_all('#@property(.*?)\n#s', $doc, $annotations);
        $model = new Test($file, $name, $annotations[1]);
        return $model;
    }
}