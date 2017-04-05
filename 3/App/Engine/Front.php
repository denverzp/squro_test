<?php

namespace App\Engine;

class Front
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Log
     */
    protected $log;

    /**
     * Front constructor.
     *
     * @param $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;

        $this->log = $registry->get('log');
    }

    /**
     * @param Action $action
     */
    public function execute(Action $action)
    {
        $file = $action->getFile();

        $class = $action->getClass();

        $method = $action->getMethod();

        $args = $action->getArgs();

        $action = '';

        if (file_exists($file)) {
            require_once $file;

            $controller = new $class($this->registry);

            if (is_callable([$controller, $method])) {
                $action = call_user_func_array([$controller, $method], $args);
            } else {
                $this->log->write('Not callable class or method! - ' . $controller . '@' . $method);

                throw new \ErrorException('Not callable class!');
            }
        } else {
            $this->log->write('Cannot find controller file! - ' . $file);

            throw new \ErrorException('Cannot find controller file!');
        }

        return $action;
    }
}
