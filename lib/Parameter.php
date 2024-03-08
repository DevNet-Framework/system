<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

use DevNet\System\Exceptions\TypeException;

abstract class Parameter
{
    use PropertyTrait;

    protected Type $type;
    protected mixed $value;

    public function __construct(Type $type)
    {
        if ($type->isGenericType() || $type->isGenericParameter()) {
            throw new TypeException("Generic argument must not be a generic type nor a generic parameter", 0, 1);
        }

        $this->type = $type;
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
