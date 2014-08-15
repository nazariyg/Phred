<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The hub for static methods that take care of ASCII strings.
 *
 * **In the OOP mode, you would likely never need to use this class.**
 *
 * **You can refer to this class by its alias, which is** `Str`.
 *
 * An ASCII string is a string of characters where each character is a byte with a value in the range from 0 (0x00) to
 * 127 (0x7F). Unlike Unicode strings, which are mostly suitable for human languages, ASCII strings are typically used
 * by computers to exchange information with other computers, markup, coding, and various metadata. When an ASCII
 * string appears with a human language in some of its part, that language is likely to be English.
 *
 * When deciding between the `compare...` methods to determine the order in which strings should appear in a sorted
 * list or any other place where strings need to be presented to a human being in a sorted fashion, `compareNatCi`
 * method is usually most preferable. Since ASCII and Unicode do not share the same view on string comparison, Unicode
 * strings, which are served by the CUString class, get compared with the `compare...` methods in a way that is
 * different from how ASCII strings get compared with the same-named methods of this class.
 */

// Method signatures:
//   static bool isValid ($sString)
//   static string sanitize ($sString)
//   static string fromBool10 ($bValue)
//   static string fromBoolTf ($bValue)
//   static string fromBoolYn ($bValue)
//   static string fromBoolOo ($bValue)
//   static string fromInt ($iValue)
//   static string fromFloat ($fValue)
//   static string fromCharCode ($iCode)
//   static bool toBool ($sString)
//   static bool toBoolFrom10 ($sString)
//   static bool toBoolFromTf ($sString)
//   static bool toBoolFromYn ($sString)
//   static bool toBoolFromOo ($sString)
//   static int toInt ($sString)
//   static int toIntFromHex ($sString)
//   static int toIntFromBase ($sString, $iBase)
//   static float toFloat ($sString)
//   static int toCharCode ($sChar)
//   static string toCharCodeHex ($sChar)
//   static string toEscString ($sString)
//   static int length ($sString)
//   static bool isEmpty ($sString)
//   static bool equals ($sString, $sToString)
//   static bool equalsCi ($sString, $sToString)
//   static int compare ($sString, $sToString)
//   static int compareCi ($sString, $sToString)
//   static int compareNat ($sString, $sToString)
//   static int compareNatCi ($sString, $sToString)
//   static int levenDist ($sString, $sToString)
//   static string metaphoneKey ($sString)
//   static int metaphoneDist ($sString, $sToString)
//   static string toLowerCase ($sString)
//   static string toUpperCase ($sString)
//   static string toUpperCaseFirst ($sString)
//   static string toTitleCase ($sString)
//   static bool startsWith ($sString, $sWithString)
//   static bool startsWithCi ($sString, $sWithString)
//   static bool endsWith ($sString, $sWithString)
//   static bool endsWithCi ($sString, $sWithString)
//   static int indexOf ($sString, $sOfString, $iStartPos = 0)
//   static int indexOfCi ($sString, $sOfString, $iStartPos = 0)
//   static int lastIndexOf ($sString, $sOfString, $iStartPos = 0)
//   static int lastIndexOfCi ($sString, $sOfString, $iStartPos = 0)
//   static bool find ($sString, $sWhatString, $iStartPos = 0)
//   static bool findCi ($sString, $sWhatString, $iStartPos = 0)
//   static bool isSubsetOf ($sString, $sOfCharSet)
//   static string substr ($sString, $iStartPos, $iLength = null)
//   static string substring ($sString, $iStartPos, $iEndPos)
//   static int numSubstrings ($sString, $sSubstring, $iStartPos = 0)
//   static CArray split ($sString, $xDelimiterOrDelimiters)
//   static CArray splitIntoChars ($sString)
//   static string trimStart ($sString)
//   static string trimEnd ($sString)
//   static string trim ($sString)
//   static string normSpacing ($sString)
//   static string normNewlines ($sString, $sNewline = self::NEWLINE)
//   static string padStart ($sString, $sPaddingString, $iNewLength)
//   static string padEnd ($sString, $sPaddingString, $iNewLength)
//   static string stripStart ($sString, $xPrefixOrPrefixes)
//   static string stripStartCi ($sString, $xPrefixOrPrefixes)
//   static string stripEnd ($sString, $xSuffixOrSuffixes)
//   static string stripEndCi ($sString, $xSuffixOrSuffixes)
//   static string insert ($sString, $iAtPos, $sInsertString)
//   static string replaceSubstring ($sString, $iStartPos, $iLength, $sWith)
//   static string replaceSubstringByRange ($sString, $iStartPos, $iEndPos, $sWith)
//   static string removeSubstring ($sString, $iStartPos, $iLength)
//   static string removeSubstringByRange ($sString, $iStartPos, $iEndPos)
//   static string replace ($sString, $sWhat, $sWith, &$riQuantity = null)
//   static string replaceCi ($sString, $sWhat, $sWith, &$riQuantity = null)
//   static string remove ($sString, $sWhat, &$riQuantity = null)
//   static string removeCi ($sString, $sWhat, &$riQuantity = null)
//   static string shuffle ($sString)
//   static string wordWrap ($sString, $iWidth, $bBreakSpacelessLines = false, $sNewline = self::NEWLINE)
//   static string decToHex ($sNumber)
//   static string hexToDec ($sNumber)
//   static string numberToBase ($sNumber, $iFromBase, $iToBase)
//   static string repeat ($sString, $iTimes)

class CString extends CRootClass implements IEqualityAndOrderStatic
{
    // The default newline format and other common newline formats.
    /**
     * `string` The default newline format, which is LF (0x0A).
     *
     * @var string
     */
    const NEWLINE = self::NEWLINE_LF;
    /**
     * `string` LF newline (0x0A). Used by Linux/Unix and OS X.
     *
     * @var string
     */
    const NEWLINE_LF = "\x0A";
    /**
     * `string` CRLF newline (0x0D, 0x0A). Used by Windows.
     *
     * @var string
     */
    const NEWLINE_CRLF = "\x0D\x0A";
    /**
     * `string` CR newline (0x0D).
     *
     * @var string
     */
    const NEWLINE_CR = "\x0D";

    /**
     * `string` The regular expression pattern used in trimming and spacing normalization.
     *
     * @var string
     */
    const TRIMMING_AND_SPACING_NORM_SUBJECT_RE = "[\\x00-\\x20\\x7F-\\xFF]";

    /**
     * `string` The regular expression pattern used in newline normalization.
     *
     * @var string
     */
    const NL_NORM_SUBJECT_RE = "\\x0D\\x0A|\\x0A|\\x0B|\\x0C|\\x0D";  // CRLF goes first because of its length

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is a valid ASCII string.
     *
     * A valid ASCII string can only contain characters (bytes) with codes that do not exceed 127 (0x7F).
     *
     * @param  string $sString The string to be looked into.
     *
     * @return bool `true` if the string is a valid ASCII string, `false` otherwise.
     */

    public static function isValid ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return ( preg_match("/^[\\x00-\\x7F]*\\z/", $sString) === 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any invalid character in an ASCII string with a question mark and returns the new string.
     *
     * An invalid character (byte) in an ASCII string is any character with a code that exceeds 127 (0x7F).
     *
     * @param  string $sString The string to be sanitized.
     *
     * @return string The sanitized string.
     */

    public static function sanitize ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return preg_replace("/[\\x80-\\xFF]/", "?", $sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "1" for `true` and as "0" for `false`.
     *
     * @param  bool $bValue The value to be converted.
     *
     * @return string "1" for `true`, "0" for `false`.
     */

    public static function fromBool10 ($bValue)
    {
        assert( 'is_bool($bValue)', vs(isset($this), get_defined_vars()) );
        return ( $bValue ) ? "1" : "0";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "true" for `true` and as "false" for `false`.
     *
     * @param  bool $bValue The value to be converted.
     *
     * @return string "true" for `true`, "false" for `false`.
     */

    public static function fromBoolTf ($bValue)
    {
        assert( 'is_bool($bValue)', vs(isset($this), get_defined_vars()) );
        return ( $bValue ) ? "true" : "false";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "yes" for `true` and as "no" for `false`.
     *
     * @param  bool $bValue The value to be converted.
     *
     * @return string "yes" for `true`, "no" for `false`.
     */

    public static function fromBoolYn ($bValue)
    {
        assert( 'is_bool($bValue)', vs(isset($this), get_defined_vars()) );
        return ( $bValue ) ? "yes" : "no";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "on" for `true` and as "off" for `false`.
     *
     * @param  bool $bValue The value to be converted.
     *
     * @return string "on" for `true`, "off" for `false`.
     */

    public static function fromBoolOo ($bValue)
    {
        assert( 'is_bool($bValue)', vs(isset($this), get_defined_vars()) );
        return ( $bValue ) ? "on" : "off";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer value into a string.
     *
     * @param  int $iValue The value to be converted.
     *
     * @return string The textual representation of the integer value.
     */

    public static function fromInt ($iValue)
    {
        assert( 'is_int($iValue)', vs(isset($this), get_defined_vars()) );
        return (string)$iValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point value into a string.
     *
     * @param  float $fValue The value to be converted.
     *
     * @return string The textual representation of the floating-point value.
     */

    public static function fromFloat ($fValue)
    {
        assert( 'is_float($fValue)', vs(isset($this), get_defined_vars()) );
        return (string)$fValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code.
     *
     * @param  int $iCode The ASCII character code.
     *
     * @return string The ASCII character with the code specified.
     */

    public static function fromCharCode ($iCode)
    {
        assert( 'is_int($iCode)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iCode && $iCode <= 0x7F', vs(isset($this), get_defined_vars()) );

        return chr($iCode);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value.
     *
     * Any string that is "1", "true", "yes", or "on", regardless of the letter case, is interpreted as `true` and any
     * other string is interpreted as `false`.
     *
     * @param  string $sString The string to be converted.
     *
     * @return bool `true` for "1", "true", "yes", and "on", `false` for any other string.
     */

    public static function toBool ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        return (
            self::equals($sString, "1") ||
            self::equalsCi($sString, "true") ||
            self::equalsCi($sString, "yes") ||
            self::equalsCi($sString, "on") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "1" and as `false` for "0" or any other string.
     *
     * @param  string $sString The string to be converted.
     *
     * @return bool `true` for "1", `false` for any other string.
     */

    public static function toBoolFrom10 ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::equals($sString, "1");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "true" and as `false` for "false" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $sString The string to be converted.
     *
     * @return bool `true` for "true", `false` for any other string.
     */

    public static function toBoolFromTf ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::equalsCi($sString, "true");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "yes" and as `false` for "no" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $sString The string to be converted.
     *
     * @return bool `true` for "yes", `false` for any other string.
     */

    public static function toBoolFromYn ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::equalsCi($sString, "yes");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "on" and as `false` for "off" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $sString The string to be converted.
     *
     * @return bool `true` for "on", `false` for any other string.
     */

    public static function toBoolFromOo ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::equalsCi($sString, "on");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into the corresponding integer value.
     *
     * @param  string $sString The string to be converted.
     *
     * @return int The integer value represented by the string.
     */

    public static function toInt ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return (int)$sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a hexadecimal integer into the corresponding integer value.
     *
     * The string may be prefixed with "0x".
     *
     * @param  string $sString The string to be converted.
     *
     * @return int The integer value represented by the string.
     */

    public static function toIntFromHex ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return intval($sString, 16);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with an arbitrary-base integer into the corresponding integer value.
     *
     * The string may be prefixed with "0x" for the base of 16.
     *
     * @param  string $sString The string to be converted.
     * @param  int $iBase The base in which the integer is represented by the string. Can be a number in the range from
     * 2 to 36.
     *
     * @return int The integer value represented by the string.
     */

    public static function toIntFromBase ($sString, $iBase)
    {
        assert( 'is_cstring($sString) && is_int($iBase)', vs(isset($this), get_defined_vars()) );
        assert( '2 <= $iBase && $iBase <= 36', vs(isset($this), get_defined_vars()) );

        return intval($sString, $iBase);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a floating-point number into the corresponding floating-point value.
     *
     * The number can be using a scientific notation, such as "2.5e-1".
     *
     * @param  string $sString The string to be converted.
     *
     * @return float The floating-point value represented by the string.
     */

    public static function toFloat ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return (float)$sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code of a specified character, as an integer.
     *
     * @param  string $sChar The character.
     *
     * @return int The ASCII code of the character.
     */

    public static function toCharCode ($sChar)
    {
        assert( 'is_cstring($sChar)', vs(isset($this), get_defined_vars()) );
        assert( 'strlen($sChar) == 1', vs(isset($this), get_defined_vars()) );

        return ord($sChar);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code of a specified character, as a hexadecimal string.
     *
     * The returned string is always two characters in length.
     *
     * @param  string $sChar The character.
     *
     * @return string The hexadecimal ASCII code of the character.
     */

    public static function toCharCodeHex ($sChar)
    {
        assert( 'is_cstring($sChar)', vs(isset($this), get_defined_vars()) );
        return self::padStart(self::decToHex((string)self::toCharCode($sChar)), "0", 2);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Escapes all the characters in a string and returns the new string.
     *
     * Each character is replaced with "\x" followed by the two-digit hexadecimal code of the character.
     *
     * @param  string $sString The string to be escaped.
     *
     * @return string The escaped string.
     */

    public static function toEscString ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $sResString = "";
        for ($i = 0; $i < strlen($sString); $i++)
        {
            $sResString .= "\\x" . self::toCharCodeHex($sString[$i]);
        }
        return $sResString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many characters there are in a string.
     *
     * @param  string $sString The string to be looked into.
     *
     * @return int The string's length.
     */

    public static function length ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return strlen($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is empty.
     *
     * @param  string $sString The string to be looked into.
     *
     * @return bool `true` if the string is empty, `false` otherwise.
     */

    public static function isEmpty ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return ( $sString === "" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two strings are equal, comparing them case-sensitively.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     *
     * @return bool `true` if the two strings are equal, taking into account the letter case of the characters, and
     * `false` otherwise.
     */

    public static function equals ($sString, $sToString)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString)', vs(isset($this), get_defined_vars()) );
        return ( $sString === $sToString );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two strings are equal, comparing them case-insensitively.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     *
     * @return bool `true` if the two strings are equal, ignoring the letter case of the characters, and `false`
     * otherwise.
     */

    public static function equalsCi ($sString, $sToString)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString)', vs(isset($this), get_defined_vars()) );
        return ( strcasecmp($sString, $sToString) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-sensitively.
     *
     * **NOTE.** In ASCII, all uppercase goes before all lowercase, so you may consider using `compareCi` method
     * instead.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     *
     * @link   #method_compareCi compareCi
     */

    public static function compare ($sString, $sToString)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign(strcmp($sString, $sToString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-insensitively.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public static function compareCi ($sString, $sToString)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign(strcasecmp($sString, $sToString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order, comparing the strings case-sensitively, and using natural order comparison.
     *
     * To illustrate natural order with an example, the strings "a100" and "a20" would get ordered as such with
     * `compare` method, but as "a20" and "a100" with this method, which is the order a human being would choose.
     *
     * **NOTE.** In ASCII, all uppercase goes before all lowercase, so you may consider using `compareNatCi` method
     * instead.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     *
     * @link   #method_compareNatCi compareNatCi
     */

    public static function compareNat ($sString, $sToString)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign(strnatcmp($sString, $sToString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order, comparing the strings case-insensitively, and using natural order comparison.
     *
     * To illustrate natural order with an example, the strings "a100" and "a20" would get ordered as such with
     * `compareCi` method, but as "a20" and "a100" with this method, which is the order a human being would choose.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public static function compareNatCi ($sString, $sToString)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign(strnatcasecmp($sString, $sToString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Levenshtein distance calculated between two strings.
     *
     * For any two strings, the [Levenshtein distance](http://en.wikipedia.org/wiki/Levenshtein_distance) is the total
     * number of insert, replace, and delete operations required to transform the first string into the second one.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     *
     * @return int The Levenshtein distance between the two strings.
     */

    public static function levenDist ($sString, $sToString)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString)', vs(isset($this), get_defined_vars()) );
        assert( 'strlen($sString) <= 255 && strlen($sToString) <= 255', vs(isset($this), get_defined_vars()) );

        return levenshtein($sString, $sToString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Metaphone key "heard" from a string.
     *
     * The algorithm used to render the [Metaphone](http://en.wikipedia.org/wiki/Metaphone) key is the first-generation
     * one.
     *
     * @param  string $sString The source string.
     *
     * @return string The Metaphone key of the string.
     */

    public static function metaphoneKey ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $xRes = metaphone($sString);
        if ( is_cstring($xRes) )
        {
            return $xRes;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Levenshtein distance calculated between the Metaphone keys of two strings.
     *
     * For any two strings, the [Levenshtein distance](http://en.wikipedia.org/wiki/Levenshtein_distance) is the total
     * number of insert, replace, and delete operations required to transform the first string into the second one. The
     * algorithm used to render the [Metaphone](http://en.wikipedia.org/wiki/Metaphone) keys is the first-generation
     * one.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     *
     * @return int The Levenshtein distance between the Metaphone keys of the two strings.
     */

    public static function metaphoneDist ($sString, $sToString)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString)', vs(isset($this), get_defined_vars()) );
        return self::levenDist(self::metaphoneKey($sString), self::metaphoneKey($sToString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts all the characters in a string to lowercase and returns the new string.
     *
     * @param  string $sString The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toLowerCase ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return strtolower($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts all the characters in a string to uppercase and returns the new string.
     *
     * @param  string $sString The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toUpperCase ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return strtoupper($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts the first character in a string to uppercase and returns the new string.
     *
     * @param  string $sString The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toUpperCaseFirst ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return ucfirst($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts the first character in each word that there is in a string to uppercase and returns the new string.
     *
     * @param  string $sString The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toTitleCase ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return ucwords($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string starts with a specified substring, searching case-sensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sWithString The searched substring.
     *
     * @return bool `true` if the string starts with the substring specified, taking into account the letter case of
     * the characters, and `false` otherwise.
     */

    public static function startsWith ($sString, $sWithString)
    {
        assert( 'is_cstring($sString) && is_cstring($sWithString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sWithString) )
        {
            return true;
        }
        return ( strpos($sString, $sWithString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string starts with a specified substring, searching case-insensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sWithString The searched substring.
     *
     * @return bool `true` if the string starts with the substring specified, ignoring the letter case of the
     * characters, and `false` otherwise.
     */

    public static function startsWithCi ($sString, $sWithString)
    {
        assert( 'is_cstring($sString) && is_cstring($sWithString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sWithString) )
        {
            return true;
        }
        return ( stripos($sString, $sWithString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string ends with a specified substring, searching case-sensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sWithString The searched substring.
     *
     * @return bool `true` if the string ends with the substring specified, taking into account the letter case of the
     * characters, and `false` otherwise.
     */

    public static function endsWith ($sString, $sWithString)
    {
        assert( 'is_cstring($sString) && is_cstring($sWithString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sWithString) )
        {
            return true;
        }
        return ( strrpos($sString, $sWithString) === strlen($sString) - strlen($sWithString) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string ends with a specified substring, searching case-insensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sWithString The searched substring.
     *
     * @return bool `true` if the string ends with the substring specified, ignoring the letter case of the characters,
     * and `false` otherwise.
     */

    public static function endsWithCi ($sString, $sWithString)
    {
        assert( 'is_cstring($sString) && is_cstring($sWithString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sWithString) )
        {
            return true;
        }
        return ( strripos($sString, $sWithString) === strlen($sString) - strlen($sWithString) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a substring in a string, searching case-sensitively.
     *
     * As a special case, the method returns the starting position of the search if the searched substring is empty.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sOfString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the first occurrence of the substring in the string or `-1` if no such substring was
     * found, taking into account the letter case during the search.
     */

    public static function indexOf ($sString, $sOfString, $iStartPos = 0)
    {
        assert( 'is_cstring($sString) && is_cstring($sOfString) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sOfString) )
        {
            return $iStartPos;
        }
        $xRes = strpos($sString, $sOfString, $iStartPos);
        return ( is_int($xRes) ) ? $xRes : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a substring in a string, searching case-insensitively.
     *
     * As a special case, the method returns the starting position of the search if the searched substring is empty.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sOfString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the first occurrence of the substring in the string or `-1` if no such substring was
     * found, ignoring the letter case during the search.
     */

    public static function indexOfCi ($sString, $sOfString, $iStartPos = 0)
    {
        assert( 'is_cstring($sString) && is_cstring($sOfString) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sOfString) )
        {
            return $iStartPos;
        }
        $xRes = stripos($sString, $sOfString, $iStartPos);
        return ( is_int($xRes) ) ? $xRes : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a substring in a string, searching case-sensitively.
     *
     * As a special case, the method returns the string's length if the searched substring is empty.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sOfString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the last occurrence of the substring in the string or `-1` if no such substring was
     * found, taking into account the letter case during the search.
     */

    public static function lastIndexOf ($sString, $sOfString, $iStartPos = 0)
    {
        assert( 'is_cstring($sString) && is_cstring($sOfString) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sOfString) )
        {
            return strlen($sString);
        }
        $xRes = strrpos($sString, $sOfString, $iStartPos);
        return ( is_int($xRes) ) ? $xRes : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a substring in a string, searching case-insensitively.
     *
     * As a special case, the method returns the string's length if the searched substring is empty.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sOfString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the last occurrence of the substring in the string or `-1` if no such substring was
     * found, ignoring the letter case during the search.
     */

    public static function lastIndexOfCi ($sString, $sOfString, $iStartPos = 0)
    {
        assert( 'is_cstring($sString) && is_cstring($sOfString) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iStartPos && $iStartPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sOfString) )
        {
            return strlen($sString);
        }
        $xRes = strripos($sString, $sOfString, $iStartPos);
        return ( is_int($xRes) ) ? $xRes : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified substring, searching case-sensitively.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sWhatString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return bool `true` if the substring was found in the string, taking into account the letter case during the
     * search, and `false` otherwise.
     */

    public static function find ($sString, $sWhatString, $iStartPos = 0)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhatString) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        return ( self::indexOf($sString, $sWhatString, $iStartPos) != -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified substring, searching case-insensitively.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sWhatString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return bool `true` if the substring was found in the string, ignoring the letter case during the search, and
     * `false` otherwise.
     */

    public static function findCi ($sString, $sWhatString, $iStartPos = 0)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhatString) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        return ( self::indexOfCi($sString, $sWhatString, $iStartPos) != -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the characters in a string are a subset of the characters in another string.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sOfCharSet The reference string.
     *
     * @return bool `true` if the string is a subset of the reference string, `false` otherwise.
     */

    public static function isSubsetOf ($sString, $sOfCharSet)
    {
        assert( 'is_cstring($sString) && is_cstring($sOfCharSet)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sString) && !self::isEmpty($sOfCharSet) )
        {
            // Special case.
            return false;
        }

        for ($i0 = 0; $i0 < strlen($sString); $i0++)
        {
            $bIsIn = false;
            for ($i1 = 0; $i1 < strlen($sOfCharSet); $i1++)
            {
                if ( self::equals($sString[$i0], $sOfCharSet[$i1]) )
                {
                    $bIsIn = true;
                    break;
                }
            }
            if ( !$bIsIn )
            {
                return false;
            }
        }
        return true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a substring from a string.
     *
     * As a special case, the method returns an empty string if the starting position is equal to the string's length
     * or if the substring's length, if specified, is `0`.
     *
     * @param  string $sString The string to be looked into.
     * @param  int $iStartPos The position of the substring's first character.
     * @param  int $iLength **OPTIONAL. Default is** *as many characters as the starting character is followed by*. The
     * length of the substring.
     *
     * @return string The substring.
     */

    public static function substr ($sString, $iStartPos, $iLength = null)
    {
        assert( 'is_cstring($sString) && is_int($iStartPos) && (!isset($iLength) || is_int($iLength))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $iStartPos && $iStartPos < strlen($sString)) || ($iStartPos == strlen($sString) && ' .
            '(!isset($iLength) || $iLength == 0))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($iLength) || ($iLength >= 0 && $iStartPos + $iLength <= strlen($sString))',
            vs(isset($this), get_defined_vars()) );

        $xRes;
        if ( !isset($iLength) )
        {
            $xRes = substr($sString, $iStartPos);
        }
        else
        {
            $xRes = substr($sString, $iStartPos, $iLength);
        }
        return ( is_cstring($xRes) ) ? $xRes : "";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a substring from a string, with both starting and ending positions specified.
     *
     * As a special case, the method returns an empty string if the starting and ending positions are the same, with
     * the greatest such position being the string's length.
     *
     * @param  string $sString The string to be looked into.
     * @param  int $iStartPos The position of the substring's first character.
     * @param  int $iEndPos The position of the character that *follows* the last character in the substring.
     *
     * @return string The substring.
     */

    public static function substring ($sString, $iStartPos, $iEndPos)
    {
        assert( 'is_cstring($sString) && is_int($iStartPos) && is_int($iEndPos)',
            vs(isset($this), get_defined_vars()) );
        return self::substr($sString, $iStartPos, $iEndPos - $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many substrings with a specified text there are in a string.
     *
     * The search is case-sensitive.
     *
     * @param  string $sString The string to be looked into.
     * @param  string $sSubstring The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The number of such substrings in the string.
     */

    public static function numSubstrings ($sString, $sSubstring, $iStartPos = 0)
    {
        assert( 'is_cstring($sString) && is_cstring($sSubstring) && is_int($iStartPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($sSubstring)', vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $iStartPos && $iStartPos < strlen($sString)) || (self::isEmpty($sString) && $iStartPos == 0)',
            vs(isset($this), get_defined_vars()) );

        return substr_count($sString, $sSubstring, $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Splits a string into substrings using a specified substring or substrings as the delimiter(s) and returns the
     * resulting strings as an array.
     *
     * If no delimiter substrings were found, the resulting array contains just one element, which is the original
     * string. If a delimiter is located at the very start or at the very end of the string or next to another
     * delimiter, it will accordingly cause some string(s) in the resulting array to be empty.
     *
     * As a special case, the delimiter substring can be empty, which will split the string into its constituting
     * characters.
     *
     * @param  string $sString The string to be split.
     * @param  string|array|map $xDelimiterOrDelimiters The substring or array of substrings to be recognized as the
     * delimiter(s).
     *
     * @return CArray The resulting strings.
     */

    public static function split ($sString, $xDelimiterOrDelimiters)
    {
        assert( 'is_cstring($sString) && ' .
                '(is_cstring($xDelimiterOrDelimiters) || is_collection($xDelimiterOrDelimiters))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($xDelimiterOrDelimiters) )
        {
            if ( self::isEmpty($xDelimiterOrDelimiters) )
            {
                // Special case.
                if ( self::isEmpty($sString) )
                {
                    $aResStrings = CArray::fromElements("");
                    return $aResStrings;
                }
                else
                {
                    $aResStrings = CArray::make(strlen($sString));
                    for ($i = 0; $i < strlen($sString); $i++)
                    {
                        $aResStrings[$i] = $sString[$i];
                    }
                    return $aResStrings;
                }
            }

            $aResStrings = CArray::make(self::numSubstrings($sString, $xDelimiterOrDelimiters) + 1);
            $iStartPos = 0;
            $i = 0;
            while ( true )
            {
                $iEndPos = self::indexOf($sString, $xDelimiterOrDelimiters, $iStartPos);
                if ( $iEndPos != -1 )
                {
                    $aResStrings[$i++] = self::substring($sString, $iStartPos, $iEndPos);
                    $iStartPos = $iEndPos + strlen($xDelimiterOrDelimiters);
                }
                else
                {
                    $aResStrings[$i] = self::substr($sString, $iStartPos);
                    break;
                }
            }
            return $aResStrings;
        }
        else  // a collection
        {
            $aResStrings = CArray::fromElements($sString);
            foreach ($xDelimiterOrDelimiters as $sDelimiter)
            {
                assert( 'is_cstring($sDelimiter)', vs(isset($this), get_defined_vars()) );
                $aResStringsNew = CArray::make();
                $iLen = CArray::length($aResStrings);
                for ($i = 0; $i < $iLen; $i++)
                {
                    CArray::pushArray($aResStringsNew, self::split($aResStrings[$i], $sDelimiter));
                }
                $aResStrings = $aResStringsNew;
            }
            return $aResStrings;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Splits a string into its constituting characters.
     *
     * @param  string $sString The string to be split.
     *
     * @return CArray The string's characters.
     */

    public static function splitIntoChars ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::split($sString, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the start of a string that are non-printable, such as whitespace, or are invalid in
     * ASCII and returns the new string.
     *
     * @param  string $sString The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trimStart ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return preg_replace("/^(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/", "", $sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the end of a string that are non-printable, such as whitespace, or are invalid in
     * ASCII and returns the new string.
     *
     * @param  string $sString The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trimEnd ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return preg_replace("/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+\\z/", "", $sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from both ends of a string that are non-printable, such as whitespace, or are invalid in
     * ASCII and returns the new string.
     *
     * @param  string $sString The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trim ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $sString = self::trimStart($sString);
        $sString = self::trimEnd($sString);
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the spacing in a string by removing all characters from both of its ends that are non-printable, such
     * as whitespace, or are invalid in ASCII and replacing any sequence of such characters within the string with a
     * single space character, and returns the new string.
     *
     * @param  string $sString The string to be normalized.
     *
     * @return string The normalized string.
     */

    public static function normSpacing ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $sString = preg_replace("/^(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/", "", $sString);
        $sString = preg_replace("/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+\\z/", "", $sString);
        $sString = preg_replace("/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/", " ", $sString);
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the newlines in a string by replacing any newline that is not an LF newline with an LF, which is the
     * standard newline format on Linux/Unix and OS X, or with a custom newline, and returns the new string.
     *
     * The known newline formats are LF (0x0A), CRLF (0x0D, 0x0A), CR (0x0D), VT (0x0B), and FF (0x0C).
     *
     * @param  string $sString The string to be normalized.
     * @param  string $sNewline **OPTIONAL. Default is** LF (0x0A).
     *
     * @return string The normalized string.
     */

    public static function normNewlines ($sString, $sNewline = self::NEWLINE)
    {
        assert( 'is_cstring($sString) && is_cstring($sNewline)', vs(isset($this), get_defined_vars()) );
        return preg_replace("/" . self::NL_NORM_SUBJECT_RE . "/", $sNewline, $sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds characters to the start of a string to make it grow to a specified length and returns the new string.
     *
     * If the input string is already of the targeted length, it's returned unmodified.
     *
     * @param  string $sString The string to be padded.
     * @param  string $sPaddingString The string to be used for padding.
     * @param  int $iNewLength The length of the padded string.
     *
     * @return string The padded string.
     */

    public static function padStart ($sString, $sPaddingString, $iNewLength)
    {
        assert( 'is_cstring($sString) && is_cstring($sPaddingString) && is_int($iNewLength)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iNewLength >= strlen($sString)', vs(isset($this), get_defined_vars()) );

        return str_pad($sString, $iNewLength, $sPaddingString, STR_PAD_LEFT);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds characters to the end of a string to make it grow to a specified length and returns the new string.
     *
     * If the input string is already of the targeted length, it's returned unmodified.
     *
     * @param  string $sString The string to be padded.
     * @param  string $sPaddingString The string to be used for padding.
     * @param  int $iNewLength The length of the padded string.
     *
     * @return string The padded string.
     */

    public static function padEnd ($sString, $sPaddingString, $iNewLength)
    {
        assert( 'is_cstring($sString) && is_cstring($sPaddingString) && is_int($iNewLength)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iNewLength >= strlen($sString)', vs(isset($this), get_defined_vars()) );

        return str_pad($sString, $iNewLength, $sPaddingString, STR_PAD_RIGHT);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the start of a string, if found searching case-sensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $sString The string to be stripped.
     * @param  string|array|map $xPrefixOrPrefixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripStart ($sString, $xPrefixOrPrefixes)
    {
        assert( 'is_cstring($sString) && (is_cstring($xPrefixOrPrefixes) || is_collection($xPrefixOrPrefixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($xPrefixOrPrefixes) )
        {
            if ( self::startsWith($sString, $xPrefixOrPrefixes) )
            {
                $sString = self::substr($sString, strlen($xPrefixOrPrefixes));
            }
        }
        else  // a collection
        {
            foreach ($xPrefixOrPrefixes as $sPrefix)
            {
                assert( 'is_cstring($sPrefix)', vs(isset($this), get_defined_vars()) );
                if ( self::startsWith($sString, $sPrefix) )
                {
                    $sString = self::substr($sString, strlen($sPrefix));
                }
            }
        }
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the start of a string, if found searching case-insensitively,
     * and returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $sString The string to be stripped.
     * @param  string|array|map $xPrefixOrPrefixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripStartCi ($sString, $xPrefixOrPrefixes)
    {
        assert( 'is_cstring($sString) && (is_cstring($xPrefixOrPrefixes) || is_collection($xPrefixOrPrefixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($xPrefixOrPrefixes) )
        {
            if ( self::startsWithCi($sString, $xPrefixOrPrefixes) )
            {
                $sString = self::substr($sString, strlen($xPrefixOrPrefixes));
            }
        }
        else  // a collection
        {
            foreach ($xPrefixOrPrefixes as $sPrefix)
            {
                assert( 'is_cstring($sPrefix)', vs(isset($this), get_defined_vars()) );
                if ( self::startsWithCi($sString, $sPrefix) )
                {
                    $sString = self::substr($sString, strlen($sPrefix));
                }
            }
        }
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the end of a string, if found searching case-sensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $sString The string to be stripped.
     * @param  string|array|map $xSuffixOrSuffixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripEnd ($sString, $xSuffixOrSuffixes)
    {
        assert( 'is_cstring($sString) && (is_cstring($xSuffixOrSuffixes) || is_collection($xSuffixOrSuffixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($xSuffixOrSuffixes) )
        {
            if ( self::endsWith($sString, $xSuffixOrSuffixes) )
            {
                $sString = self::substr($sString, 0, strlen($sString) - strlen($xSuffixOrSuffixes));
            }
        }
        else  // a collection
        {
            foreach ($xSuffixOrSuffixes as $sSuffix)
            {
                assert( 'is_cstring($sSuffix)', vs(isset($this), get_defined_vars()) );
                if ( self::endsWith($sString, $sSuffix) )
                {
                    $sString = self::substr($sString, 0, strlen($sString) - strlen($sSuffix));
                }
            }
        }
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the end of a string, if found searching case-insensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $sString The string to be stripped.
     * @param  string|array|map $xSuffixOrSuffixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripEndCi ($sString, $xSuffixOrSuffixes)
    {
        assert( 'is_cstring($sString) && (is_cstring($xSuffixOrSuffixes) || is_collection($xSuffixOrSuffixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($xSuffixOrSuffixes) )
        {
            if ( self::endsWithCi($sString, $xSuffixOrSuffixes) )
            {
                $sString = self::substr($sString, 0, strlen($sString) - strlen($xSuffixOrSuffixes));
            }
        }
        else  // a collection
        {
            foreach ($xSuffixOrSuffixes as $sSuffix)
            {
                assert( 'is_cstring($sSuffix)', vs(isset($this), get_defined_vars()) );
                if ( self::endsWithCi($sString, $sSuffix) )
                {
                    $sString = self::substr($sString, 0, strlen($sString) - strlen($sSuffix));
                }
            }
        }
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts a string into another string and returns the new string.
     *
     * As a special case, the position of insertion can be equal to the target string's length.
     *
     * @param  string $sString The target string.
     * @param  int $iAtPos The position of insertion. This is the desired position of the first character of the
     * inserted string in the resulting string.
     * @param  string $sInsertString The string to be inserted.
     *
     * @return string The resulting string.
     */

    public static function insert ($sString, $iAtPos, $sInsertString)
    {
        assert( 'is_cstring($sString) && is_int($iAtPos) && is_cstring($sInsertString)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iAtPos && $iAtPos <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        return self::substr($sString, 0, $iAtPos) . $sInsertString . self::substr($sString, $iAtPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, replaces a substring with a specified string, and returns the new string.
     *
     * @param  string $sString The source string.
     * @param  int $iStartPos The position of the first character in the substring to be replaced.
     * @param  int $iLength The length of the substring to be replaced.
     * @param  string $sWith The replacement string.
     *
     * @return string The resulting string.
     */

    public static function replaceSubstring ($sString, $iStartPos, $iLength, $sWith)
    {
        assert( 'is_cstring($sString) && is_int($iStartPos) && is_int($iLength) && is_cstring($sWith)',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $iStartPos && $iStartPos < strlen($sString)) || ' .
                '($iStartPos == strlen($sString) && $iLength == 0)', vs(isset($this), get_defined_vars()) );
        assert( '$iLength >= 0 && $iStartPos + $iLength <= strlen($sString)', vs(isset($this), get_defined_vars()) );

        return self::substr($sString, 0, $iStartPos) . $sWith . self::substr($sString, $iStartPos + $iLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, replaces a substring with a specified string, with both starting and ending positions specified,
     * and returns the new string.
     *
     * @param  string $sString The source string.
     * @param  int $iStartPos The position of the first character in the substring to be replaced.
     * @param  int $iEndPos The position of the character that *follows* the last character in the substring to be
     * replaced.
     * @param  string $sWith The replacement string.
     *
     * @return string The resulting string.
     */

    public static function replaceSubstringByRange ($sString, $iStartPos, $iEndPos, $sWith)
    {
        assert( 'is_cstring($sString) && is_int($iStartPos) && is_int($iEndPos) && is_cstring($sWith)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstring($sString, $iStartPos, $iEndPos - $iStartPos, $sWith);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a substring from a string and returns the new string.
     *
     * @param  string $sString The source string.
     * @param  int $iStartPos The position of the first character in the substring to be removed.
     * @param  int $iLength The length of the substring to be removed.
     *
     * @return string The resulting string.
     */

    public static function removeSubstring ($sString, $iStartPos, $iLength)
    {
        assert( 'is_cstring($sString) && is_int($iStartPos) && is_int($iLength)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstring($sString, $iStartPos, $iLength, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a substring from a string, with both starting and ending positions specified, and returns the new
     * string.
     *
     * @param  string $sString The source string.
     * @param  int $iStartPos The position of the first character in the substring to be removed.
     * @param  int $iEndPos The position of the character that *follows* the last character in the substring to be
     * removed.
     *
     * @return string The resulting string.
     */

    public static function removeSubstringByRange ($sString, $iStartPos, $iEndPos)
    {
        assert( 'is_cstring($sString) && is_int($iStartPos) && is_int($iEndPos)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstringByRange($sString, $iStartPos, $iEndPos, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a substring in a string with a specified string, searching case-sensitively, and
     * returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $sString The source string.
     * @param  string $sWhat The searched substring.
     * @param  string $sWith The replacement string.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replace ($sString, $sWhat, $sWith, &$riQuantity = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhat) && is_cstring($sWith)',
            vs(isset($this), get_defined_vars()) );

        return str_replace($sWhat, $sWith, $sString, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a substring in a string with a specified string, searching case-insensitively, and
     * returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $sString The source string.
     * @param  string $sWhat The searched substring.
     * @param  string $sWith The replacement string.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replaceCi ($sString, $sWhat, $sWith, &$riQuantity = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhat) && is_cstring($sWith)',
            vs(isset($this), get_defined_vars()) );

        return str_ireplace($sWhat, $sWith, $sString, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a substring from a string, searching case-sensitively, and returns the new string,
     * optionally reporting the number of removals made.
     *
     * @param  string $sString The source string.
     * @param  string $sWhat The searched substring.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return string The resulting string.
     */

    public static function remove ($sString, $sWhat, &$riQuantity = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhat)', vs(isset($this), get_defined_vars()) );
        return self::replace($sString, $sWhat, "", $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a substring from a string, searching case-insensitively, and returns the new string,
     * optionally reporting the number of removals made.
     *
     * @param  string $sString The source string.
     * @param  string $sWhat The searched substring.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return string The resulting string.
     */

    public static function removeCi ($sString, $sWhat, &$riQuantity = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sWhat)', vs(isset($this), get_defined_vars()) );
        return self::replaceCi($sString, $sWhat, "", $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Randomizes the positions of the characters in a string and returns the new string.
     *
     * @param  string $sString The string to be shuffled.
     *
     * @return string The shuffled string.
     */

    public static function shuffle ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        // The Fisher-Yates algorithm.
        for ($i = strlen($sString) - 1; $i > 0; $i--)
        {
            $iExchangeIdx = CMathi::intervalRandom(0, $i);
            $sSave = $sString[$iExchangeIdx];
            $sString[$iExchangeIdx] = $sString[$i];
            $sString[$i] = $sSave;
        }
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Wraps the text in a string to a specified width and returns the new string.
     *
     * @param  string $sString The string with the text to be wrapped.
     * @param  int $iWidth The wrapping width, in characters.
     * @param  bool $bBreakSpacelessLines **OPTIONAL. Default is** `false`. Tells whether to break any line that
     * exceeds the wrapping width while doesn't contain any spaces at which it could be broken.
     * @param  string $sNewline **OPTIONAL. Default is** LF (0x0A). The newline character(s) to be used for making
     * new lines during the wrapping.
     *
     * @return string The wrapped text.
     */

    public static function wordWrap ($sString, $iWidth, $bBreakSpacelessLines = false, $sNewline = self::NEWLINE)
    {
        assert( 'is_cstring($sString) && is_int($iWidth) && is_bool($bBreakSpacelessLines) && is_cstring($sNewline)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iWidth > 0', vs(isset($this), get_defined_vars()) );

        return wordwrap($sString, $iWidth, $sNewline, $bBreakSpacelessLines);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a decimal integer into the corresponding hexadecimal integer and returns it as another
     * string.
     *
     * @param  string $sNumber The string with the number to be converted.
     *
     * @return string The string with the converted number.
     */

    public static function decToHex ($sNumber)
    {
        assert( 'is_cstring($sNumber)', vs(isset($this), get_defined_vars()) );
        assert( 'preg_match("/^[0-9]+\\\\z/", $sNumber) === 1', vs(isset($this), get_defined_vars()) );

        return strtoupper(dechex($sNumber));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a hexadecimal integer into the corresponding decimal integer and returns it as another
     * string.
     *
     * The input string may be prefixed with "0x".
     *
     * @param  string $sNumber The string with the number to be converted.
     *
     * @return string The string with the converted number.
     */

    public static function hexToDec ($sNumber)
    {
        assert( 'is_cstring($sNumber)', vs(isset($this), get_defined_vars()) );
        assert( 'preg_match("/^(0x)?[0-9A-F]+\\\\z/i", $sNumber) === 1', vs(isset($this), get_defined_vars()) );

        return (string)hexdec($sNumber);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with an arbitrary-base integer into the corresponding integer in a different base and returns
     * it as another string.
     *
     * The input string may be prefixed with "0x" for the source base of 16.
     *
     * @param  string $sNumber The string with the number to be converted.
     * @param  int $iFromBase The source base. Can be a number in the range from 2 to 36.
     * @param  int $iToBase The destination base. Can be a number in the range from 2 to 36.
     *
     * @return string The string with the converted number.
     */

    public static function numberToBase ($sNumber, $iFromBase, $iToBase)
    {
        assert( 'is_cstring($sNumber) && is_int($iFromBase) && is_int($iToBase)',
            vs(isset($this), get_defined_vars()) );
        assert( '2 <= $iFromBase && $iFromBase <= 36', vs(isset($this), get_defined_vars()) );
        assert( '2 <= $iToBase && $iToBase <= 36', vs(isset($this), get_defined_vars()) );

        return strtoupper(base_convert($sNumber, $iFromBase, $iToBase));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Repeats a string for a specified number of times and returns the resulting string.
     *
     * For instance, the string of "a" repeated three times would result in "aaa".
     *
     * @param  string $sString The string to be repeated.
     * @param  int $iTimes The number of times for the string to be repeated.
     *
     * @return string The resulting string.
     */

    public static function repeat ($sString, $iTimes)
    {
        assert( 'is_cstring($sString) && is_int($iTimes)', vs(isset($this), get_defined_vars()) );
        assert( '$iTimes > 0 || (self::isEmpty($sString) && $iTimes == 0)', vs(isset($this), get_defined_vars()) );

        return str_repeat($sString, $iTimes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
