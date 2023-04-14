<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

trait TypeTrait
{
    private static ?Type $__type = null;

    protected function setGenericType(array $typeArguments): void
    {
        if (!static::$__type) {
            static::$__type = new Type(static::class, $typeArguments);
        }
    }

    /**
     * Get the type of the current object.
     */
    public function getType(): Type
    {
        if (!static::$__type) {
            static::$__type = new Type(static::class);
        }

        return static::$__type;
    }
}
