<?php

namespace App\Engine\Traits;

/**
 * Description of Redirect.
 *
 * @author Ice
 */
trait Redirect
{
    /**
     * Redirect.
     *
     * @param string $url
     * @param int    $status
     */
    protected function redirect($url, $status = 302)
    {
        header('Status: ' . $status);
        header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url));
        exit();
    }
}
