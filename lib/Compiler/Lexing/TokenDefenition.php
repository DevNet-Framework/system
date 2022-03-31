<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Lexing;

class TokenDefenition
{
    public string $Name;
    public string $Pattern;

    public function __construct(string $name, string $pattern)
    {
        $this->Name = $name;
        $this->Pattern = $pattern;
    }
}
