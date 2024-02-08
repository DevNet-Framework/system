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
    public array $Predecates;

    public function __construct(int $index, string $name, array $predecates)
    {
        $this->Index = $index;
        $this->Name = $name;
        $this->Predecates = $predecates;
    }

    public function getIndex(): int
    {
        return $this->Index;
    }
}
