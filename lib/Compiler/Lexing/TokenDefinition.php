<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Lexing;

class TokenDefinition
{
    public string $Name;
    public string $Pattern;

    public function __construct(string $name, string $pattern)
    {
        $this->Name = $name;
        $this->Pattern = $pattern;
    }
}
