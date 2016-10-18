<?php

namespace Testgen\Generators;

use Testgen\Template;
use Testgen\View;

/**
 * Class AbstractTest
 *
 * @package Testgen\Generators
 */
abstract class AbstractTest
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
     * Generate test actions
     *
     * @return string
     */
    abstract protected function generateActions();

    /**
     * UriTest constructor.
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
     * Generate one test file
     *
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
     * Remove suffix from file name
     *
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
     * Returns a common template for the test
     *
     * @return string
     */
    private function getTemplate()
    {
        if ('' === $this->template) {
            $this->template = Template::get();
        }
        return $this->template;
    }

    /**
     * Return brace
     *
     * @return string
     */
    private function addClosingBrace()
    {
        return <<<EOL
}

EOL;
    }

    /**
     * Return namespace for file
     * 
     * @param $namespace
     * @return string
     */
    protected function getNamespaceHeader($namespace)
    {
        return "namespace $namespace;\n";
    }
}
