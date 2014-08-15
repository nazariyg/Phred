<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that lets you decode even malformed JSON strings and to encode maps and arrays into JSON strings.
 *
 * **You can refer to this class by its alias, which is** `Jn`.
 *
 * In the process of decoding, a JSON string is transformed into a map or array, with any JSON array naturally becoming
 * an array and any plain JSON object, which is essentially an associative array, becoming a map.
 *
 * For decoding, there are three "difficulty levels" that you can choose from for an input JSON string. The strictest
 * level is `STRICT`, according to which the input JSON string is expected to be conforming to the JSON standard to the
 * smallest detail, then goes `STRICT_WITH_COMMENTS`, which allows for "//" and "/\*" comments in the JSON string, and
 * `LENIENT`, with which you can still successfully decode a JSON string that contains "//" and "/\*" comments, uses
 * single quotes on values (the JSON format requires double quotes), uses single quotes or no quotes at all on property
 * names, or contains trailing commas where they are redundant.
 *
 * **NOTE.** An integer value from a JSON string may get decoded into an integer or, if the value is too large, into a
 * floating-point value.
 *
 * **Defaults for decoding:**
 *
 * * The strictness is `STRICT`.
 *
 * **Defaults for encoding:**
 *
 * * Escape non-ASCII characters is `false`.
 * * Escape forward slashes is `false`.
 * * Escape angle brackets is `false`.
 * * Escape ampersands is `false`.
 * * Escape single quotes is `false`.
 * * Escape double quotes is `false`.
 * * Pretty printing is `false`.
 */

// Method signatures:
//   __construct ($xSource, $eDecodingStrictness = self::STRICT)
//   void setEscapeNonAsciiChars ($bEnable)
//   void setEscapeForwardSlashes ($bEnable)
//   void setEscapeAngleBrackets ($bEnable)
//   void setEscapeAmpersands ($bEnable)
//   void setEscapeSingleQuotes ($bEnable)
//   void setEscapeDoubleQuotes ($bEnable)
//   void setPrettyPrint ($bEnable)
//   mixed decode (&$rbSuccess = null)
//   CUStringObject encode ()

class CJson extends CRootClass
{
    // Decoding strictnesses.
    /**
     * `enum` Strict, with no comments expected in the JSON string.
     *
     * @var enum
     */
    const STRICT = 0;
    /**
     * `enum` Strict, with "//" and "/*" comments allowed in the JSON string.
     *
     * @var enum
     */
    const STRICT_WITH_COMMENTS = 1;
    /**
     * `enum` Lenient, with "//" and "/*" comments allowed in the JSON string, as well as single-quoted values,
     * single-quoted or just unquoted property names (keys), trailing commas after the last element in a JSON array or
     * after the last property (key-value pair) in a JSON object, and control characters represented by their literal
     * values, such as a tab represented by a 0x09 byte.
     *
     * @var enum
     */
    const LENIENT = 2;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a JSON decoder or encoder.
     *
     * @param  mixed $xSource The string to be decoded from JSON or the map or array to be encoded into JSON. In case
     * of a map or array, it can be multidimensional and contain other maps and arrays.
     * @param  enum $eDecodingStrictness **OPTIONAL. Default is** `STRICT`. If the object is being constructed for
     * decoding, this is the decoding strictness. Can be `STRICT`, `STRICT_WITH_COMMENTS`, or `LENIENT`
     * (see [Summary](#summary)).
     */

    public function __construct ($xSource, $eDecodingStrictness = self::STRICT)
    {
        assert( '(is_cstring($xSource) || is_cmap($xSource) || is_carray($xSource)) && is_enum($eDecodingStrictness)',
            vs(isset($this), get_defined_vars()) );

        $this->m_xSource = $xSource;
        $this->m_eDecodingStrictness = $eDecodingStrictness;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For encoding, sets whether any non-ASCII character should appear escaped in the resulting string.
     *
     * @param  bool $bEnable `true` to make any non-ASCII character appear escaped in the resulting string, `false`
     * otherwise.
     *
     * @return void
     */

    public function setEscapeNonAsciiChars ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bEscapeNonAsciiChars = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For encoding, sets whether any "/" should appear escaped in the resulting string.
     *
     * @param  bool $bEnable `true` to make any "/" appear escaped in the resulting string, `false` otherwise.
     *
     * @return void
     */

    public function setEscapeForwardSlashes ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bEscapeForwardSlashes = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For encoding, sets whether any "<" or ">" should appear escaped in the resulting string.
     *
     * @param  bool $bEnable `true` to make any "<" or ">" appear escaped in the resulting string, `false` otherwise.
     *
     * @return void
     */

    public function setEscapeAngleBrackets ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bEscapeAngleBrackets = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For encoding, sets whether any "&" should appear escaped in the resulting string.
     *
     * @param  bool $bEnable `true` to make any "&" appear escaped in the resulting string, `false` otherwise.
     *
     * @return void
     */

    public function setEscapeAmpersands ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bEscapeAmpersands = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For encoding, sets whether any single quote should appear escaped in the resulting string.
     *
     * @param  bool $bEnable `true` to make any single quote appear escaped in the resulting string, `false` otherwise.
     *
     * @return void
     */

    public function setEscapeSingleQuotes ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bEscapeSingleQuotes = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For encoding, sets whether any double quote should appear escaped in the resulting string.
     *
     * @param  bool $bEnable `true` to make any double quote appear escaped in the resulting string, `false` otherwise.
     *
     * @return void
     */

    public function setEscapeDoubleQuotes ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bEscapeDoubleQuotes = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For encoding, sets whether the resulting string should appear well-formatted with additional whitespace and
     * newlines.
     *
     * @param  bool $bEnable `true` to enable pretty-printing for the resulting string, `false` otherwise.
     *
     * @return void
     */

    public function setPrettyPrint ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bPrettyPrint = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Decodes the JSON-encoded string provided earlier to the decoder and returns the result.
     *
     * @param  reference $rbSuccess **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value tells whether the decoding was successful.
     *
     * @return mixed The decoded value of type `CMapObject` or `CArrayObject`.
     */

    public function decode (&$rbSuccess = null)
    {
        assert( 'is_cstring($this->m_xSource)', vs(isset($this), get_defined_vars()) );

        $rbSuccess = true;

        $sSource = $this->m_xSource;

        if ( $this->m_eDecodingStrictness == self::LENIENT &&
             !CUString::isValid($sSource) )
        {
            // Change the character encoding or try fixing it.
            if ( CEString::looksLikeLatin1($sSource) )
            {
                $sSource = CEString::convertLatin1ToUtf8($sSource);
            }
            else
            {
                $sSource = CEString::fixUtf8($sSource);
            }
        }

        if ( $this->m_eDecodingStrictness == self::STRICT_WITH_COMMENTS ||
             $this->m_eDecodingStrictness == self::LENIENT )
        {
            if ( CRegex::find($sSource, "/\\/\\/|\\/\\*/u") )
            {
                // Remove "//..." and "/*...*/" comments.
                $sSource = CRegex::remove($sSource,
                    "/(?<!\\\\)\"(?:[^\\\\\"]++|\\\\{2}|\\\\\\C)*\"(*SKIP)(*FAIL)|" .
                    "\\/\\/.*|\\/\\*\\C*?\\*\\//u");
            }
        }

        if ( $this->m_eDecodingStrictness == self::LENIENT )
        {
            if ( CRegex::find($sSource, "/[:\\[,]\\s*'([^\\\\']++|\\\\{2}|\\\\\\C)*'(?=\\s*[,}\\]])/u") )
            {
                // Convert single-quoted string values into double-quoted, taking care of double quotes within such
                // strings before and single quotes after. This needs to go in front of the rest of the leniency fixes.
                while ( true )
                {
                    $sPrevSource = $sSource;
                    $sSource = CRegex::replace($sSource,
                        "/(?<!\\\\)\"(?:[^\\\\\"]++|\\\\{2}|\\\\\\C)*\"(*SKIP)(*FAIL)|" .
                        "([:\\[,]\\s*'(?:[^\\\\'\"]++|\\\\{2}|\\\\\\C)*)\"((?:[^\\\\']++|\\\\{2}|\\\\\\C)*')/u",
                        "$1\\\"$2");
                    if ( CString::equals($sSource, $sPrevSource) || is_null($sSource) )
                    {
                        break;
                    }
                }
                if ( is_null($sSource) )
                {
                    $sSource = "";
                }

                $sSource = CRegex::replace($sSource,
                    "/(?<!\\\\)\"(?:[^\\\\\"]++|\\\\{2}|\\\\\\C)*\"(*SKIP)(*FAIL)|" .
                    "([:\\[,]\\s*)'((?:[^\\\\']++|\\\\{2}|\\\\\\C)*)'(?=\\s*[,}\\]])/u", "$1\"$2\"");

                while ( true )
                {
                    $sPrevSource = $sSource;
                    $sSource = CRegex::replace($sSource,
                        "/([:\\[,]\\s*\"(?:[^\\\\\"]++|\\\\{2}|\\\\[^'])*)\\\\'((?:[^\\\\\"]++|\\\\{2}|\\\\\\C)*\")" .
                        "(?=\\s*[,}\\]])/u", "$1'$2");
                    if ( CString::equals($sSource, $sPrevSource) || is_null($sSource) )
                    {
                        break;
                    }
                }
                if ( is_null($sSource) )
                {
                    $sSource = "";
                }
            }

            if ( CRegex::find($sSource, "/[{,]\\s*[\\w\\-.]+\\s*:/u" ) )
            {
                // Put property names in double quotes.
                $sSource = CRegex::replace($sSource,
                    "/(?<!\\\\)\"(?:[^\\\\\"]++|\\\\{2}|\\\\\\C)*\"(*SKIP)(*FAIL)|" .
                    "([{,]\\s*)([\\w\\-.]+)(\\s*:)/u", "$1\"$2\"$3");
            }

            if ( CRegex::find($sSource, "/[{,]\\s*'[\\w\\-.]+'\\s*:/u" ) )
            {
                // Put property names that are in single quotes in double quotes.
                $sSource = CRegex::replace($sSource,
                    "/(?<!\\\\)\"(?:[^\\\\\"]++|\\\\{2}|\\\\\\C)*\"(*SKIP)(*FAIL)|" .
                    "([{,]\\s*)'([\\w\\-.]+)'(\\s*:)/u", "$1\"$2\"$3");
            }

            if ( CRegex::find($sSource, "/,\\s*[}\\]]/u" ) )
            {
                // Remove trailing commas.
                $sSource = CRegex::remove($sSource,
                    "/(?<!\\\\)\"(?:[^\\\\\"]++|\\\\{2}|\\\\\\C)*\"(*SKIP)(*FAIL)|" .
                    ",(?=\\s*[}\\]])/u");
            }

            // Within string values, convert byte values for BS, FF, LF, CR, and HT, which are prohibited in JSON,
            // to their escaped equivalents.
            $sStringValueSubjectRe = "/(?<!\\\\)\"(?:[^\\\\\"]++|\\\\{2}|\\\\\\C)*\"/u";
            $sSource = CRegex::replaceWithCallback($sSource, $sStringValueSubjectRe, function ($mMatches)
                {
                    return CRegex::replace($mMatches[0], "/\\x{0008}/u", "\\b");
                });
            $sSource = CRegex::replaceWithCallback($sSource, $sStringValueSubjectRe, function ($mMatches)
                {
                    return CRegex::replace($mMatches[0], "/\\x{000C}/u", "\\f");
                });
            $sSource = CRegex::replaceWithCallback($sSource, $sStringValueSubjectRe, function ($mMatches)
                {
                    return CRegex::replace($mMatches[0], "/\\x{000A}/u", "\\n");
                });
            $sSource = CRegex::replaceWithCallback($sSource, $sStringValueSubjectRe, function ($mMatches)
                {
                    return CRegex::replace($mMatches[0], "/\\x{000D}/u", "\\r");
                });
            $sSource = CRegex::replaceWithCallback($sSource, $sStringValueSubjectRe, function ($mMatches)
                {
                    return CRegex::replace($mMatches[0], "/\\x{0009}/u", "\\t");
                });
        }

        $xDecodedValue = @json_decode($sSource, false, self::$ms_iMaxRecursionDepth);

        if ( is_null($xDecodedValue) )
        {
            if ( $this->m_eDecodingStrictness == self::STRICT ||
                 $this->m_eDecodingStrictness == self::STRICT_WITH_COMMENTS )
            {
                $rbSuccess = false;
            }
            else if ( CRegex::find($sSource, "/^\\s*[\\w.]+\\s*\\(/u") )  // $this->m_eDecodingStrictness = LENIENT
            {
                // The source string appears to be a JSONP. Extract the function's argument and try decoding again.
                $sSource = CRegex::replace($sSource, "/^\\s*[\\w.]+\\s*\\((\\C+)\\)/u", "$1");
                $xDecodedValue = @json_decode($sSource, false, self::$ms_iMaxRecursionDepth);
                if ( is_null($xDecodedValue) )
                {
                    $rbSuccess = false;
                }
            }
        }
        if ( !$rbSuccess )
        {
            return;
        }

        if ( $this->m_eDecodingStrictness == self::STRICT ||
             $this->m_eDecodingStrictness == self::STRICT_WITH_COMMENTS )
        {
            if ( !is_object($xDecodedValue) && !is_array($xDecodedValue) )
            {
                $rbSuccess = false;
                return;
            }
        }

        // Recursively convert any object into a CMapObject/CMap and any PHP array into a CArrayObject/CArray.
        $xDecodedValue = self::recurseValueAfterDecoding($xDecodedValue, 0);

        return $xDecodedValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Encodes the map or array provided earlier to the encoder into a JSON string and returns it.
     *
     * @return CUStringObject The resulting JSON string.
     */

    public function encode ()
    {
        assert( 'is_cmap($this->m_xSource) || is_carray($this->m_xSource)', vs(isset($this), get_defined_vars()) );

        $xSource = $this->m_xSource;

        if ( CDebug::assertionsLevel2() )
        {
            // Check character encodings in string values.
            self::recurseStringValidation($xSource, 0);
        }

        $xSource = self::recurseValueBeforeEncoding($xSource, 0);

        $bfOptions = 0;
        if ( !$this->m_bEscapeNonAsciiChars )
        {
            $bfOptions |= JSON_UNESCAPED_UNICODE;
        }
        if ( !$this->m_bEscapeForwardSlashes )
        {
            $bfOptions |= JSON_UNESCAPED_SLASHES;
        }
        if ( $this->m_bEscapeAngleBrackets )
        {
            $bfOptions |= JSON_HEX_TAG;
        }
        if ( $this->m_bEscapeAmpersands )
        {
            $bfOptions |= JSON_HEX_AMP;
        }
        if ( $this->m_bEscapeSingleQuotes )
        {
            $bfOptions |= JSON_HEX_APOS;
        }
        if ( $this->m_bEscapeDoubleQuotes )
        {
            $bfOptions |= JSON_HEX_QUOT;
        }
        if ( $this->m_bPrettyPrint )
        {
            $bfOptions |= JSON_PRETTY_PRINT;
        }

        $sEncodedValue = json_encode($xSource, $bfOptions, self::$ms_iMaxRecursionDepth);
        assert( 'is_cstring($sEncodedValue) && !CString::isEmpty($sEncodedValue)',
            vs(isset($this), get_defined_vars()) );
        return $sEncodedValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseValueAfterDecoding ($xValue, $iCurrDepth)
    {
        if ( $iCurrDepth == self::$ms_iMaxRecursionDepth )
        {
            return $xValue;
        }
        $iCurrDepth++;

        if ( is_string($xValue) )
        {
            return $xValue;
        }

        if ( is_object($xValue) )
        {
            $xValue = (array)$xValue;
            foreach ($xValue as &$rxValueInMap)
            {
                $rxValueInMap = self::recurseValueAfterDecoding($rxValueInMap, $iCurrDepth);
            } unset($rxValueInMap);
            $xValue = oop_m($xValue);
        }
        else if ( is_array($xValue) )
        {
            $xValue = CArray::fromPArray($xValue);
            $iLen = CArray::length($xValue);
            for ($i = 0; $i < $iLen; $i++)
            {
                $xValue[$i] = self::recurseValueAfterDecoding($xValue[$i], $iCurrDepth);
            }
            $xValue = oop_a($xValue);
        }
        return $xValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseStringValidation ($xValue, $iCurrDepth)
    {
        if ( $iCurrDepth == self::$ms_iMaxRecursionDepth )
        {
            return;
        }
        $iCurrDepth++;

        if ( is_cmap($xValue) )
        {
            foreach ($xValue as $xValueInMap)
            {
                self::recurseStringValidation($xValueInMap, $iCurrDepth);
            }
        }
        else if ( is_carray($xValue) )
        {
            $iLen = CArray::length($xValue);
            for ($i = 0; $i < $iLen; $i++)
            {
                self::recurseStringValidation($xValue[$i], $iCurrDepth);
            }
        }
        else if ( is_cstring($xValue) )
        {
            assert( 'CUString::isValid($xValue)', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseValueBeforeEncoding ($xValue, $iCurrDepth)
    {
        if ( $iCurrDepth == self::$ms_iMaxRecursionDepth )
        {
            return $xValue;
        }
        $iCurrDepth++;

        if ( is_cstring($xValue) )
        {
            return $xValue;
        }

        if ( is_cmap($xValue) )
        {
            $xValue = parray($xValue);
            foreach ($xValue as &$rxValueInMap)
            {
                $rxValueInMap = self::recurseValueBeforeEncoding($rxValueInMap, $iCurrDepth);
            } unset($rxValueInMap);
            $xValue = (object)$xValue;
        }
        else if ( is_carray($xValue) )
        {
            $xValue = splarray($xValue);
            $iLen = CArray::length($xValue);
            for ($i = 0; $i < $iLen; $i++)
            {
                $xValue[$i] = self::recurseValueBeforeEncoding($xValue[$i], $iCurrDepth);
            }
            $xValue = CArray::toPArray($xValue);
        }
        return $xValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected $m_xSource;
    protected $m_eDecodingStrictness;
    protected $m_bEscapeNonAsciiChars = false;
    protected $m_bEscapeForwardSlashes = false;
    protected $m_bEscapeAngleBrackets = false;
    protected $m_bEscapeAmpersands = false;
    protected $m_bEscapeSingleQuotes = false;
    protected $m_bEscapeDoubleQuotes = false;
    protected $m_bPrettyPrint = false;

    protected static $ms_iMaxRecursionDepth = CSystem::DEFAULT_MAX_RECURSION_DEPTH;
}
