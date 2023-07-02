<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Generic;

use DevNet\System\Exceptions\TypeException;
use DevNet\System\PropertyTrait;
use DevNet\System\Type;

class T
{
    use PropertyTrait;

    protected mixed $type;
    protected mixed $value;

    public function __construct(string $typeName)
    {
        $this->type = new Type($typeName);
    }

    public function get_Value(): mixed
    {
        return $this->value;
    }

    public function set_Value(mixed $value): void
    {
        if (!$this->type->isTypeOf($value)) {
            $type = new Type($value);
            throw new TypeException("Cannot assign {$type} to a property of type {$this->type}", 0, 1);
        }

        $this->value = $value;
    }

    public function __invoke(): mixed
    {
        return $this->value;
    }

    public function getType(): Type
    {
        return $this->type;
    }
}
