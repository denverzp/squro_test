<?php

namespace App\Engine;

/**
 * Class Model.
 *
 * @property Request $request
 * @property Session $session
 * @property Log $log
 * @property Db $db
 */
class Model
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * Model constructor.
     *
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param $key
     */
    public function __get($key)
    {
        return $this->registry->get($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }
}
