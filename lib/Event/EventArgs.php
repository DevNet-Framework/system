<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Event;

class EventArgs
{
    protected array $parameters = [];

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function set(string $name, $parameter): void
    {
        $this->parameters[$name] = $parameter;
    }

    public function get(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    public static function empty(): EventArgs
    {
        return new EventArgs();
    }
}
