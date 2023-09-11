<?php

declare(strict_types = 1);

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
