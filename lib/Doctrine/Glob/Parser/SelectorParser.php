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

namespace Doctrine\Glob\Parser;

/**
 * Parser for selector patterns
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class SelectorParser
{
    const T_STATIC = 1;
    const T_PATTERN = 2;
    const T_LAST = 4;

    /**
     * Parse the given selector.
     *
     * Returns an associative array of path elements to bitmasks.
     *
     * @return array
     */
    public function parse($selector)
    {
        if ('/' === $selector) {
            return array();
        }

        if ('/' !== substr($selector, 0, 1)) {
            throw new \InvalidArgumentException(sprintf(
                'Path "%s" must be absolute',
                $selector
            ));
        }

        $selector = substr($selector, 1);

        $segments = array();
        $elements = explode('/', $selector);

        foreach ($elements as $index => $element) {
            if ($this->containsWildcard($element)) {
                $flags = self::T_PATTERN;
            } else {
                $flags = self::T_STATIC;
            }

            if ($index === (count($elements) - 1)) {
                $flags = $flags | self::T_LAST;
            }

            $segments[] = array(stripslashes($element), $flags);
        }

        return $segments;
    }

    /**
     * Check to see if the string contains an unescaped wildcard
     *
     * @param string
     *
     * @return boolean
     */
    private function containsWildcard($string)
    {
        if (false === $strpos = strpos($string, '*')) {
            return false;
        }

        $escapeChars = 0;
        while (isset($string[--$strpos]) && $string[$strpos] === '\\') {
            $escapeChars++;
        }

        $isNotEscaped = $escapeChars % 2 === 0;

        return $isNotEscaped;
    }
}
