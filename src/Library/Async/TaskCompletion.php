<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Async;

use DateTime;
use Closure;

class TaskCompletion
{
    private static array $tasks = [];

    private Task $Task;
    private $result = null;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function setCancelled()
    {
        $this->state = 0;
    }

    public function complited()
    {
        $this->state = 1;
    }
}