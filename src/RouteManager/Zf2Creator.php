<?php

namespace Testgen\RouteManager;


use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\View\Helper\Url;

class Zf2Creator extends AbstractRouteCreator
{
    protected $routes = [];

    protected $routsName = [];

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
                $this->routes = array_merge($this->routes, $routes);
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
        foreach ($routes as $name => $route) {
            $this->routsName[$name] = $this->parseRoute($route['options']);
        }
    }

    protected function parseRoute($option)
    {
        return parent::buildRoute($option['defaults']['controller'], $option['defaults']['action']);
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
        try {
            $treeRouter = new TreeRouteStack();
            $treeRouter->addRoutes($this->routes);
            $urlHelper = new Url();
            $urlHelper->setRouter($treeRouter);
            $routeName = in_array($route, $this->routsName) ? array_search($route, $this->routsName) : '';
            return $urlHelper($routeName);

        } catch (\Exception $e) {
            return $route;
        }
    }
}
