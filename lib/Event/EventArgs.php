<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Event;

use DevNet\System\Tweak;

class EventArgs
{
    use Tweak;

    protected array $parameters = [];

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function get_Parameters(): array
    {
        return $this->parameters;
    }

    public function set(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    public function get(string $name): mixed
    {
        return $this->parameters[$name] ?? null;
    }

    public static function empty(): EventArgs
    {
        return new EventArgs();
    }
}
