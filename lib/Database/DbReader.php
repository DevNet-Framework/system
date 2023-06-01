<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Database;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;

abstract class DbReader implements IEnumerable
{
    /**
     * Advances the reader to the next record in the result set.
     */
    public abstract function read(): bool;

    /**
     * Gets the name of the column, by given column ordinal.
     */
    public abstract function getName(int $ordinal): ?string;

    /**
     * Gets the value of the specified column.
     */
    public abstract function getValue(string $name);

    /**
     * Closes the DbReader object.
     */
    public abstract function close(): void;

    /**
     * Iterates through the rows in the DbReader.
     */
    public abstract function getIterator(): Enumerator;
}
