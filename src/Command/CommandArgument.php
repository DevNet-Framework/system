<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Exceptions\PropertyException;

class CommandArgument implements ICommandArgument
{
    protected ?string $Name;
    protected $Value;

    public function __get(string $name)
    {
        if (!property_exists($this, $name)) {
            throw new PropertyException("The property {$name} doesn\'t exist.");
        }

        return $this->$name;
    }

    public function __construct(?string $name = null, ?string $value = null)
    {
        $this->Name  = $name;
        $this->Value = $value;
    }

    public function setName(string $name): void
    {
        $this->Name = $name;
    }

    public function setValue($value): void
    {
        $this->Value = $value;
    }
}
