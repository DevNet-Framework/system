<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Linq\Enumerables;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use DevNet\System\MethodTrait;
use DevNet\System\PropertyTrait;
use Closure;

class GroupEnumerable implements IEnumerable
{
    use MethodTrait;
    use PropertyTrait;

    private IEnumerable $enumerable;
    private array $array = [];
    private ?string $key;

    public function __construct(IEnumerable $enumerable, ?string $key = null)
    {
        $this->enumerable = $enumerable;
        $this->key = $key;
    }

    public function get_Key(): ?string
    {
        return $this->key;
    }

    public function groupBy(Closure $function): static
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
