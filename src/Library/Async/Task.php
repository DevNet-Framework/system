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
use Exception;

class Task
{
    private static array $Tasks = [];

    private Closure $Action;
    private int $Id;
    private int $Delay;
    private int $Status = 0;
    private Exception $Exception;
    private array $Actions = [];
    private $Result = null;

    public function __construct(Closure $action)
    {
        $this->Action = $action;
        $this->Id = spl_object_id ($this);
        $this->Delay = 0;
    }

    public function __get(string $name)
    {
        if ($name == 'Result')
        {
            return $this->wait();
        }

        return $this->$name;
    }

    public function start(TaskScheduler $taskScheduler = null)
    {
        self::$Tasks[$this->Id] = $this;
    }

    public function delay(int $delay)
    {
        $this->Delay = $delay;
        return $this;
    }

    public function wait()
    {
        if (isset(self::$Tasks[$this->Id]))
        {
            unset(self::$Tasks[$this->Id]);
        }

        $action = $this->Action;
        try {
            $this->Result = $action();
            $this->Status = 1;
            foreach ($this->Actions as $action)
            {
                $this->Result = $action($this->Result);
            }
        }
        catch (TaskException $exception)
        {
            $this->Exception = $exception;
            $this->Status = -1;
        }

        return $this->Result;
    }

    public function continueWith(Closure $next)
    {
        $this->Actions[] = $next;
        return $this;
    }

    public function then(object $next)
    {
    
        if (isset(self::$Tasks[$this->Id]))
        {
            unset(self::$Tasks[$this->Id]);
        }

        $action = $this->Action;
        try
        {
            $this->Result = $action($next);
            $this->Status = 1;
        }
        catch (TaskException $exception)
        {
            $this->Exception = $exception;
            $this->Status = -1;
        }

        return $this->Result;
    }

    public static function completedTask()
    {
        return new Task(fn() => null);
    }
}