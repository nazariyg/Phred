<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CMapObjectTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $mMap = m();
        $this->assertTrue( $mMap->length() == 0 );

        $mMap = new CMapObject();
        $mMap["one"] = "a";
        $mMap["two"] = "b";
        $mMap["three"] = "c";
        $this->assertTrue( $mMap->length() == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $mMap = new CMapObject();
        $this->assertTrue($mMap->isEmpty());

        $mMap = new CMapObject();
        $mMap["one"] = "a";
        $this->assertFalse($mMap->isEmpty());

        $mMap = new CMapObject();
        $mMap["one"] = null;
        $this->assertFalse($mMap->isEmpty());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        // Using the default comparator.

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue($mMap0->equals($mMap1));

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "a", "two" => "b", "four" => "c"]);
        $this->assertFalse($mMap0->equals($mMap1));

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "a", "two" => "b", "three" => "d"]);
        $this->assertFalse($mMap0->equals($mMap1));

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "a", "two" => "b", "three" => "C"]);
        $this->assertFalse($mMap0->equals($mMap1));

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "a", "two" => "b", "three" => "c", "four" => "d"]);
        $this->assertFalse($mMap0->equals($mMap1));

        $mMap0 = m(["one" => 1, "two" => 2, "three" => 3]);
        $mMap1 = m(["one" => 1, "two" => 2, "three" => 3]);
        $this->assertTrue($mMap0->equals($mMap1));

        $mMap0 = m([1, 2, 3]);
        $mMap1 = m([1, 2, 3]);
        $this->assertTrue($mMap0->equals($mMap1));

        $mMap0 = m([1.2, 3.4, 5.6]);
        $mMap1 = m([1.2, 3.4, 5.6]);
        $this->assertTrue($mMap0->equals($mMap1));

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => u("a"), "two" => u("b"), "three" => u("c")]);
        $this->assertTrue($mMap0->equals($mMap1));

        $mMap0 = new CMapObject();
        $mMap1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertFalse($mMap0->equals($mMap1));

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = new CMapObject();
        $this->assertFalse($mMap0->equals($mMap1));

        // Using a custom comparator.
        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "A", "two" => "B", "three" => "C"]);
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $this->assertTrue($mMap0->equals($mMap1, $fnComparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        // Using the default comparator.

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $mMap0->compare($mMap1) == 0 );

        $mMap0 = m(["a" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $mMap0->compare($mMap1) < 0 );

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "d", "two" => "e", "three" => "f"]);
        $this->assertTrue( $mMap0->compare($mMap1) < 0 );

        $mMap0 = m(["one" => "d", "two" => "e", "three" => "f"]);
        $mMap1 = m(["one" => "a", "two" => "e", "three" => "f"]);
        $this->assertTrue( $mMap0->compare($mMap1) > 0 );

        $mMap0 = m(["one" => "a", "two" => "b"]);
        $mMap1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $mMap0->compare($mMap1) < 0 );

        $mMap0 = m(["one" => 1, "two" => 2, "three" => 3]);
        $mMap1 = m(["one" => 1, "two" => 2, "three" => 3]);
        $this->assertTrue( $mMap0->compare($mMap1) == 0 );

        $mMap0 = m([1, 2, 3]);
        $mMap1 = m([1, 2, 3]);
        $this->assertTrue( $mMap0->compare($mMap1) == 0 );

        $mMap0 = m([1, 2, 3]);
        $mMap1 = m([4, 5, 6]);
        $this->assertTrue( $mMap0->compare($mMap1) < 0 );

        $mMap0 = m([4, 5, 6]);
        $mMap1 = m([3, 5, 6]);
        $this->assertTrue( $mMap0->compare($mMap1) > 0 );

        $mMap0 = m([1.2, 3.4, 5.6]);
        $mMap1 = m([1.2, 3.4, 5.6]);
        $this->assertTrue( $mMap0->compare($mMap1) == 0 );

        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => u("a"), "two" => u("b"), "three" => u("c")]);
        $this->assertTrue( $mMap0->compare($mMap1) == 0 );

        // Using a custom comparator.
        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = m(["one" => "A", "two" => "B", "three" => "C"]);
        $fnComparator = function ($sString0, $sString1)
            {
                return ( $sString0->toLowerCase()->equals($sString1->toLowerCase()) ) ? 0 : -1;
            };
        $this->assertTrue( $mMap0->compare($mMap1, $fnComparator) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasKey ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue($mMap->hasKey("two"));
        $this->assertFalse($mMap->hasKey("four"));

        $mMap = m(["one" => "a", "two" => null, "three" => "c"]);
        $this->assertTrue($mMap->hasKey("two"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPath ()
    {
        $mMap = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $this->assertTrue($mMap->hasPath("one"));
        $this->assertTrue($mMap->hasPath("two"));
        $this->assertTrue($mMap->hasPath("two.one"));
        $this->assertTrue($mMap->hasPath("two.two.three"));
        $this->assertFalse($mMap->hasPath("three"));
        $this->assertFalse($mMap->hasPath("one.one"));
        $this->assertFalse($mMap->hasPath("two.three"));

        $mMap = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $this->assertTrue($mMap->hasPath(u("one")));
        $this->assertTrue($mMap->hasPath(u("two")));
        $this->assertTrue($mMap->hasPath(u("two.one")));
        $this->assertTrue($mMap->hasPath(u("two.two.three")));
        $this->assertFalse($mMap->hasPath(u("three")));
        $this->assertFalse($mMap->hasPath(u("one.one")));
        $this->assertFalse($mMap->hasPath(u("two.three")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testValueByPath ()
    {
        $mMap = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $this->assertTrue($mMap->valueByPath("one")->equals("a"));
        $this->assertTrue(
            $mMap->valueByPath("two")->equals(
            m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])));
        $this->assertTrue($mMap->valueByPath("two.one")->equals("a"));
        $this->assertTrue(
            $mMap->valueByPath("two.two")->equals(
            m(["one" => "a", "two" => "b", "three" => "c"])));
        $this->assertTrue($mMap->valueByPath("two.two.one")->equals("a"));
        $this->assertTrue($mMap->valueByPath("two.two.two")->equals("b"));
        $this->assertTrue($mMap->valueByPath("two.two.three")->equals("c"));

        $mMap = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $this->assertTrue($mMap->valueByPath(u("one"))->equals("a"));
        $this->assertTrue(
            $mMap->valueByPath(u("two"))->equals(
            m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])));
        $this->assertTrue($mMap->valueByPath(u("two.one"))->equals("a"));
        $this->assertTrue(
            $mMap->valueByPath(u("two.two"))->equals(
            m(["one" => "a", "two" => "b", "three" => "c"])));
        $this->assertTrue($mMap->valueByPath(u("two.two.one"))->equals("a"));
        $this->assertTrue($mMap->valueByPath(u("two.two.two"))->equals("b"));
        $this->assertTrue($mMap->valueByPath(u("two.two.three"))->equals("c"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValueByPath ()
    {
        $mMap = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $mMap->setValueByPath("one", "d");
        $mMap->setValueByPath("two.one", "e");
        $mMap->setValueByPath("two.two.one", "f");
        $mMap->setValueByPath("two.two.two", "g");
        $mMap->setValueByPath("two.two.three", "h");
        $this->assertTrue($mMap->valueByPath("one")->equals("d"));
        $this->assertTrue(
            $mMap->valueByPath("two")->equals(
            m(["one" => "e", "two" => m(["one" => "f", "two" => "g", "three" => "h"])])));
        $this->assertTrue($mMap->valueByPath("two.one")->equals("e"));
        $this->assertTrue(
            $mMap->valueByPath("two.two")->equals(
            m(["one" => "f", "two" => "g", "three" => "h"])));
        $this->assertTrue($mMap->valueByPath("two.two.one")->equals("f"));
        $this->assertTrue($mMap->valueByPath("two.two.two")->equals("g"));
        $this->assertTrue($mMap->valueByPath("two.two.three")->equals("h"));

        $mMap = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $mMap->setValueByPath(u("one"), "d");
        $mMap->setValueByPath(u("two.one"), "e");
        $mMap->setValueByPath(u("two.two.one"), "f");
        $mMap->setValueByPath(u("two.two.two"), "g");
        $mMap->setValueByPath(u("two.two.three"), "h");
        $this->assertTrue($mMap->valueByPath(u("one"))->equals("d"));
        $this->assertTrue(
            $mMap->valueByPath(u("two"))->equals(
            m(["one" => "e", "two" => m(["one" => "f", "two" => "g", "three" => "h"])])));
        $this->assertTrue($mMap->valueByPath(u("two.one"))->equals("e"));
        $this->assertTrue(
            $mMap->valueByPath(u("two.two"))->equals(
            m(["one" => "f", "two" => "g", "three" => "h"])));
        $this->assertTrue($mMap->valueByPath(u("two.two.one"))->equals("f"));
        $this->assertTrue($mMap->valueByPath(u("two.two.two"))->equals("g"));
        $this->assertTrue($mMap->valueByPath(u("two.two.three"))->equals("h"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"]);

        // Using the default comparator.

        $bFound = $mMap->find("c");
        $this->assertTrue($bFound);

        $sFoundUnderKey;
        $bFound = $mMap->find("d", CComparator::EQUALITY, $sFoundUnderKey);
        $this->assertTrue($bFound);
        $this->assertTrue($sFoundUnderKey->equals("four"));

        $bFound = $mMap->find("C");
        $this->assertFalse($bFound);

        $bFound = $mMap->find("f");
        $this->assertFalse($bFound);

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $sFoundUnderKey;
        $bFound = $mMap->find(u("C"), $fnComparator, $sFoundUnderKey);
        $this->assertTrue($bFound);
        $this->assertTrue($sFoundUnderKey->equals("three"));

        // Special case.
        $mMap = new CMapObject();
        $bFound = $mMap->find("a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindScalar ()
    {
        $mMap = ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"];
        $mMap = CMapObject::fromPArray($mMap);

        $bFound = $mMap->findScalar("c");
        $this->assertTrue($bFound);

        $sFoundUnderKey;
        $bFound = $mMap->findScalar("d", $sFoundUnderKey);
        $this->assertTrue($bFound);
        $this->assertTrue($sFoundUnderKey->equals("four"));

        $bFound = $mMap->findScalar("C");
        $this->assertFalse($bFound);

        $bFound = $mMap->findScalar("f");
        $this->assertFalse($bFound);

        // Special case.
        $mMap = new CMapObject();
        $bFound = $mMap->findScalar("a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountValue ()
    {
        $mMap = m(["one" => "a", "two" => "c", "three" => "b", "four" => "c", "five" => "d", "six" => "e",
            "seven" => "c", "eight" => "c", "nine" => "f", "ten" => "g", "eleven" => "h", "twelve" => "c"]);

        // Using the default comparator.
        $this->assertTrue( $mMap->countValue("c") == 5 );

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $this->assertTrue( $mMap->countValue(u("C"), $fnComparator) == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"]);
        $mMap->remove("three");
        $this->assertTrue($mMap->equals(m(["one" => "a", "two" => "b", "four" => "d", "five" => "e"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $mMap = m(["one" => 1, "two" => 2, "three" => 3, "four" => 4, "five" => 5, "six" => 6, "seven" => 7,
            "eight" => 8, "nine" => 9, "ten" => 10]);
        $mMap = $mMap->filter(function ($iValue)
            {
                return CMathi::isEven($iValue);
            });
        $this->assertTrue($mMap->equals(m(["two" => 2, "four" => 4, "six" => 6, "eight" => 8, "ten" => 10])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKeys ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $aKeys = $mMap->keys();
        $this->assertTrue($aKeys->equals(a("one", "two", "three")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testValues ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $aValues = $mMap->values();
        $this->assertTrue($aValues->equals(a("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMerge ()
    {
        $mMap0 = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b",
            "three" => "c"])])]);
        $mMap1 = m(["one" => "b", "two" => m(["two" => m(["four" => "d"])]), "three" => "c"]);
        $mMap2 = m(["two" => m(["two" => m(["five" => "e"])])]);
        $mMap = $mMap0->merge($mMap1, $mMap2);
        $this->assertTrue($mMap->equals(m([
            "one" => "b",
            "two" => m([
                "one" => "a", "two" => m([
                    "one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"])]),
            "three" => "c"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAreKeysSequential ()
    {
        $mMap = m([0 => "a", 1 => "b", 2 => "c", 3 => "d", 4 => "e"]);
        $this->assertTrue($mMap->areKeysSequential());

        $mMap = m([0 => "a", 1 => "b", 2 => "c", 4 => "d", 5 => "e"]);
        $this->assertFalse($mMap->areKeysSequential());

        $mMap = m([0 => "a", 1 => "b", 2 => "c", "three" => "d", 4 => "e"]);
        $this->assertFalse($mMap->areKeysSequential());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsertValue ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap->insertValue("d");
        $this->assertTrue($mMap->equals(m(["one" => "a", "two" => "b", "three" => "c", 0 => "d"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromArguments ()
    {
        $mMap = CMapObject::fromArguments([["one" => "a", "two" => "b", "three" => "c"]]);
        $this->assertTrue($mMap->equals(m(["one" => "a", "two" => "b", "three" => "c"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromPArray ()
    {
        $mMap = CMapObject::fromPArray(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue($mMap->equals(m(["one" => "a", "two" => "b", "three" => "c"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToPArray ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap = $mMap->toPArray();
        $this->assertTrue(CMap::equals($mMap, ["one" => "a", "two" => "b", "three" => "c"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetExists ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue(isset($mMap["one"]));
        $this->assertFalse(isset($mMap["four"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetGet ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $mMap["one"]->equals("a") && $mMap["two"]->equals("b") && $mMap["three"]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetSet ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap["two"] = "d";
        $this->assertTrue( $mMap["one"]->equals("a") && $mMap["two"]->equals("d") && $mMap["three"]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetUnset ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        unset($mMap["two"]);
        $this->assertTrue($mMap->equals(["one" => "a", "three" => "c"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCount ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( count($mMap) == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClone ()
    {
        $mMap0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $mMap1 = clone $mMap0;
        $this->assertTrue($mMap0->equals($mMap1));
        $mMap0["one"] = "d";
        $this->assertTrue($mMap1["one"]->equals("a"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testForeach ()
    {
        $mMap = m(["one" => "a", "two" => "b", "three" => "c"]);
        $aKeys = $mMap->keys();
        $aValues = $mMap->values();
        $i = 0;
        foreach ($mMap as $sKey => $sValue)
        {
            $this->assertTrue( $sKey->equals($aKeys[$i]) && $sValue->equals($aValues[$i]) );
            $i++;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
