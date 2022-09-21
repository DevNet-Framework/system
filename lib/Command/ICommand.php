<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

interface ICommand
{
    /**
     * Get the command's name;
     */
    public function getName(): string;

    /**
     * Get the command's description;
     */
    public function getDescription(): string;

    /**
     * Get the command's arguments.
     * @return array<CommandArgument>
     */
    public function getArguments(): array;

    /**
     * Get the command's options
     * @return array<CommandOption>
     */
    public function getOptions(): array;

    /**
     * Get the parent command
     */
    public function getParent(): ?ICommand;

    /**
     * Set the parent command
     */
    public function setParent(ICommand $command): void;

    /**
     * Parse the input arguments and invoke the command
     */
    public function invoke(array $args): void;
}
