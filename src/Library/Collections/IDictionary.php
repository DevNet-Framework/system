<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

interface IDictionary extends IEnumerable
{
    public function add($key, $value) : void;

    public function contains($key) : bool;

    public function remove($key) : void;

    public function getValue($key);
}
