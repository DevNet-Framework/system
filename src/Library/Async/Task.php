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

class Task
{
    public const Created   = 0;
    public const Started   = 1;
    public const Completed = 2;
    public const Faulted   = -1;
    public const Canceled  = -2;

    private Closure $Action;
    private TaskScheduler $Scheduler;
    private int $Id;
    private int $Status;
    private $Result = null;

    public function __construct(Closure $action)
    {
        $this->Action = $action;
        $this->Id = spl_object_id ($this);
        $this->Status = Self::Created;
        $this->Scheduler = TaskScheduler::getDefaultScheduler();
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function start(TaskScheduler $taskScheduler = null) : void
    {
        if ($taskScheduler)
        {
            $this->Scheduler = $taskScheduler;
        }
        
        if ($this->Status === self::Created)
        {
            $this->Status = Self::Started;
            $this->Scheduler->add($this);
        }
        
    }

    public function then(Closure $next) : Task
    {
        $previous = $this;
        $next = function () use ($previous, $next)
        {
            if ($previous->Status !== self::Completed)
            {
                $previous->wait();
            }

            return $next($previous);
        };
        
        return new Task($next);
    }

    public function wait() : void
    {
        if ($this->Status === self::Created || $this->Status === self::Started)
        {
            $action = $this->Action;
            $this->Result = $action(null);
            $this->Status = Self::Completed;
            TaskScheduler::getDefaultScheduler()->remove($this);
        }
    }

    public static function completedTask()
    {
        return new Task(fn() => null);
    }
}
