<?php

namespace App\Engine;

/**
 * very very simple routing.
 */
class Route
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Route constructor.
     *
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        //access to Request
        $this->request = $registry->get('request');

        //default route
        $this->route = 'HomeController@index';

        //AJAX - all action
        if(true == array_key_exists('action', $this->request->post)){
            switch($this->request->post['action']){
                case 'list':
                    $this->route = 'TreesController@index';
                    break;

                case 'store':
                    $this->route = 'TreesController@store';
                    break;

                case 'update':
                    $this->route = 'TreesController@update';
                    break;

                case 'order':
                    $this->route = 'TreesController@order';
                    break;

                case 'destroy':
                    $this->route = 'TreesController@destroy';
                    break;
            }
        }
    }

    /**
     * Controller@return string.
     */
    public function getRoute()
    {
        return $this->route;
    }
}
