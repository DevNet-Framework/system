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
use Closure;

class WhereEnumerable implements IEnumerable
{
    use MethodTrait;

    private IEnumerable $enumerable;
    private array $array = [];

    public function __construct(IEnumerable $enumerable)
    {
        $this->enumerable = $enumerable;
    }

    public function where(Closure $predicate): static
    {
        $elements = [];
        foreach ($this->enumerable as $key => $element) {
            if ($predicate($element) !== false) {
                $elements[$key] = $element;
            }
        }

        $this->array = $elements;
        return $this;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }
}
