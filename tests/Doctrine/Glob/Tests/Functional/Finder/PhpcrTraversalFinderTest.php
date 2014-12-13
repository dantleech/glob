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

namespace Doctrine\Glob\Tests\Functional\Finder;

use Jackalope\RepositoryFactoryFilesystem;
use PHPCR\SimpleCredentials;
use Doctrine\Glob\Parser\SelectorParser;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Glob\Finder\PhpcrTraversalFinder;

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
