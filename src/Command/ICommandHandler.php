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
     * Execute and handle arguments.
     * @param object $sender, the command that raised the event.
     * @param EventArgs $args, a collection of parsed arguments <IcommandArgument|ICommandOption>.
     */
    public function execute(object $sender, CommandEventArgs $args): void;
}
