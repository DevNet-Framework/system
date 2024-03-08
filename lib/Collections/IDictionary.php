<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

interface IDictionary extends IEnumerable
{
    public function add($key, $value): void;

    public function contains($key): bool;

    public function remove($key): void;

    public function getValue($key);
}
