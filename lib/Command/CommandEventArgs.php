<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
