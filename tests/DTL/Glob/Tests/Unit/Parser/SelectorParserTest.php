<?php
/*
 * This file is part of the Glob package.
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Glob\Parser;

use DTL\Glob\Parser\SelectorParser;

class SelectorParserTest extends \PHPUnit_Framework_TestCase
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
                '/\bar',
                array(
                    array('\bar', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),

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
                '/\*/bar',
                array(
                    array('*', SelectorParser::T_STATIC),
                    array('bar', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),

            // literal asterix "\\\*" should be "\\*"
            array(
                '/\\\\\*/boo',
                array(
                    array('\\\\*', SelectorParser::T_STATIC),
                    array('boo', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),

            // one literal asterix and a non-espaped asterix
            array(
                '/\\\\\*/boo/\\\\*/booze',
                array(
                    array('\\\\*', SelectorParser::T_STATIC),
                    array('boo', SelectorParser::T_STATIC),
                    array('\\\\*', SelectorParser::T_PATTERN),
                    array('booze', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),

            // non-escaped asterix ("\\*")
            array(
                '/\\\*/boo',
                array(
                    array('\\\\*', SelectorParser::T_PATTERN),
                    array('boo', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),

            // two non-espaped asterixes
            array(
                '/\\\*/boo/\\\*/boom',
                array(
                    array('\\\\*', SelectorParser::T_PATTERN),
                    array('boo', SelectorParser::T_STATIC),
                    array('\\\\*', SelectorParser::T_PATTERN),
                    array('boom', SelectorParser::T_STATIC | SelectorParser::T_LAST),
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
