<?php
/*
 * This file is part of the Glob package.
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Glob\Tests\Functional\Finder;

use Jackalope\RepositoryFactoryFilesystem;
use PHPCR\SimpleCredentials;
use DTL\Glob\Parser\SelectorParser;
use Symfony\Component\Filesystem\Filesystem;
use DTL\Glob\Finder\PhpcrTraversalFinder;

class PhpcrTraversalFinderTest extends \PHPUnit_Framework_TestCase
{
    private $session;

    public function setUp()
    {
        $path = __DIR__ . '/../../../../../data';

        $sfFs = new Filesystem();
        $sfFs->remove($path);
        $factory = new RepositoryFactoryFilesystem();
        $repository = $factory->getRepository(array(
            'path' => $path,
            'search.enabled' => false,
        ));
        $credentials = new SimpleCredentials('admin', 'admin');
        $this->session = $repository->login($credentials);

        $parser = new SelectorParser();
        $this->finder = new PhpcrTraversalFinder($this->session);
    }

    public function provideFind()
    {
        return array(
            array(
                '/foo/*/*',
                array(
                    '/foo/foo/baz',
                ),
            ),
            array(
                '/foo/*/*',
                array(
                    '/foo/foo/baz',
                ),
            ),
            array(
                '/*/foo/*',
                array(
                    '/foo/foo/baz',
                    '/bar/foo/baz',
                ),
            ),
            array(
                '/*/bar',
                array(
                    '/foo/bar',
                    '/bar/bar',
                ),
            ),
            array(
                '/foo/*',
                array(
                    '/foo/foo',
                    '/foo/bar',
                ),
            ),
            array(
                '/',
                array(
                    '/',
                ),
            ),
            array(
                '/foo',
                array(
                    '/foo',
                ),
            ),
            array(
                '/foo/bar',
                array(
                    '/foo/bar'
                ),
            ),
            array(
                '/*/*',
                array(
                    '/bar/foo',
                    '/bar/bar',
                    '/foo/foo',
                    '/foo/bar',
                ),
            ),
            array(
                '/z*',
                array(
                    '/zzz'
                ),
            ),
            array(
                '/fo*/*',
                array(
                    '/foo/foo',
                    '/foo/bar',
                ),
            ),
            array(
                '/fo*/*oo',
                array(
                    '/foo/foo',
                ),
            ),
        );
    }

    /**
     * @dataProvider provideFind
     */
    public function testFind($path, $expected)
    {
        $this->loadFixtures();
        $nodes = $this->finder->find($path);

        $paths = array();
        foreach ($nodes as $node) {
            $paths[] = $node->getPath();
        }

        $this->assertCount(count($expected), $paths);
        foreach ($expected as $expectedPath) {
            $this->assertContains($expectedPath, $paths);
        }
    }

    private function loadFixtures()
    {
        $rootNode = $this->session->getRootNode();
        $node1 = $rootNode->addNode('foo');
        $node1->addNode('bar');
        $node4 = $node1->addNode('foo');
        $node4->addNode('baz');
        $node2 = $rootNode->addNode('bar');
        $node2->addNode('bar');
        $node3 = $node2->addNode('foo');
        $node3->addNode('baz');
        $rootNode->addNode('zzz');
        $this->session->save();
    }
}
