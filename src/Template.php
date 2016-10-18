<?php

namespace Testgen;

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

    private static $actionTemplate = <<< EOF

    public function {{action}}Test(AcceptanceTester \$I)
    {
        \$I->wantTo('Test action {{action}}');
        \$I->amOnPage('{{route}}');
        \$I->seeResponseCodeIs(200);
    }

EOF;

    private static $modelTemplate = <<<EOL
                  
    public function {{name}}Test(AcceptanceTester \$I)
    {
        \$data = [
            {{data}}
        ];
        \$I->dontSeeInDatabase('{{tableName}}', \$data);
        \${{tableName}} = new {{name}}();
        \${{tableName}}->load(\$data);
        \${{tableName}}->save();
        \$I->seeInDatabase('{{tableName}}', \$data);
    }

EOL;

    /**
     * Return template for action test case
     *
     * @return string
     */
    public static function getAction()
    {
        return static::$actionTemplate;
    }

    /**
     * Return template for model test case
     *
     * @return string
     */
    public static function getModel()
    {
        return static::$modelTemplate;
    }

    /**
     * Return template for test file
     *
     * @return string
     */
    public static function get()
    {
        return self::$template;
    }
}
