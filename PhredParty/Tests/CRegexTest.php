<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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

        $foundString;
        $pos = CRegex::indexOf("Hello MISTER there!", "/[A-Z]{2,}/", 0, $foundString);
        $this->assertTrue( $pos == 6 && CString::equals($foundString, "MISTER") );

        $this->assertTrue( CRegex::indexOf("Hello there!", "/\\d/") == -1 );

        // Unicode.

        $this->assertTrue( CRegex::indexOf("¡Hello there!", "/[^\\p{L} ]/u", 2) == 13 );

        $this->assertTrue( CRegex::indexOf("¡Hello! There!", "/[^\\p{L} ]/u", 8) == 14 );

        $foundString;
        $pos = CRegex::indexOf("¡Hello SEÑOR there!", "/\\p{Lu}{2,}/u", 0, $foundString);
        $this->assertTrue( $pos == 8 && CUString::equals($foundString, "SEÑOR") );

        $this->assertTrue( CRegex::indexOf("¡Hello there!", "/\\d/u") == -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLastIndexOf ()
    {
        // ASCII.

        $this->assertTrue( CRegex::lastIndexOf("Hello there!?", "/[^\\w ]/") == 12 );

        $this->assertTrue( CRegex::lastIndexOf("Hello! There!?", "/[^\\w ]/", 0) == 13 );

        $foundString;
        $pos = CRegex::lastIndexOf("Hello YOU there! Hello MISTER there!", "/[A-Z]{2,}/", 0, $foundString);
        $this->assertTrue( $pos == 23 && CString::equals($foundString, "MISTER") );

        $this->assertTrue( CRegex::lastIndexOf("Hello there!?", "/\\d/") == -1 );

        // Unicode.

        $this->assertTrue( CRegex::lastIndexOf("¿¡Hello there!?", "/[^\\p{L} ]/u") == 16 );

        $this->assertTrue( CRegex::lastIndexOf("¿¡Hello! There!?", "/[^\\p{L} ]/u", 0) == 17 );

        $foundString;
        $pos = CRegex::lastIndexOf("¿¡Hello YOU there!? ¿¡Hello SEÑOR there!?", "/\\p{Lu}{2,}/u", 0, $foundString);
        $this->assertTrue( $pos == 32 && CUString::equals($foundString, "SEÑOR") );

        $this->assertTrue( CRegex::lastIndexOf("¿¡Hello there!?", "/\\d/u") == -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        // ASCII.

        $this->assertTrue(CRegex::find("Hello there!", "/[^\\w ]/"));

        $foundString;
        $found = CRegex::find("Hello there!", "/[^\\w ]/", $foundString);
        $this->assertTrue( $found && CString::equals($foundString, "!") );

        $foundString;
        $this->assertFalse(CRegex::find("Hello there!", "/\\d/", $foundString));

        // Unicode.

        $this->assertTrue(CRegex::find("¡Hello there!", "/[^\\p{L} ]/u"));

        $foundString;
        $found = CRegex::find("¡Hello There!", "/[^\\p{L} ]/u", $foundString);
        $this->assertTrue( $found && CUString::equals($foundString, "¡") );

        $foundString;
        $this->assertFalse(CRegex::find("¡Hello there!", "/\\d/u", $foundString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindFrom ()
    {
        // ASCII.

        $this->assertTrue(CRegex::findFrom("Hello! there!", "/[^\\w ]/", 6));

        $foundString;
        $found = CRegex::findFrom("Hello? there!", "/[^\\w ]/", 6, $foundString);
        $this->assertTrue( $found && CString::equals($foundString, "!") );

        $this->assertFalse(CRegex::findFrom("Hello! there", "/!/", 6));

        // Unicode.

        $this->assertTrue(CRegex::findFrom("¡Hello there!", "/[^\\p{L} ]/u", 2));

        $foundString;
        $found = CRegex::findFrom("¡Hello? There!", "/[^\\p{L} ]/u", 8, $foundString);
        $this->assertTrue( $found && CUString::equals($foundString, "!") );

        $this->assertFalse(CRegex::findFrom("¡Hello 2 you there!", "/\\d/u", 9));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindGroups ()
    {
        // ASCII.

        $foundGroups;
        $found = CRegex::findGroups("Hello there!", "/^(\\w+) (\\w+)/", $foundGroups);
        $this->assertTrue(
            $found && CArray::length($foundGroups) == 2 &&
            CString::equals($foundGroups[0], "Hello") && CString::equals($foundGroups[1], "there") );

        $foundGroups;
        $foundString;
        $found = CRegex::findGroups("Hello there!", "/^(\\w+) (\\S+)/", $foundGroups, $foundString);
        $this->assertTrue(
            $found && CArray::length($foundGroups) == 2 &&
            CString::equals($foundGroups[0], "Hello") && CString::equals($foundGroups[1], "there!") &&
            CString::equals($foundString, "Hello there!") );

        $foundGroups;
        $this->assertFalse(CRegex::findGroups("Hello there!", "/^(\\w+) (\\w+)\\z/", $foundGroups));

        $foundGroups;
        $found = CRegex::findGroups("Hello there!", "/^\\w+ \\w+/", $foundGroups);
        $this->assertTrue( $found && CArray::isEmpty($foundGroups) );

        // Unicode.

        $foundGroups;
        $found = CRegex::findGroups("¡Hello señor!", "/(\\w+) (\\w+)/u", $foundGroups);
        $this->assertTrue(
            $found && CArray::length($foundGroups) == 2 &&
            CUString::equals($foundGroups[0], "Hello") && CUString::equals($foundGroups[1], "señor") );

        $foundGroups;
        $foundString;
        $found = CRegex::findGroups("¡Hello señor!", "/(\\w+) (\\S+)/u", $foundGroups, $foundString);
        $this->assertTrue(
            $found && CArray::length($foundGroups) == 2 &&
            CUString::equals($foundGroups[0], "Hello") && CUString::equals($foundGroups[1], "señor!") &&
            CUString::equals($foundString, "Hello señor!") );

        $foundGroups;
        $this->assertFalse(CRegex::findGroups("¡Hello señor!", "/^(\\w+) (\\w+)/u", $foundGroups));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindGroupsFrom ()
    {
        // ASCII.

        $foundGroups;
        $found = CRegex::findGroupsFrom("Hello there you!", "/(\\w+) (\\w+)/", 5, $foundGroups);
        $this->assertTrue(
            $found && CArray::length($foundGroups) == 2 &&
            CString::equals($foundGroups[0], "there") && CString::equals($foundGroups[1], "you") );

        $foundGroups;
        $foundString;
        $found = CRegex::findGroupsFrom("Hello there you!", "/(\\w+) (\\S+)/", 5, $foundGroups, $foundString);
        $this->assertTrue(
            $found && CArray::length($foundGroups) == 2 &&
            CString::equals($foundGroups[0], "there") && CString::equals($foundGroups[1], "you!") &&
            CString::equals($foundString, "there you!") );

        $foundGroups;
        $this->assertFalse(CRegex::findGroupsFrom("Hello there!", "/\\b(\\w+) (\\w+)/", 1, $foundGroups));

        // Unicode.

        $foundGroups;
        $found = CRegex::findGroupsFrom("¡Hello señor you!", "/(\\w+) (\\w+)/u", 7, $foundGroups);
        $this->assertTrue(
            $found && CArray::length($foundGroups) == 2 &&
            CUString::equals($foundGroups[0], "señor") && CUString::equals($foundGroups[1], "you") );

        $foundGroups;
        $foundString;
        $found = CRegex::findGroupsFrom("¡Hello señor you!", "/(\\w+) (\\S+)/u", 7, $foundGroups, $foundString);
        $this->assertTrue(
            $found && CArray::length($foundGroups) == 2 &&
            CUString::equals($foundGroups[0], "señor") && CUString::equals($foundGroups[1], "you!") &&
            CUString::equals($foundString, "señor you!") );

        $foundGroups;
        $this->assertFalse(CRegex::findGroupsFrom("¡Hello señor!", "/(\\w+) (\\w+)/u", 7, $foundGroups));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindAll ()
    {
        // ASCII.

        $numFound = CRegex::findAll("Hello there!", "/\\w+/");
        $this->assertTrue( $numFound == 2 );

        $foundStrings;
        $numFound = CRegex::findAll("Hello there!", "/\\w+/", $foundStrings);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundStrings) == 2 &&
            CString::equals($foundStrings[0], "Hello") && CString::equals($foundStrings[1], "there") );

        $foundStrings;
        $numFound = CRegex::findAll("Hello there!", "/a\\w+/", $foundStrings);
        $this->assertTrue( $numFound == 0 );

        // Unicode.

        $numFound = CRegex::findAll("¡Hello señor!", "/\\w+/u");
        $this->assertTrue( $numFound == 2 );

        $foundStrings;
        $numFound = CRegex::findAll("¡Hello señor!", "/\\w+/u", $foundStrings);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundStrings) == 2 &&
            CUString::equals($foundStrings[0], "Hello") && CUString::equals($foundStrings[1], "señor") );

        $foundStrings;
        $numFound = CRegex::findAll("¡Hello señor!", "/a\\w+/u", $foundStrings);
        $this->assertTrue( $numFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindAllFrom ()
    {
        // ASCII.

        $foundStrings;
        $numFound = CRegex::findAllFrom("Hello there!", "/\\w+/", 5, $foundStrings);
        $this->assertTrue(
            $numFound == 1 && CArray::length($foundStrings) == 1 &&
            CString::equals($foundStrings[0], "there") );

        $foundStrings;
        $numFound = CRegex::findAllFrom("Hello there!", "/H\\w+/", 5, $foundStrings);
        $this->assertTrue( $numFound == 0 );

        // Unicode.

        $foundStrings;
        $numFound = CRegex::findAllFrom("¡Hello señor!", "/\\S+/u", 7, $foundStrings);
        $this->assertTrue(
            $numFound == 1 && CArray::length($foundStrings) == 1 &&
            CUString::equals($foundStrings[0], "señor!") );

        $foundStrings;
        $numFound = CRegex::findAllFrom("¡Hello señor!", "/H\\w+/u", 7, $foundStrings);
        $this->assertTrue( $numFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindAllGroups ()
    {
        // ASCII.

        $foundGroupArrays;
        $numFound = CRegex::findAllGroups("Hello there!", "/(\\w\\w)(\\w\\w)/", $foundGroupArrays);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundGroupArrays) == 2 &&
            CString::equals($foundGroupArrays[0][0], "He") && CString::equals($foundGroupArrays[0][1], "ll") &&
            CString::equals($foundGroupArrays[1][0], "th") && CString::equals($foundGroupArrays[1][1], "er") );

        $foundGroupArrays;
        $foundStrings;
        $numFound = CRegex::findAllGroups("Hello there!", "/(\\w\\w)(\\w\\w)/", $foundGroupArrays, $foundStrings);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundGroupArrays) == 2 &&
            CString::equals($foundGroupArrays[0][0], "He") && CString::equals($foundGroupArrays[0][1], "ll") &&
            CString::equals($foundGroupArrays[1][0], "th") && CString::equals($foundGroupArrays[1][1], "er") &&
            CArray::length($foundStrings) == 2 &&
            CString::equals($foundStrings[0], "Hell") && CString::equals($foundStrings[1], "ther") );

        $foundGroupArrays;
        $numFound = CRegex::findAllGroups("Hello there!", "/(\\w\\w\\w)(\\w\\w\\w)/", $foundGroupArrays);
        $this->assertTrue( $numFound == 0 );

        // Unicode.

        $foundGroupArrays;
        $numFound = CRegex::findAllGroups("¡Hello señor!", "/(\\w\\w)(\\w\\w)/u", $foundGroupArrays);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundGroupArrays) == 2 &&
            CUString::equals($foundGroupArrays[0][0], "He") && CUString::equals($foundGroupArrays[0][1], "ll") &&
            CUString::equals($foundGroupArrays[1][0], "se") && CUString::equals($foundGroupArrays[1][1], "ño") );

        $foundGroupArrays;
        $foundStrings;
        $numFound = CRegex::findAllGroups("¡Hello señor!", "/(\\w\\w)(\\w\\w)/u", $foundGroupArrays, $foundStrings);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundGroupArrays) == 2 &&
            CUString::equals($foundGroupArrays[0][0], "He") && CUString::equals($foundGroupArrays[0][1], "ll") &&
            CUString::equals($foundGroupArrays[1][0], "se") && CUString::equals($foundGroupArrays[1][1], "ño") &&
            CArray::length($foundStrings) == 2 &&
            CUString::equals($foundStrings[0], "Hell") && CUString::equals($foundStrings[1], "seño") );

        $foundGroupArrays;
        $numFound = CRegex::findAllGroups("¡Hello señor!", "/(\\w\\w\\w)(\\w\\w\\w)/u", $foundGroupArrays);
        $this->assertTrue( $numFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindAllGroupsFrom ()
    {
        // ASCII.

        $foundGroupArrays;
        $numFound = CRegex::findAllGroupsFrom("Hello there!", "/(\\w\\w)(\\w\\w)/", 1, $foundGroupArrays);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundGroupArrays) == 2 &&
            CString::equals($foundGroupArrays[0][0], "el") && CString::equals($foundGroupArrays[0][1], "lo") &&
            CString::equals($foundGroupArrays[1][0], "th") && CString::equals($foundGroupArrays[1][1], "er") );

        $foundGroupArrays;
        $foundStrings;
        $numFound = CRegex::findAllGroupsFrom("Hello there!", "/(\\w\\w)(\\w\\w)/", 1, $foundGroupArrays,
            $foundStrings);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundGroupArrays) == 2 &&
            CString::equals($foundGroupArrays[0][0], "el") && CString::equals($foundGroupArrays[0][1], "lo") &&
            CString::equals($foundGroupArrays[1][0], "th") && CString::equals($foundGroupArrays[1][1], "er") &&
            CArray::length($foundStrings) == 2 &&
            CString::equals($foundStrings[0], "ello") && CString::equals($foundStrings[1], "ther") );

        $foundGroupArrays;
        $numFound = CRegex::findAllGroupsFrom("Hello there!", "/(\\w\\w\\w)(\\w\\w\\w)/", 1, $foundGroupArrays);
        $this->assertTrue( $numFound == 0 );

        // Unicode.

        $foundGroupArrays;
        $numFound = CRegex::findAllGroupsFrom("¡Hello señor!", "/(\\w\\w)(\\w\\w)/u", 3, $foundGroupArrays);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundGroupArrays) == 2 &&
            CUString::equals($foundGroupArrays[0][0], "el") && CUString::equals($foundGroupArrays[0][1], "lo") &&
            CUString::equals($foundGroupArrays[1][0], "se") && CUString::equals($foundGroupArrays[1][1], "ño") );

        $foundGroupArrays;
        $foundStrings;
        $numFound = CRegex::findAllGroupsFrom("¡Hello señor!", "/(\\w\\w)(\\w\\w)/u", 3, $foundGroupArrays,
            $foundStrings);
        $this->assertTrue(
            $numFound == 2 && CArray::length($foundGroupArrays) == 2 &&
            CUString::equals($foundGroupArrays[0][0], "el") && CUString::equals($foundGroupArrays[0][1], "lo") &&
            CUString::equals($foundGroupArrays[1][0], "se") && CUString::equals($foundGroupArrays[1][1], "ño") &&
            CArray::length($foundStrings) == 2 &&
            CUString::equals($foundStrings[0], "ello") && CUString::equals($foundStrings[1], "seño") );

        $foundGroupArrays;
        $numFound = CRegex::findAllGroupsFrom("¡Hello señor!", "/(\\w\\w\\w)(\\w\\w\\w)/u", 3, $foundGroupArrays);
        $this->assertTrue( $numFound == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplace ()
    {
        // ASCII.

        $res = CRegex::replace("Hello there!", "/[eo]/", "a");
        $this->assertTrue(CString::equals($res, "Halla thara!"));

        $quantity;
        $res = CRegex::replace("Hello there!", "/[eo]/", "a", $quantity);
        $this->assertTrue( CString::equals($res, "Halla thara!") && $quantity == 4 );

        // Unicode.

        $res = CRegex::replace("¡Hello señor!", "/[eoñ]/u", "a");
        $this->assertTrue(CUString::equals($res, "¡Halla saaar!"));

        $quantity;
        $res = CRegex::replace("¡Hello señor!", "/[eoñ]/u", "a", $quantity);
        $this->assertTrue( CUString::equals($res, "¡Halla saaar!") && $quantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReplaceWithCallback ()
    {
        // ASCII.

        $res = CRegex::replaceWithCallback("Hello there!", "/[eo]/", function ($matches)
            {
                return ( CString::equals($matches[0], "e") ) ? "3" : $matches[0];
            });
        $this->assertTrue(CString::equals($res, "H3llo th3r3!"));

        $quantity;
        $res = CRegex::replaceWithCallback("Hello there!", "/[eo]/", function ($matches)
            {
                return ( CString::equals($matches[0], "e") ) ? "3" : $matches[0];
            }, $quantity);
        $this->assertTrue( CString::equals($res, "H3llo th3r3!") && $quantity == 4 );

        // Unicode.

        $res = CRegex::replaceWithCallback("¡Hello señor!", "/[eoñ]/u", function ($matches)
            {
                return ( CUString::equals($matches[0], "ñ") ) ? "n" : $matches[0];
            });
        $this->assertTrue(CUString::equals($res, "¡Hello senor!"));

        $quantity;
        $res = CRegex::replaceWithCallback("¡Hello señor!", "/[eoñ]/u", function ($matches)
            {
                return ( CUString::equals($matches[0], "ñ") ) ? "n" : $matches[0];
            }, $quantity);
        $this->assertTrue( CUString::equals($res, "¡Hello senor!") && $quantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        // ASCII.

        $res = CRegex::remove("Hello there!", "/[eo]/");
        $this->assertTrue(CString::equals($res, "Hll thr!"));

        $quantity;
        $res = CRegex::remove("Hello there!", "/[eo]/", $quantity);
        $this->assertTrue( CString::equals($res, "Hll thr!") && $quantity == 4 );

        // Unicode.

        $res = CRegex::remove("¡Hello señor!", "/[eoñ]/u");
        $this->assertTrue(CUString::equals($res, "¡Hll sr!"));

        $quantity;
        $res = CRegex::remove("¡Hello señor!", "/[eoñ]/u", $quantity);
        $this->assertTrue( CUString::equals($res, "¡Hll sr!") && $quantity == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplit ()
    {
        // ASCII.

        $res = CRegex::split("He,llo;th,ere!", "/[,;]/");
        $this->assertTrue( CArray::length($res) == 4 &&
            CString::equals($res[0], "He") && CString::equals($res[1], "llo") &&
            CString::equals($res[2], "th") && CString::equals($res[3], "ere!") );

        $res = CRegex::split("He,llo;th.ere!", CArray::fromElements("/[,;]/", "/\\./"));
        $this->assertTrue( CArray::length($res) == 4 &&
            CString::equals($res[0], "He") && CString::equals($res[1], "llo") &&
            CString::equals($res[2], "th") && CString::equals($res[3], "ere!") );

        // Special cases.

        $res = CRegex::split("", "/[,;]/");
        $this->assertTrue( CArray::length($res) == 1 && CString::equals($res[0], "") );

        $res = CRegex::split("Hey", "//");
        $this->assertTrue( CArray::length($res) == 3 &&
            CString::equals($res[0], "H") && CString::equals($res[1], "e") && CString::equals($res[2], "y") );

        $res = CRegex::split("", "//");
        $this->assertTrue( CArray::length($res) == 1 && CString::equals($res[0], "") );

        // Unicode.

        $res = CRegex::split("¡He,llo·se,ñor!", "/[,·]/u");
        $this->assertTrue( CArray::length($res) == 4 &&
            CUString::equals($res[0], "¡He") && CUString::equals($res[1], "llo") &&
            CUString::equals($res[2], "se") && CUString::equals($res[3], "ñor!") );

        $res = CRegex::split("¡He,llo·se.ñor!", CArray::fromElements("/[,·]/u", "/\\./u"));
        $this->assertTrue( CArray::length($res) == 4 &&
            CUString::equals($res[0], "¡He") && CUString::equals($res[1], "llo") &&
            CUString::equals($res[2], "se") && CUString::equals($res[3], "ñor!") );

        // Special cases.

        $res = CRegex::split("", "/[,·]/u");
        $this->assertTrue( CArray::length($res) == 1 && CUString::equals($res[0], "") );

        $res = CRegex::split("Héy", "//u");
        $this->assertTrue( CArray::length($res) == 3 &&
            CUString::equals($res[0], "H") && CUString::equals($res[1], "é") && CUString::equals($res[2], "y") );

        $res = CRegex::split("", "//u");
        $this->assertTrue( CArray::length($res) == 1 && CUString::equals($res[0], "") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEnterTd ()
    {
        // ASCII.

        $res = CRegex::enterTd(".(]/", "/");
        $this->assertTrue(CString::equals($res, "\\.\\(\\]\\/"));

        $res = CRegex::enterTd(".(]#", "#");
        $this->assertTrue(CString::equals($res, "\\.\\(\\]\\#"));

        // Unicode.

        $res = CRegex::enterTd(".(señor]/", "/");
        $this->assertTrue(CString::equals($res, "\\.\\(señor\\]\\/"));

        $res = CRegex::enterTd(".(señor]#", "#");
        $this->assertTrue(CString::equals($res, "\\.\\(señor\\]\\#"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
