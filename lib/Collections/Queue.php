<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Collections;

use DevNet\System\Type;
use DevNet\System\Text\StringBuilder;
use DevNet\System\Exceptions\TypeException;
use DevNet\System\Exceptions\ErrorMessageExtension;

class Stack implements IEnumerable
{
    use \DevNet\System\Extension\ExtenderTrait;

    private array $array = [];
    private Type $genericType;

    public function __construct(string $valueType)
    {
        $this->genericType = new Type(self::class, new Type($valueType));
    }

    public function enqueue($value): void
    {
        $index = $this->genericType->validateArguments($value);
        if ($index > 0) {
            $message = new StringBuilder();
            $message->invalidArgumentType(get_class($this), 'push', $index, $this->genericType->GenericTypeArgs[$index - 1]->Name);
            throw new TypeException($message->__toString());
        }

        $this->array[$value];
    }

    public function dequeue()
    {
        return array_shift($this->array);
    }

    public function peek()
    {
        return reset($this->array);
    }

    public function contains($item): bool
    {
        return in_array($item, $this->array);
    }

    public function remove($item): void
    {
        if (isset($this->array[$item])) {
            unset($this->array[$item]);
        }
    }

    public function clear(): void
    {
        $this->array = [];
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->array);
    }

    public function toArray(): array
    {
        return $this->array;
    }

    public function getType(): Type
    {
        return $this->genericType;
    }
}
