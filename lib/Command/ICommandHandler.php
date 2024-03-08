<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
