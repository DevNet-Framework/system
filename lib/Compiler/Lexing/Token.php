<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Lexing;

class Token implements IToken
{
    public string $Name;
    public ?string $Value;

    public function __construct(string $name, string $value = null)
    {
        $this->Name  = $name;
        $this->Value = $value;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function getValue(): string
    {
        return $this->Value;
    }

    public function getType(): string
    {
        return get_class($this);
    }
}
