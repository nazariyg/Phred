<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A collection of static methods that let you express yourself regularly and get answered in the neatest way possible.
 *
 * **In the OOP mode, you would likely never need to use this class.**
 *
 * **You can refer to this class by its alias, which is** `Re`.
 */

// Method signatures:
//   static int indexOf ($string, $ofPattern, $startPos = 0, &$foundString = null)
//   static int lastIndexOf ($string, $ofPattern, $startPos = 0, &$foundString = null)
//   static bool find ($string, $findPattern, &$foundString = null)
//   static bool findFrom ($string, $findPattern, $startPos, &$foundString = null)
//   static bool findGroups ($string, $findPattern, &$foundGroups, &$foundString = null)
//   static bool findGroupsFrom ($string, $findPattern, $startPos, &$foundGroups, &$foundString = null)
//   static int findAll ($string, $findPattern, &$foundStrings = null)
//   static int findAllFrom ($string, $findPattern, $startPos, &$foundStrings = null)
//   static int findAllGroups ($string, $findPattern, &$foundGroupArrays, &$foundStrings = null)
//   static int findAllGroupsFrom ($string, $findPattern, $startPos, &$foundGroupArrays, &$foundStrings = null)
//   static string replace ($string, $whatPattern, $with, &$quantity = null)
//   static string replaceWithCallback ($string, $whatPattern, $callback, &$quantity = null)
//   static string remove ($string, $whatPattern, &$quantity = null)
//   static CArray split ($string, $delimiterPatternOrPatterns)
//   static string enterTd ($string, $delimiter = self::DEFAULT_PATTERN_DELIMITER)

class CRegex extends CRootClass
{
    /**
     * `string` The default pattern delimiter that is supposed to be used in regular expressions.
     *
     * @var string
     */
    const DEFAULT_PATTERN_DELIMITER = "/";

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a pattern in a string, optionally reporting the substring that
     * matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofPattern The searched pattern.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     * @param  reference $foundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return int The position of the first occurrence of the pattern in the string or `-1` if no such pattern was
     * found.
     */

    public static function indexOf ($string, $ofPattern, $startPos = 0, &$foundString = null)
    {
        assert( 'is_cstring($string) && is_cstring($ofPattern) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        $matches;
        $res = preg_match($ofPattern, $string, $matches, PREG_OFFSET_CAPTURE, $startPos);
        if ( $res === 1 )
        {
            $foundString = $matches[0][0];
            return $matches[0][1];
        }
        else
        {
            return -1;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a pattern in a string, optionally reporting the substring that
     * matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofPattern The searched pattern.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     * @param  reference $foundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the last substring that
     * matched the pattern.
     *
     * @return int The position of the last occurrence of the pattern in the string or `-1` if no such pattern was
     * found.
     */

    public static function lastIndexOf ($string, $ofPattern, $startPos = 0, &$foundString = null)
    {
        assert( 'is_cstring($string) && is_cstring($ofPattern) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        $matches;
        $res = preg_match_all($ofPattern, $string, $matches, PREG_OFFSET_CAPTURE, $startPos);
        if ( is_int($res) && $res > 0 )
        {
            $lastIdx = count($matches[0]) - 1;
            $foundString = $matches[0][$lastIdx][0];
            return $matches[0][$lastIdx][1];
        }
        else
        {
            return -1;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified pattern, optionally reporting the substring that matched the
     * pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $findPattern The searched pattern.
     * @param  reference $foundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public static function find ($string, $findPattern, &$foundString = null)
    {
        assert( 'is_cstring($string) && is_cstring($findPattern)', vs(isset($this), get_defined_vars()) );

        if ( func_num_args() == 2 )
        {
            // Try not to waste memory on matched text.
            return ( preg_match($findPattern, $string) === 1 );
        }
        else
        {
            $matches;
            $res = preg_match($findPattern, $string, $matches);
            if ( $res === 1 )
            {
                $foundString = $matches[0];
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified pattern, starting the search from a specified position and
     * optionally reporting the substring that matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $findPattern The searched pattern.
     * @param  int $startPos The starting position for the search.
     * @param  reference $foundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public static function findFrom ($string, $findPattern, $startPos, &$foundString = null)
    {
        assert( 'is_cstring($string) && is_cstring($findPattern) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        $matches;
        $res = preg_match($findPattern, $string, $matches, 0, $startPos);
        if ( $res === 1 )
        {
            $foundString = $matches[0];
            return true;
        }
        else
        {
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified pattern, putting every substring that matches a regular expression
     * group into an array for output and optionally reporting the substring that matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $findPattern The searched pattern, usually with groups.
     * @param  reference $foundGroups **OUTPUT.** If the pattern was found, this is an array of type `CArray`
     * containing the substrings that matched the groups in the pattern, in the same order, if any.
     * @param  reference $foundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public static function findGroups ($string, $findPattern, &$foundGroups, &$foundString = null)
    {
        assert( 'is_cstring($string) && is_cstring($findPattern)', vs(isset($this), get_defined_vars()) );

        $matches;
        $res = preg_match($findPattern, $string, $matches);
        if ( $res === 1 )
        {
            $foundGroups = CArray::make(count($matches) - 1);
            for ($i0 = 1, $i1 = 0; $i0 < count($matches); $i0++, $i1++)
            {
                $foundGroups[$i1] = $matches[$i0];
            }
            $foundString = $matches[0];
            return true;
        }
        else
        {
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified pattern, starting the search from a specified position and putting
     * every substring that matches a regular expression group into an array for output, optionally reporting the
     * substring that matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $findPattern The searched pattern, usually with groups.
     * @param  int $startPos The starting position for the search.
     * @param  reference $foundGroups **OUTPUT.** If the pattern was found, this is an array of type `CArray`
     * containing the substrings that matched the groups in the pattern, in the same order, if any.
     * @param  reference $foundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public static function findGroupsFrom ($string, $findPattern, $startPos, &$foundGroups, &$foundString = null)
    {
        assert( 'is_cstring($string) && is_cstring($findPattern) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        $matches;
        $res = preg_match($findPattern, $string, $matches, 0, $startPos);
        if ( $res === 1 )
        {
            $foundGroups = CArray::make(count($matches) - 1);
            for ($i0 = 1, $i1 = 0; $i0 < count($matches); $i0++, $i1++)
            {
                $foundGroups[$i1] = $matches[$i0];
            }
            $foundString = $matches[0];
            return true;
        }
        else
        {
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many matches of a specified pattern there are in a string, optionally reporting the substrings that
     * matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $findPattern The searched pattern.
     * @param  reference $foundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArray` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public static function findAll ($string, $findPattern, &$foundStrings = null)
    {
        assert( 'is_cstring($string) && is_cstring($findPattern)', vs(isset($this), get_defined_vars()) );

        if ( func_num_args() == 2 )
        {
            // Try not to waste memory on matched text.
            $res = preg_match_all($findPattern, $string);
            return ( is_int($res) ) ? $res : 0;
        }
        else
        {
            $matches;
            $res = preg_match_all($findPattern, $string, $matches);
            if ( is_int($res) && $res > 0 )
            {
                $foundStrings = CArray::make($res);
                for ($i = 0; $i < $res; $i++)
                {
                    $foundStrings[$i] = $matches[0][$i];
                }
                return $res;
            }
            else
            {
                return 0;
            }
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many matches of a specified pattern there are in a string, starting the search from a specified
     * position and optionally reporting the substrings that matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $findPattern The searched pattern.
     * @param  int $startPos The starting position for the search.
     * @param  reference $foundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArray` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public static function findAllFrom ($string, $findPattern, $startPos, &$foundStrings = null)
    {
        assert( 'is_cstring($string) && is_cstring($findPattern) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        $matches;
        $res = preg_match_all($findPattern, $string, $matches, PREG_PATTERN_ORDER, $startPos);
        if ( is_int($res) && $res > 0 )
        {
            $foundStrings = CArray::make($res);
            for ($i = 0; $i < $res; $i++)
            {
                $foundStrings[$i] = $matches[0][$i];
            }
            return $res;
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many matches of a specified pattern there are in a string, putting every substring that matches a
     * regular expression group into an array for output so that each match of the pattern is associated with an array
     * of group matches and optionally reporting the substrings that matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $findPattern The searched pattern, usually with groups.
     * @param  reference $foundGroupArrays **OUTPUT.** If any patterns were found, this is a two-dimensional array of
     * type `CArray`, per each pattern match containing an array of the substrings that matched the groups in the
     * pattern, in the same order, if any.
     * @param  reference $foundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArray` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public static function findAllGroups ($string, $findPattern, &$foundGroupArrays, &$foundStrings = null)
    {
        assert( 'is_cstring($string) && is_cstring($findPattern)', vs(isset($this), get_defined_vars()) );

        $matches;
        $res = preg_match_all($findPattern, $string, $matches, PREG_SET_ORDER);
        if ( is_int($res) && $res > 0 )
        {
            $foundGroupArrays = CArray::make($res);
            $foundStrings = CArray::make($res);
            for ($i0 = 0; $i0 < $res; $i0++)
            {
                $qty = count($matches[$i0]);
                $foundGroupArrays[$i0] = CArray::make($qty - 1);
                for ($i1 = 1; $i1 < $qty; $i1++)
                {
                    $foundGroupArrays[$i0][$i1 - 1] = $matches[$i0][$i1];
                }
                $foundStrings[$i0] = $matches[$i0][0];
            }
            return $res;
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many matches of a specified pattern there are in a string, starting the search from a specified
     * position and putting every substring that matches a regular expression group into an array for output so that
     * each match of the pattern is associated with an array of group matches, optionally reporting the substrings that
     * matched the pattern.
     *
     * @param  string $string The string to be looked into.
     * @param  string $findPattern The searched pattern, usually with groups.
     * @param  int $startPos The starting position for the search.
     * @param  reference $foundGroupArrays **OUTPUT.** If any patterns were found, this is a two-dimensional array of
     * type `CArray`, per each pattern match containing an array of the substrings that matched the groups in the
     * pattern, in the same order, if any.
     * @param  reference $foundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArray` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public static function findAllGroupsFrom ($string, $findPattern, $startPos, &$foundGroupArrays,
        &$foundStrings = null)
    {
        assert( 'is_cstring($string) && is_cstring($findPattern) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        $matches;
        $res = preg_match_all($findPattern, $string, $matches, PREG_SET_ORDER, $startPos);
        if ( is_int($res) && $res > 0 )
        {
            $foundGroupArrays = CArray::make($res);
            $foundStrings = CArray::make($res);
            for ($i0 = 0; $i0 < $res; $i0++)
            {
                $qty = count($matches[$i0]);
                $foundGroupArrays[$i0] = CArray::make($qty - 1);
                for ($i1 = 1; $i1 < $qty; $i1++)
                {
                    $foundGroupArrays[$i0][$i1 - 1] = $matches[$i0][$i1];
                }
                $foundStrings[$i0] = $matches[$i0][0];
            }
            return $res;
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a pattern in a string with a specified string and returns the new string, optionally
     * reporting the number of replacements made.
     *
     * @param  string $string The source string.
     * @param  string $whatPattern The pattern to be replaced.
     * @param  string $with The replacement string.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replace ($string, $whatPattern, $with, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($whatPattern) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );

        return preg_replace($whatPattern, $with, $string, -1, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any occurrence of a pattern in a string with the string returned by a function or method called on the
     * matching substring and returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $string The source string.
     * @param  string $whatPattern The pattern to be replaced.
     * @param  callable $callback A function or method that, as imposed by PHP's PCRE, takes a PHP's associative
     * array (CMap) as a parameter, which contains the matching substring under the key of `0`, the substring that
     * matched the first group, if any, under the key of `1`, and so on for groups, and returns the string with which
     * the matching substring should be replaced.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replaceWithCallback ($string, $whatPattern, $callback, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($whatPattern) && is_callable($callback)',
            vs(isset($this), get_defined_vars()) );

        return preg_replace_callback($whatPattern, $callback, $string, -1, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a pattern in a string and returns the new string, optionally reporting the number of
     * removals made.
     *
     * @param  string $string The source string.
     * @param  string $whatPattern The pattern to be removed.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return string The resulting string.
     */

    public static function remove ($string, $whatPattern, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($whatPattern)', vs(isset($this), get_defined_vars()) );
        return self::replace($string, $whatPattern, "", $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Splits a string into substrings using a specified pattern or patterns as the delimiter(s) and returns the
     * resulting strings as an array.
     *
     * If no delimiter patterns were found, the resulting array contains just one element, which is the original
     * string. If a delimiter is located at the very start or at the very end of the string or next to another
     * delimiter, it will accordingly cause some string(s) in the resulting array to be empty.
     *
     * As a special case, the delimiter pattern can be empty, which will split the string into its constituting
     * characters.
     *
     * @param  string $string The string to be split.
     * @param  string|array|map $delimiterPatternOrPatterns The pattern or array of patterns to be recognized as the
     * delimiter(s).
     *
     * @return CArray The resulting strings.
     */

    public static function split ($string, $delimiterPatternOrPatterns)
    {
        assert( 'is_cstring($string) && (is_cstring($delimiterPatternOrPatterns) || ' .
                'is_collection($delimiterPatternOrPatterns))', vs(isset($this), get_defined_vars()) );

        if ( is_cstring($delimiterPatternOrPatterns) )
        {
            $numIdt = self::findGroups($delimiterPatternOrPatterns, "/^([^0-9A-Za-z\\s\\\\])(.*)\\1/", $foundGroups);
            assert( '$numIdt == 2', vs(isset($this), get_defined_vars()) );
            $idt = $foundGroups[1];
            if ( CString::isEmpty($idt) )
            {
                // Special case.
                if ( CString::isEmpty($string) )
                {
                    $resStrings = CArray::fromElements("");
                    return $resStrings;
                }
                else
                {
                    if ( preg_match(
                            "/^([^0-9A-Za-z\\s\\\\])\\1[A-Za-z]*u[A-Za-z]*\\z/", $delimiterPatternOrPatterns) !== 1 )
                    {
                        $resStrings = CArray::make(strlen($string));
                        for ($i = 0; $i < strlen($string); $i++)
                        {
                            $resStrings[$i] = $string[$i];
                        }
                        return $resStrings;
                    }
                    else
                    {
                        return CUString::splitIntoChars($string);
                    }
                }
            }

            $paResStrings = preg_split($delimiterPatternOrPatterns, $string);
            $qty = count($paResStrings);
            $resStrings = CArray::make($qty);
            for ($i = 0; $i < $qty; $i++)
            {
                $resStrings[$i] = $paResStrings[$i];
            }
            return $resStrings;
        }
        else  // a collection
        {
            $resStrings = CArray::fromElements($string);
            foreach ($delimiterPatternOrPatterns as $delimiterPattern)
            {
                assert( 'is_cstring($delimiterPattern)', vs(isset($this), get_defined_vars()) );
                $resStringsNew = CArray::make();
                $len = CArray::length($resStrings);
                for ($i = 0; $i < $len; $i++)
                {
                    CArray::pushArray($resStringsNew, self::split($resStrings[$i], $delimiterPattern));
                }
                $resStrings = $resStringsNew;
            }
            return $resStrings;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, escapes all characters that have a special meaning in the regular expression domain, and returns
     * the escaped string.
     *
     * With this method, you can prepare an arbitrary string to be used as a part of a regular expression.
     *
     * @param  string $string The string to be escaped.
     * @param  string $delimiter **OPTIONAL. Default is** "/". The pattern delimiter that is going to be used by the
     * resulting regular expression and therefore needs to be escaped as well.
     *
     * @return string The escaped string.
     */

    public static function enterTd ($string, $delimiter = self::DEFAULT_PATTERN_DELIMITER)
    {
        assert( 'is_cstring($string) && is_cstring($delimiter)', vs(isset($this), get_defined_vars()) );
        return preg_quote($string, $delimiter);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
