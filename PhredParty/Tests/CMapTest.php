<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
        $map = CMap::make();
        $this->assertTrue( count($map) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMakeCopy ()
    {
        $map0 = CMap::make();
        $map0["one"] = "a";
        $map0["two"] = "b";
        $map0["three"] = "c";
        $map1 = CMap::makeCopy($map0);
        $this->assertTrue(
            $map0["one"] === $map1["one"] &&
            $map0["two"] === $map1["two"] &&
            $map0["three"] === $map1["three"] );
        $map0["one"] = "d";
        $this->assertTrue( $map1["one"] === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $map = CMap::make();
        $map["one"] = "a";
        $map["two"] = "b";
        $map["three"] = "c";
        $this->assertTrue( CMap::length($map) == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $map = CMap::make();
        $this->assertTrue(CMap::isEmpty($map));

        $map = CMap::make();
        $map["one"] = "a";
        $this->assertFalse(CMap::isEmpty($map));

        $map = CMap::make();
        $map["one"] = null;
        $this->assertFalse(CMap::isEmpty($map));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        // Using the default comparator.

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue(CMap::equals($map0, $map1));

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "a", "two" => "b", "four" => "c"];
        $this->assertFalse(CMap::equals($map0, $map1));

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "a", "two" => "b", "three" => "d"];
        $this->assertFalse(CMap::equals($map0, $map1));

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "a", "two" => "b", "three" => "C"];
        $this->assertFalse(CMap::equals($map0, $map1));

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "a", "two" => "b", "three" => "c", "four" => "d"];
        $this->assertFalse(CMap::equals($map0, $map1));

        $map0 = ["one" => 1, "two" => 2, "three" => 3];
        $map1 = ["one" => 1, "two" => 2, "three" => 3];
        $this->assertTrue(CMap::equals($map0, $map1));

        $map0 = [1, 2, 3];
        $map1 = [1, 2, 3];
        $this->assertTrue(CMap::equals($map0, $map1));

        $map0 = [1.2, 3.4, 5.6];
        $map1 = [1.2, 3.4, 5.6];
        $this->assertTrue(CMap::equals($map0, $map1));

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => u("a"), "two" => u("b"), "three" => u("c")];
        $this->assertTrue(CMap::equals($map0, $map1));

        $map0 = CMap::make();
        $map1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertFalse(CMap::equals($map0, $map1));

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = CMap::make();
        $this->assertFalse(CMap::equals($map0, $map1));

        // Using a custom comparator.
        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "A", "two" => "B", "three" => "C"];
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $this->assertTrue(CMap::equals($map0, $map1, $comparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        // Using the default comparator.

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue( CMap::compare($map0, $map1) == 0 );

        $map0 = ["a" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue( CMap::compare($map0, $map1) < 0 );

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "d", "two" => "e", "three" => "f"];
        $this->assertTrue( CMap::compare($map0, $map1) < 0 );

        $map0 = ["one" => "d", "two" => "e", "three" => "f"];
        $map1 = ["one" => "a", "two" => "e", "three" => "f"];
        $this->assertTrue( CMap::compare($map0, $map1) > 0 );

        $map0 = ["one" => "a", "two" => "b"];
        $map1 = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue( CMap::compare($map0, $map1) < 0 );

        $map0 = ["one" => 1, "two" => 2, "three" => 3];
        $map1 = ["one" => 1, "two" => 2, "three" => 3];
        $this->assertTrue( CMap::compare($map0, $map1) == 0 );

        $map0 = [1, 2, 3];
        $map1 = [1, 2, 3];
        $this->assertTrue( CMap::compare($map0, $map1) == 0 );

        $map0 = [1, 2, 3];
        $map1 = [4, 5, 6];
        $this->assertTrue( CMap::compare($map0, $map1) < 0 );

        $map0 = [4, 5, 6];
        $map1 = [3, 5, 6];
        $this->assertTrue( CMap::compare($map0, $map1) > 0 );

        $map0 = [1.2, 3.4, 5.6];
        $map1 = [1.2, 3.4, 5.6];
        $this->assertTrue( CMap::compare($map0, $map1) == 0 );

        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => u("a"), "two" => u("b"), "three" => u("c")];
        $this->assertTrue( CMap::compare($map0, $map1) == 0 );

        // Using a custom comparator.
        $map0 = ["one" => "a", "two" => "b", "three" => "c"];
        $map1 = ["one" => "A", "two" => "B", "three" => "C"];
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) ) ? 0 : -1;
            };
        $this->assertTrue( CMap::compare($map0, $map1, $comparator) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasKey ()
    {
        $map = ["one" => "a", "two" => "b", "three" => "c"];
        $this->assertTrue(CMap::hasKey($map, "two"));
        $this->assertFalse(CMap::hasKey($map, "four"));

        $map = ["one" => "a", "two" => null, "three" => "c"];
        $this->assertTrue(CMap::hasKey($map, "two"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPath ()
    {
        $map = ["one" => "a", "two" => ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]];
        $this->assertTrue(CMap::hasPath($map, "one"));
        $this->assertTrue(CMap::hasPath($map, "two"));
        $this->assertTrue(CMap::hasPath($map, "two.one"));
        $this->assertTrue(CMap::hasPath($map, "two.two.three"));
        $this->assertFalse(CMap::hasPath($map, "three"));
        $this->assertFalse(CMap::hasPath($map, "one.one"));
        $this->assertFalse(CMap::hasPath($map, "two.three"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testValueByPath ()
    {
        $map = ["one" => "a", "two" => ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]];
        $this->assertTrue( CMap::valueByPath($map, "one") === "a" );
        $this->assertTrue(
            CMap::equals(CMap::valueByPath($map, "two"),
            ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]));
        $this->assertTrue( CMap::valueByPath($map, "two.one") === "a" );
        $this->assertTrue(
            CMap::equals(CMap::valueByPath($map, "two.two"),
            ["one" => "a", "two" => "b", "three" => "c"]));
        $this->assertTrue( CMap::valueByPath($map, "two.two.one") === "a" );
        $this->assertTrue( CMap::valueByPath($map, "two.two.two") === "b" );
        $this->assertTrue( CMap::valueByPath($map, "two.two.three") === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValueByPath ()
    {
        $map = ["one" => "a", "two" => ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]];

        CMap::setValueByPath($map, "one", "d");
        CMap::setValueByPath($map, "two.one", "e");
        CMap::setValueByPath($map, "two.two.one", "f");
        CMap::setValueByPath($map, "two.two.two", "g");
        CMap::setValueByPath($map, "two.two.three", "h");

        $this->assertTrue( CMap::valueByPath($map, "one") === "d" );
        $this->assertTrue(
            CMap::equals(CMap::valueByPath($map, "two"),
            ["one" => "e", "two" => ["one" => "f", "two" => "g", "three" => "h"]]));
        $this->assertTrue( CMap::valueByPath($map, "two.one") === "e" );
        $this->assertTrue(
            CMap::equals(CMap::valueByPath($map, "two.two"),
            ["one" => "f", "two" => "g", "three" => "h"]));
        $this->assertTrue( CMap::valueByPath($map, "two.two.one") === "f" );
        $this->assertTrue( CMap::valueByPath($map, "two.two.two") === "g" );
        $this->assertTrue( CMap::valueByPath($map, "two.two.three") === "h" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $map = ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"];

        // Using the default comparator.

        $found = CMap::find($map, "c");
        $this->assertTrue($found);

        $foundUnderKey;
        $found = CMap::find($map, "d", CComparator::EQUALITY, $foundUnderKey);
        $this->assertTrue($found);
        $this->assertTrue( $foundUnderKey === "four" );

        $found = CMap::find($map, "C");
        $this->assertFalse($found);

        $found = CMap::find($map, "f");
        $this->assertFalse($found);

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $foundUnderKey;
        $found = CMap::find($map, "C", $comparator, $foundUnderKey);
        $this->assertTrue($found);
        $this->assertTrue( $foundUnderKey === "three" );

        // Special case.
        $map = CMap::make();
        $found = CMap::find($map, "a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindScalar ()
    {
        $map = ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"];

        $found = CMap::findScalar($map, "c");
        $this->assertTrue($found);

        $foundUnderKey;
        $found = CMap::findScalar($map, "d", $foundUnderKey);
        $this->assertTrue($found);
        $this->assertTrue( $foundUnderKey === "four" );

        $found = CMap::findScalar($map, "C");
        $this->assertFalse($found);

        $found = CMap::findScalar($map, "f");
        $this->assertFalse($found);

        // Special case.
        $map = CMap::make();
        $found = CMap::findScalar($map, "a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountValue ()
    {
        $map = ["one" => "a", "two" => "c", "three" => "b", "four" => "c", "five" => "d", "six" => "e",
            "seven" => "c", "eight" => "c", "nine" => "f", "ten" => "g", "eleven" => "h", "twelve" => "c"];

        // Using the default comparator.
        $this->assertTrue( CMap::countValue($map, "c") == 5 );

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $this->assertTrue( CMap::countValue($map, "C", $comparator) == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $map = ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"];
        CMap::remove($map, "three");
        $this->assertTrue(CMap::equals($map, ["one" => "a", "two" => "b", "four" => "d", "five" => "e"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $map = ["one" => 1, "two" => 2, "three" => 3, "four" => 4, "five" => 5, "six" => 6, "seven" => 7,
            "eight" => 8, "nine" => 9, "ten" => 10];
        $map = CMap::filter($map, function ($value)
            {
                return CMathi::isEven($value);
            });
        $this->assertTrue(CMap::equals($map, ["two" => 2, "four" => 4, "six" => 6, "eight" => 8, "ten" => 10]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKeys ()
    {
        $map = ["one" => "a", "two" => "b", "three" => "c"];
        $keys = CMap::keys($map);
        $this->assertTrue(CArray::equals($keys, CArray::fromElements("one", "two", "three")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testValues ()
    {
        $map = ["one" => "a", "two" => "b", "three" => "c"];
        $values = CMap::values($map);
        $this->assertTrue(CArray::equals($values, CArray::fromElements("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMerge ()
    {
        $map0 = ["one" => "a", "two" => ["one" => "a", "two" => ["one" => "a", "two" => "b", "three" => "c"]]];
        $map1 = ["one" => "b", "two" => ["two" => ["four" => "d"]], "three" => "c"];
        $map2 = ["two" => ["two" => ["five" => "e"]]];
        $map = CMap::merge($map0, $map1, $map2);
        $this->assertTrue(CMap::equals($map, [
            "one" => "b",
            "two" => ["one" => "a", "two" =>
                ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"]],
            "three" => "c"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAreKeysSequential ()
    {
        $map = [0 => "a", 1 => "b", 2 => "c", 3 => "d", 4 => "e"];
        $this->assertTrue(CMap::areKeysSequential($map));

        $map = [0 => "a", 1 => "b", 2 => "c", 4 => "d", 5 => "e"];
        $this->assertFalse(CMap::areKeysSequential($map));

        $map = [0 => "a", 1 => "b", 2 => "c", "three" => "d", 4 => "e"];
        $this->assertFalse(CMap::areKeysSequential($map));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsertValue ()
    {
        $map = ["one" => "a", "two" => "b", "three" => "c"];
        CMap::insertValue($map, "d");
        $this->assertTrue(CMap::equals($map, ["one" => "a", "two" => "b", "three" => "c", 0 => "d"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
