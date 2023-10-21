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
    public static ?ConsoleColor $ForegroundColor = null;
    public static ?ConsoleColor $BackgroundColor = null;

    public static function write(string $format, array|string ...$args): void
    {
        if (!isset(static::$Out)) {
            static::$Out = new FileStream('php://stdout', FileMode::Open, FileAccess::Write);
        }

        // if the fist argument is an array use it as arguments.
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        $pattern = '/\{(\d+)(?:,\s*(-?\d+))?\}/';
        $string = preg_replace_callback($pattern, function ($match) use ($args) {
            $index = $match[1];
            $space = isset($match[2]) ? (int)$match[2] : 0;
            $value = isset($args[$index]) ? $args[$index] : '';
            if ($space > 0) {
                return str_pad($value, $space, ' ', STR_PAD_LEFT);
            } else if ($space < 0) {
                return str_pad($value, -$space, ' ', STR_PAD_RIGHT);
            } else {
                return $value;
            }
        }, $format);

        $color = '';
        if (static::$ForegroundColor) {
            $code = static::$ForegroundColor->parse(1);
            $color = "\e[{$code}m";
        }

        $bgcolor = '';
        if (static::$BackgroundColor) {
            $code = static::$BackgroundColor->parse(2);
            $bgcolor = "\e[{$code}m";
        }

        static::$Out->write("{$color}{$bgcolor}{$string}");
    }

    public static function writeLine(?string $format = null, array|string ...$args): void
    {
        // overide the arguments if the fist argument is an array
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        static::write((string) $format, $args);
        static::write("\e[0m" . PHP_EOL);
    }

    public static function readLine(?string $prompt = null): string
    {
        if (!isset(static::$In)) {
            static::$In = new FileStream('php://stdin', FileMode::Open, FileAccess::Read);
        }

        if ($prompt) {
            static::write($prompt);
        }

        return trim((string)static::$In->readLine());
    }

    public static function resetColor(): void
    {
        static::$ForegroundColor = null;
        static::$BackgroundColor = null;
        static::write("\e[0m");
    }

    public static function clear(): void
    {
        static::write("\e[H\e[J");
    }
}
