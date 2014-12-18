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

/**
 * Interface for glob finders
 */
interface FinderInterface
{
    /**
     * Locate a collection of resources from the
     * given locator.
     *
     * @see Puli\Repository\ResourceRepositoryInterface#find
     *
     * @param string $selector
     * @return ResourceCollectionInterface
     */
    public function find($selector);
}
