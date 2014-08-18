<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
        $map = m();
        $this->assertTrue( $map->length() == 0 );

        $map = new CMapObject();
        $map["one"] = "a";
        $map["two"] = "b";
        $map["three"] = "c";
        $this->assertTrue( $map->length() == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $map = new CMapObject();
        $this->assertTrue($map->isEmpty());

        $map = new CMapObject();
        $map["one"] = "a";
        $this->assertFalse($map->isEmpty());

        $map = new CMapObject();
        $map["one"] = null;
        $this->assertFalse($map->isEmpty());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        // Using the default comparator.

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue($map0->equals($map1));

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "a", "two" => "b", "four" => "c"]);
        $this->assertFalse($map0->equals($map1));

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "a", "two" => "b", "three" => "d"]);
        $this->assertFalse($map0->equals($map1));

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "a", "two" => "b", "three" => "C"]);
        $this->assertFalse($map0->equals($map1));

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "a", "two" => "b", "three" => "c", "four" => "d"]);
        $this->assertFalse($map0->equals($map1));

        $map0 = m(["one" => 1, "two" => 2, "three" => 3]);
        $map1 = m(["one" => 1, "two" => 2, "three" => 3]);
        $this->assertTrue($map0->equals($map1));

        $map0 = m([1, 2, 3]);
        $map1 = m([1, 2, 3]);
        $this->assertTrue($map0->equals($map1));

        $map0 = m([1.2, 3.4, 5.6]);
        $map1 = m([1.2, 3.4, 5.6]);
        $this->assertTrue($map0->equals($map1));

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => u("a"), "two" => u("b"), "three" => u("c")]);
        $this->assertTrue($map0->equals($map1));

        $map0 = new CMapObject();
        $map1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertFalse($map0->equals($map1));

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = new CMapObject();
        $this->assertFalse($map0->equals($map1));

        // Using a custom comparator.
        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "A", "two" => "B", "three" => "C"]);
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $this->assertTrue($map0->equals($map1, $comparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        // Using the default comparator.

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $map0->compare($map1) == 0 );

        $map0 = m(["a" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $map0->compare($map1) < 0 );

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "d", "two" => "e", "three" => "f"]);
        $this->assertTrue( $map0->compare($map1) < 0 );

        $map0 = m(["one" => "d", "two" => "e", "three" => "f"]);
        $map1 = m(["one" => "a", "two" => "e", "three" => "f"]);
        $this->assertTrue( $map0->compare($map1) > 0 );

        $map0 = m(["one" => "a", "two" => "b"]);
        $map1 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $map0->compare($map1) < 0 );

        $map0 = m(["one" => 1, "two" => 2, "three" => 3]);
        $map1 = m(["one" => 1, "two" => 2, "three" => 3]);
        $this->assertTrue( $map0->compare($map1) == 0 );

        $map0 = m([1, 2, 3]);
        $map1 = m([1, 2, 3]);
        $this->assertTrue( $map0->compare($map1) == 0 );

        $map0 = m([1, 2, 3]);
        $map1 = m([4, 5, 6]);
        $this->assertTrue( $map0->compare($map1) < 0 );

        $map0 = m([4, 5, 6]);
        $map1 = m([3, 5, 6]);
        $this->assertTrue( $map0->compare($map1) > 0 );

        $map0 = m([1.2, 3.4, 5.6]);
        $map1 = m([1.2, 3.4, 5.6]);
        $this->assertTrue( $map0->compare($map1) == 0 );

        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => u("a"), "two" => u("b"), "three" => u("c")]);
        $this->assertTrue( $map0->compare($map1) == 0 );

        // Using a custom comparator.
        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = m(["one" => "A", "two" => "B", "three" => "C"]);
        $comparator = function ($string0, $string1)
            {
                return ( $string0->toLowerCase()->equals($string1->toLowerCase()) ) ? 0 : -1;
            };
        $this->assertTrue( $map0->compare($map1, $comparator) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasKey ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue($map->hasKey("two"));
        $this->assertFalse($map->hasKey("four"));

        $map = m(["one" => "a", "two" => null, "three" => "c"]);
        $this->assertTrue($map->hasKey("two"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPath ()
    {
        $map = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $this->assertTrue($map->hasPath("one"));
        $this->assertTrue($map->hasPath("two"));
        $this->assertTrue($map->hasPath("two.one"));
        $this->assertTrue($map->hasPath("two.two.three"));
        $this->assertFalse($map->hasPath("three"));
        $this->assertFalse($map->hasPath("one.one"));
        $this->assertFalse($map->hasPath("two.three"));

        $map = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $this->assertTrue($map->hasPath(u("one")));
        $this->assertTrue($map->hasPath(u("two")));
        $this->assertTrue($map->hasPath(u("two.one")));
        $this->assertTrue($map->hasPath(u("two.two.three")));
        $this->assertFalse($map->hasPath(u("three")));
        $this->assertFalse($map->hasPath(u("one.one")));
        $this->assertFalse($map->hasPath(u("two.three")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testValueByPath ()
    {
        $map = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $this->assertTrue($map->valueByPath("one")->equals("a"));
        $this->assertTrue(
            $map->valueByPath("two")->equals(
            m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])));
        $this->assertTrue($map->valueByPath("two.one")->equals("a"));
        $this->assertTrue(
            $map->valueByPath("two.two")->equals(
            m(["one" => "a", "two" => "b", "three" => "c"])));
        $this->assertTrue($map->valueByPath("two.two.one")->equals("a"));
        $this->assertTrue($map->valueByPath("two.two.two")->equals("b"));
        $this->assertTrue($map->valueByPath("two.two.three")->equals("c"));

        $map = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $this->assertTrue($map->valueByPath(u("one"))->equals("a"));
        $this->assertTrue(
            $map->valueByPath(u("two"))->equals(
            m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])));
        $this->assertTrue($map->valueByPath(u("two.one"))->equals("a"));
        $this->assertTrue(
            $map->valueByPath(u("two.two"))->equals(
            m(["one" => "a", "two" => "b", "three" => "c"])));
        $this->assertTrue($map->valueByPath(u("two.two.one"))->equals("a"));
        $this->assertTrue($map->valueByPath(u("two.two.two"))->equals("b"));
        $this->assertTrue($map->valueByPath(u("two.two.three"))->equals("c"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValueByPath ()
    {
        $map = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $map->setValueByPath("one", "d");
        $map->setValueByPath("two.one", "e");
        $map->setValueByPath("two.two.one", "f");
        $map->setValueByPath("two.two.two", "g");
        $map->setValueByPath("two.two.three", "h");
        $this->assertTrue($map->valueByPath("one")->equals("d"));
        $this->assertTrue(
            $map->valueByPath("two")->equals(
            m(["one" => "e", "two" => m(["one" => "f", "two" => "g", "three" => "h"])])));
        $this->assertTrue($map->valueByPath("two.one")->equals("e"));
        $this->assertTrue(
            $map->valueByPath("two.two")->equals(
            m(["one" => "f", "two" => "g", "three" => "h"])));
        $this->assertTrue($map->valueByPath("two.two.one")->equals("f"));
        $this->assertTrue($map->valueByPath("two.two.two")->equals("g"));
        $this->assertTrue($map->valueByPath("two.two.three")->equals("h"));

        $map = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b", "three" => "c"])])]);
        $map->setValueByPath(u("one"), "d");
        $map->setValueByPath(u("two.one"), "e");
        $map->setValueByPath(u("two.two.one"), "f");
        $map->setValueByPath(u("two.two.two"), "g");
        $map->setValueByPath(u("two.two.three"), "h");
        $this->assertTrue($map->valueByPath(u("one"))->equals("d"));
        $this->assertTrue(
            $map->valueByPath(u("two"))->equals(
            m(["one" => "e", "two" => m(["one" => "f", "two" => "g", "three" => "h"])])));
        $this->assertTrue($map->valueByPath(u("two.one"))->equals("e"));
        $this->assertTrue(
            $map->valueByPath(u("two.two"))->equals(
            m(["one" => "f", "two" => "g", "three" => "h"])));
        $this->assertTrue($map->valueByPath(u("two.two.one"))->equals("f"));
        $this->assertTrue($map->valueByPath(u("two.two.two"))->equals("g"));
        $this->assertTrue($map->valueByPath(u("two.two.three"))->equals("h"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"]);

        // Using the default comparator.

        $found = $map->find("c");
        $this->assertTrue($found);

        $foundUnderKey;
        $found = $map->find("d", CComparator::EQUALITY, $foundUnderKey);
        $this->assertTrue($found);
        $this->assertTrue($foundUnderKey->equals("four"));

        $found = $map->find("C");
        $this->assertFalse($found);

        $found = $map->find("f");
        $this->assertFalse($found);

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $foundUnderKey;
        $found = $map->find(u("C"), $comparator, $foundUnderKey);
        $this->assertTrue($found);
        $this->assertTrue($foundUnderKey->equals("three"));

        // Special case.
        $map = new CMapObject();
        $found = $map->find("a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindScalar ()
    {
        $map = ["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"];
        $map = CMapObject::fromPArray($map);

        $found = $map->findScalar("c");
        $this->assertTrue($found);

        $foundUnderKey;
        $found = $map->findScalar("d", $foundUnderKey);
        $this->assertTrue($found);
        $this->assertTrue($foundUnderKey->equals("four"));

        $found = $map->findScalar("C");
        $this->assertFalse($found);

        $found = $map->findScalar("f");
        $this->assertFalse($found);

        // Special case.
        $map = new CMapObject();
        $found = $map->findScalar("a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountValue ()
    {
        $map = m(["one" => "a", "two" => "c", "three" => "b", "four" => "c", "five" => "d", "six" => "e",
            "seven" => "c", "eight" => "c", "nine" => "f", "ten" => "g", "eleven" => "h", "twelve" => "c"]);

        // Using the default comparator.
        $this->assertTrue( $map->countValue("c") == 5 );

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $this->assertTrue( $map->countValue(u("C"), $comparator) == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"]);
        $map->remove("three");
        $this->assertTrue($map->equals(m(["one" => "a", "two" => "b", "four" => "d", "five" => "e"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $map = m(["one" => 1, "two" => 2, "three" => 3, "four" => 4, "five" => 5, "six" => 6, "seven" => 7,
            "eight" => 8, "nine" => 9, "ten" => 10]);
        $map = $map->filter(function ($value)
            {
                return CMathi::isEven($value);
            });
        $this->assertTrue($map->equals(m(["two" => 2, "four" => 4, "six" => 6, "eight" => 8, "ten" => 10])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKeys ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $keys = $map->keys();
        $this->assertTrue($keys->equals(a("one", "two", "three")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testValues ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $values = $map->values();
        $this->assertTrue($values->equals(a("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMerge ()
    {
        $map0 = m(["one" => "a", "two" => m(["one" => "a", "two" => m(["one" => "a", "two" => "b",
            "three" => "c"])])]);
        $map1 = m(["one" => "b", "two" => m(["two" => m(["four" => "d"])]), "three" => "c"]);
        $map2 = m(["two" => m(["two" => m(["five" => "e"])])]);
        $map = $map0->merge($map1, $map2);
        $this->assertTrue($map->equals(m([
            "one" => "b",
            "two" => m([
                "one" => "a", "two" => m([
                    "one" => "a", "two" => "b", "three" => "c", "four" => "d", "five" => "e"])]),
            "three" => "c"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAreKeysSequential ()
    {
        $map = m([0 => "a", 1 => "b", 2 => "c", 3 => "d", 4 => "e"]);
        $this->assertTrue($map->areKeysSequential());

        $map = m([0 => "a", 1 => "b", 2 => "c", 4 => "d", 5 => "e"]);
        $this->assertFalse($map->areKeysSequential());

        $map = m([0 => "a", 1 => "b", 2 => "c", "three" => "d", 4 => "e"]);
        $this->assertFalse($map->areKeysSequential());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsertValue ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map->insertValue("d");
        $this->assertTrue($map->equals(m(["one" => "a", "two" => "b", "three" => "c", 0 => "d"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromArguments ()
    {
        $map = CMapObject::fromArguments([["one" => "a", "two" => "b", "three" => "c"]]);
        $this->assertTrue($map->equals(m(["one" => "a", "two" => "b", "three" => "c"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromPArray ()
    {
        $map = CMapObject::fromPArray(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue($map->equals(m(["one" => "a", "two" => "b", "three" => "c"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToPArray ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map = $map->toPArray();
        $this->assertTrue(CMap::equals($map, ["one" => "a", "two" => "b", "three" => "c"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetExists ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue(isset($map["one"]));
        $this->assertFalse(isset($map["four"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetGet ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $map["one"]->equals("a") && $map["two"]->equals("b") && $map["three"]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetSet ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map["two"] = "d";
        $this->assertTrue( $map["one"]->equals("a") && $map["two"]->equals("d") && $map["three"]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetUnset ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        unset($map["two"]);
        $this->assertTrue($map->equals(["one" => "a", "three" => "c"]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCount ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( count($map) == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClone ()
    {
        $map0 = m(["one" => "a", "two" => "b", "three" => "c"]);
        $map1 = clone $map0;
        $this->assertTrue($map0->equals($map1));
        $map0["one"] = "d";
        $this->assertTrue($map1["one"]->equals("a"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testForeach ()
    {
        $map = m(["one" => "a", "two" => "b", "three" => "c"]);
        $keys = $map->keys();
        $values = $map->values();
        $i = 0;
        foreach ($map as $key => $value)
        {
            $this->assertTrue( $key->equals($keys[$i]) && $value->equals($values[$i]) );
            $i++;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
