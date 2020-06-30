<?php declare(strict_types=1);

if (!function_exists('dd')) {
    function dd($data)
    {
        echo "<pre>";
        var_dump($data);
        exit;
    }
}