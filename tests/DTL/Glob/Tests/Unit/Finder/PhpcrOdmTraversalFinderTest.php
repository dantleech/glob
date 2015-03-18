<?php
/*
 * This file is part of the Glob package.
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Glob\Finder;

use Prophecy\PhpUnit\ProphecyTestCase;
use DTL\Glob\Parser\SelectorParser;
use DTL\Glob\Finder\PhpcrOdmTraversalFinder;

class PhpcrOdmTraversalFinderTest extends \PHPUnit_Framework_TestCase
{
    private $finder;
    private $manager;
    private $managerRegistry;
    private $document;

    public function setUp()
    {
        $this->managerRegistry = $this->prophesize('Doctrine\Bundle\PHPCRBundle\ManagerRegistry');
        $this->manager = $this->prophesize('Doctrine\ODM\PHPCR\DocumentManager');
        $this->finder = new PhpcrOdmTraversalFinder($this->managerRegistry->reveal());
        $this->document = new \stdClass;
    }

    public function testFind()
    {
        $this->managerRegistry->getManager()->willReturn($this->manager->reveal());
        $this->manager->find(null, '/foo')->willReturn($this->document);
        $this->manager->getChildren($this->document, '*')->willReturn(array());
        $res = $this->finder->find('/foo/*');
    }
}

