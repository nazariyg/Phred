<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
 * $urlQueryCopy = clone $urlQuery;
 * ```
 */

// Method signatures:
//   __construct ($queryString = null, &$parsingWasFruitful = null)
//   bool hasField ($fieldName)
//   CUStringObject|CArrayObject|CMapObject field ($fieldName)
//   void addField ($fieldName, $value)
//   CUStringObject queryString ($sortFields = false)

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
     * @param  string $queryString **OPTIONAL. Default is** *create an empty URL query*. The source query string.
     * @param  reference $parsingWasFruitful **OPTIONAL. OUTPUT.** After the object is constructed, this parameter,
     * which is of type `bool`, tells whether the parsing of the query string resulted in any valid fields.
     */

    public function __construct ($queryString = null, &$parsingWasFruitful = null)
    {
        assert( '!isset($queryString) || is_cstring($queryString)', vs(isset($this), get_defined_vars()) );

        if ( isset($queryString) )
        {
            if ( !CString::isEmpty($queryString) )
            {
                // Before parsing, normalize field delimiters to the default one (e.g. make ";" to be "&").
                $delimiters = CString::splitIntoChars(self::$ms_fieldDelimiters);
                $fields = CString::split($queryString, $delimiters);
                $queryString = CArray::join($fields, self::$ms_fieldDelimiters[0]);

                // Parse the query string.
                parse_str($queryString, $this->m_query);
                if ( !is_cmap($this->m_query) )
                {
                    $this->m_query = CMap::make();
                }

                // Recursively convert any PHP's associative array with sequential keys into a CArray.
                foreach ($this->m_query as &$value)
                {
                    $value = self::recurseQueryValueAfterParsing($value, 0);
                } unset($value);
            }
            else
            {
                $this->m_query = CMap::make();
            }

            $parsingWasFruitful = !CMap::isEmpty($this->m_query);
        }
        else
        {
            $this->m_query = CMap::make();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL query contains a field with a specified name.
     *
     * In case of nested fields, i.e. multi-dimensional values, the method only looks into the fields that belong to
     * the topmost level.
     *
     * @param  string $fieldName The name of the field to be looked for. The characters in the string should be
     * represented literally and no characters should be percent-encoded.
     *
     * @return bool `true` if the URL query contains a field with the name specified, `false` otherwise.
     */

    public function hasField ($fieldName)
    {
        assert( 'is_cstring($fieldName)', vs(isset($this), get_defined_vars()) );
        return CMap::hasKey($this->m_query, $fieldName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a URL query, returns the value of the field with a specified name.
     *
     * Query strings allow for field values to be arrays and maps that can contain not only strings but also other
     * arrays and maps, and so on. If the field's value is a string, it's returned without any of its characters being
     * percent-encoded.
     *
     * @param  string $fieldName The name of the field to be looked for. The characters in the string should be
     * represented literally and no characters should be percent-encoded.
     *
     * @return CUStringObject|CArrayObject|CMapObject The value of the field with the name specified. This can be a
     * string (the most common case), an array, or a map.
     */

    public function field ($fieldName)
    {
        assert( 'is_cstring($fieldName)', vs(isset($this), get_defined_vars()) );
        assert( '$this->hasField($fieldName)', vs(isset($this), get_defined_vars()) );

        return $this->m_query[$fieldName];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds a field to a URL query.
     *
     * Query strings allow for field values to be arrays and maps that can contain not only strings but also other
     * arrays and maps, and so on. If the field's value is a string, the characters in it should be represented
     * literally and no characters should be percent-encoded.
     *
     * @param  string $fieldName The name of the field. The characters in the string should be represented literally
     * and no characters should be percent-encoded.
     * @param  mixed $value The value of the field. This can be a string (the most common case), a `bool`, an `int`, a
     * `float`, an array, or a map.
     *
     * @return void
     */

    public function addField ($fieldName, $value)
    {
        assert( 'is_cstring($fieldName) && (is_cstring($value) || ' .
            'is_bool($value) || is_int($value) || is_float($value) || is_collection($value))',
            vs(isset($this), get_defined_vars()) );
        if ( CDebug::isDebugModeOn() )
        {
            self::recurseFieldValidation($value, 0);
        }

        $this->m_query[$fieldName] = $value;
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
     * @param  bool $sortFields **OPTIONAL. Default is** `false`. Tells whether the fields in the query string should
     * appear sorted in the ascending order, case-insensitively, and with natural order comparison used for sorting.
     *
     * @return CUStringObject The query string.
     */

    public function queryString ($sortFields = false)
    {
        assert( 'is_bool($sortFields)', vs(isset($this), get_defined_vars()) );

        if ( !CMap::isEmpty($this->m_query) )
        {
            $useQuery = CMap::makeCopy($this->m_query);

            // Recursively convert any CArray into a CMap for `http_build_query` function to accept the query.
            $useQuery = self::recurseQueryValueBeforeComposingQs($useQuery, 0);

            // Compose a preliminary query string.
            $queryString = http_build_query($useQuery, "", self::$ms_fieldDelimiters[0], PHP_QUERY_RFC1738);
            if ( !is_cstring($queryString) )
            {
                return "";
            }

            // Break the string into fields.
            $fields = CString::split($queryString, self::$ms_fieldDelimiters[0]);

            // Adjust the result of `http_build_query` function.
            $len = CArray::length($fields);
            for ($i = 0; $i < $len; $i++)
            {
                if ( CString::find($fields[$i], "=") )
                {
                    // Revert excessive percent-encoding of the square brackets next to the identifiers of
                    // multidimensional data.
                    $fields[$i] = CRegex::replaceWithCallback($fields[$i],
                        "/(?:%5B(?:[^%]++|%(?!5B|5D))*+%5D)+?=/i", function ($matches)
                        {
                            $value = $matches[0];
                            $value = CString::replace($value, "%5B", "[");
                            $value = CString::replace($value, "%5D", "]");
                            return $value;
                        });

                    // Remove redundant indexing next to the identifiers of simple arrays.
                    $fields[$i] = CRegex::replaceWithCallback($fields[$i], "/^.+?=/",
                        function ($matches)
                        {
                            return CRegex::replace($matches[0], "/\\[\\d+\\]/", "[]");
                        });
                }
            }

            if ( $sortFields )
            {
                // Normalize the order of fields.
                CArray::sortStringsNatCi($fields);
            }

            $queryString = CArray::join($fields, self::$ms_fieldDelimiters[0]);
            return $queryString;
        }
        else
        {
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseQueryValueAfterParsing ($value, $currDepth)
    {
        if ( $currDepth == self::$ms_maxRecursionDepth )
        {
            return $value;
        }
        $currDepth++;

        if ( !is_cmap($value) )
        {
            // Only interested in PHP's associative arrays.
            return $value;
        }

        if ( CMap::areKeysSequential($value) )
        {
            $value = CArray::fromPArray($value);

            $len = CArray::length($value);
            for ($i = 0; $i < $len; $i++)
            {
                $value[$i] = self::recurseQueryValueAfterParsing($value[$i], $currDepth);
            }

            return oop_a($value);
        }
        else
        {
            foreach ($value as &$mapValue)
            {
                $mapValue = self::recurseQueryValueAfterParsing($mapValue, $currDepth);
            } unset($mapValue);

            return oop_m($value);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseFieldValidation ($value, $currDepth)
    {
        if ( $currDepth == self::$ms_maxRecursionDepth )
        {
            return;
        }
        $currDepth++;

        if ( !is_collection($value) )
        {
            assert( 'is_cstring($value) || is_bool($value) || is_int($value) || is_float($value)',
                vs(isset($this), get_defined_vars()) );
            return;
        }

        if ( is_carray($value) )
        {
            $len = CArray::length($value);
            for ($i = 0; $i < $len; $i++)
            {
                self::recurseFieldValidation($value[$i], $currDepth);
            }
        }
        else  // a CMap
        {
            foreach ($value as $mapValue)
            {
                self::recurseFieldValidation($mapValue, $currDepth);
            }
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseQueryValueBeforeComposingQs ($value, $currDepth)
    {
        if ( $currDepth == self::$ms_maxRecursionDepth )
        {
            return $value;
        }
        $currDepth++;

        if ( !is_collection($value) )
        {
            if ( !is_cstring($value) )
            {
                if ( is_bool($value) )
                {
                    $value = CString::fromBool10($value);
                }
                else if ( is_int($value) )
                {
                    $value = CString::fromInt($value);
                }
                else if ( is_float($value) )
                {
                    $value = CString::fromFloat($value);
                }
                else
                {
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                }
            }
            return $value;
        }

        if ( is_carray($value) )
        {
            $value = splarray($value)->toArray();
        }
        else  // map
        {
            $value = parray($value);
        }

        foreach ($value as &$mapValue)
        {
            $mapValue = self::recurseQueryValueBeforeComposingQs($mapValue, $currDepth);
        } unset($mapValue);

        return $value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_query;

    // The set of field delimiters in a query string. The first character in the string is used as the default
    // delimiter.
    protected static $ms_fieldDelimiters = "&;";

    protected static $ms_maxRecursionDepth = CSystem::DEFAULT_MAX_RECURSION_DEPTH;
}
