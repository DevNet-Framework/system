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
    protected array $parameters = [];
    protected array $values = [];

    public function __construct(array $parameters = [], array $values = [])
    {
        $this->parameters = $parameters;
        $this->values = $values;
    }

    public function getParameter(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
