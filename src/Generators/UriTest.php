<?php
namespace Testgen\Generators;

use Testgen\Application;
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
        $routeCreator = Application::getRouteCreator();
        foreach ($this->settings['actions'] as $key => $action) {
            $route = $routeCreator->buildRoute($this->name, $action);
            $template .= (new View(Template::getAction()))
                ->place('action', $action)
                ->place('route', $route)
                ->produce();
        }
        return $template;
    }
}
