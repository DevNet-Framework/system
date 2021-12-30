<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Text;

class StringBuilder
{
    use \DevNet\System\Extension\ExtensionTrait;

    private int $Capacity;
    private string $Text;

    public function __construct(int $capacity = 0)
    {
        $this->Capacity = $capacity;
        $this->Text = '';
    }

    public function append(string $value): StringBuilder
    {
        $this->Text .= $value;
        return $this;
    }

    public function appendLine(string $value = null): StringBuilder
    {
        $this->Text .= $value . PHP_EOL;
        return $this;
    }

    public function appendJoin(string $separator, array $values): StringBuilder
    {
        $this->Text .= implode($separator, $values);
        return $this;
    }

    public function appendFormat(string $format, array $values): StringBuilder
    {
        $this->Text .= printf($format, $values);
        return $this;
    }

    public function copy(int $index, int $length): StringBuilder
    {
        $text   = substr($this->Text, $index, $length);
        $string = new StringBuilder();
        $string->append($text);
        return $string;
    }

    public function insert(string $value, int $index, int $length = 0): StringBuilder
    {
        $this->Text = substr_replace($this->Text, $value, $index, $length);
        return $this;
    }

    public function remove(int $index, int $length): StringBuilder
    {
        $this->Text = substr_replace($this->Text, '', $index, $length);
        return $this;
    }

    public function replace(string $oldValue, string $newValue): StringBuilder
    {
        $this->Text = str_replace($oldValue, $newValue, $this->Text);
        return $this;
    }

    public function clear(): StringBuilder
    {
        $this->Text = "";
        return $this;
    }

    public function getLength(): int
    {
        return strlen($this->Text);
    }

    public function __toString(): string
    {
        return $this->Text;
    }
}
