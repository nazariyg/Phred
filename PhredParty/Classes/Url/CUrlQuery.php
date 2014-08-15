<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * This class allows you to parse the query string of a URL into fields (key-value pairs), compose a query string from
 * one-dimensional or multi-dimensional fields, while automatically handling percent-encoding in both cases.
 *
 * Internally, the characters in the names and values of the fields in a URL query that is represented by an object of
 * this class are stored in their literal representations, i.e. without any of them being percent-encoded. It is only
 * at the time of constructing an object from a query string or converting an object into the corresponding query
 * string when percent-encoding comes into play.
 *
 * When parsing a query string, the value in a resulting field can be a string, an array (parsed from e.g.
 * "name[]=value0&name[]=value1"), or a map (parsed from e.g. "name[key0]=value0&name[key1]=value1"). When composing a
 * query string, the value of a field you add with `addField` method can be a string, a `bool`, an `int`, a `float`, an
 * array, or a map. If it's a collection, such as an array or map, any value in that collection can be a collection on
 * its own and so on, which enables for multi-dimensional values.
 *
 * The class supports both "&" and ";" as field delimiters in query strings.
 *
 * You can make a copy of a URL query object using `clone` keyword like so:
 *
 * ```
 * $oUrlQueryCopy = clone $oUrlQuery;
 * ```
 */

// Method signatures:
//   __construct ($sQueryString = null, &$rbParsingWasFruitful = null)
//   bool hasField ($sFieldName)
//   CUStringObject|CArrayObject|CMapObject field ($sFieldName)
//   void addField ($sFieldName, $xValue)
//   CUStringObject queryString ($bSortFields = false)

class CUrlQuery extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a URL query from a query string or as empty.
     *
     * Just like in any valid URL, a provided query string is not expected to contain characters that cannot be
     * represented literally and percent-encoding is expected to be used for any such characters. The query string
     * should not contain a leading "?" because it's rather a delimiter that is used to separate the query string from
     * the preceding URL part.
     *
     * @param  string $sQueryString **OPTIONAL. Default is** *create an empty URL query*. The source query string.
     * @param  reference $rbParsingWasFruitful **OPTIONAL. OUTPUT.** After the object is constructed, this parameter,
     * which is of type `bool`, tells whether the parsing of the query string resulted in any valid fields.
     */

    public function __construct ($sQueryString = null, &$rbParsingWasFruitful = null)
    {
        assert( '!isset($sQueryString) || is_cstring($sQueryString)', vs(isset($this), get_defined_vars()) );

        if ( isset($sQueryString) )
        {
            if ( !CString::isEmpty($sQueryString) )
            {
                // Before parsing, normalize field delimiters to the default one (e.g. make ";" to be "&").
                $aDelimiters = CString::splitIntoChars(self::$ms_sFieldDelimiters);
                $aFields = CString::split($sQueryString, $aDelimiters);
                $sQueryString = CArray::join($aFields, self::$ms_sFieldDelimiters[0]);

                // Parse the query string.
                parse_str($sQueryString, $this->m_mQuery);
                if ( !is_cmap($this->m_mQuery) )
                {
                    $this->m_mQuery = CMap::make();
                }

                // Recursively convert any PHP's associative array with sequential keys into a CArray.
                foreach ($this->m_mQuery as &$rxValue)
                {
                    $rxValue = self::recurseQueryValueAfterParsing($rxValue, 0);
                } unset($rxValue);
            }
            else
            {
                $this->m_mQuery = CMap::make();
            }

            $rbParsingWasFruitful = !CMap::isEmpty($this->m_mQuery);
        }
        else
        {
            $this->m_mQuery = CMap::make();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL query contains a field with a specified name.
     *
     * In case of nested fields, i.e. multi-dimensional values, the method only looks into the fields that belong to
     * the topmost level.
     *
     * @param  string $sFieldName The name of the field to be looked for. The characters in the string should be
     * represented literally and no characters should be percent-encoded.
     *
     * @return bool `true` if the URL query contains a field with the name specified, `false` otherwise.
     */

    public function hasField ($sFieldName)
    {
        assert( 'is_cstring($sFieldName)', vs(isset($this), get_defined_vars()) );
        return CMap::hasKey($this->m_mQuery, $sFieldName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a URL query, returns the value of the field with a specified name.
     *
     * Query strings allow for field values to be arrays and maps that can contain not only strings but also other
     * arrays and maps, and so on. If the field's value is a string, it's returned without any of its characters being
     * percent-encoded.
     *
     * @param  string $sFieldName The name of the field to be looked for. The characters in the string should be
     * represented literally and no characters should be percent-encoded.
     *
     * @return CUStringObject|CArrayObject|CMapObject The value of the field with the name specified. This can be a
     * string (the most common case), an array, or a map.
     */

    public function field ($sFieldName)
    {
        assert( 'is_cstring($sFieldName)', vs(isset($this), get_defined_vars()) );
        assert( '$this->hasField($sFieldName)', vs(isset($this), get_defined_vars()) );

        return $this->m_mQuery[$sFieldName];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds a field to a URL query.
     *
     * Query strings allow for field values to be arrays and maps that can contain not only strings but also other
     * arrays and maps, and so on. If the field's value is a string, the characters in it should be represented
     * literally and no characters should be percent-encoded.
     *
     * @param  string $sFieldName The name of the field. The characters in the string should be represented literally
     * and no characters should be percent-encoded.
     * @param  mixed $xValue The value of the field. This can be a string (the most common case), a `bool`, an `int`, a
     * `float`, an array, or a map.
     *
     * @return void
     */

    public function addField ($sFieldName, $xValue)
    {
        assert( 'is_cstring($sFieldName) && (is_cstring($xValue) || ' .
            'is_bool($xValue) || is_int($xValue) || is_float($xValue) || is_collection($xValue))',
            vs(isset($this), get_defined_vars()) );
        if ( CDebug::isDebugModeOn() )
        {
            self::recurseFieldValidation($xValue, 0);
        }

        $this->m_mQuery[$sFieldName] = $xValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Composes a URL query into a query string ready to be used as a part of a URL and returns it.
     *
     * Any characters that cannot be represented literally in a valid query string come out percent-encoded. The
     * resulting query string never starts with "?".
     *
     * Because the characters in field named and field values are stored in their literal representations, the
     * resulting query string is always normalized, with only those characters appearing percent-encoded that really
     * require it for the query string to be valid and with the hexadecimal letters in percent-encoded characters
     * appearing uppercased. Also, no duplicate fields are produced in the resulting query string (even if the object
     * was constructed from a query string with duplicate fields in it) and "=" is added after any field name that goes
     * without a value and is not followed by "=".
     *
     * @param  bool $bSortFields **OPTIONAL. Default is** `false`. Tells whether the fields in the query string should
     * appear sorted in the ascending order, case-insensitively, and with natural order comparison used for sorting.
     *
     * @return CUStringObject The query string.
     */

    public function queryString ($bSortFields = false)
    {
        assert( 'is_bool($bSortFields)', vs(isset($this), get_defined_vars()) );

        if ( !CMap::isEmpty($this->m_mQuery) )
        {
            $mUseQuery = CMap::makeCopy($this->m_mQuery);

            // Recursively convert any CArray into a CMap for `http_build_query` function to accept the query.
            $mUseQuery = self::recurseQueryValueBeforeComposingQs($mUseQuery, 0);

            // Compose a preliminary query string.
            $sQueryString = http_build_query($mUseQuery, "", self::$ms_sFieldDelimiters[0], PHP_QUERY_RFC1738);
            if ( !is_cstring($sQueryString) )
            {
                return "";
            }

            // Break the string into fields.
            $aFields = CString::split($sQueryString, self::$ms_sFieldDelimiters[0]);

            // Adjust the result of `http_build_query` function.
            $iLen = CArray::length($aFields);
            for ($i = 0; $i < $iLen; $i++)
            {
                if ( CString::find($aFields[$i], "=") )
                {
                    // Revert excessive percent-encoding of the square brackets next to the identifiers of
                    // multidimensional data.
                    $aFields[$i] = CRegex::replaceWithCallback($aFields[$i],
                        "/(?:%5B(?:[^%]++|%(?!5B|5D))*+%5D)+?=/i", function ($mMatches)
                        {
                            $sValue = $mMatches[0];
                            $sValue = CString::replace($sValue, "%5B", "[");
                            $sValue = CString::replace($sValue, "%5D", "]");
                            return $sValue;
                        });

                    // Remove redundant indexing next to the identifiers of simple arrays.
                    $aFields[$i] = CRegex::replaceWithCallback($aFields[$i], "/^.+?=/",
                        function ($mMatches)
                        {
                            return CRegex::replace($mMatches[0], "/\\[\\d+\\]/", "[]");
                        });
                }
            }

            if ( $bSortFields )
            {
                // Normalize the order of fields.
                CArray::sortStringsNatCi($aFields);
            }

            $sQueryString = CArray::join($aFields, self::$ms_sFieldDelimiters[0]);
            return $sQueryString;
        }
        else
        {
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseQueryValueAfterParsing ($xValue, $iCurrDepth)
    {
        if ( $iCurrDepth == self::$ms_iMaxRecursionDepth )
        {
            return $xValue;
        }
        $iCurrDepth++;

        if ( !is_cmap($xValue) )
        {
            // Only interested in PHP's associative arrays.
            return $xValue;
        }

        if ( CMap::areKeysSequential($xValue) )
        {
            $xValue = CArray::fromPArray($xValue);

            $iLen = CArray::length($xValue);
            for ($i = 0; $i < $iLen; $i++)
            {
                $xValue[$i] = self::recurseQueryValueAfterParsing($xValue[$i], $iCurrDepth);
            }

            return oop_a($xValue);
        }
        else
        {
            foreach ($xValue as &$rxMapValue)
            {
                $rxMapValue = self::recurseQueryValueAfterParsing($rxMapValue, $iCurrDepth);
            } unset($rxMapValue);

            return oop_m($xValue);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseFieldValidation ($xValue, $iCurrDepth)
    {
        if ( $iCurrDepth == self::$ms_iMaxRecursionDepth )
        {
            return;
        }
        $iCurrDepth++;

        if ( !is_collection($xValue) )
        {
            assert( 'is_cstring($xValue) || is_bool($xValue) || is_int($xValue) || is_float($xValue)',
                vs(isset($this), get_defined_vars()) );
            return;
        }

        if ( is_carray($xValue) )
        {
            $iLen = CArray::length($xValue);
            for ($i = 0; $i < $iLen; $i++)
            {
                self::recurseFieldValidation($xValue[$i], $iCurrDepth);
            }
        }
        else  // a CMap
        {
            foreach ($xValue as $xMapValue)
            {
                self::recurseFieldValidation($xMapValue, $iCurrDepth);
            }
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseQueryValueBeforeComposingQs ($xValue, $iCurrDepth)
    {
        if ( $iCurrDepth == self::$ms_iMaxRecursionDepth )
        {
            return $xValue;
        }
        $iCurrDepth++;

        if ( !is_collection($xValue) )
        {
            if ( !is_cstring($xValue) )
            {
                if ( is_bool($xValue) )
                {
                    $xValue = CString::fromBool10($xValue);
                }
                else if ( is_int($xValue) )
                {
                    $xValue = CString::fromInt($xValue);
                }
                else if ( is_float($xValue) )
                {
                    $xValue = CString::fromFloat($xValue);
                }
                else
                {
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                }
            }
            return $xValue;
        }

        if ( is_carray($xValue) )
        {
            $xValue = splarray($xValue)->toArray();
        }
        else  // map
        {
            $xValue = parray($xValue);
        }

        foreach ($xValue as &$rxMapValue)
        {
            $rxMapValue = self::recurseQueryValueBeforeComposingQs($rxMapValue, $iCurrDepth);
        } unset($rxMapValue);

        return $xValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_mQuery;

    // The set of field delimiters in a query string. The first character in the string is used as the default
    // delimiter.
    protected static $ms_sFieldDelimiters = "&;";

    protected static $ms_iMaxRecursionDepth = CSystem::DEFAULT_MAX_RECURSION_DEPTH;
}
