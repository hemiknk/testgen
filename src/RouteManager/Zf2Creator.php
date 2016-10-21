<?php

namespace Testgen\RouteManager;
use Zend\View\Helper\Url;

class Zf2Creator extends AbstractRouteCreator
{
    protected $routes = [];

    protected $routsName = [];

    /**
     * @param $settings
     */
    public function init($settings)
    {
        parent::init($settings);
        $this->prepareRouts();
    }

    /**
     * Save routs from config in $this->routes
     */
    private function prepareRouts()
    {
        $this->prepareRoutsFromConfig();
        $modules = scandir($this->settings['modules']);
        $modules = array_diff($modules, ['..', '.']);
        foreach ($modules as $module) {
            $path = $this->settings['modules'] . '/' . $module . '/config/module.config.php';
            if (file_exists($path)) {
                $this->addRoutes($path);
            }
        }
    }

    /**
     * Add routs to $this->routes from custom paths
     */
    protected function prepareRoutsFromConfig()
    {
        foreach ($this->settings['paths'] as $path) {
            $this->addRoutes($path);
        }
    }

    /**
     * Add routs to $this->routes
     *
     * @param $path
     */
    protected function addRoutes($path)
    {
        $routes = include $path;
        if ($routes = $this->getRoutes($routes)) {
            $this->routes = array_merge($this->routes, $routes);
            $this->createRoutesArray($routes);
        }
    }

    /**
     * Return routs if they exit in config
     *
     * @param $routes
     * @return bool
     *
     */
    protected function getRoutes($routes)
    {
        if (array_key_exists('router', $routes) && array_key_exists('routes', $routes['router'])) {
            return $routes['router']['routes'];
        }
        return false;
    }

    /**
     * Make array like 'simple-url' => 'url/from/config'
     *
     * @param $routes
     */
    protected function createRoutesArray($routes)
    {
        foreach ($routes as $name => $route) {
            $this->routsName[$name] = $this->parseRoute($route['options']);
        }
    }

    /**
     * Build simple route
     *
     * @param $option
     * @return string
     */
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
            $treeRouter = $this->createTreeRouter();
            $treeRouter->addRoutes($this->routes);
            $urlHelper = new Url();
            $urlHelper->setRouter($treeRouter);
            $routeName = in_array($route, $this->routsName) ? array_search($route, $this->routsName) : '';
            return $urlHelper($routeName);

        } catch (\Exception $e) {
            return $route;
        }
    }

    /**
     * @return \Zend\Router\Http\TreeRouteStack|\Zend\Router\Http\TreeRouteStack
     * @throws \Exception
     */
    protected function createTreeRouter()
    {
        if (class_exists('\\Zend\\Router\\Http\\TreeRouteStack')) {
            return new \Zend\Router\Http\TreeRouteStack();
        } elseif (class_exists('\\Zend\\Mvc\\Router\\Http\\TreeRouteStack')) {
            return new \Zend\Mvc\Router\Http\TreeRouteStack();
        }
        throw new \Exception('TreeRouteStack  not exist');
    }
}
