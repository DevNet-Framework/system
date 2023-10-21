<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

class File
{
    private string $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function create(FileAccess $fileAccess = FileAccess::ReadWrite): FileStream
    {
        try {
            $fileStream = new FileStream($this->fileName, FileMode::Create, $fileAccess);
        } catch (FileException $exception) {
            throw new FileException($exception->getMessage(), 0, 1);
        }

        return $fileStream;
    }

    public function open(FileAccess $fileAccess = FileAccess::ReadWrite): FileStream
    {
        try {
            $fileStream = new FileStream($this->fileName, FileMode::Open, $fileAccess);
        } catch (FileException $exception) {
            throw new FileException($exception->getMessage(), 0, 1);
        }

        return $fileStream;
    }

    public function openRead(): FileStream
    {
        try {
            $fileStream = new FileStream($this->fileName, FileMode::Open, FileAccess::Read);
        } catch (FileException $exception) {
            throw new FileException($exception->getMessage(), 0, 1);
        }

        return $fileStream;
    }

    public function openWrite(): FileStream
    {
        try {
            $fileStream = new FileStream($this->fileName, FileMode::Open, FileAccess::Write);
        } catch (FileException $exception) {
            throw new FileException($exception->getMessage(), 0, 1);
        }

        return $fileStream;
    }

    public function delete(): bool
    {
        return unlink($this->fileName);
    }

    public function copyTo(string $destFileName): bool
    {
        return copy($this->fileName, $destFileName);
    }
}
