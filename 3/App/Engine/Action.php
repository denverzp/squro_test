<?php

namespace App\Engine;

class Action
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var mixed
     */
    protected $class;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $args = [];

    /**
     * Action constructor.
     *
     * @param $route
     * @param array $args
     */
    public function __construct($route, $args = [])
    {
        $parts = explode('@', str_replace('../', '', $route));

        $controller = array_shift($parts);

        $file = DIR_APPLICATION . 'Controller/' . str_replace('../', '', ucfirst($controller)) . '.php';

        if (is_file($file)) {
            $this->file = $file;

            $this->class = '\\App\\Controller\\' . preg_replace('/[^a-zA-Z0-9]/', '', $controller) . '';
        } else {
            trigger_error('Cannot find controller file! - ');
        }

        if ($args) {
            $this->args = $args;
        }

        $method = array_shift($parts);

        if ($method) {
            $this->method = $method;
        } else {
            $this->method = 'index';
        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getArgs()
    {
        return $this->args;
    }
}
