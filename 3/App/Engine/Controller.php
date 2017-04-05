<?php

namespace App\Engine;

/**
 * Class Controller.
 *
 * @property Request $request
 * @property Session $session
 * @property Log $log
 * @property Db $db
 */
class Controller
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var
     */
    protected $template;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Controller constructor.
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

    /**
     * @param array  $data
     * @param string $template
     *
     * @return string
     */
    protected function render()
    {
        $template = DIR_TEMPLATE . '' . $this->template . '.templ.php';

        if (file_exists($template)) {
            extract($this->data);

            ob_start();

            require DIR_TEMPLATE . 'header.templ.php';
            require $template;
            require DIR_TEMPLATE . 'footer.templ.php';

            $output = ob_get_contents();

            ob_end_clean();

            echo $output;
        } else {
            trigger_error('Error: Could not load template ' . $template . '!', E_ERROR);
        }
    }
}
