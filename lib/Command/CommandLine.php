<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Command\Help\HelpBuilder;
use DevNet\System\Event\EventHandler;
use DevNet\System\IO\Console;
use DevNet\System\IO\ConsoleColor;
use DevNet\System\Command\Parsing\Parser;
use Closure;

class CommandLine implements ICommand
{
    private string $name;
    private string $description;
    private array $arguments = [];
    private array $options = [];
    private array $commands = [];
    private ?ICommand $parent = null;
    private ?EventHandler $handler = null;
    private ?Closure $customize = null;

    public function __construct(string $name, string $description = '')
    {
        $this->name = strtolower($name);
        $this->description = $description;
        $this->addOption('--help', 'Show help for the given command-line', '-h', null);
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
    }

    public function addArgument(string $name, string $description = '', $value = ''): void
    {
        $this->arguments[strtolower($name)] = new CommandArgument($name, $description, $value);
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function addOption(string $name, string $description = '', string $alias = '', $value = ''): void
    {
        $this->options[strtolower($name)] = new CommandOption($name, $description, $alias, $value);
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function addCommand(ICommand $command): void
    {
        $command->setParent($this);
        $this->commands[$command->getName()] = $command;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function setParent(ICommand $command): void
    {
        $this->parent = $command;
    }

    public function getParent(): ?ICommand
    {
        return $this->parent;
    }

    public function setHandler(callable $handler): void
    {
        $this->handler = new EventHandler($handler);
    }

    public function setHelp(Closure $customize): void
    {
        $this->customize = $customize;
    }

    public function invoke(array $args): void
    {
        $parser = new Parser();

        foreach ($this->arguments as $argument) {
            $parser->addArgument($argument);
        }

        foreach ($this->options as $option) {
            $parser->addOption($option);
        }

        $result = $parser->parse($args);
        $unparsedTokens = $result->getUnparsedTokens();
        $input = (string) array_shift($unparsedTokens);

        foreach ($this->commands as $command) {
            if ($command->getName() == $input) {
                $command->invoke($unparsedTokens);
                return;
            }
        }

        if ($input) {
            Console::foregroundColor(ConsoleColor::Red);
            Console::writeline("Unrecognized command or argument '{$input}', try '--help' option for usage information.");
            Console::resetColor();
            return;
        }

        $parameters = array_merge($result->getArguments(), $result->getOptions());
        $eventArgs = new CommandEventArgs($parameters, $args);

        $help = $eventArgs->getParameter('--help');
        if ($help) {
            $help = new HelpBuilder($this);
            if ($this->customize) {
                $customize = $this->customize;
                $customize($help);
            } else {
                $help->useDefaults();
            }

            $help->build()->write();
            return;
        }

        $this->handler->invoke($this, $eventArgs);
    }
}
