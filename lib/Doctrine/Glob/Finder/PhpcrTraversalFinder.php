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

use PHPCR\SessionInterface;

/**
 * PHPCR finder which users traversal.
 *
 * Supports single-star matching on path elements.
 * Currently does not support the double-star syntax
 * for "deep" recursing.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class PhpcrTraversalFinder extends AbstractTraversalFinder
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     * @param SelectorParser $parser
     */
    public function __construct(SessionInterface $session, SelectorParser $parser = null)
    {
        parent::__construct($parser);
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    protected function getNode(array $pathSegments)
    {
        $absPath = '/' . implode('/', $pathSegments);

        try {
            $node = $this->session->getNode($absPath);
        } catch (\PHPCR\PathNotFoundException $e) {
            $node = null;
        }

        return $node;
    }

    /**
     * {@inheritDoc}
     */
    protected function getChildren($parentNode, $selector)
    {
        return $parentNode->getNodes($selector);
    }
}
