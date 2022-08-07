<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

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

    private static FileStream $In;
    private static FileStream $Out;

    public static function write(string $value, ...$args): void
    {
        if (!isset(self::$Out)) {
            self::$Out = new FileStream('php://stdout', 'w');
        }

        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        $replace = [];
        foreach ($args as $key => $arg) {
            // map the arguments if the value can be casted to string
            if (!is_array($arg) && (!is_object($arg) || method_exists($arg, '__toString'))) {
                $replace['{' . $key . '}'] = $arg;
            }
        }

        // interpolate replacement values into the string format
        if ($replace) {
            $value = strtr($value, $replace);
        }

        self::$Out->write($value);
    }

    public static function writeline(string $value = "", ...$args): void
    {
        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        self::write($value . PHP_EOL, $args);
    }

    public static function readLine(string $value = null): string
    {
        if (!isset(self::$In)) {
            self::$In = new FileStream('php://stdin', 'r');
        }

        if ($value) {
            self::write($value);
        }

        return str_replace(PHP_EOL, '', self::$In->readLine());
    }

    public static function foregroundColor(int $color): void
    {
        $color = self::FGCOLORS[$color] ?? null;
        if ($color !== null) {
            self::write("\e[${color}m");
        }
    }

    public static function backgroundColor(int $color): void
    {
        $color = self::BGCOLORS[$color] ?? null;
        if ($color !== null) {
            self::write("\e[${color}m");
        }
    }

    public static function resetColor(): void
    {
        self::write("\e[0m");
    }
}
