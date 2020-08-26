<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq\Enumerables;

use Artister\System\Collections\Enumerator;
use Artister\System\Collections\IEnumerable;
use Closure;

class GroupEnumerable implements IEnumerable
{
    use \Artister\System\Extension\ExtensionTrait;

    private IEnumerable $Enumerable;
    private array $Array = [];
    protected ?string $Key;

    public function __construct(IEnumerable $enumerable, string $key = null)
    {
        $this->Enumerable = $enumerable;
        $this->Key = $key;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function groupBy(Closure $function)
    {
        $groups = [];
        foreach ($this->Enumerable as $element)
        {
            $groupeName = $function($element);
            
            if (isset($groups[$groupeName]))
            {
                $group = $groups[$groupeName];
                $array = $group->Array;
                $array[] = $element;
            }
            else
            {
                $array = [$element];
            }

            $this->Key = strval($groupeName);
            $this->Array = $array;

            $group = clone $this;
            $groups[$groupeName] = $group;
        }
        
        $this->Key = null;
        $this->Array = $groups;
        return $this;
    }

    public function getIterator() : Enumerator
    {
        return new Enumerator($this->Array);
    }
}