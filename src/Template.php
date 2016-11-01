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
        \$loginPage = Login::openBy(\$I);
        \$loginPage->login('', '');
    }

    public function _after({{actor}} \$I)
    {
    }

EOF;

    /**
     * @var string
     */
    private static $actionTemplate = <<< EOF

    public function {{action}}Test(AcceptanceTester \$I)
    {
        \$I->wantTo('Test action {{action}}');
        \$I->amOnPage('{{route}}');
        \$I->seeResponseCodeIs(200);
    }

EOF;

    /**
     * @var string
     */
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
     * @var string
     */
    private static $loginTemplate = <<<EOL
<?php
namespace tests\codeception\frontend\acceptance;

class Login
{
    /**
     * @var \Codeception\Actor the testing guy object
     */
    protected \$actor;

    /**
     * Constructor.
     *
     * @param \Codeception\Actor \$I the testing guy object
     */
    public function __construct(\$I)
    {
        \$this->actor = \$I;
    }

    /**
     * @param string \$username
     * @param string \$password
     */
    public function login(\$username, \$password)
    {
        \$this->actor->amOnPage('/user/login');
        \$this->actor->fillField('input[name="login-form[login]"]', \$username);
        \$this->actor->fillField('input[name="login-form[password]"]', \$password);
        \$this->actor->click('button[type=submit]');
        \$this->actor->expectTo('see that user is logged');
        \$this->actor->see('Logout (Frirst_user)', 'form button[type=submit]');
    }
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

    /**
     * Return template for login file
     *
     * @return string
     */
    public static function getLogin()
    {
        return self::$loginTemplate;
    }
}
