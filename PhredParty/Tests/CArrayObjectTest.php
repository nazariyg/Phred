<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CArrayObjectTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $array = a();
        $this->assertTrue( $array->length() == 0 );

        $array = new CArrayObject();
        $this->assertTrue( $array->length() == 0 );
        $array = new CArrayObject(10);
        $this->assertTrue( $array->length() == 10 );

        $array = CArrayObject::makeDim2(2, 3);
        $this->assertTrue(
            $array->length() == 2 &&
            $array[0]->length() == 3 && $array[1]->length() == 3 );

        $array = CArrayObject::makeDim3(2, 3, 2);
        $this->assertTrue(
            $array->length() == 2 &&
            $array[0]->length() == 3 && $array[1]->length() == 3 &&
            $array[0][0]->length() == 2 && $array[0][1]->length() == 2 && $array[0][2]->length() == 2 );

        $array = CArrayObject::makeDim4(2, 3, 2, 1);
        $this->assertTrue(
            $array->length() == 2 &&
            $array[0]->length() == 3 && $array[1]->length() == 3 &&
            $array[0][0]->length() == 2 && $array[0][1]->length() == 2 && $array[0][2]->length() == 2 &&
            $array[0][0][0]->length() == 1 && $array[0][0][1]->length() == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromPArray ()
    {
        $map = [0 => u("a"), 1 => u("b"), 3 => u("c"), 9 => u("d")];
        $array = CArrayObject::fromPArray($map);
        $this->assertTrue($array->equals(a("a", "b", "c", "d")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToPArray ()
    {
        $array = a("a", "b", "c");
        $map = $array->toPArray();
        $this->assertTrue( $map[0]->equals("a") && $map[1]->equals("b") && $map[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testJoin ()
    {
        $array = a("a", "b", "c");
        $joined = $array->join(", ");
        $this->assertTrue($joined->equals("a, b, c"));

        $array = a(true, false, true);
        $joined = $array->join(", ");
        $this->assertTrue($joined->equals("1, 0, 1"));

        $array = a(12, 34, 56);
        $joined = $array->join(", ");
        $this->assertTrue($joined->equals("12, 34, 56"));

        $array = a(1.2, 3.4, 5.6);
        $joined = $array->join(", ");
        $this->assertTrue($joined->equals("1.2, 3.4, 5.6"));

        $array = a("a", "b", "c");
        $joined = $array->join("");
        $this->assertTrue($joined->equals("abc"));

        // Special cases.

        $array = new CArrayObject();
        $joined = $array->join("");
        $this->assertTrue($joined->equals(""));

        $array = new CArrayObject();
        $joined = $array->join(", ");
        $this->assertTrue($joined->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $array = new CArrayObject();
        $this->assertTrue( $array->length() == 0 );

        $array = new CArrayObject(2);
        $this->assertTrue( $array->length() == 2 );

        $array = a("a", "b", "c");
        $this->assertTrue( $array->length() == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $array = new CArrayObject();
        $this->assertTrue($array->isEmpty());

        $array = new CArrayObject(2);
        $this->assertFalse($array->isEmpty());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        // Using the default comparator.

        $array0 = a("a", "b", "c");
        $array1 = a("a", "b", "c");
        $this->assertTrue($array0->equals($array1));

        $array0 = a("a", "b", "c");
        $array1 = a("a", "b", "d");
        $this->assertFalse($array0->equals($array1));

        $array0 = a("a", "b", "c");
        $array1 = a("a", "b", "C");
        $this->assertFalse($array0->equals($array1));

        $array0 = a("a", "b", "c");
        $array1 = a("a", "b", "c", "d");
        $this->assertFalse($array0->equals($array1));

        $array0 = a(1, 2, 3);
        $array1 = a(1, 2, 3);
        $this->assertTrue($array0->equals($array1));

        $array0 = a(1.2, 3.4, 5.6);
        $array1 = a(1.2, 3.4, 5.6);
        $this->assertTrue($array0->equals($array1));

        $array0 = a(u("a"), u("b"), u("c"));
        $array1 = a(u("a"), u("b"), u("c"));
        $this->assertTrue($array0->equals($array1));

        $array0 = new CArrayObject();
        $array1 = a("a", "b", "c");
        $this->assertFalse($array0->equals($array1));

        $array0 = a("a", "b", "c");
        $array1 = new CArrayObject();
        $this->assertFalse($array0->equals($array1));

        // Using a custom comparator.
        $array0 = a("a", "b", "c");
        $array1 = a("A", "B", "C");
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $this->assertTrue($array0->equals($array1, $comparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        // Using the default comparator.

        $array0 = a("a", "b", "c");
        $array1 = a("a", "b", "c");
        $this->assertTrue( $array0->compare($array1) == 0 );

        $array0 = a("a", "b", "c");
        $array1 = a("d", "e", "f");
        $this->assertTrue( $array0->compare($array1) < 0 );

        $array0 = a("d", "e", "f");
        $array1 = a("a", "e", "f");
        $this->assertTrue( $array0->compare($array1) > 0 );

        $array0 = a("a", "b");
        $array1 = a("a", "b", "c");
        $this->assertTrue( $array0->compare($array1) < 0 );

        $array0 = a(1, 2, 3);
        $array1 = a(1, 2, 3);
        $this->assertTrue( $array0->compare($array1) == 0 );

        $array0 = a(1, 2, 3);
        $array1 = a(4, 5, 6);
        $this->assertTrue( $array0->compare($array1) < 0 );

        $array0 = a(4, 5, 6);
        $array1 = a(3, 5, 6);
        $this->assertTrue( $array0->compare($array1) > 0 );

        $array0 = a(1.2, 3.4, 5.6);
        $array1 = a(1.2, 3.4, 5.6);
        $this->assertTrue( $array0->compare($array1) == 0 );

        $array0 = a(u("a"), u("b"), u("c"));
        $array1 = a(u("a"), u("b"), u("c"));
        $this->assertTrue( $array0->compare($array1) == 0 );

        // Using a custom comparator.

        $array0 = a("a", "b", "c");
        $array1 = a("A", "B", "C");
        $comparator = function ($string0, $string1)
            {
                return ( $string0->toLowerCase()->equals($string1->toLowerCase()) ) ? 0 : -1;
            };
        $this->assertTrue( $array0->compare($array1, $comparator) == 0 );

        $array0 = a(1, 3, 5);
        $array1 = a(2, 4, 6);
        $comparator = function ($value0, $value1)
            {
                return ( CMathi::isOdd($value0) && CMathi::isEven($value1) ) ? 1 : -1;
            };
        $this->assertTrue( $array0->compare($array1, $comparator) > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFirst ()
    {
        $array = a("a", "b", "c");
        $this->assertTrue($array->first()->equals("a"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLast ()
    {
        $array = a("a", "b", "c");
        $this->assertTrue($array->last()->equals("c"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubar ()
    {
        $array = a("a", "b", "c", "d", "e");

        $subarray = $array->subar(3);
        $this->assertTrue($subarray->equals(a("d", "e")));

        $subarray = $array->subar(1, 3);
        $this->assertTrue($subarray->equals(a("b", "c", "d")));

        // Special cases.

        $subarray = $array->subar(5);
        $this->assertTrue($subarray->length() == 0);

        $subarray = $array->subar(5, 0);
        $this->assertTrue($subarray->length() == 0);

        $subarray = $array->subar(0, 0);
        $this->assertTrue($subarray->length() == 0);

        $subarray = $array->subar(2, 0);
        $this->assertTrue($subarray->length() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubarray ()
    {
        $array = a("a", "b", "c", "d", "e");

        $subarray = $array->subarray(1, 4);
        $this->assertTrue($subarray->equals(a("b", "c", "d")));

        $subarray = $array->subarray(3, 5);
        $this->assertTrue($subarray->equals(a("d", "e")));

        // Special cases.

        $subarray = $array->subarray(5, 5);
        $this->assertTrue($subarray->length() == 0);

        $subarray = $array->subarray(0, 0);
        $this->assertTrue($subarray->length() == 0);

        $subarray = $array->subarray(2, 2);
        $this->assertTrue($subarray->length() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSlice ()
    {
        $array = a("a", "b", "c", "d", "e");

        $subarray = $array->slice(1, 4);
        $this->assertTrue($subarray->equals(a("b", "c", "d")));

        $subarray = $array->slice(3, 5);
        $this->assertTrue($subarray->equals(a("d", "e")));

        // Special cases.

        $subarray = $array->slice(5, 5);
        $this->assertTrue($subarray->length() == 0);

        $subarray = $array->slice(0, 0);
        $this->assertTrue($subarray->length() == 0);

        $subarray = $array->slice(2, 2);
        $this->assertTrue($subarray->length() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $array = a("a", "b", "c", "d", "e");

        // Using the default comparator.

        $found = $array->find("c");
        $this->assertTrue($found);

        $foundAtPos;
        $found = $array->find("d", CComparator::EQUALITY, $foundAtPos);
        $this->assertTrue($found);
        $this->assertTrue( $foundAtPos == 3 );

        $found = $array->find("C");
        $this->assertFalse($found);

        $found = $array->find("f");
        $this->assertFalse($found);

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $foundAtPos;
        $found = $array->find(u("C"), $comparator, $foundAtPos);
        $this->assertTrue($found);
        $this->assertTrue( $foundAtPos == 2 );

        // Special case.
        $array = new CArrayObject();
        $found = $array->find("a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindScalar ()
    {
        $array = CArrayObject::fromSplArray(CArray::fromElements("a", "b", "c", "d", "e"));

        $found = $array->findScalar("c");
        $this->assertTrue($found);

        $foundAtPos;
        $found = $array->findScalar("d", $foundAtPos);
        $this->assertTrue($found);
        $this->assertTrue( $foundAtPos == 3 );

        $found = $array->findScalar("C");
        $this->assertFalse($found);

        $found = $array->findScalar("f");
        $this->assertFalse($found);

        // Special case.
        $array = new CArrayObject();
        $found = $array->findScalar("a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindBinary ()
    {
        $array = a("oua", "vnf", "fnf", "aod", "tvi", "nbt", "jny", "vor", "rfd", "cvm", "hyh",
            "kng", "ggo", "uea", "hkb", "qbk", "xla", "uod", "jzi", "chw", "ssy", "olr", "bzl", "oux", "ltk", "bah",
            "khu", "msr", "pqv", "npb", "mtb", "eku", "vcv", "vbv", "wuo", "lrw", "bkw", "ezz", "jtc", "dwk", "dsq",
            "kzu", "oey", "vbi", "seh", "klz", "asj", "gzg", "ccs", "qop");
        $arrayOrig = clone $array;
        $len = $arrayOrig->length();

        // Sort the array first.
        $array->sort(CComparator::ORDER_ASC);

        // Using the default comparators.
        for ($i = 0; $i < $len; $i += 3)
        {
            $string = $arrayOrig[$i];

            $foundAtPos0;
            $array->find($string, CComparator::EQUALITY, $foundAtPos0);
            $foundAtPos1;
            $found = $array->findBinary($string, CComparator::ORDER_ASC, $foundAtPos1);
            $this->assertTrue($found);
            $this->assertTrue( $foundAtPos1 == $foundAtPos0 );
        }

        // Using custom comparators.
        $comparatorEquality = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $comparatorOrderAsc = function ($string0, $string1)
            {
                return $string0->toLowerCase()->compare($string1->toLowerCase());
            };
        for ($i = 0; $i < $len; $i += 5)
        {
            $string = $arrayOrig[$i]->toUpperCase();

            $foundAtPos0;
            $array->find($string, $comparatorEquality, $foundAtPos0);
            $foundAtPos1;
            $found = $array->findBinary($string, $comparatorOrderAsc, $foundAtPos1);
            $this->assertTrue($found);
            $this->assertTrue( $foundAtPos1 == $foundAtPos0 );
        }

        // Special cases.

        $array = a("a", "b");
        $found = $array->findBinary("a");
        $this->assertTrue($found);
        $found = $array->findBinary("b");
        $this->assertTrue($found);
        $found = $array->findBinary("c");
        $this->assertFalse($found);

        $array = a("a");
        $found = $array->findBinary("a");
        $this->assertTrue($found);

        $array = new CArrayObject();
        $found = $array->findBinary("a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountElement ()
    {
        $array = a("a", "c", "b", "c", "d", "e", "c", "c", "f", "g", "h", "c");

        // Using the default comparator.
        $this->assertTrue( $array->countElement("c") == 5 );

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $this->assertTrue( $array->countElement(u("C"), $comparator) == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetLength ()
    {
        $array = a("a", "b");
        $array->setLength(3);
        $this->assertTrue( $array->length() == 3 && $array[0]->equals("a") && $array[1]->equals("b") );
        $array->setLength(1);
        $this->assertTrue( $array->length() == 1 && $array[0]->equals("a") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPush ()
    {
        $array = a("a", "b", "c");
        $newLength = $array->push("d");
        $this->assertTrue($array->equals(a("a", "b", "c", "d")));
        $this->assertTrue( $newLength == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPop ()
    {
        $array = a("a", "b", "c");
        $poppedString = $array->pop();
        $this->assertTrue($array->equals(a("a", "b")));
        $this->assertTrue($poppedString->equals("c"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPushArray ()
    {
        $array = a("a", "b", "c");
        $newLength = $array->pushArray(a("d", "e"));
        $this->assertTrue($array->equals(a("a", "b", "c", "d", "e")));
        $this->assertTrue( $newLength == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnshift ()
    {
        $array = a("a", "b", "c");
        $newLength = $array->unshift("d");
        $this->assertTrue($array->equals(a("d", "a", "b", "c")));
        $this->assertTrue( $newLength == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShift ()
    {
        $array = a("a", "b", "c");
        $poppedString = $array->shift();
        $this->assertTrue($array->equals(a("b", "c")));
        $this->assertTrue($poppedString->equals("a"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnshiftArray ()
    {
        $array = a("a", "b", "c");
        $newLength = $array->unshiftArray(a("d", "e"));
        $this->assertTrue($array->equals(a("d", "e", "a", "b", "c")));
        $this->assertTrue( $newLength == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsert ()
    {
        $array = a("a", "b", "c", "d", "e");
        $array->insert(3, "x");
        $this->assertTrue($array->equals(a("a", "b", "c", "x", "d", "e")));

        $array = a("a", "b", "c", "d", "e");
        $array->insert(0, "x");
        $this->assertTrue($array->equals(a("x", "a", "b", "c", "d", "e")));

        // Special cases.

        $array = a("a", "b", "c", "d", "e");
        $array->insert(5, "x");
        $this->assertTrue($array->equals(a("a", "b", "c", "d", "e", "x")));

        $array = new CArrayObject();
        $array->insert(0, "x");
        $this->assertTrue($array->equals(a("x")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsertArray ()
    {
        $array = a("a", "b", "c", "d", "e");
        $array->insertArray(3, a("x", "y"));
        $this->assertTrue($array->equals(a("a", "b", "c", "x", "y", "d", "e")));

        $array = a("a", "b", "c", "d", "e");
        $array->insertArray(0, a("x", "y"));
        $this->assertTrue($array->equals(a("x", "y", "a", "b", "c", "d", "e")));

        // Special cases.

        $array = a("a", "b", "c", "d", "e");
        $array->insertArray(5, a("x", "y"));
        $this->assertTrue($array->equals(a("a", "b", "c", "d", "e", "x", "y")));

        $array = new CArrayObject();
        $array->insertArray(0, a("x", "y"));
        $this->assertTrue($array->equals(a("x", "y")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadStart ()
    {
        $array = a("a", "b", "c");
        $array->padStart(" ", 5);
        $this->assertTrue($array->equals(a(" ", " ", "a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadEnd ()
    {
        $array = a("a", "b", "c");
        $array->padEnd(" ", 5);
        $this->assertTrue($array->equals(a("a", "b", "c", " ", " ")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $array = a("a", "b", "c", "d", "e");
        $array->remove(2);
        $this->assertTrue($array->equals(a("a", "b", "d", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveByValue ()
    {
        // Using the default comparator.

        $array = a("a", "b", "c", "d", "e");
        $anyRemoval = $array->removeByValue("d");
        $this->assertTrue($array->equals(a("a", "b", "c", "e")));
        $this->assertTrue($anyRemoval);

        $array = a("a", "b", "c", "d", "e", "c", "d", "e", "c", "d", "e");
        $anyRemoval = $array->removeByValue("d");
        $this->assertTrue($array->equals(a("a", "b", "c", "e", "c", "e", "c", "e")));
        $this->assertTrue($anyRemoval);

        $array = a("a", "b", "c");
        $anyRemoval = $array->removeByValue("d");
        $this->assertFalse($anyRemoval);

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $array = a("a", "b", "c", "d", "e");
        $anyRemoval = $array->removeByValue(u("D"), $comparator);
        $this->assertTrue($array->equals(a("a", "b", "c", "e")));
        $this->assertTrue($anyRemoval);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubarray ()
    {
        $array = a("a", "b", "c", "d", "e");
        $array->removeSubarray(3);
        $this->assertTrue($array->equals(a("a", "b", "c")));

        $array = a("a", "b", "c", "d", "e");
        $array->removeSubarray(1, 3);
        $this->assertTrue($array->equals(a("a", "e")));

        // Special cases.

        $array = a("a", "b", "c");
        $array->removeSubarray(3);
        $this->assertTrue($array->equals(a("a", "b", "c")));

        $array = a("a", "b", "c");
        $array->removeSubarray(3, 0);
        $this->assertTrue($array->equals(a("a", "b", "c")));

        $array = a("a", "b", "c");
        $array->removeSubarray(0, 0);
        $this->assertTrue($array->equals(a("a", "b", "c")));

        $array = a("a", "b", "c");
        $array->removeSubarray(1, 0);
        $this->assertTrue($array->equals(a("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplice ()
    {
        $array = a("a", "b", "c", "d", "e");
        $remArray = $array->splice(3);
        $this->assertTrue($array->equals(a("a", "b", "c")));
        $this->assertTrue($remArray->equals(a("d", "e")));

        $array = a("a", "b", "c", "d", "e");
        $remArray = $array->splice(1, 3);
        $this->assertTrue($array->equals(a("a", "e")));
        $this->assertTrue($remArray->equals(a("b", "c", "d")));

        // Special cases.

        $array = a("a", "b", "c");
        $remArray = $array->splice(3);
        $this->assertTrue($array->equals(a("a", "b", "c")));
        $this->assertTrue($remArray->equals(new CArrayObject()));

        $array = a("a", "b", "c");
        $remArray = $array->splice(3, 0);
        $this->assertTrue($array->equals(a("a", "b", "c")));
        $this->assertTrue($remArray->equals(new CArrayObject()));

        $array = a("a", "b", "c");
        $remArray = $array->splice(0, 0);
        $this->assertTrue($array->equals(a("a", "b", "c")));
        $this->assertTrue($remArray->equals(new CArrayObject()));

        $array = a("a", "b", "c");
        $remArray = $array->splice(1, 0);
        $this->assertTrue($array->equals(a("a", "b", "c")));
        $this->assertTrue($remArray->equals(new CArrayObject()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubarrayByRange ()
    {
        $array = a("a", "b", "c", "d", "e");
        $array->removeSubarrayByRange(3, 5);
        $this->assertTrue($array->equals(a("a", "b", "c")));

        $array = a("a", "b", "c", "d", "e");
        $array->removeSubarrayByRange(1, 4);
        $this->assertTrue($array->equals(a("a", "e")));

        // Special cases.

        $array = a("a", "b", "c");
        $array->removeSubarrayByRange(3, 3);
        $this->assertTrue($array->equals(a("a", "b", "c")));

        $array = a("a", "b", "c");
        $array->removeSubarrayByRange(0, 0);
        $this->assertTrue($array->equals(a("a", "b", "c")));

        $array = a("a", "b", "c");
        $array->removeSubarrayByRange(1, 1);
        $this->assertTrue($array->equals(a("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReverse ()
    {
        $array = a("a", "b", "c", "d", "e");
        $array->reverse();
        $this->assertTrue($array->equals(a("e", "d", "c", "b", "a")));

        $array = a("a", "b", "c", "d", "e", "f");
        $array->reverse();
        $this->assertTrue($array->equals(a("f", "e", "d", "c", "b", "a")));

        // Special cases.

        $array = a("a");
        $array->reverse();
        $this->assertTrue($array->equals(a("a")));

        $array = new CArrayObject();
        $array->reverse();
        $this->assertTrue($array->equals(new CArrayObject()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShuffle ()
    {
        $array = a("a", "b", "c", "d", "e", "f", "g", "h", "i");
        $arrayOrig = clone $array;
        $array->shuffle();
        $this->assertTrue($array->isSubsetOf($arrayOrig));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSort ()
    {
        $array = a("oua", "vnf", "fnf", "aod", "tvi", "nbt", "jny", "vor", "rfd", "cvm", "hyh",
            "kng", "ggo", "uea", "hkb", "qbk", "xla", "uod", "jzi", "chw", "ssy", "olr", "bzl", "oux", "ltk", "bah",
            "khu", "msr", "pqv", "npb", "mtb", "eku", "vcv", "vbv", "wuo", "lrw", "bkw", "ezz", "jtc", "dwk", "dsq",
            "kzu", "oey", "vbi", "seh", "klz", "asj", "gzg", "ccs", "qop");

        $array->sort(CComparator::ORDER_ASC);
        $this->assertTrue($array->equals(a("aod", "asj", "bah", "bkw", "bzl", "ccs", "chw",
            "cvm", "dsq", "dwk", "eku", "ezz", "fnf", "ggo", "gzg", "hkb", "hyh", "jny", "jtc", "jzi", "khu", "klz",
            "kng", "kzu", "lrw", "ltk", "msr", "mtb", "nbt", "npb", "oey", "olr", "oua", "oux", "pqv", "qbk", "qop",
            "rfd", "seh", "ssy", "tvi", "uea", "uod", "vbi", "vbv", "vcv", "vnf", "vor", "wuo", "xla")));

        $array->sort(CComparator::ORDER_DESC);
        $this->assertTrue($array->equals(a("xla", "wuo", "vor", "vnf", "vcv", "vbv", "vbi",
            "uod", "uea", "tvi", "ssy", "seh", "rfd", "qop", "qbk", "pqv", "oux", "oua", "olr", "oey", "npb", "nbt",
            "mtb", "msr", "ltk", "lrw", "kzu", "kng", "klz", "khu", "jzi", "jtc", "jny", "hyh", "hkb", "gzg", "ggo",
            "fnf", "ezz", "eku", "dwk", "dsq", "cvm", "chw", "ccs", "bzl", "bkw", "bah", "asj", "aod")));

        $array = a(5, 2, 1, 3, 4);
        $array->sort(CComparator::ORDER_ASC);
        $this->assertTrue($array->equals(a(1, 2, 3, 4, 5)));

        // Special cases.

        $array = a("a");
        $array->sort(CComparator::ORDER_ASC);
        $this->assertTrue($array->equals(a("a")));

        $array = new CArrayObject();
        $array->sort(CComparator::ORDER_ASC);
        $this->assertTrue($array->equals(new CArrayObject()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortOn ()
    {
        $array = a(
            new ClassForSortingObj(u("d")),
            new ClassForSortingObj(u("e")),
            new ClassForSortingObj(u("a")),
            new ClassForSortingObj(u("c")),
            new ClassForSortingObj(u("b")));
        $array->sortOn("value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $array[0]->value()->equals("a") &&
            $array[1]->value()->equals("b") &&
            $array[2]->value()->equals("c") &&
            $array[3]->value()->equals("d") &&
            $array[4]->value()->equals("e") );

        $array = a(
            new ClassForSortingObj(5),
            new ClassForSortingObj(2),
            new ClassForSortingObj(1),
            new ClassForSortingObj(3),
            new ClassForSortingObj(4));
        $array->sortOn("value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $array[0]->value() == 1 &&
            $array[1]->value() == 2 &&
            $array[2]->value() == 3 &&
            $array[3]->value() == 4 &&
            $array[4]->value() == 5 );

        $array = a(
            new ClassForSortingObj(u("d")),
            new ClassForSortingObj(u("e")),
            new ClassForSortingObj(u("a")),
            new ClassForSortingObj(u("c")),
            new ClassForSortingObj(u("b")));
        $array->sortOn("value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $array[0]->value()->equals("a") &&
            $array[1]->value()->equals("b") &&
            $array[2]->value()->equals("c") &&
            $array[3]->value()->equals("d") &&
            $array[4]->value()->equals("e") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStrings ()
    {
        $array = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        $array->sortUStrings(CUString::COLLATION_DEFAULT);
        $this->assertTrue($array->equals(a(
            "a", "A", "b", "B", "c", "C", "d", "D", "e", "E")));

        $array = a(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
        $array->sortUStrings(CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue($array->equals(a(
            "á", "A", "b", "B", "č", "C", "d", "D", "ê", "E")));

        $array = a(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
        $array->sortUStrings(CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue($array->equals(a(
            "?a", "[A", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E")));

        $array = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        $array->sortUStrings(CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue($array->equals(a(
            "A", "a", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsCi ()
    {
        $array = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        $array->sortUStringsCi(CUString::COLLATION_DEFAULT);
        $this->assertTrue($array->equals(a(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));

        $array = a(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
        $array->sortUStringsCi(CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue($array->equals(a(
            "á", "A", "B", "b", "C", "č", "D", "d", "E", "ê")));

        $array = a(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
        $array->sortUStringsCi(CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue($array->equals(a(
            "?a", "[A", ",B", "(b", "!C", " c", ";D", ".d", ":E", "\"e")));

        $array = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        $array->sortUStringsCi(CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue($array->equals(a(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsNat ()
    {
        $array = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        $array->sortUStringsNat(CUString::COLLATION_DEFAULT);
        $this->assertTrue($array->equals(a(
            "a", "A3", "A20", "A100", "b", "B", "c", "C", "d", "D", "e", "E")));

        $array = a(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A3", "A20", "A100");
        $array->sortUStringsNat(CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue($array->equals(a(
            "á", "A3", "A20", "A100", "b", "B", "č", "C", "d", "D", "ê", "E")));

        $array = a(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A3", "[A20", "[A100");
        $array->sortUStringsNat(CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue($array->equals(a(
            "?a", "[A3", "[A20", "[A100", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E")));

        $array = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        $array->sortUStringsNat(CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue($array->equals(a(
            "a", "A3", "A20", "A100", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsNatCi ()
    {
        $array = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        $array->sortUStringsNatCi(CUString::COLLATION_DEFAULT);
        $this->assertTrue($array->equals(a(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));

        $array = a(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A3", "A20", "A100");
        $array->sortUStringsNatCi(CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue($array->equals(a(
            "á", "A3", "A20", "A100", "b", "B", "C", "č", "d", "D", "ê", "E")));

        $array = a(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A3", "[A20", "[A100");
        $array->sortUStringsNatCi(CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue($array->equals(a(
            "?a", "[A3", "[A20", "[A100", "(b", ",B", "!C", " c", ".d", ";D", "\"e", ":E")));

        $array = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        $array->sortUStringsNatCi(CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue($array->equals(a(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $array = a(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $array = $array->filter(function ($element)
            {
                return CMathi::isEven($element);
            });
        $this->assertTrue($array->equals(a(2, 4, 6, 8, 10)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnique ()
    {
        // Using the default comparator.
        $array = a("a", "b", "c", "d", "e", "a", "c", "e");
        $array = $array->unique();
        $this->assertTrue($array->equals(a("a", "b", "c", "d", "e")));

        // Using a custom comparator.
        $array = a("A", "b", "C", "d", "E", "a", "c", "e");
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $array = $array->unique($comparator);
        $this->assertTrue($array->equals(a("A", "b", "C", "d", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testElementsSum ()
    {
        $array = a(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $this->assertTrue( $array->elementsSum() == 55 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testElementsProduct ()
    {
        $array = a(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $this->assertTrue( $array->elementsProduct() == 3628800 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsSubsetOf ()
    {
        // Using the default comparator.

        $array = a("a", "b", "c", "a", "b", "c", "a", "b", "c");
        $this->assertTrue($array->isSubsetOf(a("a", "b", "c")));

        $array = a("a", "b", "c", "a", "b", "c", "a", "d", "b", "c");
        $this->assertFalse($array->isSubsetOf(a("a", "b", "c")));

        // Using a custom comparator.
        $array = a("a", "b", "c", "a", "b", "c", "a", "b", "c");
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $this->assertTrue($array->isSubsetOf(a("A", "B", "C"), $comparator));

        // Special case.
        $array = new CArrayObject();
        $this->assertFalse($array->isSubsetOf(a("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnion ()
    {
        $array0 = a("a", "b", "c");
        $array1 = a("d", "e", "f");
        $array2 = a("g", "h", "i");
        $array = $array0->union($array1, $array2);
        $this->assertTrue($array->equals(a("a", "b", "c", "d", "e", "f", "g", "h", "i")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIntersection ()
    {
        // Using the default comparator.
        $array0 = a("a", "b", "c", "d", "e", "f");
        $array1 = a("g", "b", "h", "d", "i", "f");
        $array = $array0->intersection($array1);
        $this->assertTrue($array->equals(a("b", "d", "f")));

        // Using a custom comparator.
        $array0 = a("a", "b", "c", "d", "e", "f");
        $array1 = a("G", "B", "H", "D", "I", "F");
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $array = $array0->intersection($array1, $comparator);
        $this->assertTrue($array->equals(a("b", "d", "f")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDifference ()
    {
        // Using the default comparator.
        $array0 = a("a", "b", "c", "d", "e", "f");
        $array1 = a("g", "b", "h", "d", "i", "f");
        $array = $array0->difference($array1);
        $this->assertTrue($array->equals(a("a", "c", "e")));

        // Using a custom comparator.
        $array0 = a("a", "b", "c", "d", "e", "f");
        $array1 = a("G", "B", "H", "D", "I", "F");
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $array = $array0->difference($array1, $comparator);
        $this->assertTrue($array->equals(a("a", "c", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSymmetricDifference ()
    {
        // Using the default comparator.
        $array0 = a("a", "b", "c", "d", "e", "f");
        $array1 = a("g", "b", "h", "d", "i", "f");
        $array = $array0->symmetricDifference($array1);
        $this->assertTrue($array->equals(a("a", "c", "e", "g", "h", "i")));

        // Using a custom comparator.
        $array0 = a("a", "b", "c", "d", "e", "f");
        $array1 = a("G", "B", "H", "D", "I", "F");
        $comparator = function ($string0, $string1)
            {
                return $string0->toLowerCase()->equals($string1->toLowerCase());
            };
        $array = $array0->symmetricDifference($array1, $comparator);
        $this->assertTrue($array->equals(a("a", "c", "e", "G", "H", "I")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRepeat ()
    {
        $array = CArrayObject::repeat("a", 5);
        $this->assertTrue($array->equals(a("a", "a", "a", "a", "a")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromArguments ()
    {
        $array = CArrayObject::fromArguments(["a", "b", "c"]);
        $this->assertTrue( $array[0]->equals("a") && $array[1]->equals("b") && $array[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromSplArray ()
    {
        $array = CArrayObject::fromSplArray(SplFixedArray::fromArray([u("a"), u("b"), u("c")]));
        $this->assertTrue( $array[0]->equals("a") && $array[1]->equals("b") && $array[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToSplArray ()
    {
        $array = a("a", "b", "c");
        $splArray = $array->toSplArray();
        $this->assertTrue( $splArray[0]->equals("a") && $splArray[1]->equals("b") && $splArray[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetExists ()
    {
        $array = a("a", "b", "c");
        $this->assertTrue(isset($array[0]));
        $this->assertFalse(isset($array[3]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetGet ()
    {
        $array = a("a", "b", "c");
        $this->assertTrue( $array[0]->equals("a") && $array[1]->equals("b") && $array[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetSet ()
    {
        $array = a("a", "b", "c");
        $array[0] = "d";
        $this->assertTrue($array[0]->equals("d"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCount ()
    {
        $array = a("a", "b", "c");
        $this->assertTrue( count($array) == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClone ()
    {
        $array0 = a("a", "b", "c");
        $array1 = clone $array0;
        $this->assertTrue(
            $array0[0]->equals($array1[0]) && $array0[1]->equals($array1[1]) && $array0[2]->equals($array1[2]) );
        $array0[0] = "d";
        $this->assertTrue($array1[0]->equals("a"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testForeach ()
    {
        $array = a("a", "b", "c");
        $i = 0;
        foreach ($array as $element)
        {
            $this->assertTrue($element->equals($array[$i]));
            $i++;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}

/**
 * @ignore
 */

class ClassForSortingObj
{
    public function __construct ($sortOnValue)
    {
        $this->m_sortOnValue = $sortOnValue;
    }
    public function value ()
    {
        return $this->m_sortOnValue;
    }
    protected $m_sortOnValue;
}
