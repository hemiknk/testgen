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
     * @param array $settings
     */
    public function init(array $settings)
    {
        $this->name = $settings['className'];
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
        $namespace = $this->getNamespaceHeader();
        if ($namespace) {
            $namespace .= "use " . $this->settings['namespace'] . '\\' . $actor . ";\n";
        }
        if (array_key_exists('modelNamespace', $this->settings)) {
            $namespace .= "use " . $this->settings['modelNamespace']. ";";
        }

        $template = (new View($this->getTemplate()))
            ->place('name', $this->name)
            ->place('namespace', $namespace)
            ->place('actor', $actor)
            ->produce();
        $template .= $this->generateActions();
        $template .= $this->addClosingBrace();
        return $template;
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
     * @return string
     */
    protected function getNamespaceHeader()
    {
        $namespace = rtrim($this->settings['namespace'], '\\');
        return "namespace $namespace;\n";
    }

    /**
     * Return required generator, depends of test type
     *
     * @param $type
     * @return AbstractTest
     */
    public static function get($type)
    {
        if ('controllers' === $type) {
            return new UriTest();
        }
        return new ModelTest();
    }
}
