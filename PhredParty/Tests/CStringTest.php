<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CStringTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsValid ()
    {
        $this->assertTrue(CString::isValid("Hello there!"));
        $this->assertFalse(CString::isValid("Hello\x80there!\xFF"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSanitize ()
    {
        $this->assertTrue(
            CString::equals(CString::sanitize("Hello\x80there!\xFF"), "Hello?there!?"));
        $this->assertTrue(
            CString::equals(CString::sanitize("Hello there!"), "Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBool10 ()
    {
        $this->assertTrue(
            CString::equals(CString::fromBool10(true), "1"));
        $this->assertTrue(
            CString::equals(CString::fromBool10(false), "0"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolTf ()
    {
        $this->assertTrue(
            CString::equals(CString::fromBoolTf(true), "true"));
        $this->assertTrue(
            CString::equals(CString::fromBoolTf(false), "false"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolYn ()
    {
        $this->assertTrue(
            CString::equals(CString::fromBoolYn(true), "yes"));
        $this->assertTrue(
            CString::equals(CString::fromBoolYn(false), "no"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromBoolOo ()
    {
        $this->assertTrue(
            CString::equals(CString::fromBoolOo(true), "on"));
        $this->assertTrue(
            CString::equals(CString::fromBoolOo(false), "off"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromInt ()
    {
        $this->assertTrue(
            CString::equals(CString::fromInt(1234), "1234"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromFloat ()
    {
        $this->assertTrue(
            CString::equals(CString::fromFloat(12.34), "12.34"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCharCode ()
    {
        $this->assertTrue(
            CString::equals(CString::fromCharCode(0x41), "A"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBool ()
    {
        $this->assertTrue( CString::toBool("1") === true );
        $this->assertTrue( CString::toBool("true") === true );
        $this->assertTrue( CString::toBool("yes") === true );
        $this->assertTrue( CString::toBool("on") === true );

        $this->assertTrue( CString::toBool("0") === false );
        $this->assertTrue( CString::toBool("false") === false );
        $this->assertTrue( CString::toBool("no") === false );
        $this->assertTrue( CString::toBool("off") === false );
        $this->assertTrue( CString::toBool("maybe") === false );
        $this->assertTrue( CString::toBool("01") === false );
        $this->assertTrue( CString::toBool("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFrom10 ()
    {
        $this->assertTrue( CString::toBoolFrom10("1") === true );
        $this->assertTrue( CString::toBoolFrom10("0") === false );

        $this->assertTrue( CString::toBoolFrom10("maybe") === false );
        $this->assertTrue( CString::toBoolFrom10("01") === false );
        $this->assertTrue( CString::toBoolFrom10("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromTf ()
    {
        $this->assertTrue( CString::toBoolFromTf("true") === true );
        $this->assertTrue( CString::toBoolFromTf("false") === false );

        $this->assertTrue( CString::toBoolFromTf("maybe") === false );
        $this->assertTrue( CString::toBoolFromTf("01") === false );
        $this->assertTrue( CString::toBoolFromTf("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromYn ()
    {
        $this->assertTrue( CString::toBoolFromYn("yes") === true );
        $this->assertTrue( CString::toBoolFromYn("no") === false );

        $this->assertTrue( CString::toBoolFromYn("maybe") === false );
        $this->assertTrue( CString::toBoolFromYn("01") === false );
        $this->assertTrue( CString::toBoolFromYn("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToBoolFromOo ()
    {
        $this->assertTrue( CString::toBoolFromOo("on") === true );
        $this->assertTrue( CString::toBoolFromOo("off") === false );

        $this->assertTrue( CString::toBoolFromOo("maybe") === false );
        $this->assertTrue( CString::toBoolFromOo("01") === false );
        $this->assertTrue( CString::toBoolFromOo("abc") === false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToInt ()
    {
        $this->assertTrue( CString::toInt("1234") === 1234 );
        $this->assertTrue( CString::toInt("01234") === 1234 );
        $this->assertTrue( CString::toInt("001234") === 1234 );
        $this->assertTrue( CString::toInt("-1234") === -1234 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToIntFromHex ()
    {
        $this->assertTrue( CString::toIntFromHex("100") === 256 );
        $this->assertTrue( CString::toIntFromHex("0x100") === 256 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToIntFromBase ()
    {
        $this->assertTrue( CString::toIntFromBase("100", 8) === 64 );
        $this->assertTrue( CString::toIntFromBase("1234", 10) === 1234 );
        $this->assertTrue( CString::toIntFromBase("100", 16) === 256 );
        $this->assertTrue( CString::toIntFromBase("0x100", 16) === 256 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToFloat ()
    {
        $this->assertTrue( CString::toFloat("12.34") === 12.34 );
        $this->assertTrue( CString::toFloat("1e2") === 100.0 );
        $this->assertTrue( CString::toFloat("1e+2") === 100.0 );
        $this->assertTrue( CString::toFloat("1e-2") === 0.01 );
        $this->assertTrue( CString::toFloat("1E+2") === 100.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToCharCode ()
    {
        $this->assertTrue( CString::toCharCode("A") == 0x41 );
        $this->assertTrue( CString::toCharCode("\x00") == 0x00 );
        $this->assertTrue( CString::toCharCode("\t") == 0x09 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToCharCodeHex ()
    {
        $this->assertTrue(
            CString::equals(CString::toCharCodeHex("A"), "41"));
        $this->assertTrue(
            CString::equals(CString::toCharCodeHex("\t"), "09"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToEscString ()
    {
        $this->assertTrue(
            CString::equals(CString::toEscString("ABC"), "\\x41\\x42\\x43"));
        $this->assertTrue(
            CString::equals(CString::toEscString("\t"), "\\x09"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $this->assertTrue( CString::length("Hello there!") == 12 );
        $this->assertTrue( CString::length("") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $this->assertFalse(CString::isEmpty("Hello there!"));
        $this->assertTrue(CString::isEmpty(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $this->assertTrue(
            CString::equals("Hello there!", "Hello there!"));
        $this->assertFalse(
            CString::equals("Hello there!", "Hola there!"));
        $this->assertTrue(
            CString::equals("", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEqualsCi ()
    {
        $this->assertTrue(
            CString::equalsCi("Hello there!", "HELLO THERE!"));
        $this->assertTrue(
            CString::equalsCi("", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        $this->assertTrue( CString::compare("Hello there!", "Hello there!") == 0 );
        $this->assertTrue( CString::compare("A", "B") < 0 );
        $this->assertTrue( CString::compare("A", "a") < 0 );
        $this->assertTrue( CString::compare("C", "B") > 0 );
        $this->assertTrue( CString::compare(" ", "a") < 0 );
        $this->assertTrue( CString::compare("", "") == 0 );

        $this->assertTrue( CString::compare("a2", "a10") > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareCi ()
    {
        $this->assertTrue( CString::compareCi("Hello there!", "HELLO THERE!") == 0 );
        $this->assertTrue( CString::compareCi("a", "B") < 0 );
        $this->assertTrue( CString::compareCi("A", "a") == 0 );
        $this->assertTrue( CString::compareCi("C", "b") > 0 );
        $this->assertTrue( CString::compareCi(" ", "a") < 0 );
        $this->assertTrue( CString::compareCi("", "") == 0 );

        $this->assertTrue( CString::compareCi("A2", "a10") > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareNat ()
    {
        $this->assertTrue( CString::compareNat("Hello there!", "Hello there!") == 0 );
        $this->assertTrue( CString::compareNat("A", "B") < 0 );
        $this->assertTrue( CString::compareNat("A", "a") < 0 );
        $this->assertTrue( CString::compareNat("C", "B") > 0 );
        $this->assertTrue( CString::compareNat(" ", "a") < 0 );
        $this->assertTrue( CString::compareNat("", "") == 0 );

        $this->assertTrue( CString::compareNat("a2", "a10") < 0 );
        $this->assertTrue( CString::compareNat("a 30", "a 200") < 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompareNatCi ()
    {
        $this->assertTrue( CString::compareNatCi("Hello there!", "HELLO THERE!") == 0 );
        $this->assertTrue( CString::compareNatCi("a", "B") < 0 );
        $this->assertTrue( CString::compareNatCi("A", "a") == 0 );
        $this->assertTrue( CString::compareNatCi("C", "b") > 0 );
        $this->assertTrue( CString::compareNatCi(" ", "a") < 0 );
        $this->assertTrue( CString::compareNatCi("", "") == 0 );

        $this->assertTrue( CString::compareNatCi("a2", "A10") < 0 );
        $this->assertTrue( CString::compareNatCi("a 30", "A 200") < 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLevenDist ()
    {
        $this->assertTrue( CString::levenDist("abc", "abcd") == 1 );
        $this->assertTrue( CString::levenDist("abc", "bb cd") == 3 );
        $this->assertTrue( CString::levenDist("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMetaphoneKey ()
    {
        $this->assertTrue(
            CString::equals(CString::metaphoneKey("Hello"), "HL"));
        $this->assertTrue(
            CString::equals(CString::metaphoneKey("there!"), "0R"));
        $this->assertTrue(
            CString::equals(CString::metaphoneKey(""), ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMetaphoneDist ()
    {
        $this->assertTrue( CString::metaphoneDist("Hello", "Hello") == 0 );
        $this->assertTrue( CString::metaphoneDist("Hello", "Hola") == 0 );
        $this->assertTrue( CString::metaphoneDist("Hello", "Hey") == 1 );
        $this->assertTrue( CString::metaphoneDist("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToLowerCase ()
    {
        $this->assertTrue(
            CString::equals(CString::toLowerCase("HELLO.1"), "hello.1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToUpperCase ()
    {
        $this->assertTrue(
            CString::equals(CString::toUpperCase("hello.1"), "HELLO.1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToUpperCaseFirst ()
    {
        $this->assertTrue(
            CString::equals(CString::toUpperCaseFirst("hello there"), "Hello there"));
        $this->assertTrue(
            CString::equals(CString::toUpperCaseFirst(""), ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToTitleCase ()
    {
        $this->assertTrue(
            CString::equals(CString::toTitleCase("hello there you!"), "Hello There You!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStartsWith ()
    {
        $this->assertTrue(CString::startsWith("Hello", "Hell"));
        $this->assertFalse(CString::startsWith("Hello", "Hi"));
        $this->assertTrue(CString::startsWith("Hello", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStartsWithCi ()
    {
        $this->assertTrue(CString::startsWithCi("Hello", "HELL"));
        $this->assertFalse(CString::startsWithCi("Hello", "HI"));
        $this->assertTrue(CString::startsWithCi("Hello", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEndsWith ()
    {
        $this->assertTrue(CString::endsWith("Hello", "ello"));
        $this->assertFalse(CString::endsWith("Hello", "elo"));
        $this->assertTrue(CString::endsWith("Hello", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEndsWithCi ()
    {
        $this->assertTrue(CString::endsWithCi("Hello", "ELLO"));
        $this->assertFalse(CString::endsWithCi("Hello", "ELO"));
        $this->assertTrue(CString::endsWithCi("Hello", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIndexOf ()
    {
        $this->assertTrue( CString::indexOf("Hello there!", "there") == 6 );
        $this->assertTrue( CString::indexOf("Hello there there!", "there", 7) == 12 );
        $this->assertTrue( CString::indexOf("Hello there!", "bye") == -1 );

        // Special cases.
        $this->assertTrue( CString::indexOf("Hello there!", "") == 0 );
        $this->assertTrue( CString::indexOf("Hello there!", "", 3) == 3 );
        $this->assertTrue( CString::indexOf("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIndexOfCi ()
    {
        $this->assertTrue( CString::indexOfCi("Hello there!", "THERE") == 6 );
        $this->assertTrue( CString::indexOfCi("Hello there there!", "THERE", 7) == 12 );
        $this->assertTrue( CString::indexOfCi("Hello there!", "BYE") == -1 );

        // Special cases.
        $this->assertTrue( CString::indexOfCi("Hello there!", "") == 0 );
        $this->assertTrue( CString::indexOfCi("Hello there!", "", 3) == 3 );
        $this->assertTrue( CString::indexOfCi("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastIndexOf ()
    {
        $this->assertTrue( CString::lastIndexOf("Hello there there!", "there") == 12 );
        $this->assertTrue( CString::lastIndexOf("Hello there there there!", "there", 7) == 18 );
        $this->assertTrue( CString::lastIndexOf("Hello there!", "bye") == -1 );

        // Special cases.
        $this->assertTrue( CString::lastIndexOf("Hello there!", "") == 12 );
        $this->assertTrue( CString::lastIndexOf("Hello there!", "", 3) == 12 );
        $this->assertTrue( CString::lastIndexOf("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastIndexOfCi ()
    {
        $this->assertTrue( CString::lastIndexOfCi("Hello there there!", "THERE") == 12 );
        $this->assertTrue( CString::lastIndexOfCi("Hello there there there!", "THERE", 7) == 18 );
        $this->assertTrue( CString::lastIndexOfCi("Hello there!", "BYE") == -1 );

        // Special cases.
        $this->assertTrue( CString::lastIndexOfCi("Hello there!", "") == 12 );
        $this->assertTrue( CString::lastIndexOfCi("Hello there!", "", 3) == 12 );
        $this->assertTrue( CString::lastIndexOfCi("", "") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $this->assertTrue(CString::find("Hello there!", "there"));
        $this->assertFalse(CString::find("Hello there!", "there", 7));
        $this->assertFalse(CString::find("Hello there!", "bye"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindCi ()
    {
        $this->assertTrue(CString::findCi("Hello there!", "THERE"));
        $this->assertFalse(CString::findCi("Hello there!", "THERE", 7));
        $this->assertFalse(CString::findCi("Hello there!", "BYE"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsSubsetOf ()
    {
        $this->assertTrue(CString::isSubsetOf("Hello there!", " Helothr!"));
        $this->assertFalse(CString::isSubsetOf("Hello there!", " Heloth!"));
        $this->assertFalse(CString::isSubsetOf("Hello there!", "Abcde"));

        // Special cases.
        $this->assertFalse(CString::isSubsetOf("", "Abcde"));
        $this->assertFalse(CString::isSubsetOf("Hello there!", ""));
        $this->assertTrue(CString::isSubsetOf("", ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubstr ()
    {
        $this->assertTrue(
            CString::equals(CString::substr("Hello there!", 2), "llo there!"));
        $this->assertTrue(
            CString::equals(CString::substr("Hello there!", 2, 3), "llo"));
        $this->assertTrue(
            CString::equals(CString::substr("Hello there!", 0, 12), "Hello there!"));

        // Special cases.
        $this->assertTrue(
            CString::equals(CString::substr("Hello there!", 2, 0), ""));
        $this->assertTrue(
            CString::equals(CString::substr("Hello there!", 12), ""));
        $this->assertTrue(
            CString::equals(CString::substr("Hello there!", 12, 0), ""));
        $this->assertTrue(
            CString::equals(CString::substr("Hello there!", 0, 0), ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubstring ()
    {
        $this->assertTrue(
            CString::equals(CString::substring("Hello there!", 2, 5), "llo"));
        $this->assertTrue(
            CString::equals(CString::substring("Hello there!", 0, 12), "Hello there!"));

        // Special cases.
        $this->assertTrue(
            CString::equals(CString::substring("Hello there!", 2, 2), ""));
        $this->assertTrue(
            CString::equals(CString::substring("Hello there!", 12, 12), ""));
        $this->assertTrue(
            CString::equals(CString::substring("Hello there!", 0, 0), ""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumSubstrings ()
    {
        $this->assertTrue( CString::numSubstrings("Hello there!", "e") == 3 );
        $this->assertTrue( CString::numSubstrings("Hello there!", "e", 2) == 2 );
        $this->assertTrue( CString::numSubstrings("Hello there there!", "there") == 2 );
        $this->assertTrue( CString::numSubstrings("Hello there!", "x") == 0 );
        $this->assertTrue( CString::numSubstrings("Hello there!", "E") == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplit ()
    {
        $res = CString::split("He,llo,th,ere!", ",");
        $this->assertTrue( CArray::length($res) == 4 &&
            CString::equals($res[0], "He") && CString::equals($res[1], "llo") &&
            CString::equals($res[2], "th") && CString::equals($res[3], "ere!") );

        $res = CString::split("He,llo;th.ere!", CArray::fromElements(",", ";", ".", "!"));
        $this->assertTrue( CArray::length($res) == 5 &&
            CString::equals($res[0], "He") && CString::equals($res[1], "llo") &&
            CString::equals($res[2], "th") && CString::equals($res[3], "ere") && CString::equals($res[4], "") );

        // Special cases.

        $res = CString::split("Hey", "");
        $this->assertTrue( CArray::length($res) == 3 &&
            CString::equals($res[0], "H") && CString::equals($res[1], "e") && CString::equals($res[2], "y") );

        $res = CString::split("", "");
        $this->assertTrue( CArray::length($res) == 1 && CString::equals($res[0], "") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplitIntoChars ()
    {
        $res = CString::splitIntoChars("Hey");
        $this->assertTrue( CArray::length($res) == 3 &&
            CString::equals($res[0], "H") && CString::equals($res[1], "e") && CString::equals($res[2], "y") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrimStart ()
    {
        $this->assertTrue(
            CString::equals(CString::trimStart(" \n\r\t\x80\xFFHello there!"), "Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrimEnd ()
    {
        $this->assertTrue(
            CString::equals(CString::trimEnd("Hello there! \n\r\t\x80\xFF"), "Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTrim ()
    {
        $this->assertTrue(
            CString::equals(CString::trim(" \n\r\t\x80\xFFHello there! \n\r\t\x80\xFF"), "Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormSpacing ()
    {
        $this->assertTrue(
            CString::equals(CString::normSpacing(
            " \n\r\t\x80\xFFHello \n\r\t\x80\xFF \n\r\t\x80\xFF \n\r\t\x80\xFFthere! \n\r\t\x80\xFF"), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::normSpacing("\nHello\n\nthere!\n"), "Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormNewlines ()
    {
        $this->assertTrue(
            CString::equals(CString::normNewlines("Hello\r\nthere!\r\n\r\v\f"), "Hello\nthere!\n\n\n\n"));
        $this->assertTrue(
            CString::equals(CString::normNewlines("Hello\r\nthere!\r\n\r\v\f", "\r"), "Hello\rthere!\r\r\r\r"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadStart ()
    {
        $this->assertTrue(
            CString::equals(CString::padStart("Hello there!", "-", 14), "--Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadEnd ()
    {
        $this->assertTrue(
            CString::equals(CString::padEnd("Hello there!", "-", 14), "Hello there!--"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripStart ()
    {
        $this->assertTrue(
            CString::equals(CString::stripStart("xxHello there!", "xx"), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::stripStart("xxyyHello there!", CArray::fromElements("xx", "yy")), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::stripStart("xxyyHello there!", CArray::fromElements("yy", "xx")),
            "yyHello there!"));
        $this->assertTrue(
            CString::equals(CString::stripStart("xxHello there!", "yy"), "xxHello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripStartCi ()
    {
        $this->assertTrue(
            CString::equals(CString::stripStartCi("xxHello there!", "XX"), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::stripStartCi("xxyyHello there!",
            CArray::fromElements("XX", "YY")), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::stripStartCi("xxyyHello there!",
            CArray::fromElements("YY", "XX")), "yyHello there!"));
        $this->assertTrue(
            CString::equals(CString::stripStartCi("xxHello there!", "YY"), "xxHello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripEnd ()
    {
        $this->assertTrue(
            CString::equals(CString::stripEnd("Hello there!xx", "xx"), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::stripEnd("Hello there!yyxx", CArray::fromElements("xx", "yy")), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::stripEnd("Hello there!yyxx", CArray::fromElements("yy", "xx")), "Hello there!yy"));
        $this->assertTrue(
            CString::equals(CString::stripEnd("Hello there!xx", "yy"), "Hello there!xx"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStripEndCi ()
    {
        $this->assertTrue(
            CString::equals(CString::stripEndCi("Hello there!xx", "XX"), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::stripEndCi("Hello there!yyxx", CArray::fromElements("XX", "YY")), "Hello there!"));
        $this->assertTrue(
            CString::equals(CString::stripEndCi("Hello there!yyxx",
            CArray::fromElements("YY", "XX")), "Hello there!yy"));
        $this->assertTrue(
            CString::equals(CString::stripEndCi("Hello there!xx", "YY"), "Hello there!xx"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsert ()
    {
        $this->assertTrue(
            CString::equals(CString::insert("Hello there!", 6, "you "), "Hello you there!"));
        $this->assertTrue(
            CString::equals(CString::insert("Hello there!", 0, "you "), "you Hello there!"));
        $this->assertTrue(
            CString::equals(CString::insert("Hello there!", 12, " you"), "Hello there! you"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceSubstring ()
    {
        $this->assertTrue(
            CString::equals(CString::replaceSubstring("Hello there!", 6, 5, "you"), "Hello you!"));
        $this->assertTrue(
            CString::equals(CString::replaceSubstring("Hello there!", 0, 0, "you "), "you Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceSubstringByRange ()
    {
        $this->assertTrue(
            CString::equals(CString::replaceSubstringByRange("Hello there!", 6, 11, "you"), "Hello you!"));
        $this->assertTrue(
            CString::equals(CString::replaceSubstringByRange("Hello there!", 0, 0, "you "), "you Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubstring ()
    {
        $this->assertTrue(
            CString::equals(CString::removeSubstring("Hello there!", 5, 6), "Hello!"));
        $this->assertTrue(
            CString::equals(CString::removeSubstring("Hello there!", 5, 0), "Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubstringByRange ()
    {
        $this->assertTrue(
            CString::equals(CString::removeSubstringByRange("Hello there!", 5, 11), "Hello!"));
        $this->assertTrue(
            CString::equals(CString::removeSubstringByRange("Hello there!", 5, 5), "Hello there!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplace ()
    {
        $this->assertTrue(
            CString::equals(CString::replace("Hello there!", "Hello", "Hola"), "Hola there!"));

        $quantity;
        $this->assertTrue(
            CString::equals(CString::replace("Hello-Hello there!", "Hello", "Hola", $quantity),
            "Hola-Hola there!") && $quantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceCi ()
    {
        $this->assertTrue(
            CString::equals(CString::replaceCi("Hello there!", "HELLO", "Hola"), "Hola there!"));

        $quantity;
        $this->assertTrue(
            CString::equals(CString::replaceCi("Hello-Hello there!", "HELLO", "Hola", $quantity),
            "Hola-Hola there!") && $quantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $this->assertTrue(
            CString::equals(CString::remove("Hello there!", " there"), "Hello!"));

        $quantity;
        $this->assertTrue(
            CString::equals(CString::remove("Hello there there!", " there", $quantity), "Hello!") &&
            $quantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveCi ()
    {
        $this->assertTrue(
            CString::equals(CString::removeCi("Hello there!", " THERE"), "Hello!"));

        $quantity;
        $this->assertTrue(
            CString::equals(CString::removeCi("Hello there there!", " THERE", $quantity), "Hello!") &&
            $quantity == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShuffle ()
    {
        $string = "abcdefghijklmnopqrstuvwxyz";
        $shuffledString = CString::shuffle($string);
        $this->assertTrue(CString::isSubsetOf($shuffledString, $string));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWordWrap ()
    {
        $this->assertTrue(
            CString::equals(CString::wordWrap(
            "Hello there! Hello there! Hello there! Hello there! Hello there! Hello there! Hello there! Hello there!",
            21),
            "Hello there! Hello\nthere! Hello there!\nHello there! Hello\nthere! Hello there!\nHello there! Hello\n" .
            "there!"));

        $this->assertTrue(
            CString::equals(CString::wordWrap(
            "Hello there! Hello there! Hello there! Hello there! Hello there! Hello there! Hello there! Hello there!",
            4, true),
            "Hell\no\nther\ne!\nHell\no\nther\ne!\nHell\no\nther\ne!\nHell\no\nther\ne!\nHell\no\nther\ne!\nHell\no\n" .
            "ther\ne!\nHell\no\nther\ne!\nHell\no\nther\ne!"));

        $this->assertTrue(
            CString::equals(CString::wordWrap(
            "Hello there! Hello there! Hello there! Hello there! Hello there! Hello there! Hello there! Hello there!",
            21, false, "\r"),
            "Hello there! Hello\rthere! Hello there!\rHello there! Hello\rthere! Hello there!\rHello there! Hello\r" .
            "there!"));

        $this->assertTrue(
            CString::equals(CString::wordWrap(
            "Hello there! Hello there! Hello there! Hello there! Hello there! Hello there! Hello there! Hello there!",
            4, true, "\r"),
            "Hell\ro\rther\re!\rHell\ro\rther\re!\rHell\ro\rther\re!\rHell\ro\rther\re!\rHell\ro\rther\re!\rHell\ro\r" .
            "ther\re!\rHell\ro\rther\re!\rHell\ro\rther\re!"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDecToHex ()
    {
        $this->assertTrue(
            CString::equals(CString::decToHex("256"), "100"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHexToDec ()
    {
        $this->assertTrue(
            CString::equals(CString::hexToDec("100"), "256"));
        $this->assertTrue(
            CString::equals(CString::hexToDec("0x100"), "256"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumberToBase ()
    {
        $this->assertTrue(
            CString::equals(CString::numberToBase("100", 8, 10), "64"));
        $this->assertTrue(
            CString::equals(CString::numberToBase("100", 16, 10), "256"));
        $this->assertTrue(
            CString::equals(CString::numberToBase("0x100", 16, 10), "256"));
        $this->assertTrue(
            CString::equals(CString::numberToBase("100", 8, 16), "40"));
        $this->assertTrue(
            CString::equals(CString::numberToBase("101", 2, 10), "5"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRepeat ()
    {
        $this->assertTrue(
            CString::equals(CString::repeat("a", 5), "aaaaa"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
