<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Diagnostic;

use Artister\System\Diagnostic\Internal\DebugInspector;
use Artister\System\Diagnostic\Internal\DebugHandler;
use Artister\System\Diagnostic\Internal\DisableHandler;

class Debuger
{
    private DebugInspector $Inspector;
    private IDebugHandler $Handler;

    public function __construct()
    {
        $this->Inspector    = new DebugInspector();
        $this->Handler      = new DebugHandler();
    }

    public function setHandler(IDebugHandler $handler) : void
    {
        $this->Handler = $handler;
    }

    public function enable() : void
    {
        $this->Inspector->register($this->Handler);
    }

    public function disable() : void
    {
        $this->Inspector->register(new DisableHandler());
        /* restore_error_handler();
        restore_exception_handler();
        ini_set("display_errors", "on");
        error_reporting(); */
    }
}