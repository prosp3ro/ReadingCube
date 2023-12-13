<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

if (! function_exists('dump')) {
    function dump(...$args)
    {
        foreach ($args as $arg) {
            echo "<br/>";
            echo '<div style="display: inline-block; padding: 0 10px; border: 1px solid gray; background: black; color: white;">';
            echo "<pre>";
            print_r($arg);
            echo "</pre>";
            echo "</div>";
            echo "<br/>";
        }
    }
}

if (! function_exists('dd')) {
    function dd(...$args)
    {
        foreach ($args as $arg) {
            echo "<br/>";
            echo '<div style="display: inline-block; padding: 0 10px; border: 1px solid gray; background: black; color: white;">';
            echo "<pre>";
            print_r($arg);
            echo "</pre>";
            echo "</div>";
            echo "<br/>";
        }

        die();
    }
}

if (! function_exists('showException')) {
    function showException(Throwable $exception)
    {
        echo "<br/>";
        echo '<div style="display: inline-block; padding: 0 10px; border: 1px solid gray; background: black; color: white;">';
        echo "<b>" . $exception->getMessage() . "</b><br>";
        echo "<b>File:</b> " . $exception->getFile() . "<br>";
        echo "<b>Line:</b> " . $exception->getLine() . "<br>";
        echo "<pre>";
        echo "<b>Stack Trace:</b><br>";
        echo $exception->getTraceAsString();
        echo "</pre>";
        echo "</div>";
        echo "<br/>";
    }
}
