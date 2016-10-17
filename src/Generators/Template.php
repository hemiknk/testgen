<?php

namespace Testgen\Generators;

/**
 * Class Template
 *
 * @package Testgen\Generators
 */
class Template
{
    private static $template = <<<EOF
<?php
{{namespace}}

class {{name}}Cest
{
    public function _before({{actor}} \$I)
    {
    }

    public function _after({{actor}} \$I)
    {
    }

EOF;

    public static function get()
    {
        return self::$template;
    }

}