<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

use DevNet\System\Exceptions\ArgumentException;

enum ConsoleColor: int
{
    case Black       = 0;
    case DarkGrey    = 1;
    case DarkRed     = 2;
    case DarkMagenta = 3;
    case DarkBlue    = 4;
    case DarkCyan    = 5;
    case DarkGreen   = 6;
    case DarkYellow  = 7;
    case Gray        = 8;
    case Red         = 9;
    case Magenta     = 10;
    case Blue        = 11;
    case Cyan        = 12;
    case Green       = 13;
    case Yellow      = 14;
    case White       = 15;

    /**
     * @param int $type Color type must be 1 for frontground or 2 for background.
     * @throws ArgumentException If the argument is not equal to the value 1 or 2.
     */
    public function parse(int $type): ?string
    {
        switch ($type) {
            case 1:
                return match ($this) {
                    self::Black       => '0;30',
                    self::DarkGrey    => '1;30',
                    self::DarkRed     => '0;31',
                    self::DarkMagenta => '0;35',
                    self::DarkBlue    => '0;34',
                    self::DarkCyan    => '0;36',
                    self::DarkGreen   => '0;32',
                    self::DarkYellow  => '0;33',
                    self::Gray        => '0;37',
                    self::Red         => '1;31',
                    self::Magenta     => '1;35',
                    self::Blue        => '1;34',
                    self::Cyan        => '1;36',
                    self::Green       => '1;32',
                    self::Yellow      => '1;33',
                    self::White       => '1;37'
                };
                break;
            case 2:
                return match ($this) {
                    self::Gray    => '40',
                    self::Red     => '41',
                    self::Magenta => '45',
                    self::Blue    => '44',
                    self::Cyan    => '46',
                    self::Green   => '42',
                    self::Yellow  => '43',
                    self::White   => '47'
                };
                break;
            default:
                throw new ArgumentException("Color type must be 1 for frontground or 2 for background!", 0, 1);
                break;
        }
    }
}
