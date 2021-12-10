<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

require __DIR__ . '/../../../../../../vendor/autoload.php';

use DevNet\System\Runtime\ClassLoader;
use DevNet\System\Async\TaskException;
use Opis\Closure\SerializableClosure;

class TaskWorker
{
    private string $Data;

    public function __construct(array $args)
    {
        $this->Data = $args[2];
        $this->load($args[1]);
        set_error_handler([$this, 'errorHandler']);
    }

    public function load(string $workspace): void
    {
        if (file_exists($workspace . '/vendor/autoload.php')) {
            require $workspace . '/vendor/autoload.php';
        }

        if (file_exists($workspace . "/project.phproj")) {
            $projectFile = simplexml_load_file($workspace . "/project.phproj");
            if ($projectFile) {
                $namespace = (string)$projectFile->properties->namespace;
                if (!empty($namespace)) {
                    $loader = new ClassLoader($workspace);
                    $loader->map($namespace, "/");
                    $loader->register();
                }
            }
        }
    }

    public function execute(): void
    {
        try {
            $result = null;
            $action = unserialize(base64_decode($this->Data));
            $result = $action();

            if ($result instanceof Closure) {
                $result = new SerializableClosure($result);
            }

            fwrite(STDERR, serialize($result));
        } catch (\Throwable $error) {
            $exception = new TaskException($error->getMessage(), $error->getCode());
            fwrite(STDERR, serialize($exception));
        }
    }

    public function errorHandler(int $severity, string $message, string $file, int $line)
    {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
}

$worker = new TaskWorker($argv);
$worker->execute();
exit;
