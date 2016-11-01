<?php
/**
 * Testgen Component
 *
 */

/**
 * @namespace
 */
namespace Testgen;

/**
 * Class Test
 *
 * Container for single info block of class or module
 * @package Testgen
 */
class Test
{
    /**
     * @var $path string
     */
    public $path = '';
    /**
     * @var $name string
     */
    public $name = '';
    /**
     * @var $actions array
     */
    public $actions = [];

    /**
     * Test constructor.
     *
     * @param $path
     * @param $name
     * @param $params
     */
    public function __construct($path = '', $name = '', $params = '')
    {
        $this->path = $path;
        $this->name = $name;
        $this->actions = $params;
    }
}
