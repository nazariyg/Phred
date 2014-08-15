<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class of the HyperText Markup Language, inheriting from the CXml class.
 */

// Method signatures:
//   static CUStringObject prependNewlinesWithBr ($sString)
//   static CUStringObject stripTags ($sString)

class CHtml extends CXml
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Prepends every newline in a string with an HTML line break tag and returns the resulting string.
     *
     * @param  string $sString The input string.
     *
     * @return CUStringObject The resulting string.
     */

    public static function prependNewlinesWithBr ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return nl2br($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Strips HTML and PHP tags from a string and returns the resulting string.
     *
     * @param  string $sString The input string.
     *
     * @return CUStringObject The resulting string.
     */

    public static function stripTags ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return strip_tags($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
