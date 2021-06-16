<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

use DevNet\System\Async\Task;

function await(Task $task)
{
    $task->wait();
    return $task->Result;
}

/**
 * add some PHP 8 features to PHP 7.
 */
if(!function_exists("str_contains"))
{
    function str_contains(string $string, string $needle) : bool
    {
        return strpos($string, $needle) !== false ? true : false;
    }
}

if(!function_exists("str_starts_with"))
{
    function str_starts_with(string $string, string $needle) : bool
    {
        return strpos($string, $needle) === 0 ? true : false;
    }
}

if(!function_exists("str_ends_with"))
{
    function str_ends_with(string $string, string $needle) : bool
    {
        return strpos(strrev($string), strrev($needle)) === 0 ? true : false;
    }
}
