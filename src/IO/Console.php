<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

if(!defined('STDIN'))  define('STDIN',  fopen('php://stdin',  'r'));
if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'w'));

class Console
{
    private const FGCOLORS = [
        0  => '0;30',
        1  => '1;30',
        2  => '0;31',
        3  => '0;35',
        4  => '0;34',
        5  => '0;36',
        6  => '0;32',
        7  => '0;33',
        8  => '0;37',
        9  => '1;31',
        10 => '1;35',
        11 => '1;34',
        12 => '1;36',
        13 => '1;32',
        14 => '1;33',
        15 => '1;37'
    ];

    private const BGCOLORS = [
        0  => '40',
        2  => '41',
        3  => '45',
        4  => '44',
        5  => '46',
        6  => '42',
        7  => '43',
        15 => '47'
    ];

    public static function readLine(string $string = null)
    {
        if ($string != null)
        {
            fwrite(STDOUT, $string);
        }

        return str_replace(PHP_EOL, '', fgets(STDIN));
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

    public static function foregroundColor(int $fgColor)
    {
        $color = self::FGCOLORS[$fgColor] ?? null;

        if ($color != null)
        {
            fwrite(STDOUT, "\e[${color}m");
        }
    }

    public static function backgroundColor(int $bgColor)
    {
        $color = self::BGCOLORS[$bgColor] ?? null;

        if ($color != null)
        {
            fwrite(STDOUT, "\e[${color}m");
        }
    }

    public static function resetColor()
    {
        fwrite(STDOUT, "\e[0m");
    }
}
