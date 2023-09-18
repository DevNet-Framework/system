<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

use DevNet\System\Async\CancelationToken;
use DevNet\System\Async\Task;
use DevNet\System\PropertyTrait;

abstract class Stream
{
    use PropertyTrait;

    protected $resource;

    public function get_IsSeekable(): bool
    {
        $meta = stream_get_meta_data($this->resource);
        return $meta['seekable'];
    }

    public function get_IsReadable(): bool
    {
        $meta = stream_get_meta_data($this->resource);
        $mode = $meta['mode'];

        return (strstr($mode, 'r') || strstr($mode, '+'));
    }

    public function get_IsWritable(): bool
    {
        $meta = stream_get_meta_data($this->resource);
        $mode = $meta['mode'];

        return (strstr($mode, 'x')
            || strstr($mode, 'w')
            || strstr($mode, 'c')
            || strstr($mode, 'a')
            || strstr($mode, '+'));
    }

    public function get_EndOfStream(): bool
    {
        return feof($this->resource);
    }

    public function get_Length(): ?int
    {
        $stats = fstat($this->resource);

        if ($stats !== false) {
            return $stats['size'];
        }

        return null;
    }

    public function get_Position(): ?int
    {
        $position = ftell($this->resource);

        if ($position === false) {
            return null;
        }

        return $position;
    }

    public function set_Position(int $offset): void
    {
        $this->seek($offset);
    }

    public function seek(int $offset, int $whence = SEEK_SET): int
    {
        if (!$this->IsSeekable) {
            throw new StreamException("The resource is not seekable");
        }

        $result = fseek($this->resource, $offset, $whence);

        return $result;
    }

    public function read(int $length): string
    {
        if ($length <= 0) {
            throw new StreamException("Length must be greater than 0", 0, 1);
        }

        if ($this->IsSeekable && $this->Position == $this->Length) {
            $this->seek(0);
        }

        stream_set_blocking($this->resource, true);
        $result = fread($this->resource, $length);

        if ($result === false) {
            throw new StreamException("Unable to read from the resource", 0, 1);
        }

        return $result;
    }

    public function readAsync(int $length, ?CancelationToken $cancellation = null): Task
    {
        return Task::run(function () use ($length) {
            if ($length <= 0) {
                throw new StreamException("Length must be greater than 0", 0, 1);
            }

            if ($this->IsSeekable && $this->Position == $this->Length) {
                $this->seek(0);
            }

            do {
                stream_set_blocking($this->resource, false);
                $result = yield fread($this->resource, $length);
                if ($result === false) {
                    throw new StreamException("Unable to read from the resource", 0, 1);
                }
            } while (!$this->EndOfStream && $result === '');

            return $result;
        }, $cancellation);
    }

    public function readLine(): string
    {
        stream_set_blocking($this->resource, true);
        $result = fgets($this->resource);

        if ($result === false) {
            throw new StreamException("Unable to read from the resource", 0, 1);
        }

        return $result;
    }

    public function readLineAsync(?CancelationToken $cancellation = null): Task
    {
        return Task::run(function () {
            do {
                stream_set_blocking($this->resource, false);
                $result = yield fgets($this->resource);
                if ($result === false) {
                    throw new StreamException("Unable to read from the resource", 0, 1);
                }
            } while (!$this->EndOfStream && $result === '');

            return $result;
        }, $cancellation);
    }

    public function write(string $string): int
    {
        stream_set_blocking($this->resource, true);
        $result = fwrite($this->resource, $string);

        if ($result === false) {
            throw new StreamException('Unable to write to resource', 0, 1);
        }

        return $result;
    }

    public function writeAsync(string $value, ?CancelationToken $cancellation = null): Task
    {
        return Task::run(function () use ($value) {
            do {
                stream_set_blocking($this->resource, false);
                $result = yield fwrite($this->resource, $value);
                if ($result === false) {
                    throw new StreamException('Unable to write to resource', 0, 1);
                }

                if ($result < strlen($value)) {
                    $value = substr($value, $result);
                    $result = 0;
                }
            } while ($result !== strlen($value));

            return $result;
        }, $cancellation);
    }

    public function flush(): void
    {
        $result = fflush($this->resource);

        if ($result === false) {
            throw new StreamException("Unable to flush the resource", 0, 1);
        }
    }

    public function flushAsync(): Task
    {
        return Task::run(function () {
            $result = yield fflush($this->resource);

            if ($result === false) {
                throw new StreamException("Unable to flush the resource", 0, 1);
            }
        });
    }

    public function truncate(int $size): void
    {
        $result = ftruncate($this->resource, $size);

        if ($result === false) {
            throw new StreamException("Unable to truncate the resource", 0, 1);
        }
    }

    public function close(): void
    {
        fclose($this->resource);
    }

    public function __toString(): string
    {
        if ($this->IsSeekable) {
            $this->seek(0);
        }

        $result = "";
        while (!$this->EndOfStream) {
            $result .= $this->read(1024);
        }

        return $result;
    }
}
