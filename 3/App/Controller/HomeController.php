<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Engine\Registry;
use App\Model\Table;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);

        //check for needed tables - if not exist create it
        $table = new Table($registry);

        $table->checkExistTables();
    }

    /**
     * show homepage.
     */
    public function index()
    {
        //set homepage template
        $this->template = 'home';

        $this->render();
    }
}
