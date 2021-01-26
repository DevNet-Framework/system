<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\System\Compiler;

use Artister\System\Compiler\Expressions\Expression;
use Artister\System\Compiler\Lexing\LexerBuilder;
use Artister\System\Compiler\Parsing\ParserBuilder;
use Artister\System\Compiler\Parsing\Stack;
use Artister\System\Compiler\Parsing\Parser;
use Closure;

class ExpressionParser
{
    private static $Instance;
    private Parser $Parser;
    private int $StartLine = 0;
    private int $Position;
    private array $OuterVariables = [];

    public function __construct(Parser $parser)
    {
        $this->Parser = $parser;
    }

    public static function getInstance()
    {
        if (self::$Instance) {
            return self::$Instance;
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
        $parserBuilder->define('array', ['variable', '[','list', ']']); //37
        $parserBuilder->define('list', ['list', ',', 'expression']); //38
        $parserBuilder->define('list', ['expression']); //39

        $parser = $parserBuilder->build();

        self::$Instance = new ExpressionParser($parser);
        return self::$Instance;
    }

    public function parse(Closure $function)
    {
        $variables = [];
        ob_start();
        var_dump($function);
        $buffer = ob_get_clean();
        $buffer = preg_replace('/\s|(bool|int)\((\w+|\d+)\)|(string|)\(\d+\)|:"[\w\\\\]+"/','\\2', $buffer);
        preg_match('/(\{(?:[^}{]+|(?R))*+\})\[/', $buffer, $matches); // keep onley the static values

        if (isset($matches[1])) {
            $buffer = 'array'.$matches[1];
            $buffer = preg_replace('/"(\w+)"(:\w+)/','"\\1\\2"', $buffer);
            $buffer = preg_replace('/object\([\w\\\\]+\)#\d+/','(object)array', $buffer);
            $buffer = preg_replace('/uninitialized\([\w\?\\\\]+\)/','NULL', $buffer);
            $buffer = strtr($buffer, '{}[]', "(), ");
            $buffer = str_replace('(,', '(', $buffer);
            $buffer = '$variables='.$buffer;

            eval($buffer.'?>');
        }

        $this->OuterVariables = $variables;
        $this->FunctionReflector = new \ReflectionFunction($function);

        $fileName = $this->FunctionReflector->getFileName();
        $startLine = $this->FunctionReflector->getStartLine() - 1; // adjustment by - 1, because line 1 is in inedx 0
        $endLine = $this->FunctionReflector->getEndLine();
        $length = $endLine - $startLine;

        $source = file($fileName, FILE_IGNORE_NEW_LINES);
        $lines = array_slice($source, $startLine, $length);
        $functionLine = implode("\n", $lines);
        if ($this->StartLine == $startLine){
            $position = $this->Position;
        } else {
            $position = 0;
        }
        preg_match("/\((?:[^)(]+|(?R))*+\)/", $functionLine, $matches, PREG_OFFSET_CAPTURE, $position);
        if ($matches) {
            $this->Position = $matches[0][1] + strlen($matches[0][0]);
            $this->StartLine = $startLine;
            $body = preg_replace( "/\(\s*fn\s*\(.*?\)\s*=>\s*(.*?)\)/", "\\1", $matches[0][0]);
        }

        $this->Parser->consume($body);
    }
    
    public function getBody() : ?Expression
    {
        $list = [];
        $expression = null;
        $stack = new Stack();
        do {
            $this->Parser->advance();

            if ($this->Parser->action == Parser::ERROR) {
                $node = $this->Parser->getNode();
                if ($node->getName() == 'UNKNOWN') {
                    throw new \Exception("UNKNOWN Token");
                }
            }

            if ($this->Parser->action == Parser::REDUCE) {
                $ruleId = $this->Parser->getReduceId();
                $node = $this->Parser->getNode();
                $items = $node->getValues();

                switch ($ruleId) {
                    case 39: // one item list
                    case 38 : // nested list
                        $list[] = $stack->pop();
                        break;
                    case 36 : // call with arguments
                        $expression = Expression::call(null, $items[0]->getValue(), $list);
                        $stack->push($expression);
                        $list = [];
                        break;
                    case 35 : // call without arguments
                        $expression = Expression::call(null, $items[0]->getValue());
                        $stack->push($expression);
                        break;
                    case 33 : // variable property
                        $name = ltrim($items[0]->getValue(), '$');
                        $parameter = Expression::parameter($name, $items[0]->getName());
                        $name = ltrim($items[2]->getValue(), '$');
                        
                        if (!isset($this->OuterVariables[$name])) {
                            throw new \Exception("Undefined outer variable \${$name}");
                        }

                        if (is_array($this->OuterVariables[$name]) || is_object($this->OuterVariables[$name])) {
                            throw new \Exception("Unsupported outer variable type, only scalar types are supported.");
                        }

                        $name = $this->OuterVariables[$name];
                        $expression = Expression::property($parameter, $name);
                        $stack->push($expression);
                        break;
                    case 32 : // property
                        $name = ltrim($items[0]->getValue(), '$');
                        $parameter = Expression::parameter($name, $items[0]->getName());
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
                    case 28 : // not
                    case 27 : // sign
                        $expression = $stack->pop();
                        $expression = Expression::unary($items[0]->getValue(), $expression);
                        $stack->push($expression);
                        break;
                    case 19 : // variable
                        $name = ltrim($items[0]->getValue(), '$');
                        $value = null;
                        if (isset($this->OuterVariables[$name])) {
                            if (is_array($this->OuterVariables[$name]) || is_object($this->OuterVariables[$name])) {
                                throw new \Exception("Unsupported outer variable type, only scalar types are supported.");
                            }
                            $value = $this->OuterVariables[$name];
                        }
                        $expression = Expression::parameter($name, gettype($value), $value);
                        $stack->push($expression);
                        break;
                    case 18 : // string (remove double quotes quotes & escaping slashes)
                        $value = $items[0]->getValue();
                        $value = preg_replace("%^\"+|\"+$|^\'+|\'+$%", "", $value);
                        $value = stripslashes($value);
                        $expression = Expression::constant($value, $items[0]->getName());
                        $stack->push($expression);
                        break;
                    case 17 : // number (convert string to number)
                        $value = $items[0]->getValue() + 0;
                        $expression = Expression::constant($value, $items[0]->getName());
                        $stack->push($expression);
                        break;
                    case 16 : // bool
                        $value = strtolower($items[0]->getValue()) == "true" ? true : false;
                        $expression = Expression::constant($value, $items[0]->getName());
                        $stack->push($expression);
                        break;
                    case 14 : // product
                    case 12 : // product
                    case 10 : // product
                    case 8 : // product
                    case 6 : // product
                    case 4 : // product
                        $right = $stack->pop();
                        $left = $stack->pop();
                        $expression = Expression::binary($items[1]->getValue(), $left, $right);
                        $stack->push($expression);
                        break;
                    case 2 : // lambda with parameters
                        $body = $stack->pop();
                        $expression = Expression::lambda($body, $list);
                        $stack->push($expression);
                        $list = [];
                        break;
                    case 1 : // lambda without parameters
                        $body = $stack->pop();
                        $expression = Expression::lambda($body);
                        $stack->push($expression);
                        break;
                }
            }
            
        } while ($this->Parser->action != Parser::ACCEPT && $this->Parser->action != Parser::ERROR);

        return $expression;
    }

    public function getParameters()
    {
        $parameters = [];
        foreach ($this->FunctionReflector->getParameters() as $paramReflector) {
            $parameterName = $paramReflector->getName();
            $parameterType = null;
            if ($paramReflector->getType()) {
                $parameterType = $paramReflector->getType()->getName();
            }
            $parameters[] = Expression::parameter($parameterName, $parameterType);
        }

        return $parameters;
    }

    public function getOuterVariables()
    {
        $outerVariables = [];
        $function = $this->FunctionReflector->getClosure();
        ob_start();
        var_dump($function);
        $buffer = ob_get_clean();
        $buffer = preg_replace('/\s|(bool|int)\((\w+|\d+)\)|(string|)\(\d+\)|:"\w+"|object\(Closure\)#\d\s\(\d\)\s\{\s+\["static"\]=>|\["parameter"\](.|\s)+/','\\2', $buffer);
        $buffer = preg_replace('/"(\w+)"(:\w+)/','"\\1\\2"', $buffer);
        $buffer = preg_replace('/object\(\w+\)#\d+|\["static"\]=>array/','(object)array', $buffer);
        $buffer = strtr($buffer, '"{}[]', "'(), ");
        $buffer = str_replace('(,', '(', $buffer);
        $buffer = '$outerVariables='.$buffer;
        eval($buffer.'?>');
        $this->OuterVariables = $outerVariables;

        return $outerVariables;
    }

}