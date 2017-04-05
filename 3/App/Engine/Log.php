<?php

namespace App\Engine;

/**
 * Class Log.
 */
class Log
{
    /**
     * @var
     */
    private $filename;

    /**
     * Log constructor.
     *
     * @param string $filename
     */
    public function __construct($filename = 'global.log')
    {
        $this->filename = $filename;
    }

    /**
     * @param $message
     */
    public function write($message)
    {
        $file = DIR_LOGS . $this->filename;

        $handle = fopen($file, 'a+');

        fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");

        fclose($handle);
    }
}
