<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Command\Parser\CommandParser;
use DevNet\System\Event\EventHandler;
use DevNet\System\Event\EventArgs;

class CommandLine
{
    private string $Name;
    private string $Description;
    private array $Parameters = [];
    private array $Options = [];
    private EventHandler $Event;

    public function __construct()
    {
        $this->Event = new EventHandler();
    }

    public function setName(string $name)
    {
        $this->Name = $name;
    }

    public function setDescription(string $description)
    {
        $this->Description = $description;
    }

    public function getName() : string
    {
        return $this->Name;
    }

    public function getDescription() : string
    {
        return $this->Description;
    }

    public function addParameter(string $name)
    {
        $this->Parameters[] = $name;
    }

    public function addOption(string $name)
    {
        $this->Options[] = $name;
    }

    public function getParameters() : array
    {
        return $this->Parameters;
    }

    public function getOptions() : array
    {
        return $this->Options;
    }

    public function onExecute(object $handler, ?string $action = null)
    {
        $this->Event->add($handler, $action);
    }

    public function Execute(array $args)
    {
        $parser = new CommandParser();

        foreach ($this->Parameters as $parameter) {
            $parser->addParameter($parameter);
        }

        foreach ($this->Options as $option) {
            $parser->addOption($option);
        }

        $arguments = $parser->parse($args);

        $eventArgs = new EventArgs();
        $eventArgs->setAttribute("arguments", $arguments);

        $this->Event->__invoke($this, $eventArgs);
    }
}
