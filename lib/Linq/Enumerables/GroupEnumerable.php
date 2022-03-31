<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq\Enumerables;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\Exceptions\PropertyException;
use Closure;

class GroupEnumerable implements IEnumerable
{
    use \DevNet\System\Extension\ExtenderTrait;

    private IEnumerable $enumerable;
    private array $array = [];
    private ?string $key;

    public function __construct(IEnumerable $enumerable, string $key = null)
    {
        $this->enumerable = $enumerable;
        $this->key = $key;
    }

    public function groupBy(Closure $function)
    {
        $groups = [];
        foreach ($this->enumerable as $element) {
            $groupeName = $function($element);

            if (isset($groups[$groupeName])) {
                $group = $groups[$groupeName];
                $array = $group->getIterator()->toArray();
                $array[] = $element;
            } else {
                $array = [$element];
            }

            $this->key = strval($groupeName);
            $this->array = $array;

            $group = clone $this;
            $groups[$groupeName] = $group;
        }

        $this->key = null;
        $this->array = $groups;
        return $this;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }
}
