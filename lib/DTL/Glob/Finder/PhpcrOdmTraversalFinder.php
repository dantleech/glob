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
        return $this->getManager()->find(null, $absPath);
    }

    /**
     * {@inheritDoc}
     */
    protected function getChildren($parentNode, $selector)
    {
        return $this->getManager()->getChildren($parentNode, $selector);
    }
}
