<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use DateTime;
use Closure;
use Exception;

class TaskCompletion
{
    private Task $Task;

    public function __construct()
    {
        $this->Task = new Task(fn () => null);
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function setResult($result)
    {
        $this->Task = new Task(fn () => $result);
    }

    public function setExecption(string $exception)
    {
        $this->Task = new Task(fn () => throw new TaskException($exception));
    }
}
