<?php
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Logging\Console;

use DevNet\System\IO\Console;
use DevNet\System\IO\ConsoleColor;
use DevNet\System\Logging\ILogger;
use DevNet\System\Logging\LogLevel;

class ConsoleLogger implements ILogger
{
    private string $Category;
    
    public function __construct(string $category)
    {
        $this->Category = $category;
    }

    public function log(int $level, string $message, array $args): void
    {
        switch ($level) {
            case LogLevel::Debug:
                Console::foregroundColor(ConsoleColor::Blue);
                Console::write('Debug:');
                Console::resetColor();
                break;
            case LogLevel::Notice:
                Console::foregroundColor(ConsoleColor::Green);
                Console::write('Notice:');
                Console::resetColor();
                break;
            case LogLevel::Warning:
                Console::foregroundColor(ConsoleColor::Yellow);
                Console::write('Warning:');
                Console::resetColor();
                break;
            case LogLevel::Error:
                Console::foregroundColor(ConsoleColor::Red);
                Console::write('Error:');
                Console::resetColor();
                break;
            case LogLevel::Critical:
                Console::backgroundColor(ConsoleColor::Red);
                Console::write('Critical:');
                Console::resetColor();
                break;
            }
            
        Console::write(' ');
        Console::writeline($this->Category);
        Console::writeline($message, $args);
        Console::writeline();
    }
}
