<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

abstract class Stream
{
    protected float $Timeout = 0;
    protected bool $Blocking = true;
    protected $Resource = null;

    public function isSeekable(): bool
    {
        if (!$this->Resource) {
            return false;
        }

        $meta = stream_get_meta_data($this->Resource);
        return $meta['seekable'];
    }

    public function isReadable(): bool
    {
        if (!$this->Resource) {
            return false;
        }

        $meta = stream_get_meta_data($this->Resource);
        $mode = $meta['mode'];

        return (strstr($mode, 'r') || strstr($mode, '+'));
    }

    public function isWritable(): bool
    {
        if (!$this->Resource) {
            return false;
        }

        $meta = stream_get_meta_data($this->Resource);
        $mode = $meta['mode'];

        return (strstr($mode, 'x')
            || strstr($mode, 'w')
            || strstr($mode, 'c')
            || strstr($mode, 'a')
            || strstr($mode, '+'));
    }

    public function eof(): bool
    {
        if (!$this->Resource) {
            return true;
        }

        return feof($this->Resource);
    }

    public function getSize(): ?int
    {
        if (null === $this->Resource) {
            return null;
        }

        $stats = fstat($this->Resource);

        if ($stats !== false) {
            return $stats['size'];
        }

        return null;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->Resource) {
            throw new \Exception("Missing Resource");
        }

        if (!$this->isSeekable()) {
            throw new \Exception("Resource is not seekable");
        }

        $result = fseek($this->Resource, $offset, $whence);

        return $result;
    }

    public function write(string $string): int
    {
        if (!$this->Resource) {
            throw new \Exception('Missing resource');
        }

        if (!$this->isWritable()) {
            throw new \Exception('not writible');
        }

        $result = fwrite($this->Resource, $string);

        if (false === $result) {
            throw new \Exception('Unable to write to resource');
        }

        return $result;
    }

    public function read(int $buffer = null): ?string
    {
        if (!$this->Resource) {
            throw new \Exception('Missing resource');
        }

        if (!$this->isReadable()) {
            throw new \Exception('Not readable');
        }

        if ($buffer == null) {
            if (!$this->getSize()) {
                return null;
            }
            $buffer = $this->getSize();
            $this->seek(0);
        }

        if ($this->Timeout) {
            stream_set_timeout($this->Resource, (int) $this->Timeout, $this->Timeout * 1000000 % 1000000);
        }

        $result = fread($this->Resource, $buffer);

        if ($result === false) {
            return null;
        }

        return $result;
    }

    public function readLine(): ?string
    {
        if (!$this->Resource) {
            throw new \Exception('Missing resource');
        }

        if (!$this->isReadable()) {
            throw new \Exception('Not readable');
        }

        if ($this->Timeout) {
            stream_set_timeout($this->Resource, (int) $this->Timeout, $this->Timeout * 1000000 % 1000000);
        }

        $result = fgets($this->Resource);

        if ($result === false) {
            return null;
        }

        return $result;
    }

    public function flush(): void
    {
        if (!$this->Resource) {
            throw new \Exception('Missing resource');
        }

        if (!$this->isWritable()) {
            throw new \Exception('not writible');
        }

        rewind($this->Resource);
        fflush($this->Resource);
    }

    public function close(): void
    {
        if ($this->Resource) {
            fclose($this->Resource);
            $this->Resource = null;
        }
    }

    public function __toString(): string
    {
        if (!$this->isSeekable()) {
            return '';
        }

        if (!$this->isReadable()) {
            return '';
        }

        $this->seek(0);
        $result = stream_get_contents($this->Resource);

        if ($result == false) {
            throw new \Exception("Unable to read from the stream");
        }

        return $result;
    }
}
