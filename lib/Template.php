<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use Attribute;

#[Attribute]
class Template
{
    private array $types = [];

    public function __construct(string $typeName, string ...$typeNames)
    {
        $this->types[] = new Type($typeName);
        foreach ($typeNames as $typeName) {
            $this->types[] = new Type($typeName);
        }
    }

    public function getTypes(): array
    {
        return $this->types;
    }
}
