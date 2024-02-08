<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler\Expressions;

use DevNet\System\Compiler\ExpressionParser;
use Closure;

class LambdaExpression extends Expression
{
    public $Body;
    public array $Parameters = [];
    public ?string $ReturnType = null;

    public function __construct($predicate, array $parameters = [], ?string $returnType = null)
    {
        if ($predicate instanceof Expression) {
            $this->Body = $predicate;
            $this->Parameters = $parameters;
            $this->ReturnType = $returnType;
        } else if ($predicate instanceof Closure) {
            $parser = ExpressionParser::getInstance();
            $parser->parse($predicate);
            $this->Body = $parser->getBody();
            $this->Parameters = $parser->getParameters();
        } else {
            throw new \Exception("argument 1 must be type of Closure or Expression ");
        }
    }

    public function accept(ExpressionVisitor $visitor): void
    {
        $visitor->visitLambda($this);
    }
}
