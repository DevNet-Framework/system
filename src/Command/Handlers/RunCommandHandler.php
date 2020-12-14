<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Command\Handlers;

use Artister\System\Cli\ICommandHandler;
use Artister\System\Event\EventArgs;
use Artister\System\ConsoleColor;
use Artister\System\Console;

class RunCommandHandler implements ICommandHandler
{

    public function execute(object $sender, EventArgs $event) : void
    {
        // default main class name need to be inhetrited from sittings, noted to be added later
        $mainClass  = "Application\Program";
        $arguments  = $event->getAttribute('arguments');
        $help       = $arguments->getOption('--help');
        
        if ($help)
        {
            $this->showHelp();
        }

        $args = $arguments->Values;
        $main = $arguments->getOption('--main');

        if ($main) {
            if ( $main->Value) {
                $mainClass = $main->Value;
                foreach ($args as $key => $arg) {
                    if ($arg == $main->Name) {
                        unset($args[$key]);
                        unset($args[$key+1]);
                        $args = array_values($args);
                        break;
                    }
                }
            }
        }

        $mainClass = ucwords($mainClass, "\\");

        if (!class_exists($mainClass)) {
            Console::foregroundColor(ConsoleColor::Red);
            Console::writeline("Couldn't find the class $mainClass");
            Console::resetColor();
            Console::writeline();
            exit;
        }

        if (!method_exists($mainClass, 'main')) {
            Console::foregroundColor(ConsoleColor::Red);
            Console::writeline("Couldn't find the main method to run, Ensure it exists in the class {$mainClass}");
            Console::resetColor();
            Console::writeline('');
            exit;
        }

        $mainClass::main($args);
    }

    public function showHelp()
    {
        Console::writeline("DevNet SDK command line interpreter");
        Console::writeline();
        Console::writeline("Usage:");
        Console::writeline("  run [arguments] [options]");
        Console::writeline();
        Console::writeline("Options:");
        Console::writeline("  --help  Displays help for this command.");
        Console::writeline("  --main  Main class of the program to run.");
        Console::writeline();
        exit;
    }
}