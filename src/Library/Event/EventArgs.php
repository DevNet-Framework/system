<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Event;

class EventArgs
{   
    protected array $Attributes;

    public function setAttribute(string $name, $atribute) : void
    {
        $this->Attributes[$name] = $atribute;
    }

    public function getAttribute(string $name)
    {
        return $this->Attributes[$name] ?? null;
    }

    public function hasAttribute(string $name) : bool
    {
        return isset($this->Attributes[$name]);
    }

    public function getAttributes() : array
    {
        return $this->Attributes;
    }
}