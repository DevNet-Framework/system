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

    public static function writeLine(?string $value = null, ...$args): void
    {
        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        self::write((string) $value . PHP_EOL, $args);
    }

    public static function readLine(string $value = null): string
    {
        if (!isset(self::$In)) {
            self::$In = new FileStream('php://stdin', 'r');
        }

        if ($value) {
            self::write($value);
        }

        return trim(self::$In->readLine());
    }

    public static function foregroundColor(int $color): void
    {
        $color = ConsoleColor::parse('frontground', $color);
        if ($color !== null) {
            self::write("\e[${color}m");
        }
    }

    public static function backgroundColor(int $color): void
    {
        $color = ConsoleColor::parse('background', $color);
        if ($color !== null) {
            self::write("\e[${color}m");
        }
    }

    public static function resetColor(): void
    {
        self::write("\e[0m");
    }

    public static function clear(): void
    {
        self::write("\e[H\e[J");
    }
}
