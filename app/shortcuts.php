<?php

define('DS', DIRECTORY_SEPARATOR);

function dd()
{
    foreach (func_get_args() as $var) {
        dump($var);
    }
    die;
}

