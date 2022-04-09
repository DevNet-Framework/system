<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Type;

trait GenericTrait
{
    private ?Type $type = null;

    protected function setTypeParameters(array $typeParameters): void
    {
        $this->type = new Type(get_class($this), $typeParameters);
    }

    public function getType(): Type
    {
        if (!$this->type) {
            $this->type = new Type(get_class($this));
        }

        return $this->type;
    }
}
