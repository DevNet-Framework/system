<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler\Parser;

class Rule
{
    public int $index;
    public string $name;
    public array $predecates;

    public function __construct(int $index, string $name, array $predecates)
    {
        $this->index = $index;
        $this->name = $name;
        $this->predecates = $predecates;
    }

    public function getIndex()
    {
        return $this->index;
    }
}