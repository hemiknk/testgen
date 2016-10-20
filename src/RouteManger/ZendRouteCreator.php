<?php

namespace Testgen\RouteManager;

class ZendRouteCreator extends AbstractRouteCreator
{

    /**
     * Return route using controller and action
     *
     * @param $controller
     * @param $action
     * @return string
     */
    public function buildRoute($controller, $action)
    {
        return $this->findRoute();

    }

}
