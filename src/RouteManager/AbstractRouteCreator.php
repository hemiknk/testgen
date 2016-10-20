<?php

namespace Testgen\RouteManager;

abstract class AbstractRouteCreator
{
    /**
     * @var array $settings
     */
    protected $settings = [];

    /**
     * @param $settings
     */
    public function init($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Return route using controller and action
     * Build route like controller-name/action-name
     *
     * @param $controller
     * @param $action
     * @return string
     */
    public function buildRoute($controller, $action)
    {
        $route = $this->camel2id(str_ireplace('Controller', '', $controller)) . '/'
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

    /**
     * @param $type
     * @return AbstractRouteCreator
     */
    public static function get($type)
    {
        $className = 'Testgen\\RouteManager\\' . ucfirst($type) . 'Creator';
        return new $className();
    }
}