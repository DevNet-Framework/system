<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Reflection;

trait ReflectionTrait
{
    protected ?Type $Type = null;

    public function getType(): Type
    {
        if (!$this->Type) {
            $this->Type = new Type(get_class($this));
        }

        return $this->Type;
    }
}
