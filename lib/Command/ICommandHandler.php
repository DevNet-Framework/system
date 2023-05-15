<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

interface ICommandHandler
{
    /**
     * handle the command.
     * @param object $sender, the command that raised the event.
     * @param CommandEventArgs $args, a collection of parsed arguments and options.
     */
    public function onExecute(object $sender, CommandEventArgs $args): void;
}
