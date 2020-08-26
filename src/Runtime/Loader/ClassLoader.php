<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Runtime\Boot;

use Artister\System\IO\Console;
use Closure;

class Loader
{
    private object $ClassLoader;

    public function __construct(object $loader)
    {
        $this->ClassLoader = $loader;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }
    
    public function getPath(string $className) : ?string
    {
        foreach ($this->ClassLoader->getPrefixesPsr4() as $prefix => $path)
        {
            $pattern = str_replace("\\", "\\\\", $prefix);
            preg_match("%".$pattern."%", $className, $matches);
            
            if ($matches)
            {
                $realPath = realpath($path[0]);
                $directory = str_replace($prefix, '', $className);
                return  $realPath.DIRECTORY_SEPARATOR.$directory.".php";
            }
        }

        return null;
    }
}
