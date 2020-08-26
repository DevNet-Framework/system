<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Linq;

use Artister\System\Collections\Enumerator;
use Artister\System\Database\DbConnection;
use Artister\System\Linq\Expressions\Expression;
use Artister\System\Linq\Expressions\ExpressionSqlBuilder;
use Artister\System\Linq\IQueryable;

class QueryProvider implements IQueryProvider
{
    private ?DbConnection $Connection;

    public function __construct(DbConnection $connection = null)
    {
        $this->Connection = $connection;
    }

    public function CreateQuery(string $resultType, Expression $expression = null)
    {
        return new Queryable($resultType, $this, $expression);
    }

    /**
     * create the Db command and excute the query than return Iterable collection of entities
     */
    public function execute(string $resultType, Expression $expression)
    {
        $visitor = new ExpressionSqlBuilder();
        $visitor->visit($expression);
        $slq = $visitor->out;
        $this->Connection->open();
        $command = $this->Connection->createCommand($slq);
        if ($visitor->outerVariables) {
            $command->addParameters($visitor->outerVariables);
        }

        $dbReader = $command->executeReader($resultType);

        if (!$dbReader) {
            return new Enumerator();
        }

        $entities = [];
        foreach ($dbReader as $entity) {
            $entities[] = $entity;
            $entry = $this->Mapper->EntityStateManager->getEntry($entity);
            if ($entry) {
                $this->Mapper->EntityStateManager->addEntry($entity);
            }
        }

        return new Enumerator($entities);
    }

    public function GetQueryText(Expression $expression) : string
    {
        $visitor = new ExpressionSqlBuilder();
        $visitor->visit($expression);
        return $visitor->Out;
    }
}