<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Doctrine\Glob;

use Prophecy\PhpUnit\ProphecyTestCase;
use Doctrine\Glob\SelectorParser;

class SelectorParserTest extends ProphecyTestCase
{
    /**
     * @var SelectorParser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new SelectorParser();
    }

    public function provideParse()
    {
        return array(
            array(
                '/z*',
                array(
                    array('z*', SelectorParser::T_PATTERN | SelectorParser::T_LAST),
                ),
            ),
            array(
                '/',
                array(
                ),
            ),
            array(
                '/foo',
                array(
                    array('foo', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),
            array(
                '/foo/bar',
                array(
                    array('foo', SelectorParser::T_STATIC),
                    array('bar', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),
            array(
                '/*/bar',
                array(
                    array('*', SelectorParser::T_PATTERN),
                    array('bar', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),
            array(
                '/foo?/bar',
                array(
                    array('foo?', SelectorParser::T_PATTERN),
                    array('bar', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),
            array(
                '/foo?/bar/baz*',
                array(
                    array('foo?', SelectorParser::T_PATTERN),
                    array('bar', SelectorParser::T_STATIC),
                    array('baz*', SelectorParser::T_PATTERN | SelectorParser::T_LAST),
                ),
            ),
        );
    }

    /**
     * @dataProvider provideParse
     */
    public function testParse($path, $expected)
    {
        $res = $this->parser->parse($path);
        $this->assertSame($res, $expected);
    }
}
