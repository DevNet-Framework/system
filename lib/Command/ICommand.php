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

interface ICommand
{
    /**
     * This method must retun the following properties.
     * @return string $Name
     * @return string $Description
     * @return array <CommandArgument> $Arguments
     * @return array <CommandOption> $Options array
     * @return array <ICommand> $Commands array
     * @return EventHandler $Handler
     * and must throw an exception if the property doesn't exist
     * @throws PropertyException
     */
    public function __get(string $name);

    /**
     * set the command's property Name;
     */
    public function setName(string $name): void;

    /**
     * set the command's property Description;
     */
    public function setDescription(string $description): void;

    /**
     * add CommandArgument to to the property Arguments array<CommandArgument>;
     */
    public function addArgument(CommandArgument $argument): void;

    /**
     * add CommandOption to to the property Options array<CommandOption>;
     */
    public function addOption(CommandOption $option): void;

    /**
     * add ICommand to the property Commands array<ICommand>;
     */
    public function addCommand(ICommand $command): void;

    /**
     * add ICommandHandler to command's property EventHandler;
     */
    public function addHandler(ICommandHandler $handler): void;

    /**
     * Parse the input arguments to ICommand, CommandArgument and CommandOption,
     * Invoke the matched command else invoke the current command by invoking the EventHandler.
     * Return false if didn't match any criteria.
     */
    public function invoke(array $args): bool;
}
