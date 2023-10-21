<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\IO;

enum FileMode: int
{
    /**
     * Creates a new file or throw an exception if the file already exists.
     */
    case Create = 1;

    /**
     * Opens an existing file or throws an exception if the file does not exist.
     */
    case Open = 2;

    /**
     * Opens an existing file or creates a new one if the file does not exist.
     */
    case OpenOrCreate = 3;
}
