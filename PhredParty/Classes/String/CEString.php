<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A collection of static methods that let you convert strings from one character encoding into another, "flatten" any
 * Unicode string to ASCII, find out the encoding of a string, and not get lost in the multitude of character encoding
 * names.
 *
 * **You can refer to this class by its alias, which is** `EStr`.
 *
 * Powered by the ICU library, this class is able to convert between practically all character encodings there are in
 * existence. For historical and other reasons, almost every of the supported character encodings is known by more than
 * one name. So for each character encoding ICU picked a single name by which it refers to the encoding internally.
 * Such character encoding names are called *primary* by this class and the rest of the names are called *aliases*. For
 * example, "UTF-8" is a primary character encoding name, while "ibm-1208" and "cp1208" are some of its many aliases
 * (and it's safe to say that "UTF-8" is an alias of "cp1208"). When you need to provide a character encoding name to a
 * method of this class, an alias is just as good as the primary name. In addition to other places, you can find the
 * ICU's selection of character encodings in the
 * [ICU's Converter Explorer](http://demo.icu-project.org/icu-bin/convexp).
 *
 * Excluding about 275 IBM character encodings, the primary names of the character encodings known to the class are
 * UTF-8, UTF-16, UTF-16BE, UTF-16LE, UTF-32, UTF-32BE, UTF-32LE, UTF16_PlatformEndian, UTF16_OppositeEndian,
 * UTF32_PlatformEndian, UTF32_OppositeEndian, UTF-16BE,version=1, UTF-16LE,version=1, UTF-16,version=1,
 * UTF-16,version=2, UTF-7, IMAP-mailbox-name, SCSU, BOCU-1, CESU-8, ISO-8859-1, US-ASCII, gb18030, iso-8859_10-1998,
 * iso-8859_11-2001, iso-8859_14-1998, windows-51932-2006, euc-jp-2007, windows-950-2000, windows-936-2000,
 * windows-949-2000, windows-874-2000, macos-0_2-10.2, macos-6_2-10.4, macos-7_3-10.2, macos-29-10.2, macos-35-10.2,
 * ISO_2022,locale=ja,version=0, ISO_2022,locale=ja,version=1, ISO_2022,locale=ja,version=2,
 * ISO_2022,locale=ja,version=3, ISO_2022,locale=ja,version=4, ISO_2022,locale=ko,version=0,
 * ISO_2022,locale=ko,version=1, ISO_2022,locale=zh,version=0, ISO_2022,locale=zh,version=1,
 * ISO_2022,locale=zh,version=2, HZ, x11-compound-text, ISCII,version=0, ISCII,version=1, ISCII,version=2,
 * ISCII,version=3, ISCII,version=4, ISCII,version=5, ISCII,version=6, ISCII,version=7, ISCII,version=8, LMBCS-1,
 * ebcdic-xml-us, gsm-03.38-2000, iso-8859_16-2001.
 */

// Method signatures:
//   static CUStringObject convert ($sString, $xFromEnc, $xToEnc)
//   static CUStringObject convertLatin1ToUtf8 ($sString)
//   static CUStringObject flattenUnicodeToAscii ($sString, $bTransliterate = true)
//   static bool looksLikeUtf8 ($sString)
//   static bool looksLikeAscii ($sString)
//   static bool looksLikeLatin1 ($sString)
//   static bool guessEnc ($sString, &$reEnc)
//   static CArrayObject knownPrimaryEncNames ()
//   static bool isEncNameKnown ($sName)
//   static CArrayObject encNameAliases ($sName)
//   static bool areEncNamesEquivalent ($sName0, $sName1)
//   static bool isEncNameUtf8 ($sName)
//   static bool isEncNameAscii ($sName)
//   static bool isEncNameLatin1 ($sName)
//   static CUStringObject fixUtf8 ($sString)
//   static CUStringObject fixUtf8More ($sString)

class CEString extends CRootClass
{
    // The main character encodings.
    /**
     * `enum` UTF-8 character encoding. This is the encoding that is assumed by default for any Unicode string.
     *
     * @var enum
     */
    const UTF8 = 0;
    /**
     * `enum` ASCII character encoding.
     *
     * @var enum
     */
    const ASCII = 1;
    /**
     * `enum` Latin-1 character encoding, formally known as ISO-8859-1.
     *
     * @var enum
     */
    const LATIN1 = 2;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string from one character encoding into another and returns the new string.
     *
     * If you specify a character encoding by name, you don't need to worry about the letter case used within the name
     * or about hyphens, underscores, or whitespace in it, the method will focus on just the meaningful parts and will
     * get any valid name right. You can also use any valid alias of a character encoding name.
     *
     * If the source string contains any bytes that are invalid in the character encoding declared for it, each of them
     * will come out as "�" (U+FFFD) when converting to Unicode or as "?" when converting to ASCII.
     *
     * @param  string $sString The string to be converted.
     * @param  enum|string $xFromEnc The character encoding of the string. This is either the enumerand of one of the
     * three main encodings, which are `UTF8`, `ASCII`, and `LATIN1`, or a character encoding name, primary one or an
     * alias.
     * @param  enum|string $xToEnc The output character encoding. This is either the enumerand of one of the three
     * main encodings, which are `UTF8`, `ASCII`, and `LATIN1`, or a character encoding name, primary one or an alias.
     *
     * @return CUStringObject The converted string.
     */

    public static function convert ($sString, $xFromEnc, $xToEnc)
    {
        assert( 'is_cstring($sString) && (is_cstring($xFromEnc) || is_enum($xFromEnc)) && ' .
                '(is_cstring($xToEnc) || is_enum($xToEnc))', vs(isset($this), get_defined_vars()) );
        assert( '(is_enum($xFromEnc) || self::isEncNameKnown($xFromEnc)) && ' .
                '(is_enum($xToEnc) || self::isEncNameKnown($xToEnc))', vs(isset($this), get_defined_vars()) );

        $aEncs = CArray::fromElements($xFromEnc, $xToEnc);
        for ($i = 0; $i < 2; $i++)
        {
            if ( is_enum($aEncs[$i]) )
            {
                switch ( $aEncs[$i] )
                {
                case self::UTF8:
                    $aEncs[$i] = "utf-8";
                    break;
                case self::ASCII:
                    $aEncs[$i] = "ascii";
                    break;
                case self::LATIN1:
                    $aEncs[$i] = "latin1";
                    break;
                default:
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                    break;
                }
            }
        }
        $sFromEnc = (string)$aEncs[0];
        $sToEnc = (string)$aEncs[1];

        $bDstEncIsUtf8 = self::areEncNamesEquivalent($sToEnc, "utf-8");
        $bDstEncIsOtherUtf = false;
        if ( !$bDstEncIsUtf8 )
        {
            $bDstEncIsOtherUtf = (
                self::areEncNamesEquivalent($sToEnc, "utf-16") ||
                self::areEncNamesEquivalent($sToEnc, "utf-32") ||
                self::areEncNamesEquivalent($sToEnc, "utf-16le") ||
                self::areEncNamesEquivalent($sToEnc, "utf-16be") ||
                self::areEncNamesEquivalent($sToEnc, "utf-32le") ||
                self::areEncNamesEquivalent($sToEnc, "utf-32be") );
        }

        // Set a substitution character for any invalid character/byte in the source string.
        $oQmConv = new UConverter($sToEnc, "utf-8");
        if ( $bDstEncIsUtf8 || $bDstEncIsOtherUtf )
        {
            // Targeting a Unicode encoding, use U+FFFD question mark.
            $sQm = $oQmConv->convert("\xEF\xBF\xBD");
        }
        else
        {
            // Targeting a non-Unicode encoding, use "?".
            $sQm = $oQmConv->convert("\x3F");
        }
        if ( !is_cstring($sQm) )
        {
            $sQm = "";
        }

        $oConv = new UConverter($sToEnc, $sFromEnc);
        $oConv->setSubstChars($sQm);
        $xRes = $oConv->convert($sString);
        $sRes = ( is_cstring($xRes) ) ? $xRes : "";
        if ( $bDstEncIsUtf8 || $bDstEncIsOtherUtf )
        {
            if ( $bDstEncIsUtf8 )
            {
                return $sRes;
            }
            else  // other Unicode encoding
            {
                return $sRes;
            }
        }
        else if ( self::areEncNamesEquivalent($sToEnc, "ascii") )
        {
            return $sRes;
        }
        else
        {
            return $sRes;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a Latin-1 (ISO-8859-1) string into a UTF-8 string and returns it.
     *
     * @param  string $sString The string to be converted.
     *
     * @return CUStringObject The converted string.
     */

    public static function convertLatin1ToUtf8 ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return self::convert($sString, self::LATIN1, self::UTF8);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts any Unicode string into ASCII, preserving as much information as possible, by default having
     * transliterated the string into the Latin script beforehand, and returns the new string.
     *
     * Flattening Unicode strings to ASCII might be useful for the purposes of indexing Unicode text, searching inside
     * or with Unicode text, and collating Unicode text.
     *
     * The flattening represents Latin characters from the Latin-1 Supplement, Latin Extended-A, and Latin Extended-B
     * blocks with one or more ASCII characters. Latin characters like "æ" and German sharp "ß" are all handled
     * properly ("æ" becomes "ae" and "ß" becomes "ss"). The method also converts to ASCII some of the beyond-Latin
     * Unicode characters that have similar appearance or meaning. The Unicode Line Separator and Paragraph Separator
     * characters are converted into ASCII LF characters. ASCII control characters (0x00-0x1F) are not affected.
     *
     * @param  string $sString The string to be flattened.
     * @param  bool $bTransliterate **OPTIONAL. Default is** `true`. Tells whether to transliterate the string into the
     * Latin script before flattening. This is what you would normally wish to happen for an arbitrary Unicode string
     * since there is no way of mapping e.g. Japanese characters to ASCII ones but the transliteration makes any string
     * in Japanese sound same or similar in Latin by rewriting it with Latin characters, after which mapping to ASCII
     * becomes straightforward. For example, "こんにちは" is transliterated to "kon'nichiha".
     *
     * @return CUStringObject The flattened string.
     */

    public static function flattenUnicodeToAscii ($sString, $bTransliterate = true)
    {
        assert( 'is_cstring($sString) && is_bool($bTransliterate)', vs(isset($this), get_defined_vars()) );

        if ( $bTransliterate )
        {
            // For Unicode texts that may contain scripts other than Latin.
            $oTranslit = Transliterator::create("latin");
            $sString = $oTranslit->transliterate($sString);
            if ( !is_cstring($sString) )
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
                return "";
            }
        }

        // Latin Extended-A (part 1). Goes first to avoid Middle dots being produced from U+013F and U+0140
        // L-with-a-dot characters by the upcoming NFKD normalization step and the subsequent conversion of Middle dots
        // into spaces by the Latin-1 Supplement mapping step.
        $sString = preg_replace(DEString::$FlatteningReplaceWhatPt1, DEString::$FlatteningReplaceWithPt1, $sString);

        // Decompose characters by compatibility and by canonical equivalence (NFKD). Gives ICU a chance to flatten on
        // its own some of the characters that can be represented by one or more lower-code characters. Combining
        // characters, such as marks, become isolated code points.
        $sString = Normalizer::normalize($sString, Normalizer::FORM_KD);
        if ( !is_cstring($sString) )
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }

        // Remove combining marks. NFKD normalization, unlike NFD, produces preceding spaces for lone marks, so remove
        // those spaces too.
        $sString = preg_replace("/\\x20?\\p{M}/u", "", $sString);

        // Part 2 of all the character-to-character replacements.
        $sString = preg_replace(DEString::$FlatteningReplaceWhatPt2, DEString::$FlatteningReplaceWithPt2, $sString);

        // In addition to the alias/cross-reference conversion performed earlier, convert all characters in the "Zs"
        // Unicode category into spaces.
        $sString = preg_replace("/\\p{Zs}/u", " ", $sString);

        // Remove all non-ASCII bytes.
        $sString = preg_replace("/[\\x80-\\xFF]/", "", $sString);

        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string appears to be a valid Unicode string encoded in UTF-8.
     *
     * Thanks to the fact that the ASCII charset and encoding are compatible with UTF-8, this method will return `true`
     * for any valid ASCII string.
     *
     * @param  string $sString The string to be looked into.
     *
     * @return bool `true` if the string looks like a valid UTF-8 string, `false` otherwise.
     */

    public static function looksLikeUtf8 ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $sPattern = "/^(" .
            "[\\x09\\x0A\\x0D\\x20-\\x7E]" .         // ASCII, some bytes
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
     * Determines if a string appears to be an ASCII string.
     *
     * The method will return `false` if the string contains any control characters other than HT (0x09), LF (0x0A),
     * and CR (0x0D).
     *
     * @param  string $sString The string to be looked into.
     *
     * @return bool `true` if the string looks like an ASCII string, `false` otherwise.
     */

    public static function looksLikeAscii ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $sPattern = "/^[\\x09\\x0A\\x0D\\x20-\\x7E]*\\z/";
        return CRegex::find($sString, $sPattern);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string appears to be a Latin-1 (ISO-8859-1) string.
     *
     * The method will return `false` if the string contains any control characters other than HT (0x09), LF (0x0A),
     * and CR (0x0D).
     *
     * @param  string $sString The string to be looked into.
     *
     * @return bool `true` if the string looks like a Latin-1 string, `false` otherwise.
     */

    public static function looksLikeLatin1 ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $sPattern = "/^[\\x09\\x0A\\x0D\\x20-\\x7E\\xA0-\\xFF]*\\z/";
        return CRegex::find($sString, $sPattern);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tries to determine the character encoding used by a string.
     *
     * For the reason that any ASCII string is indistinguishable from the same UTF-8 string, the character encoding of
     * a string is guessed only between UTF-8 and Latin-1.
     *
     * @param  string $sString The string to be looked into.
     * @param  reference $reEnc **OUTPUT.** If the method returned `true`, the value of this parameter is the enumerand
     * of the character encoding determined for the string, that is either `UTF8` or `LATIN1`.
     *
     * @return bool `true` if the method was able to guess the character encoding of the string, `false` otherwise.
     */

    public static function guessEnc ($sString, &$reEnc)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        if ( self::looksLikeUtf8($sString) )
        {
            $reEnc = self::UTF8;
            return true;
        }
        if ( self::looksLikeLatin1($sString) )
        {
            $reEnc = self::LATIN1;
            return true;
        }
        return false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the primary character encoding names that are known to the class.
     *
     * This method only outputs the primary character encoding names. Use `isEncNameKnown` method to find out if a
     * character encoding name, primary one or an alias, can be used with the class.
     *
     * @return CArrayObject The primary character encoding names of type `CUStringObject`.
     *
     * @link   #method_isEncNameKnown isEncNameKnown
     */

    public static function knownPrimaryEncNames ()
    {
        return oop_a(CArray::fromPArray(UConverter::getAvailable()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a character encoding name is known to the class and thus can be used with it, looking among known
     * aliases as well.
     *
     * You don't need to worry about the letter case used within the character encoding name or about hyphens,
     * underscores, or whitespace in it, the method will focus on just the meaningful parts and will get any valid name
     * right.
     *
     * @param  string $sName The character encoding name to be looked for.
     *
     * @return bool `true` if the name is known to the class, `false` otherwise.
     */

    public static function isEncNameKnown ($sName)
    {
        assert( 'is_cstring($sName)', vs(isset($this), get_defined_vars()) );
        return ( count(UConverter::getAliases($sName)) != 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the aliases that are known for a character encoding name.
     *
     * You don't need to worry about the letter case used within the character encoding name or about hyphens,
     * underscores, or whitespace in it, the method will focus on just the meaningful parts and will get any valid name
     * right.
     *
     * @param  string $sName The character encoding name to be looked for. Can be an alias itself.
     *
     * @return CArrayObject The name's aliases of type `CUStringObject`.
     */

    public static function encNameAliases ($sName)
    {
        assert( 'is_cstring($sName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::isEncNameKnown($sName)', vs(isset($this), get_defined_vars()) );

        return oop_a(CArray::fromPArray(UConverter::getAliases($sName)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a character encoding name refers to the same encoding as another name.
     *
     * The method looks into the aliases of the character encoding names too.
     *
     * You don't need to worry about the letter case used within the character encoding names or about hyphens,
     * underscores, or whitespace in them, the method will focus on just the meaningful parts and will get any valid
     * name right.
     *
     * @param  string $sName0 The first character encoding name for comparison.
     * @param  string $sName1 The second character encoding name for comparison.
     *
     * @return bool `true` if the two names are equivalent, `false` otherwise.
     */

    public static function areEncNamesEquivalent ($sName0, $sName1)
    {
        assert( 'is_cstring($sName0) && is_cstring($sName1)', vs(isset($this), get_defined_vars()) );

        if ( CString::equals(self::flattenEncName($sName0), self::flattenEncName($sName1)) )
        {
            return true;
        }

        $mAliases0 = UConverter::getAliases($sName0);
        $mAliases1 = UConverter::getAliases($sName1);
        foreach ($mAliases0 as $sAlias0)
        {
            foreach ($mAliases1 as $sAlias1)
            {
                if ( CString::equals($sAlias0, $sAlias1) )
                {
                    return true;
                }
            }
        }
        return false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a character encoding name refers to UTF-8.
     *
     * You don't need to worry about the letter case used within the character encoding name or about hyphens,
     * underscores, or whitespace in it, the method will focus on just the meaningful parts and will get any valid name
     * right.
     *
     * @param  string $sName The character encoding name to be looked into.
     *
     * @return bool `true` if the name refers to the UTF-8 character encoding, `false` otherwise.
     */

    public static function isEncNameUtf8 ($sName)
    {
        assert( 'is_cstring($sName)', vs(isset($this), get_defined_vars()) );
        return self::areEncNamesEquivalent($sName, "utf-8");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a character encoding name refers to ASCII.
     *
     * You don't need to worry about the letter case used within the character encoding name or about hyphens,
     * underscores, or whitespace in it, the method will focus on just the meaningful parts and will get any valid name
     * right.
     *
     * @param  string $sName The character encoding name to be looked into.
     *
     * @return bool `true` if the name refers to the ASCII character encoding, `false` otherwise.
     */

    public static function isEncNameAscii ($sName)
    {
        assert( 'is_cstring($sName)', vs(isset($this), get_defined_vars()) );
        return self::areEncNamesEquivalent($sName, "ascii");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a character encoding name refers to Latin-1 (ISO-8859-1).
     *
     * You don't need to worry about the letter case used within the character encoding name or about hyphens,
     * underscores, or whitespace in it, the method will focus on just the meaningful parts and will get any valid name
     * right.
     *
     * @param  string $sName The character encoding name to be looked into.
     *
     * @return bool `true` if the name refers to the Latin-1 (ISO-8859-1) character encoding, `false` otherwise.
     */

    public static function isEncNameLatin1 ($sName)
    {
        assert( 'is_cstring($sName)', vs(isset($this), get_defined_vars()) );
        return self::areEncNamesEquivalent($sName, "latin1");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /*
    Copyright (c) 2008 Sebastián Grignoli
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions
    are met:
    1. Redistributions of source code must retain the above copyright
       notice, this list of conditions and the following disclaimer.
    2. Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.
    3. Neither the name of copyright holders nor the names of its
       contributors may be used to endorse or promote products derived
       from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
    ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
    TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
    PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL COPYRIGHT HOLDERS OR CONTRIBUTORS
    BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
    CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
    CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.
    */

    /**
     * Tries to fix a presumably UTF-8 string that contains or may contain UTF-8 text mixed with Latin-1 (ISO-8859-1)
     * or Windows-1252 text and returns the new UTF-8 string.
     *
     * @param  string $sString The string to be fixed.
     *
     * @return CUStringObject The resulting string.
     */

    public static function fixUtf8 ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        $iMax = CString::length($sString);
        $sNewString = "";
        for ($i = 0; $i < $iMax; $i++)
        {
            $sC1 = $sString[$i];
            if ( $sC1 >= "\xC0" )
            {
                // Should be converted to UTF-8, if it is not UTF-8 already.
                $sC2 = ( $i + 1 >= $iMax ) ? "\x00" : $sString[$i + 1];
                $sC3 = ( $i + 2 >= $iMax ) ? "\x00" : $sString[$i + 2];
                $sC4 = ( $i + 3 >= $iMax ) ? "\x00" : $sString[$i + 3];
                if ( $sC1 >= "\xC0" & $sC1 <= "\xDF" )
                {
                    // Looks like 2 bytes of UTF-8.
                    if ( $sC2 >= "\x80" && $sC2 <= "\xBF" )
                    {
                        // Almost sure it is UTF-8 already.
                        $sNewString .= $sC1 . $sC2;
                        $i++;
                    }
                    else
                    {
                        // Not a valid UTF-8, convert it.
                        $sCc1 = CString::fromCharCode(CString::toCharCode($sC1) >> 6) | "\xC0";
                        $sCc2 = ($sC1 & "\x3F") | "\x80";
                        $sNewString .= $sCc1 . $sCc2;
                    }
                }
                else if ( $sC1 >= "\xE0" & $sC1 <= "\xEF" )
                {
                    // Looks like 3 bytes of UTF-8.
                    if ( $sC2 >= "\x80" && $sC2 <= "\xBF" && $sC3 >= "\x80" && $sC3 <= "\xBF" )
                    {
                        // Almost sure it is UTF-8 already.
                        $sNewString .= $sC1 . $sC2 . $sC3;
                        $i += 2;
                    }
                    else
                    {
                        // Not a valid UTF-8, convert it.
                        $sCc1 = CString::fromCharCode(CString::toCharCode($sC1) >> 6) | "\xC0";
                        $sCc2 = ($sC1 & "\x3F") | "\x80";
                        $sNewString .= $sCc1 . $sCc2;
                    }
                }
                else if ( $sC1 >= "\xF0" & $sC1 <= "\xF7" )
                {
                    // Looks like 4 bytes of UTF-8.
                    if ( $sC2 >= "\x80" && $sC2 <= "\xBF" && $sC3 >= "\x80" && $sC3 <= "\xBF" &&
                         $sC4 >= "\x80" && $sC4 <= "\xBF" )
                    {
                        // Almost sure it is UTF-8 already.
                        $sNewString .= $sC1 . $sC2 . $sC3;
                        $i += 2;
                    }
                    else
                    {
                        // Not a valid UTF-8, convert it.
                        $sCc1 = CString::fromCharCode(CString::toCharCode($sC1) >> 6) | "\xC0";
                        $sCc2 = ($sC1 & "\x3F") | "\x80";
                        $sNewString .= $sCc1 . $sCc2;
                    }
                }
                else
                {
                    // Does not look like UTF-8, but should be converted.
                    $sCc1 = CString::fromCharCode(CString::toCharCode($sC1) >> 6) | "\xC0";
                    $sCc2 = ($sC1 & "\x3F") | "\x80";
                    $sNewString .= $sCc1 . $sCc2;
                }
            }
            else if ( ($sC1 & "\xC0") === "\x80" )
            {
                // Needs to be converted.
                if ( isset(self::$ms_mWin1252ToUtf8[CString::toCharCode($sC1)]) )
                {
                    // Found in Windows-1252 special cases.
                    $sNewString .= self::$ms_mWin1252ToUtf8[CString::toCharCode($sC1)];
                }
                else
                {
                    $sCc1 = CString::fromCharCode(CString::toCharCode($sC1) >> 6) | "\xC0";
                    $sCc2 = ($sC1 & "\x3F") | "\x80";
                    $sNewString .= $sCc1 . $sCc2;
                }
            }
            else
            {
                // Needs no conversion.
                $sNewString .= $sC1;
            }
        }
        return $sNewString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /*
    Copyright (c) 2008 Sebastián Grignoli
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions
    are met:
    1. Redistributions of source code must retain the above copyright
       notice, this list of conditions and the following disclaimer.
    2. Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.
    3. Neither the name of copyright holders nor the names of its
       contributors may be used to endorse or promote products derived
       from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
    ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
    TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
    PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL COPYRIGHT HOLDERS OR CONTRIBUTORS
    BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
    CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
    CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.
    */

    /**
     * Tries to fix a garbled UTF-8 string, that is a string containing UTF-8 text mixed with Latin-1 (ISO-8859-1) or
     * Windows-1252 text that has been re-encoded into UTF-8 one or more times, and returns the new UTF-8 string.
     *
     * For example, if this method is given "FÃ©dÃ©ration Camerounaise de Football", it will return "Fédération
     * Camerounaise de Football".
     *
     * @param  string $sString The string to be fixed.
     *
     * @return CUStringObject The resulting string.
     */

    public static function fixUtf8More ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );

        for ($i = 0; $i < self::$ms_iFixUtf8MoreMaxNumIterations; $i++)
        {
            $sPrevString = $sString;
            $sString = str_replace(array_keys(self::$ms_mUtf8ToWin1252), array_values(self::$ms_mUtf8ToWin1252),
                $sString);
            $sString = self::convert($sString, self::UTF8, self::LATIN1);
            $sString = self::fixUtf8($sString);
            if ( CString::equals($sString, $sPrevString) )
            {
                break;
            }
        }
        return $sString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function flattenEncName ($sName)
    {
        // Make the encoding name lowercase and remove everything that is not a letter or digit.
        return CRegex::remove(CString::toLowerCase($sName), "/[^a-z0-9]/");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_mWin1252ToUtf8 = [
        128 => "\xE2\x82\xAC", 130 => "\xE2\x80\x9A", 131 => "\xC6\x92", 132 => "\xE2\x80\x9E",
        133 => "\xE2\x80\xA6", 134 => "\xE2\x80\xA0", 135 => "\xE2\x80\xA1", 136 => "\xCB\x86",
        137 => "\xE2\x80\xB0", 138 => "\xC5\xA0", 139 => "\xE2\x80\xB9", 140 => "\xC5\x92",
        142 => "\xC5\xBD", 145 => "\xE2\x80\x98", 146 => "\xE2\x80\x99", 147 => "\xE2\x80\x9C",
        148 => "\xE2\x80\x9D", 149 => "\xE2\x80\xA2", 150 => "\xE2\x80\x93", 151 => "\xE2\x80\x94",
        152 => "\xCB\x9C", 153 => "\xE2\x84\xA2", 154 => "\xC5\xA1", 155 => "\xE2\x80\xBA",
        156 => "\xC5\x93", 158 => "\xC5\xBE", 159 => "\xC5\xB8"];

    protected static $ms_mUtf8ToWin1252 = [
        "\xE2\x82\xAC" => "\x80", "\xE2\x80\x9A" => "\x82", "\xC6\x92" => "\x83", "\xE2\x80\x9E" => "\x84",
        "\xE2\x80\xA6" => "\x85", "\xE2\x80\xA0" => "\x86", "\xE2\x80\xA1" => "\x87", "\xCB\x86" => "\x88",
        "\xE2\x80\xB0" => "\x89", "\xC5\xA0" => "\x8A", "\xE2\x80\xB9" => "\x8B", "\xC5\x92" => "\x8C",
        "\xC5\xBD" => "\x8E", "\xE2\x80\x98" => "\x91", "\xE2\x80\x99" => "\x92", "\xE2\x80\x9C" => "\x93",
        "\xE2\x80\x9D" => "\x94", "\xE2\x80\xA2" => "\x95", "\xE2\x80\x93" => "\x96", "\xE2\x80\x94" => "\x97",
        "\xCB\x9C" => "\x98", "\xE2\x84\xA2" => "\x99", "\xC5\xA1" => "\x9A", "\xE2\x80\xBA" => "\x9B",
        "\xC5\x93" => "\x9C", "\xC5\xBE" => "\x9E", "\xC5\xB8" => "\x9F"];

    protected static $ms_iFixUtf8MoreMaxNumIterations = 8;
}
