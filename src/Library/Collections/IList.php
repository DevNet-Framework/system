<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Collections;

interface IList extends IEnumerable
{
    public function add($item) : void;

    public function contains($item) : bool;

    public function remove($item) : void;
}