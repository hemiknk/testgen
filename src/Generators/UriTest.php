<?php
namespace Testgen\Generators;

use Testgen\RouteManager\AbstractRouteCreator;
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
     * @var AbstractRouteCreator
     */
    protected $routeCreator = null;

    /**
     * @param AbstractRouteCreator $routeCreator
     */
    public function setRouteCreator(AbstractRouteCreator $routeCreator)
    {
        $this->routeCreator = $routeCreator;
    }

    public function __construct(array $settings)
    {
        parent::__construct($settings);
        $this->routeCreator = AbstractRouteCreator::get($this->settings['config']['type']);
        $this->routeCreator->init($settings['config']);
    }

    /**
     * Generate test actions
     *
     * @return string
     */
    protected function generateActions()
    {
        $template = '';
        foreach ($this->settings['actions'] as $key => $action) {
            $route = $this->routeCreator->buildRoute($this->name, $action);
            $template .= (new View(Template::getAction()))
                ->place('action', $action)
                ->place('route', $route)
                ->produce();
        }
        return $template;
    }
}
