<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   static bool isValid ($string)
//   static string sanitize ($string)
//   static string normalize ($string, $form = self::NF_C)
//   static bool isNormalized ($string, $form = self::NF_C)
//   static string fromBool10 ($value)
//   static string fromBoolTf ($value)
//   static string fromBoolYn ($value)
//   static string fromBoolOo ($value)
//   static string fromInt ($value)
//   static string fromFloat ($value)
//   static string fromCharCode ($code)
//   static string fromCharCodeHex ($code)
//   static string fromCharCodeEsc ($code)
//   static string fromEscString ($string)
//   static bool toBool ($string)
//   static bool toBoolFrom10 ($string)
//   static bool toBoolFromTf ($string)
//   static bool toBoolFromYn ($string)
//   static bool toBoolFromOo ($string)
//   static int toInt ($string)
//   static int toIntFromHex ($string)
//   static int toIntFromBase ($string, $base)
//   static float toFloat ($string)
//   static int toCharCode ($char)
//   static string toCharCodeHex ($char)
//   static string toCharCodeEsc ($char)
//   static string toEscString ($string)
//   static int length ($string)
//   static bool isEmpty ($string)
//   static string charAt ($string, $pos)
//   static void setCharAt (&$string, $pos, $char)
//   static bool equals ($string, $toString, $collationFlags = self::COLLATION_DEFAULT)
//   static bool equalsCi ($string, $toString, $collationFlags = self::COLLATION_DEFAULT)
//   static int compare ($string, $toString, $collationFlags = self::COLLATION_DEFAULT, CULocale $inLocale = null)
//   static int compareCi ($string, $toString, $collationFlags = self::COLLATION_DEFAULT,
//     CULocale $inLocale = null)
//   static int compareNat ($string, $toString, $collationFlags = self::COLLATION_DEFAULT,
//     CULocale $inLocale = null)
//   static int compareNatCi ($string, $toString, $collationFlags = self::COLLATION_DEFAULT,
//     CULocale $inLocale = null)
//   static int levenDist ($string, $toString, $transliterate = true)
//   static string metaphoneKey ($string, $transliterate = true)
//   static int metaphoneDist ($string, $toString, $transliterate = true)
//   static string toLowerCase ($string)
//   static string toUpperCase ($string)
//   static string toUpperCaseFirst ($string)
//   static string toTitleCase ($string)
//   static bool startsWith ($string, $withString)
//   static bool startsWithCi ($string, $withString)
//   static bool endsWith ($string, $withString)
//   static bool endsWithCi ($string, $withString)
//   static int indexOf ($string, $ofString, $startPos = 0)
//   static int indexOfCi ($string, $ofString, $startPos = 0)
//   static int lastIndexOf ($string, $ofString, $startPos = 0)
//   static int lastIndexOfCi ($string, $ofString, $startPos = 0)
//   static bool find ($string, $whatString, $startPos = 0)
//   static bool findCi ($string, $whatString, $startPos = 0)
//   static bool isSubsetOf ($string, $ofCharSet)
//   static string substr ($string, $startPos, $length = null)
//   static string substring ($string, $startPos, $endPos)
//   static int numSubstrings ($string, $substring, $startPos = 0)
//   static CArray split ($string, $delimiterOrDelimiters)
//   static CArray splitIntoChars ($string)
//   static string trimStart ($string)
//   static string trimEnd ($string)
//   static string trim ($string)
//   static string normSpacing ($string)
//   static string normNewlines ($string, $newline = self::NEWLINE)
//   static string padStart ($string, $paddingString, $newLength)
//   static string padEnd ($string, $paddingString, $newLength)
//   static string stripStart ($string, $prefixOrPrefixes)
//   static string stripStartCi ($string, $prefixOrPrefixes)
//   static string stripEnd ($string, $suffixOrSuffixes)
//   static string stripEndCi ($string, $suffixOrSuffixes)
//   static string insert ($string, $atPos, $insertString)
//   static string replaceSubstring ($string, $startPos, $length, $with)
//   static string replaceSubstringByRange ($string, $startPos, $endPos, $with)
//   static string removeSubstring ($string, $startPos, $length)
//   static string removeSubstringByRange ($string, $startPos, $endPos)
//   static string replace ($string, $what, $with, &$quantity = null)
//   static string replaceCi ($string, $what, $with, &$quantity = null)
//   static string remove ($string, $what, &$quantity = null)
//   static string removeCi ($string, $what, &$quantity = null)
//   static string shuffle ($string)
//   static string wordWrap ($string, $width, $wrappingFlags = self::WRAPPING_DEFAULT, $newline = self::NEWLINE)
//   static string decToHex ($number)
//   static string hexToDec ($number)
//   static string numberToBase ($number, $fromBase, $toBase)
//   static string transliterate ($string, $fromScript, $toScript)
//   static string transliterateFromAny ($string, $toScript)
//   static string applyPublishingFilter ($string)
//   static string halfwidthToFullwidth ($string)
//   static string fullwidthToHalfwidth ($string)
//   static string transform ($string, $transform)
//   static string repeat ($string, $times)
//   static bool hasCjkChar ($string)

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
     * @param  string $string The string to be looked into.
     *
     * @return bool `true` if the string is a valid Unicode string encoded in UTF-8, `false` otherwise.
     */

    public static function isValid ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        $pattern = "/^(" .
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
        return CRegex::find($string, $pattern);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any invalid byte in a Unicode string with a Replacement Character (U+FFFD), which is "�", and returns
     * the new string.
     *
     * @param  string $string The string to be sanitized.
     *
     * @return string The sanitized string.
     */

    public static function sanitize ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return CEString::convert($string, CEString::UTF8, CEString::UTF8);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes a string to a specified Unicode normal form and returns the new string.
     *
     * @param  string $string The string to be normalized.
     * @param  enum $form **OPTIONAL. Default is** `NF_C`. The Unicode normal form of the normalized string. The
     * possible normal forms are `NF_C`, `NF_D`, `NF_KC`, and `NF_KD` (see [Summary](#summary)).
     *
     * @return string The normalized string.
     */

    public static function normalize ($string, $form = self::NF_C)
    {
        assert( 'is_cstring($string) && is_enum($form)', vs(isset($this), get_defined_vars()) );

        $string = Normalizer::normalize($string, self::normFormToNc($form));
        if ( is_cstring($string) )
        {
            return $string;
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
     * @param  string $string The string to be looked into.
     * @param  enum $form **OPTIONAL. Default is** `NF_C`. The Unicode normal form to be verified against. The
     * possible normal forms are `NF_C`, `NF_D`, `NF_KC`, and `NF_KD` (see [Summary](#summary)).
     *
     * @return bool `true` if the string appears to be normalized according to the normal form specified, `false`
     * otherwise.
     */

    public static function isNormalized ($string, $form = self::NF_C)
    {
        assert( 'is_cstring($string) && is_enum($form)', vs(isset($this), get_defined_vars()) );
        return Normalizer::isNormalized($string, self::normFormToNc($form));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "1" for `true` and as "0" for `false`.
     *
     * @param  bool $value The value to be converted.
     *
     * @return string "1" for `true`, "0" for `false`.
     */

    public static function fromBool10 ($value)
    {
        assert( 'is_bool($value)', vs(isset($this), get_defined_vars()) );
        return ( $value ) ? "1" : "0";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "true" for `true` and as "false" for `false`.
     *
     * @param  bool $value The value to be converted.
     *
     * @return string "true" for `true`, "false" for `false`.
     */

    public static function fromBoolTf ($value)
    {
        assert( 'is_bool($value)', vs(isset($this), get_defined_vars()) );
        return ( $value ) ? "true" : "false";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "yes" for `true` and as "no" for `false`.
     *
     * @param  bool $value The value to be converted.
     *
     * @return string "yes" for `true`, "no" for `false`.
     */

    public static function fromBoolYn ($value)
    {
        assert( 'is_bool($value)', vs(isset($this), get_defined_vars()) );
        return ( $value ) ? "yes" : "no";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "on" for `true` and as "off" for `false`.
     *
     * @param  bool $value The value to be converted.
     *
     * @return string "on" for `true`, "off" for `false`.
     */

    public static function fromBoolOo ($value)
    {
        assert( 'is_bool($value)', vs(isset($this), get_defined_vars()) );
        return ( $value ) ? "on" : "off";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer value into a string.
     *
     * @param  int $value The value to be converted.
     *
     * @return string The textual representation of the integer value.
     */

    public static function fromInt ($value)
    {
        assert( 'is_int($value)', vs(isset($this), get_defined_vars()) );
        return (string)$value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point value into a string.
     *
     * @param  float $value The value to be converted.
     *
     * @return string The textual representation of the floating-point value.
     */

    public static function fromFloat ($value)
    {
        assert( 'is_float($value)', vs(isset($this), get_defined_vars()) );
        return (string)$value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code point specified as an integer.
     *
     * @param  int $code The Unicode code point.
     *
     * @return string The Unicode character with the code point specified.
     */

    public static function fromCharCode ($code)
    {
        assert( 'is_int($code)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $code && $code < 0xFFFE', vs(isset($this), get_defined_vars()) );

        return json_decode("\"\\u" . CString::padStart(CString::decToHex(CString::fromInt($code)), "0", 4) . "\"");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code point specified as a hexadecimal string.
     *
     * For instance, "0041" or "41" would return "A".
     *
     * @param  string $code The Unicode code point. This can be a string with up to four hexadecimal digits.
     *
     * @return string The Unicode character with the code point specified.
     */

    public static function fromCharCodeHex ($code)
    {
        assert( 'is_cstring($code)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($code, "/^[0-9A-F]{1,4}(?<!FFFE|FFFF)\\\\z/i")', vs(isset($this), get_defined_vars()) );

        return json_decode("\"\\u" . CString::padStart($code, "0", 4) . "\"");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code point specified in an escaped fashion.
     *
     * For instance, "\u0041" would return "A".
     *
     * @param  string $code The Unicode code point prefixed with "\u". The number of hexadecimal digits in the string
     * should be four.
     *
     * @return string The Unicode character with the code point specified.
     */

    public static function fromCharCodeEsc ($code)
    {
        assert( 'is_cstring($code)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($code, "/^\\\\\\\\u((?i)[0-9A-F]{4}(?<!FFFE|FFFF))\\\\z/")',
            vs(isset($this), get_defined_vars()) );

        return json_decode("\"\\u" . CString::padStart(CString::substr($code, 2), "0", 4) . "\"");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Unescapes any escaped Unicode characters in a string and returns the new string.
     *
     * For instance, "\u0041bc" would return "Abc".
     *
     * @param  string $string The string to be unescaped.
     *
     * @return string The unescaped string.
     */

    public static function fromEscString ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "hex/java-any");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value.
     *
     * Any string that is "1", "true", "yes", or "on", regardless of the letter case, is interpreted as `true` and any
     * other string is interpreted as `false`.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "1", "true", "yes", and "on", `false` for any other string.
     */

    public static function toBool ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        return (
            CString::equals($string, "1") ||
            CString::equalsCi($string, "true") ||
            CString::equalsCi($string, "yes") ||
            CString::equalsCi($string, "on") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "1" and as `false` for "0" or any other string.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "1", `false` for any other string.
     */

    public static function toBoolFrom10 ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return CString::equals($string, "1");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "true" and as `false` for "false" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "true", `false` for any other string.
     */

    public static function toBoolFromTf ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return CString::equalsCi($string, "true");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "yes" and as `false` for "no" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "yes", `false` for any other string.
     */

    public static function toBoolFromYn ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return CString::equalsCi($string, "yes");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "on" and as `false` for "off" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "on", `false` for any other string.
     */

    public static function toBoolFromOo ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return CString::equalsCi($string, "on");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into the corresponding integer value.
     *
     * @param  string $string The string to be converted.
     *
     * @return int The integer value represented by the string.
     */

    public static function toInt ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return (int)$string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a hexadecimal integer into the corresponding integer value.
     *
     * The string may be prefixed with "0x".
     *
     * @param  string $string The string to be converted.
     *
     * @return int The integer value represented by the string.
     */

    public static function toIntFromHex ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return CString::toIntFromHex($string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with an arbitrary-base integer into the corresponding integer value.
     *
     * The string may be prefixed with "0x" for the base of 16.
     *
     * @param  string $string The string to be converted.
     * @param  int $base The base in which the integer is represented by the string. Can be a number in the range from
     * 2 to 36.
     *
     * @return int The integer value represented by the string.
     */

    public static function toIntFromBase ($string, $base)
    {
        assert( 'is_cstring($string) && is_int($base)', vs(isset($this), get_defined_vars()) );
        return CString::toIntFromBase($string, $base);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a floating-point number into the corresponding floating-point value.
     *
     * The number can be using a scientific notation, such as "2.5e-1".
     *
     * @param  string $string The string to be converted.
     *
     * @return float The floating-point value represented by the string.
     */

    public static function toFloat ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return (float)$string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code point of a specified character, as an integer.
     *
     * @param  string $char The character.
     *
     * @return int The Unicode code point of the character.
     */

    public static function toCharCode ($char)
    {
        assert( 'is_cstring($char)', vs(isset($this), get_defined_vars()) );
        assert( 'self::length($char) == 1', vs(isset($this), get_defined_vars()) );

        return CString::toInt(CString::hexToDec(CString::substr(self::toEscString($char), 2)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code point of a specified character, as a hexadecimal string.
     *
     * The returned string is always four characters in length.
     *
     * For instance, "A" would return "0041".
     *
     * @param  string $char The character.
     *
     * @return string The Unicode code point of the character.
     */

    public static function toCharCodeHex ($char)
    {
        assert( 'is_cstring($char)', vs(isset($this), get_defined_vars()) );
        assert( 'self::length($char) == 1', vs(isset($this), get_defined_vars()) );

        return CString::substr(self::toEscString($char), 2);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code point of a specified character, as an escaped string.
     *
     * For instance, "A" would return "\u0041".
     *
     * @param  string $char The character.
     *
     * @return string The escaped character.
     */

    public static function toCharCodeEsc ($char)
    {
        assert( 'is_cstring($char)', vs(isset($this), get_defined_vars()) );
        assert( 'self::length($char) == 1', vs(isset($this), get_defined_vars()) );

        return self::toEscString($char);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Escapes all the characters in a string and returns the new string.
     *
     * @param  string $string The string to be escaped.
     *
     * @return string The escaped string.
     */

    public static function toEscString ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "hex/java");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many characters there are in a string.
     *
     * @param  string $string The string to be looked into.
     *
     * @return int The string's length.
     */

    public static function length ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        $res = grapheme_strlen($string);
        if ( is_int($res) )
        {
            return $res;
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
     * @param  string $string The string to be looked into.
     *
     * @return bool `true` if the string is empty, `false` otherwise.
     */

    public static function isEmpty ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return ( self::length($string) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a string, returns the character located at a specified position.
     *
     * @param  string $string The string to be looked into.
     * @param  int $pos The character's position.
     *
     * @return string The character located at the position specified.
     */

    public static function charAt ($string, $pos)
    {
        assert( 'is_cstring($string) && is_int($pos)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $pos && $pos < self::length($string)', vs(isset($this), get_defined_vars()) );

        return grapheme_substr($string, $pos, 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets a character in a string.
     *
     * The string is modified in-place.
     *
     * @param  reference $string The string to be modified.
     * @param  int $pos The position of the character to be set.
     * @param  string $char The replacement character.
     *
     * @return void
     */

    public static function setCharAt (&$string, $pos, $char)
    {
        assert( 'is_cstring($string) && is_int($pos) && is_cstring($char)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $pos && $pos < self::length($string)', vs(isset($this), get_defined_vars()) );
        assert( 'self::length($char) == 1', vs(isset($this), get_defined_vars()) );

        $string = self::substr($string, 0, $pos) . $char . self::substr($string, $pos + 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two strings are equal, comparing them case-sensitively.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     *
     * @return bool `true` if the two strings are equal, taking into account the letter case of the characters, and
     * `false` otherwise.
     */

    public static function equals ($string, $toString, $collationFlags = self::COLLATION_DEFAULT)
    {
        assert( 'is_cstring($string) && is_cstring($toString) && is_bitfield($collationFlags)',
            vs(isset($this), get_defined_vars()) );

        $coll = self::collatorObject(false, false, "root", $collationFlags);
        return ( $coll->compare($string, $toString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two strings are equal, comparing them case-insensitively.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     *
     * @return bool `true` if the two strings are equal, ignoring the letter case of the characters, and `false`
     * otherwise.
     */

    public static function equalsCi ($string, $toString, $collationFlags = self::COLLATION_DEFAULT)
    {
        assert( 'is_cstring($string) && is_cstring($toString) && is_bitfield($collationFlags)',
            vs(isset($this), get_defined_vars()) );

        $coll = self::collatorObject(true, false, "root", $collationFlags);
        return ( $coll->compare($string, $toString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-sensitively.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     */

    public static function compare ($string, $toString, $collationFlags = self::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        assert( 'is_cstring($string) && is_cstring($toString) && is_bitfield($collationFlags)',
            vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $coll = self::collatorObject(false, false, $locale, $collationFlags);
        $res = $coll->compare($string, $toString);
        if ( is_int($res) )
        {
            return $res;
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
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public static function compareCi ($string, $toString, $collationFlags = self::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        assert( 'is_cstring($string) && is_cstring($toString) && is_bitfield($collationFlags)',
            vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $coll = self::collatorObject(true, false, $locale, $collationFlags);
        $res = $coll->compare($string, $toString);
        if ( is_int($res) )
        {
            return $res;
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
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     */

    public static function compareNat ($string, $toString, $collationFlags = self::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        assert( 'is_cstring($string) && is_cstring($toString) && is_bitfield($collationFlags)',
            vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $coll = self::collatorObject(false, true, $locale, $collationFlags);
        $res = $coll->compare($string, $toString);
        if ( is_int($res) )
        {
            return $res;
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
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public static function compareNatCi ($string, $toString, $collationFlags = self::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        assert( 'is_cstring($string) && is_cstring($toString) && is_bitfield($collationFlags)',
            vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $coll = self::collatorObject(true, true, $locale, $collationFlags);
        $res = $coll->compare($string, $toString);
        if ( is_int($res) )
        {
            return $res;
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
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     * @param  bool $transliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the strings into
     * the Latin script and then flatten them to ASCII before calculating the distance. This is what you would normally
     * wish to happen for arbitrary Unicode strings since the algorithm of calculating the Levenshtein distance is not
     * Unicode-aware. For example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return int The Levenshtein distance between the two strings.
     */

    public static function levenDist ($string, $toString, $transliterate = true)
    {
        assert( 'is_cstring($string) && is_cstring($toString) && is_bool($transliterate)',
            vs(isset($this), get_defined_vars()) );

        $string = CEString::flattenUnicodeToAscii($string, $transliterate);
        $toString = CEString::flattenUnicodeToAscii($toString, $transliterate);
        return CString::levenDist($string, $toString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Metaphone key "heard" from a string.
     *
     * The algorithm used to render the [Metaphone](http://en.wikipedia.org/wiki/Metaphone) key is the first-generation
     * one.
     *
     * @param  string $string The source string.
     * @param  bool $transliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the string into the
     * Latin script and then flatten it to ASCII before generating the key. Since the Metaphone algorithm is not
     * Unicode-aware, the touch of transliteration is something that any arbitrary Unicode string would wish for. For
     * example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return string The Metaphone key of the string.
     */

    public static function metaphoneKey ($string, $transliterate = true)
    {
        assert( 'is_cstring($string) && is_bool($transliterate)', vs(isset($this), get_defined_vars()) );

        $string = CEString::flattenUnicodeToAscii($string, $transliterate);
        return CString::metaphoneKey($string);
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
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     * @param  bool $transliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the strings into
     * the Latin script and then flatten them to ASCII before generating the keys. Since neither the Metaphone or
     * Levenshtein algorithm is Unicode-aware, the touch of transliteration is something that any arbitrary Unicode
     * strings would wish for. For example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return int The Levenshtein distance between the Metaphone keys of the two strings.
     */

    public static function metaphoneDist ($string, $toString, $transliterate = true)
    {
        assert( 'is_cstring($string) && is_cstring($toString) && is_bool($transliterate)',
            vs(isset($this), get_defined_vars()) );
        return CString::levenDist(self::metaphoneKey($string, $transliterate),
            self::metaphoneKey($toString, $transliterate));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts all the characters in a string to lowercase and returns the new string.
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toLowerCase ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "lower");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts all the characters in a string to uppercase and returns the new string.
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toUpperCase ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "upper");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts the first character in a string to uppercase and returns the new string.
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toUpperCaseFirst ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($string) )
        {
            return "";
        }
        return self::toUpperCase(self::substr($string, 0, 1)) . self::substr($string, 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts the first character in each word that there is in a string to uppercase and returns the new string.
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toTitleCase ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "title");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string starts with a specified substring, searching case-sensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $withString The searched substring.
     *
     * @return bool `true` if the string starts with the substring specified, taking into account the letter case of
     * the characters, and `false` otherwise.
     */

    public static function startsWith ($string, $withString)
    {
        assert( 'is_cstring($string) && is_cstring($withString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($withString) )
        {
            return true;
        }
        return ( grapheme_strpos($string, $withString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string starts with a specified substring, searching case-insensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $withString The searched substring.
     *
     * @return bool `true` if the string starts with the substring specified, ignoring the letter case of the
     * characters, and `false` otherwise.
     */

    public static function startsWithCi ($string, $withString)
    {
        assert( 'is_cstring($string) && is_cstring($withString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($withString) )
        {
            return true;
        }
        return ( grapheme_stripos($string, $withString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string ends with a specified substring, searching case-sensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $withString The searched substring.
     *
     * @return bool `true` if the string ends with the substring specified, taking into account the letter case of the
     * characters, and `false` otherwise.
     */

    public static function endsWith ($string, $withString)
    {
        assert( 'is_cstring($string) && is_cstring($withString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($withString) )
        {
            return true;
        }
        return ( grapheme_strrpos($string, $withString) === self::length($string) - self::length($withString) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string ends with a specified substring, searching case-insensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $withString The searched substring.
     *
     * @return bool `true` if the string ends with the substring specified, ignoring the letter case of the characters,
     * and `false` otherwise.
     */

    public static function endsWithCi ($string, $withString)
    {
        assert( 'is_cstring($string) && is_cstring($withString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($withString) )
        {
            return true;
        }
        return ( grapheme_strripos($string, $withString) === self::length($string) - self::length($withString) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a substring in a string, searching case-sensitively.
     *
     * As a special case, the method returns the starting position of the search if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the first occurrence of the substring in the string or `-1` if no such substring was
     * found, taking into account the letter case during the search.
     */

    public static function indexOf ($string, $ofString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($ofString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= self::length($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($ofString) )
        {
            return $startPos;
        }
        $res = grapheme_strpos($string, $ofString, $startPos);
        return ( is_int($res) ) ? $res : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a substring in a string, searching case-insensitively.
     *
     * As a special case, the method returns the starting position of the search if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the first occurrence of the substring in the string or `-1` if no such substring was
     * found, ignoring the letter case during the search.
     */

    public static function indexOfCi ($string, $ofString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($ofString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= self::length($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($ofString) )
        {
            return $startPos;
        }
        $res = grapheme_stripos($string, $ofString, $startPos);
        return ( is_int($res) ) ? $res : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a substring in a string, searching case-sensitively.
     *
     * As a special case, the method returns the string's length if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the last occurrence of the substring in the string or `-1` if no such substring was
     * found, taking into account the letter case during the search.
     */

    public static function lastIndexOf ($string, $ofString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($ofString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= self::length($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($ofString) )
        {
            return self::length($string);
        }
        $res = grapheme_strrpos($string, $ofString, $startPos);
        return ( is_int($res) ) ? $res : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a substring in a string, searching case-insensitively.
     *
     * As a special case, the method returns the string's length if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the last occurrence of the substring in the string or `-1` if no such substring was
     * found, ignoring the letter case during the search.
     */

    public static function lastIndexOfCi ($string, $ofString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($ofString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= self::length($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($ofString) )
        {
            return self::length($string);
        }
        $res = grapheme_strripos($string, $ofString, $startPos);
        return ( is_int($res) ) ? $res : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified substring, searching case-sensitively.
     *
     * @param  string $string The string to be looked into.
     * @param  string $whatString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return bool `true` if the substring was found in the string, taking into account the letter case during the
     * search, and `false` otherwise.
     */

    public static function find ($string, $whatString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($whatString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        return ( self::indexOf($string, $whatString, $startPos) != -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified substring, searching case-insensitively.
     *
     * @param  string $string The string to be looked into.
     * @param  string $whatString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return bool `true` if the substring was found in the string, ignoring the letter case during the search, and
     * `false` otherwise.
     */

    public static function findCi ($string, $whatString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($whatString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        return ( self::indexOfCi($string, $whatString, $startPos) != -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the characters in a string are a subset of the characters in another string.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofCharSet The reference string.
     *
     * @return bool `true` if the string is a subset of the reference string, `false` otherwise.
     */

    public static function isSubsetOf ($string, $ofCharSet)
    {
        assert( 'is_cstring($string) && is_cstring($ofCharSet)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($string) && !self::isEmpty($ofCharSet) )
        {
            // Special case.
            return false;
        }

        $sLength = self::length($string);
        $csLength = self::length($ofCharSet);
        for ($i0 = 0; $i0 < $sLength; $i0++)
        {
            $isIn = false;
            for ($i1 = 0; $i1 < $csLength; $i1++)
            {
                if ( self::equals(self::charAt($string, $i0), self::charAt($ofCharSet, $i1)) )
                {
                    $isIn = true;
                    break;
                }
            }
            if ( !$isIn )
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
     * @param  string $string The string to be looked into.
     * @param  int $startPos The position of the substring's first character.
     * @param  int $length **OPTIONAL. Default is** *as many characters as the starting character is followed by*. The
     * length of the substring.
     *
     * @return string The substring.
     */

    public static function substr ($string, $startPos, $length = null)
    {
        assert( 'is_cstring($string) && is_int($startPos) && (!isset($length) || is_int($length))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < self::length($string)) || ' .
                '($startPos == self::length($string) && (!isset($length) || $length == 0))',
            vs(isset($this), get_defined_vars()) );
        assert( '!isset($length) || ($length >= 0 && $startPos + $length <= self::length($string))',
            vs(isset($this), get_defined_vars()) );

        $res;
        if ( !isset($length) )
        {
            $res = grapheme_substr($string, $startPos);
        }
        else
        {
            $res = grapheme_substr($string, $startPos, $length);
        }
        return ( is_cstring($res) ) ? $res : "";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a substring from a string, with both starting and ending positions specified.
     *
     * As a special case, the method returns an empty string if the starting and ending positions are the same, with
     * the greatest such position being the string's length.
     *
     * @param  string $string The string to be looked into.
     * @param  int $startPos The position of the substring's first character.
     * @param  int $endPos The position of the character that *follows* the last character in the substring.
     *
     * @return string The substring.
     */

    public static function substring ($string, $startPos, $endPos)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($endPos)',
            vs(isset($this), get_defined_vars()) );
        return self::substr($string, $startPos, $endPos - $startPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many substrings with a specified text there are in a string.
     *
     * The search is case-sensitive.
     *
     * @param  string $string The string to be looked into.
     * @param  string $substring The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The number of such substrings in the string.
     */

    public static function numSubstrings ($string, $substring, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($substring) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($substring)', vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < self::length($string)) || ' .
                '(self::isEmpty($string) && $startPos == 0)', vs(isset($this), get_defined_vars()) );

        $qty = 0;
        $ssLength = self::length($substring);
        while ( true )
        {
            $pos = self::indexOf($string, $substring, $startPos);
            if ( $pos == -1 )
            {
                break;
            }
            $qty++;
            $startPos = $pos + $ssLength;
        }
        return $qty;
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
     * @param  string $string The string to be split.
     * @param  string|array|map $delimiterOrDelimiters The substring or array of substrings to be recognized as the
     * delimiter(s).
     *
     * @return CArray The resulting strings.
     */

    public static function split ($string, $delimiterOrDelimiters)
    {
        assert( 'is_cstring($string) && ' .
                '(is_cstring($delimiterOrDelimiters) || is_collection($delimiterOrDelimiters))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($delimiterOrDelimiters) )
        {
            if ( self::isEmpty($delimiterOrDelimiters) )
            {
                // Special case.
                if ( self::isEmpty($string) )
                {
                    $resStrings = CArray::fromElements("");
                    return $resStrings;
                }
                else
                {
                    $sLength = self::length($string);
                    $resStrings = CArray::make($sLength);
                    for ($i = 0; $i < $sLength; $i++)
                    {
                        $resStrings[$i] = self::charAt($string, $i);
                    }
                    return $resStrings;
                }
            }

            $resStrings = CArray::make(self::numSubstrings($string, $delimiterOrDelimiters) + 1);
            $startPos = 0;
            $i = 0;
            $dLength = self::length($delimiterOrDelimiters);
            while ( true )
            {
                $endPos = self::indexOf($string, $delimiterOrDelimiters, $startPos);
                if ( $endPos != -1 )
                {
                    $resStrings[$i++] = self::substring($string, $startPos, $endPos);
                    $startPos = $endPos + $dLength;
                }
                else
                {
                    $resStrings[$i] = self::substr($string, $startPos);
                    break;
                }
            }
            return $resStrings;
        }
        else  // a collection
        {
            $resStrings = CArray::fromElements($string);
            foreach ($delimiterOrDelimiters as $delimiter)
            {
                assert( 'is_cstring($delimiter)', vs(isset($this), get_defined_vars()) );
                $resStringsNew = CArray::make();
                $len = CArray::length($resStrings);
                for ($i = 0; $i < $len; $i++)
                {
                    CArray::pushArray($resStringsNew, self::split($resStrings[$i], $delimiter));
                }
                $resStrings = $resStringsNew;
            }
            return $resStrings;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Splits a string into its constituting characters.
     *
     * @param  string $string The string to be split.
     *
     * @return CArray The string's characters.
     */

    public static function splitIntoChars ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::split($string, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the start of a string that are non-printable, such as whitespace.
     *
     * @param  string $string The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trimStart ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return CRegex::remove($string, "/^(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/u");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the end of a string that are non-printable, such as whitespace.
     *
     * @param  string $string The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trimEnd ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return CRegex::remove($string, "/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+\\z/u");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from both ends of a string that are non-printable, such as whitespace.
     *
     * @param  string $string The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trim ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        $string = self::trimStart($string);
        $string = self::trimEnd($string);
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the spacing in a string by removing all characters from both of its ends that are non-printable, such
     * as whitespace, and replacing any sequence of such characters within the string with a single space character,
     * and returns the new string.
     *
     * @param  string $string The string to be normalized.
     *
     * @return string The normalized string.
     */

    public static function normSpacing ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        $string = CRegex::remove($string, "/^(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/u");
        $string = CRegex::remove($string, "/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+\\z/u");
        $string = CRegex::replace($string, "/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/u", " ");
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the newlines in a string by replacing any newline that is not an LF newline with an LF, which is the
     * standard newline format on Linux/Unix and OS X, or with a custom newline, and returns the new string.
     *
     * The known newline formats are LF (U+000A), CRLF (U+000D, U+000A), CR (U+000D), VT (U+000B), FF (U+000C),
     * Next Line (U+0085), Line Separator (U+2028), and Paragraph Separator (U+2029).
     *
     * @param  string $string The string to be normalized.
     * @param  string $newline **OPTIONAL. Default is** LF (U+000A).
     *
     * @return string The normalized string.
     */

    public static function normNewlines ($string, $newline = self::NEWLINE)
    {
        assert( 'is_cstring($string) && is_cstring($newline)', vs(isset($this), get_defined_vars()) );
        return CRegex::replace($string, "/" . self::NL_NORM_SUBJECT_RE . "/u", $newline);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds characters to the start of a string to make it grow to a specified length and returns the new string.
     *
     * If the input string is already of the targeted length, it's returned unmodified.
     *
     * @param  string $string The string to be padded.
     * @param  string $paddingString The string to be used for padding.
     * @param  int $newLength The length of the padded string.
     *
     * @return string The padded string.
     */

    public static function padStart ($string, $paddingString, $newLength)
    {
        assert( 'is_cstring($string) && is_cstring($paddingString) && is_int($newLength)',
            vs(isset($this), get_defined_vars()) );
        assert( '$newLength >= self::length($string)', vs(isset($this), get_defined_vars()) );

        $padding = "";
        $diff = $newLength - self::length($string);
        for ($i = 0; $i < $diff; $i++)
        {
            $padding .= $paddingString;
        }
        return $padding . $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds characters to the end of a string to make it grow to a specified length and returns the new string.
     *
     * If the input string is already of the targeted length, it's returned unmodified.
     *
     * @param  string $string The string to be padded.
     * @param  string $paddingString The string to be used for padding.
     * @param  int $newLength The length of the padded string.
     *
     * @return string The padded string.
     */

    public static function padEnd ($string, $paddingString, $newLength)
    {
        assert( 'is_cstring($string) && is_cstring($paddingString) && is_int($newLength)',
            vs(isset($this), get_defined_vars()) );
        assert( '$newLength >= self::length($string)', vs(isset($this), get_defined_vars()) );

        $padding = "";
        $diff = $newLength - self::length($string);
        for ($i = 0; $i < $diff; $i++)
        {
            $padding .= $paddingString;
        }
        return $string . $padding;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the start of a string, if found searching case-sensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $string The string to be stripped.
     * @param  string|array|map $prefixOrPrefixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripStart ($string, $prefixOrPrefixes)
    {
        assert( 'is_cstring($string) && (is_cstring($prefixOrPrefixes) || is_collection($prefixOrPrefixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($prefixOrPrefixes) )
        {
            if ( self::startsWith($string, $prefixOrPrefixes) )
            {
                $string = self::substr($string, self::length($prefixOrPrefixes));
            }
        }
        else  // a collection
        {
            foreach ($prefixOrPrefixes as $prefix)
            {
                assert( 'is_cstring($prefix)', vs(isset($this), get_defined_vars()) );
                if ( self::startsWith($string, $prefix) )
                {
                    $string = self::substr($string, self::length($prefix));
                }
            }
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the start of a string, if found searching case-insensitively,
     * and returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $string The string to be stripped.
     * @param  string|array|map $prefixOrPrefixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripStartCi ($string, $prefixOrPrefixes)
    {
        assert( 'is_cstring($string) && (is_cstring($prefixOrPrefixes) || is_collection($prefixOrPrefixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($prefixOrPrefixes) )
        {
            if ( self::startsWithCi($string, $prefixOrPrefixes) )
            {
                $string = self::substr($string, self::length($prefixOrPrefixes));
            }
        }
        else  // a collection
        {
            foreach ($prefixOrPrefixes as $prefix)
            {
                assert( 'is_cstring($prefix)', vs(isset($this), get_defined_vars()) );
                if ( self::startsWithCi($string, $prefix) )
                {
                    $string = self::substr($string, self::length($prefix));
                }
            }
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the end of a string, if found searching case-sensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $string The string to be stripped.
     * @param  string|array|map $suffixOrSuffixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripEnd ($string, $suffixOrSuffixes)
    {
        assert( 'is_cstring($string) && (is_cstring($suffixOrSuffixes) || is_collection($suffixOrSuffixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($suffixOrSuffixes) )
        {
            if ( self::endsWith($string, $suffixOrSuffixes) )
            {
                $string = self::substr($string, 0, self::length($string) - self::length($suffixOrSuffixes));
            }
        }
        else  // a collection
        {
            foreach ($suffixOrSuffixes as $suffix)
            {
                assert( 'is_cstring($suffix)', vs(isset($this), get_defined_vars()) );
                if ( self::endsWith($string, $suffix) )
                {
                    $string = self::substr($string, 0, self::length($string) - self::length($suffix));
                }
            }
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the end of a string, if found searching case-insensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $string The string to be stripped.
     * @param  string|array|map $suffixOrSuffixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripEndCi ($string, $suffixOrSuffixes)
    {
        assert( 'is_cstring($string) && (is_cstring($suffixOrSuffixes) || is_collection($suffixOrSuffixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($suffixOrSuffixes) )
        {
            if ( self::endsWithCi($string, $suffixOrSuffixes) )
            {
                $string = self::substr($string, 0, self::length($string) - self::length($suffixOrSuffixes));
            }
        }
        else  // a collection
        {
            foreach ($suffixOrSuffixes as $suffix)
            {
                assert( 'is_cstring($suffix)', vs(isset($this), get_defined_vars()) );
                if ( self::endsWithCi($string, $suffix) )
                {
                    $string = self::substr($string, 0, self::length($string) - self::length($suffix));
                }
            }
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts a string into another string and returns the new string.
     *
     * As a special case, the position of insertion can be equal to the target string's length.
     *
     * @param  string $string The target string.
     * @param  int $atPos The position of insertion. This is the desired position of the first character of the
     * inserted string in the resulting string.
     * @param  string $insertString The string to be inserted.
     *
     * @return string The resulting string.
     */

    public static function insert ($string, $atPos, $insertString)
    {
        assert( 'is_cstring($string) && is_int($atPos) && is_cstring($insertString)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $atPos && $atPos <= self::length($string)', vs(isset($this), get_defined_vars()) );

        return self::substr($string, 0, $atPos) . $insertString . self::substr($string, $atPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, replaces a substring with a specified string, and returns the new string.
     *
     * @param  string $string The source string.
     * @param  int $startPos The position of the first character in the substring to be replaced.
     * @param  int $length The length of the substring to be replaced.
     * @param  string $with The replacement string.
     *
     * @return string The resulting string.
     */

    public static function replaceSubstring ($string, $startPos, $length, $with)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($length) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < self::length($string)) || ' .
                '($startPos == self::length($string) && $length == 0)', vs(isset($this), get_defined_vars()) );
        assert( '$length >= 0 && $startPos + $length <= self::length($string)',
            vs(isset($this), get_defined_vars()) );

        return self::substr($string, 0, $startPos) . $with . self::substr($string, $startPos + $length);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, replaces a substring with a specified string, with both starting and ending positions specified,
     * and returns the new string.
     *
     * @param  string $string The source string.
     * @param  int $startPos The position of the first character in the substring to be replaced.
     * @param  int $endPos The position of the character that *follows* the last character in the substring to be
     * replaced.
     * @param  string $with The replacement string.
     *
     * @return string The resulting string.
     */

    public static function replaceSubstringByRange ($string, $startPos, $endPos, $with)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($endPos) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstring($string, $startPos, $endPos - $startPos, $with);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a substring from a string and returns the new string.
     *
     * @param  string $string The source string.
     * @param  int $startPos The position of the first character in the substring to be removed.
     * @param  int $length The length of the substring to be removed.
     *
     * @return string The resulting string.
     */

    public static function removeSubstring ($string, $startPos, $length)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($length)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstring($string, $startPos, $length, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a substring from a string, with both starting and ending positions specified, and returns the new
     * string.
     *
     * @param  string $string The source string.
     * @param  int $startPos The position of the first character in the substring to be removed.
     * @param  int $endPos The position of the character that *follows* the last character in the substring to be
     * removed.
     *
     * @return string The resulting string.
     */

    public static function removeSubstringByRange ($string, $startPos, $endPos)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($endPos)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstringByRange($string, $startPos, $endPos, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a substring in a string with a specified string, searching case-sensitively, and
     * returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $string The source string.
     * @param  string $what The searched substring.
     * @param  string $with The replacement string.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replace ($string, $what, $with, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($what) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($what) )
        {
            // Special case.
            return $string;
        }

        $newString = "";
        $quantity = 0;
        $startPos = 0;
        $whatLength = self::length($what);
        while ( true )
        {
            $endPos = self::indexOf($string, $what, $startPos);
            if ( $endPos != -1 )
            {
                $newString .= self::substring($string, $startPos, $endPos);
                $newString .= $with;
                $quantity++;
                $startPos = $endPos + $whatLength;
            }
            else
            {
                $newString .= self::substr($string, $startPos);
                break;
            }
        }
        return $newString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a substring in a string with a specified string, searching case-insensitively, and
     * returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $string The source string.
     * @param  string $what The searched substring.
     * @param  string $with The replacement string.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replaceCi ($string, $what, $with, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($what) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($what) )
        {
            // Special case.
            return $string;
        }

        $newString = "";
        $quantity = 0;
        $startPos = 0;
        $whatLength = self::length($what);
        while ( true )
        {
            $endPos = self::indexOfCi($string, $what, $startPos);
            if ( $endPos != -1 )
            {
                $newString .= self::substring($string, $startPos, $endPos);
                $newString .= $with;
                $quantity++;
                $startPos = $endPos + $whatLength;
            }
            else
            {
                $newString .= self::substr($string, $startPos);
                break;
            }
        }
        return $newString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a substring from a string, searching case-sensitively, and returns the new string,
     * optionally reporting the number of removals made.
     *
     * @param  string $string The source string.
     * @param  string $what The searched substring.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return string The resulting string.
     */

    public static function remove ($string, $what, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($what)', vs(isset($this), get_defined_vars()) );
        return self::replace($string, $what, "", $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a substring from a string, searching case-insensitively, and returns the new string,
     * optionally reporting the number of removals made.
     *
     * @param  string $string The source string.
     * @param  string $what The searched substring.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return string The resulting string.
     */

    public static function removeCi ($string, $what, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($what)', vs(isset($this), get_defined_vars()) );
        return self::replaceCi($string, $what, "", $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Randomizes the positions of the characters in a string and returns the new string.
     *
     * @param  string $string The string to be shuffled.
     *
     * @return string The shuffled string.
     */

    public static function shuffle ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        // The Fisher-Yates algorithm.
        for ($i = self::length($string) - 1; $i > 0; $i--)
        {
            $exchangeIdx = CMathi::intervalRandom(0, $i);
            $save = self::charAt($string, $exchangeIdx);
            self::setCharAt($string, $exchangeIdx, self::charAt($string, $i));
            self::setCharAt($string, $i, $save);
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Wraps the text in a string to a specified width and returns the new string.
     *
     * @param  string $string The string with the text to be wrapped.
     * @param  int $width The wrapping width, in characters.
     * @param  bitfield $wrappingFlags **OPTIONAL. Default is** `WRAPPING_DEFAULT`. The wrapping option(s). The
     * available options are `WRAPPING_BREAK_SPACELESS_LINES`, `WRAPPING_ALLOW_TRAILING_SPACES`,
     * `WRAPPING_DISALLOW_LEADING_SPACES`, and `WRAPPING_DONT_BREAK_SPACELESS_CJK_ENDING_LINES`
     * (see [Summary](#summary)).
     * @param  string $newline **OPTIONAL. Default is** LF (U+000A). The newline character(s) to be used for making
     * new lines in the process of wrapping.
     *
     * @return string The wrapped text.
     */

    public static function wordWrap ($string, $width, $wrappingFlags = self::WRAPPING_DEFAULT,
        $newline = self::NEWLINE)
    {
        assert( 'is_cstring($string) && is_int($width) && is_bitfield($wrappingFlags) && is_cstring($newline)',
            vs(isset($this), get_defined_vars()) );
        assert( '$width > 0', vs(isset($this), get_defined_vars()) );

        // Constant. Newline character that is used by the input string (after newline normalization).
        $normNl = self::NEWLINE;

        // Constant. Determines what characters should be considered spaces.
        // A character in the "Zs" Unicode category, an HT, or a Zero Width Space, except No-break Space and Narrow
        // No-break Space.
        $spaceSubjectRe = "(\\p{Zs}|\\x{0009}|\\x{200B})(?<!\\x{00A0}|\\x{202F})";

        // Break enabling characters.
        // Soft Hyphen or Tibetan Mark Intersyllabic Tsheg.
        $breakAllowCharSubjectRe = "\\x{00AD}|\\x{0F0B}";

        // Retrieve the wrapping options.
        $breakSpacelessLines = CBitField::isBitSet($wrappingFlags, self::WRAPPING_BREAK_SPACELESS_LINES);
        $allowTrailingSpaces = CBitField::isBitSet($wrappingFlags, self::WRAPPING_ALLOW_TRAILING_SPACES);
        $disallowLeadingSpaces = CBitField::isBitSet($wrappingFlags, self::WRAPPING_DISALLOW_LEADING_SPACES);
        $dontBreakSpacelessCjkEndingLines = CBitField::isBitSet($wrappingFlags,
            self::WRAPPING_DONT_BREAK_SPACELESS_CJK_ENDING_LINES);

        // Normalize newlines in the input string.
        $string = self::normNewlines($string, $normNl);
        $normNlLength = self::length($normNl);

        $newString = "";

        $pos = 0;
        $bytePos = 0;
        $sLength = self::length($string);
        while ( true )
        {
            $numCharsLeft = $sLength - $pos;

            // A portion begins at the very start or right after a newline, either it is native or added. The length of
            // a portion is the wrapping width or less.
            $portionLength = CMathi::min($width, $numCharsLeft);
            $portion = self::substr($string, $pos, $portionLength);
            $portionByteLength = CString::length($portion);

            if ( $portionLength == $numCharsLeft )
            {
                // All done.
                $newString .= $portion;
                break;
            }

            // The starting position of the next portion.
            $nextPos = $pos + $portionLength;
            $nextBytePos = $bytePos + $portionByteLength;

            // Look for the first occurrence of a newline in the portion.
            $nlPos = self::indexOf($portion, $normNl);
            if ( $nlPos != -1 )
            {
                // This portion contains a newline, so the next portion is going to start right after this first found
                // newline.
                $subPLength = $nlPos + $normNlLength;
                $subP = self::substr($portion, 0, $subPLength);
                $newString .= $subP;
                $pos += $subPLength;
                $bytePos += CString::length($subP);
                continue;
            }

            // There are no newlines in this portion. Before the next step, make sure that the next portion is not
            // going to start with a newline.
            if ( $numCharsLeft - $portionLength >= $normNlLength )
            {
                $nextPortionBeginning = self::substr($string, $nextPos, $normNlLength);
                if ( self::indexOf($nextPortionBeginning, $normNl) == 0 )
                {
                    // The next portion is going to start with a newline, so no need to break this one, regardless of
                    // whether or not it contains any spaces.
                    $newString .= $portion;
                    $pos = $nextPos;
                    $bytePos = $nextBytePos;
                    continue;
                }
            }

            // The next portion is not going to start with a newline. Look for the last occurrence of a space or
            // break-allow character in this portion.
            $lastSubjectBytePos = CRegex::lastIndexOf($portion, "/($spaceSubjectRe)|($breakAllowCharSubjectRe)/u",
                0, $foundString);
            if ( $lastSubjectBytePos != -1 )
            {
                // Add a newline right after this last occurring space or break-allow character.
                $subP = CString::substring($portion, 0, $lastSubjectBytePos + CString::length($foundString));
                $newString .= $subP;
                $newString .= $newline;
                $pos += self::length($subP);
                $bytePos += CString::length($subP);
                continue;
            }

            // There are no spaces or break-allow characters in this portion. Consider adding a newline right after the
            // portion.
            if ( $breakSpacelessLines ||
                 (!$dontBreakSpacelessCjkEndingLines &&
                 self::hasCjkChar(self::charAt($portion, $portionLength - 1))) )
            {
                $newString .= $portion;
                $newString .= $newline;
                $pos = $nextPos;
                $bytePos = $nextBytePos;
                continue;
            }

            // There are no spaces or break-allow characters in this portion and it should go adjacent to the upcoming
            // text. Look for the first newline, space, or break-allow character in the upcoming text.
            $nextSubjectBytePos = CRegex::indexOf($string,
                "/$normNl|(($spaceSubjectRe)|($breakAllowCharSubjectRe))(?!$normNl)/u", $nextBytePos,
                $foundString);
            if ( $nextSubjectBytePos != -1 )
            {
                // Found a newline, space, or a break-allow character, so the next portion is going to start right
                // after it.
                $afterP = CString::substring($string, $nextBytePos,
                    $nextSubjectBytePos + CString::length($foundString));
                $newString .= $portion;
                $newString .= $afterP;
                if ( !CString::equals($foundString, $normNl) )
                {
                    // It is a space or break-allow character that was found, so add a newline after it.
                    $newString .= $newline;
                }
                $pos += $portionLength + self::length($afterP);
                $bytePos += $portionByteLength + CString::length($afterP);
                continue;
            }

            // There are no spaces, newlines, or break-allow characters in the upcoming text. Finalize according to the
            // breaking options.
            if ( !$breakSpacelessLines )
            {
                $newString .= $portion;
                $newString .= self::substr($string, $nextPos);
            }
            else
            {
                $newString .= $portion;
                $newString .= $newline;
                $pos = $nextPos;
                while ( true )
                {
                    $numCharsLeft = $sLength - $pos;
                    $portionLength = CMathi::min($width, $numCharsLeft);
                    $newString .= self::substr($string, $pos, $portionLength);
                    if ( $portionLength == $numCharsLeft )
                    {
                        break;
                    }
                    $newString .= $newline;
                    $pos += $portionLength;
                }
            }
            break;
        }

        if ( !$allowTrailingSpaces )
        {
            // Remove trailing spaces.
            $newString = CRegex::remove($newString, "/($spaceSubjectRe)+(?=$normNl|\\z)/u");
        }

        if ( $disallowLeadingSpaces )
        {
            // Remove leading spaces.
            $newString = CRegex::remove($newString, "/(?<=$normNl|^)($spaceSubjectRe)+/u");
        }

        return $newString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a decimal integer into the corresponding hexadecimal integer and returns it as another
     * string.
     *
     * @param  string $number The string with the number to be converted.
     *
     * @return string The string with the converted number.
     */

    public static function decToHex ($number)
    {
        assert( 'is_cstring($number)', vs(isset($this), get_defined_vars()) );

        $number = self::normalize($number, self::NF_KC);
        return CString::decToHex($number);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a hexadecimal integer into the corresponding decimal integer and returns it as another
     * string.
     *
     * The input string may be prefixed with "0x".
     *
     * @param  string $number The string with the number to be converted.
     *
     * @return string The string with the converted number.
     */

    public static function hexToDec ($number)
    {
        assert( 'is_cstring($number)', vs(isset($this), get_defined_vars()) );

        $number = self::normalize($number, self::NF_KC);
        return CString::hexToDec($number);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with an arbitrary-base integer into the corresponding integer in a different base and returns
     * it as another string.
     *
     * The input string may be prefixed with "0x" for the source base of 16.
     *
     * @param  string $number The string with the number to be converted.
     * @param  int $fromBase The source base. Can be a number in the range from 2 to 36.
     * @param  int $toBase The destination base. Can be a number in the range from 2 to 36.
     *
     * @return string The string with the converted number.
     */

    public static function numberToBase ($number, $fromBase, $toBase)
    {
        assert( 'is_cstring($number) && is_int($fromBase) && is_int($toBase)',
            vs(isset($this), get_defined_vars()) );

        $number = self::normalize($number, self::NF_KC);
        return CString::numberToBase($number, $fromBase, $toBase);
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
     * @param  string $string The string to be transliterated.
     * @param  string $fromScript The name of the source script (case-insensitive).
     * @param  string $toScript The name of the destination script (case-insensitive).
     *
     * @return string The transliterated string.
     */

    public static function transliterate ($string, $fromScript, $toScript)
    {
        assert( 'is_cstring($string) && is_cstring($fromScript) && is_cstring($toScript)',
            vs(isset($this), get_defined_vars()) );

        return self::handleTransform($string, "$fromScript-$toScript");
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
     * @param  string $string The string to be transliterated.
     * @param  string $toScript The name of the destination script (case-insensitive).
     *
     * @return string The transliterated string.
     */

    public static function transliterateFromAny ($string, $toScript)
    {
        assert( 'is_cstring($string) && is_cstring($toScript)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "any-$toScript");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts from typewriter-style punctuation to publishing-style and returns the new string.
     *
     * For example, this method converts "--" into "—" and a set of simple quotes into a set of "smart quotes".
     *
     * @param  string $string The string to be filtered.
     *
     * @return string The filtered string.
     */

    public static function applyPublishingFilter ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "publishing");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts any half-width characters in a string to full-width and returns the new string.
     *
     * For example, this method converts "123" to "１２３".
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function halfwidthToFullwidth ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "halfwidth-fullwidth");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts any full-width characters in a string to half-width and returns the new string.
     *
     * For example, this method converts "１２３" to "123".
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function fullwidthToHalfwidth ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, "fullwidth-halfwidth");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Transforms a string according to a specified ICU general transform.
     *
     * See [General Transforms](http://userguide.icu-project.org/transforms/general) in the ICU User Guide for the
     * information on what you can do with ICU general transforms.
     *
     * @param  string $string The string to be transformed.
     * @param  string $transform The transform.
     *
     * @return string The transformed string.
     */

    public static function transform ($string, $transform)
    {
        assert( 'is_cstring($string) && is_cstring($transform)', vs(isset($this), get_defined_vars()) );
        return self::handleTransform($string, $transform);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Repeats a string for a specified number of times and returns the resulting string.
     *
     * For instance, the string of "a" repeated three times would result in "aaa".
     *
     * @param  string $string The string to be repeated.
     * @param  int $times The number of times for the string to be repeated.
     *
     * @return string The resulting string.
     */

    public static function repeat ($string, $times)
    {
        assert( 'is_cstring($string) && is_int($times)', vs(isset($this), get_defined_vars()) );
        assert( '$times > 0 || (self::isEmpty($string) && $times == 0)', vs(isset($this), get_defined_vars()) );

        $resString = "";
        for ($i = 0; $i < $times; $i++)
        {
            $resString .= $string;
        }
        return $resString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains any characters from the Chinese, Japanese, or Korean scripts.
     *
     * @param  string $string The string to be looked into.
     *
     * @return bool `true` if the string contains at least one CJK character, `false` otherwise.
     */

    public static function hasCjkChar ($string)
    {

        // U+2E80-U+9FFF, U+F900-U+FAFF
        return CRegex::find($string, "/[\\x{2E80}-\\x{9FFF}\\x{F900}-\\x{FAFF}]/u");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function collatorObject ($caseInsensitive, $naturalOrder, $locale, $collationFlags)
    {
        // Is public to be accessible by other classes, such as CArray.

        assert( 'is_bool($caseInsensitive) && is_bool($naturalOrder) && is_cstring($locale) && ' .
                'is_bitfield($collationFlags)', vs(isset($this), get_defined_vars()) );
        assert( 'CULocale::isValid($locale) || CString::equalsCi($locale, "root")',
            vs(isset($this), get_defined_vars()) );

        $coll = new Collator($locale);

        // Case sensitivity.
        if ( !$caseInsensitive )
        {
            $coll->setStrength(Collator::TERTIARY);
        }
        else
        {
            $coll->setStrength(Collator::SECONDARY);
        }

        // Natural order.
        if ( !$naturalOrder )
        {
            // To be sure.
            $coll->setAttribute(Collator::NUMERIC_COLLATION, Collator::OFF);
        }
        else
        {
            $coll->setAttribute(Collator::NUMERIC_COLLATION, Collator::ON);
        }

        // Accents.
        if ( CBitField::isBitSet($collationFlags, self::COLLATION_IGNORE_ACCENTS) )
        {
            $coll->setStrength(Collator::PRIMARY);
            if ( !$caseInsensitive )
            {
                $coll->setAttribute(Collator::CASE_LEVEL, Collator::ON);
            }
        }

        // Invisible characters, some punctuation and symbols.
        if ( CBitField::isBitSet($collationFlags, self::COLLATION_IGNORE_NONWORD) )
        {
            $coll->setAttribute(Collator::ALTERNATE_HANDLING, Collator::SHIFTED);
        }

        // Case order.
        if ( !CBitField::isBitSet($collationFlags, self::COLLATION_UPPERCASE_FIRST) )
        {
            // To be sure.
            $coll->setAttribute(Collator::CASE_FIRST, Collator::OFF);
        }
        else
        {
            $coll->setAttribute(Collator::CASE_FIRST, Collator::UPPER_FIRST);
        }

        // "French" collation.
        if ( CBitField::isBitSet($collationFlags, self::COLLATION_FRENCH) )
        {
            $coll->setAttribute(Collator::FRENCH_COLLATION, Collator::ON);
        }

        return $coll;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    protected static function handleTransform ($string, $transform)
    {
        $translit = Transliterator::create($transform);
        $string = $translit->transliterate($string);
        if ( is_cstring($string) )
        {
            return $string;
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

    protected static function normFormToNc ($form)
    {
        switch ( $form )
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
