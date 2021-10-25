<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Command;

class CommandOption extends CommandArgument
{
    public ?string $Alias;

    public function __construct(?string $name = null, ?string $alias = null, ?string $value = null)
    {
        $this->Name  = $name;
        $this->Alias = $alias;
        $this->Value = $value;
    }
}
