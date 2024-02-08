<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use IteratorAggregate;

interface IEnumerable extends IteratorAggregate
{
    public function getIterator(): Enumerator;
}
