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

class CountEnumerable implements IEnumerable
{
    use \Artister\System\Extension\ExtensionTrait;

    private IEnumerable $Enumerable;

    public function __construct(IEnumerable $enumerable)
    {
        $this->Enumerable = $enumerable;
    }

    public function count(Closure $predecate = null) : int
    {
        $cout = 0;
        foreach ($this->Enumerable as $element)
        {
            if ($predecate)
            {
                if ($predecate($element))
                {
                    $cout++;
                }
            }
            else
            {
                $cout++;
            }
        }

        return $cout;
    }

    public function max(Closure $predecate = null)
    {
        $value = null;
        foreach ($this->Enumerable as $element)
        {
            if ($predecate)
            {
                $element = $predecate($element);
            }

            if ($value == null || $element > $value)
            {
                $value = $element;
            }
        }

        return $value;
    }

    public function min(Closure $predecate = null)
    {
        $value = null;
        foreach ($this->Enumerable as $element)
        {
            if ($predecate)
            {
                $element = $predecate($element);
            }

            if ($value == null || $element < $value)
            {
                $value = $element;
            }
        }

        return $value;
    }

    public function getIterator() : Enumerator
    {
        return $this->Enumerable->getIterator();
    }
}