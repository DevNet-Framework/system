<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Event;

class EventArgs
{
    protected array $parameters = [];

    public array $Parameters { get => $this->parameters; }

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
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
