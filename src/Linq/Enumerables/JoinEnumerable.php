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

class JoinEnumerable implements IEnumerable
{
    use \DevNet\System\Extension\ExtensionTrait;

    private IEnumerable $Enumerable;
    private array $Array = [];

    public function __construct(IEnumerable $enumerable)
    {
        $this->Enumerable = $enumerable;
    }

    public function join($innerCollection, Closure $outerSelector, Closure $innerSelector, Closure $resultSelector)
    {
        $innerJoin = [];
        $joinResult = [];
        foreach ($this->Enumerable as $key => $outerElement) {
            $outerKey = $outerSelector($outerElement);
            foreach ($innerCollection as $innerElement) {
                $innerKey = $innerSelector($innerElement);
                if ($outerKey == $innerKey) {
                    $innerJoin[$key] = [$outerElement, $innerElement];
                }
            }
        }

        foreach ($innerJoin as $key => $element) {
            $joinResult[$key] = $resultSelector($element[0], $element[1]);
        }

        $this->Array = $joinResult;
        return $this;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->Array);
    }
}
