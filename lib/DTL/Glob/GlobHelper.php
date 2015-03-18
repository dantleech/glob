<?php

namespace DTL\Glob;

use DTL\Glob\Parser\SelectorParser;

/**
 * Glob helper
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class GlobHelper
{
    private $parser;

    /**
     * @param GlobParser $parser
     */
    public function __construct(SelectorParser $parser = null)
    {
        $this->parser = $parser ? : new SelectorParser();
    }

    /**
     * Return true if the given string is contains a glob pattern
     *
     * @param string $string
     *
     * @return boolean
     */
    public function isGlobbed($string)
    {
        $segments = $this->parser->parse($string);
        foreach ($segments as $segment) {
            // if bitmask contains pattern
            if ($segment[1] & SelectorParser::T_PATTERN) {
                return true;
            }
        }

        return false;
    }
}
