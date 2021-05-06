<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

require __DIR__ . '/../../../../../../vendor/autoload.php';

use Opis\Closure\SerializableClosure;
use DevNet\System\Async\TaskException;

class Worker
{
    public function __construct()
    {
        set_error_handler([$this, 'errorHandler']);
    }

    public function execute()
    {
        try
        {
            $result = null;
            $status = fstat(STDIN);
            if($status['size'] != 0)
            {
                $serializedTask = fread(STDIN, $status['size']);
                $action = unserialize($serializedTask);
                $result = $action();
                
                if ($result instanceof Closure)
                {
                    $result = new SerializableClosure($result);
                }
                
                fwrite(STDERR, serialize($result));
            }
        }
        catch (\Throwable $error)
        {
            $exception = new TaskException($error->getMessage(), $error->getCode());
            fwrite(STDERR, serialize($exception));
        }
    }

    public function errorHandler(int $severity, string $message, string $file, int $line)
    {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public static function create() : Worker
    {
        return new self;
    }
}

Worker::create()->execute();
exit;
