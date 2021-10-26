<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Event\EventHandler;
use DevNet\System\Exceptions\PropertyException;

class CommandLine implements ICommand
{
    protected ?string $Name;
    protected ?string $Description;
    protected array $Arguments = [];
    protected array $Options = [];
    protected array $Commands = [];
    protected EventHandler $Handler;

    public function __get(string $name)
    {
        if (!property_exists($this, $name)) {
            throw new PropertyException("The property {$name} doesn\'t exist.");
        }

        return $this->$name;
    }

    public function __construct(?string $name = null, ?string $description = null)
    {
        $this->Name        = $name;
        $this->Description = $description;
        $this->Handler     = new EventHandler();
    }

    public function setName(string $name): void
    {
        $this->Name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->Description = $description;
    }

    public function addArgument(CommandArgument $argument): void
    {
        $this->Arguments[$argument->Name] = $argument;
    }

    public function addOption(CommandOption $option): void
    {
        $this->Options[$option->Name] = $option;
    }

    public function addCommand(ICommand $command): void
    {
        $this->Commands[$command->Name] = $command;
    }

    public function addHandler(ICommandHandler $handler): void
    {
        if (isset($this->Handler)) {
            $this->Handler->add($handler, 'execute');
            return;
        }

        $this->Handler = new EventHandler($handler, 'execute');
    }

    public function invoke(array $args): bool
    {
        $inputs = $args;
        $commandName = array_shift($args);
        if (isset($this->Commands[$commandName])) {
            $command = $this->Commands[$commandName];
            return $command->invoke($args);
        }

        $parser = new CommandParser();

        foreach ($this->Arguments as $argument) {
            $parser->addArgument($argument);
        }

        foreach ($this->Options as $option) {
            $parser->addOption($option);
        }

        $eventArgs = $parser->parse($inputs);
        
        if (!$eventArgs) {
            return false;
        }
        
        $eventArgs->Inputs = $inputs;
        $this->Handler->invoke($this, $eventArgs);

        return true;
    }
}
