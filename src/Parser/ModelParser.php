<?php

namespace Testgen\Parser;

/**
 * Class ModelParser
 *
 * @package Testgen\Generators
 */
class ModelParser extends AbstractParser
{
    protected function getActions($file, $name)
    {
        include $file;
        try {
            $r = new \ReflectionClass($name);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return 1;
        }
        $doc = $r->getDocComment();
        preg_match_all('#@property(.*?)\n#s', $doc, $annotations);
        return $annotations[1];
    }
}
