<?php

namespace Testgen;

class Test
{
    public $path;
    public $name;
    public $actions = [];

    public function __construct($path, $name, $actions)
    {
        $this->path = $path;
        $this->name = $name;
        $this->actions = $actions;
    }
}