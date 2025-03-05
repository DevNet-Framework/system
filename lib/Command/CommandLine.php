<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Command\Help\HelpBuilder;
use DevNet\System\Command\Parsing\Parser;
use DevNet\System\Event\EventHandler;
use DevNet\System\IO\Console;
use DevNet\System\IO\ConsoleColor;
use DevNet\System\PropertyTrait;
use Closure;

class CommandLine
{
    use PropertyTrait;

    private string $name;
    private string $description;
    private array $arguments = [];
    private array $options = [];
    private array $commands = [];
    private ?CommandLine $parent = null;
    private ?EventHandler $handler = null;
    private ?Closure $customize = null;

    public function __construct(string $name, string $description = '')
    {
        $this->name = strtolower($name);
        $this->description = $description;
        $this->addOption('--help', 'Show help for the given command-line', '-h');

        $interfaces = class_implements($this);
        if (in_array(ICommandHandler::class, $interfaces)) {
            $this->handler = new EventHandler([$this, 'onExecute']);
        }
    }

    public function get_Name(): string
    {
        return $this->name;
    }

    public function get_Description(): string
    {
        return $this->description;
    }

    public function get_Options(): array
    {
        return $this->options;
    }

    public function get_Arguments(): array
    {
        return $this->arguments;
    }

    public function get_Commands(): array
    {
        return $this->commands;
    }

    public function get_Parent(): ?CommandLine
    {
        return $this->parent;
    }

    public function addArgument(string $name, string $description = '', string $value = ''): void
    {
        $this->arguments[strtolower($name)] = new CommandArgument($name, $description, $value);
    }

    public function addOption(string $name, string $description = '', string $alias = '', string $value = ''): void
    {
        $this->options[strtolower($name)] = new CommandOption($name, $description, $alias, $value);
    }

    public function addCommand(CommandLine $command): void
    {
        $command->setParent($this);
        $this->commands[$command->Name] = $command;
    }

    public function setParent(CommandLine $command): void
    {
        $this->parent = $command;
    }

    public function setHandler(ICommandHandler|callable $handler): void
    {
        if ($handler instanceof ICommandHandler) {
            $handler = [$handler, 'onExecute'];
        }

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
            if ($command->Name == $input) {
                $command->invoke($unparsedTokens);
                return;
            }
        }

        if ($input) {
            Console::$ForegroundColor = ConsoleColor::Red;
            Console::writeLine("Unrecognized command or argument '{$input}', try '--help' option for usage information.");
            Console::resetColor();
            return;
        }

        $parameters = array_merge($result->getArguments(), $result->getOptions());
        $eventArgs = new CommandEventArgs($parameters, $args);

        $help = $eventArgs->get('--help');
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
