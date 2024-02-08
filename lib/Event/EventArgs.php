<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Event;

use DevNet\System\PropertyTrait;

class EventArgs
{
    use PropertyTrait;

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
