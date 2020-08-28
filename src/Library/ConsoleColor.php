<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System;

class ConsoleColor
{
    public const Black          = 0;
    public const Grey           = 1;
    public const Red            = 2;
    public const Magenta        = 3;
    public const Blue           = 4;
    public const Cyan           = 5;
    public const Green          = 6;
    public const Yellow         = 7;
    public const LightGray      = 8;
    public const LightRed       = 9;
    public const LightMagenta   = 10;
    public const LightBlue      = 11;
    public const LightCyan      = 12;
    public const LightGreen     = 13;
    public const LightYellow    = 14;
    public const White          = 15;

    private const FGColors = [
        0   => '0;30',
        1   => '1;30',
        2   => '1;31',
        3   => '1;35',
        4   => '0;34',
        5   => '0;36',
        6   => '0;32',
        7   => '0;33',
        8   => '0;37',
        9   => '0;31',
        10  => '0;35',
        11  => '1;34',
        12  => '1;36',
        13  => '1;32',
        14  => '1;33',
        15  => '1;37'
    ];

    private const BGColors = [
        0   => '40',
        2   => '41',
        3   => '45',
        4   => '44',
        5   => '46',
        6   => '42',
        7   => '43',
        15  => '47'
    ];

}