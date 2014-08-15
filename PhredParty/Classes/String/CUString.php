<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The hub for static methods that take care of Unicode strings and can provide the same quality of service to ASCII
 * strings.
 *
 * **In the OOP mode, you would likely never need to use this class.**
 *
 * The default encoding of a Unicode string is UTF-8. Since the ASCII charset is a subset of Unicode and the ASCII
 * encoding is compatible with UTF-8, it allows ASCII strings to be processed not only with the methods of the CString
 * class but also with the twin-looking methods of this class, which increases the overall consistency.
 *
 * When making a choice between Unicode and ASCII strings to be used for a particular purpose, you would stick with
 * Unicode by default unless you are sure that the strings will never need to contain characters from a language other
 * than English or any characters or punctuation outside the textual scope of ASCII. Only Unicode is such a linguist
 * that it can comprehend the whole variety of human languages with their letters, punctuations, and symbols of every
 * sort, as well as the rules of handling such text. By this virtue, Unicode strings usually carry all text that is
 * meant to reach the eyes of a human being at any stage. ASCII, however, is limited to just English and the basic
 * punctuation that you can see on a Latin-script keyboard. The advantage of ASCII strings, on the other hand, is that
 * they let string operations perform bit faster.
 *
 * From the PHP's perspective, ASCII and Unicode strings are creatures of the same kind and are all strings of bytes.
 * But how those bytes are treated determines whether we are talking ASCII or Unicode. When dealing with PHP's strings,
 * you use the CString class to treat a string as an ASCII one and the CUString class to see it through the Unicode
 * glasses, either it's a truly Unicode string or an ASCII string that needs to be treated as such.
 *
 * Practically all methods that you can see in the CString class are present in the CUString class too and are
 * accepting the same (non-optional) parameters, so operating on strings with the CString class is forward-compatible
 * with the CUString class. It's only the `compare...` methods on which ASCII and Unicode don't share the same
 * viewpoint as to how they should behave. ASCII prefers to put forward uppercase when comparing strings to find out
 * the order in which they should appear, while Unicode puts forward lowercase, which however is something you can
 * change with a collation option when comparing Unicode strings.
 *
 * *The next paragraph dives deeper into Unicode and doesn't need to be read before you can start with the class.*
 *
 * Different from an ASCII string where any character is a single byte, a Unicode character or, as it's often called, a
 * Unicode *code point* can "sit on multiple chairs" and span over up to four bytes when encoded in UTF-8. But if you
 * looked at the bytes of any characters from the ASCII charset present in a UTF-8 string, you would see them appear
 * identical to ASCII, so any ASCII string is also a valid UTF-8 string. A Unicode character, when displayed on a
 * screen, can be represented not only by multiple bytes but also by multiple code points. It's a peculiarity of
 * Unicode that the code point of a specific Latin letter followed by the code point of a specific accent mark is
 * displayed as that letter with that accent mark on top, and not as two separate pieces of graphics. Even though there
 * may exist a code point specifically dedicated to the combination of that very letter with that very accent mark,
 * having the combination as two code points instead of a single one is perfectly acceptable in Unicode. Just as a side
 * note, code points that can blob together in this way are called *combining characters* and ready-made characters are
 * called *precomposed characters*. What this means is that, even if two strings differ at the byte level, they may
 * look exactly the same on the screen and thus be equal in every sense. When combinations of code points in two
 * strings have equal meanings, such strings are called *canonically equivalent* in Unicode and, if you were to compare
 * such strings using `equals` method of this class, it would surely return `true`. But sometimes the byte
 * representation of a Unicode string does matter or certain representation is preferred. This is when Unicode
 * *normalization* comes into play. A string where every character is stored with the minimum possible number of code
 * points is said to be normalized to the normal form of NFC ("composed by equivalence"), and the normal form of a
 * string that is maximally stretched in terms of code points is NFD ("decomposed by equivalence"). Some characters and
 * character sequences are also said to be *compatible* with each other, for instance "ﬄ" (single character) and "ffl"
 * (three characters), which adds the second dimension to the normalization and in total makes up four possible normal
 * forms, with the other two being NFKC and NFKD. To get to NFKC or NFKD, the characters are first decomposed by
 * compatibility ("K") and then either composed ("C") or decomposed ("D") by equivalence.
 */

// Method signatures:
//   static bool isValid ($sString)
//   static string sanitize ($sString)
//   static string normalize ($sString, $eForm = self::NF_C)
//   static bool isNormalized ($sString, $eForm = self::NF_C)
//   static string fromBool10 ($bValue)
//   static string fromBoolTf ($bValue)
//   static string fromBoolYn ($bValue)
//   static string fromBoolOo ($bValue)
//   static string fromInt ($iValue)
//   static string fromFloat ($fValue)
//   static string fromCharCode ($iCode)
//   static string fromCharCodeHex ($sCode)
//   static string fromCharCodeEsc ($sCode)
//   static string fromEscString ($sString)
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
//   static string toCharCodeEsc ($sChar)
//   static string toEscString ($sString)
//   static int length ($sString)
//   static bool isEmpty ($sString)
//   static string charAt ($sString, $iPos)
//   static void setCharAt (&$rsString, $iPos, $sChar)
//   static bool equals ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT)
//   static bool equalsCi ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT)
//   static int compare ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
//   static int compareCi ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT,
//     CULocale $oInLocale = null)
//   static int compareNat ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT,
//     CULocale $oInLocale = null)
//   static int compareNatCi ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT,
//     CULocale $oInLocale = null)
//   static int levenDist ($sString, $sToString, $bTransliterate = true)
//   static string metaphoneKey ($sString, $bTransliterate = true)
//   static int metaphoneDist ($sString, $sToString, $bTransliterate = true)
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
//   static string wordWrap ($sString, $iWidth, $bfWrappingFlags = self::WRAPPING_DEFAULT, $sNewline = self::NEWLINE)
//   static string decToHex ($sNumber)
//   static string hexToDec ($sNumber)
//   static string numberToBase ($sNumber, $iFromBase, $iToBase)
//   static string transliterate ($sString, $sFromScript, $sToScript)
//   static string transliterateFromAny ($sString, $sToScript)
//   static string applyPublishingFilter ($sString)
//   static string halfwidthToFullwidth ($sString)
//   static string fullwidthToHalfwidth ($sString)
//   static string transform ($sString, $sTransform)
//   static string repeat ($sString, $iTimes)
//   static bool hasCjkChar ($sString)

class CUString extends CRootClass implements IEqualityAndOrderStatic
{
    // The Unicode normal forms.
    /**
     * `enum` Unicode NFC normal form: Canonical Composition. To normalize an arbitrary Unicode string to the NFC form,
     * characters are decomposed and then recomposed by canonical equivalence.
     *
     * @var enum
     */
    const NF_C = 0;
    /**
     * `enum` Unicode NFD normal form: Canonical Decomposition. To normalize an arbitrary Unicode string to the NFD
     * form, characters are decomposed by canonical equivalence and multiple combining characters are arranged in a
     * specific order, but not composed.
     *
     * @var enum
     */
    const NF_D = 1;
    /**
     * `enum` Unicode NFKC normal form: Compatibility Composition. To normalize an arbitrary Unicode string to the NFKC
     * form, characters are decomposed by compatibility and then recomposed by canonical equivalence.
     *
     * @var enum
     */
    const NF_KC = 2;
    /**
     * `enum` Unicode NFKD normal form: Compatibility Decomposition. To normalize an arbitrary Unicode string to the
     * NFKD form, characters are decomposed by compatibility and multiple combining characters are arranged in a
     * specific order, but not composed.
     *
     * @var enum
     */
    const NF_KD = 3;

    // The default newline format and other common newline formats.
    /**
     * `string` The default newline format, which is LF (U+000A).
     *
     * @var string
     */
    const NEWLINE = self::NEWLINE_LF;
    /**
     * `string` LF newline (U+000A). Used by Linux/Unix and OS X.
     *
     * @var string
     */
    const NEWLINE_LF = CString::NEWLINE_LF;
    /**
     * `string` CRLF newline (U+000D, U+000A). Used by Windows.
     *
     * @var string
     */
    const NEWLINE_CRLF = CString::NEWLINE_CRLF;
    /**
     * `string` CR newline (U+000D).
     *
     * @var string
     */
    const NEWLINE_CR = CString::NEWLINE_CR;
    /**
     * `string` Unicode's Line Separator (U+2028).
     *
     * @var string
     */
    const NEWLINE_LS = "\xE2\x80\xA8";
    /**
     * `string` Unicode's Paragraph Separator (U+2029).
     *
     * @var string
     */
    const NEWLINE_PS = "\xE2\x80\xA9";

    // Collation flags.
    /**
     * `bitfield` None of the below collation flags (`0`). Accents and marks are not ignored when comparing Unicode
     * strings, neither are whitespace characters, vertical space characters, punctuation characters, and symbols,
     * while lowercase goes ahead of uppercase, and the "French" collation is not used.
     *
     * @var bitfield
     */
    const COLLATION_DEFAULT = CBitField::ALL_UNSET;
    /**
     * `bitfield` Ignore accents and other marks when comparing Unicode strings.
     *
     * @var bitfield
     */
    const COLLATION_IGNORE_ACCENTS = CBitField::SET_0;
    /**
     * `bitfield` Ignore whitespace, vertical space, punctuation, and symbols when comparing Unicode strings, such as
     * , . : ; ! ? - _ ' " @ # % & * ( ) [ ] { } \ (but not ~ $ ^ = + | / < > ` ).
     *
     * @var bitfield
     */
    const COLLATION_IGNORE_NONWORD = CBitField::SET_1;
    /**
     * `bitfield` Uppercase should go ahead of lowercase when comparing Unicode strings to determine their order.
     *
     * @var bitfield
     */
    const COLLATION_UPPERCASE_FIRST = CBitField::SET_2;
    /**
     * `bitfield` Use the "French" collation when comparing Unicode strings to determine their order.
     *
     * @var bitfield
     */
    const COLLATION_FRENCH = CBitField::SET_3;

    // Word wrapping flags.
    /**
     * `bitfield` None of the below wrapping flags (`0`). Overly lengthy but spaceless lines do not get broken, lines
     * in a wrapped text are not allowed to end with whitespace but allowed to start with whitespace, and characters
     * from the Chinese, Japanese, and Korean scripts do not have any influence on how spaceless lines are treated.
     *
     * @var bitfield
     */
    const WRAPPING_DEFAULT = CBitField::ALL_UNSET;
    /**
     * `bitfield` Break any line that exceeds the wrapping width while doesn't contain any spaces at which it could be
     * broken.
     *
     * @var bitfield
     */
    const WRAPPING_BREAK_SPACELESS_LINES = CBitField::SET_0;
    /**
     * `bitfield` Allow lines in the wrapped text to end with one or more whitespace characters from the source text.
     *
     * @var bitfield
     */
    const WRAPPING_ALLOW_TRAILING_SPACES = CBitField::SET_1;
    /**
     * `bitfield` Disallow lines in the wrapped text to start with whitespace characters from the source text.
     *
     * @var bitfield
     */
    const WRAPPING_DISALLOW_LEADING_SPACES = CBitField::SET_2;
    /**
     * `bitfield` Even if overly lengthy but spaceless lines were told to break with `WRAPPING_BREAK_SPACELESS_LINES`
     * flag, this flag tells not to break any line that at the point of destined break contains a character from the
     * Chinese, Japanese, or Korean scripts.
     *
     * @var bitfield
     */
    const WRAPPING_DONT_BREAK_SPACELESS_CJK_ENDING_LINES = CBitField::SET_3;

    /**
     * `string` The regular expression pattern used in trimming and spacing normalization.
     *
     * @var string
     */
    const TRIMMING_AND_SPACING_NORM_SUBJECT_RE = "\\p{Z}|\\p{C}";

    /**
     * `string` The regular expression pattern used in newline normalization.
     *
     * @var string
     */
    const NL_NORM_SUBJECT_RE = "\\R";

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is a valid Unicode string encoded in UTF-8.
     *
     * @param  string $sString The string to be looked into.
     *
     * @return bool `true` if the string is a valid Unicode string encoded in UTF-8, `false` otherwise.
     */

    public static function isValid ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $sPattern = "/^(" .
            "[\\x00-\\x7F]" .                        // ASCII (all bytes)
            "|[\\xC2-\\xDF][\\x80-\\xBF]" .          // non-overlong 2-byte
            "|\\xE0[\\xA0-\\xBF][\\x80-\\xBF]" .     // excluding overlongs
            "|[\\xE1-\\xEC\\xEE][\\x80-\\xBF]{2}" .  // 3-byte, but exclude U+FFFE and U+FFFF
            "|\\xEF[\\x80-\\xBE][\\x80-\\xBF]" .
            "|\\xEF\\xBF[\\x80-\\xBD]" .
            "|\\xED[\\x80-\\x9F][\\x80-\\xBF]" .     // excluding surrogates
            "|\\xF0[\\x90-\\xBF][\\x80-\\xBF]{2}" .  // planes 1-3
            "|[\\xF1-\\xF3][\\x80-\\xBF]{3}" .       // planes 4-15
            "|\\xF4[\\x80-\\x8F][\\x80-\\xBF]{2}" .  // plane 16
            ")*\\z/";
        return CRegex::find($sString, $sPattern);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any invalid byte in a Unicode string with a Replacement Character (U+FFFD), which is "�", and returns
     * the new string.
     *
     * @param  string $sString The string to be sanitized.
     *
     * @return string The sanitized string.
     */

    public static function sanitize ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return CEString::convert($sString, CEString::UTF8, CEString::UTF8);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes a string to a specified Unicode normal form and returns the new string.
     *
     * @param  string $sString The string to be normalized.
     * @param  enum $eForm **OPTIONAL. Default is** `NF_C`. The Unicode normal form of the normalized string. The
     * possible normal forms are `NF_C`, `NF_D`, `NF_KC`, and `NF_KD` (see [Summary](#summary)).
     *
     * @return string The normalized string.
     */

    public static function normalize ($sString, $eForm = self::NF_C)
    {
        assert( 'is_cstring($sString) && is_enum($eForm)', vs(isset($this), get_defined_vars()) );

        $sString = Normalizer::normalize($sString, self::normFormToNc($eForm));
        if ( is_cstring($sString) )
        {
            return $sString;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is normalized according to a specified Unicode normal form.
     *
     * @param  string $sString The string to be looked into.
     * @param  enum $eForm **OPTIONAL. Default is** `NF_C`. The Unicode normal form to be verified against. The
     * possible normal forms are `NF_C`, `NF_D`, `NF_KC`, and `NF_KD` (see [Summary](#summary)).
     *
     * @return bool `true` if the string appears to be normalized according to the normal form specified, `false`
     * otherwise.
     */

    public static function isNormalized ($sString, $eForm = self::NF_C)
    {
        assert( 'is_cstring($sString) && is_enum($eForm)', vs(isset($this), get_defined_vars()) );
        return Normalizer::isNormalized($sString, self::normFormToNc($eForm));
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
     * Returns a character by its code point specified as an integer.
     *
     * @param  int $iCode The Unicode code point.
     *
     * @return string The Unicode character with the code point specified.
     */

    public static function fromCharCode ($iCode)
    {
        assert( 'is_int($iCode)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iCode && $iCode < 0xFFFE', vs(isset($this), get_defined_vars()) );

        return json_decode("\"\\u" . CString::padStart(CString::decToHex(CString::fromInt($iCode)), "0", 4) . "\"");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code point specified as a hexadecimal string.
     *
     * For instance, "0041" or "41" would return "A".
     *
     * @param  string $sCode The Unicode code point. This can be a string with up to four hexadecimal digits.
     *
     * @return string The Unicode character with the code point specified.
     */

    public static function fromCharCodeHex ($sCode)
    {
        assert( 'is_cstring($sCode)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sCode, "/^[0-9A-F]{1,4}(?<!FFFE|FFFF)\\\\z/i")', vs(isset($this), get_defined_vars()) );

        return json_decode("\"\\u" . CString::padStart($sCode, "0", 4) . "\"");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code point specified in an escaped fashion.
     *
     * For instance, "\u0041" would return "A".
     *
     * @param  string $sCode The Unicode code point prefixed with "\u". The number of hexadecimal digits in the string
     * should be four.
     *
     * @return string The Unicode character with the code point specified.
     */

    public static function fromCharCodeEsc ($sCode)
    {
        assert( 'is_cstring($sCode)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sCode, "/^\\\\\\\\u((?i)[0-9A-F]{4}(?<!FFFE|FFFF))\\\\z/")',
            vs(isset($this), get_defined_vars()) );

        return json_decode("\"\\u" . CString::padStart(CString::substr($sCode, 2), "0", 4) . "\"");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Unescapes any escaped Unicode characters in a string and returns the new string.
     *
     * For instance, "\u0041bc" would return "Abc".
     *
     * @param  string $sString The string to be unescaped.
     *
     * @return string The unescaped string.
     */

    public static function fromEscString ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($sString, "hex/java-any");
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
            CString::equals($sString, "1") ||
            CString::equalsCi($sString, "true") ||
            CString::equalsCi($sString, "yes") ||
            CString::equalsCi($sString, "on") );
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
        return CString::equals($sString, "1");
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
        return CString::equalsCi($sString, "true");
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
        return CString::equalsCi($sString, "yes");
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
        return CString::equalsCi($sString, "on");
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
        return CString::toIntFromHex($sString);
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
        return CString::toIntFromBase($sString, $iBase);
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
     * Returns the code point of a specified character, as an integer.
     *
     * @param  string $sChar The character.
     *
     * @return int The Unicode code point of the character.
     */

    public static function toCharCode ($sChar)
    {
        assert( 'is_cstring($sChar)', vs(isset($this), get_defined_vars()) );
        assert( 'self::length($sChar) == 1', vs(isset($this), get_defined_vars()) );

        return CString::toInt(CString::hexToDec(CString::substr(self::toEscString($sChar), 2)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code point of a specified character, as a hexadecimal string.
     *
     * The returned string is always four characters in length.
     *
     * For instance, "A" would return "0041".
     *
     * @param  string $sChar The character.
     *
     * @return string The Unicode code point of the character.
     */

    public static function toCharCodeHex ($sChar)
    {
        assert( 'is_cstring($sChar)', vs(isset($this), get_defined_vars()) );
        assert( 'self::length($sChar) == 1', vs(isset($this), get_defined_vars()) );

        return CString::substr(self::toEscString($sChar), 2);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code point of a specified character, as an escaped string.
     *
     * For instance, "A" would return "\u0041".
     *
     * @param  string $sChar The character.
     *
     * @return string The escaped character.
     */

    public static function toCharCodeEsc ($sChar)
    {
        assert( 'is_cstring($sChar)', vs(isset($this), get_defined_vars()) );
        assert( 'self::length($sChar) == 1', vs(isset($this), get_defined_vars()) );

        return self::toEscString($sChar);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Escapes all the characters in a string and returns the new string.
     *
     * @param  string $sString The string to be escaped.
     *
     * @return string The escaped string.
     */

    public static function toEscString ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($sString, "hex/java");
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

        $xRes = grapheme_strlen($sString);
        if ( is_int($xRes) )
        {
            return $xRes;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return 0;
        }
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
        return ( self::length($sString) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a string, returns the character located at a specified position.
     *
     * @param  string $sString The string to be looked into.
     * @param  int $iPos The character's position.
     *
     * @return string The character located at the position specified.
     */

    public static function charAt ($sString, $iPos)
    {
        assert( 'is_cstring($sString) && is_int($iPos)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iPos && $iPos < self::length($sString)', vs(isset($this), get_defined_vars()) );

        return grapheme_substr($sString, $iPos, 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets a character in a string.
     *
     * The string is modified in-place.
     *
     * @param  reference $rsString The string to be modified.
     * @param  int $iPos The position of the character to be set.
     * @param  string $sChar The replacement character.
     *
     * @return void
     */

    public static function setCharAt (&$rsString, $iPos, $sChar)
    {
        assert( 'is_cstring($rsString) && is_int($iPos) && is_cstring($sChar)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iPos && $iPos < self::length($rsString)', vs(isset($this), get_defined_vars()) );
        assert( 'self::length($sChar) == 1', vs(isset($this), get_defined_vars()) );

        $rsString = self::substr($rsString, 0, $iPos) . $sChar . self::substr($rsString, $iPos + 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two strings are equal, comparing them case-sensitively.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     *
     * @return bool `true` if the two strings are equal, taking into account the letter case of the characters, and
     * `false` otherwise.
     */

    public static function equals ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString) && is_bitfield($bfCollationFlags)',
            vs(isset($this), get_defined_vars()) );

        $oColl = self::collatorObject(false, false, "root", $bfCollationFlags);
        return ( $oColl->compare($sString, $sToString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two strings are equal, comparing them case-insensitively.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     *
     * @return bool `true` if the two strings are equal, ignoring the letter case of the characters, and `false`
     * otherwise.
     */

    public static function equalsCi ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString) && is_bitfield($bfCollationFlags)',
            vs(isset($this), get_defined_vars()) );

        $oColl = self::collatorObject(true, false, "root", $bfCollationFlags);
        return ( $oColl->compare($sString, $sToString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-sensitively.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     */

    public static function compare ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT,
        CULocale $oInLocale = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString) && is_bitfield($bfCollationFlags)',
            vs(isset($this), get_defined_vars()) );

        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $oColl = self::collatorObject(false, false, $sLocale, $bfCollationFlags);
        $xRes = $oColl->compare($sString, $sToString);
        if ( is_int($xRes) )
        {
            return $xRes;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return -1;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-insensitively.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public static function compareCi ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT,
        CULocale $oInLocale = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString) && is_bitfield($bfCollationFlags)',
            vs(isset($this), get_defined_vars()) );

        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $oColl = self::collatorObject(true, false, $sLocale, $bfCollationFlags);
        $xRes = $oColl->compare($sString, $sToString);
        if ( is_int($xRes) )
        {
            return $xRes;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return -1;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order, comparing the strings case-sensitively, and using natural order comparison.
     *
     * To illustrate natural order with an example, the strings "a100" and "a20" would get ordered as such with
     * `compare` method, but as "a20" and "a100" with this method, which is the order a human being would choose.
     *
     * @param  string $sString The first string for comparison.
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     */

    public static function compareNat ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT,
        CULocale $oInLocale = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString) && is_bitfield($bfCollationFlags)',
            vs(isset($this), get_defined_vars()) );

        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $oColl = self::collatorObject(false, true, $sLocale, $bfCollationFlags);
        $xRes = $oColl->compare($sString, $sToString);
        if ( is_int($xRes) )
        {
            return $xRes;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return -1;
        }
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
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public static function compareNatCi ($sString, $sToString, $bfCollationFlags = self::COLLATION_DEFAULT,
        CULocale $oInLocale = null)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString) && is_bitfield($bfCollationFlags)',
            vs(isset($this), get_defined_vars()) );

        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $oColl = self::collatorObject(true, true, $sLocale, $bfCollationFlags);
        $xRes = $oColl->compare($sString, $sToString);
        if ( is_int($xRes) )
        {
            return $xRes;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return -1;
        }
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
     * @param  bool $bTransliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the strings into
     * the Latin script and then flatten them to ASCII before calculating the distance. This is what you would normally
     * wish to happen for arbitrary Unicode strings since the algorithm of calculating the Levenshtein distance is not
     * Unicode-aware. For example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return int The Levenshtein distance between the two strings.
     */

    public static function levenDist ($sString, $sToString, $bTransliterate = true)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString) && is_bool($bTransliterate)',
            vs(isset($this), get_defined_vars()) );

        $sString = CEString::flattenUnicodeToAscii($sString, $bTransliterate);
        $sToString = CEString::flattenUnicodeToAscii($sToString, $bTransliterate);
        return CString::levenDist($sString, $sToString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Metaphone key "heard" from a string.
     *
     * The algorithm used to render the [Metaphone](http://en.wikipedia.org/wiki/Metaphone) key is the first-generation
     * one.
     *
     * @param  string $sString The source string.
     * @param  bool $bTransliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the string into the
     * Latin script and then flatten it to ASCII before generating the key. Since the Metaphone algorithm is not
     * Unicode-aware, the touch of transliteration is something that any arbitrary Unicode string would wish for. For
     * example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return string The Metaphone key of the string.
     */

    public static function metaphoneKey ($sString, $bTransliterate = true)
    {
        assert( 'is_cstring($sString) && is_bool($bTransliterate)', vs(isset($this), get_defined_vars()) );

        $sString = CEString::flattenUnicodeToAscii($sString, $bTransliterate);
        return CString::metaphoneKey($sString);
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
     * @param  bool $bTransliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the strings into
     * the Latin script and then flatten them to ASCII before generating the keys. Since neither the Metaphone or
     * Levenshtein algorithm is Unicode-aware, the touch of transliteration is something that any arbitrary Unicode
     * strings would wish for. For example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return int The Levenshtein distance between the Metaphone keys of the two strings.
     */

    public static function metaphoneDist ($sString, $sToString, $bTransliterate = true)
    {
        assert( 'is_cstring($sString) && is_cstring($sToString) && is_bool($bTransliterate)',
            vs(isset($this), get_defined_vars()) );
        return CString::levenDist(self::metaphoneKey($sString, $bTransliterate),
            self::metaphoneKey($sToString, $bTransliterate));
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
        return self::handleTransform($sString, "lower");
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
        return self::handleTransform($sString, "upper");
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

        if ( self::isEmpty($sString) )
        {
            return "";
        }
        return self::toUpperCase(self::substr($sString, 0, 1)) . self::substr($sString, 1);
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
        return self::handleTransform($sString, "title");
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
        return ( grapheme_strpos($sString, $sWithString) === 0 );
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
        return ( grapheme_stripos($sString, $sWithString) === 0 );
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
        return ( grapheme_strrpos($sString, $sWithString) === self::length($sString) - self::length($sWithString) );
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
        return ( grapheme_strripos($sString, $sWithString) === self::length($sString) - self::length($sWithString) );
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
        assert( '0 <= $iStartPos && $iStartPos <= self::length($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sOfString) )
        {
            return $iStartPos;
        }
        $xRes = grapheme_strpos($sString, $sOfString, $iStartPos);
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
        assert( '0 <= $iStartPos && $iStartPos <= self::length($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sOfString) )
        {
            return $iStartPos;
        }
        $xRes = grapheme_stripos($sString, $sOfString, $iStartPos);
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
        assert( '0 <= $iStartPos && $iStartPos <= self::length($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sOfString) )
        {
            return self::length($sString);
        }
        $xRes = grapheme_strrpos($sString, $sOfString, $iStartPos);
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
        assert( '0 <= $iStartPos && $iStartPos <= self::length($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($sOfString) )
        {
            return self::length($sString);
        }
        $xRes = grapheme_strripos($sString, $sOfString, $iStartPos);
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

        $iSLength = self::length($sString);
        $iCsLength = self::length($sOfCharSet);
        for ($i0 = 0; $i0 < $iSLength; $i0++)
        {
            $bIsIn = false;
            for ($i1 = 0; $i1 < $iCsLength; $i1++)
            {
                if ( self::equals(self::charAt($sString, $i0), self::charAt($sOfCharSet, $i1)) )
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
        assert( '(0 <= $iStartPos && $iStartPos < self::length($sString)) || ' .
                '($iStartPos == self::length($sString) && (!isset($iLength) || $iLength == 0))',
            vs(isset($this), get_defined_vars()) );
        assert( '!isset($iLength) || ($iLength >= 0 && $iStartPos + $iLength <= self::length($sString))',
            vs(isset($this), get_defined_vars()) );

        $xRes;
        if ( !isset($iLength) )
        {
            $xRes = grapheme_substr($sString, $iStartPos);
        }
        else
        {
            $xRes = grapheme_substr($sString, $iStartPos, $iLength);
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
        assert( '(0 <= $iStartPos && $iStartPos < self::length($sString)) || ' .
                '(self::isEmpty($sString) && $iStartPos == 0)', vs(isset($this), get_defined_vars()) );

        $iQty = 0;
        $iSsLength = self::length($sSubstring);
        while ( true )
        {
            $iPos = self::indexOf($sString, $sSubstring, $iStartPos);
            if ( $iPos == -1 )
            {
                break;
            }
            $iQty++;
            $iStartPos = $iPos + $iSsLength;
        }
        return $iQty;
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
                    $iSLength = self::length($sString);
                    $aResStrings = CArray::make($iSLength);
                    for ($i = 0; $i < $iSLength; $i++)
                    {
                        $aResStrings[$i] = self::charAt($sString, $i);
                    }
                    return $aResStrings;
                }
            }

            $aResStrings = CArray::make(self::numSubstrings($sString, $xDelimiterOrDelimiters) + 1);
            $iStartPos = 0;
            $i = 0;
            $iDLength = self::length($xDelimiterOrDelimiters);
            while ( true )
            {
                $iEndPos = self::indexOf($sString, $xDelimiterOrDelimiters, $iStartPos);
                if ( $iEndPos != -1 )
                {
                    $aResStrings[$i++] = self::substring($sString, $iStartPos, $iEndPos);
                    $iStartPos = $iEndPos + $iDLength;
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
     * Removes all characters from the start of a string that are non-printable, such as whitespace.
     *
     * @param  string $sString The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trimStart ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return CRegex::remove($sString, "/^(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/u");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the end of a string that are non-printable, such as whitespace.
     *
     * @param  string $sString The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trimEnd ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return CRegex::remove($sString, "/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+\\z/u");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from both ends of a string that are non-printable, such as whitespace.
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
     * as whitespace, and replacing any sequence of such characters within the string with a single space character,
     * and returns the new string.
     *
     * @param  string $sString The string to be normalized.
     *
     * @return string The normalized string.
     */

    public static function normSpacing ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $sString = CRegex::remove($sString, "/^(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/u");
        $sString = CRegex::remove($sString, "/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+\\z/u");
        $sString = CRegex::replace($sString, "/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/u", " ");
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the newlines in a string by replacing any newline that is not an LF newline with an LF, which is the
     * standard newline format on Linux/Unix and OS X, or with a custom newline, and returns the new string.
     *
     * The known newline formats are LF (U+000A), CRLF (U+000D, U+000A), CR (U+000D), VT (U+000B), FF (U+000C),
     * Next Line (U+0085), Line Separator (U+2028), and Paragraph Separator (U+2029).
     *
     * @param  string $sString The string to be normalized.
     * @param  string $sNewline **OPTIONAL. Default is** LF (U+000A).
     *
     * @return string The normalized string.
     */

    public static function normNewlines ($sString, $sNewline = self::NEWLINE)
    {
        assert( 'is_cstring($sString) && is_cstring($sNewline)', vs(isset($this), get_defined_vars()) );
        return CRegex::replace($sString, "/" . self::NL_NORM_SUBJECT_RE . "/u", $sNewline);
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
        assert( '$iNewLength >= self::length($sString)', vs(isset($this), get_defined_vars()) );

        $sPadding = "";
        $iDiff = $iNewLength - self::length($sString);
        for ($i = 0; $i < $iDiff; $i++)
        {
            $sPadding .= $sPaddingString;
        }
        return $sPadding . $sString;
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
        assert( '$iNewLength >= self::length($sString)', vs(isset($this), get_defined_vars()) );

        $sPadding = "";
        $iDiff = $iNewLength - self::length($sString);
        for ($i = 0; $i < $iDiff; $i++)
        {
            $sPadding .= $sPaddingString;
        }
        return $sString . $sPadding;
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
                $sString = self::substr($sString, self::length($xPrefixOrPrefixes));
            }
        }
        else  // a collection
        {
            foreach ($xPrefixOrPrefixes as $sPrefix)
            {
                assert( 'is_cstring($sPrefix)', vs(isset($this), get_defined_vars()) );
                if ( self::startsWith($sString, $sPrefix) )
                {
                    $sString = self::substr($sString, self::length($sPrefix));
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
                $sString = self::substr($sString, self::length($xPrefixOrPrefixes));
            }
        }
        else  // a collection
        {
            foreach ($xPrefixOrPrefixes as $sPrefix)
            {
                assert( 'is_cstring($sPrefix)', vs(isset($this), get_defined_vars()) );
                if ( self::startsWithCi($sString, $sPrefix) )
                {
                    $sString = self::substr($sString, self::length($sPrefix));
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
                $sString = self::substr($sString, 0, self::length($sString) - self::length($xSuffixOrSuffixes));
            }
        }
        else  // a collection
        {
            foreach ($xSuffixOrSuffixes as $sSuffix)
            {
                assert( 'is_cstring($sSuffix)', vs(isset($this), get_defined_vars()) );
                if ( self::endsWith($sString, $sSuffix) )
                {
                    $sString = self::substr($sString, 0, self::length($sString) - self::length($sSuffix));
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
                $sString = self::substr($sString, 0, self::length($sString) - self::length($xSuffixOrSuffixes));
            }
        }
        else  // a collection
        {
            foreach ($xSuffixOrSuffixes as $sSuffix)
            {
                assert( 'is_cstring($sSuffix)', vs(isset($this), get_defined_vars()) );
                if ( self::endsWithCi($sString, $sSuffix) )
                {
                    $sString = self::substr($sString, 0, self::length($sString) - self::length($sSuffix));
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
        assert( '0 <= $iAtPos && $iAtPos <= self::length($sString)', vs(isset($this), get_defined_vars()) );

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
        assert( '(0 <= $iStartPos && $iStartPos < self::length($sString)) || ' .
                '($iStartPos == self::length($sString) && $iLength == 0)', vs(isset($this), get_defined_vars()) );
        assert( '$iLength >= 0 && $iStartPos + $iLength <= self::length($sString)',
            vs(isset($this), get_defined_vars()) );

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

        if ( self::isEmpty($sWhat) )
        {
            // Special case.
            return $sString;
        }

        $sNewString = "";
        $riQuantity = 0;
        $iStartPos = 0;
        $iWhatLength = self::length($sWhat);
        while ( true )
        {
            $iEndPos = self::indexOf($sString, $sWhat, $iStartPos);
            if ( $iEndPos != -1 )
            {
                $sNewString .= self::substring($sString, $iStartPos, $iEndPos);
                $sNewString .= $sWith;
                $riQuantity++;
                $iStartPos = $iEndPos + $iWhatLength;
            }
            else
            {
                $sNewString .= self::substr($sString, $iStartPos);
                break;
            }
        }
        return $sNewString;
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

        if ( self::isEmpty($sWhat) )
        {
            // Special case.
            return $sString;
        }

        $sNewString = "";
        $riQuantity = 0;
        $iStartPos = 0;
        $iWhatLength = self::length($sWhat);
        while ( true )
        {
            $iEndPos = self::indexOfCi($sString, $sWhat, $iStartPos);
            if ( $iEndPos != -1 )
            {
                $sNewString .= self::substring($sString, $iStartPos, $iEndPos);
                $sNewString .= $sWith;
                $riQuantity++;
                $iStartPos = $iEndPos + $iWhatLength;
            }
            else
            {
                $sNewString .= self::substr($sString, $iStartPos);
                break;
            }
        }
        return $sNewString;
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
        for ($i = self::length($sString) - 1; $i > 0; $i--)
        {
            $iExchangeIdx = CMathi::intervalRandom(0, $i);
            $sSave = self::charAt($sString, $iExchangeIdx);
            self::setCharAt($sString, $iExchangeIdx, self::charAt($sString, $i));
            self::setCharAt($sString, $i, $sSave);
        }
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Wraps the text in a string to a specified width and returns the new string.
     *
     * @param  string $sString The string with the text to be wrapped.
     * @param  int $iWidth The wrapping width, in characters.
     * @param  bitfield $bfWrappingFlags **OPTIONAL. Default is** `WRAPPING_DEFAULT`. The wrapping option(s). The
     * available options are `WRAPPING_BREAK_SPACELESS_LINES`, `WRAPPING_ALLOW_TRAILING_SPACES`,
     * `WRAPPING_DISALLOW_LEADING_SPACES`, and `WRAPPING_DONT_BREAK_SPACELESS_CJK_ENDING_LINES`
     * (see [Summary](#summary)).
     * @param  string $sNewline **OPTIONAL. Default is** LF (U+000A). The newline character(s) to be used for making
     * new lines in the process of wrapping.
     *
     * @return string The wrapped text.
     */

    public static function wordWrap ($sString, $iWidth, $bfWrappingFlags = self::WRAPPING_DEFAULT,
        $sNewline = self::NEWLINE)
    {
        assert( 'is_cstring($sString) && is_int($iWidth) && is_bitfield($bfWrappingFlags) && is_cstring($sNewline)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iWidth > 0', vs(isset($this), get_defined_vars()) );

        // Constant. Newline character that is used by the input string (after newline normalization).
        $sNormNl = self::NEWLINE;

        // Constant. Determines what characters should be considered spaces.
        // A character in the "Zs" Unicode category, an HT, or a Zero Width Space, except No-break Space and Narrow
        // No-break Space.
        $sSpaceSubjectRe = "(\\p{Zs}|\\x{0009}|\\x{200B})(?<!\\x{00A0}|\\x{202F})";

        // Break enabling characters.
        // Soft Hyphen or Tibetan Mark Intersyllabic Tsheg.
        $sBreakAllowCharSubjectRe = "\\x{00AD}|\\x{0F0B}";

        // Retrieve the wrapping options.
        $bBreakSpacelessLines = CBitField::isBitSet($bfWrappingFlags, self::WRAPPING_BREAK_SPACELESS_LINES);
        $bAllowTrailingSpaces = CBitField::isBitSet($bfWrappingFlags, self::WRAPPING_ALLOW_TRAILING_SPACES);
        $bDisallowLeadingSpaces = CBitField::isBitSet($bfWrappingFlags, self::WRAPPING_DISALLOW_LEADING_SPACES);
        $bDontBreakSpacelessCjkEndingLines = CBitField::isBitSet($bfWrappingFlags,
            self::WRAPPING_DONT_BREAK_SPACELESS_CJK_ENDING_LINES);

        // Normalize newlines in the input string.
        $sString = self::normNewlines($sString, $sNormNl);
        $iNormNlLength = self::length($sNormNl);

        $sNewString = "";

        $iPos = 0;
        $iBytePos = 0;
        $iSLength = self::length($sString);
        while ( true )
        {
            $iNumCharsLeft = $iSLength - $iPos;

            // A portion begins at the very start or right after a newline, either it is native or added. The length of
            // a portion is the wrapping width or less.
            $iPortionLength = CMathi::min($iWidth, $iNumCharsLeft);
            $sPortion = self::substr($sString, $iPos, $iPortionLength);
            $iPortionByteLength = CString::length($sPortion);

            if ( $iPortionLength == $iNumCharsLeft )
            {
                // All done.
                $sNewString .= $sPortion;
                break;
            }

            // The starting position of the next portion.
            $iNextPos = $iPos + $iPortionLength;
            $iNextBytePos = $iBytePos + $iPortionByteLength;

            // Look for the first occurrence of a newline in the portion.
            $iNlPos = self::indexOf($sPortion, $sNormNl);
            if ( $iNlPos != -1 )
            {
                // This portion contains a newline, so the next portion is going to start right after this first found
                // newline.
                $iSubPLength = $iNlPos + $iNormNlLength;
                $sSubP = self::substr($sPortion, 0, $iSubPLength);
                $sNewString .= $sSubP;
                $iPos += $iSubPLength;
                $iBytePos += CString::length($sSubP);
                continue;
            }

            // There are no newlines in this portion. Before the next step, make sure that the next portion is not
            // going to start with a newline.
            if ( $iNumCharsLeft - $iPortionLength >= $iNormNlLength )
            {
                $sNextPortionBeginning = self::substr($sString, $iNextPos, $iNormNlLength);
                if ( self::indexOf($sNextPortionBeginning, $sNormNl) == 0 )
                {
                    // The next portion is going to start with a newline, so no need to break this one, regardless of
                    // whether or not it contains any spaces.
                    $sNewString .= $sPortion;
                    $iPos = $iNextPos;
                    $iBytePos = $iNextBytePos;
                    continue;
                }
            }

            // The next portion is not going to start with a newline. Look for the last occurrence of a space or
            // break-allow character in this portion.
            $iLastSubjectBytePos = CRegex::lastIndexOf($sPortion, "/($sSpaceSubjectRe)|($sBreakAllowCharSubjectRe)/u",
                0, $sFoundString);
            if ( $iLastSubjectBytePos != -1 )
            {
                // Add a newline right after this last occurring space or break-allow character.
                $sSubP = CString::substring($sPortion, 0, $iLastSubjectBytePos + CString::length($sFoundString));
                $sNewString .= $sSubP;
                $sNewString .= $sNewline;
                $iPos += self::length($sSubP);
                $iBytePos += CString::length($sSubP);
                continue;
            }

            // There are no spaces or break-allow characters in this portion. Consider adding a newline right after the
            // portion.
            if ( $bBreakSpacelessLines ||
                 (!$bDontBreakSpacelessCjkEndingLines &&
                 self::hasCjkChar(self::charAt($sPortion, $iPortionLength - 1))) )
            {
                $sNewString .= $sPortion;
                $sNewString .= $sNewline;
                $iPos = $iNextPos;
                $iBytePos = $iNextBytePos;
                continue;
            }

            // There are no spaces or break-allow characters in this portion and it should go adjacent to the upcoming
            // text. Look for the first newline, space, or break-allow character in the upcoming text.
            $iNextSubjectBytePos = CRegex::indexOf($sString,
                "/$sNormNl|(($sSpaceSubjectRe)|($sBreakAllowCharSubjectRe))(?!$sNormNl)/u", $iNextBytePos,
                $sFoundString);
            if ( $iNextSubjectBytePos != -1 )
            {
                // Found a newline, space, or a break-allow character, so the next portion is going to start right
                // after it.
                $sAfterP = CString::substring($sString, $iNextBytePos,
                    $iNextSubjectBytePos + CString::length($sFoundString));
                $sNewString .= $sPortion;
                $sNewString .= $sAfterP;
                if ( !CString::equals($sFoundString, $sNormNl) )
                {
                    // It is a space or break-allow character that was found, so add a newline after it.
                    $sNewString .= $sNewline;
                }
                $iPos += $iPortionLength + self::length($sAfterP);
                $iBytePos += $iPortionByteLength + CString::length($sAfterP);
                continue;
            }

            // There are no spaces, newlines, or break-allow characters in the upcoming text. Finalize according to the
            // breaking options.
            if ( !$bBreakSpacelessLines )
            {
                $sNewString .= $sPortion;
                $sNewString .= self::substr($sString, $iNextPos);
            }
            else
            {
                $sNewString .= $sPortion;
                $sNewString .= $sNewline;
                $iPos = $iNextPos;
                while ( true )
                {
                    $iNumCharsLeft = $iSLength - $iPos;
                    $iPortionLength = CMathi::min($iWidth, $iNumCharsLeft);
                    $sNewString .= self::substr($sString, $iPos, $iPortionLength);
                    if ( $iPortionLength == $iNumCharsLeft )
                    {
                        break;
                    }
                    $sNewString .= $sNewline;
                    $iPos += $iPortionLength;
                }
            }
            break;
        }

        if ( !$bAllowTrailingSpaces )
        {
            // Remove trailing spaces.
            $sNewString = CRegex::remove($sNewString, "/($sSpaceSubjectRe)+(?=$sNormNl|\\z)/u");
        }

        if ( $bDisallowLeadingSpaces )
        {
            // Remove leading spaces.
            $sNewString = CRegex::remove($sNewString, "/(?<=$sNormNl|^)($sSpaceSubjectRe)+/u");
        }

        return $sNewString;
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

        $sNumber = self::normalize($sNumber, self::NF_KC);
        return CString::decToHex($sNumber);
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

        $sNumber = self::normalize($sNumber, self::NF_KC);
        return CString::hexToDec($sNumber);
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

        $sNumber = self::normalize($sNumber, self::NF_KC);
        return CString::numberToBase($sNumber, $iFromBase, $iToBase);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Transliterates a string from one script to another and returns the new string.
     *
     * With this method, you can make a text written in one script sound same or similar in a different script. Some of
     * the available scripts are Latin, Arabic, Armenian, Bopomofo, Cyrillic, Georgian, Greek, Han, Hangul, Hebrew,
     * Hiragana, Indic, Jamo, Katakana, Syriac, Thaana, Thai, and ASCII. You can convert to Latin from any of the
     * scripts and vice versa. For more information, see
     * [General Transforms](http://userguide.icu-project.org/transforms/general) in the ICU User Guide and
     * [ICU Transform Demonstration](http://demo.icu-project.org/icu-bin/translit).
     *
     * @param  string $sString The string to be transliterated.
     * @param  string $sFromScript The name of the source script (case-insensitive).
     * @param  string $sToScript The name of the destination script (case-insensitive).
     *
     * @return string The transliterated string.
     */

    public static function transliterate ($sString, $sFromScript, $sToScript)
    {
        assert( 'is_cstring($sString) && is_cstring($sFromScript) && is_cstring($sToScript)',
            vs(isset($this), get_defined_vars()) );

        return self::handleTransform($sString, "$sFromScript-$sToScript");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Transliterates a string from an arbitrary script to a specified one and returns the new string.
     *
     * With this method, you can make a text written in an arbitrary script sound same or similar in another script.
     * Some of the available scripts are Latin, Arabic, Armenian, Bopomofo, Cyrillic, Georgian, Greek, Han, Hangul,
     * Hebrew, Hiragana, Indic, Jamo, Katakana, Syriac, Thaana, Thai, and ASCII. You can convert to Latin from any of
     * the scripts and vice versa. For more information, see
     * [General Transforms](http://userguide.icu-project.org/transforms/general) in the ICU User Guide and
     * [ICU Transform Demonstration](http://demo.icu-project.org/icu-bin/translit).
     *
     * @param  string $sString The string to be transliterated.
     * @param  string $sToScript The name of the destination script (case-insensitive).
     *
     * @return string The transliterated string.
     */

    public static function transliterateFromAny ($sString, $sToScript)
    {
        assert( 'is_cstring($sString) && is_cstring($sToScript)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($sString, "any-$sToScript");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts from typewriter-style punctuation to publishing-style and returns the new string.
     *
     * For example, this method converts "--" into "—" and a set of simple quotes into a set of "smart quotes".
     *
     * @param  string $sString The string to be filtered.
     *
     * @return string The filtered string.
     */

    public static function applyPublishingFilter ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($sString, "publishing");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts any half-width characters in a string to full-width and returns the new string.
     *
     * For example, this method converts "123" to "１２３".
     *
     * @param  string $sString The string to be converted.
     *
     * @return string The converted string.
     */

    public static function halfwidthToFullwidth ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($sString, "halfwidth-fullwidth");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts any full-width characters in a string to half-width and returns the new string.
     *
     * For example, this method converts "１２３" to "123".
     *
     * @param  string $sString The string to be converted.
     *
     * @return string The converted string.
     */

    public static function fullwidthToHalfwidth ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($sString, "fullwidth-halfwidth");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Transforms a string according to a specified ICU general transform.
     *
     * See [General Transforms](http://userguide.icu-project.org/transforms/general) in the ICU User Guide for the
     * information on what you can do with ICU general transforms.
     *
     * @param  string $sString The string to be transformed.
     * @param  string $sTransform The transform.
     *
     * @return string The transformed string.
     */

    public static function transform ($sString, $sTransform)
    {
        assert( 'is_cstring($sString) && is_cstring($sTransform)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($sString, $sTransform);
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

        $sResString = "";
        for ($i = 0; $i < $iTimes; $i++)
        {
            $sResString .= $sString;
        }
        return $sResString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains any characters from the Chinese, Japanese, or Korean scripts.
     *
     * @param  string $sString The string to be looked into.
     *
     * @return bool `true` if the string contains at least one CJK character, `false` otherwise.
     */

    public static function hasCjkChar ($sString)
    {

        // U+2E80-U+9FFF, U+F900-U+FAFF
        return CRegex::find($sString, "/[\\x{2E80}-\\x{9FFF}\\x{F900}-\\x{FAFF}]/u");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function collatorObject ($bCaseInsensitive, $bNaturalOrder, $sLocale, $bfCollationFlags)
    {
        // Is public to be accessible by other classes, such as CArray.

        assert( 'is_bool($bCaseInsensitive) && is_bool($bNaturalOrder) && is_cstring($sLocale) && ' .
                'is_bitfield($bfCollationFlags)', vs(isset($this), get_defined_vars()) );
        assert( 'CULocale::isValid($sLocale) || CString::equalsCi($sLocale, "root")',
            vs(isset($this), get_defined_vars()) );

        $oColl = new Collator($sLocale);

        // Case sensitivity.
        if ( !$bCaseInsensitive )
        {
            $oColl->setStrength(Collator::TERTIARY);
        }
        else
        {
            $oColl->setStrength(Collator::SECONDARY);
        }

        // Natural order.
        if ( !$bNaturalOrder )
        {
            // To be sure.
            $oColl->setAttribute(Collator::NUMERIC_COLLATION, Collator::OFF);
        }
        else
        {
            $oColl->setAttribute(Collator::NUMERIC_COLLATION, Collator::ON);
        }

        // Accents.
        if ( CBitField::isBitSet($bfCollationFlags, self::COLLATION_IGNORE_ACCENTS) )
        {
            $oColl->setStrength(Collator::PRIMARY);
            if ( !$bCaseInsensitive )
            {
                $oColl->setAttribute(Collator::CASE_LEVEL, Collator::ON);
            }
        }

        // Invisible characters, some punctuation and symbols.
        if ( CBitField::isBitSet($bfCollationFlags, self::COLLATION_IGNORE_NONWORD) )
        {
            $oColl->setAttribute(Collator::ALTERNATE_HANDLING, Collator::SHIFTED);
        }

        // Case order.
        if ( !CBitField::isBitSet($bfCollationFlags, self::COLLATION_UPPERCASE_FIRST) )
        {
            // To be sure.
            $oColl->setAttribute(Collator::CASE_FIRST, Collator::OFF);
        }
        else
        {
            $oColl->setAttribute(Collator::CASE_FIRST, Collator::UPPER_FIRST);
        }

        // "French" collation.
        if ( CBitField::isBitSet($bfCollationFlags, self::COLLATION_FRENCH) )
        {
            $oColl->setAttribute(Collator::FRENCH_COLLATION, Collator::ON);
        }

        return $oColl;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    protected static function handleTransform ($sString, $sTransform)
    {
        $oTranslit = Transliterator::create($sTransform);
        $sString = $oTranslit->transliterate($sString);
        if ( is_cstring($sString) )
        {
            return $sString;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    protected static function normFormToNc ($eForm)
    {
        switch ( $eForm )
        {
        case self::NF_C:
            return Normalizer::FORM_C;
        case self::NF_D:
            return Normalizer::FORM_D;
        case self::NF_KC:
            return Normalizer::FORM_KC;
        case self::NF_KD:
            return Normalizer::FORM_KD;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
