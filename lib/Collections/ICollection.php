<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Collections\IEnumerable;

interface ICollection extends IEnumerable
{
    public function contains(mixed $element): bool;

    public function remove(mixed $element): void;

    public function clear(): void;

    public function toArray(): array;
}
