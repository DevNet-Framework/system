<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Exceptions\TypeException;
use DevNet\System\MethodTrait;
use DevNet\System\Template;
use DevNet\System\Type;

#[Template('T')]
class ArrayList extends AbstractArray implements IList
{
    use MethodTrait;

    public function __construct(string $valueType)
    {
        $this->setGenericArguments($valueType);
    }

    public function addRange(array $array): void
    {
        try {
            foreach ($array as $value) {
                $this->offsetSet(null, $value);
            }
        } catch (TypeException $exception) {
            $genericArgs = $this->getType()->getGenericArguments();
            throw new TypeException(static::class . "::addRange(): Argument #1, must be of type array<{$genericArgs['T']}>", 0, 1);
        }
    }

    public function add(#[Type('T')] $element): void
    {
        $this->checkArgumentTypes(func_get_args());
        $this->array[] = $element;
    }

    public function contains(#[Type('T')] mixed $element): bool
    {
        $this->checkArgumentTypes(func_get_args());
        foreach ($this->getIterator() as $value) {
            if ($value == $element) {
                return true;
            }
        }

        return false;
    }

    public function remove(#[Type('T')] mixed $element): void
    {
        $this->checkArgumentTypes(func_get_args());
        foreach ($this->getIterator() as $key => $value) {
            if ($element == $value) {
                $this->offsetUnset($key);
                break;
            }
        }
    }

    public function removeAt(int $index): void
    {
        $this->offsetUnset($index);
    }
}
