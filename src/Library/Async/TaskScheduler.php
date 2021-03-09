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

class TaskScheduler
{
    private static array $Tasks = [];

    public function add(Task $task)
    {
        self::$Tasks[$task->Id] = $task;
    }

    public static function run()
    {
        //set time out
        $timeOut = [];
        $currentTime = 1000 * microtime(true);
        foreach (self::$Tasks as $key => $task)
        {
            $timeOut[$key] = $currentTime + $task->Delay;
        }

        while (self::$Tasks)
        {
            foreach (self::$Tasks as $key => $task)
            {
                $currentTime = 1000 * microtime(true);
                
                if ($currentTime > $timeOut[$key])
                {
                    $action = $task->Action;
                    $action($task);

                    if ($task->Status == 1)
                    {
                        unset(self::$Tasks[$key]);
                    }
                }
            }
        }
    }
}