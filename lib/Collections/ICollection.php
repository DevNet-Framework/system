<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
