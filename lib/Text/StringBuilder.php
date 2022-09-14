<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Text;

use DevNet\System\ObjectTrait;

class StringBuilder
{
    use ObjectTrait;

    private int $capacity = 0;
    private string $text  = '';

    public function __construct(int $capacity = 0)
    {
        $this->capacity = $capacity;
    }

    public function append(string $value): StringBuilder
    {
        $this->text .= $value;
        return $this;
    }

    public function appendLine(string $value = null): StringBuilder
    {
        $this->text .= $value . PHP_EOL;
        return $this;
    }

    public function appendJoin(string $separator, array $values): StringBuilder
    {
        $this->text .= implode($separator, $values);
        return $this;
    }

    public function appendFormat(string $format, array $values): StringBuilder
    {
        $this->text .= printf($format, $values);
        return $this;
    }

    public function copy(int $index, int $length): StringBuilder
    {
        $text   = substr($this->text, $index, $length);
        $string = new StringBuilder();
        $string->append($text);
        return $string;
    }

    public function insert(string $value, int $index, int $length = 0): StringBuilder
    {
        $this->text = substr_replace($this->text, $value, $index, $length);
        return $this;
    }

    public function remove(int $index, int $length): StringBuilder
    {
        $this->text = substr_replace($this->text, '', $index, $length);
        return $this;
    }

    public function replace(string $oldValue, string $newValue): StringBuilder
    {
        $this->text = str_replace($oldValue, $newValue, $this->text);
        return $this;
    }

    public function clear(): StringBuilder
    {
        $this->text = "";
        return $this;
    }

    public function getLength(): int
    {
        return strlen($this->text);
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
