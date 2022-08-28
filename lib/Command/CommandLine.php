<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Event\EventHandler;
use DevNet\System\ObjectTrait;

class CommandLine implements ICommand
{
    use ObjectTrait;

    protected ?string $name;
    protected ?string $description;
    protected array $arguments = [];
    protected array $options   = [];
    protected array $commands  = [];
    protected EventHandler $handler;

    public function __construct(?string $name = null, ?string $description = null)
    {
        $this->name        = $name;
        $this->description = $description;
        $this->handler     = new EventHandler();
    }

    public function get_Name(): ?string
    {
        return $this->name;
    }

    public function get_Description(): ?string
    {
        return $this->description;
    }

    public function get_Options(): array
    {
        return $this->options;
    }

    public function get_Commands(): array
    {
        return $this->commands;
    }

    public function get_Handler(): EventHandler
    {
        return $this->handler;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function addArgument(CommandArgument $argument): void
    {
        $this->arguments[$argument->Name] = $argument;
    }

    public function addOption(CommandOption $option): void
    {
        $this->options[$option->Name] = $option;
    }

    public function addCommand(ICommand $command): void
    {
        $this->commands[$command->Name] = $command;
    }

    public function addHandler(ICommandHandler $handler): void
    {
        if (isset($this->handler)) {
            $this->handler->add([$handler, 'execute']);
            return;
        }

        $this->handler = new EventHandler([$handler, 'execute']);
    }

    public function invoke(array $args): void
    {
        $inputs = $args;
        $commandName = (string) array_shift($args);
        if (isset($this->commands[$commandName])) {
            $command = $this->commands[$commandName];
            $command->invoke($args);
            return;
        }

        $parser = new CommandParser();

        foreach ($this->arguments as $argument) {
            $parser->addArgument($argument);
        }

        foreach ($this->options as $option) {
            $parser->addOption($option);
        }

        $eventArgs = $parser->parse($inputs);

        $eventArgs->Inputs = $inputs;
        $this->handler->invoke($this, $eventArgs);
    }
}
