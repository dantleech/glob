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

use PHPCR\NodeInterface;
use DTL\Glob\FinderInterface;
use DTL\Glob\Parser\SelectorParser;

/**
 * Abstract finder implementation for traversing hierachical
 * object structures.
 *
 * Supports single-star matching on path elements.
 * Currently does not support the double-star syntax
 * for "deep" recursing.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
abstract class AbstractTraversalFinder implements FinderInterface
{
    /**
     * @var SelectorParser
     */
    private $parser;

    /**
     * @param SelectorParser $parser
     */
    public function __construct(SelectorParser $parser = null)
    {
        $this->parser = $parser ? : new SelectorParser();
    }

    /**
     * {@inheritDoc}
     */
    public function find($selector)
    {
        if ($selector == '/') {
            return array($this->getNode(array()));
        }

        $segments = $this->parser->parse($selector);

        $result = array();
        $this->traverse($segments, $result);

        return $result;
    }

    /**
     * Traverse the node
     *
     * @param NodeInterface|null $node  The node to traverse, if it exists yet
     * @param array $segments  The element => token stack
     * @param array $result  The result
     *
     * @return null
     */
    private function traverse(array $segments, &$result = array(), $node = null)
    {
        $path = array();

        if (null !== $node) {
            $path = explode('/', substr($node->getPath(), 1));
        }

        do {
            list($element, $bitmask) = array_shift($segments);

            if ($bitmask & SelectorParser::T_STATIC) {
                $path[] = $element;

                if ($bitmask & SelectorParser::T_LAST) {
                    if ($node = $this->getNode($path)) {
                        $result[] = $node;
                        break;
                    }
                }
            }

            if ($bitmask & SelectorParser::T_PATTERN) {
                if (null === $parentNode = $this->getNode($path)) {
                    return;
                }

                $children = $this->getChildren($parentNode, $element);

                foreach ($children as $child) {
                    if ($bitmask & SelectorParser::T_LAST) {
                        $result[] = $child;
                    } else {
                        $this->traverse($segments, $result, $child);
                    }
                }

                return;
            }
        } while ($segments);
    }

    /**
     * Return nodes for given path
     * The path is given as an array of path segments
     *
     * @param string[] Path segments
     *
     * @return mixed The node matching the given path
     */
    abstract protected function getNode(array $pathSegments);

    /**
     * Return children of given node matching the given selector
     *
     * @return mixed[] Array of nodes
     */
    abstract protected function getChildren($parentNode, $selector);
}
