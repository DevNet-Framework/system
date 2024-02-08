<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\System\Compiler;

use DevNet\System\Compiler\Expressions\Expression;
use DevNet\System\Compiler\Lexing\LexerBuilder;
use DevNet\System\Compiler\Parsing\ParserBuilder;
use DevNet\System\Compiler\Parsing\ParserException;
use DevNet\System\Compiler\Parsing\Parser;
use DevNet\System\Compiler\Parsing\Stack;
use ReflectionFunction;
use Closure;

class ExpressionParser
{
    private static $instance;
    private Parser $parser;
    private int $startLine;
    private int $position;
    private ReflectionFunction $function;
    private array $outerVariables = [];

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public static function getInstance(): static
    {
        if (self::$instance) {
            return self::$instance;
        }

        $lexerBuilder = new LexerBuilder();
        $lexerBuilder->define('fn', '(?i)fn');
        $lexerBuilder->define('=>', '=>');
        $lexerBuilder->define('->', '->');
        $lexerBuilder->define('bool', '(?i)false|true');
        $lexerBuilder->define('number', "\d*[.]?\d+");
        $lexerBuilder->define('string', "'(\\\.|[^'])*'");
        $lexerBuilder->define('string', '"(\\\.|[^"])*"');
        $lexerBuilder->define('variable', '\$[A-Za-z_][\w]*');
        $lexerBuilder->define('identifier', '[A-Za-z_][\w]*');
        $lexerBuilder->define('Inequality', '(<=|>=|<|>)');
        $lexerBuilder->define('equality', '(==|===|!=)');
        $lexerBuilder->define('sign', '(\+|-)');
        $lexerBuilder->define('times', '(\*|/)');
        $lexerBuilder->define('=', '=');
        $lexerBuilder->define('||', '\|\|');
        $lexerBuilder->define('&&', '&&');
        $lexerBuilder->define('!', '!');
        $lexerBuilder->define('(', '\(');
        $lexerBuilder->define(')', '\)');
        $lexerBuilder->define('[', '\[');
        $lexerBuilder->define(']', '\]');
        $lexerBuilder->define('}', '\}');
        $lexerBuilder->define('{', '\{');
        $lexerBuilder->define(';', ';');
        $lexerBuilder->define(',', ',');

        $lexer = $lexerBuilder->build();
        $parserBuilder = new ParserBuilder($lexer);

        $parserBuilder->define('lambda', ['fn', '(', ')', '=>', 'statement']); //1
        $parserBuilder->define('lambda', ['fn', '(', 'list', ')', '=>', 'statement']); //2
        $parserBuilder->define('statement', ['binary-exp']); //3
        $parserBuilder->define('binary-exp', ['binary-exp', '||', 'binary-term']); //4
        $parserBuilder->define('binary-exp', ['binary-term']); //5
        $parserBuilder->define('binary-term', ['binary-term', '&&', 'relation-exp']); //6
        $parserBuilder->define('binary-term', ['relation-exp']); //7
        $parserBuilder->define('relation-exp', ['relation-exp', 'equality', 'relation-term']); //8
        $parserBuilder->define('relation-exp', ['relation-term']); //9
        $parserBuilder->define('relation-term', ['relation-term', 'Inequality', 'expression']); //10
        $parserBuilder->define('relation-term', ['expression']); //11
        $parserBuilder->define('expression', ['expression', 'sign', 'term']); //12
        $parserBuilder->define('expression', ['term']); //13
        $parserBuilder->define('term', ['term', 'times', 'factor']); //14
        $parserBuilder->define('term', ['factor']); //15
        $parserBuilder->define('factor', ['bool']); //16
        $parserBuilder->define('factor', ['number']); //17
        $parserBuilder->define('factor', ['string']); //18
        $parserBuilder->define('factor', ['variable']); //19
        $parserBuilder->define('factor', ['identifier']); //20
        $parserBuilder->define('factor', ['unary']); //21
        $parserBuilder->define('factor', ['assignment']); //22
        $parserBuilder->define('factor', ['group']); //23
        $parserBuilder->define('factor', ['property']); //24
        $parserBuilder->define('factor', ['method']); //25
        $parserBuilder->define('factor', ['call']); //26
        $parserBuilder->define('factor', ['array']); //27
        $parserBuilder->define('unary', ['sign', 'factor']); //28
        $parserBuilder->define('unary', ['!', 'factor']); //29
        $parserBuilder->define('assignment', ['variable', '=', 'binary-exp']); //30
        $parserBuilder->define('group', ['(', 'binary-exp', ')']); //31
        $parserBuilder->define('property', ['variable', '->', 'identifier']); //32
        $parserBuilder->define('property', ['variable', '->', 'variable']); //33
        $parserBuilder->define('method', ['variable', '->', 'call']); //34
        $parserBuilder->define('call', ['identifier', '(', ')']); //35
        $parserBuilder->define('call', ['identifier', '(', 'list', ')']); //36
        $parserBuilder->define('array', ['variable', '[', 'list', ']']); //37
        $parserBuilder->define('list', ['list', ',', 'expression']); //38
        $parserBuilder->define('list', ['expression']); //39

        $parser = $parserBuilder->build();

        self::$instance = new ExpressionParser($parser);
        return self::$instance;
    }

    public function parse(Closure $function): void
    {
        $this->position = 0;
        $this->startLine = 0;
        $this->function = new ReflectionFunction($function);
        $this->outerVariables =  $this->function->getStaticVariables();

        $fileName = $this->function->getFileName();
        $startLine = $this->function->getStartLine() - 1; // adjustment by - 1, because line 1 is in inedx 0
        $endLine = $this->function->getEndLine();
        $length = $endLine - $startLine;

        $source = file($fileName, FILE_IGNORE_NEW_LINES);
        $lines = array_slice($source, $startLine, $length);
        $functionLine = implode("\n", $lines);

        if ($this->startLine == $startLine) {
            $position = $this->position;
        } else {
            $position = 0;
        }

        preg_match("/\((?:[^)(]+|(?R))*+\)/", $functionLine, $matches, PREG_OFFSET_CAPTURE, $position);
        if ($matches) {
            $this->position = $matches[0][1] + strlen($matches[0][0]);
            $this->startLine = $startLine;
            $body = preg_replace("/\(\s*fn\s*\(.*?\)\s*=>\s*(.*?)\)/", "\\1", $matches[0][0]);
        }

        $this->parser->consume($body);
    }

    public function getBody(): ?Expression
    {
        $list = [];
        $expression = null;
        $stack = new Stack();
        do {
            $this->parser->advance();

            if ($this->parser->Action == Parser::ERROR) {
                $node = $this->parser->getNode();
                if ($node->getName() == 'UNKNOWN') {
                    throw new \Exception("UNKNOWN Token");
                }
            }

            if ($this->parser->Action == Parser::REDUCE) {
                $ruleId = $this->parser->getReduceId();
                $node = $this->parser->getNode();
                $items = $node->getValues();

                switch ($ruleId) {
                    case 39: // one item list
                    case 38: // nested list
                        $list[] = $stack->pop();
                        break;
                    case 36: // call with arguments
                        $expression = Expression::call(null, $items[0]->getValue(), $list);
                        $stack->push($expression);
                        $list = [];
                        break;
                    case 35: // call without arguments
                        $expression = Expression::call(null, $items[0]->getValue());
                        $stack->push($expression);
                        break;
                    case 33: // variable property
                        $parameterName = ltrim($items[0]->getValue(), '$');
                        $parameter     = Expression::parameter($parameterName, $items[0]->getName());
                        $variableName  = ltrim($items[2]->getValue(), '$');

                        if (!array_key_exists($variableName, $this->outerVariables)) {
                            throw new ParserException("Undefined property variable \${$parameterName}::\${$variableName}");
                        }

                        if (!is_string($this->outerVariables[$variableName])) {
                            throw new ParserException("Variable property {$parameterName}::\${$variableName} must be of type string.");
                        }

                        $propertyName = $this->outerVariables[$variableName];
                        $expression   = Expression::property($parameter, $propertyName);
                        $stack->push($expression);
                        break;
                    case 32: // property
                        $name = ltrim($items[0]->getValue(), '$');
                        $value = $this->outerVariables[$name] ?? null;
                        $parameter = Expression::parameter($name, $items[0]->getName(), $value);
                        $expression = Expression::property($parameter, $items[2]->getValue());
                        $stack->push($expression);
                        break;
                    case 31: // group
                        $expression = $stack->pop();
                        $expression = Expression::group('bracket', $expression);
                        $stack->push($expression);
                        break;
                    case 30: // assignment
                        $expression = $stack->pop();
                        $variable = Expression::parameter($items[0]->getValue(), $items[0]->getName());
                        $expression = Expression::binary($items[1]->getValue(), $variable, $expression);
                        $stack->push($expression);
                        break;
                    case 28: // not
                    case 27: // sign
                        $expression = $stack->pop();
                        $expression = Expression::unary($items[0]->getValue(), $expression);
                        $stack->push($expression);
                        break;
                    case 19: // variable
                        $name  = ltrim($items[0]->getValue(), '$');
                        $value = $this->outerVariables[$name] ?? null;

                        if (is_array($value)) {
                            throw new ParserException("value of type array not supported yet, only object and scalar types are supported.");
                        }
                        $value = $this->outerVariables[$name];

                        $expression = Expression::parameter($name, gettype($value), $value);
                        $stack->push($expression);
                        break;
                    case 18: // string (remove double quotes quotes & escaping slashes)
                        $value = $items[0]->getValue();
                        $value = preg_replace("%^\"+|\"+$|^\'+|\'+$%", "", $value);
                        $value = stripslashes($value);
                        $expression = Expression::constant($value, $items[0]->getName());
                        $stack->push($expression);
                        break;
                    case 17: // number (convert string to number)
                        $value = $items[0]->getValue() + 0;
                        $expression = Expression::constant($value, $items[0]->getName());
                        $stack->push($expression);
                        break;
                    case 16: // bool
                        $value = strtolower($items[0]->getValue()) == "true" ? true : false;
                        $expression = Expression::constant($value, $items[0]->getName());
                        $stack->push($expression);
                        break;
                    case 14: // product
                    case 12: // product
                    case 10: // product
                    case 8: // product
                    case 6: // product
                    case 4: // product
                        $right = $stack->pop();
                        $left = $stack->pop();
                        $expression = Expression::binary($items[1]->getValue(), $left, $right);
                        $stack->push($expression);
                        break;
                    case 2: // lambda with parameters
                        $body = $stack->pop();
                        $expression = Expression::lambda($body, $list);
                        $stack->push($expression);
                        $list = [];
                        break;
                    case 1: // lambda without parameters
                        $body = $stack->pop();
                        $expression = Expression::lambda($body);
                        $stack->push($expression);
                        break;
                }
            }
        } while ($this->parser->Action != Parser::ACCEPT && $this->parser->Action != Parser::ERROR);

        return $expression;
    }

    public function getParameters(): array
    {
        $parameters = [];
        foreach ($this->function->getParameters() as $paramReflector) {
            $parameterName = $paramReflector->getName();
            $parameterType = null;
            if ($paramReflector->getType()) {
                $parameterType = $paramReflector->getType()->getName();
            }
            $parameters[] = Expression::parameter($parameterName, $parameterType);
        }

        return $parameters;
    }

    public function getOuterVariables(): array
    {
        return $this->outerVariables;
    }
}
