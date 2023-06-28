<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\TypeException;
use Attribute;

#[Attribute]
class Generic
{
    private array $types = [];

    public function __construct(string $typeName, string ...$typeNames)
    {
        $this->types[$typeName] = new Type($typeName);
        foreach ($typeNames as $typeName) {
            if (isset($this->types[$typeName])) {
                throw new TypeException("The generic type should not have a repeated parameter type.", 0, 1);
            }
            $this->types[$typeName] = new Type($typeName);
        }
    }

    public function getTypes(): array
    {
        return $this->types;
    }
}
