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

class SelectEnumerable implements IEnumerable
{
    use \Artister\System\Extension\ExtensionTrait;
    
    private IEnumerable $Enumerable;
    private array $Array = [];

    public function __construct(IEnumerable $enumerable)
    {
        $this->Enumerable = $enumerable;
    }

    public function select(Closure $predecate)
    {
        $elements = [];
        foreach ($this->Enumerable as $element)
        {
            $object = $predecate($element);
            $elements[] = $object;
        }

        $this->Array = $elements;
        return $this;
    }

    public function getIterator() : Enumerator
    {
        return new Enumerator($this->Array);
    }
}