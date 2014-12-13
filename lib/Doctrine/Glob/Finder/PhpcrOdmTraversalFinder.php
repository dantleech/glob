<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
