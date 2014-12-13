<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
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
