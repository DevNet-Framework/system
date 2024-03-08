<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
                    self::Black       => '30',
                    self::DarkGrey    => '90',
                    self::DarkRed     => '31',
                    self::DarkMagenta => '35',
                    self::DarkBlue    => '34',
                    self::DarkCyan    => '36',
                    self::DarkGreen   => '32',
                    self::DarkYellow  => '33',
                    self::Gray        => '37',
                    self::Red         => '91',
                    self::Magenta     => '95',
                    self::Blue        => '94',
                    self::Cyan        => '96',
                    self::Green       => '92',
                    self::Yellow      => '93',
                    self::White       => '97'
                };
                break;
            case 2:
                return match ($this) {
                    self::Black       => '40',
                    self::DarkGrey    => '100',
                    self::DarkRed     => '41',
                    self::DarkMagenta => '45',
                    self::DarkBlue    => '44',
                    self::DarkCyan    => '46',
                    self::DarkGreen   => '42',
                    self::DarkYellow  => '43',
                    self::Gray        => '47',
                    self::Red         => '101',
                    self::Magenta     => '105',
                    self::Blue        => '104',
                    self::Cyan        => '106',
                    self::Green       => '102',
                    self::Yellow      => '103',
                    self::White       => '107'
                };
                break;
            default:
                throw new ArgumentException("Color type must be 1 for frontground or 2 for background!", 0, 1);
                break;
        }
    }
}
