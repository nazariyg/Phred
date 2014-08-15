<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CUStringTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsValid ()
    {
        $this->assertTrue(CUString::isValid("¡Hola señor!"));
        $this->assertFalse(CUString::isValid("¡Hola\xFF\xFEseñor!"));
        $this->assertFalse(CUString::isValid("¡Hola\xFF\xFFseñor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSanitize ()
    {
        $this->assertTrue(
            CUString::equals(CUString::sanitize("¡Hola\xFF\xFEseñor!\xFF\xFF"), "¡Hola��señor!��"));
        $this->assertTrue(
            CUString::equals(CUString::sanitize("¡Hola señor!"), "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalize ()
    {
        $this->assertTrue(
            CUString::equals(CUString::normalize("¡Hola se\x6E\xCC\x83or!", CUString::NF_C), "¡Hola se\xC3\xB1or!"));
        $this->assertTrue(
            CUString::equals(CUString::normalize("¡Hola se\xC3\xB1or!", CUString::NF_D), "¡Hola se\x6E\xCC\x83or!"));
        $this->assertTrue(
            CUString::equals(CUString::normalize("¡Hola se\x6E\xCC\x83or ﬄ!", CUString::NF_KC),
            "¡Hola se\xC3\xB1or ffl!"));
        $this->assertTrue(
            CUString::equals(CUString::normalize("¡Hola se\xC3\xB1or ﬄ!", CUString::NF_KD),
            "¡Hola se\x6E\xCC\x83or ffl!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsNormalized ()
    {
        $this->assertTrue(CUString::isNormalized("¡Hola se\xC3\xB1or!", CUString::NF_C));
        $this->assertFalse(CUString::isNormalized("¡Hola se\x6E\xCC\x83or!", CUString::NF_C));

        $this->assertTrue(CUString::isNormalized("¡Hola se\x6E\xCC\x83or!", CUString::NF_D));
        $this->assertFalse(CUString::isNormalized("¡Hola se\xC3\xB1or!", CUString::NF_D));

        $this->assertTrue(CUString::isNormalized("¡Hola se\xC3\xB1or ffl!", CUString::NF_KC));
        $this->assertFalse(CUString::isNormalized("¡Hola se\x6E\xCC\x83or ﬄ!", CUString::NF_KC));

        $this->assertTrue(CUString::isNormalized("¡Hola se\x6E\xCC\x83or ffl!", CUString::NF_KD));
        $this->assertFalse(CUString::isNormalized("¡Hola se\xC3\xB1or ﬄ!", CUString::NF_KD));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBool10 ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromBool10(true), "1"));
        $this->assertTrue(
            CUString::equals(CUString::fromBool10(false), "0"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolTf ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromBoolTf(true), "true"));
        $this->assertTrue(
            CUString::equals(CUString::fromBoolTf(false), "false"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolYn ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromBoolYn(true), "yes"));
        $this->assertTrue(
            CUString::equals(CUString::fromBoolYn(false), "no"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolOo ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromBoolOo(true), "on"));
        $this->assertTrue(
            CUString::equals(CUString::fromBoolOo(false), "off"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromInt ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromInt(1234), "1234"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromFloat ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromFloat(12.34), "12.34"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCharCode ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromCharCode(0x0041), "A"));
        $this->assertTrue(
            CUString::equals(CUString::fromCharCode(0x00C6), "Æ"));
        $this->assertTrue(
            CUString::equals(CUString::fromCharCode(0x270C), "✌"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCharCodeHex ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromCharCodeHex("41"), "A"));
        $this->assertTrue(
            CUString::equals(CUString::fromCharCodeHex("0041"), "A"));
        $this->assertTrue(
            CUString::equals(CUString::fromCharCodeHex("00C6"), "Æ"));
        $this->assertTrue(
            CUString::equals(CUString::fromCharCodeHex("270C"), "✌"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCharCodeEsc ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromCharCodeEsc("\\u0041"), "A"));
        $this->assertTrue(
            CUString::equals(CUString::fromCharCodeEsc("\\u00C6"), "Æ"));
        $this->assertTrue(
            CUString::equals(CUString::fromCharCodeEsc("\\u270C"), "✌"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromEscString ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fromEscString("\\u00A1Hola se\\u00F1or!"), "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBool ()
    {
        $this->assertTrue( CUString::toBool("1") === true );
        $this->assertTrue( CUString::toBool("true") === true );
        $this->assertTrue( CUString::toBool("yes") === true );
        $this->assertTrue( CUString::toBool("on") === true );

        $this->assertTrue( CUString::toBool("0") === false );
        $this->assertTrue( CUString::toBool("false") === false );
        $this->assertTrue( CUString::toBool("no") === false );
        $this->assertTrue( CUString::toBool("off") === false );
        $this->assertTrue( CUString::toBool("maybe") === false );
        $this->assertTrue( CUString::toBool("01") === false );
        $this->assertTrue( CUString::toBool("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFrom10 ()
    {
        $this->assertTrue( CUString::toBoolFrom10("1") === true );
        $this->assertTrue( CUString::toBoolFrom10("0") === false );

        $this->assertTrue( CUString::toBoolFrom10("maybe") === false );
        $this->assertTrue( CUString::toBoolFrom10("01") === false );
        $this->assertTrue( CUString::toBoolFrom10("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromTf ()
    {
        $this->assertTrue( CUString::toBoolFromTf("true") === true );
        $this->assertTrue( CUString::toBoolFromTf("false") === false );

        $this->assertTrue( CUString::toBoolFromTf("maybe") === false );
        $this->assertTrue( CUString::toBoolFromTf("01") === false );
        $this->assertTrue( CUString::toBoolFromTf("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromYn ()
    {
        $this->assertTrue( CUString::toBoolFromYn("yes") === true );
        $this->assertTrue( CUString::toBoolFromYn("no") === false );

        $this->assertTrue( CUString::toBoolFromYn("maybe") === false );
        $this->assertTrue( CUString::toBoolFromYn("01") === false );
        $this->assertTrue( CUString::toBoolFromYn("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromOo ()
    {
        $this->assertTrue( CUString::toBoolFromOo("on") === true );
        $this->assertTrue( CUString::toBoolFromOo("off") === false );

        $this->assertTrue( CUString::toBoolFromOo("maybe") === false );
        $this->assertTrue( CUString::toBoolFromOo("01") === false );
        $this->assertTrue( CUString::toBoolFromOo("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToInt ()
    {
        $this->assertTrue( CUString::toInt("1234") === 1234 );
        $this->assertTrue( CUString::toInt("01234") === 1234 );
        $this->assertTrue( CUString::toInt("001234") === 1234 );
        $this->assertTrue( CUString::toInt("-1234") === -1234 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToIntFromHex ()
    {
        $this->assertTrue( CUString::toIntFromHex("100") === 256 );
        $this->assertTrue( CUString::toIntFromHex("0x100") === 256 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToIntFromBase ()
    {
        $this->assertTrue( CUString::toIntFromBase("100", 8) === 64 );
        $this->assertTrue( CUString::toIntFromBase("1234", 10) === 1234 );
        $this->assertTrue( CUString::toIntFromBase("100", 16) === 256 );
        $this->assertTrue( CUString::toIntFromBase("0x100", 16) === 256 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToFloat ()
    {
        $this->assertTrue( CUString::toFloat("12.34") === 12.34 );
        $this->assertTrue( CUString::toFloat("1e2") === 100.0 );
        $this->assertTrue( CUString::toFloat("1e+2") === 100.0 );
        $this->assertTrue( CUString::toFloat("1e-2") === 0.01 );
        $this->assertTrue( CUString::toFloat("1E+2") === 100.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToCharCode ()
    {
        $this->assertTrue( CUString::toCharCode("A") == 0x0041 );
        $this->assertTrue( CUString::toCharCode("\x00") == 0x0000 );
        $this->assertTrue( CUString::toCharCode("\t") == 0x0009 );

        $this->assertTrue( CUString::toCharCode("ñ") == 0x00F1 );
        $this->assertTrue( CUString::toCharCode("Æ") == 0x00C6 );
        $this->assertTrue( CUString::toCharCode("✌") == 0x270C );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToCharCodeHex ()
    {
        $this->assertTrue(
            CUString::equals(CUString::toCharCodeHex("A"), "0041"));
        $this->assertTrue(
            CUString::equals(CUString::toCharCodeHex("\t"), "0009"));

        $this->assertTrue(
            CUString::equals(CUString::toCharCodeHex("ñ"), "00F1"));
        $this->assertTrue(
            CUString::equals(CUString::toCharCodeHex("Æ"), "00C6"));
        $this->assertTrue(
            CUString::equals(CUString::toCharCodeHex("✌"), "270C"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToCharCodeEsc ()
    {
        $this->assertTrue(
            CUString::equals(CUString::toCharCodeEsc("A"), "\\u0041"));
        $this->assertTrue(
            CUString::equals(CUString::toCharCodeEsc("\t"), "\\u0009"));

        $this->assertTrue(
            CUString::equals(CUString::toCharCodeEsc("ñ"), "\\u00F1"));
        $this->assertTrue(
            CUString::equals(CUString::toCharCodeEsc("Æ"), "\\u00C6"));
        $this->assertTrue(
            CUString::equals(CUString::toCharCodeEsc("✌"), "\\u270C"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToEscString ()
    {
        $this->assertTrue(
            CUString::equals(CUString::toEscString("ABC"), "\\u0041\\u0042\\u0043"));
        $this->assertTrue(
            CUString::equals(CUString::toEscString("\t"), "\\u0009"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $this->assertTrue( CUString::length("¡Hola señor!") == 12 );
        $this->assertTrue( CUString::length("¡Hola señor! Æ✌") == 15 );
        $this->assertTrue( CUString::length("") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $this->assertFalse(CUString::isEmpty("¡Hola señor!"));
        $this->assertTrue(CUString::isEmpty(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCharAt ()
    {
        $this->assertTrue(
            CUString::equals(CUString::charAt("¡Hola señor!", 0), "¡"));
        $this->assertTrue(
            CUString::equals(CUString::charAt("¡Hola señor!", 1), "H"));
        $this->assertTrue(
            CUString::equals(CUString::charAt("¡Hola señor!", 8), "ñ"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetCharAt ()
    {
        $sString = "¡Hola señor!";
        CUString::setCharAt($sString, 0, " ");
        CUString::setCharAt($sString, 1, "L");
        CUString::setCharAt($sString, 8, "n");
        $this->assertTrue(
            CUString::equals($sString, " Lola senor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $this->assertTrue(
            CUString::equals("¡Hola señor!", "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals("¡Hola se\x6E\xCC\x83or!", "¡Hola se\xC3\xB1or!"));  // different normal forms
        $this->assertFalse(
            CUString::equals("¡Hola señor!", "¡Hola senor!"));
        $this->assertTrue(
            CUString::equals("", ""));

        $this->assertTrue(
            CUString::equals("¡Hola señor!", "¡Hola senor!", CUString::COLLATION_IGNORE_ACCENTS));
        $this->assertTrue(
            CUString::equals("¡Hola señor!", " Hola señor. ", CUString::COLLATION_IGNORE_NONWORD));
        $this->assertTrue(
            CUString::equals("¡Hola señor!", " Hola senor. ",
            CUString::COLLATION_IGNORE_ACCENTS | CUString::COLLATION_IGNORE_NONWORD));

        $this->assertFalse(
            CUString::equals("¡Hola señor!", "+Hola señor!", CUString::COLLATION_IGNORE_NONWORD));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEqualsCi ()
    {
        $this->assertTrue(
            CUString::equalsCi("¡Hola señor!", "¡HOLA SEÑOR!"));
        $this->assertTrue(
            CUString::equalsCi("¡Hola se\x6E\xCC\x83or!", "¡HOLA SE\xC3\x91OR!"));  // different normal forms
        $this->assertFalse(
            CUString::equalsCi("¡Hola señor!", "¡HOLA SENOR!"));
        $this->assertTrue(
            CUString::equalsCi("", ""));

        $this->assertTrue(
            CUString::equalsCi("¡Hola señor!", "¡HOLA SENOR!", CUString::COLLATION_IGNORE_ACCENTS));
        $this->assertTrue(
            CUString::equalsCi("¡Hola señor!", " HOLA SEÑOR. ", CUString::COLLATION_IGNORE_NONWORD));
        $this->assertTrue(
            CUString::equalsCi("¡Hola señor!", " HOLA SENOR. ",
            CUString::COLLATION_IGNORE_ACCENTS | CUString::COLLATION_IGNORE_NONWORD));

        $this->assertFalse(
            CUString::equalsCi("¡Hola señor!", "=HOLA SEÑOR!", CUString::COLLATION_IGNORE_NONWORD));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        $this->assertTrue( CUString::compare("¡Hola señor!", "¡Hola señor!") == 0 );
        $this->assertTrue( CUString::compare("A", "B") < 0 );
        $this->assertTrue( CUString::compare("a", "A") < 0 );
        $this->assertTrue( CUString::compare("C", "B") > 0 );
        $this->assertTrue( CUString::compare(" ", "a") < 0 );
        $this->assertTrue( CUString::compare("", "") == 0 );

        $this->assertTrue( CUString::compare("Ć", "Ĉ") < 0 );
        $this->assertTrue( CUString::compare("①", "②") < 0 );

        $this->assertTrue( CUString::compare("Ć", "Ĉ", CUString::COLLATION_IGNORE_ACCENTS) == 0 );
        $this->assertTrue( CUString::compare(" A,.", "A;:", CUString::COLLATION_IGNORE_NONWORD) == 0 );
        $this->assertTrue(
            CUString::compare(" Ć,.", "Ĉ;:",
            CUString::COLLATION_IGNORE_ACCENTS | CUString::COLLATION_IGNORE_NONWORD) == 0 );

        $this->assertTrue( CUString::compare("Å", "Z", CUString::COLLATION_DEFAULT,
            new CULocale(CULocale::ENGLISH_UNITED_STATES)) < 0 );
        $this->assertTrue( CUString::compare("Å", "Z", CUString::COLLATION_DEFAULT,
            new CULocale(CULocale::DANISH_DENMARK)) > 0 );

        $this->assertTrue( CUString::compare("a2", "a10") > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareCi ()
    {
        $this->assertTrue( CUString::compareCi("¡Hola señor!", "¡HOLA SEÑOR!") == 0 );
        $this->assertTrue( CUString::compareCi("a", "B") < 0 );
        $this->assertTrue( CUString::compareCi("a", "A") == 0 );
        $this->assertTrue( CUString::compareCi("C", "b") > 0 );
        $this->assertTrue( CUString::compareCi(" ", "a") < 0 );
        $this->assertTrue( CUString::compareCi("", "") == 0 );

        $this->assertTrue( CUString::compareCi("ć", "Ĉ") < 0 );
        $this->assertTrue( CUString::compareCi("①", "②") < 0 );

        $this->assertTrue( CUString::compareCi("ć", "Ĉ", CUString::COLLATION_IGNORE_ACCENTS) == 0 );
        $this->assertTrue( CUString::compareCi(" a,.", "A;:", CUString::COLLATION_IGNORE_NONWORD) == 0 );
        $this->assertTrue(
            CUString::compareCi(" ć,.", "Ĉ;:",
            CUString::COLLATION_IGNORE_ACCENTS | CUString::COLLATION_IGNORE_NONWORD) == 0 );

        $this->assertTrue(
            CUString::compareCi("Å", "z", CUString::COLLATION_DEFAULT,
            new CULocale(CULocale::ENGLISH_UNITED_STATES)) < 0 );
        $this->assertTrue(
            CUString::compareCi("å", "Z", CUString::COLLATION_DEFAULT,
            new CULocale(CULocale::DANISH_DENMARK)) > 0 );

        $this->assertTrue( CUString::compareCi("A2", "a10") > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareNat ()
    {
        $this->assertTrue( CUString::compareNat("¡Hola señor!", "¡Hola señor!") == 0 );
        $this->assertTrue( CUString::compareNat("A", "B") < 0 );
        $this->assertTrue( CUString::compareNat("a", "A") < 0 );
        $this->assertTrue( CUString::compareNat("C", "B") > 0 );
        $this->assertTrue( CUString::compareNat(" ", "a") < 0 );
        $this->assertTrue( CUString::compareNat("", "") == 0 );

        $this->assertTrue( CUString::compareNat("Ć", "Ĉ") < 0 );
        $this->assertTrue( CUString::compareNat("①", "②") < 0 );

        $this->assertTrue( CUString::compareNat("Ć", "Ĉ", CUString::COLLATION_IGNORE_ACCENTS) == 0 );
        $this->assertTrue( CUString::compareNat(" A,.", "A;:", CUString::COLLATION_IGNORE_NONWORD) == 0 );
        $this->assertTrue(
            CUString::compareNat(" Ć,.", "Ĉ;:",
            CUString::COLLATION_IGNORE_ACCENTS | CUString::COLLATION_IGNORE_NONWORD) == 0 );

        $this->assertTrue(
            CUString::compareNat("Å", "Z", CUString::COLLATION_DEFAULT,
            new CULocale(CULocale::ENGLISH_UNITED_STATES)) < 0 );
        $this->assertTrue(
            CUString::compareNat("Å", "Z", CUString::COLLATION_DEFAULT,
            new CULocale(CULocale::DANISH_DENMARK)) > 0 );

        $this->assertTrue( CUString::compareNat("a2", "a10") < 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareNatCi ()
    {
        $this->assertTrue( CUString::compareNatCi("¡Hola señor!", "¡HOLA SEÑOR!") == 0 );
        $this->assertTrue( CUString::compareNatCi("a", "B") < 0 );
        $this->assertTrue( CUString::compareNatCi("a", "A") == 0 );
        $this->assertTrue( CUString::compareNatCi("C", "b") > 0 );
        $this->assertTrue( CUString::compareNatCi(" ", "a") < 0 );
        $this->assertTrue( CUString::compareNatCi("", "") == 0 );

        $this->assertTrue( CUString::compareNatCi("ć", "Ĉ") < 0 );
        $this->assertTrue( CUString::compareNatCi("①", "②") < 0 );

        $this->assertTrue( CUString::compareNatCi("ć", "Ĉ", CUString::COLLATION_IGNORE_ACCENTS) == 0 );
        $this->assertTrue( CUString::compareNatCi(" a,.", "A;:", CUString::COLLATION_IGNORE_NONWORD) == 0 );
        $this->assertTrue(
            CUString::compareNatCi(" ć,.", "Ĉ;:",
            CUString::COLLATION_IGNORE_ACCENTS | CUString::COLLATION_IGNORE_NONWORD) == 0 );

        $this->assertTrue(
            CUString::compareNatCi("Å", "z", CUString::COLLATION_DEFAULT,
            new CULocale(CULocale::ENGLISH_UNITED_STATES)) < 0 );
        $this->assertTrue(
            CUString::compareNatCi("å", "Z", CUString::COLLATION_DEFAULT,
            new CULocale(CULocale::DANISH_DENMARK)) > 0 );

        $this->assertTrue( CUString::compareNatCi("A2", "a10") < 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLevenDist ()
    {
        $this->assertTrue( CUString::levenDist("åbc", "åbcd") == 1 );
        $this->assertTrue( CUString::levenDist("åbc", "bb cd") == 3 );
        $this->assertTrue( CUString::levenDist("", "") == 0 );

        $this->assertTrue( CUString::levenDist("señor", "senor") == 0 );
        $this->assertTrue( CUString::levenDist("やまもと, のぼる", "yamamoto, noboru") == 0 );
        $this->assertTrue( CUString::levenDist("やまもと, のぼる", "yamamoto, noboru", false) == 14 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMetaphoneKey ()
    {
        $this->assertTrue(
            CUString::equals(CUString::metaphoneKey("¡Hola"), "HL"));
        $this->assertTrue(
            CUString::equals(CUString::metaphoneKey("señor!"), "SNR"));
        $this->assertTrue(
            CUString::equals(CUString::metaphoneKey(""), ""));

        $this->assertTrue(
            CUString::equals(CUString::metaphoneKey("こんにちは"), "KNNXH"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMetaphoneDist ()
    {
        $this->assertTrue( CUString::metaphoneDist("¡Hola", "¡Hola") == 0 );
        $this->assertTrue( CUString::metaphoneDist("¡Hola", "Hola") == 0 );
        $this->assertTrue( CUString::metaphoneDist("¡Hola", "Hey") == 1 );
        $this->assertTrue( CUString::metaphoneDist("", "") == 0 );

        $this->assertTrue( CUString::metaphoneDist("こんにちは", "こんには") == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToLowerCase ()
    {
        $this->assertTrue(
            CUString::equals(CUString::toLowerCase("SEÑOR.1"), "señor.1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToUpperCase ()
    {
        $this->assertTrue(
            CUString::equals(CUString::toUpperCase("señor.1"), "SEÑOR.1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToUpperCaseFirst ()
    {
        $this->assertTrue(
            CUString::equals(CUString::toUpperCaseFirst("hola señor"), "Hola señor"));
        $this->assertTrue(
            CUString::equals(CUString::toUpperCaseFirst(""), ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToTitleCase ()
    {
        $this->assertTrue(
            CUString::equals(CUString::toTitleCase("¡hola señor you!"), "¡Hola Señor You!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStartsWith ()
    {
        $this->assertTrue(CUString::startsWith("¡Señor", "¡Señ"));
        $this->assertFalse(CUString::startsWith("¡Señor", "th"));
        $this->assertTrue(CUString::startsWith("¡Señor", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStartsWithCi ()
    {
        $this->assertTrue(CUString::startsWithCi("¡Señor", "¡SEÑ"));
        $this->assertFalse(CUString::startsWithCi("¡Señor", "TH"));
        $this->assertTrue(CUString::startsWithCi("¡Señor", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEndsWith ()
    {
        $this->assertTrue(CUString::endsWith("¡Señor", "ñor"));
        $this->assertFalse(CUString::endsWith("¡Señor", "ere"));
        $this->assertTrue(CUString::endsWith("¡Señor", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEndsWithCi ()
    {
        $this->assertTrue(CUString::endsWithCi("¡Señor", "ÑOR"));
        $this->assertFalse(CUString::endsWithCi("¡Señor", "ERE"));
        $this->assertTrue(CUString::endsWithCi("¡Señor", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIndexOf ()
    {
        $this->assertTrue( CUString::indexOf("¡Hola señor!", "señor") == 6 );
        $this->assertTrue( CUString::indexOf("¡Hola señor señor!", "señor", 7) == 12 );
        $this->assertTrue( CUString::indexOf("¡Hola señor!", "bye") == -1 );

        // Special cases.
        $this->assertTrue( CUString::indexOf("¡Hola señor!", "") == 0 );
        $this->assertTrue( CUString::indexOf("¡Hola señor!", "", 3) == 3 );
        $this->assertTrue( CUString::indexOf("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIndexOfCi ()
    {
        $this->assertTrue( CUString::indexOfCi("¡Hola señor!", "SEÑOR") == 6 );
        $this->assertTrue( CUString::indexOfCi("¡Hola señor señor!", "SEÑOR", 7) == 12 );
        $this->assertTrue( CUString::indexOfCi("¡Hola señor!", "BYE") == -1 );

        // Special cases.
        $this->assertTrue( CUString::indexOfCi("¡Hola señor!", "") == 0 );
        $this->assertTrue( CUString::indexOfCi("¡Hola señor!", "", 3) == 3 );
        $this->assertTrue( CUString::indexOfCi("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastIndexOf ()
    {
        $this->assertTrue( CUString::lastIndexOf("¡Hola señor señor!", "señor") == 12 );
        $this->assertTrue( CUString::lastIndexOf("¡Hola señor señor señor!", "señor", 7) == 18 );
        $this->assertTrue( CUString::lastIndexOf("¡Hola señor!", "bye") == -1 );

        // Special cases.
        $this->assertTrue( CUString::lastIndexOf("¡Hola señor!", "") == 12 );
        $this->assertTrue( CUString::lastIndexOf("¡Hola señor!", "", 3) == 12 );
        $this->assertTrue( CUString::lastIndexOf("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastIndexOfCi ()
    {
        $this->assertTrue( CUString::lastIndexOfCi("¡Hola señor señor!", "SEÑOR") == 12 );
        $this->assertTrue( CUString::lastIndexOfCi("¡Hola señor señor señor!", "SEÑOR", 7) == 18 );
        $this->assertTrue( CUString::lastIndexOfCi("¡Hola señor!", "BYE") == -1 );

        // Special cases.
        $this->assertTrue( CUString::lastIndexOfCi("¡Hola señor!", "") == 12 );
        $this->assertTrue( CUString::lastIndexOfCi("¡Hola señor!", "", 3) == 12 );
        $this->assertTrue( CUString::lastIndexOfCi("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $this->assertTrue(CUString::find("¡Hola señor!", "señor"));
        $this->assertFalse(CUString::find("¡Hola señor!", "senor"));
        $this->assertFalse(CUString::find("¡Hola señor!", "señor", 7));
        $this->assertFalse(CUString::find("¡Hola señor!", "bye"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindCi ()
    {
        $this->assertTrue(CUString::findCi("¡Hola señor!", "SEÑOR"));
        $this->assertFalse(CUString::findCi("¡Hola señor!", "SENOR"));
        $this->assertFalse(CUString::findCi("¡Hola señor!", "SEÑOR", 7));
        $this->assertFalse(CUString::findCi("¡Hola señor!", "BYE"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsSubsetOf ()
    {
        $this->assertTrue(CUString::isSubsetOf("¡Hola señor!", " ¡Holaseñr!"));
        $this->assertFalse(CUString::isSubsetOf("¡Hola señor!", "Abcde"));

        // Special cases.
        $this->assertFalse(CUString::isSubsetOf("", "Abcde"));
        $this->assertFalse(CUString::isSubsetOf("¡Hola señor!", ""));
        $this->assertTrue(CUString::isSubsetOf("", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubstr ()
    {
        $this->assertTrue(
            CUString::equals(CUString::substr("¡Hola señor!", 2), "ola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::substr("¡Hola señor!", 2, 3), "ola"));
        $this->assertTrue(
            CUString::equals(CUString::substr("¡Hola señor!", 0, 12), "¡Hola señor!"));

        // Special cases.
        $this->assertTrue(
            CUString::equals(CUString::substr("¡Hola señor!", 2, 0), ""));
        $this->assertTrue(
            CUString::equals(CUString::substr("¡Hola señor!", 12), ""));
        $this->assertTrue(
            CUString::equals(CUString::substr("¡Hola señor!", 12, 0), ""));
        $this->assertTrue(
            CUString::equals(CUString::substr("¡Hola señor!", 0, 0), ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubstring ()
    {
        $this->assertTrue(
            CUString::equals(CUString::substring("¡Hola señor!", 2, 5), "ola"));
        $this->assertTrue(
            CUString::equals(CUString::substring("¡Hola señor!", 0, 12), "¡Hola señor!"));

        // Special cases.
        $this->assertTrue(
            CUString::equals(CUString::substring("¡Hola señor!", 2, 2), ""));
        $this->assertTrue(
            CUString::equals(CUString::substring("¡Hola señor!", 12, 12), ""));
        $this->assertTrue(
            CUString::equals(CUString::substring("¡Hola señor!", 0, 0), ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumSubstrings ()
    {
        $this->assertTrue( CUString::numSubstrings("¡Hola señor!", "o") == 2 );
        $this->assertTrue( CUString::numSubstrings("¡Hola señor!", "o", 3) == 1 );
        $this->assertTrue( CUString::numSubstrings("¡Hola señor señor!", "ñ") == 2 );
        $this->assertTrue( CUString::numSubstrings("¡Hola señor señor!", "señor") == 2 );
        $this->assertTrue( CUString::numSubstrings("¡Hola señor!", "x") == 0 );
        $this->assertTrue( CUString::numSubstrings("¡Hola señor!", "Ñ") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplit ()
    {
        $aRes = CUString::split("¡Ho,la,señ,or!", ",");
        $this->assertTrue( CArray::length($aRes) == 4 &&
            CUString::equals($aRes[0], "¡Ho") && CUString::equals($aRes[1], "la") &&
            CUString::equals($aRes[2], "señ") && CUString::equals($aRes[3], "or!") );

        $aRes = CUString::split("¡Ho,la·señ.or!", CArray::fromElements(",", "·", ".", "!"));
        $this->assertTrue( CArray::length($aRes) == 5 &&
            CUString::equals($aRes[0], "¡Ho") && CUString::equals($aRes[1], "la") &&
            CUString::equals($aRes[2], "señ") && CUString::equals($aRes[3], "or") && CUString::equals($aRes[4], "") );

        // Special cases.

        $aRes = CUString::split("Hey", "");
        $this->assertTrue( CArray::length($aRes) == 3 &&
            CUString::equals($aRes[0], "H") && CUString::equals($aRes[1], "e") && CUString::equals($aRes[2], "y") );

        $aRes = CUString::split("", "");
        $this->assertTrue( CArray::length($aRes) == 1 && CUString::equals($aRes[0], "") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplitIntoChars ()
    {
        $aRes = CUString::splitIntoChars("Señor");
        $this->assertTrue( CArray::length($aRes) == 5 &&
            CUString::equals($aRes[0], "S") && CUString::equals($aRes[1], "e") && CUString::equals($aRes[2], "ñ") &&
            CUString::equals($aRes[3], "o") && CUString::equals($aRes[4], "r") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrimStart ()
    {
        $this->assertTrue(
            CUString::equals(CUString::trimStart(" \n\r\t \xE2\x80\xA8 \xE2\x80\xA9¡Hola señor!"), "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrimEnd ()
    {
        $this->assertTrue(
            CUString::equals(CUString::trimEnd("¡Hola señor! \n\r\t \xE2\x80\xA8 \xE2\x80\xA9"), "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrim ()
    {
        $this->assertTrue(
            CUString::equals(CUString::trim(
            " \n\r\t \xE2\x80\xA8 \xE2\x80\xA9¡Hola señor! \n\r\t \xE2\x80\xA8 \xE2\x80\xA9"), "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormSpacing ()
    {
        $this->assertTrue(
            CUString::equals(CUString::normSpacing(
            " \n\r\t \xE2\x80\xA8 \xE2\x80\xA9¡Hola \n\r\t \xE2\x80\xA8 \xE2\x80\xA9 \n\r\t \xE2\x80\xA8 \xE2\x80\xA9" .
            "señor! \n\r\t \xE2\x80\xA8 \xE2\x80\xA9"), "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::normSpacing("\n¡Hola\n\nseñor!\n"), "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormNewlines ()
    {
        $this->assertTrue(
            CUString::equals(CUString::normNewlines("¡Hola\r\n\xE2\x80\xA8señor!\r\n\r\v\f\xE2\x80\xA9"),
            "¡Hola\n\nseñor!\n\n\n\n\n"));
        $this->assertTrue(
            CUString::equals(CUString::normNewlines("¡Hola\r\n\xE2\x80\xA8señor!\r\n\r\v\f\xE2\x80\xA9", "\r"),
            "¡Hola\r\rseñor!\r\r\r\r\r"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadStart ()
    {
        $this->assertTrue(
            CUString::equals(CUString::padStart("¡Hola señor!", "-", 14), "--¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadEnd ()
    {
        $this->assertTrue(
            CUString::equals(CUString::padEnd("¡Hola señor!", "-", 14), "¡Hola señor!--"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripStart ()
    {
        $this->assertTrue(
            CUString::equals(CUString::stripStart("xx¡Hola señor!", "xx"), "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripStart("xxyy¡Hola señor!", CArray::fromElements("xx", "yy")),
            "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripStart("xxyy¡Hola señor!", CArray::fromElements("yy", "xx")),
            "yy¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripStart("xx¡Hola señor!", "yy"), "xx¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripStartCi ()
    {
        $this->assertTrue(
            CUString::equals(CUString::stripStartCi("xx¡Hola señor!", "XX"), "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripStartCi("xxyy¡Hola señor!",
            CArray::fromElements("XX", "YY")), "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripStartCi("xxyy¡Hola señor!",
            CArray::fromElements("YY", "XX")), "yy¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripStartCi("xx¡Hola señor!", "YY"), "xx¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripEnd ()
    {
        $this->assertTrue(
            CUString::equals(CUString::stripEnd("¡Hola señor!xx", "xx"), "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripEnd("¡Hola señor!yyxx", CArray::fromElements("xx", "yy")), "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripEnd("¡Hola señor!yyxx", CArray::fromElements("yy", "xx")),
            "¡Hola señor!yy"));
        $this->assertTrue(
            CUString::equals(CUString::stripEnd("¡Hola señor!xx", "yy"), "¡Hola señor!xx"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripEndCi ()
    {
        $this->assertTrue(
            CUString::equals(CUString::stripEndCi("¡Hola señor!xx", "XX"), "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripEndCi("¡Hola señor!yyxx", CArray::fromElements("XX", "YY")),
            "¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::stripEndCi("¡Hola señor!yyxx",
            CArray::fromElements("YY", "XX")), "¡Hola señor!yy"));
        $this->assertTrue(
            CUString::equals(CUString::stripEndCi("¡Hola señor!xx", "YY"), "¡Hola señor!xx"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsert ()
    {
        $this->assertTrue(
            CUString::equals(CUString::insert("¡Hola señor!", 6, "you "), "¡Hola you señor!"));
        $this->assertTrue(
            CUString::equals(CUString::insert("¡Hola señor!", 0, "you "), "you ¡Hola señor!"));
        $this->assertTrue(
            CUString::equals(CUString::insert("¡Hola señor!", 12, " you"), "¡Hola señor! you"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceSubstring ()
    {
        $this->assertTrue(
            CUString::equals(CUString::replaceSubstring("¡Hola señor!", 6, 5, "you"), "¡Hola you!"));
        $this->assertTrue(
            CUString::equals(CUString::replaceSubstring("¡Hola señor!", 0, 0, "you "), "you ¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceSubstringByRange ()
    {
        $this->assertTrue(
            CUString::equals(CUString::replaceSubstringByRange("¡Hola señor!", 6, 11, "you"), "¡Hola you!"));
        $this->assertTrue(
            CUString::equals(CUString::replaceSubstringByRange("¡Hola señor!", 0, 0, "you "), "you ¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubstring ()
    {
        $this->assertTrue(
            CUString::equals(CUString::removeSubstring("¡Hola señor!", 5, 6), "¡Hola!"));
        $this->assertTrue(
            CUString::equals(CUString::removeSubstring("¡Hola señor!", 5, 0), "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubstringByRange ()
    {
        $this->assertTrue(
            CUString::equals(CUString::removeSubstringByRange("¡Hola señor!", 5, 11), "¡Hola!"));
        $this->assertTrue(
            CUString::equals(CUString::removeSubstringByRange("¡Hola señor!", 5, 5), "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplace ()
    {
        $this->assertTrue(
            CUString::equals(CUString::replace("¡Hola señor!", "¡Hola", "Hello"), "Hello señor!"));

        $iQuantity;
        $this->assertTrue(
            CUString::equals(CUString::replace("¡Hola-Hola señor!", "Hola", "Hello", $iQuantity),
            "¡Hello-Hello señor!") && $iQuantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceCi ()
    {
        $this->assertTrue(
            CUString::equals(CUString::replaceCi("¡Hola señor!", "¡HOLA", "Hello"), "Hello señor!"));

        $iQuantity;
        $this->assertTrue(
            CUString::equals(CUString::replaceCi("¡Hola-Hola señor!", "HOLA", "Hello", $iQuantity),
            "¡Hello-Hello señor!") && $iQuantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $this->assertTrue(
            CUString::equals(CUString::remove("¡Hola señor!", " señor"), "¡Hola!"));

        $iQuantity;
        $this->assertTrue(
            CUString::equals(CUString::remove("¡Hola señor señor!", " señor", $iQuantity), "¡Hola!") &&
            $iQuantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveCi ()
    {
        $this->assertTrue(
            CUString::equals(CUString::removeCi("¡Hola señor!", " SEÑOR"), "¡Hola!"));

        $iQuantity;
        $this->assertTrue(
            CUString::equals(CUString::removeCi("¡Hola señor señor!", " SEÑOR", $iQuantity), "¡Hola!") &&
            $iQuantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShuffle ()
    {
        $sString = "るabĉdéfghijklmñopqrštuvwxŷzの";
        $sShuffledString = CUString::shuffle($sString);
        $this->assertTrue(CUString::isSubsetOf($sShuffledString, $sString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWordWrap ()
    {
        $this->assertTrue(
            CUString::equals(CUString::wordWrap(
            "¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!",
            21),
            "¡Hola señor! ¡Hola\nseñor! ¡Hola señor!\n¡Hola señor! ¡Hola\nseñor! ¡Hola señor!\n¡Hola señor! ¡Hola\n" .
            "señor!"));

        $this->assertTrue(
            CUString::equals(CUString::wordWrap(
            "¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!",
            4, CUString::WRAPPING_BREAK_SPACELESS_LINES),
            "¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\n" .
            "seño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!"));

        $this->assertTrue(
            CUString::equals(CUString::wordWrap(
            "¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!",
            4, CUString::WRAPPING_ALLOW_TRAILING_SPACES),
            "¡Hola \nseñor! \n¡Hola \nseñor! \n¡Hola \nseñor! \n¡Hola \nseñor! \n¡Hola \nseñor! \n¡Hola \nseñor! \n" .
            "¡Hola \nseñor! \n¡Hola \nseñor!"));

        $this->assertTrue(
            CUString::equals(CUString::wordWrap(
            "¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!",
            4, CUString::WRAPPING_DISALLOW_LEADING_SPACES),
            "¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\n" .
            "señor!\n¡Hola\nseñor!"));

        $this->assertTrue(
            CUString::equals(CUString::wordWrap(
            "¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!",
            21, CUString::WRAPPING_DEFAULT, "\r"),
            "¡Hola señor! ¡Hola \rseñor! ¡Hola señor! \r¡Hola señor! ¡Hola \rseñor! ¡Hola señor! \r" .
            "¡Hola señor! ¡Hola \rseñor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDecToHex ()
    {
        $this->assertTrue(
            CUString::equals(CUString::decToHex("256"), "100"));

        $this->assertTrue(
            CUString::equals(CUString::decToHex("⁵⁶⁷⁸"), "162E"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHexToDec ()
    {
        $this->assertTrue(
            CUString::equals(CUString::hexToDec("100"), "256"));
        $this->assertTrue(
            CUString::equals(CUString::hexToDec("0x100"), "256"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumberToBase ()
    {
        $this->assertTrue(
            CUString::equals(CUString::numberToBase("100", 8, 10), "64"));
        $this->assertTrue(
            CUString::equals(CUString::numberToBase("100", 16, 10), "256"));
        $this->assertTrue(
            CUString::equals(CUString::numberToBase("0x100", 16, 10), "256"));
        $this->assertTrue(
            CUString::equals(CUString::numberToBase("100", 8, 16), "40"));
        $this->assertTrue(
            CUString::equals(CUString::numberToBase("101", 2, 10), "5"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTransliterate ()
    {
        $this->assertTrue(
            CUString::equals(CUString::transliterate("やまもと, のぼる", "hiragana", "latin"), "yamamoto, noboru"));
        $this->assertTrue(
            CUString::equals(CUString::transliterate("Britney Spears", "latin", "katakana"), "ブリテネイ スペアルス"));
        $this->assertTrue(
            CUString::equals(CUString::transliterate("ブリテネイ スペアルス", "katakana", "latin; title"),
            "Buritenei Supearusu"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTransliterateFromAny ()
    {
        $this->assertTrue(
            CUString::equals(CUString::transliterateFromAny("やまもと, のぼる", "latin"), "yamamoto, noboru"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testApplyPublishingFilter ()
    {
        $this->assertTrue(
            CUString::equals(CUString::applyPublishingFilter("\"¡Hola -- señor!\""), "“¡Hola — señor!”"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHalfwidthToFullwidth ()
    {
        $this->assertTrue(
            CUString::equals(CUString::halfwidthToFullwidth("Hola"), "Ｈｏｌａ"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFullwidthToHalfwidth ()
    {
        $this->assertTrue(
            CUString::equals(CUString::fullwidthToHalfwidth("Ｈｏｌａ"), "Hola"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTransform ()
    {
        $this->assertTrue(
            CUString::equals(CUString::transform("やまもと, のぼる", "hiragana; latin; title"), "Yamamoto, Noboru"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRepeat ()
    {
        $this->assertTrue(
            CUString::equals(CUString::repeat("ñ", 5), "ñññññ"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasCjkChar ()
    {
        $this->assertTrue(CUString::hasCjkChar("Konichiwa やまもと"));
        $this->assertFalse(CUString::hasCjkChar("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
