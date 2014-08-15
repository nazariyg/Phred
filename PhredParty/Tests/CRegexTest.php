<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CRegexTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIndexOf ()
    {
        // ASCII.

        $this->assertTrue( CRegex::indexOf("Hello there!", "/[^\\w ]/") == 11 );

        $this->assertTrue( CRegex::indexOf("Hello! There!", "/[^\\w ]/", 6) == 12 );

        $sFoundString;
        $iPos = CRegex::indexOf("Hello MISTER there!", "/[A-Z]{2,}/", 0, $sFoundString);
        $this->assertTrue( $iPos == 6 && CString::equals($sFoundString, "MISTER") );

        $this->assertTrue( CRegex::indexOf("Hello there!", "/\\d/") == -1 );

        // Unicode.

        $this->assertTrue( CRegex::indexOf("¡Hello there!", "/[^\\p{L} ]/u", 2) == 13 );

        $this->assertTrue( CRegex::indexOf("¡Hello! There!", "/[^\\p{L} ]/u", 8) == 14 );

        $sFoundString;
        $iPos = CRegex::indexOf("¡Hello SEÑOR there!", "/\\p{Lu}{2,}/u", 0, $sFoundString);
        $this->assertTrue( $iPos == 8 && CUString::equals($sFoundString, "SEÑOR") );

        $this->assertTrue( CRegex::indexOf("¡Hello there!", "/\\d/u") == -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastIndexOf ()
    {
        // ASCII.

        $this->assertTrue( CRegex::lastIndexOf("Hello there!?", "/[^\\w ]/") == 12 );

        $this->assertTrue( CRegex::lastIndexOf("Hello! There!?", "/[^\\w ]/", 0) == 13 );

        $sFoundString;
        $iPos = CRegex::lastIndexOf("Hello YOU there! Hello MISTER there!", "/[A-Z]{2,}/", 0, $sFoundString);
        $this->assertTrue( $iPos == 23 && CString::equals($sFoundString, "MISTER") );

        $this->assertTrue( CRegex::lastIndexOf("Hello there!?", "/\\d/") == -1 );

        // Unicode.

        $this->assertTrue( CRegex::lastIndexOf("¿¡Hello there!?", "/[^\\p{L} ]/u") == 16 );

        $this->assertTrue( CRegex::lastIndexOf("¿¡Hello! There!?", "/[^\\p{L} ]/u", 0) == 17 );

        $sFoundString;
        $iPos = CRegex::lastIndexOf("¿¡Hello YOU there!? ¿¡Hello SEÑOR there!?", "/\\p{Lu}{2,}/u", 0, $sFoundString);
        $this->assertTrue( $iPos == 32 && CUString::equals($sFoundString, "SEÑOR") );

        $this->assertTrue( CRegex::lastIndexOf("¿¡Hello there!?", "/\\d/u") == -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        // ASCII.

        $this->assertTrue(CRegex::find("Hello there!", "/[^\\w ]/"));

        $sFoundString;
        $bFound = CRegex::find("Hello there!", "/[^\\w ]/", $sFoundString);
        $this->assertTrue( $bFound && CString::equals($sFoundString, "!") );

        $sFoundString;
        $this->assertFalse(CRegex::find("Hello there!", "/\\d/", $sFoundString));

        // Unicode.

        $this->assertTrue(CRegex::find("¡Hello there!", "/[^\\p{L} ]/u"));

        $sFoundString;
        $bFound = CRegex::find("¡Hello There!", "/[^\\p{L} ]/u", $sFoundString);
        $this->assertTrue( $bFound && CUString::equals($sFoundString, "¡") );

        $sFoundString;
        $this->assertFalse(CRegex::find("¡Hello there!", "/\\d/u", $sFoundString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindFrom ()
    {
        // ASCII.

        $this->assertTrue(CRegex::findFrom("Hello! there!", "/[^\\w ]/", 6));

        $sFoundString;
        $bFound = CRegex::findFrom("Hello? there!", "/[^\\w ]/", 6, $sFoundString);
        $this->assertTrue( $bFound && CString::equals($sFoundString, "!") );

        $this->assertFalse(CRegex::findFrom("Hello! there", "/!/", 6));

        // Unicode.

        $this->assertTrue(CRegex::findFrom("¡Hello there!", "/[^\\p{L} ]/u", 2));

        $sFoundString;
        $bFound = CRegex::findFrom("¡Hello? There!", "/[^\\p{L} ]/u", 8, $sFoundString);
        $this->assertTrue( $bFound && CUString::equals($sFoundString, "!") );

        $this->assertFalse(CRegex::findFrom("¡Hello 2 you there!", "/\\d/u", 9));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindGroups ()
    {
        // ASCII.

        $aFoundGroups;
        $bFound = CRegex::findGroups("Hello there!", "/^(\\w+) (\\w+)/", $aFoundGroups);
        $this->assertTrue(
            $bFound && CArray::length($aFoundGroups) == 2 &&
            CString::equals($aFoundGroups[0], "Hello") && CString::equals($aFoundGroups[1], "there") );

        $aFoundGroups;
        $sFoundString;
        $bFound = CRegex::findGroups("Hello there!", "/^(\\w+) (\\S+)/", $aFoundGroups, $sFoundString);
        $this->assertTrue(
            $bFound && CArray::length($aFoundGroups) == 2 &&
            CString::equals($aFoundGroups[0], "Hello") && CString::equals($aFoundGroups[1], "there!") &&
            CString::equals($sFoundString, "Hello there!") );

        $aFoundGroups;
        $this->assertFalse(CRegex::findGroups("Hello there!", "/^(\\w+) (\\w+)\\z/", $aFoundGroups));

        $aFoundGroups;
        $bFound = CRegex::findGroups("Hello there!", "/^\\w+ \\w+/", $aFoundGroups);
        $this->assertTrue( $bFound && CArray::isEmpty($aFoundGroups) );

        // Unicode.

        $aFoundGroups;
        $bFound = CRegex::findGroups("¡Hello señor!", "/(\\w+) (\\w+)/u", $aFoundGroups);
        $this->assertTrue(
            $bFound && CArray::length($aFoundGroups) == 2 &&
            CUString::equals($aFoundGroups[0], "Hello") && CUString::equals($aFoundGroups[1], "señor") );

        $aFoundGroups;
        $sFoundString;
        $bFound = CRegex::findGroups("¡Hello señor!", "/(\\w+) (\\S+)/u", $aFoundGroups, $sFoundString);
        $this->assertTrue(
            $bFound && CArray::length($aFoundGroups) == 2 &&
            CUString::equals($aFoundGroups[0], "Hello") && CUString::equals($aFoundGroups[1], "señor!") &&
            CUString::equals($sFoundString, "Hello señor!") );

        $aFoundGroups;
        $this->assertFalse(CRegex::findGroups("¡Hello señor!", "/^(\\w+) (\\w+)/u", $aFoundGroups));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindGroupsFrom ()
    {
        // ASCII.

        $aFoundGroups;
        $bFound = CRegex::findGroupsFrom("Hello there you!", "/(\\w+) (\\w+)/", 5, $aFoundGroups);
        $this->assertTrue(
            $bFound && CArray::length($aFoundGroups) == 2 &&
            CString::equals($aFoundGroups[0], "there") && CString::equals($aFoundGroups[1], "you") );

        $aFoundGroups;
        $sFoundString;
        $bFound = CRegex::findGroupsFrom("Hello there you!", "/(\\w+) (\\S+)/", 5, $aFoundGroups, $sFoundString);
        $this->assertTrue(
            $bFound && CArray::length($aFoundGroups) == 2 &&
            CString::equals($aFoundGroups[0], "there") && CString::equals($aFoundGroups[1], "you!") &&
            CString::equals($sFoundString, "there you!") );

        $aFoundGroups;
        $this->assertFalse(CRegex::findGroupsFrom("Hello there!", "/\\b(\\w+) (\\w+)/", 1, $aFoundGroups));

        // Unicode.

        $aFoundGroups;
        $bFound = CRegex::findGroupsFrom("¡Hello señor you!", "/(\\w+) (\\w+)/u", 7, $aFoundGroups);
        $this->assertTrue(
            $bFound && CArray::length($aFoundGroups) == 2 &&
            CUString::equals($aFoundGroups[0], "señor") && CUString::equals($aFoundGroups[1], "you") );

        $aFoundGroups;
        $sFoundString;
        $bFound = CRegex::findGroupsFrom("¡Hello señor you!", "/(\\w+) (\\S+)/u", 7, $aFoundGroups, $sFoundString);
        $this->assertTrue(
            $bFound && CArray::length($aFoundGroups) == 2 &&
            CUString::equals($aFoundGroups[0], "señor") && CUString::equals($aFoundGroups[1], "you!") &&
            CUString::equals($sFoundString, "señor you!") );

        $aFoundGroups;
        $this->assertFalse(CRegex::findGroupsFrom("¡Hello señor!", "/(\\w+) (\\w+)/u", 7, $aFoundGroups));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindAll ()
    {
        // ASCII.

        $iNumFound = CRegex::findAll("Hello there!", "/\\w+/");
        $this->assertTrue( $iNumFound == 2 );

        $aFoundStrings;
        $iNumFound = CRegex::findAll("Hello there!", "/\\w+/", $aFoundStrings);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundStrings) == 2 &&
            CString::equals($aFoundStrings[0], "Hello") && CString::equals($aFoundStrings[1], "there") );

        $aFoundStrings;
        $iNumFound = CRegex::findAll("Hello there!", "/a\\w+/", $aFoundStrings);
        $this->assertTrue( $iNumFound == 0 );

        // Unicode.

        $iNumFound = CRegex::findAll("¡Hello señor!", "/\\w+/u");
        $this->assertTrue( $iNumFound == 2 );

        $aFoundStrings;
        $iNumFound = CRegex::findAll("¡Hello señor!", "/\\w+/u", $aFoundStrings);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundStrings) == 2 &&
            CUString::equals($aFoundStrings[0], "Hello") && CUString::equals($aFoundStrings[1], "señor") );

        $aFoundStrings;
        $iNumFound = CRegex::findAll("¡Hello señor!", "/a\\w+/u", $aFoundStrings);
        $this->assertTrue( $iNumFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindAllFrom ()
    {
        // ASCII.

        $aFoundStrings;
        $iNumFound = CRegex::findAllFrom("Hello there!", "/\\w+/", 5, $aFoundStrings);
        $this->assertTrue(
            $iNumFound == 1 && CArray::length($aFoundStrings) == 1 &&
            CString::equals($aFoundStrings[0], "there") );

        $aFoundStrings;
        $iNumFound = CRegex::findAllFrom("Hello there!", "/H\\w+/", 5, $aFoundStrings);
        $this->assertTrue( $iNumFound == 0 );

        // Unicode.

        $aFoundStrings;
        $iNumFound = CRegex::findAllFrom("¡Hello señor!", "/\\S+/u", 7, $aFoundStrings);
        $this->assertTrue(
            $iNumFound == 1 && CArray::length($aFoundStrings) == 1 &&
            CUString::equals($aFoundStrings[0], "señor!") );

        $aFoundStrings;
        $iNumFound = CRegex::findAllFrom("¡Hello señor!", "/H\\w+/u", 7, $aFoundStrings);
        $this->assertTrue( $iNumFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindAllGroups ()
    {
        // ASCII.

        $aFoundGroupArrays;
        $iNumFound = CRegex::findAllGroups("Hello there!", "/(\\w\\w)(\\w\\w)/", $aFoundGroupArrays);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundGroupArrays) == 2 &&
            CString::equals($aFoundGroupArrays[0][0], "He") && CString::equals($aFoundGroupArrays[0][1], "ll") &&
            CString::equals($aFoundGroupArrays[1][0], "th") && CString::equals($aFoundGroupArrays[1][1], "er") );

        $aFoundGroupArrays;
        $aFoundStrings;
        $iNumFound = CRegex::findAllGroups("Hello there!", "/(\\w\\w)(\\w\\w)/", $aFoundGroupArrays, $aFoundStrings);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundGroupArrays) == 2 &&
            CString::equals($aFoundGroupArrays[0][0], "He") && CString::equals($aFoundGroupArrays[0][1], "ll") &&
            CString::equals($aFoundGroupArrays[1][0], "th") && CString::equals($aFoundGroupArrays[1][1], "er") &&
            CArray::length($aFoundStrings) == 2 &&
            CString::equals($aFoundStrings[0], "Hell") && CString::equals($aFoundStrings[1], "ther") );

        $aFoundGroupArrays;
        $iNumFound = CRegex::findAllGroups("Hello there!", "/(\\w\\w\\w)(\\w\\w\\w)/", $aFoundGroupArrays);
        $this->assertTrue( $iNumFound == 0 );

        // Unicode.

        $aFoundGroupArrays;
        $iNumFound = CRegex::findAllGroups("¡Hello señor!", "/(\\w\\w)(\\w\\w)/u", $aFoundGroupArrays);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundGroupArrays) == 2 &&
            CUString::equals($aFoundGroupArrays[0][0], "He") && CUString::equals($aFoundGroupArrays[0][1], "ll") &&
            CUString::equals($aFoundGroupArrays[1][0], "se") && CUString::equals($aFoundGroupArrays[1][1], "ño") );

        $aFoundGroupArrays;
        $aFoundStrings;
        $iNumFound = CRegex::findAllGroups("¡Hello señor!", "/(\\w\\w)(\\w\\w)/u", $aFoundGroupArrays, $aFoundStrings);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundGroupArrays) == 2 &&
            CUString::equals($aFoundGroupArrays[0][0], "He") && CUString::equals($aFoundGroupArrays[0][1], "ll") &&
            CUString::equals($aFoundGroupArrays[1][0], "se") && CUString::equals($aFoundGroupArrays[1][1], "ño") &&
            CArray::length($aFoundStrings) == 2 &&
            CUString::equals($aFoundStrings[0], "Hell") && CUString::equals($aFoundStrings[1], "seño") );

        $aFoundGroupArrays;
        $iNumFound = CRegex::findAllGroups("¡Hello señor!", "/(\\w\\w\\w)(\\w\\w\\w)/u", $aFoundGroupArrays);
        $this->assertTrue( $iNumFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindAllGroupsFrom ()
    {
        // ASCII.

        $aFoundGroupArrays;
        $iNumFound = CRegex::findAllGroupsFrom("Hello there!", "/(\\w\\w)(\\w\\w)/", 1, $aFoundGroupArrays);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundGroupArrays) == 2 &&
            CString::equals($aFoundGroupArrays[0][0], "el") && CString::equals($aFoundGroupArrays[0][1], "lo") &&
            CString::equals($aFoundGroupArrays[1][0], "th") && CString::equals($aFoundGroupArrays[1][1], "er") );

        $aFoundGroupArrays;
        $aFoundStrings;
        $iNumFound = CRegex::findAllGroupsFrom("Hello there!", "/(\\w\\w)(\\w\\w)/", 1, $aFoundGroupArrays,
            $aFoundStrings);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundGroupArrays) == 2 &&
            CString::equals($aFoundGroupArrays[0][0], "el") && CString::equals($aFoundGroupArrays[0][1], "lo") &&
            CString::equals($aFoundGroupArrays[1][0], "th") && CString::equals($aFoundGroupArrays[1][1], "er") &&
            CArray::length($aFoundStrings) == 2 &&
            CString::equals($aFoundStrings[0], "ello") && CString::equals($aFoundStrings[1], "ther") );

        $aFoundGroupArrays;
        $iNumFound = CRegex::findAllGroupsFrom("Hello there!", "/(\\w\\w\\w)(\\w\\w\\w)/", 1, $aFoundGroupArrays);
        $this->assertTrue( $iNumFound == 0 );

        // Unicode.

        $aFoundGroupArrays;
        $iNumFound = CRegex::findAllGroupsFrom("¡Hello señor!", "/(\\w\\w)(\\w\\w)/u", 3, $aFoundGroupArrays);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundGroupArrays) == 2 &&
            CUString::equals($aFoundGroupArrays[0][0], "el") && CUString::equals($aFoundGroupArrays[0][1], "lo") &&
            CUString::equals($aFoundGroupArrays[1][0], "se") && CUString::equals($aFoundGroupArrays[1][1], "ño") );

        $aFoundGroupArrays;
        $aFoundStrings;
        $iNumFound = CRegex::findAllGroupsFrom("¡Hello señor!", "/(\\w\\w)(\\w\\w)/u", 3, $aFoundGroupArrays,
            $aFoundStrings);
        $this->assertTrue(
            $iNumFound == 2 && CArray::length($aFoundGroupArrays) == 2 &&
            CUString::equals($aFoundGroupArrays[0][0], "el") && CUString::equals($aFoundGroupArrays[0][1], "lo") &&
            CUString::equals($aFoundGroupArrays[1][0], "se") && CUString::equals($aFoundGroupArrays[1][1], "ño") &&
            CArray::length($aFoundStrings) == 2 &&
            CUString::equals($aFoundStrings[0], "ello") && CUString::equals($aFoundStrings[1], "seño") );

        $aFoundGroupArrays;
        $iNumFound = CRegex::findAllGroupsFrom("¡Hello señor!", "/(\\w\\w\\w)(\\w\\w\\w)/u", 3, $aFoundGroupArrays);
        $this->assertTrue( $iNumFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplace ()
    {
        // ASCII.

        $sRes = CRegex::replace("Hello there!", "/[eo]/", "a");
        $this->assertTrue(CString::equals($sRes, "Halla thara!"));

        $iQuantity;
        $sRes = CRegex::replace("Hello there!", "/[eo]/", "a", $iQuantity);
        $this->assertTrue( CString::equals($sRes, "Halla thara!") && $iQuantity == 4 );

        // Unicode.

        $sRes = CRegex::replace("¡Hello señor!", "/[eoñ]/u", "a");
        $this->assertTrue(CUString::equals($sRes, "¡Halla saaar!"));

        $iQuantity;
        $sRes = CRegex::replace("¡Hello señor!", "/[eoñ]/u", "a", $iQuantity);
        $this->assertTrue( CUString::equals($sRes, "¡Halla saaar!") && $iQuantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceWithCallback ()
    {
        // ASCII.

        $sRes = CRegex::replaceWithCallback("Hello there!", "/[eo]/", function ($mMatches)
            {
                return ( CString::equals($mMatches[0], "e") ) ? "3" : $mMatches[0];
            });
        $this->assertTrue(CString::equals($sRes, "H3llo th3r3!"));

        $iQuantity;
        $sRes = CRegex::replaceWithCallback("Hello there!", "/[eo]/", function ($mMatches)
            {
                return ( CString::equals($mMatches[0], "e") ) ? "3" : $mMatches[0];
            }, $iQuantity);
        $this->assertTrue( CString::equals($sRes, "H3llo th3r3!") && $iQuantity == 4 );

        // Unicode.

        $sRes = CRegex::replaceWithCallback("¡Hello señor!", "/[eoñ]/u", function ($mMatches)
            {
                return ( CUString::equals($mMatches[0], "ñ") ) ? "n" : $mMatches[0];
            });
        $this->assertTrue(CUString::equals($sRes, "¡Hello senor!"));

        $iQuantity;
        $sRes = CRegex::replaceWithCallback("¡Hello señor!", "/[eoñ]/u", function ($mMatches)
            {
                return ( CUString::equals($mMatches[0], "ñ") ) ? "n" : $mMatches[0];
            }, $iQuantity);
        $this->assertTrue( CUString::equals($sRes, "¡Hello senor!") && $iQuantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        // ASCII.

        $sRes = CRegex::remove("Hello there!", "/[eo]/");
        $this->assertTrue(CString::equals($sRes, "Hll thr!"));

        $iQuantity;
        $sRes = CRegex::remove("Hello there!", "/[eo]/", $iQuantity);
        $this->assertTrue( CString::equals($sRes, "Hll thr!") && $iQuantity == 4 );

        // Unicode.

        $sRes = CRegex::remove("¡Hello señor!", "/[eoñ]/u");
        $this->assertTrue(CUString::equals($sRes, "¡Hll sr!"));

        $iQuantity;
        $sRes = CRegex::remove("¡Hello señor!", "/[eoñ]/u", $iQuantity);
        $this->assertTrue( CUString::equals($sRes, "¡Hll sr!") && $iQuantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplit ()
    {
        // ASCII.

        $aRes = CRegex::split("He,llo;th,ere!", "/[,;]/");
        $this->assertTrue( CArray::length($aRes) == 4 &&
            CString::equals($aRes[0], "He") && CString::equals($aRes[1], "llo") &&
            CString::equals($aRes[2], "th") && CString::equals($aRes[3], "ere!") );

        $aRes = CRegex::split("He,llo;th.ere!", CArray::fromElements("/[,;]/", "/\\./"));
        $this->assertTrue( CArray::length($aRes) == 4 &&
            CString::equals($aRes[0], "He") && CString::equals($aRes[1], "llo") &&
            CString::equals($aRes[2], "th") && CString::equals($aRes[3], "ere!") );

        // Special cases.

        $aRes = CRegex::split("", "/[,;]/");
        $this->assertTrue( CArray::length($aRes) == 1 && CString::equals($aRes[0], "") );

        $aRes = CRegex::split("Hey", "//");
        $this->assertTrue( CArray::length($aRes) == 3 &&
            CString::equals($aRes[0], "H") && CString::equals($aRes[1], "e") && CString::equals($aRes[2], "y") );

        $aRes = CRegex::split("", "//");
        $this->assertTrue( CArray::length($aRes) == 1 && CString::equals($aRes[0], "") );

        // Unicode.

        $aRes = CRegex::split("¡He,llo·se,ñor!", "/[,·]/u");
        $this->assertTrue( CArray::length($aRes) == 4 &&
            CUString::equals($aRes[0], "¡He") && CUString::equals($aRes[1], "llo") &&
            CUString::equals($aRes[2], "se") && CUString::equals($aRes[3], "ñor!") );

        $aRes = CRegex::split("¡He,llo·se.ñor!", CArray::fromElements("/[,·]/u", "/\\./u"));
        $this->assertTrue( CArray::length($aRes) == 4 &&
            CUString::equals($aRes[0], "¡He") && CUString::equals($aRes[1], "llo") &&
            CUString::equals($aRes[2], "se") && CUString::equals($aRes[3], "ñor!") );

        // Special cases.

        $aRes = CRegex::split("", "/[,·]/u");
        $this->assertTrue( CArray::length($aRes) == 1 && CUString::equals($aRes[0], "") );

        $aRes = CRegex::split("Héy", "//u");
        $this->assertTrue( CArray::length($aRes) == 3 &&
            CUString::equals($aRes[0], "H") && CUString::equals($aRes[1], "é") && CUString::equals($aRes[2], "y") );

        $aRes = CRegex::split("", "//u");
        $this->assertTrue( CArray::length($aRes) == 1 && CUString::equals($aRes[0], "") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEnterTd ()
    {
        // ASCII.

        $sRes = CRegex::enterTd(".(]/", "/");
        $this->assertTrue(CString::equals($sRes, "\\.\\(\\]\\/"));

        $sRes = CRegex::enterTd(".(]#", "#");
        $this->assertTrue(CString::equals($sRes, "\\.\\(\\]\\#"));

        // Unicode.

        $sRes = CRegex::enterTd(".(señor]/", "/");
        $this->assertTrue(CString::equals($sRes, "\\.\\(señor\\]\\/"));

        $sRes = CRegex::enterTd(".(señor]#", "#");
        $this->assertTrue(CString::equals($sRes, "\\.\\(señor\\]\\#"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
