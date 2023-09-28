<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Exceptions\TypeException;
use DevNet\System\Template;
use DevNet\System\Type;

#[Template('K', 'V')]
class Dictionary extends AbstractArray implements IDictionary
{
    public function __construct(string $keyType, string $valueType)
    {
        $keyType = strtolower($keyType);
        if ($keyType != 'int' && $keyType != 'integer' &&  $keyType != 'string') {
            throw new TypeException(static::class . "::__construct(): Key type must be defined as integer or string", 0, 1);
        }

        $this->setGenericArguments($keyType, $valueType);
    }

    public function add(#[Type('K')] $key, #[Type('V')] $value): void
    {
        $this->checkArgumentTypes(func_get_args());
        $this->array[$key] = $value;
    }

    public function contains(#[Type('K')] $key): bool
    {
        $this->checkArgumentTypes(func_get_args());
        return isset($this->array[$key]) ? true : false;
    }

    public function remove(#[Type('K')] $key): void
    {
        $this->checkArgumentTypes(func_get_args());
        if (isset($this->array[$key])) unset($this->array[$key]);
    }

    public function getValue(#[Type('K')] $key)
    {
        $this->checkArgumentTypes(func_get_args());
        return $this->array[$key] ?? null;
    }
}
