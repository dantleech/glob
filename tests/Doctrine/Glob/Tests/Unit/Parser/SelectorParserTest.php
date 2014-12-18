<?php
/*
 * This file is part of the Glob package.
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Doctrine\Glob;

use Prophecy\PhpUnit\ProphecyTestCase;
use Doctrine\Glob\Parser\SelectorParser;

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
                '/\bar',
                array(
                    array('\bar', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),

            array(
                '/z*',
                array(
                    array('z*', SelectorParser::T_ASTERISK | SelectorParser::T_LAST),
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
                    array('*', SelectorParser::T_ASTERISK),
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
                    array('\\\\*', SelectorParser::T_ASTERISK),
                    array('booze', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),

            // non-escaped asterix ("\\*")
            array(
                '/\\\*/boo',
                array(
                    array('\\\\*', SelectorParser::T_ASTERISK),
                    array('boo', SelectorParser::T_STATIC | SelectorParser::T_LAST),
                ),
            ),

            // two non-espaped asterixes
            array(
                '/\\\*/boo/\\\*/boom',
                array(
                    array('\\\\*', SelectorParser::T_ASTERISK),
                    array('boo', SelectorParser::T_STATIC),
                    array('\\\\*', SelectorParser::T_ASTERISK),
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
