<?php
namespace Testgen\Generators;

use Testgen\Template;
use Testgen\View;

/**
 * Class UriTest
 *
 * @package Testgen\Generators
 */
class UriTest extends AbstractTest
{
    /**
     * Generate test actions
     *
     * @return string
     */
    protected function generateActions()
    {
        $template = '';
        foreach ($this->settings['actions'] as $key => $action) {
            $route = $this->buildRoute($action);
            $template .= (new View(Template::getAction()))
                ->place('action', $action)
                ->place('route', $route)
                ->produce();
        }
        return $template;
    }

    /**
     * Build route like controller-name/action-name
     *
     * @param $action
     * @return string
     */
    protected function buildRoute($action)
    {
        $route = $this->camel2id(str_ireplace('Controller', '', $this->name)) . '/'
            . $this->camel2id(str_ireplace('action', '', $action));
        return $route;
    }

    /**
     * Replace camel to '-' separated test
     *
     * @param $name
     * @param string $separator
     * @param bool $strict
     * @return string
     */
    protected function camel2id($name, $separator = '-', $strict = false)
    {
        $regex = $strict ? '/[A-Z]/' : '/(?<![A-Z])[A-Z]/';
        if ($separator === '_') {
            return trim(strtolower(preg_replace($regex, '_\0', $name)), '_');
        } else {
            return trim(strtolower(str_replace('_', $separator, preg_replace($regex, $separator . '\0', $name))), $separator);
        }
    }
}
