<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Event\EventArgs;

class CommandEventArgs extends EventArgs
{
    public array $Inputs = [];
    protected array $Parameters;

    public function __construct(array $parameters = [])
    {
        $this->Parameters = $parameters;
    }

    public function set(string $name, ICommandArgument $parameter): void
    {
        $this->Parameters[$name] = $parameter;
    }

    public function get(string $name): ?ICommandArgument
    {
        return $this->Parameters[$name] ?? null;
    }
}
