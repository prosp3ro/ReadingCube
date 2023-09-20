<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

function dd($data)
{
    echo "<br/>";
    echo '<div style="display: inline-block; padding: 0 10px; border: 1px solid gray; background: lightgray;">';
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    echo "</div>";
    echo "<br/>";
    // die();
}

function showException(Throwable $exception)
{
    echo "<br/>";
    echo '<div style="display: inline-block; padding: 0 10px; border: 1px solid gray; background: lightgray;">';
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
