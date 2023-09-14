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
    error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());

    echo "<br/>";
    echo '<div style="display: inline-block; padding: 0 10px; border: 1px solid gray; background: lightgray;">';
    echo "An error occurred: " . $exception->getMessage() . "<br>";
    echo "File: " . $exception->getFile() . "<br>";
    echo "Line: " . $exception->getLine() . "<br>";
    echo "<pre>";
    echo "Stack Trace:<br>";
    echo $exception->getTraceAsString();
    echo "</pre>";
    echo "</div>";
    echo "<br/>";
}
