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

namespace Doctrine\Glob\Finder;

use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * PHPCR ODM finder which users traversal.
 *
 * {@inheritDoc}
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class PhpcrOdmTraversalFinder extends AbstractTraversalFinder
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @param SessionInterface $managerRegistry
     * @param SelectorParser $parser
     */
    public function __construct(ManagerRegistry $managerRegistry, SelectorParser $parser = null)
    {
        parent::__construct($parser);
        $this->managerRegistry = $managerRegistry;
    }

    private function getManager()
    {
        return $this->managerRegistry->getManager();
    }

    /**
     * {@inheritDoc}
     */
    protected function getNode(array $pathSegments)
    {
        $absPath = '/' . implode('/', $pathSegments);
        $document = $this->getManager()->find(null, $absPath);

        return $document;
    }

    /**
     * {@inheritDoc}
     */
    protected function getChildren($parentNode, $selector)
    {
        return $this->getManager()->getChildren($parentNode, $selector);
    }
}
