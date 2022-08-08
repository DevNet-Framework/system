<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

class ConsoleColor
{
    public const Black       = 0;
    public const DarkGrey    = 1;
    public const DarkRed     = 2;
    public const DarkMagenta = 3;
    public const DarkBlue    = 4;
    public const DarkCyan    = 5;
    public const DarkGreen   = 6;
    public const DarkYellow  = 7;
    public const Gray        = 8;
    public const Red         = 9;
    public const Magenta     = 10;
    public const Blue        = 11;
    public const Cyan        = 12;
    public const Green       = 13;
    public const Yellow      = 14;
    public const White       = 15;

    private const frontground = [
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

    private const background = [
        8   => '40',
        9   => '41',
        10  => '45',
        11  => '44',
        12  => '46',
        13  => '42',
        14  => '43',
        15  => '47'
    ];

    public static function getNames(): array
    {
        return [
            'Black',
            'DarkGrey',
            'DarkRed',
            'DarkMagenta',
            'DarkBlue',
            'DarkCyan',
            'DarkGreen',
            'DarkYellow',
            'Gray',
            'Red',
            'Magenta',
            'Blue',
            'Cyan',
            'Green',
            'Yellow',
            'White'
        ];
    }

    public static function getValues(): array
    {
        return [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    }

    public static function parse(string $type, int $value): ?string
    {
        switch ($type) {
            case 'frontground':
                return self::frontground[$value] ?? null;
                break;
            case 'background':
                return self::background[$value] ?? null;
                break;
            default:
                return null;
                break;
        }
    }
}
