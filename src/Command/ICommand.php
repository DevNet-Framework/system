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
     * add ICommandOption to the property Commands array<ICommand>;
     */
    public function addCommand(ICommand $command): void;

    /**
     * add ICommandOption to to the property Arguments array<ICommandArgument>;
     */
    public function addArgument(ICommandArgument $argument): void;

    /**
     * add ICommandOption to to the property Options array<ICommandOption>;
     */
    public function addOption(ICommandOption $option): void;

    /**
     * add ICommandHandler to command's property EventHandler;
     */
    public function addHandler(ICommandHandler $handler): void;

    /**
     * Parse the input arguments to ICommand, ICommandArgument and ICommandOption,
     * Invoke the matched command else invoke the current command by invoking the EventHandler.
     */
    public function invoke(array $args = []);
}
