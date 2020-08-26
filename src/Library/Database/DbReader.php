<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Database;

use Artister\System\Collections\Enumerator;
use IteratorAggregate;

class DbReader implements IteratorAggregate
{
    private DbCommand $Command;

    public function __construct(DbCommand $command)
    {
        $this->Command = $command;
    }

    public function read()
    {
        if ($this->Command->Connection->getState() == 1) {
            return $this->Command->Statement->fetch();
        }

        $this->Command->Statement->closeCursor();
        return null;

    }

    public function reset()
    {
        $this->Command->Statement->closeCursor();
    }

    public function close()
    {
        $this->Command->Statement->closeCursor();
        $this->Command->Statement = null;
        $this->Command->Connection = null;
    }

    public function getIterator() : iterable
    {
        return new Enumerator($this->Command->Statement->fetchAll());
    }
}