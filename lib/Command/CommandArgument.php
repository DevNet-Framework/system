<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

class CommandArgument
{
    protected string $name;
    protected string $description;
    protected string $value;

    public string $Name { get => $this->name; }
    public string $Description { get => $this->description; }
    public string $Value { get => $this->value; set => $this->value = $value; }

    public function __construct(string $name, string $description = '', string $value = '')
    {
        $this->name = strtolower($name);
        $this->description = $description;
        $this->value = $value;
    }
}
