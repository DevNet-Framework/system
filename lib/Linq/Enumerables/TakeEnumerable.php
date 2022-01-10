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
use Closure;

class TakeEnumerable implements IEnumerable
{
    use \DevNet\System\Extension\ExtenderTrait;

    private array $Array = [];

    public function __construct(IEnumerable $enumerable)
    {
        $this->Array = $enumerable->getIterator()->toArray();
    }

    public function take(int $limit)
    {
        $i = 1;
        $elements = [];
        foreach ($this->Array as $key => $element) {
            $elements[$key] = $element;

            if ($i == $limit) {
                break;
            }

            $i++;
        }

        $this->Array = $elements;
        return $this;
    }

    public function skip(int $offset)
    {
        $i = 1;
        $elements = [];
        foreach ($this->Array as $key => $element) {
            if ($i <= $offset) {
                $i++;
                continue;
            }

            $elements[$key] = $element;
        }

        $this->Array = $elements;
        return $this;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->Array);
    }
}
