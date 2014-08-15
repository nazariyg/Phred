<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
//   static int indexOf ($sString, $sOfPattern, $iStartPos = 0, &$rsFoundString = null)
//   static int lastIndexOf ($sString, $sOfPattern, $iStartPos = 0, &$rsFoundString = null)
//   static bool find ($sString, $sFindPattern, &$rsFoundString = null)
//   static bool findFrom ($sString, $sFindPattern, $iStartPos, &$rsFoundString = null)
//   static bool findGroups ($sString, $sFindPattern, &$raFoundGroups, &$rsFoundString = null)
//   static bool findGroupsFrom ($sString, $sFindPattern, $iStartPos, &$raFoundGroups, &$rsFoundString = null)
//   static int findAll ($sString, $sFindPattern, &$raFoundStrings = null)
//   static int findAllFrom ($sString, $sFindPattern, $iStartPos, &$raFoundStrings = null)
//   static int findAllGroups ($sString, $sFindPattern, &$raFoundGroupArrays, &$raFoundStrings = null)
//   static int findAllGroupsFrom ($sString, $sFindPattern, $iStartPos, &$raFoundGroupArrays, &$raFoundStrings = null)
//   static string replace ($sString, $sWhatPattern, $sWith, &$riQuantity = null)
//   static string replaceWithCallback ($sString, $sWhatPattern, $fnCallback, &$riQuantity = null)
//   static string remove ($sString, $sWhatPattern, &$riQuantity = null)
//   static CArray split ($sString, $xDelimiterPatternOrPatterns)
//   static string enterTd ($sString, $sDelimiter = self::DEFAULT_PATTERN_DELIMITER)

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
     * @param  string $sString The string to be looked into.
     * @param  string $sOfPattern The searched pattern.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return int The position of the first occurrence of the pattern in the string or `-1` if no such pattern was
     * found.
     */

    public static function indexOf ($sString, $sOfPattern, $iStartPos = 0, &$rsFoundString = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sOfPattern) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        $mMatches;
        $xRes = preg_match($sOfPattern, $sString, $mMatches, PREG_OFFSET_CAPTURE, $iStartPos);
        if ( $xRes === 1 )
        {
            $rsFoundString = $mMatches[0][0];
            return $mMatches[0][1];
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
     * @param  string $sString The string to be looked into.
     * @param  string $sOfPattern The searched pattern.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the last substring that
     * matched the pattern.
     *
     * @return int The position of the last occurrence of the pattern in the string or `-1` if no such pattern was
     * found.
     */

    public static function lastIndexOf ($sString, $sOfPattern, $iStartPos = 0, &$rsFoundString = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sOfPattern) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        $mMatches;
        $xRes = preg_match_all($sOfPattern, $sString, $mMatches, PREG_OFFSET_CAPTURE, $iStartPos);
        if ( is_int($xRes) && $xRes > 0 )
        {
            $iLastIdx = count($mMatches[0]) - 1;
            $rsFoundString = $mMatches[0][$iLastIdx][0];
            return $mMatches[0][$iLastIdx][1];
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
     * @param  string $sString The string to be looked into.
     * @param  string $sFindPattern The searched pattern.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public static function find ($sString, $sFindPattern, &$rsFoundString = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sFindPattern)', vs(isset($this), get_defined_vars()) );

        if ( func_num_args() == 2 )
        {
            // Try not to waste memory on matched text.
            return ( preg_match($sFindPattern, $sString) === 1 );
        }
        else
        {
            $mMatches;
            $xRes = preg_match($sFindPattern, $sString, $mMatches);
            if ( $xRes === 1 )
            {
                $rsFoundString = $mMatches[0];
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
     * @param  string $sString The string to be looked into.
     * @param  string $sFindPattern The searched pattern.
     * @param  int $iStartPos The starting position for the search.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public static function findFrom ($sString, $sFindPattern, $iStartPos, &$rsFoundString = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sFindPattern) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        $mMatches;
        $xRes = preg_match($sFindPattern, $sString, $mMatches, 0, $iStartPos);
        if ( $xRes === 1 )
        {
            $rsFoundString = $mMatches[0];
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
     * @param  string $sString The string to be looked into.
     * @param  string $sFindPattern The searched pattern, usually with groups.
     * @param  reference $raFoundGroups **OUTPUT.** If the pattern was found, this is an array of type `CArray`
     * containing the substrings that matched the groups in the pattern, in the same order, if any.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public static function findGroups ($sString, $sFindPattern, &$raFoundGroups, &$rsFoundString = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sFindPattern)', vs(isset($this), get_defined_vars()) );

        $mMatches;
        $xRes = preg_match($sFindPattern, $sString, $mMatches);
        if ( $xRes === 1 )
        {
            $raFoundGroups = CArray::make(count($mMatches) - 1);
            for ($i0 = 1, $i1 = 0; $i0 < count($mMatches); $i0++, $i1++)
            {
                $raFoundGroups[$i1] = $mMatches[$i0];
            }
            $rsFoundString = $mMatches[0];
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
     * @param  string $sString The string to be looked into.
     * @param  string $sFindPattern The searched pattern, usually with groups.
     * @param  int $iStartPos The starting position for the search.
     * @param  reference $raFoundGroups **OUTPUT.** If the pattern was found, this is an array of type `CArray`
     * containing the substrings that matched the groups in the pattern, in the same order, if any.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `string`, is the first substring that
     * matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public static function findGroupsFrom ($sString, $sFindPattern, $iStartPos, &$raFoundGroups, &$rsFoundString = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sFindPattern) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        $mMatches;
        $xRes = preg_match($sFindPattern, $sString, $mMatches, 0, $iStartPos);
        if ( $xRes === 1 )
        {
            $raFoundGroups = CArray::make(count($mMatches) - 1);
            for ($i0 = 1, $i1 = 0; $i0 < count($mMatches); $i0++, $i1++)
            {
                $raFoundGroups[$i1] = $mMatches[$i0];
            }
            $rsFoundString = $mMatches[0];
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
     * @param  string $sString The string to be looked into.
     * @param  string $sFindPattern The searched pattern.
     * @param  reference $raFoundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArray` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public static function findAll ($sString, $sFindPattern, &$raFoundStrings = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sFindPattern)', vs(isset($this), get_defined_vars()) );

        if ( func_num_args() == 2 )
        {
            // Try not to waste memory on matched text.
            $xRes = preg_match_all($sFindPattern, $sString);
            return ( is_int($xRes) ) ? $xRes : 0;
        }
        else
        {
            $mMatches;
            $xRes = preg_match_all($sFindPattern, $sString, $mMatches);
            if ( is_int($xRes) && $xRes > 0 )
            {
                $raFoundStrings = CArray::make($xRes);
                for ($i = 0; $i < $xRes; $i++)
                {
                    $raFoundStrings[$i] = $mMatches[0][$i];
                }
                return $xRes;
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
     * @param  string $sString The string to be looked into.
     * @param  string $sFindPattern The searched pattern.
     * @param  int $iStartPos The starting position for the search.
     * @param  reference $raFoundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArray` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public static function findAllFrom ($sString, $sFindPattern, $iStartPos, &$raFoundStrings = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sFindPattern) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        $mMatches;
        $xRes = preg_match_all($sFindPattern, $sString, $mMatches, PREG_PATTERN_ORDER, $iStartPos);
        if ( is_int($xRes) && $xRes > 0 )
        {
            $raFoundStrings = CArray::make($xRes);
            for ($i = 0; $i < $xRes; $i++)
            {
                $raFoundStrings[$i] = $mMatches[0][$i];
            }
            return $xRes;
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
     * @param  string $sString The string to be looked into.
     * @param  string $sFindPattern The searched pattern, usually with groups.
     * @param  reference $raFoundGroupArrays **OUTPUT.** If any patterns were found, this is a two-dimensional array of
     * type `CArray`, per each pattern match containing an array of the substrings that matched the groups in the
     * pattern, in the same order, if any.
     * @param  reference $raFoundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArray` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public static function findAllGroups ($sString, $sFindPattern, &$raFoundGroupArrays, &$raFoundStrings = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sFindPattern)', vs(isset($this), get_defined_vars()) );

        $mMatches;
        $xRes = preg_match_all($sFindPattern, $sString, $mMatches, PREG_SET_ORDER);
        if ( is_int($xRes) && $xRes > 0 )
        {
            $raFoundGroupArrays = CArray::make($xRes);
            $raFoundStrings = CArray::make($xRes);
            for ($i0 = 0; $i0 < $xRes; $i0++)
            {
                $iQty = count($mMatches[$i0]);
                $raFoundGroupArrays[$i0] = CArray::make($iQty - 1);
                for ($i1 = 1; $i1 < $iQty; $i1++)
                {
                    $raFoundGroupArrays[$i0][$i1 - 1] = $mMatches[$i0][$i1];
                }
                $raFoundStrings[$i0] = $mMatches[$i0][0];
            }
            return $xRes;
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
     * @param  string $sString The string to be looked into.
     * @param  string $sFindPattern The searched pattern, usually with groups.
     * @param  int $iStartPos The starting position for the search.
     * @param  reference $raFoundGroupArrays **OUTPUT.** If any patterns were found, this is a two-dimensional array of
     * type `CArray`, per each pattern match containing an array of the substrings that matched the groups in the
     * pattern, in the same order, if any.
     * @param  reference $raFoundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArray` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public static function findAllGroupsFrom ($sString, $sFindPattern, $iStartPos, &$raFoundGroupArrays,
        &$raFoundStrings = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sFindPattern) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        $mMatches;
        $xRes = preg_match_all($sFindPattern, $sString, $mMatches, PREG_SET_ORDER, $iStartPos);
        if ( is_int($xRes) && $xRes > 0 )
        {
            $raFoundGroupArrays = CArray::make($xRes);
            $raFoundStrings = CArray::make($xRes);
            for ($i0 = 0; $i0 < $xRes; $i0++)
            {
                $iQty = count($mMatches[$i0]);
                $raFoundGroupArrays[$i0] = CArray::make($iQty - 1);
                for ($i1 = 1; $i1 < $iQty; $i1++)
                {
                    $raFoundGroupArrays[$i0][$i1 - 1] = $mMatches[$i0][$i1];
                }
                $raFoundStrings[$i0] = $mMatches[$i0][0];
            }
            return $xRes;
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
     * @param  string $sString The source string.
     * @param  string $sWhatPattern The pattern to be replaced.
     * @param  string $sWith The replacement string.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replace ($sString, $sWhatPattern, $sWith, &$riQuantity = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhatPattern) && is_cstring($sWith)',
            vs(isset($this), get_defined_vars()) );

        return preg_replace($sWhatPattern, $sWith, $sString, -1, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any occurrence of a pattern in a string with the string returned by a function or method called on the
     * matching substring and returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $sString The source string.
     * @param  string $sWhatPattern The pattern to be replaced.
     * @param  callable $fnCallback A function or method that, as imposed by PHP's PCRE, takes a PHP's associative
     * array (CMap) as a parameter, which contains the matching substring under the key of `0`, the substring that
     * matched the first group, if any, under the key of `1`, and so on for groups, and returns the string with which
     * the matching substring should be replaced.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replaceWithCallback ($sString, $sWhatPattern, $fnCallback, &$riQuantity = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhatPattern) && is_callable($fnCallback)',
            vs(isset($this), get_defined_vars()) );

        return preg_replace_callback($sWhatPattern, $fnCallback, $sString, -1, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a pattern in a string and returns the new string, optionally reporting the number of
     * removals made.
     *
     * @param  string $sString The source string.
     * @param  string $sWhatPattern The pattern to be removed.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return string The resulting string.
     */

    public static function remove ($sString, $sWhatPattern, &$riQuantity = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhatPattern)', vs(isset($this), get_defined_vars()) );
        return self::replace($sString, $sWhatPattern, "", $riQuantity);
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
     * @param  string $sString The string to be split.
     * @param  string|array|map $xDelimiterPatternOrPatterns The pattern or array of patterns to be recognized as the
     * delimiter(s).
     *
     * @return CArray The resulting strings.
     */

    public static function split ($sString, $xDelimiterPatternOrPatterns)
    {
        assert( 'is_cstring($sString) && (is_cstring($xDelimiterPatternOrPatterns) || ' .
                'is_collection($xDelimiterPatternOrPatterns))', vs(isset($this), get_defined_vars()) );

        if ( is_cstring($xDelimiterPatternOrPatterns) )
        {
            $iNumIdt = self::findGroups($xDelimiterPatternOrPatterns, "/^([^0-9A-Za-z\\s\\\\])(.*)\\1/", $aFoundGroups);
            assert( '$iNumIdt == 2', vs(isset($this), get_defined_vars()) );
            $sIdt = $aFoundGroups[1];
            if ( CString::isEmpty($sIdt) )
            {
                // Special case.
                if ( CString::isEmpty($sString) )
                {
                    $aResStrings = CArray::fromElements("");
                    return $aResStrings;
                }
                else
                {
                    if ( preg_match(
                            "/^([^0-9A-Za-z\\s\\\\])\\1[A-Za-z]*u[A-Za-z]*\\z/", $xDelimiterPatternOrPatterns) !== 1 )
                    {
                        $aResStrings = CArray::make(strlen($sString));
                        for ($i = 0; $i < strlen($sString); $i++)
                        {
                            $aResStrings[$i] = $sString[$i];
                        }
                        return $aResStrings;
                    }
                    else
                    {
                        return CUString::splitIntoChars($sString);
                    }
                }
            }

            $mResStrings = preg_split($xDelimiterPatternOrPatterns, $sString);
            $iQty = count($mResStrings);
            $aResStrings = CArray::make($iQty);
            for ($i = 0; $i < $iQty; $i++)
            {
                $aResStrings[$i] = $mResStrings[$i];
            }
            return $aResStrings;
        }
        else  // a collection
        {
            $aResStrings = CArray::fromElements($sString);
            foreach ($xDelimiterPatternOrPatterns as $sDelimiterPattern)
            {
                assert( 'is_cstring($sDelimiterPattern)', vs(isset($this), get_defined_vars()) );
                $aResStringsNew = CArray::make();
                $iLen = CArray::length($aResStrings);
                for ($i = 0; $i < $iLen; $i++)
                {
                    CArray::pushArray($aResStringsNew, self::split($aResStrings[$i], $sDelimiterPattern));
                }
                $aResStrings = $aResStringsNew;
            }
            return $aResStrings;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, escapes all characters that have a special meaning in the regular expression domain, and returns
     * the escaped string.
     *
     * With this method, you can prepare an arbitrary string to be used as a part of a regular expression.
     *
     * @param  string $sString The string to be escaped.
     * @param  string $sDelimiter **OPTIONAL. Default is** "/". The pattern delimiter that is going to be used by the
     * resulting regular expression and therefore needs to be escaped as well.
     *
     * @return string The escaped string.
     */

    public static function enterTd ($sString, $sDelimiter = self::DEFAULT_PATTERN_DELIMITER)
    {
        assert( 'is_cstring($sString) && is_cstring($sDelimiter)', vs(isset($this), get_defined_vars()) );
        return preg_quote($sString, $sDelimiter);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
