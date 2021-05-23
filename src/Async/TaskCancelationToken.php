<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Async;

use Closure;

class TaskCancelationToken
{
    private TaskCancelationSource $Canceler;
    private Closure $Action;
    private bool $IsCanceled = false;

    public function __construct($canceler)
    {
        $this->Canceler = $canceler;
    }

    public function __get(string $name)
    {
        if ($name == 'IsCanceled')
        {
            return $this->Canceler->IsCanceled;
        }
        
        return $this->$name;
    }

    public function register(Closure $action)
    {
        $this->Action = $action;
    }
}
