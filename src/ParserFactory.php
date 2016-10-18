<?php

namespace Testgen;

use Testgen\Parser\AbstractParser;
use Testgen\Parser\ControllerParser;
use Testgen\Parser\ModelParser;

class ParserFactory
{
    /**
     * Return required getGenerator, depends of test type
     *
     * @param $type
     * @return AbstractParser
     */
    public static function get($type)
    {
        if ('controllers' === $type) {
            return new ControllerParser();
        }
        return new ModelParser();
    }
}
