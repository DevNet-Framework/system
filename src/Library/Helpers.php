<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

function Url(string $path = '/') : string
{
    // Scheme
    $https = !isset($_SERVER['HTTPS']) ? 'off' : $_SERVER['HTTPS'];
    $scheme = $https == 'off' ? 'http' : 'https';

    // Host
    if (isset($_SERVER['HTTP_HOST']))
    {
        $hostFragments = explode(':', $_SERVER['HTTP_HOST']);
        $host = $hostFragments[0];
    }

    // Port
    $port = !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ($scheme == 'https' ? 443 : 80);
    $port = $port != 80 && $port != '' ? ":".$port : '';

    if (strpos($path, "/") !== 0 ) {
        $path = "/{$path}";
    }

    return $scheme .'://'. $host . $port . $path;
}

/**
 * add some PHP 8 features to PHP 7.
 */
if(PHP_MAJOR_VERSION < 8)
{
    function str_contains(string $string, string $needle) : bool
    {
        return strpos($string, $needle) !== false ? true : false;
    }
    
    function str_starts_with(string $string, string $needle) : bool
    {
        return strpos($string, $needle) === 0 ? true : false;
    }

    function str_ends_with(string $string, string $needle) : bool
    {
        return strpos(strrev($string), strrev($needle)) === 0 ? true : false;
    }
}