<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CMapTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $mMap = CMap::make();
        $this->assertTrue( count($mMap) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMakeCopy ()
    {
        $mMap0 = CMap::make();
        $mMap0["one"] = "a";
        $mMap0["two"] = "b";
        $mMap0["three"] = "c";
        $mMap1 = CMap::makeCopy($mMap0);
        $this->assertTrue(
            $mMap0["one"] === $mMap1["one"] &&
            $mMap0["two"] === $mMap1["two"] &&
            $mMap0["three"] === $mMap1["three"] );
        $mMap0["one"] = "d";
        $this->assertTrue( $mMap1["one"] === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $mMap = CMap::make();
        $mMap["one"] = "a";
        $mMap["two"] = "b";
        $mMap["three"] = "c";
        $this->assertTrue( CMap::length($mMap) == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $mMap = CMap::make();
        $this->assertTrue(CMap::isEmpty($mMap));

        $mMap = CMap::make();
        $mMap["one"] = "a";
        $this->assertFalse(CMap::isEmpty($mMap));

        $mMap = CMap::make();
        $mMap["one"] = null;
        $this->assertFalse(CMap::isEmpty($mMap));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        // Using the default comparator.

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue(CMap::equals($mMap0, $mMap1));

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "a", "two" => "b", "four" => "c"];
        $this->assertFalse(CMap::equals($mMap0, $mMap1));

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "a", "two" => "b", "three" => "d"];
        $this->assertFalse(CMap::equals($mMap0, $mMap1));

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "a", "two" => "b", "three" => "C"];
        $this->assertFalse(CMap::equals($mMap0, $mMap1));

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "a", "two" => "b", "three" => "c", "four" => "d"];
        $this->assertFalse(CMap::equals($mMap0, $mMap1));

        $mMap0 = ["one" => 1, "two" => 2, "three" => 3];
        $mMap1 = ["one" => 1, "two" => 2, "three" => 3];
        $this->assertTrue(CMap::equals($mMap0, $mMap1));

        $mMap0 = [1, 2, 3];
        $mMap1 = [1, 2, 3];
        $this->assertTrue(CMap::equals($mMap0, $mMap1));

        $mMap0 = [1.2, 3.4, 5.6];
        $mMap1 = [1.2, 3.4, 5.6];
        $this->assertTrue(CMap::equals($mMap0, $mMap1));

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => u("a"), "two" => u("b"), "three" => u("c")];
        $this->assertTrue(CMap::equals($mMap0, $mMap1));

        $mMap0 = CMap::make();
        $mMap1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertFalse(CMap::equals($mMap0, $mMap1));

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = CMap::make();
        $this->assertFalse(CMap::equals($mMap0, $mMap1));

        // Using a custom comparator.
        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "A", "two" => "B", "three" => "C"];
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $this->assertTrue(CMap::equals($mMap0, $mMap1, $fnComparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        // Using the default comparator.

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) == 0 );

        $mMap0 = ["a" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) < 0 );

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "d", "two" => "e", "three" => "f"];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) < 0 );

        $mMap0 = ["one" => "d", "two" => "e", "three" => "f"];
        $mMap1 = ["one" => "a", "two" => "e", "three" => "f"];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) > 0 );

        $mMap0 = ["one" => "a", "two" => "b"];
        $mMap1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) < 0 );

        $mMap0 = ["one" => 1, "two" => 2, "three" => 3];
        $mMap1 = ["one" => 1, "two" => 2, "three" => 3];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) == 0 );

        $mMap0 = [1, 2, 3];
        $mMap1 = [1, 2, 3];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) == 0 );

        $mMap0 = [1, 2, 3];
        $mMap1 = [4, 5, 6];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) < 0 );

        $mMap0 = [4, 5, 6];
        $mMap1 = [3, 5, 6];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) > 0 );

        $mMap0 = [1.2, 3.4, 5.6];
        $mMap1 = [1.2, 3.4, 5.6];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) == 0 );

        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => u("a"), "two" => u("b"), "three" => u("c")];
        $this->assertTrue( CMap::compare($mMap0, $mMap1) == 0 );

        // Using a custom comparator.
        $mMap0 = ["one" => "a", "two" => "b", "three" => "c"];
        $mMap1 = ["one" => "A", "two" => "B", "three" => "C"];
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) ) ? 0 : -1;
            };
        $this->assertTrue( CMap::compare($mMap0, $mMap1, $fnComparator) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasKey ()
    {
        $mMap = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue(CMap::hasKey($mMap, "two"));
        $this->assertFalse(CMap::hasKey($mMap, "four"));

        $mMap = ["one" => "a", "two" => null, "three" => "c"];
        $this->assertTrue(CMap::hasKey($mMap, "two"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPath ()
    {
        $mMap = ["one" => "a", "two" => ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]];
        $this->assertTrue(CMap::hasPath($mMap, "one"));
        $this->assertTrue(CMap::hasPath($mMap, "two"));
        $this->assertTrue(CMap::hasPath($mMap, "two.one"));
        $this->assertTrue(CMap::hasPath($mMap, "two.two.three"));
        $this->assertFalse(CMap::hasPath($mMap, "three"));
        $this->assertFalse(CMap::hasPath($mMap, "one.one"));
        $this->assertFalse(CMap::hasPath($mMap, "two.three"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testValueByPath ()
    {
        $mMap = ["one" => "a", "two" => ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]];
        $this->assertTrue( CMap::valueByPath($mMap, "one") === "a" );
        $this->assertTrue(
            CMap::equals(CMap::valueByPath($mMap, "two"),
            ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]));
        $this->assertTrue( CMap::valueByPath($mMap, "two.one") === "a" );
        $this->assertTrue(
            CMap::equals(CMap::valueByPath($mMap, "two.two"),
            ["one" => "a", "two" => "b", "three" => "c"]));
        $this->assertTrue( CMap::valueByPath($mMap, "two.two.one") === "a" );
        $this->assertTrue( CMap::valueByPath($mMap, "two.two.two") === "b" );
        $this->assertTrue( CMap::valueByPath($mMap, "two.two.three") === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValueByPath ()
    {
        $mMap = ["one" => "a", "two" => ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]];

        CMap::setValueByPath($mMap, "one", "d");
        CMap::setValueByPath($mMap, "two.one", "e");
        CMap::setValueByPath($mMap, "two.two.one", "f");
        CMap::setValueByPath($mMap, "two.two.two", "g");
        CMap::setValueByPath($mMap, "two.two.three", "h");

        $this->assertTrue( CMap::valueByPath($mMap, "one") === "d" );
        $this->assertTrue(
            CMap::equals(CMap::valueByPath($mMap, "two"),
            ["one" => "e", "two" => ["one" => "f", "two" => "g", "three" => "h"]]));
        $this->assertTrue( CMap::valueByPath($mMap, "two.one") === "e" );
        $this->assertTrue(
            CMap::equals(CMap::valueByPath($mMap, "two.two"),
            ["one" => "f", "two" => "g", "three" => "h"]));
        $this->assertTrue( CMap::valueByPath($mMap, "two.two.one") === "f" );
        $this->assertTrue( CMap::valueByPath($mMap, "two.two.two") === "g" );
        $this->assertTrue( CMap::valueByPath($mMap, "two.two.three") === "h" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $mMap = ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"];

        // Using the default comparator.

        $bFound = CMap::find($mMap, "c");
        $this->assertTrue($bFound);

        $xFoundUnderKey;
        $bFound = CMap::find($mMap, "d", CComparator::EQUALITY, $xFoundUnderKey);
        $this->assertTrue($bFound);
        $this->assertTrue( $xFoundUnderKey === "four" );

        $bFound = CMap::find($mMap, "C");
        $this->assertFalse($bFound);

        $bFound = CMap::find($mMap, "f");
        $this->assertFalse($bFound);

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $xFoundUnderKey;
        $bFound = CMap::find($mMap, "C", $fnComparator, $xFoundUnderKey);
        $this->assertTrue($bFound);
        $this->assertTrue( $xFoundUnderKey === "three" );

        // Special case.
        $mMap = CMap::make();
        $bFound = CMap::find($mMap, "a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindScalar ()
    {
        $mMap = ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"];

        $bFound = CMap::findScalar($mMap, "c");
        $this->assertTrue($bFound);

        $xFoundUnderKey;
        $bFound = CMap::findScalar($mMap, "d", $xFoundUnderKey);
        $this->assertTrue($bFound);
        $this->assertTrue( $xFoundUnderKey === "four" );

        $bFound = CMap::findScalar($mMap, "C");
        $this->assertFalse($bFound);

        $bFound = CMap::findScalar($mMap, "f");
        $this->assertFalse($bFound);

        // Special case.
        $mMap = CMap::make();
        $bFound = CMap::findScalar($mMap, "a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountValue ()
    {
        $mMap = ["one" => "a", "two" => "c", "three" => "b", "four" => "c", "five" => "d", "six" => "e",
            "seven" => "c", "eight" => "c", "nine" => "f", "ten" => "g", "eleven" => "h", "twelve" => "c"];

        // Using the default comparator.
        $this->assertTrue( CMap::countValue($mMap, "c") == 5 );

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $this->assertTrue( CMap::countValue($mMap, "C", $fnComparator) == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $mMap = ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"];
        CMap::remove($mMap, "three");
        $this->assertTrue(CMap::equals($mMap, ["one" => "a", "two" => "b", "four" => "d", "five" => "e"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $mMap = ["one" => 1, "two" => 2, "three" => 3, "four" => 4, "five" => 5, "six" => 6, "seven" => 7,
            "eight" => 8, "nine" => 9, "ten" => 10];
        $mMap = CMap::filter($mMap, function ($iValue)
            {
                return CMathi::isEven($iValue);
            });
        $this->assertTrue(CMap::equals($mMap, ["two" => 2, "four" => 4, "six" => 6, "eight" => 8, "ten" => 10]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKeys ()
    {
        $mMap = ["one" => "a", "two" => "b", "three" => "c"];
        $aKeys = CMap::keys($mMap);
        $this->assertTrue(CArray::equals($aKeys, CArray::fromElements("one", "two", "three")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testValues ()
    {
        $mMap = ["one" => "a", "two" => "b", "three" => "c"];
        $aValues = CMap::values($mMap);
        $this->assertTrue(CArray::equals($aValues, CArray::fromElements("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMerge ()
    {
        $mMap0 = ["one" => "a", "two" => ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]];
        $mMap1 = ["one" => "b", "two" => ["two" => ["four" => "d"]], "three" => "c"];
        $mMap2 = ["two" => ["two" => ["five" => "e"]]];
        $mMap = CMap::merge($mMap0, $mMap1, $mMap2);
        $this->assertTrue(CMap::equals($mMap, [
            "one" => "b",
            "two" => ["one" => "a", "two" =>
                ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"]],
            "three" => "c"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAreKeysSequential ()
    {
        $mMap = [0 => "a", 1 => "b", 2 => "c", 3 => "d", 4 => "e"];
        $this->assertTrue(CMap::areKeysSequential($mMap));

        $mMap = [0 => "a", 1 => "b", 2 => "c", 4 => "d", 5 => "e"];
        $this->assertFalse(CMap::areKeysSequential($mMap));

        $mMap = [0 => "a", 1 => "b", 2 => "c", "three" => "d", 4 => "e"];
        $this->assertFalse(CMap::areKeysSequential($mMap));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsertValue ()
    {
        $mMap = ["one" => "a", "two" => "b", "three" => "c"];
        CMap::insertValue($mMap, "d");
        $this->assertTrue(CMap::equals($mMap, ["one" => "a", "two" => "b", "three" => "c", 0 => "d"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
