<?php
/**
 * for PHP 5
 * @param \Exception $exception
 * for PHP7
 * @param \Throwable $exception
 */
function errorExceptionHandler($exception)
{
    errorHandler(E_ERROR, $exception->getMessage(), $exception->getFile(), $exception->getLine());
}

/**
 * @param $errno
 * @param $errstr
 * @param $errfile
 * @param $errline
 * @return bool
 */
function errorHandler($errno, $errstr, $errfile, $errline)
{
    if (0 === error_reporting()) {
        return true;
    }

    $die = false;

    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            $die = true;
            break;
        default:
            $error = 'Unknown';
            break;
    }

    echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b><br>';

    if ($die) {
        die();
    }

    return true;
}

//error handler
set_error_handler('errorHandler');
set_exception_handler('errorExceptionHandler');
