<?php

namespace App\Engine;

/**
 * Class Request.
 */
class Request
{
    /**
     * @var array
     */
    public $get = [];

    /**
     * @var array
     */
    public $post = [];

    /**
     * @var array
     */
    public $cookie = [];

    /**
     * @var array
     */
    public $files = [];

    /**
     * @var array
     */
    public $server = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->get = $_GET;

        $this->post = $_POST;

        $this->cookie = $_COOKIE;

        $this->files = $_FILES;

        $this->server = $_SERVER;
    }
}
