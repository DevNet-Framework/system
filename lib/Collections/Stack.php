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
    use \DevNet\System\Extension\ExtensionTrait;

    private array $Array = [];
    private Type $GenericType;

    public function __construct(string $valueType)
    {
        $this->GenericType = new Type(self::class, new Type($valueType));
    }

    public function push($value): void
    {
        $index = $this->GenericType->validateArguments($value);
        if ($index > 0) {
            $message = new StringBuilder();
            $message->invalidArgumentType(get_class($this), 'push', $index, $this->GenericType->GenericTypeArgs[$index - 1]->Name);
            throw new TypeException($message->__toString());
        }

        $this->Array[$value];
    }

    public function pop()
    {
        return array_pop($this->Array);
    }

    public function peek()
    {
        return end($this->Array);
    }

    public function contains($item): bool
    {
        return in_array($item, $this->Array);
    }

    public function remove($item): void
    {
        if (isset($this->Array[$item])) {
            unset($this->Array[$item]);
        }
    }

    public function clear(): void
    {
        $this->Array = [];
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->Array);
    }

    public function toArray(): array
    {
        return $this->Array;
    }

    public function getType(): Type
    {
        return $this->GenericType;
    }
}
