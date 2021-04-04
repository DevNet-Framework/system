<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System;

class Type
{
    const Boolean = 'boolean';
    const Integer = 'integer';
    const Float   = 'float';
    const String  = 'string';
    const Array   = 'array';
    const Object  = 'object';

    private string $Name;
    private array $GenericTypeArgs;

    public function __construct(string $name, Type ...$argument)
    {
        $this->Name = $name;
        $this->GenericTypeArgs = $argument;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function validateArguments(...$args) : int
    {
        foreach ($this->GenericTypeArgs as $index => $GenericTypeArg)
        {
            if (isset($args[$index]))
            {
                $arg = $args[$index];
                if ($GenericTypeArg->Name == gettype($arg))
                {
                    continue;
                }

                if (is_object($arg))
                {
                    $type = get_class($arg);
                    if ($GenericTypeArg->Name == $type)
                    {
                        continue;
                    }
                }

                return $index + 1;
            }
            else
            {
                return ($index + 1) * -1;
            }

            $index++;
        }

        return 0;
    }

    public function isPrimitive() : bool
    {
        $types = ['boolean', 'integer', 'float', 'string', 'array', 'object'];
        if (in_array($this->Name, $types))
        {
            return true;
        }
        
        return false;
    }

    public function IsGeneric() : bool
    {
        if ($this->GenericTypeArgs)
        {
            return true;
        }
        
        return false;
    }

    public function IsClass() : bool
    {
        if (class_exists($this->Name))
        {
            return true;
        }
        
        return false;
    }

    public function IsInterface() : bool
    {
        if (interface_exists($this->Name))
        {
            return true;
        }
        
        return false;
    }

    public static function typeOf($value) : string
    {
        if (is_object($value))
        {
            return get_class($value);
        }
        else
        {
            return gettype($value);
        }
    }

    public static function getType(string $type)
    {
        return new Type($type);
    }
}
