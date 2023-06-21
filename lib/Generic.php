<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use Attribute;

#[Attribute]
class Generic
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
