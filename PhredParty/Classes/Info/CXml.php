<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class of the Extensible Markup Language.
 */

// Method signatures:
//   static CUStringObject enterTd ($string, $translateExisting = true)
//   static CUStringObject leaveTd ($string)
//   static CUStringObject enterTdAll ($string, $translateExisting = true)
//   static CUStringObject leaveTdAll ($string)

class CXml extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Translates a string into the XML text domain, replacing only those characters with equivalent XML entities that
     * cannot be represented literally in XML.
     *
     * Both double and single quotes are translated.
     *
     * @param  string $string The string to be translated.
     * @param  bool $translateExisting **OPTIONAL. Default is** `true`. Tells whether the applicable characters of any
     * XML entities that are already contained in the input string should be translated so that those entities do not
     * preserve their XML meanings.
     *
     * @return CUStringObject The translated string.
     */

    public static function enterTd ($string, $translateExisting = true)
    {
        assert( 'is_cstring($string) && is_bool($translateExisting)', vs(isset($this), get_defined_vars()) );
        return htmlspecialchars($string, ENT_QUOTES, "UTF-8", $translateExisting);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Translates a string out of the XML text domain, replacing only those XML entities with their literal
     * equivalents that cannot be represented literally in XML.
     *
     * Both double and single quotes are translated.
     *
     * @param  string $string The string to be translated.
     *
     * @return CUStringObject The translated string.
     */

    public static function leaveTd ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return htmlspecialchars_decode($string, ENT_QUOTES);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Translates a string into the XML text domain, replacing all characters with equivalent XML entities for which
     * such entities exist in XML.
     *
     * Both double and single quotes are translated.
     *
     * @param  string $string The string to be translated.
     * @param  bool $translateExisting **OPTIONAL. Default is** `true`. Tells whether the applicable characters of any
     * XML entities that are already contained in the input string should be translated so that those entities do not
     * preserve their XML meanings.
     *
     * @return CUStringObject The translated string.
     */

    public static function enterTdAll ($string, $translateExisting = true)
    {
        assert( 'is_cstring($string) && is_bool($translateExisting)', vs(isset($this), get_defined_vars()) );
        return htmlentities($string, ENT_QUOTES, "UTF-8", $translateExisting);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Translates a string out of the XML text domain, replacing all XML entities with their literal equivalents.
     *
     * Both double and single quotes are translated.
     *
     * @param  string $string The string to be translated.
     *
     * @return CUStringObject The translated string.
     */

    public static function leaveTdAll ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return html_entity_decode($string, ENT_QUOTES, "UTF-8");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
