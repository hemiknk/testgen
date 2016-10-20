<?php

namespace Testgen\RouteManager;

class Zf2Creator extends AbstractRouteCreator
{
    protected $routeArray = [];

    public function init($settings)
    {
        parent::init($settings);
        $this->prepareRouts();
    }

    private function prepareRouts()
    {
        foreach ($this->settings['paths'] as $path) {
            $routes = include_once $path;
            if ($routes = $this->getRoutes($routes)) {
                $this->createRoutesArray($routes);
            }
        }
    }

    protected function getRoutes($routes)
    {
        if (array_key_exists('router', $routes) && array_key_exists('routes', $routes['router'])) {
            return $routes['router']['routes'];
        }
        return false;
    }

    protected function createRoutesArray($routes)
    {
        foreach ($routes as $route) {
            if(!$this->isLiteral($route['type'])){
                continue;
            }
            $this->parseRoute($route['options']);
        }
    }

    protected function parseRoute($option)
    {
        $simpleRoute = parent::buildRoute($option['defaults']['controller'], $option['defaults']['action']);
        $this->routeArray[$simpleRoute] = $option['route'];
    }

    protected function isLiteral($type)
    {
        return 'literal' === $type || 'Zend\Mvc\Router\Http\Literal' === $type ? true : false;
    }

    /**
     * Return route using controller and action
     *
     * @param $controller
     * @param $action
     * @return string
     */
    public function buildRoute($controller, $action)
    {
        $route = parent::buildRoute($controller, $action);
        if (array_key_exists($route, $this->routeArray)){
            $route = $this->routeArray[$route];
        }
        return $route;
    }

}
