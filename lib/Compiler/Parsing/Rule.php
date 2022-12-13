<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
