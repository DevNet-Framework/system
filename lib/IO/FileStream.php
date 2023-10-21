<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

class FileStream extends Stream
{
    public function __construct(string $fileName, FileMode $fileMode, FileAccess $fileAccess = FileAccess::ReadWrite)
    {
        switch ($fileMode->value . $fileAccess->value) {
            case '12':
                $mode = 'x';
                break;
            case '13':
                $mode = 'x+';
                break;
            case '21':
                $mode = 'r';
                break;
            case '22':
                $mode = 'r+'; // 'r' to require the existence of the file but it will be changed to 'c' for write only.
                break;
            case '23':
                $mode = 'r+';
                break;
            case '32':
                $mode = 'c';
                break;
            case '33':
                $mode = 'c+';
                break;
            default:
                throw new FileException("The file access '{$fileAccess->name}' not compatible with the file mode 'Open'", 0, 1);
                break;
        }

        $this->resource = @fopen($fileName, $mode);

        if (!$this->resource && $fileMode->value == 1) {
            throw new FileException("The file: {$fileName} already exists!", 0, 1);
        }
        
        if (!$this->resource || !$this->resource && $fileMode->value == 2) {
            throw new FileException("Could not open the path: {$fileName}", 0, 1);
        }

        // change 'r' to 'c' in the case of "Open for write only".
        if ($fileMode->value . $fileAccess->value == '12') {
            $this->resource = fopen($fileName, 'c');
        }
    }
}
