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
use Artister\System\Linq\Enumerable;
use Closure;

class OrderEnumerable implements IEnumerable
{
    use \Artister\System\Extension\ExtensionTrait;

    private IEnumerable $Enumerable;
    private array $Array    = [];
    private array $Sort     = [];

    public function __construct(IEnumerable $enumerable)
    {
        $this->Enumerable = $enumerable;
    }

    public function orderBy(Closure $predecate)
    {
        $array          = $this->Enumerable->getIterator()->toArray();
        $this->Sort     = $this->sort($array, $predecate);
        $list           = $this->list($this->Sort); 
        $this->Array    = $list;
        return $this;
    }

    public function orderByDescending(Closure $predecate)
    {
        $array          = $this->Enumerable->getIterator()->toArray();
        $this->Sort     = $this->sort($array, $predecate, true);
        $list           = $this->list($this->Sort);
        $this->Array    = $list;
        return $this;
    }

    public function thenBy(Closure $predecate)
    {
        $map            = $this->sort($this->Sort, $predecate);
        $list           = $this->list($map);
        $this->Array    = $list;
        return $this;
    }
    
    public function thenByDescending(Closure $predecate)
    {
        $map            = $this->sort($this->Sort, $predecate, true);
        $list           = $this->list($map);
        $this->Array    = $list;
        return $this;
    }

    private function sort(array $array, Closure $predecate, $reverseOrder = false)
    {
        $sort = [];
        $leaf = false;
        foreach ($array as $key => $element)
        {
            $subKey = false;
            if (is_array($element))
            {
                $element = $this->sort($element, $predecate, $reverseOrder);
            }
            else
            {
                $key    = $predecate($element);
                $subKey = true;
                $leaf   = true;
            }

            if ($subKey)
            {
                $sort[$key][] = $element;
            }
            else
            {
                $sort[$key] = $element;
            }
        }

        if ($leaf)
        {
            if ($reverseOrder)
            {
                krsort($sort);
            }
            else
            {
                ksort($sort);
            }
        }
       
        return $sort;
    }

    private function list(array $array)
    {
        $list = [];
        foreach ($array as $element) 
        {
            if (is_array($element))
            {
                $element    = $this->list($element);
                $list       = array_merge($list, $element);
            }
            else
            {
                $list[] = $element;
            }
        }

        return $list;
    }

    public function getIterator() : Enumerator
    {
        return new Enumerator($this->Array);
    }
}