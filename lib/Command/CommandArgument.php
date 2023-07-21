<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

use DevNet\System\Tweak;

class CommandArgument
{
    use Tweak;

    protected string $name;
    protected string $description;
    protected string $value;

    public function __construct(string $name, string $description = '', string $value = '')
    {
        $this->name = strtolower($name);
        $this->description = $description;
        $this->value = $value;
    }

    public function get_Name(): string
    {
        return $this->name;
    }

    public function get_Description(): string
    {
        return $this->description;
    }

    public function get_Value(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
