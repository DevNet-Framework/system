<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Parsing;

class Rule
{
    public int $Index;
    public string $Name;
    public array $Predicates;

    public function __construct(int $index, string $name, array $predicates)
    {
        $this->Index = $index;
        $this->Name = $name;
        $this->Predicates = $predicates;
    }

    public function getIndex(): int
    {
        return $this->Index;
    }
}
