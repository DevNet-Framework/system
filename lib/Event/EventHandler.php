<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Event;

use DevNet\System\Delegate;

class EventHandler extends Delegate
{
    public function eventHandler(object $sender, EventArgs $args): void
    {
    }
}
