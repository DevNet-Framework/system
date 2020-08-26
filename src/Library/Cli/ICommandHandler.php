<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Cli;

use Artister\System\Event\EventArgs;

interface ICommandHandler
{
    public function execute(object $sender,  EventArgs $event) : void;
}