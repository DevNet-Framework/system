<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Command;

use Artister\System\Command\Handlers\RunCommandHandler;
use Artister\System\Command\Handlers\NewCommandHandler;
use Artister\System\Cli\CommandDispatcher;
use Artister\System\Cli\Command;
use Artister\System\ConsoleColor;
use Artister\System\Console;

class Program
{
    public static function main(array $args = [])
    {
        $dispatcher = new CommandDispatcher();

        $dispatcher->addCommand(function(Command $command){
            $command->setName('new');
            $command->setDescription('Create a new project');
            $command->addParameter('template');
            $command->addOption('--help');
            $command->OnExecute(new NewCommandHandler(), 'execute');
        });

        $dispatcher->addCommand(function(Command $command){
            $command->setName('run');
            $command->setDescription('Run a DevNet applicaton');
            $command->addOption('--main');
            $command->addOption('--help');
            $command->OnExecute(new RunCommandHandler(), 'execute');
        });

        self::processArgs($dispatcher, $args);

    }

    public static function processArgs(CommandDispatcher $dispatcher, array $args) : void
    {
        $argument = $args[0] ?? null;

        if ($argument == '--version')
        {
            self::showVersion();
        }

        if ($argument == '--help' || $argument == null)
        {
            self::showHelp($dispatcher);
        }

        $result = $dispatcher->invoke($args);

        if (!$result)
        {
            Console::foregroundColor(ConsoleColor::Red);
            Console::writeline("Command not found, try --help option for more informations");
            Console::resetColor();
            Console::writeline();
            exit;
        }
    }

    public static function showHelp(CommandDispatcher $dispatcher) : void
    {
        Console::writeline();
        Console::writeline("DevNet SDK command line interpreter");
        Console::writeline();
        Console::writeline("Usage:");
        Console::writeline("  command [options] [arguments]");
        Console::writeline();
        Console::writeline("Options:");
        Console::writeline("  --help      Show command line help.");
        Console::writeline("  --version   Show DevNet SDK version.");
        Console::writeline();
        Console::writeline("commands:");
        $super = 0;
        $commands = $dispatcher->Commands;

        foreach ($commands as $command)
        {
            $lenth = strlen($command->getName());
            if ($lenth > $super) {
                $super = $lenth;
            }
        }

        foreach ($commands as $command)
        {
            $lenth = strlen($command->getName());
            $steps = $super - $lenth + 3;
            $space = str_repeat(" ", $steps);
            Console::writeline("  {$command->getName()}$space{$command->getDescription()}");
        }

        Console::writeline();
        exit;
    }

    public static function showVersion() : void
    {
        Console::writeline("DevNet SDK command line interpreter, Version 1.0.0");
        Console::writeline("Copyright (c) Mohammed Moussaoui");
        Console::writeline();
        exit;
    }
}
