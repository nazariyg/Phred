<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CUStringObjectTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $string = u("¡Hola señor!");
        $this->assertTrue($string->equals("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsValid ()
    {
        $this->assertTrue(u("¡Hola señor!")->isValid());
        $this->assertFalse(u("¡Hola\xFF\xFEseñor!")->isValid());
        $this->assertFalse(u("¡Hola\xFF\xFFseñor!")->isValid());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSanitize ()
    {
        $this->assertTrue(
            u("¡Hola\xFF\xFEseñor!\xFF\xFF")->sanitize()->equals("¡Hola��señor!��"));
        $this->assertTrue(
            u("¡Hola señor!")->sanitize()->equals("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalize ()
    {
        $this->assertTrue(
            u("¡Hola se\x6E\xCC\x83or!")->normalize(CUStringObject::NF_C)->equals("¡Hola se\xC3\xB1or!"));
        $this->assertTrue(
            u("¡Hola se\xC3\xB1or!")->normalize(CUStringObject::NF_D)->equals("¡Hola se\x6E\xCC\x83or!"));
        $this->assertTrue(
            u("¡Hola se\x6E\xCC\x83or ﬄ!")->normalize(CUStringObject::NF_KC)->equals("¡Hola se\xC3\xB1or ffl!"));
        $this->assertTrue(
            u("¡Hola se\xC3\xB1or ﬄ!")->normalize(CUStringObject::NF_KD)->equals("¡Hola se\x6E\xCC\x83or ffl!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsNormalized ()
    {
        $this->assertTrue(u("¡Hola se\xC3\xB1or!")->isNormalized(CUStringObject::NF_C));
        $this->assertFalse(u("¡Hola se\x6E\xCC\x83or!")->isNormalized(CUStringObject::NF_C));

        $this->assertTrue(u("¡Hola se\x6E\xCC\x83or!")->isNormalized(CUStringObject::NF_D));
        $this->assertFalse(u("¡Hola se\xC3\xB1or!")->isNormalized(CUStringObject::NF_D));

        $this->assertTrue(u("¡Hola se\xC3\xB1or ffl!")->isNormalized(CUStringObject::NF_KC));
        $this->assertFalse(u("¡Hola se\x6E\xCC\x83or ﬄ!")->isNormalized(CUStringObject::NF_KC));

        $this->assertTrue(u("¡Hola se\x6E\xCC\x83or ffl!")->isNormalized(CUStringObject::NF_KD));
        $this->assertFalse(u("¡Hola se\xC3\xB1or ﬄ!")->isNormalized(CUStringObject::NF_KD));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBool10 ()
    {
        $this->assertTrue(
            CUStringObject::fromBool10(true)->equals("1"));
        $this->assertTrue(
            CUStringObject::fromBool10(false)->equals("0"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolTf ()
    {
        $this->assertTrue(
            CUStringObject::fromBoolTf(true)->equals("true"));
        $this->assertTrue(
            CUStringObject::fromBoolTf(false)->equals("false"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolYn ()
    {
        $this->assertTrue(
            CUStringObject::fromBoolYn(true)->equals("yes"));
        $this->assertTrue(
            CUStringObject::fromBoolYn(false)->equals("no"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolOo ()
    {
        $this->assertTrue(
            CUStringObject::fromBoolOo(true)->equals("on"));
        $this->assertTrue(
            CUStringObject::fromBoolOo(false)->equals("off"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromInt ()
    {
        $this->assertTrue(
            CUStringObject::fromInt(1234)->equals("1234"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromFloat ()
    {
        $this->assertTrue(
            CUStringObject::fromFloat(12.34)->equals("12.34"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCharCode ()
    {
        $this->assertTrue(
            CUStringObject::fromCharCode(0x0041)->equals("A"));
        $this->assertTrue(
            CUStringObject::fromCharCode(0x00C6)->equals("Æ"));
        $this->assertTrue(
            CUStringObject::fromCharCode(0x270C)->equals("✌"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCharCodeHex ()
    {
        $this->assertTrue(
            CUStringObject::fromCharCodeHex("41")->equals("A"));
        $this->assertTrue(
            CUStringObject::fromCharCodeHex("0041")->equals("A"));
        $this->assertTrue(
            CUStringObject::fromCharCodeHex("00C6")->equals("Æ"));
        $this->assertTrue(
            CUStringObject::fromCharCodeHex("270C")->equals("✌"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCharCodeEsc ()
    {
        $this->assertTrue(
            CUStringObject::fromCharCodeEsc("\\u0041")->equals("A"));
        $this->assertTrue(
            CUStringObject::fromCharCodeEsc("\\u00C6")->equals("Æ"));
        $this->assertTrue(
            CUStringObject::fromCharCodeEsc("\\u270C")->equals("✌"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromEscString ()
    {
        $this->assertTrue(
            CUStringObject::fromEscString("\\u00A1Hola se\\u00F1or!")->equals("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBool ()
    {
        $this->assertTrue( u("1")->toBool() === true );
        $this->assertTrue( u("true")->toBool() === true );
        $this->assertTrue( u("yes")->toBool() === true );
        $this->assertTrue( u("on")->toBool() === true );

        $this->assertTrue( u("0")->toBool() === false );
        $this->assertTrue( u("false")->toBool() === false );
        $this->assertTrue( u("no")->toBool() === false );
        $this->assertTrue( u("off")->toBool() === false );
        $this->assertTrue( u("maybe")->toBool() === false );
        $this->assertTrue( u("01")->toBool() === false );
        $this->assertTrue( u("abc")->toBool() === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFrom10 ()
    {
        $this->assertTrue( u("1")->toBoolFrom10() === true );
        $this->assertTrue( u("0")->toBoolFrom10() === false );

        $this->assertTrue( u("maybe")->toBoolFrom10() === false );
        $this->assertTrue( u("01")->toBoolFrom10() === false );
        $this->assertTrue( u("abc")->toBoolFrom10() === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromTf ()
    {
        $this->assertTrue( u("true")->toBoolFromTf() === true );
        $this->assertTrue( u("false")->toBoolFromTf() === false );

        $this->assertTrue( u("maybe")->toBoolFromTf() === false );
        $this->assertTrue( u("01")->toBoolFromTf() === false );
        $this->assertTrue( u("abc")->toBoolFromTf() === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromYn ()
    {
        $this->assertTrue( u("yes")->toBoolFromYn() === true );
        $this->assertTrue( u("no")->toBoolFromYn() === false );

        $this->assertTrue( u("maybe")->toBoolFromYn() === false );
        $this->assertTrue( u("01")->toBoolFromYn() === false );
        $this->assertTrue( u("abc")->toBoolFromYn() === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromOo ()
    {
        $this->assertTrue( u("on")->toBoolFromOo() === true );
        $this->assertTrue( u("off")->toBoolFromOo() === false );

        $this->assertTrue( u("maybe")->toBoolFromOo() === false );
        $this->assertTrue( u("01")->toBoolFromOo() === false );
        $this->assertTrue( u("abc")->toBoolFromOo() === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToInt ()
    {
        $this->assertTrue( u("1234")->toInt() === 1234 );
        $this->assertTrue( u("01234")->toInt() === 1234 );
        $this->assertTrue( u("001234")->toInt() === 1234 );
        $this->assertTrue( u("-1234")->toInt() === -1234 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToIntFromHex ()
    {
        $this->assertTrue( u("100")->toIntFromHex() === 256 );
        $this->assertTrue( u("0x100")->toIntFromHex() === 256 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToIntFromBase ()
    {
        $this->assertTrue( u("100")->toIntFromBase(8) === 64 );
        $this->assertTrue( u("1234")->toIntFromBase(10) === 1234 );
        $this->assertTrue( u("100")->toIntFromBase(16) === 256 );
        $this->assertTrue( u("0x100")->toIntFromBase(16) === 256 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToFloat ()
    {
        $this->assertTrue( u("12.34")->toFloat() === 12.34 );
        $this->assertTrue( u("1e2")->toFloat() === 100.0 );
        $this->assertTrue( u("1e+2")->toFloat() === 100.0 );
        $this->assertTrue( u("1e-2")->toFloat() === 0.01 );
        $this->assertTrue( u("1E+2")->toFloat() === 100.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToCharCode ()
    {
        $this->assertTrue( u("A")->toCharCode() == 0x0041 );
        $this->assertTrue( u("\x00")->toCharCode() == 0x0000 );
        $this->assertTrue( u("\t")->toCharCode() == 0x0009 );

        $this->assertTrue( u("ñ")->toCharCode() == 0x00F1 );
        $this->assertTrue( u("Æ")->toCharCode() == 0x00C6 );
        $this->assertTrue( u("✌")->toCharCode() == 0x270C );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToCharCodeHex ()
    {
        $this->assertTrue(
            u("A")->toCharCodeHex()->equals("0041"));
        $this->assertTrue(
            u("\t")->toCharCodeHex()->equals("0009"));

        $this->assertTrue(
            u("ñ")->toCharCodeHex()->equals("00F1"));
        $this->assertTrue(
            u("Æ")->toCharCodeHex()->equals("00C6"));
        $this->assertTrue(
            u("✌")->toCharCodeHex()->equals("270C"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToCharCodeEsc ()
    {
        $this->assertTrue(
            u("A")->toCharCodeEsc()->equals("\\u0041"));
        $this->assertTrue(
            u("\t")->toCharCodeEsc()->equals("\\u0009"));

        $this->assertTrue(
            u("ñ")->toCharCodeEsc()->equals("\\u00F1"));
        $this->assertTrue(
            u("Æ")->toCharCodeEsc()->equals("\\u00C6"));
        $this->assertTrue(
            u("✌")->toCharCodeEsc()->equals("\\u270C"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToEscString ()
    {
        $this->assertTrue(
            u("ABC")->toEscString()->equals("\\u0041\\u0042\\u0043"));
        $this->assertTrue(
            u("\t")->toEscString()->equals("\\u0009"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $this->assertTrue( u("¡Hola señor!")->length() == 12 );
        $this->assertTrue( u("¡Hola señor! Æ✌")->length() == 15 );
        $this->assertTrue( u("")->length() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $this->assertFalse(u("¡Hola señor!")->isEmpty());
        $this->assertTrue(u("")->isEmpty());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCharAt ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->charAt(0)->equals("¡"));
        $this->assertTrue(
            u("¡Hola señor!")->charAt(1)->equals("H"));
        $this->assertTrue(
            u("¡Hola señor!")->charAt(8)->equals("ñ"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetCharAt ()
    {
        // $string = u("¡Hola señor!");
        // $string->setCharAt(0, " ");
        // $string->setCharAt(1, "L");
        // $string->setCharAt(8, "n");
        // $this->assertTrue(
        //     $string->equals(" Lola senor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->equals("¡Hola señor!"));
        $this->assertTrue(
            u("¡Hola se\x6E\xCC\x83or!")->equals("¡Hola se\xC3\xB1or!"));  // different normal forms
        $this->assertFalse(
            u("¡Hola señor!")->equals("¡Hola senor!"));
        $this->assertTrue(
            u("")->equals(""));

        $this->assertTrue(
            u("¡Hola señor!")->equals("¡Hola senor!", CUStringObject::COLLATION_IGNORE_ACCENTS));
        $this->assertTrue(
            u("¡Hola señor!")->equals(" Hola señor. ", CUStringObject::COLLATION_IGNORE_NONWORD));
        $this->assertTrue(
            u("¡Hola señor!")->equals(" Hola senor. ",
            CUStringObject::COLLATION_IGNORE_ACCENTS | CUStringObject::COLLATION_IGNORE_NONWORD));

        $this->assertFalse(
            u("¡Hola señor!")->equals("+Hola señor!", CUStringObject::COLLATION_IGNORE_NONWORD));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEqualsCi ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->equalsCi("¡HOLA SEÑOR!"));
        $this->assertTrue(
            u("¡Hola se\x6E\xCC\x83or!")->equalsCi("¡HOLA SE\xC3\x91OR!"));  // different normal forms
        $this->assertFalse(
            u("¡Hola señor!")->equalsCi("¡HOLA SENOR!"));
        $this->assertTrue(
            u("")->equalsCi(""));

        $this->assertTrue(
            u("¡Hola señor!")->equalsCi("¡HOLA SENOR!", CUStringObject::COLLATION_IGNORE_ACCENTS));
        $this->assertTrue(
            u("¡Hola señor!")->equalsCi(" HOLA SEÑOR. ", CUStringObject::COLLATION_IGNORE_NONWORD));
        $this->assertTrue(
            u("¡Hola señor!")->equalsCi(" HOLA SENOR. ",
            CUStringObject::COLLATION_IGNORE_ACCENTS | CUStringObject::COLLATION_IGNORE_NONWORD));

        $this->assertFalse(
            u("¡Hola señor!")->equalsCi("=HOLA SEÑOR!", CUStringObject::COLLATION_IGNORE_NONWORD));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEqualsBi ()
    {
        $this->assertTrue(
            u("BEEF")->equalsBi("BEEF"));
        $this->assertFalse(
            u("BEEF")->equalsBi("REEF"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        $this->assertTrue( u("¡Hola señor!")->compare("¡Hola señor!") == 0 );
        $this->assertTrue( u("A")->compare("B") < 0 );
        $this->assertTrue( u("a")->compare("A") < 0 );
        $this->assertTrue( u("C")->compare("B") > 0 );
        $this->assertTrue( u(" ")->compare("a") < 0 );
        $this->assertTrue( u("")->compare("") == 0 );

        $this->assertTrue( u("Ć")->compare("Ĉ") < 0 );
        $this->assertTrue( u("①")->compare("②") < 0 );

        $this->assertTrue( u("Ć")->compare("Ĉ", CUStringObject::COLLATION_IGNORE_ACCENTS) == 0 );
        $this->assertTrue( u(" A,.")->compare("A;:", CUStringObject::COLLATION_IGNORE_NONWORD) == 0 );
        $this->assertTrue(
            u(" Ć,.")->compare("Ĉ;:",
            CUStringObject::COLLATION_IGNORE_ACCENTS | CUStringObject::COLLATION_IGNORE_NONWORD) == 0 );

        $this->assertTrue( u("Å")->compare("Z", CUStringObject::COLLATION_DEFAULT,
            new CULocale(CULocale::ENGLISH_UNITED_STATES)) < 0 );
        $this->assertTrue( u("Å")->compare("Z", CUStringObject::COLLATION_DEFAULT,
            new CULocale(CULocale::DANISH_DENMARK)) > 0 );

        $this->assertTrue( u("a2")->compare("a10") > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareCi ()
    {
        $this->assertTrue( u("¡Hola señor!")->compareCi("¡HOLA SEÑOR!") == 0 );
        $this->assertTrue( u("a")->compareCi("B") < 0 );
        $this->assertTrue( u("a")->compareCi("A") == 0 );
        $this->assertTrue( u("C")->compareCi("b") > 0 );
        $this->assertTrue( u(" ")->compareCi("a") < 0 );
        $this->assertTrue( u("")->compareCi("") == 0 );

        $this->assertTrue( u("ć")->compareCi("Ĉ") < 0 );
        $this->assertTrue( u("①")->compareCi("②") < 0 );

        $this->assertTrue( u("ć")->compareCi("Ĉ", CUStringObject::COLLATION_IGNORE_ACCENTS) == 0 );
        $this->assertTrue( u(" a,.")->compareCi("A;:", CUStringObject::COLLATION_IGNORE_NONWORD) == 0 );
        $this->assertTrue(
            u(" ć,.")->compareCi("Ĉ;:",
            CUStringObject::COLLATION_IGNORE_ACCENTS | CUStringObject::COLLATION_IGNORE_NONWORD) == 0 );

        $this->assertTrue(
            u("Å")->compareCi("z", CUStringObject::COLLATION_DEFAULT,
            new CULocale(CULocale::ENGLISH_UNITED_STATES)) < 0 );
        $this->assertTrue(
            u("å")->compareCi("Z", CUStringObject::COLLATION_DEFAULT, new CULocale(CULocale::DANISH_DENMARK)) > 0 );

        $this->assertTrue( u("A2")->compareCi("a10") > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareNat ()
    {
        $this->assertTrue( u("¡Hola señor!")->compareNat("¡Hola señor!") == 0 );
        $this->assertTrue( u("A")->compareNat("B") < 0 );
        $this->assertTrue( u("a")->compareNat("A") < 0 );
        $this->assertTrue( u("C")->compareNat("B") > 0 );
        $this->assertTrue( u(" ")->compareNat("a") < 0 );
        $this->assertTrue( u("")->compareNat("") == 0 );

        $this->assertTrue( u("Ć")->compareNat("Ĉ") < 0 );
        $this->assertTrue( u("①")->compareNat("②") < 0 );

        $this->assertTrue( u("Ć")->compareNat("Ĉ", CUStringObject::COLLATION_IGNORE_ACCENTS) == 0 );
        $this->assertTrue( u(" A,.")->compareNat("A;:", CUStringObject::COLLATION_IGNORE_NONWORD) == 0 );
        $this->assertTrue(
            u(" Ć,.")->compareNat("Ĉ;:",
            CUStringObject::COLLATION_IGNORE_ACCENTS | CUStringObject::COLLATION_IGNORE_NONWORD) == 0 );

        $this->assertTrue(
            u("Å")->compareNat("Z", CUStringObject::COLLATION_DEFAULT,
            new CULocale(CULocale::ENGLISH_UNITED_STATES)) < 0 );
        $this->assertTrue(
            u("Å")->compareNat("Z", CUStringObject::COLLATION_DEFAULT, new CULocale(CULocale::DANISH_DENMARK)) > 0 );

        $this->assertTrue( u("a2")->compareNat("a10") < 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareNatCi ()
    {
        $this->assertTrue( u("¡Hola señor!")->compareNatCi("¡HOLA SEÑOR!") == 0 );
        $this->assertTrue( u("a")->compareNatCi("B") < 0 );
        $this->assertTrue( u("a")->compareNatCi("A") == 0 );
        $this->assertTrue( u("C")->compareNatCi("b") > 0 );
        $this->assertTrue( u(" ")->compareNatCi("a") < 0 );
        $this->assertTrue( u("")->compareNatCi("") == 0 );

        $this->assertTrue( u("ć")->compareNatCi("Ĉ") < 0 );
        $this->assertTrue( u("①")->compareNatCi("②") < 0 );

        $this->assertTrue( u("ć")->compareNatCi("Ĉ", CUStringObject::COLLATION_IGNORE_ACCENTS) == 0 );
        $this->assertTrue( u(" a,.")->compareNatCi("A;:", CUStringObject::COLLATION_IGNORE_NONWORD) == 0 );
        $this->assertTrue(
            u(" ć,.")->compareNatCi("Ĉ;:",
            CUStringObject::COLLATION_IGNORE_ACCENTS | CUStringObject::COLLATION_IGNORE_NONWORD) == 0 );

        $this->assertTrue(
            u("Å")->compareNatCi("z", CUStringObject::COLLATION_DEFAULT,
            new CULocale(CULocale::ENGLISH_UNITED_STATES)) < 0 );
        $this->assertTrue(
            u("å")->compareNatCi("Z", CUStringObject::COLLATION_DEFAULT, new CULocale(CULocale::DANISH_DENMARK)) > 0 );

        $this->assertTrue( u("A2")->compareNatCi("a10") < 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLevenDist ()
    {
        $this->assertTrue( u("åbc")->levenDist("åbcd") == 1 );
        $this->assertTrue( u("åbc")->levenDist("bb cd") == 3 );
        $this->assertTrue( u("")->levenDist("") == 0 );

        $this->assertTrue( u("señor")->levenDist("senor") == 0 );
        $this->assertTrue( u("やまもと, のぼる")->levenDist("yamamoto, noboru") == 0 );
        $this->assertTrue( u("やまもと, のぼる")->levenDist("yamamoto, noboru", false) == 14 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMetaphoneKey ()
    {
        $this->assertTrue(
            u("¡Hola")->metaphoneKey()->equals("HL"));
        $this->assertTrue(
            u("señor!")->metaphoneKey()->equals("SNR"));
        $this->assertTrue(
            u("")->metaphoneKey()->equals(""));

        $this->assertTrue(
            u("こんにちは")->metaphoneKey()->equals("KNNXH"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMetaphoneDist ()
    {
        $this->assertTrue( u("¡Hola")->metaphoneDist("¡Hola") == 0 );
        $this->assertTrue( u("¡Hola")->metaphoneDist("Hola") == 0 );
        $this->assertTrue( u("¡Hola")->metaphoneDist("Hey") == 1 );
        $this->assertTrue( u("")->metaphoneDist("") == 0 );

        $this->assertTrue( u("こんにちは")->metaphoneDist("こんには") == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToLowerCase ()
    {
        $this->assertTrue(
            u("SEÑOR.1")->toLowerCase()->equals("señor.1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToUpperCase ()
    {
        $this->assertTrue(
            u("señor.1")->toUpperCase()->equals("SEÑOR.1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToUpperCaseFirst ()
    {
        $this->assertTrue(
            u("hola señor")->toUpperCaseFirst()->equals("Hola señor"));
        $this->assertTrue(
            u("")->toUpperCaseFirst()->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToTitleCase ()
    {
        $this->assertTrue(
            u("¡hola señor you!")->toTitleCase()->equals("¡Hola Señor You!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStartsWith ()
    {
        $this->assertTrue(u("¡Señor")->startsWith("¡Señ"));
        $this->assertFalse(u("¡Señor")->startsWith("th"));
        $this->assertTrue(u("¡Señor")->startsWith(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStartsWithCi ()
    {
        $this->assertTrue(u("¡Señor")->startsWithCi("¡SEÑ"));
        $this->assertFalse(u("¡Señor")->startsWithCi("TH"));
        $this->assertTrue(u("¡Señor")->startsWithCi(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEndsWith ()
    {
        $this->assertTrue(u("¡Señor")->endsWith("ñor"));
        $this->assertFalse(u("¡Señor")->endsWith("ere"));
        $this->assertTrue(u("¡Señor")->endsWith(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEndsWithCi ()
    {
        $this->assertTrue(u("¡Señor")->endsWithCi("ÑOR"));
        $this->assertFalse(u("¡Señor")->endsWithCi("ERE"));
        $this->assertTrue(u("¡Señor")->endsWithCi(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIndexOf ()
    {
        $this->assertTrue( u("¡Hola señor!")->indexOf("señor") == 6 );
        $this->assertTrue( u("¡Hola señor señor!")->indexOf("señor", 7) == 12 );
        $this->assertTrue( u("¡Hola señor!")->indexOf("bye") == -1 );

        // Special cases.
        $this->assertTrue( u("¡Hola señor!")->indexOf("") == 0 );
        $this->assertTrue( u("¡Hola señor!")->indexOf("", 3) == 3 );
        $this->assertTrue( u("")->indexOf("") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIndexOfCi ()
    {
        $this->assertTrue( u("¡Hola señor!")->indexOfCi("SEÑOR") == 6 );
        $this->assertTrue( u("¡Hola señor señor!")->indexOfCi("SEÑOR", 7) == 12 );
        $this->assertTrue( u("¡Hola señor!")->indexOfCi("BYE") == -1 );

        // Special cases.
        $this->assertTrue( u("¡Hola señor!")->indexOfCi("") == 0 );
        $this->assertTrue( u("¡Hola señor!")->indexOfCi("", 3) == 3 );
        $this->assertTrue( u("")->indexOfCi("") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastIndexOf ()
    {
        $this->assertTrue( u("¡Hola señor señor!")->lastIndexOf("señor") == 12 );
        $this->assertTrue( u("¡Hola señor señor señor!")->lastIndexOf("señor", 7) == 18 );
        $this->assertTrue( u("¡Hola señor!")->lastIndexOf("bye") == -1 );

        // Special cases.
        $this->assertTrue( u("¡Hola señor!")->lastIndexOf("") == 12 );
        $this->assertTrue( u("¡Hola señor!")->lastIndexOf("", 3) == 12 );
        $this->assertTrue( u("")->lastIndexOf("") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastIndexOfCi ()
    {
        $this->assertTrue( u("¡Hola señor señor!")->lastIndexOfCi("SEÑOR") == 12 );
        $this->assertTrue( u("¡Hola señor señor señor!")->lastIndexOfCi("SEÑOR", 7) == 18 );
        $this->assertTrue( u("¡Hola señor!")->lastIndexOfCi("BYE") == -1 );

        // Special cases.
        $this->assertTrue( u("¡Hola señor!")->lastIndexOfCi("") == 12 );
        $this->assertTrue( u("¡Hola señor!")->lastIndexOfCi("", 3) == 12 );
        $this->assertTrue( u("")->lastIndexOfCi("") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $this->assertTrue(u("¡Hola señor!")->find("señor"));
        $this->assertFalse(u("¡Hola señor!")->find("senor"));
        $this->assertFalse(u("¡Hola señor!")->find("señor", 7));
        $this->assertFalse(u("¡Hola señor!")->find("bye"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindCi ()
    {
        $this->assertTrue(u("¡Hola señor!")->findCi("SEÑOR"));
        $this->assertFalse(u("¡Hola señor!")->findCi("SENOR"));
        $this->assertFalse(u("¡Hola señor!")->findCi("SEÑOR", 7));
        $this->assertFalse(u("¡Hola señor!")->findCi("BYE"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsSubsetOf ()
    {
        $this->assertTrue(u("¡Hola señor!")->isSubsetOf(" ¡Holaseñr!"));
        $this->assertFalse(u("¡Hola señor!")->isSubsetOf("Abcde"));

        // Special cases.
        $this->assertFalse(u("")->isSubsetOf("Abcde"));
        $this->assertFalse(u("¡Hola señor!")->isSubsetOf(""));
        $this->assertTrue(u("")->isSubsetOf(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubstr ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->substr(2)->equals("ola señor!"));
        $this->assertTrue(
            u("¡Hola señor!")->substr(2, 3)->equals("ola"));
        $this->assertTrue(
            u("¡Hola señor!")->substr(0, 12)->equals("¡Hola señor!"));

        // Special cases.
        $this->assertTrue(
            u("¡Hola señor!")->substr(2, 0)->equals(""));
        $this->assertTrue(
            u("¡Hola señor!")->substr(12)->equals(""));
        $this->assertTrue(
            u("¡Hola señor!")->substr(12, 0)->equals(""));
        $this->assertTrue(
            u("¡Hola señor!")->substr(0, 0)->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubstring ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->substring(2, 5)->equals("ola"));
        $this->assertTrue(
            u("¡Hola señor!")->substring(0, 12)->equals("¡Hola señor!"));

        // Special cases.
        $this->assertTrue(
            u("¡Hola señor!")->substring(2, 2)->equals(""));
        $this->assertTrue(
            u("¡Hola señor!")->substring(12, 12)->equals(""));
        $this->assertTrue(
            u("¡Hola señor!")->substring(0, 0)->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumSubstrings ()
    {
        $this->assertTrue( u("¡Hola señor!")->numSubstrings("o") == 2 );
        $this->assertTrue( u("¡Hola señor!")->numSubstrings("o", 3) == 1 );
        $this->assertTrue( u("¡Hola señor señor!")->numSubstrings("ñ") == 2 );
        $this->assertTrue( u("¡Hola señor señor!")->numSubstrings("señor") == 2 );
        $this->assertTrue( u("¡Hola señor!")->numSubstrings("x") == 0 );
        $this->assertTrue( u("¡Hola señor!")->numSubstrings("Ñ") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplit ()
    {
        $res = u("¡Ho,la,señ,or!")->split(",");
        $this->assertTrue( $res->length() == 4 &&
            $res[0]->equals("¡Ho") && $res[1]->equals("la") &&
            $res[2]->equals("señ") && $res[3]->equals("or!") );

        $res = u("¡Ho,la·señ.or!")->split(a(",", "·", ".", "!"));
        $this->assertTrue( $res->length() == 5 &&
            $res[0]->equals("¡Ho") && $res[1]->equals("la") &&
            $res[2]->equals("señ") && $res[3]->equals("or") && $res[4]->equals("") );

        // Special cases.

        $res = u("Hey")->split("");
        $this->assertTrue( $res->length() == 3 &&
            $res[0]->equals("H") && $res[1]->equals("e") && $res[2]->equals("y") );

        $res = u("")->split("");
        $this->assertTrue( $res->length() == 1 && $res[0]->equals("") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplitIntoChars ()
    {
        $res = u("Señor")->splitIntoChars();
        $this->assertTrue( $res->length() == 5 &&
            $res[0]->equals("S") && $res[1]->equals("e") && $res[2]->equals("ñ") &&
            $res[3]->equals("o") && $res[4]->equals("r") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrimStart ()
    {
        $this->assertTrue(
            u(" \n\r\t \xE2\x80\xA8 \xE2\x80\xA9¡Hola señor!")->trimStart()->equals("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrimEnd ()
    {
        $this->assertTrue(
            u("¡Hola señor! \n\r\t \xE2\x80\xA8 \xE2\x80\xA9")->trimEnd()->equals("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrim ()
    {
        $this->assertTrue(
            u(" \n\r\t \xE2\x80\xA8 \xE2\x80\xA9¡Hola señor! \n\r\t \xE2\x80\xA8 \xE2\x80\xA9")->trim()->equals(
            "¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormSpacing ()
    {
        $this->assertTrue(
            u(" \n\r\t \xE2\x80\xA8 \xE2\x80\xA9¡Hola \n\r\t \xE2\x80\xA8 \xE2\x80\xA9 \n\r\t \xE2\x80\xA8 " .
            "\xE2\x80\xA9señor! \n\r\t \xE2\x80\xA8 \xE2\x80\xA9")->normSpacing()->equals("¡Hola señor!"));
        $this->assertTrue(
            u("\n¡Hola\n\nseñor!\n")->normSpacing()->equals("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormNewlines ()
    {
        $this->assertTrue(
            u("¡Hola\r\n\xE2\x80\xA8señor!\r\n\r\v\f\xE2\x80\xA9")->normNewlines()->equals(
            "¡Hola\n\nseñor!\n\n\n\n\n"));
        $this->assertTrue(
            u("¡Hola\r\n\xE2\x80\xA8señor!\r\n\r\v\f\xE2\x80\xA9")->normNewlines("\r")->equals(
            "¡Hola\r\rseñor!\r\r\r\r\r"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAdd ()
    {
        $string0 = u("Hello");
        $string1 = u(" there!");
        $resString = $string0->add($string1);
        $this->assertTrue($resString->equals("Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAddWs ()
    {
        $string0 = u("Hello");
        $string1 = u("there!");
        $resString = $string0->addWs($string1);
        $this->assertTrue($resString->equals("Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadStart ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->padStart("-", 14)->equals("--¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadEnd ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->padEnd("-", 14)->equals("¡Hola señor!--"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripStart ()
    {
        $this->assertTrue(
            u("xx¡Hola señor!")->stripStart("xx")->equals("¡Hola señor!"));
        $this->assertTrue(
            u("xxyy¡Hola señor!")->stripStart(a("xx", "yy"))->equals("¡Hola señor!"));
        $this->assertTrue(
            u("xxyy¡Hola señor!")->stripStart(a("yy", "xx"))->equals("yy¡Hola señor!"));
        $this->assertTrue(
            u("xx¡Hola señor!")->stripStart("yy")->equals("xx¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripStartCi ()
    {
        $this->assertTrue(
            u("xx¡Hola señor!")->stripStartCi("XX")->equals("¡Hola señor!"));
        $this->assertTrue(
            u("xxyy¡Hola señor!")->stripStartCi(a("XX", "YY"))->equals("¡Hola señor!"));
        $this->assertTrue(
            u("xxyy¡Hola señor!")->stripStartCi(a("YY", "XX"))->equals("yy¡Hola señor!"));
        $this->assertTrue(
            u("xx¡Hola señor!")->stripStartCi("YY")->equals("xx¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripEnd ()
    {
        $this->assertTrue(
            u("¡Hola señor!xx")->stripEnd("xx")->equals("¡Hola señor!"));
        $this->assertTrue(
            u("¡Hola señor!yyxx")->stripEnd(a("xx", "yy"))->equals("¡Hola señor!"));
        $this->assertTrue(
            u("¡Hola señor!yyxx")->stripEnd(a("yy", "xx"))->equals("¡Hola señor!yy"));
        $this->assertTrue(
            u("¡Hola señor!xx")->stripEnd("yy")->equals("¡Hola señor!xx"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripEndCi ()
    {
        $this->assertTrue(
            u("¡Hola señor!xx")->stripEndCi("XX")->equals("¡Hola señor!"));
        $this->assertTrue(
            u("¡Hola señor!yyxx")->stripEndCi(a("XX", "YY"))->equals("¡Hola señor!"));
        $this->assertTrue(
            u("¡Hola señor!yyxx")->stripEndCi(a("YY", "XX"))->equals("¡Hola señor!yy"));
        $this->assertTrue(
            u("¡Hola señor!xx")->stripEndCi("YY")->equals("¡Hola señor!xx"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsert ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->insert(6, "you ")->equals("¡Hola you señor!"));
        $this->assertTrue(
            u("¡Hola señor!")->insert(0, "you ")->equals("you ¡Hola señor!"));
        $this->assertTrue(
            u("¡Hola señor!")->insert(12, " you")->equals("¡Hola señor! you"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceSubstring ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->replaceSubstring(6, 5, "you")->equals("¡Hola you!"));
        $this->assertTrue(
            u("¡Hola señor!")->replaceSubstring(0, 0, "you ")->equals("you ¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceSubstringByRange ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->replaceSubstringByRange(6, 11, "you")->equals("¡Hola you!"));
        $this->assertTrue(
            u("¡Hola señor!")->replaceSubstringByRange(0, 0, "you ")->equals("you ¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubstring ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->removeSubstring(5, 6)->equals("¡Hola!"));
        $this->assertTrue(
            u("¡Hola señor!")->removeSubstring(5, 0)->equals("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubstringByRange ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->removeSubstringByRange(5, 11)->equals("¡Hola!"));
        $this->assertTrue(
            u("¡Hola señor!")->removeSubstringByRange(5, 5)->equals("¡Hola señor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplace ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->replace("¡Hola", "Hello")->equals("Hello señor!"));

        $quantity;
        $this->assertTrue(
            u("¡Hola-Hola señor!")->replace("Hola", "Hello", $quantity)->equals("¡Hello-Hello señor!") &&
            $quantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceCi ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->replaceCi("¡HOLA", "Hello")->equals("Hello señor!"));

        $quantity;
        $this->assertTrue(
            u("¡Hola-Hola señor!")->replaceCi("HOLA", "Hello", $quantity)->equals("¡Hello-Hello señor!") &&
            $quantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->remove(" señor")->equals("¡Hola!"));

        $quantity;
        $this->assertTrue(
            u("¡Hola señor señor!")->remove(" señor", $quantity)->equals("¡Hola!") &&
            $quantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveCi ()
    {
        $this->assertTrue(
            u("¡Hola señor!")->removeCi(" SEÑOR")->equals("¡Hola!"));

        $quantity;
        $this->assertTrue(
            u("¡Hola señor señor!")->removeCi(" SEÑOR", $quantity)->equals("¡Hola!") &&
            $quantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShuffle ()
    {
        $string = u("るabĉdéfghijklmñopqrštuvwxŷzの");
        $shuffledString = $string->shuffle();
        $this->assertTrue($shuffledString->isSubsetOf($string));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWordWrap ()
    {
        $this->assertTrue(
            u("¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!")
            ->wordWrap(21)->equals(
            "¡Hola señor! ¡Hola\nseñor! ¡Hola señor!\n¡Hola señor! ¡Hola\nseñor! ¡Hola señor!\n¡Hola señor! ¡Hola\n" .
            "señor!"));

        $this->assertTrue(
            u("¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!")
            ->wordWrap(4, CUStringObject::WRAPPING_BREAK_SPACELESS_LINES)->equals(
            "¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\n" .
            "seño\nr!\n¡Hol\na\nseño\nr!\n¡Hol\na\nseño\nr!"));

        $this->assertTrue(
            u("¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!")
            ->wordWrap(4, CUStringObject::WRAPPING_ALLOW_TRAILING_SPACES)->equals(
            "¡Hola \nseñor! \n¡Hola \nseñor! \n¡Hola \nseñor! \n¡Hola \nseñor! \n¡Hola \nseñor! \n¡Hola \nseñor! \n" .
            "¡Hola \nseñor! \n¡Hola \nseñor!"));

        $this->assertTrue(
            u("¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!")
            ->wordWrap(4, CUStringObject::WRAPPING_DISALLOW_LEADING_SPACES)->equals(
            "¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\nseñor!\n¡Hola\n" .
            "señor!\n¡Hola\nseñor!"));

        $this->assertTrue(
            u("¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor! ¡Hola señor!")
            ->wordWrap(21, CUStringObject::WRAPPING_DEFAULT, "\r")->equals(
            "¡Hola señor! ¡Hola \rseñor! ¡Hola señor! \r¡Hola señor! ¡Hola \rseñor! ¡Hola señor! \r" .
            "¡Hola señor! ¡Hola \rseñor!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDecToHex ()
    {
        $this->assertTrue(
            u("256")->decToHex()->equals("100"));

        $this->assertTrue(
            u("⁵⁶⁷⁸")->decToHex()->equals("162E"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHexToDec ()
    {
        $this->assertTrue(
            u("100")->hexToDec()->equals("256"));
        $this->assertTrue(
            u("0x100")->hexToDec()->equals("256"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumberToBase ()
    {
        $this->assertTrue(
            u("100")->numberToBase(8, 10)->equals("64"));
        $this->assertTrue(
            u("100")->numberToBase(16, 10)->equals("256"));
        $this->assertTrue(
            u("0x100")->numberToBase(16, 10)->equals("256"));
        $this->assertTrue(
            u("100")->numberToBase(8, 16)->equals("40"));
        $this->assertTrue(
            u("101")->numberToBase(2, 10)->equals("5"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTransliterate ()
    {
        $this->assertTrue(
            u("やまもと, のぼる")->transliterate("hiragana", "latin")->equals("yamamoto, noboru"));
        $this->assertTrue(
            u("Britney Spears")->transliterate("latin", "katakana")->equals("ブリテネイ スペアルス"));
        $this->assertTrue(
            u("ブリテネイ スペアルス")->transliterate("katakana", "latin; title")->equals("Buritenei Supearusu"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTransliterateFromAny ()
    {
        $this->assertTrue(
            u("やまもと, のぼる")->transliterateFromAny("latin")->equals("yamamoto, noboru"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testApplyPublishingFilter ()
    {
        $this->assertTrue(
            u("\"¡Hola -- señor!\"")->applyPublishingFilter()->equals("“¡Hola — señor!”"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHalfwidthToFullwidth ()
    {
        $this->assertTrue(
            u("Hola")->halfwidthToFullwidth()->equals("Ｈｏｌａ"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFullwidthToHalfwidth ()
    {
        $this->assertTrue(
            u("Ｈｏｌａ")->fullwidthToHalfwidth()->equals("Hola"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTransform ()
    {
        $this->assertTrue(
            u("やまもと, のぼる")->transform("hiragana; latin; title")->equals("Yamamoto, Noboru"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRepeat ()
    {
        $this->assertTrue(
            u("ñ")->repeat(5)->equals("ñññññ"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasCjkChar ()
    {
        $this->assertTrue(u("Konichiwa やまもと")->hasCjkChar());
        $this->assertFalse(u("¡Hola señor!")->hasCjkChar());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReIndexOf ()
    {
        $this->assertTrue( u("¡Hello there!")->reIndexOf("/[^\\p{L} ]/u", 2) == 13 );

        $this->assertTrue( u("¡Hello! There!")->reIndexOf("/[^\\p{L} ]/u", 8) == 14 );

        $foundString;
        $pos = u("¡Hello SEÑOR there!")->reIndexOf("/\\p{Lu}{2,}/u", 0, $foundString);
        $this->assertTrue( $pos == 8 && $foundString->equals("SEÑOR") );

        $this->assertTrue( u("¡Hello there!")->reIndexOf("/\\d/u") == -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReLastIndexOf ()
    {
        $this->assertTrue( u("¿¡Hello there!?")->reLastIndexOf("/[^\\p{L} ]/u") == 16 );

        $this->assertTrue( u("¿¡Hello! There!?")->reLastIndexOf("/[^\\p{L} ]/u", 0) == 17 );

        $foundString;
        $pos = u("¿¡Hello YOU there!? ¿¡Hello SEÑOR there!?")->reLastIndexOf("/\\p{Lu}{2,}/u", 0, $foundString);
        $this->assertTrue( $pos == 32 && $foundString->equals("SEÑOR") );

        $this->assertTrue( u("¿¡Hello there!?")->reLastIndexOf("/\\d/u") == -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFind ()
    {
        $this->assertTrue(u("¡Hello there!")->reFind("/[^\\p{L} ]/u"));

        $foundString;
        $found = u("¡Hello There!")->reFind("/[^\\p{L} ]/u", $foundString);
        $this->assertTrue( $found && $foundString->equals("¡") );

        $foundString;
        $this->assertFalse(u("¡Hello there!")->reFind("/\\d/u", $foundString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindFrom ()
    {
        $this->assertTrue(u("¡Hello there!")->reFindFrom("/[^\\p{L} ]/u", 2));

        $foundString;
        $found = u("¡Hello? There!")->reFindFrom("/[^\\p{L} ]/u", 8, $foundString);
        $this->assertTrue( $found && $foundString->equals("!") );

        $this->assertFalse(u("¡Hello 2 you there!")->reFindFrom("/\\d/u", 9));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindGroups ()
    {
        $foundGroups;
        $found = u("¡Hello señor!")->reFindGroups("/(\\w+) (\\w+)/u", $foundGroups);
        $this->assertTrue(
            $found && $foundGroups->length() == 2 &&
            $foundGroups[0]->equals("Hello") && $foundGroups[1]->equals("señor") );

        $foundGroups;
        $foundString;
        $found = u("¡Hello señor!")->reFindGroups("/(\\w+) (\\S+)/u", $foundGroups, $foundString);
        $this->assertTrue(
            $found && $foundGroups->length() == 2 &&
            $foundGroups[0]->equals("Hello") && $foundGroups[1]->equals("señor!") &&
            $foundString->equals("Hello señor!") );

        $foundGroups;
        $this->assertFalse(u("¡Hello señor!")->reFindGroups("/^(\\w+) (\\w+)/u", $foundGroups));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindGroupsFrom ()
    {
        $foundGroups;
        $found = u("¡Hello señor you!")->reFindGroupsFrom("/(\\w+) (\\w+)/u", 7, $foundGroups);
        $this->assertTrue(
            $found && $foundGroups->length() == 2 &&
            $foundGroups[0]->equals("señor") && $foundGroups[1]->equals("you") );

        $foundGroups;
        $foundString;
        $found = u("¡Hello señor you!")->reFindGroupsFrom("/(\\w+) (\\S+)/u", 7, $foundGroups, $foundString);
        $this->assertTrue(
            $found && $foundGroups->length() == 2 &&
            $foundGroups[0]->equals("señor") && $foundGroups[1]->equals("you!") &&
            $foundString->equals("señor you!") );

        $foundGroups;
        $this->assertFalse(u("¡Hello señor!")->reFindGroupsFrom("/(\\w+) (\\w+)/u", 7, $foundGroups));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindAll ()
    {
        $numFound = u("¡Hello señor!")->reFindAll("/\\w+/u");
        $this->assertTrue( $numFound == 2 );

        $foundStrings;
        $numFound = u("¡Hello señor!")->reFindAll("/\\w+/u", $foundStrings);
        $this->assertTrue(
            $numFound == 2 && $foundStrings->length() == 2 &&
            $foundStrings[0]->equals("Hello") && $foundStrings[1]->equals("señor") );

        $foundStrings;
        $numFound = u("¡Hello señor!")->reFindAll("/a\\w+/u", $foundStrings);
        $this->assertTrue( $numFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindAllFrom ()
    {
        $foundStrings;
        $numFound = u("¡Hello señor!")->reFindAllFrom("/\\S+/u", 7, $foundStrings);
        $this->assertTrue(
            $numFound == 1 && $foundStrings->length() == 1 &&
            $foundStrings[0]->equals("señor!") );

        $foundStrings;
        $numFound = u("¡Hello señor!")->reFindAllFrom("/H\\w+/u", 7, $foundStrings);
        $this->assertTrue( $numFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindAllGroups ()
    {
        $foundGroupArrays;
        $numFound = u("¡Hello señor!")->reFindAllGroups("/(\\w\\w)(\\w\\w)/u", $foundGroupArrays);
        $this->assertTrue(
            $numFound == 2 && $foundGroupArrays->length() == 2 &&
            $foundGroupArrays[0][0]->equals("He") && $foundGroupArrays[0][1]->equals("ll") &&
            $foundGroupArrays[1][0]->equals("se") && $foundGroupArrays[1][1]->equals("ño") );

        $foundGroupArrays;
        $foundStrings;
        $numFound = u("¡Hello señor!")->reFindAllGroups("/(\\w\\w)(\\w\\w)/u", $foundGroupArrays, $foundStrings);
        $this->assertTrue(
            $numFound == 2 && $foundGroupArrays->length() == 2 &&
            $foundGroupArrays[0][0]->equals("He") && $foundGroupArrays[0][1]->equals("ll") &&
            $foundGroupArrays[1][0]->equals("se") && $foundGroupArrays[1][1]->equals("ño") &&
            $foundStrings->length() == 2 &&
            $foundStrings[0]->equals("Hell") && $foundStrings[1]->equals("seño") );

        $foundGroupArrays;
        $numFound = u("¡Hello señor!")->reFindAllGroups("/(\\w\\w\\w)(\\w\\w\\w)/u", $foundGroupArrays);
        $this->assertTrue( $numFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReFindAllGroupsFrom ()
    {
        $foundGroupArrays;
        $numFound = u("¡Hello señor!")->reFindAllGroupsFrom("/(\\w\\w)(\\w\\w)/u", 3, $foundGroupArrays);
        $this->assertTrue(
            $numFound == 2 && $foundGroupArrays->length() == 2 &&
            $foundGroupArrays[0][0]->equals("el") && $foundGroupArrays[0][1]->equals("lo") &&
            $foundGroupArrays[1][0]->equals("se") && $foundGroupArrays[1][1]->equals("ño") );

        $foundGroupArrays;
        $foundStrings;
        $numFound = u("¡Hello señor!")->reFindAllGroupsFrom("/(\\w\\w)(\\w\\w)/u", 3, $foundGroupArrays,
            $foundStrings);
        $this->assertTrue(
            $numFound == 2 && $foundGroupArrays->length() == 2 &&
            $foundGroupArrays[0][0]->equals("el") && $foundGroupArrays[0][1]->equals("lo") &&
            $foundGroupArrays[1][0]->equals("se") && $foundGroupArrays[1][1]->equals("ño") &&
            $foundStrings->length() == 2 &&
            $foundStrings[0]->equals("ello") && $foundStrings[1]->equals("seño") );

        $foundGroupArrays;
        $numFound = u("¡Hello señor!")->reFindAllGroupsFrom("/(\\w\\w\\w)(\\w\\w\\w)/u", 3, $foundGroupArrays);
        $this->assertTrue( $numFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReReplace ()
    {
        $res = u("¡Hello señor!")->reReplace("/[eoñ]/u", "a");
        $this->assertTrue($res->equals("¡Halla saaar!"));

        $quantity;
        $res = u("¡Hello señor!")->reReplace("/[eoñ]/u", "a", $quantity);
        $this->assertTrue( $res->equals("¡Halla saaar!") && $quantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReReplaceWithCallback ()
    {
        $res = u("¡Hello señor!")->reReplaceWithCallback("/[eoñ]/u", function ($matches)
            {
                return ( $matches[0]->equals("ñ") ) ? "n" : $matches[0];
            });
        $this->assertTrue($res->equals("¡Hello senor!"));

        $quantity;
        $res = u("¡Hello señor!")->reReplaceWithCallback("/[eoñ]/u", function ($matches)
            {
                return ( $matches[0]->equals("ñ") ) ? "n" : $matches[0];
            }, $quantity);
        $this->assertTrue( $res->equals("¡Hello senor!") && $quantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReRemove ()
    {
        $res = u("¡Hello señor!")->reRemove("/[eoñ]/u");
        $this->assertTrue($res->equals("¡Hll sr!"));

        $quantity;
        $res = u("¡Hello señor!")->reRemove("/[eoñ]/u", $quantity);
        $this->assertTrue( $res->equals("¡Hll sr!") && $quantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReSplit ()
    {
        $res = u("¡He,llo·se,ñor!")->reSplit("/[,·]/u");
        $this->assertTrue( $res->length() == 4 &&
            $res[0]->equals("¡He") && $res[1]->equals("llo") &&
            $res[2]->equals("se") && $res[3]->equals("ñor!") );

        $res = u("¡He,llo·se.ñor!")->reSplit(a("/[,·]/u", "/\\./u"));
        $this->assertTrue( $res->length() == 4 &&
            $res[0]->equals("¡He") && $res[1]->equals("llo") &&
            $res[2]->equals("se") && $res[3]->equals("ñor!") );

        // Special cases.

        $res = u("")->reSplit("/[,·]/u");
        $this->assertTrue( $res->length() == 1 && $res[0]->equals("") );

        $res = u("Héy")->reSplit("//u");
        $this->assertTrue( $res->length() == 3 &&
            $res[0]->equals("H") && $res[1]->equals("é") && $res[2]->equals("y") );

        $res = u("")->reSplit("//u");
        $this->assertTrue( $res->length() == 1 && $res[0]->equals("") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReEnterTd ()
    {
        $res = u(".(señor]/u")->reEnterTd("/u");
        $this->assertTrue($res->equals("\\.\\(señor\\]\\/u"));

        $res = u(".(señor]#")->reEnterTd("#");
        $this->assertTrue($res->equals("\\.\\(señor\\]\\#"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // public function testOffsetExists ()
    // {
    //     $string = u("abc");
    //     $this->assertTrue(isset($string[0]));
    //     $this->assertFalse(isset($string[3]));
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // public function testOffsetGet ()
    // {
    //     $string = u("abc");
    //     $this->assertTrue( $string[0]->equals("a") && $string[1]->equals("b") && $string[2]->equals("c") );
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // public function testOffsetSet ()
    // {
    //     $string = u("ñññ");
    //     $string[1] = "a";
    //     $this->assertTrue($string->equals("ñañ"));
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
