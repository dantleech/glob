<?php
/*
 * This file is part of the Glob package.
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Glob;

use DTL\Glob\GlobHelper;
use DTL\Glob\Parser\SelectorParser;

class GlobHelperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->helper = new GlobHelper();
    }

    public function provideIsGlobbed()
    {
        return array(
            array(
                '/hello', false
            ),
            array(
                '/hello/*', true
            ),
            array(
                '/hello/*/goodbye', true
            ),
        );
    }

    /**
     * @dataProvider provideIsGlobbed
     */
    public function testIsGlobbed($string, $expectedResult)
    {
        $result = $this->helper->isGlobbed($string);
        $this->assertEquals($expectedResult, $result);
    }
}
