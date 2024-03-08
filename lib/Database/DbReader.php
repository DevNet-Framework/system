<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
