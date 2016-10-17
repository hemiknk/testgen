<?php
namespace Testgen\Generators;

use Testgen\Template;

class CestAction
{

    protected $template = <<<EOF
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

    protected $settings;
    protected $name;

    public function __construct($className, $settings)
    {
        $this->name = $this->removeSuffix($className, 'Cest');
        $this->settings = $settings;
    }

    public function produce()
    {
        $actor = $this->settings['actorName'];
        $namespace = rtrim($this->settings['namespace'], '\\');
        $ns = $this->getNamespaceHeader($namespace);
        if ($ns) {
            $ns .= "use " . $this->settings['namespace'] . '\\' . $actor . ";\n";
        }
        if (array_key_exists('modelNamespace', $this->settings)) {
            $ns .= "use " . $this->settings['modelNamespace']. ";";
        }

        $template = (new Template($this->template))
            ->place('name', $this->name)
            ->place('namespace', $ns)
            ->place('actor', $actor)
            ->produce();
        $template = $this->generateActions($template);
        return $this->addClosingBrace($template);
    }

    protected function getNamespaceHeader($namespace)
    {
        return "namespace $namespace;\n";
    }

    protected function generateActions($template)
    {
        foreach ($this->settings['actions'] as $key => $action) {
            $template .= <<<EOL
            
    public function {$action}Test(AcceptanceTester \$I)
    {
        \$I->wantTo('Test action $action');
        \$I->amOnPage('{$this->buildRoute($action)}');
        \$I->seeResponseCodeIs(200);
    }

EOL;
        }
        return $template;
    }

    private function buildRoute($action)
    {
        $route = $this->camel2id(str_ireplace('Controller', '', $this->name)) . '/'
            . $this->camel2id(str_ireplace('action', '', $action));
        return $route;
    }

    private function camel2id($name, $separator = '-', $strict = false)
    {
        $regex = $strict ? '/[A-Z]/' : '/(?<![A-Z])[A-Z]/';
        if ($separator === '_') {
            return trim(strtolower(preg_replace($regex, '_\0', $name)), '_');
        } else {
            return trim(strtolower(str_replace('_', $separator, preg_replace($regex, $separator . '\0', $name))), $separator);
        }
    }

    private function addClosingBrace($template)
    {
        $template .= <<<EOL
}

EOL;
        return $template;
    }

    protected function removeSuffix($className, $suffix)
    {
        $className = preg_replace('~\.php$~', '', $className);
        return preg_replace("~$suffix$~", '', $className);
    }

}