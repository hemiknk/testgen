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
     * AbstractTest constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings)
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
            $namespace .= "use " . $this->settings['AcceptanceTesterFullName'] . $actor . ";\n";
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
     * @param $settings
     * @return AbstractTest
     */
    public static function get($settings)
    {
        if ('controllers' === $settings['type']) {
            return new UriTest($settings);
        }
        return new ModelTest($settings);
    }
}
