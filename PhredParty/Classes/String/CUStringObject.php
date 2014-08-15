<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class of any OOP Unicode string.
 *
 * **You can refer to this class by its alias, which is** `UStr`.
 *
 * The default encoding of a Unicode string is UTF-8.
 *
 * Unicode is a standard that comprehends the whole variety of human languages with their letters, punctuations, and
 * symbols of every sort, as well as the rules of handling such text. By this virtue, Unicode strings usually carry all
 * text that is meant to reach the eyes of a human being at any stage. ASCII, however, is limited to just English and
 * the basic punctuation that you can see on a Latin-script keyboard.
 *
 * *The following information dives deeper into Unicode and doesn't need to be read before you can start with the
 * class.*
 *
 * Different from an ASCII string where any character is a single byte, a Unicode character or, as it's often called, a
 * Unicode *code point* can "sit on multiple chairs" and span over up to four bytes when encoded in UTF-8. But if you
 * looked at the bytes of any characters from the ASCII charset present in a UTF-8 string, you would see them appear
 * identical to ASCII, so any ASCII string is also a valid UTF-8 string.
 *
 * A Unicode character, when displayed on a screen, can be represented not only by multiple bytes but also by multiple
 * code points. It's a peculiarity of Unicode that the code point of a specific Latin letter followed by the code point
 * of a specific accent mark is displayed as that letter with that accent mark on top, and not as two separate pieces
 * of graphics. Even though there may exist a code point specifically dedicated to the combination of that very letter
 * with that very accent mark, having the combination as two code points instead of a single one is perfectly
 * acceptable in Unicode. As a side note, code points that can blob together in this way are called *combining
 * characters* and ready-made characters are called *precomposed characters*.
 *
 * What this means is that, even if two strings differ at the byte level, they may look exactly the same on the screen
 * and thus be equal in every sense. When combinations of code points in two strings have equal meanings, such strings
 * are called *canonically equivalent* in Unicode and, if you were to compare such strings using `equals` method of
 * this class, it would surely return `true`. But sometimes the byte representation of a Unicode string does matter or
 * certain representation is preferred.
 *
 * This is when Unicode *normalization* comes into play. A string where every character is stored with the minimum
 * possible number of code points is said to be normalized to the normal form of NFC ("composed by equivalence"), and
 * the normal form of a string that is maximally stretched in terms of code points is NFD ("decomposed by
 * equivalence"). Some characters and character sequences are also said to be *compatible* with each other, for
 * instance "ﬄ" (single character) and "ffl" (three characters), which adds the second dimension to the normalization
 * and in total makes up four possible normal forms, with the other two being NFKC and NFKD. To get to NFKC or NFKD,
 * the characters are first decomposed by compatibility ("K") and then either composed ("C") or decomposed ("D") by
 * equivalence.
 */

// Method signatures:
//   bool isValid ()
//   CUStringObject sanitize ()
//   CUStringObject normalize ($eForm = self::NF_C)
//   bool isNormalized ($eForm = self::NF_C)
//   static CUStringObject fromBool10 ($bValue)
//   static CUStringObject fromBoolTf ($bValue)
//   static CUStringObject fromBoolYn ($bValue)
//   static CUStringObject fromBoolOo ($bValue)
//   static CUStringObject fromInt ($iValue)
//   static CUStringObject fromFloat ($fValue)
//   static CUStringObject fromCharCode ($iCode)
//   static CUStringObject fromCharCodeHex ($sCode)
//   static CUStringObject fromCharCodeEsc ($sCode)
//   static CUStringObject fromEscString ($sString)
//   bool toBool ()
//   bool toBoolFrom10 ()
//   bool toBoolFromTf ()
//   bool toBoolFromYn ()
//   bool toBoolFromOo ()
//   int toInt ()
//   int toIntFromHex ()
//   int toIntFromBase ($iBase)
//   float toFloat ()
//   int toCharCode ()
//   CUStringObject toCharCodeHex ()
//   CUStringObject toCharCodeEsc ()
//   CUStringObject toEscString ()
//   int length ()
//   bool isEmpty ()
//   CUStringObject charAt ($iPos)
//   void setCharAt ($iPos, $sChar)
//   bool equals ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT)
//   bool equalsCi ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT)
//   bool equalsBi ($sToString)
//   int compare ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
//   int compareCi ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
//   int compareNat ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
//   int compareNatCi ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
//   int levenDist ($sToString, $bTransliterate = true)
//   CUStringObject metaphoneKey ($bTransliterate = true)
//   int metaphoneDist ($sToString, $bTransliterate = true)
//   CUStringObject toLowerCase ()
//   CUStringObject toUpperCase ()
//   CUStringObject toUpperCaseFirst ()
//   CUStringObject toTitleCase ()
//   bool startsWith ($sWithString)
//   bool startsWithCi ($sWithString)
//   bool endsWith ($sWithString)
//   bool endsWithCi ($sWithString)
//   int indexOf ($sOfString, $iStartPos = 0)
//   int indexOfCi ($sOfString, $iStartPos = 0)
//   int lastIndexOf ($sOfString, $iStartPos = 0)
//   int lastIndexOfCi ($sOfString, $iStartPos = 0)
//   bool find ($sWhatString, $iStartPos = 0)
//   bool findCi ($sWhatString, $iStartPos = 0)
//   bool isSubsetOf ($sOfCharSet)
//   CUStringObject substr ($iStartPos, $iLength = null)
//   CUStringObject substring ($iStartPos, $iEndPos)
//   int numSubstrings ($sSubstring, $iStartPos = 0)
//   CArrayObject split ($xDelimiterOrDelimiters)
//   CArrayObject splitIntoChars ()
//   CUStringObject trimStart ()
//   CUStringObject trimEnd ()
//   CUStringObject trim ()
//   CUStringObject normSpacing ()
//   CUStringObject normNewlines ($sNewline = self::NEWLINE)
//   CUStringObject add ($xString)
//   CUStringObject addWs ($xString)
//   CUStringObject padStart ($sPaddingString, $iNewLength)
//   CUStringObject padEnd ($sPaddingString, $iNewLength)
//   CUStringObject stripStart ($xPrefixOrPrefixes)
//   CUStringObject stripStartCi ($xPrefixOrPrefixes)
//   CUStringObject stripEnd ($xSuffixOrSuffixes)
//   CUStringObject stripEndCi ($xSuffixOrSuffixes)
//   CUStringObject insert ($iAtPos, $sInsertString)
//   CUStringObject replaceSubstring ($iStartPos, $iLength, $sWith)
//   CUStringObject replaceSubstringByRange ($iStartPos, $iEndPos, $sWith)
//   CUStringObject removeSubstring ($iStartPos, $iLength)
//   CUStringObject removeSubstringByRange ($iStartPos, $iEndPos)
//   CUStringObject replace ($sWhat, $sWith, &$riQuantity = null)
//   CUStringObject replaceCi ($sWhat, $sWith, &$riQuantity = null)
//   CUStringObject remove ($sWhat, &$riQuantity = null)
//   CUStringObject removeCi ($sWhat, &$riQuantity = null)
//   CUStringObject shuffle ()
//   CUStringObject wordWrap ($iWidth, $bfWrappingFlags = self::WRAPPING_DEFAULT, $sNewline = self::NEWLINE)
//   CUStringObject decToHex ()
//   CUStringObject hexToDec ()
//   CUStringObject numberToBase ($iFromBase, $iToBase)
//   CUStringObject transliterate ($sFromScript, $sToScript)
//   CUStringObject transliterateFromAny ($sToScript)
//   CUStringObject applyPublishingFilter ()
//   CUStringObject halfwidthToFullwidth ()
//   CUStringObject fullwidthToHalfwidth ()
//   CUStringObject transform ($sTransform)
//   CUStringObject repeat ($iTimes)
//   bool hasCjkChar ()
//   int reIndexOf ($sOfPattern, $iStartPos = 0, &$rsFoundString = null)
//   int reLastIndexOf ($sOfPattern, $iStartPos = 0, &$rsFoundString = null)
//   bool reFind ($sFindPattern, &$rsFoundString = null)
//   bool reFindFrom ($sFindPattern, $iStartPos, &$rsFoundString = null)
//   bool reFindGroups ($sFindPattern, &$raFoundGroups, &$rsFoundString = null)
//   bool reFindGroupsFrom ($sFindPattern, $iStartPos, &$raFoundGroups, &$rsFoundString = null)
//   int reFindAll ($sFindPattern, &$raFoundStrings = null)
//   int reFindAllFrom ($sFindPattern, $iStartPos, &$raFoundStrings = null)
//   int reFindAllGroups ($sFindPattern, &$raFoundGroupArrays, &$raFoundStrings = null)
//   int reFindAllGroupsFrom ($sFindPattern, $iStartPos, &$raFoundGroupArrays, &$raFoundStrings = null)
//   CUStringObject reReplace ($sWhatPattern, $sWith, &$riQuantity = null)
//   CUStringObject reReplaceWithCallback ($sWhatPattern, $fnCallback, &$riQuantity = null)
//   CUStringObject reRemove ($sWhatPattern, &$riQuantity = null)
//   CArrayObject reSplit ($xDelimiterPatternOrPatterns)
//   CUStringObject reEnterTd ($sDelimiter = CRegex::DEFAULT_PATTERN_DELIMITER)

class CUStringObject extends CRootClass implements IEqualityAndOrder/*, ArrayAccess*/
{
    // The Unicode normal forms.
    /**
     * `enum` Unicode NFC normal form: Canonical Composition. To normalize an arbitrary Unicode string to the NFC form,
     * characters are decomposed and then recomposed by canonical equivalence.
     *
     * @var enum
     */
    const NF_C = CUString::NF_C;
    /**
     * `enum` Unicode NFD normal form: Canonical Decomposition. To normalize an arbitrary Unicode string to the NFD
     * form, characters are decomposed by canonical equivalence and multiple combining characters are arranged in a
     * specific order, but not composed.
     *
     * @var enum
     */
    const NF_D = CUString::NF_D;
    /**
     * `enum` Unicode NFKC normal form: Compatibility Composition. To normalize an arbitrary Unicode string to the NFKC
     * form, characters are decomposed by compatibility and then recomposed by canonical equivalence.
     *
     * @var enum
     */
    const NF_KC = CUString::NF_KC;
    /**
     * `enum` Unicode NFKD normal form: Compatibility Decomposition. To normalize an arbitrary Unicode string to the
     * NFKD form, characters are decomposed by compatibility and multiple combining characters are arranged in a
     * specific order, but not composed.
     *
     * @var enum
     */
    const NF_KD = CUString::NF_KD;

    // The default newline format and other common newline formats.
    /**
     * `string` The default newline format, which is LF (U+000A).
     *
     * @var string
     */
    const NEWLINE = CUString::NEWLINE;
    /**
     * `string` LF newline (U+000A). Used by Linux/Unix and OS X.
     *
     * @var string
     */
    const NEWLINE_LF = CUString::NEWLINE_LF;
    /**
     * `string` CRLF newline (U+000D, U+000A). Used by Windows.
     *
     * @var string
     */
    const NEWLINE_CRLF = CUString::NEWLINE_CRLF;
    /**
     * `string` CR newline (U+000D).
     *
     * @var string
     */
    const NEWLINE_CR = CUString::NEWLINE_CR;
    /**
     * `string` Unicode's Line Separator (U+2028).
     *
     * @var string
     */
    const NEWLINE_LS = CUString::NEWLINE_LS;
    /**
     * `string` Unicode's Paragraph Separator (U+2029).
     *
     * @var string
     */
    const NEWLINE_PS = CUString::NEWLINE_PS;

    // Collation flags.
    /**
     * `bitfield` None of the below collation flags (`0`). Accents and marks are not ignored when comparing Unicode
     * strings, neither are whitespace characters, vertical space characters, punctuation characters, and symbols,
     * while lowercase goes ahead of uppercase, and the "French" collation is not used.
     *
     * @var bitfield
     */
    const COLLATION_DEFAULT = CUString::COLLATION_DEFAULT;
    /**
     * `bitfield` Ignore accents and other marks when comparing Unicode strings.
     *
     * @var bitfield
     */
    const COLLATION_IGNORE_ACCENTS = CUString::COLLATION_IGNORE_ACCENTS;
    /**
     * `bitfield` Ignore whitespace, vertical space, punctuation, and symbols when comparing Unicode strings, such as
     * , . : ; ! ? - _ ' " @ # % & * ( ) [ ] { } \ (but not ~ $ ^ = + | / < > ` ).
     *
     * @var bitfield
     */
    const COLLATION_IGNORE_NONWORD = CUString::COLLATION_IGNORE_NONWORD;
    /**
     * `bitfield` Uppercase should go ahead of lowercase when comparing Unicode strings to determine their order.
     *
     * @var bitfield
     */
    const COLLATION_UPPERCASE_FIRST = CUString::COLLATION_UPPERCASE_FIRST;
    /**
     * `bitfield` Use the "French" collation when comparing Unicode strings to determine their order.
     *
     * @var bitfield
     */
    const COLLATION_FRENCH = CUString::COLLATION_FRENCH;

    // Word wrapping flags.
    /**
     * `bitfield` None of the below wrapping flags (`0`). Overly lengthy but spaceless lines do not get broken, lines
     * in a wrapped text are not allowed to end with whitespace but allowed to start with whitespace, and characters
     * from the Chinese, Japanese, and Korean scripts do not have any influence on how spaceless lines are treated.
     *
     * @var bitfield
     */
    const WRAPPING_DEFAULT = CUString::WRAPPING_DEFAULT;
    /**
     * `bitfield` Break any line that exceeds the wrapping width while doesn't contain any spaces at which it could be
     * broken.
     *
     * @var bitfield
     */
    const WRAPPING_BREAK_SPACELESS_LINES = CUString::WRAPPING_BREAK_SPACELESS_LINES;
    /**
     * `bitfield` Allow lines in the wrapped text to end with one or more whitespace characters from the source text.
     *
     * @var bitfield
     */
    const WRAPPING_ALLOW_TRAILING_SPACES = CUString::WRAPPING_ALLOW_TRAILING_SPACES;
    /**
     * `bitfield` Disallow lines in the wrapped text to start with whitespace characters from the source text.
     *
     * @var bitfield
     */
    const WRAPPING_DISALLOW_LEADING_SPACES = CUString::WRAPPING_DISALLOW_LEADING_SPACES;
    /**
     * `bitfield` Even if overly lengthy but spaceless lines were told to break with `WRAPPING_BREAK_SPACELESS_LINES`
     * flag, this flag tells not to break any line that at the point of destined break contains a character from the
     * Chinese, Japanese, or Korean scripts.
     *
     * @var bitfield
     */
    const WRAPPING_DONT_BREAK_SPACELESS_CJK_ENDING_LINES = CUString::WRAPPING_DONT_BREAK_SPACELESS_CJK_ENDING_LINES;

    /**
     * `string` The regular expression pattern used in trimming and spacing normalization.
     *
     * @var string
     */
    const TRIMMING_AND_SPACING_NORM_SUBJECT_RE = CUString::TRIMMING_AND_SPACING_NORM_SUBJECT_RE;

    /**
     * `string` The regular expression pattern used in newline normalization.
     *
     * @var string
     */
    const NL_NORM_SUBJECT_RE = CUString::NL_NORM_SUBJECT_RE;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is a valid Unicode string encoded in UTF-8.
     *
     * @return bool `true` if the string is a valid Unicode string encoded in UTF-8, `false` otherwise.
     */

    public function isValid ()
    {
        return CUString::isValid($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any invalid byte in a Unicode string with a Replacement Character (U+FFFD), which is "�", and returns
     * the new string.
     *
     * @return CUStringObject The sanitized string.
     */

    public function sanitize ()
    {
        return CUString::sanitize($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes a string to a specified Unicode normal form and returns the new string.
     *
     * @param  enum $eForm **OPTIONAL. Default is** `NF_C`. The Unicode normal form of the normalized string. The
     * possible normal forms are `NF_C`, `NF_D`, `NF_KC`, and `NF_KD` (see [Summary](#summary)).
     *
     * @return CUStringObject The normalized string.
     */

    public function normalize ($eForm = self::NF_C)
    {
        return CUString::normalize($this, $eForm);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is normalized according to a specified Unicode normal form.
     *
     * @param  enum $eForm **OPTIONAL. Default is** `NF_C`. The Unicode normal form to be verified against. The
     * possible normal forms are `NF_C`, `NF_D`, `NF_KC`, and `NF_KD` (see [Summary](#summary)).
     *
     * @return bool `true` if the string appears to be normalized according to the normal form specified, `false`
     * otherwise.
     */

    public function isNormalized ($eForm = self::NF_C)
    {
        return CUString::isNormalized($this, $eForm);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "1" for `true` and as "0" for `false`.
     *
     * @param  bool $bValue The value to be converted.
     *
     * @return CUStringObject "1" for `true`, "0" for `false`.
     */

    public static function fromBool10 ($bValue)
    {
        return CUString::fromBool10($bValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "true" for `true` and as "false" for `false`.
     *
     * @param  bool $bValue The value to be converted.
     *
     * @return CUStringObject "true" for `true`, "false" for `false`.
     */

    public static function fromBoolTf ($bValue)
    {
        return CUString::fromBoolTf($bValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "yes" for `true` and as "no" for `false`.
     *
     * @param  bool $bValue The value to be converted.
     *
     * @return CUStringObject "yes" for `true`, "no" for `false`.
     */

    public static function fromBoolYn ($bValue)
    {
        return CUString::fromBoolYn($bValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "on" for `true` and as "off" for `false`.
     *
     * @param  bool $bValue The value to be converted.
     *
     * @return CUStringObject "on" for `true`, "off" for `false`.
     */

    public static function fromBoolOo ($bValue)
    {
        return CUString::fromBoolOo($bValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer value into a string.
     *
     * @param  int $iValue The value to be converted.
     *
     * @return CUStringObject The textual representation of the integer value.
     */

    public static function fromInt ($iValue)
    {
        return CUString::fromInt($iValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point value into a string.
     *
     * @param  float $fValue The value to be converted.
     *
     * @return CUStringObject The textual representation of the floating-point value.
     */

    public static function fromFloat ($fValue)
    {
        return CUString::fromFloat($fValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code point specified as an integer.
     *
     * @param  int $iCode The Unicode code point.
     *
     * @return CUStringObject The Unicode character with the code point specified.
     */

    public static function fromCharCode ($iCode)
    {
        return CUString::fromCharCode($iCode);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code point specified as a hexadecimal string.
     *
     * For instance, "0041" or "41" would return "A".
     *
     * @param  string $sCode The Unicode code point. This can be a string with up to four hexadecimal digits.
     *
     * @return CUStringObject The Unicode character with the code point specified.
     */

    public static function fromCharCodeHex ($sCode)
    {
        return CUString::fromCharCodeHex($sCode);
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
     * @return CUStringObject The Unicode character with the code point specified.
     */

    public static function fromCharCodeEsc ($sCode)
    {
        return CUString::fromCharCodeEsc($sCode);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Unescapes any escaped Unicode characters in a string and returns the new string.
     *
     * For instance, "\u0041bc" would return "Abc".
     *
     * @param  string $sString The string to be unescaped.
     *
     * @return CUStringObject The unescaped string.
     */

    public static function fromEscString ($sString)
    {
        return CUString::fromEscString($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value.
     *
     * Any string that is "1", "true", "yes", or "on", regardless of the letter case, is interpreted as `true` and any
     * other string is interpreted as `false`.
     *
     * @return bool `true` for "1", "true", "yes", and "on", `false` for any other string.
     */

    public function toBool ()
    {
        return (
            CString::equals($this, "1") ||
            CString::equalsCi($this, "true") ||
            CString::equalsCi($this, "yes") ||
            CString::equalsCi($this, "on") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "1" and as `false` for "0" or any other string.
     *
     * @return bool `true` for "1", `false` for any other string.
     */

    public function toBoolFrom10 ()
    {
        return CString::equals($this, "1");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "true" and as `false` for "false" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @return bool `true` for "true", `false` for any other string.
     */

    public function toBoolFromTf ()
    {
        return CString::equalsCi($this, "true");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "yes" and as `false` for "no" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @return bool `true` for "yes", `false` for any other string.
     */

    public function toBoolFromYn ()
    {
        return CString::equalsCi($this, "yes");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "on" and as `false` for "off" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @return bool `true` for "on", `false` for any other string.
     */

    public function toBoolFromOo ()
    {
        return CString::equalsCi($this, "on");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into the corresponding integer value.
     *
     * @return int The integer value represented by the string.
     */

    public function toInt ()
    {
        return (int)($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a hexadecimal integer into the corresponding integer value.
     *
     * The string may be prefixed with "0x".
     *
     * @return int The integer value represented by the string.
     */

    public function toIntFromHex ()
    {
        return CUString::toIntFromHex($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with an arbitrary-base integer into the corresponding integer value.
     *
     * The string may be prefixed with "0x" for the base of 16.
     *
     * @param  int $iBase The base in which the integer is represented by the string. Can be a number in the range from
     * 2 to 36.
     *
     * @return int The integer value represented by the string.
     */

    public function toIntFromBase ($iBase)
    {
        return CUString::toIntFromBase($this, $iBase);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a floating-point number into the corresponding floating-point value.
     *
     * The number can be using a scientific notation, such as "2.5e-1".
     *
     * @return float The floating-point value represented by the string.
     */

    public function toFloat ()
    {
        return (float)($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code point of the character if the string is a single character, as an integer.
     *
     * @return int The Unicode code point of the character.
     */

    public function toCharCode ()
    {
        return CUString::toCharCode($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code point of the character if the string is a single character, as a hexadecimal string.
     *
     * The returned string is always four characters in length.
     *
     * For instance, "A" would return "0041".
     *
     * @return CUStringObject The Unicode code point of the character.
     */

    public function toCharCodeHex ()
    {
        return CUString::toCharCodeHex($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code point of the character if the string is a single character, as an escaped string.
     *
     * For instance, "A" would return "\u0041".
     *
     * @return CUStringObject The escaped character.
     */

    public function toCharCodeEsc ()
    {
        return CUString::toCharCodeEsc($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Escapes all the characters in a string and returns the new string.
     *
     * @return CUStringObject The escaped string.
     */

    public function toEscString ()
    {
        return CUString::toEscString($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many characters there are in a string.
     *
     * @return int The string's length.
     */

    public function length ()
    {
        return CUString::length($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is empty.
     *
     * @return bool `true` if the string is empty, `false` otherwise.
     */

    public function isEmpty ()
    {
        return CUString::isEmpty($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the character located at a specified position.
     *
     * @param  int $iPos The character's position.
     *
     * @return CUStringObject The character located at the position specified.
     */

    public function charAt ($iPos)
    {
        return CUString::charAt($this, $iPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // /**
    //  * Sets a character in a string.
    //  *
    //  * The string is modified in-place.
    //  *
    //  * @param  int $iPos The position of the character to be set.
    //  * @param  string $sChar The replacement character.
    //  *
    //  * @return void
    //  */

    // public function setCharAt ($iPos, $sChar)
    // {
    //     CUString::setCharAt($this, $iPos, $sChar);
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is equal to another string, comparing case-sensitively.
     *
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     *
     * @return bool `true` if the two strings are equal, taking into account the letter case of the characters, and
     * `false` otherwise.
     */

    public function equals ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT)
    {
        return CUString::equals($this, $sToString, $bfCollationFlags);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is equal to another string, comparing case-insensitively.
     *
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     *
     * @return bool `true` if the two strings are equal, ignoring the letter case of the characters, and `false`
     * otherwise.
     */

    public function equalsCi ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT)
    {
        return CUString::equalsCi($this, $sToString, $bfCollationFlags);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an ASCII string or binary data is equal to another ASCII string or binary data.
     *
     * This method is suitable for binary-safe comparison of strings and runs faster than `equals` method when
     * comparing strings known to contain no Unicode characters and is the method to be preferred for comparison of
     * binary data.
     *
     * @param  string $sToString The second string for comparison.
     *
     * @return bool `true` if the two strings are equal, comparing them byte-to-byte, and `false` otherwise.
     */

    public function equalsBi ($sToString)
    {
        return CString::equals($this, $sToString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-sensitively.
     *
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if *this* string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     */

    public function compare ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
    {
        return CUString::compare($this, $sToString, $bfCollationFlags, $oInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-insensitively.
     *
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if *this* string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public function compareCi ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
    {
        return CUString::compareCi($this, $sToString, $bfCollationFlags, $oInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order, comparing the strings case-sensitively, and using natural order comparison.
     *
     * To illustrate natural order with an example, the strings "a100" and "a20" would get ordered as such with
     * `compare` method, but as "a20" and "a100" with this method, which is the order a human being would choose.
     *
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if *this* string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     */

    public function compareNat ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
    {
        return CUString::compareNat($this, $sToString, $bfCollationFlags, $oInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order, comparing the strings case-insensitively, and using natural order comparison.
     *
     * To illustrate natural order with an example, the strings "a100" and "a20" would get ordered as such with
     * `compareCi` method, but as "a20" and "a100" with this method, which is the order a human being would choose.
     *
     * @param  string $sToString The second string for comparison.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `COLLATION_DEFAULT`. The collation option(s) to be
     * used for the comparison. The available collation options are `COLLATION_IGNORE_ACCENTS`,
     * `COLLATION_IGNORE_NONWORD`, `COLLATION_UPPERCASE_FIRST`, and `COLLATION_FRENCH` (see [Summary](#summary)).
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * strings are to be compared.
     *
     * @return int `-1` if *this* string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public function compareNatCi ($sToString, $bfCollationFlags = self::COLLATION_DEFAULT, CULocale $oInLocale = null)
    {
        return CUString::compareNatCi($this, $sToString, $bfCollationFlags, $oInLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Levenshtein distance calculated from another string.
     *
     * For any two strings, the [Levenshtein distance](http://en.wikipedia.org/wiki/Levenshtein_distance) is the total
     * number of insert, replace, and delete operations required to transform the first string into the second one.
     *
     * @param  string $sToString The second string for comparison.
     * @param  bool $bTransliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the strings into
     * the Latin script and then flatten them to ASCII before calculating the distance. This is what you would normally
     * wish to happen for arbitrary Unicode strings since the algorithm of calculating the Levenshtein distance is not
     * Unicode-aware. For example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return int The Levenshtein distance between the two strings.
     */

    public function levenDist ($sToString, $bTransliterate = true)
    {
        return CUString::levenDist($this, $sToString, $bTransliterate);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Metaphone key "heard" from a string.
     *
     * The algorithm used to render the [Metaphone](http://en.wikipedia.org/wiki/Metaphone) key is the first-generation
     * one.
     *
     * @param  bool $bTransliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the string into the
     * Latin script and then flatten it to ASCII before generating the key. Since the Metaphone algorithm is not
     * Unicode-aware, the touch of transliteration is something that any arbitrary Unicode string would wish for. For
     * example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return CUStringObject The Metaphone key of the string.
     */

    public function metaphoneKey ($bTransliterate = true)
    {
        return CUString::metaphoneKey($this, $bTransliterate);
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
     * @param  string $sToString The second string for comparison.
     * @param  bool $bTransliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the strings into
     * the Latin script and then flatten them to ASCII before generating the keys. Since neither the Metaphone or
     * Levenshtein algorithm is Unicode-aware, the touch of transliteration is something that any arbitrary Unicode
     * strings would wish for. For example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return int The Levenshtein distance between the Metaphone keys of the two strings.
     */

    public function metaphoneDist ($sToString, $bTransliterate = true)
    {
        return CUString::metaphoneDist($this, $sToString, $bTransliterate);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts all the characters in a string to lowercase and returns the new string.
     *
     * @return CUStringObject The converted string.
     */

    public function toLowerCase ()
    {
        return CUString::toLowerCase($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts all the characters in a string to uppercase and returns the new string.
     *
     * @return CUStringObject The converted string.
     */

    public function toUpperCase ()
    {
        return CUString::toUpperCase($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts the first character in a string to uppercase and returns the new string.
     *
     * @return CUStringObject The converted string.
     */

    public function toUpperCaseFirst ()
    {
        return CUString::toUpperCaseFirst($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts the first character in each word that there is in a string to uppercase and returns the new string.
     *
     * @return CUStringObject The converted string.
     */

    public function toTitleCase ()
    {
        return CUString::toTitleCase($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string starts with a specified substring, searching case-sensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $sWithString The searched substring.
     *
     * @return bool `true` if the string starts with the substring specified, taking into account the letter case of
     * the characters, and `false` otherwise.
     */

    public function startsWith ($sWithString)
    {
        return CUString::startsWith($this, $sWithString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string starts with a specified substring, searching case-insensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $sWithString The searched substring.
     *
     * @return bool `true` if the string starts with the substring specified, ignoring the letter case of the
     * characters, and `false` otherwise.
     */

    public function startsWithCi ($sWithString)
    {
        return CUString::startsWithCi($this, $sWithString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string ends with a specified substring, searching case-sensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $sWithString The searched substring.
     *
     * @return bool `true` if the string ends with the substring specified, taking into account the letter case of the
     * characters, and `false` otherwise.
     */

    public function endsWith ($sWithString)
    {
        return CUString::endsWith($this, $sWithString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string ends with a specified substring, searching case-insensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $sWithString The searched substring.
     *
     * @return bool `true` if the string ends with the substring specified, ignoring the letter case of the characters,
     * and `false` otherwise.
     */

    public function endsWithCi ($sWithString)
    {
        return CUString::endsWithCi($this, $sWithString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a substring in a string, searching case-sensitively.
     *
     * As a special case, the method returns the starting position of the search if the searched substring is empty.
     *
     * @param  string $sOfString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the first occurrence of the substring in the string or `-1` if no such substring was
     * found, taking into account the letter case during the search.
     */

    public function indexOf ($sOfString, $iStartPos = 0)
    {
        return CUString::indexOf($this, $sOfString, $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a substring in a string, searching case-insensitively.
     *
     * As a special case, the method returns the starting position of the search if the searched substring is empty.
     *
     * @param  string $sOfString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the first occurrence of the substring in the string or `-1` if no such substring was
     * found, ignoring the letter case during the search.
     */

    public function indexOfCi ($sOfString, $iStartPos = 0)
    {
        return CUString::indexOfCi($this, $sOfString, $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a substring in a string, searching case-sensitively.
     *
     * As a special case, the method returns the string's length if the searched substring is empty.
     *
     * @param  string $sOfString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the last occurrence of the substring in the string or `-1` if no such substring was
     * found, taking into account the letter case during the search.
     */

    public function lastIndexOf ($sOfString, $iStartPos = 0)
    {
        return CUString::lastIndexOf($this, $sOfString, $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a substring in a string, searching case-insensitively.
     *
     * As a special case, the method returns the string's length if the searched substring is empty.
     *
     * @param  string $sOfString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the last occurrence of the substring in the string or `-1` if no such substring was
     * found, ignoring the letter case during the search.
     */

    public function lastIndexOfCi ($sOfString, $iStartPos = 0)
    {
        return CUString::lastIndexOfCi($this, $sOfString, $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified substring, searching case-sensitively.
     *
     * @param  string $sWhatString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return bool `true` if the substring was found in the string, taking into account the letter case during the
     * search, and `false` otherwise.
     */

    public function find ($sWhatString, $iStartPos = 0)
    {
        return CUString::find($this, $sWhatString, $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified substring, searching case-insensitively.
     *
     * @param  string $sWhatString The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return bool `true` if the substring was found in the string, ignoring the letter case during the search, and
     * `false` otherwise.
     */

    public function findCi ($sWhatString, $iStartPos = 0)
    {
        return CUString::findCi($this, $sWhatString, $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the characters in a string are a subset of the characters in another string.
     *
     * @param  string $sOfCharSet The reference string.
     *
     * @return bool `true` if *this* string is a subset of the reference string, `false` otherwise.
     */

    public function isSubsetOf ($sOfCharSet)
    {
        return CUString::isSubsetOf($this, $sOfCharSet);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a substring from a string.
     *
     * As a special case, the method returns an empty string if the starting position is equal to the string's length
     * or if the substring's length, if specified, is `0`.
     *
     * @param  int $iStartPos The position of the substring's first character.
     * @param  int $iLength **OPTIONAL. Default is** *as many characters as the starting character is followed by*. The
     * length of the substring.
     *
     * @return CUStringObject The substring.
     */

    public function substr ($iStartPos, $iLength = null)
    {
        return CUString::substr($this, $iStartPos, $iLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a substring from a string, with both starting and ending positions specified.
     *
     * As a special case, the method returns an empty string if the starting and ending positions are the same, with
     * the greatest such position being the string's length.
     *
     * @param  int $iStartPos The position of the substring's first character.
     * @param  int $iEndPos The position of the character that *follows* the last character in the substring.
     *
     * @return CUStringObject The substring.
     */

    public function substring ($iStartPos, $iEndPos)
    {
        return CUString::substring($this, $iStartPos, $iEndPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many substrings with a specified text there are in a string.
     *
     * The search is case-sensitive.
     *
     * @param  string $sSubstring The searched substring.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The number of such substrings in the string.
     */

    public function numSubstrings ($sSubstring, $iStartPos = 0)
    {
        return CUString::numSubstrings($this, $sSubstring, $iStartPos);
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
     * @param  string|array|map $xDelimiterOrDelimiters The substring or array of substrings to be recognized as the
     * delimiter(s).
     *
     * @return CArrayObject The resulting strings of type `CUStringObject`.
     */

    public function split ($xDelimiterOrDelimiters)
    {
        return to_oop(CUString::split($this, $xDelimiterOrDelimiters));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Splits a string into its constituting characters.
     *
     * @return CArrayObject The string's characters of type `CUStringObject`.
     */

    public function splitIntoChars ()
    {
        return to_oop(CUString::splitIntoChars($this));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the start of a string that are non-printable, such as whitespace.
     *
     * @return CUStringObject The trimmed string.
     */

    public function trimStart ()
    {
        return CUString::trimStart($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the end of a string that are non-printable, such as whitespace.
     *
     * @return CUStringObject The trimmed string.
     */

    public function trimEnd ()
    {
        return CUString::trimEnd($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from both ends of a string that are non-printable, such as whitespace.
     *
     * @return CUStringObject The trimmed string.
     */

    public function trim ()
    {
        return CUString::trim($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the spacing in a string by removing all characters from both of its ends that are non-printable, such
     * as whitespace, and replacing any sequence of such characters within the string with a single space character,
     * and returns the new string.
     *
     * @return CUStringObject The normalized string.
     */

    public function normSpacing ()
    {
        return CUString::normSpacing($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the newlines in a string by replacing any newline that is not an LF newline with an LF, which is the
     * standard newline format on Linux/Unix and OS X, or with a custom newline, and returns the new string.
     *
     * The known newline formats are LF (U+000A), CRLF (U+000D, U+000A), CR (U+000D), VT (U+000B), FF (U+000C),
     * Next Line (U+0085), Line Separator (U+2028), and Paragraph Separator (U+2029).
     *
     * @param  string $sNewline **OPTIONAL. Default is** LF (U+000A).
     *
     * @return CUStringObject The normalized string.
     */

    public function normNewlines ($sNewline = self::NEWLINE)
    {
        return CUString::normNewlines($this, $sNewline);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds another string to the end of a string and returns the new string.
     *
     * In case of a boolean value, it's added as "1" for `true` and as "0" for `false`.
     *
     * @param  string|int|float|bool $xString The string or value to be added.
     *
     * @return CUStringObject The resulting string.
     */

    public function add ($xString)
    {
        if ( !is_string($xString) )
        {
            $xString = ( !is_bool($xString) ) ? (string)$xString : CUString::fromBool10($xString);
        }
        return $this . $xString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds another string to the end of a string, separating the strings with a space character, and returns the new
     * string.
     *
     * In case of a boolean value, it's added as "1" for `true` and as "0" for `false`.
     *
     * @param  string|int|float|bool $xString The string or value to be added.
     *
     * @return CUStringObject The resulting string.
     */

    public function addWs ($xString)
    {
        if ( !is_string($xString) )
        {
            $xString = ( !is_bool($xString) ) ? (string)$xString : CUString::fromBool10($xString);
        }
        return $this . " " . $xString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds characters to the start of a string to make it grow to a specified length and returns the new string.
     *
     * If the string is already of the targeted length, it's returned unmodified.
     *
     * @param  string $sPaddingString The string to be used for padding.
     * @param  int $iNewLength The length of the padded string.
     *
     * @return CUStringObject The padded string.
     */

    public function padStart ($sPaddingString, $iNewLength)
    {
        return CUString::padStart($this, $sPaddingString, $iNewLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds characters to the end of a string to make it grow to a specified length and returns the new string.
     *
     * If the string is already of the targeted length, it's returned unmodified.
     *
     * @param  string $sPaddingString The string to be used for padding.
     * @param  int $iNewLength The length of the padded string.
     *
     * @return CUStringObject The padded string.
     */

    public function padEnd ($sPaddingString, $iNewLength)
    {
        return CUString::padEnd($this, $sPaddingString, $iNewLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the start of a string, if found searching case-sensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string|array|map $xPrefixOrPrefixes The substring or array of substrings to be stripped off.
     *
     * @return CUStringObject The stripped string.
     */

    public function stripStart ($xPrefixOrPrefixes)
    {
        return CUString::stripStart($this, $xPrefixOrPrefixes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the start of a string, if found searching case-insensitively,
     * and returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string|array|map $xPrefixOrPrefixes The substring or array of substrings to be stripped off.
     *
     * @return CUStringObject The stripped string.
     */

    public function stripStartCi ($xPrefixOrPrefixes)
    {
        return CUString::stripStartCi($this, $xPrefixOrPrefixes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the end of a string, if found searching case-sensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string|array|map $xSuffixOrSuffixes The substring or array of substrings to be stripped off.
     *
     * @return CUStringObject The stripped string.
     */

    public function stripEnd ($xSuffixOrSuffixes)
    {
        return CUString::stripEnd($this, $xSuffixOrSuffixes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the end of a string, if found searching case-insensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string|array|map $xSuffixOrSuffixes The substring or array of substrings to be stripped off.
     *
     * @return CUStringObject The stripped string.
     */

    public function stripEndCi ($xSuffixOrSuffixes)
    {
        return CUString::stripEndCi($this, $xSuffixOrSuffixes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts another string into a string and returns the new string.
     *
     * As a special case, the position of insertion can be equal to the string's length.
     *
     * @param  int $iAtPos The position of insertion. This is the desired position of the first character of the
     * inserted string in the resulting string.
     * @param  string $sInsertString The string to be inserted.
     *
     * @return CUStringObject The resulting string.
     */

    public function insert ($iAtPos, $sInsertString)
    {
        return CUString::insert($this, $iAtPos, $sInsertString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces a substring with a specified string, and returns the new string.
     *
     * @param  int $iStartPos The position of the first character in the substring to be replaced.
     * @param  int $iLength The length of the substring to be replaced.
     * @param  string $sWith The replacement string.
     *
     * @return CUStringObject The resulting string.
     */

    public function replaceSubstring ($iStartPos, $iLength, $sWith)
    {
        return CUString::replaceSubstring($this, $iStartPos, $iLength, $sWith);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces a substring with a specified string, with both starting and ending positions specified, and returns the
     * new string.
     *
     * @param  int $iStartPos The position of the first character in the substring to be replaced.
     * @param  int $iEndPos The position of the character that *follows* the last character in the substring to be
     * replaced.
     * @param  string $sWith The replacement string.
     *
     * @return CUStringObject The resulting string.
     */

    public function replaceSubstringByRange ($iStartPos, $iEndPos, $sWith)
    {
        return CUString::replaceSubstringByRange($this, $iStartPos, $iEndPos, $sWith);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a substring from a string and returns the new string.
     *
     * @param  int $iStartPos The position of the first character in the substring to be removed.
     * @param  int $iLength The length of the substring to be removed.
     *
     * @return CUStringObject The resulting string.
     */

    public function removeSubstring ($iStartPos, $iLength)
    {
        return CUString::removeSubstring($this, $iStartPos, $iLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a substring from a string, with both starting and ending positions specified, and returns the new
     * string.
     *
     * @param  int $iStartPos The position of the first character in the substring to be removed.
     * @param  int $iEndPos The position of the character that *follows* the last character in the substring to be
     * removed.
     *
     * @return CUStringObject The resulting string.
     */

    public function removeSubstringByRange ($iStartPos, $iEndPos)
    {
        return CUString::removeSubstringByRange($this, $iStartPos, $iEndPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a substring in a string with a specified string, searching case-sensitively, and
     * returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $sWhat The searched substring.
     * @param  string $sWith The replacement string.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return CUStringObject The resulting string.
     */

    public function replace ($sWhat, $sWith, &$riQuantity = null)
    {
        return CUString::replace($this, $sWhat, $sWith, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a substring in a string with a specified string, searching case-insensitively, and
     * returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $sWhat The searched substring.
     * @param  string $sWith The replacement string.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return CUStringObject The resulting string.
     */

    public function replaceCi ($sWhat, $sWith, &$riQuantity = null)
    {
        return CUString::replaceCi($this, $sWhat, $sWith, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a substring from a string, searching case-sensitively, and returns the new string,
     * optionally reporting the number of removals made.
     *
     * @param  string $sWhat The searched substring.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return CUStringObject The resulting string.
     */

    public function remove ($sWhat, &$riQuantity = null)
    {
        return CUString::remove($this, $sWhat, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a substring from a string, searching case-insensitively, and returns the new string,
     * optionally reporting the number of removals made.
     *
     * @param  string $sWhat The searched substring.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return CUStringObject The resulting string.
     */

    public function removeCi ($sWhat, &$riQuantity = null)
    {
        return CUString::removeCi($this, $sWhat, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Randomizes the positions of the characters in a string and returns the new string.
     *
     * @return CUStringObject The shuffled string.
     */

    public function shuffle ()
    {
        return CUString::shuffle($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Wraps the text in a string to a specified width and returns the new string.
     *
     * @param  int $iWidth The wrapping width, in characters.
     * @param  bitfield $bfWrappingFlags **OPTIONAL. Default is** `WRAPPING_DEFAULT`. The wrapping option(s). The
     * available options are `WRAPPING_BREAK_SPACELESS_LINES`, `WRAPPING_ALLOW_TRAILING_SPACES`,
     * `WRAPPING_DISALLOW_LEADING_SPACES`, and `WRAPPING_DONT_BREAK_SPACELESS_CJK_ENDING_LINES`
     * (see [Summary](#summary)).
     * @param  string $sNewline **OPTIONAL. Default is** LF (U+000A). The newline character(s) to be used for making
     * new lines in the process of wrapping.
     *
     * @return CUStringObject The wrapped text.
     */

    public function wordWrap ($iWidth, $bfWrappingFlags = self::WRAPPING_DEFAULT, $sNewline = self::NEWLINE)
    {
        return CUString::wordWrap($this, $iWidth, $bfWrappingFlags, $sNewline);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a decimal integer into the corresponding hexadecimal integer and returns it as another
     * string.
     *
     * @return CUStringObject The string with the converted number.
     */

    public function decToHex ()
    {
        return CUString::decToHex($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a hexadecimal integer into the corresponding decimal integer and returns it as another
     * string.
     *
     * The string may be prefixed with "0x".
     *
     * @return CUStringObject The string with the converted number.
     */

    public function hexToDec ()
    {
        return CUString::hexToDec($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with an arbitrary-base integer into the corresponding integer in a different base and returns
     * it as another string.
     *
     * The string may be prefixed with "0x" for the source base of 16.
     *
     * @param  int $iFromBase The source base. Can be a number in the range from 2 to 36.
     * @param  int $iToBase The destination base. Can be a number in the range from 2 to 36.
     *
     * @return CUStringObject The string with the converted number.
     */

    public function numberToBase ($iFromBase, $iToBase)
    {
        return CUString::numberToBase($this, $iFromBase, $iToBase);
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
     * @param  string $sFromScript The name of the source script (case-insensitive).
     * @param  string $sToScript The name of the destination script (case-insensitive).
     *
     * @return CUStringObject The transliterated string.
     */

    public function transliterate ($sFromScript, $sToScript)
    {
        return CUString::transliterate($this, $sFromScript, $sToScript);
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
     * @param  string $sToScript The name of the destination script (case-insensitive).
     *
     * @return CUStringObject The transliterated string.
     */

    public function transliterateFromAny ($sToScript)
    {
        return CUString::transliterateFromAny($this, $sToScript);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts from typewriter-style punctuation to publishing-style and returns the new string.
     *
     * For example, this method converts "--" into "—" and a set of simple quotes into a set of "smart quotes".
     *
     * @return CUStringObject The filtered string.
     */

    public function applyPublishingFilter ()
    {
        return CUString::applyPublishingFilter($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts any half-width characters in a string to full-width and returns the new string.
     *
     * For example, this method converts "123" to "１２３".
     *
     * @return CUStringObject The converted string.
     */

    public function halfwidthToFullwidth ()
    {
        return CUString::halfwidthToFullwidth($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts any full-width characters in a string to half-width and returns the new string.
     *
     * For example, this method converts "１２３" to "123".
     *
     * @return CUStringObject The converted string.
     */

    public function fullwidthToHalfwidth ()
    {
        return CUString::fullwidthToHalfwidth($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Transforms a string according to a specified ICU general transform.
     *
     * See [General Transforms](http://userguide.icu-project.org/transforms/general) in the ICU User Guide for the
     * information on what you can do with ICU general transforms.
     *
     * @param  string $sTransform The transform.
     *
     * @return CUStringObject The transformed string.
     */

    public function transform ($sTransform)
    {
        return CUString::transform($this, $sTransform);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Repeats a string for a specified number of times and returns the resulting string.
     *
     * For instance, the string of "a" repeated three times would result in "aaa".
     *
     * @param  int $iTimes The number of times for the string to be repeated.
     *
     * @return CUStringObject The resulting string.
     */

    public function repeat ($iTimes)
    {
        return CUString::repeat($this, $iTimes);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains any characters from the Chinese, Japanese, or Korean scripts.
     *
     * @return bool `true` if the string contains at least one CJK character, `false` otherwise.
     */

    public function hasCjkChar ()
    {
        return CUString::hasCjkChar($this);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a regular expression pattern in a string, optionally reporting
     * the substring that matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * **NOTE.** Unlike the non-regex methods of the class, which count positions within a Unicode string in
     * characters, the PCRE engine and therefore this method count positions in bytes.
     *
     * @param  string $sOfPattern The searched pattern.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `CUStringObject`, is the first substring
     * that matched the pattern.
     *
     * @return int The position of the first occurrence of the pattern in the string or `-1` if no such pattern was
     * found.
     */

    public function reIndexOf ($sOfPattern, $iStartPos = 0, &$rsFoundString = null)
    {
        $sOfPattern = self::ensureUModifier($sOfPattern);
        $xRet = CRegex::indexOf($this, $sOfPattern, $iStartPos, $rsFoundString);
        $rsFoundString = to_oop($rsFoundString);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a regular expression pattern in a string, optionally reporting
     * the substring that matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * **NOTE.** Unlike the non-regex methods of the class, which count positions within a Unicode string in
     * characters, the PCRE engine and therefore this method count positions in bytes.
     *
     * @param  string $sOfPattern The searched pattern.
     * @param  int $iStartPos **OPTIONAL. Default is** `0`. The starting position for the search.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `CUStringObject`, is the last substring
     * that matched the pattern.
     *
     * @return int The position of the last occurrence of the pattern in the string or `-1` if no such pattern was
     * found.
     */

    public function reLastIndexOf ($sOfPattern, $iStartPos = 0, &$rsFoundString = null)
    {
        $sOfPattern = self::ensureUModifier($sOfPattern);
        $xRet = CRegex::lastIndexOf($this, $sOfPattern, $iStartPos, $rsFoundString);
        $rsFoundString = to_oop($rsFoundString);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified regular expression pattern, optionally reporting the substring that
     * matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * @param  string $sFindPattern The searched pattern.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `CUStringObject`, is the first substring
     * that matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public function reFind ($sFindPattern, &$rsFoundString = null)
    {
        $sFindPattern = self::ensureUModifier($sFindPattern);
        $xRet = CRegex::find($this, $sFindPattern, $rsFoundString);
        $rsFoundString = to_oop($rsFoundString);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified regular expression pattern, starting the search from a specified
     * position and optionally reporting the substring that matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * **NOTE.** Unlike the non-regex methods of the class, which count positions within a Unicode string in
     * characters, the PCRE engine and therefore this method count positions in bytes.
     *
     * @param  string $sFindPattern The searched pattern.
     * @param  int $iStartPos The starting position for the search.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `CUStringObject`, is the first substring
     * that matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public function reFindFrom ($sFindPattern, $iStartPos, &$rsFoundString = null)
    {
        $sFindPattern = self::ensureUModifier($sFindPattern);
        $xRet = CRegex::findFrom($this, $sFindPattern, $iStartPos, $rsFoundString);
        $rsFoundString = to_oop($rsFoundString);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified regular expression pattern, putting every substring that matches a
     * regular expression group into an array for output and optionally reporting the substring that matched the
     * pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * @param  string $sFindPattern The searched pattern, usually with groups.
     * @param  reference $raFoundGroups **OUTPUT.** If the pattern was found, this is an array of type `CArrayObject`
     * containing the substrings that matched the groups in the pattern, in the same order, if any.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `CUStringObject`, is the first substring
     * that matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public function reFindGroups ($sFindPattern, &$raFoundGroups, &$rsFoundString = null)
    {
        $sFindPattern = self::ensureUModifier($sFindPattern);
        $xRet = CRegex::findGroups($this, $sFindPattern, $raFoundGroups, $rsFoundString);
        $raFoundGroups = to_oop($raFoundGroups);
        $rsFoundString = to_oop($rsFoundString);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified regular expression pattern, starting the search from a specified
     * position and putting every substring that matches a regular expression group into an array for output,
     * optionally reporting the substring that matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * **NOTE.** Unlike the non-regex methods of the class, which count positions within a Unicode string in
     * characters, the PCRE engine and therefore this method count positions in bytes.
     *
     * @param  string $sFindPattern The searched pattern, usually with groups.
     * @param  int $iStartPos The starting position for the search.
     * @param  reference $raFoundGroups **OUTPUT.** If the pattern was found, this is an array of type `CArrayObject`
     * containing the substrings that matched the groups in the pattern, in the same order, if any.
     * @param  reference $rsFoundString **OPTIONAL. OUTPUT.** If the pattern has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `CUStringObject`, is the first substring
     * that matched the pattern.
     *
     * @return bool `true` if the pattern was found in the string, `false` otherwise.
     */

    public function reFindGroupsFrom ($sFindPattern, $iStartPos, &$raFoundGroups, &$rsFoundString = null)
    {
        $sFindPattern = self::ensureUModifier($sFindPattern);
        $xRet = CRegex::findGroupsFrom($this, $sFindPattern, $iStartPos, $raFoundGroups, $rsFoundString);
        $raFoundGroups = to_oop($raFoundGroups);
        $rsFoundString = to_oop($rsFoundString);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many matches of a specified regular expression pattern there are in a string, optionally reporting the
     * substrings that matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * @param  string $sFindPattern The searched pattern.
     * @param  reference $raFoundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArrayObject` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public function reFindAll ($sFindPattern, &$raFoundStrings = null)
    {
        $sFindPattern = self::ensureUModifier($sFindPattern);
        $xRet = CRegex::findAll($this, $sFindPattern, $raFoundStrings);
        $raFoundStrings = to_oop($raFoundStrings);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many matches of a specified regular expression pattern there are in a string, starting the search from
     * a specified position and optionally reporting the substrings that matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * **NOTE.** Unlike the non-regex methods of the class, which count positions within a Unicode string in
     * characters, the PCRE engine and therefore this method count positions in bytes.
     *
     * @param  string $sFindPattern The searched pattern.
     * @param  int $iStartPos The starting position for the search.
     * @param  reference $raFoundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArrayObject` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public function reFindAllFrom ($sFindPattern, $iStartPos, &$raFoundStrings = null)
    {
        $sFindPattern = self::ensureUModifier($sFindPattern);
        $xRet = CRegex::findAllFrom($this, $sFindPattern, $iStartPos, $raFoundStrings);
        $raFoundStrings = to_oop($raFoundStrings);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many matches of a specified regular expression pattern there are in a string, putting every substring
     * that matches a regular expression group into an array for output so that each match of the pattern is associated
     * with an array of group matches and optionally reporting the substrings that matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * @param  string $sFindPattern The searched pattern, usually with groups.
     * @param  reference $raFoundGroupArrays **OUTPUT.** If any patterns were found, this is a two-dimensional array of
     * type `CArrayObject`, per each pattern match containing an array of the substrings that matched the groups in the
     * pattern, in the same order, if any.
     * @param  reference $raFoundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArrayObject` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public function reFindAllGroups ($sFindPattern, &$raFoundGroupArrays, &$raFoundStrings = null)
    {
        $sFindPattern = self::ensureUModifier($sFindPattern);
        $xRet = CRegex::findAllGroups($this, $sFindPattern, $raFoundGroupArrays, $raFoundStrings);
        $raFoundGroupArrays = to_oop($raFoundGroupArrays);
        $raFoundStrings = to_oop($raFoundStrings);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many matches of a specified regular expression pattern there are in a string, starting the search from
     * a specified position and putting every substring that matches a regular expression group into an array for
     * output so that each match of the pattern is associated with an array of group matches, optionally reporting the
     * substrings that matched the pattern.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * **NOTE.** Unlike the non-regex methods of the class, which count positions within a Unicode string in
     * characters, the PCRE engine and therefore this method count positions in bytes.
     *
     * @param  string $sFindPattern The searched pattern, usually with groups.
     * @param  int $iStartPos The starting position for the search.
     * @param  reference $raFoundGroupArrays **OUTPUT.** If any patterns were found, this is a two-dimensional array of
     * type `CArrayObject`, per each pattern match containing an array of the substrings that matched the groups in the
     * pattern, in the same order, if any.
     * @param  reference $raFoundStrings **OPTIONAL. OUTPUT.** If any patterns have been found after the method was
     * called with this parameter provided, the parameter's value is an array of type `CArrayObject` containing the
     * substrings that matched the pattern, in the order of appearance.
     *
     * @return int The number of matches of the pattern in the string.
     */

    public function reFindAllGroupsFrom ($sFindPattern, $iStartPos, &$raFoundGroupArrays, &$raFoundStrings = null)
    {
        $sFindPattern = self::ensureUModifier($sFindPattern);
        $xRet = CRegex::findAllGroupsFrom($this, $sFindPattern, $iStartPos, $raFoundGroupArrays,
            $raFoundStrings);
        $raFoundGroupArrays = to_oop($raFoundGroupArrays);
        $raFoundStrings = to_oop($raFoundStrings);
        return $xRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a regular expression pattern in a string with a specified string and returns the new
     * string, optionally reporting the number of replacements made.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * @param  string $sWhatPattern The pattern to be replaced.
     * @param  string $sWith The replacement string.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return CUStringObject The resulting string.
     */

    public function reReplace ($sWhatPattern, $sWith, &$riQuantity = null)
    {
        $sWhatPattern = self::ensureUModifier($sWhatPattern);
        return CRegex::replace($this, $sWhatPattern, $sWith, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any occurrence of a regular expression pattern in a string with the string returned by a function or
     * method called on the matching substring and returns the new string, optionally reporting the number of
     * replacements made.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * @param  string $sWhatPattern The pattern to be replaced.
     * @param  callable $fnCallback A function or method that, as imposed by PHP's PCRE, takes a map as a parameter,
     * which contains the matching substring under the key of `0`, the substring that matched the first group, if any,
     * under the key of `1`, and so on for groups, and returns the string with which the matching substring should be
     * replaced.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return CUStringObject The resulting string.
     */

    public function reReplaceWithCallback ($sWhatPattern, $fnCallback, &$riQuantity = null)
    {
        $sWhatPattern = self::ensureUModifier($sWhatPattern);
        $fnUseCallback = function ($mMatches) use ($fnCallback)
            {
                $mMatches = to_oop($mMatches);
                return call_user_func($fnCallback, $mMatches);
            };
        return CRegex::replaceWithCallback($this, $sWhatPattern, $fnUseCallback, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a regular expression pattern in a string and returns the new string, optionally
     * reporting the number of removals made.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * @param  string $sWhatPattern The pattern to be removed.
     * @param  reference $riQuantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return CUStringObject The resulting string.
     */

    public function reRemove ($sWhatPattern, &$riQuantity = null)
    {
        $sWhatPattern = self::ensureUModifier($sWhatPattern);
        return CRegex::remove($this, $sWhatPattern, $riQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Splits a string into substrings using a specified regular expression pattern or patterns as the delimiter(s) and
     * returns the resulting strings as an array.
     *
     * If no delimiter patterns were found, the resulting array contains just one element, which is the original
     * string. If a delimiter is located at the very start or at the very end of the string or next to another
     * delimiter, it will accordingly cause some string(s) in the resulting array to be empty.
     *
     * As a special case, the delimiter pattern can be empty, which will split the string into its constituting
     * characters.
     *
     * The "u" modifier in the pattern is optional. If the pattern does not contain the "u" modifier, which is to turn
     * on the Unicode mode for a regular expression, this method will add the modifier when passing the pattern to the
     * PCRE engine.
     *
     * @param  string|array|map $xDelimiterPatternOrPatterns The pattern or array of patterns to be recognized as the
     * delimiter(s).
     *
     * @return CArrayObject The resulting strings of type `CUStringObject`.
     */

    public function reSplit ($xDelimiterPatternOrPatterns)
    {
        $xDelimiterPatternOrPatterns = self::ensureUModifier($xDelimiterPatternOrPatterns);
        return to_oop(CRegex::split($this, $xDelimiterPatternOrPatterns));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Escapes all characters that have a special meaning in the regular expression domain, and returns the escaped
     * string.
     *
     * With this method, you can prepare an arbitrary string to be used as a part of a regular expression.
     *
     * @param  string $sDelimiter **OPTIONAL. Default is** "/". The pattern delimiter that is going to be used by the
     * resulting regular expression and therefore needs to be escaped as well.
     *
     * @return CUStringObject The escaped string.
     */

    public function reEnterTd ($sDelimiter = CRegex::DEFAULT_PATTERN_DELIMITER)
    {
        return CRegex::enterTd($this, $sDelimiter);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    // public function offsetExists ($iOffset)
    // {
    //     assert( 'is_int($iOffset)', vs(isset($this), get_defined_vars()) );
    //     return ( 0 <= $iOffset && $iOffset < CUString::length($this) );
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    // public function offsetGet ($iOffset)
    // {
    //     return CUString::charAt($this, $iOffset);
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    // public function offsetSet ($iOffset, $sChar)
    // {
    //     CUString::setCharAt($this, $iOffset, $sChar);
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    // public function offsetUnset ($iOffset)
    // {
    //     assert( 'false', vs(isset($this), get_defined_vars()) );
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    protected static function ensureUModifier ($xPatternOrPatterns)
    {
        // Are we automatically adding the "u" modifier to any regular expression pattern that doesn't already have it?
        // So far, the answer is No.
        return $xPatternOrPatterns;

        // static $s_sSearchFor = "/u[A-Za-z]*\\z/";
        // if ( is_cstring($xPatternOrPatterns) )
        // {
        //     return ( preg_match($s_sSearchFor, $xPatternOrPatterns) !== 1 ) ? $xPatternOrPatterns . "u" :
        //         $xPatternOrPatterns;
        // }
        // if ( is_carray($xPatternOrPatterns) )
        // {
        //     $iLen = CArray::length($xPatternOrPatterns);
        //     for ($i = 0; $i < $iLen; $i++)
        //     {
        //         if ( preg_match($s_sSearchFor, $xPatternOrPatterns[$i]) !== 1 )
        //         {
        //             $xPatternOrPatterns[$i] = $xPatternOrPatterns[$i] . "u";
        //         }
        //     }
        //     return $xPatternOrPatterns;
        // }
        // if ( is_cmap($xPatternOrPatterns) )
        // {
        //     foreach ($xPatternOrPatterns as &$rsPattern)
        //     {
        //         if ( preg_match($s_sSearchFor, $rsPattern) !== 1 )
        //         {
        //             $rsPattern .= "u";
        //         }
        //     } unset($rsPattern);
        //     return $xPatternOrPatterns;
        // }
        // assert( 'false', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
