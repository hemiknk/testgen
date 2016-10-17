<?php
namespace Testgen\Generators;

use Testgen\View;

/**
 * Class CestAction
 *
 * @package Testgen\Generators
 */
class CestAction
{
    /**
     * @var string
     */
    protected $template = '';
    /**
     * @var $settings array
     */
    protected $settings = [];
    /**
     * @var mixed
     */
    protected $name;

    /**
     * CestAction constructor.
     *
     * @param $className
     * @param $settings array
     */
    public function __construct($className, array $settings)
    {
        $this->name = $this->removeSuffix($className, 'Cest');
        $this->settings = $settings;
    }

    /**
     * @return string
     */
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

        $template = (new View($this->getTemplate()))
            ->place('name', $this->name)
            ->place('namespace', $ns)
            ->place('actor', $actor)
            ->produce();
        $template .= $this->generateActions();
        $template .= $this->addClosingBrace();
        return $template;
    }

    /**
     * @param $namespace
     * @return string
     */
    protected function getNamespaceHeader($namespace)
    {
        return "namespace $namespace;\n";
    }

    /**
     * @return string
     */
    protected function generateActions()
    {
        $template = '';
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

    /**
     * @param $action
     * @return string
     */
    private function buildRoute($action)
    {
        $route = $this->camel2id(str_ireplace('Controller', '', $this->name)) . '/'
            . $this->camel2id(str_ireplace('action', '', $action));
        return $route;
    }

    /**
     * @param $name
     * @param string $separator
     * @param bool $strict
     * @return string
     */
    private function camel2id($name, $separator = '-', $strict = false)
    {
        $regex = $strict ? '/[A-Z]/' : '/(?<![A-Z])[A-Z]/';
        if ($separator === '_') {
            return trim(strtolower(preg_replace($regex, '_\0', $name)), '_');
        } else {
            return trim(strtolower(str_replace('_', $separator, preg_replace($regex, $separator . '\0', $name))), $separator);
        }
    }

    /**
     * @return string
     */
    private function addClosingBrace()
    {
        return <<<EOL
}

EOL;
    }

    /**
     * @param $className
     * @param $suffix
     * @return mixed
     */
    protected function removeSuffix($className, $suffix)
    {
        $className = preg_replace('~\.php$~', '', $className);
        return preg_replace("~$suffix$~", '', $className);
    }

    /**
     * @return string
     */
    private function getTemplate()
    {
        if ('' === $this->template) {
            $this->template = Template::get();
        }
        return $this->template;
    }
}