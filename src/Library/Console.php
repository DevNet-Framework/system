<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System;

if(!defined('STDIN'))  define('STDIN',  fopen('php://stdin',  'r'));
if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'w'));

class Console
{
    private static $fgColors = [
        'black'         => '0;30',
        'dark grey'     => '1;30',
        'red'           => '0;31',
        'light red'     => '1;31',
        'green'         => '0;32',
        'light green'   => '1;32',
        'brawon'        => '0;33',
        'yellow'        => '1;33',
        'blue'          => '0;34',
        'light blue'    => '1;34',
        'magenta'       => '0;35',
        'light magenta' => '1;35',
        'cyan'          => '0;36',
        'light cyan'    => '1;36',
        'light grey'    => '0;37',
        'white'         => '1;37'
    ];

    private static $bgColors = [
        'black'         => '40',
        'red'           => '41',
        'green'         => '42',
        'yellow'        => '43',
        'blue'          => '44',
        'magenta'       => '45',
        'cyan'          => '46',
        'light grey'    => '47'
    ];

    public static function read(string $string = null)
    {
        if ($string != null) {
            fwrite(STDOUT, $string);
        }
        return fgets(STDIN);
    }

    public static function write(...$parameters)
    {
        $string = call_user_func_array('sprintf', $parameters);
        fwrite(STDOUT, $string);
    }

    public static function writeline(string $string = "")
    {
        $string = $string . PHP_EOL;
        fwrite(STDOUT, $string);
    }

    public static function foregroundColor(string $fgColor)
    {
        $fgColor = strtolower($fgColor);
        $fgColor = self::$fgColors[$fgColor];
        fwrite(STDOUT, "\e[${fgColor}m");
    }

    public static function backgroundColor(string $bgColor)
    {
        $bgColor = strtolower($bgColor);
        $bgColor = self::$bgColors[$bgColor];
        fwrite(STDOUT, "\e[${bgColor}m");
    }

    public static function resetColor()
    {
        fwrite(STDOUT, "\e[0m");
    }
}