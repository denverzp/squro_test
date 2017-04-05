<?php

namespace App\Engine;

/**
 * Class Template.
 */
class Template
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @param $filename
     *
     * @return string
     */
    public function fetch($filename)
    {
        $file = DIR_TEMPLATE . $filename . '.templ.php';

        if (file_exists($file)) {
            extract($this->data);

            ob_start();

            include $file;

            $content = ob_get_contents();

            ob_end_clean();

            return $content;
        } else {
            trigger_error('Error: Could not load template ' . $file . '!');
        }

        return false;
    }
}
