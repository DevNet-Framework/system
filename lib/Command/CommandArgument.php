<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

class CommandArgument
{
    public ?string $Name;
    public $Value = null;

    public function __construct(?string $name = null, ?string $value = null)
    {
        $this->Name  = $name;
        $this->Value = $value;
    }
}
