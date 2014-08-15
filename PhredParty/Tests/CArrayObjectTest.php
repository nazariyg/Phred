<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
        $aArray = a();
        $this->assertTrue( $aArray->length() == 0 );

        $aArray = new CArrayObject();
        $this->assertTrue( $aArray->length() == 0 );
        $aArray = new CArrayObject(10);
        $this->assertTrue( $aArray->length() == 10 );

        $aArray = CArrayObject::makeDim2(2, 3);
        $this->assertTrue(
            $aArray->length() == 2 &&
            $aArray[0]->length() == 3 && $aArray[1]->length() == 3 );

        $aArray = CArrayObject::makeDim3(2, 3, 2);
        $this->assertTrue(
            $aArray->length() == 2 &&
            $aArray[0]->length() == 3 && $aArray[1]->length() == 3 &&
            $aArray[0][0]->length() == 2 && $aArray[0][1]->length() == 2 && $aArray[0][2]->length() == 2 );

        $aArray = CArrayObject::makeDim4(2, 3, 2, 1);
        $this->assertTrue(
            $aArray->length() == 2 &&
            $aArray[0]->length() == 3 && $aArray[1]->length() == 3 &&
            $aArray[0][0]->length() == 2 && $aArray[0][1]->length() == 2 && $aArray[0][2]->length() == 2 &&
            $aArray[0][0][0]->length() == 1 && $aArray[0][0][1]->length() == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromPArray ()
    {
        $mMap = [0 => u("a"), 1 => u("b"), 3 => u("c"), 9 => u("d")];
        $aArray = CArrayObject::fromPArray($mMap);
        $this->assertTrue($aArray->equals(a("a", "b", "c", "d")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToPArray ()
    {
        $aArray = a("a", "b", "c");
        $mMap = $aArray->toPArray();
        $this->assertTrue( $mMap[0]->equals("a") && $mMap[1]->equals("b") && $mMap[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testJoin ()
    {
        $aArray = a("a", "b", "c");
        $sJoined = $aArray->join(", ");
        $this->assertTrue($sJoined->equals("a, b, c"));

        $aArray = a(true, false, true);
        $sJoined = $aArray->join(", ");
        $this->assertTrue($sJoined->equals("1, 0, 1"));

        $aArray = a(12, 34, 56);
        $sJoined = $aArray->join(", ");
        $this->assertTrue($sJoined->equals("12, 34, 56"));

        $aArray = a(1.2, 3.4, 5.6);
        $sJoined = $aArray->join(", ");
        $this->assertTrue($sJoined->equals("1.2, 3.4, 5.6"));

        $aArray = a("a", "b", "c");
        $sJoined = $aArray->join("");
        $this->assertTrue($sJoined->equals("abc"));

        // Special cases.

        $aArray = new CArrayObject();
        $sJoined = $aArray->join("");
        $this->assertTrue($sJoined->equals(""));

        $aArray = new CArrayObject();
        $sJoined = $aArray->join(", ");
        $this->assertTrue($sJoined->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $aArray = new CArrayObject();
        $this->assertTrue( $aArray->length() == 0 );

        $aArray = new CArrayObject(2);
        $this->assertTrue( $aArray->length() == 2 );

        $aArray = a("a", "b", "c");
        $this->assertTrue( $aArray->length() == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $aArray = new CArrayObject();
        $this->assertTrue($aArray->isEmpty());

        $aArray = new CArrayObject(2);
        $this->assertFalse($aArray->isEmpty());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        // Using the default comparator.

        $aArray0 = a("a", "b", "c");
        $aArray1 = a("a", "b", "c");
        $this->assertTrue($aArray0->equals($aArray1));

        $aArray0 = a("a", "b", "c");
        $aArray1 = a("a", "b", "d");
        $this->assertFalse($aArray0->equals($aArray1));

        $aArray0 = a("a", "b", "c");
        $aArray1 = a("a", "b", "C");
        $this->assertFalse($aArray0->equals($aArray1));

        $aArray0 = a("a", "b", "c");
        $aArray1 = a("a", "b", "c", "d");
        $this->assertFalse($aArray0->equals($aArray1));

        $aArray0 = a(1, 2, 3);
        $aArray1 = a(1, 2, 3);
        $this->assertTrue($aArray0->equals($aArray1));

        $aArray0 = a(1.2, 3.4, 5.6);
        $aArray1 = a(1.2, 3.4, 5.6);
        $this->assertTrue($aArray0->equals($aArray1));

        $aArray0 = a(u("a"), u("b"), u("c"));
        $aArray1 = a(u("a"), u("b"), u("c"));
        $this->assertTrue($aArray0->equals($aArray1));

        $aArray0 = new CArrayObject();
        $aArray1 = a("a", "b", "c");
        $this->assertFalse($aArray0->equals($aArray1));

        $aArray0 = a("a", "b", "c");
        $aArray1 = new CArrayObject();
        $this->assertFalse($aArray0->equals($aArray1));

        // Using a custom comparator.
        $aArray0 = a("a", "b", "c");
        $aArray1 = a("A", "B", "C");
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $this->assertTrue($aArray0->equals($aArray1, $fnComparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        // Using the default comparator.

        $aArray0 = a("a", "b", "c");
        $aArray1 = a("a", "b", "c");
        $this->assertTrue( $aArray0->compare($aArray1) == 0 );

        $aArray0 = a("a", "b", "c");
        $aArray1 = a("d", "e", "f");
        $this->assertTrue( $aArray0->compare($aArray1) < 0 );

        $aArray0 = a("d", "e", "f");
        $aArray1 = a("a", "e", "f");
        $this->assertTrue( $aArray0->compare($aArray1) > 0 );

        $aArray0 = a("a", "b");
        $aArray1 = a("a", "b", "c");
        $this->assertTrue( $aArray0->compare($aArray1) < 0 );

        $aArray0 = a(1, 2, 3);
        $aArray1 = a(1, 2, 3);
        $this->assertTrue( $aArray0->compare($aArray1) == 0 );

        $aArray0 = a(1, 2, 3);
        $aArray1 = a(4, 5, 6);
        $this->assertTrue( $aArray0->compare($aArray1) < 0 );

        $aArray0 = a(4, 5, 6);
        $aArray1 = a(3, 5, 6);
        $this->assertTrue( $aArray0->compare($aArray1) > 0 );

        $aArray0 = a(1.2, 3.4, 5.6);
        $aArray1 = a(1.2, 3.4, 5.6);
        $this->assertTrue( $aArray0->compare($aArray1) == 0 );

        $aArray0 = a(u("a"), u("b"), u("c"));
        $aArray1 = a(u("a"), u("b"), u("c"));
        $this->assertTrue( $aArray0->compare($aArray1) == 0 );

        // Using a custom comparator.

        $aArray0 = a("a", "b", "c");
        $aArray1 = a("A", "B", "C");
        $fnComparator = function ($sString0, $sString1)
            {
                return ( $sString0->toLowerCase()->equals($sString1->toLowerCase()) ) ? 0 : -1;
            };
        $this->assertTrue( $aArray0->compare($aArray1, $fnComparator) == 0 );

        $aArray0 = a(1, 3, 5);
        $aArray1 = a(2, 4, 6);
        $fnComparator = function ($iValue0, $iValue1)
            {
                return ( CMathi::isOdd($iValue0) && CMathi::isEven($iValue1) ) ? 1 : -1;
            };
        $this->assertTrue( $aArray0->compare($aArray1, $fnComparator) > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFirst ()
    {
        $aArray = a("a", "b", "c");
        $this->assertTrue($aArray->first()->equals("a"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLast ()
    {
        $aArray = a("a", "b", "c");
        $this->assertTrue($aArray->last()->equals("c"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubar ()
    {
        $aArray = a("a", "b", "c", "d", "e");

        $aSubarray = $aArray->subar(3);
        $this->assertTrue($aSubarray->equals(a("d", "e")));

        $aSubarray = $aArray->subar(1, 3);
        $this->assertTrue($aSubarray->equals(a("b", "c", "d")));

        // Special cases.

        $aSubarray = $aArray->subar(5);
        $this->assertTrue($aSubarray->length() == 0);

        $aSubarray = $aArray->subar(5, 0);
        $this->assertTrue($aSubarray->length() == 0);

        $aSubarray = $aArray->subar(0, 0);
        $this->assertTrue($aSubarray->length() == 0);

        $aSubarray = $aArray->subar(2, 0);
        $this->assertTrue($aSubarray->length() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubarray ()
    {
        $aArray = a("a", "b", "c", "d", "e");

        $aSubarray = $aArray->subarray(1, 4);
        $this->assertTrue($aSubarray->equals(a("b", "c", "d")));

        $aSubarray = $aArray->subarray(3, 5);
        $this->assertTrue($aSubarray->equals(a("d", "e")));

        // Special cases.

        $aSubarray = $aArray->subarray(5, 5);
        $this->assertTrue($aSubarray->length() == 0);

        $aSubarray = $aArray->subarray(0, 0);
        $this->assertTrue($aSubarray->length() == 0);

        $aSubarray = $aArray->subarray(2, 2);
        $this->assertTrue($aSubarray->length() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSlice ()
    {
        $aArray = a("a", "b", "c", "d", "e");

        $aSubarray = $aArray->slice(1, 4);
        $this->assertTrue($aSubarray->equals(a("b", "c", "d")));

        $aSubarray = $aArray->slice(3, 5);
        $this->assertTrue($aSubarray->equals(a("d", "e")));

        // Special cases.

        $aSubarray = $aArray->slice(5, 5);
        $this->assertTrue($aSubarray->length() == 0);

        $aSubarray = $aArray->slice(0, 0);
        $this->assertTrue($aSubarray->length() == 0);

        $aSubarray = $aArray->slice(2, 2);
        $this->assertTrue($aSubarray->length() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $aArray = a("a", "b", "c", "d", "e");

        // Using the default comparator.

        $bFound = $aArray->find("c");
        $this->assertTrue($bFound);

        $iFoundAtPos;
        $bFound = $aArray->find("d", CComparator::EQUALITY, $iFoundAtPos);
        $this->assertTrue($bFound);
        $this->assertTrue( $iFoundAtPos == 3 );

        $bFound = $aArray->find("C");
        $this->assertFalse($bFound);

        $bFound = $aArray->find("f");
        $this->assertFalse($bFound);

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $iFoundAtPos;
        $bFound = $aArray->find(u("C"), $fnComparator, $iFoundAtPos);
        $this->assertTrue($bFound);
        $this->assertTrue( $iFoundAtPos == 2 );

        // Special case.
        $aArray = new CArrayObject();
        $bFound = $aArray->find("a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindScalar ()
    {
        $aArray = CArrayObject::fromSplArray(CArray::fromElements("a", "b", "c", "d", "e"));

        $bFound = $aArray->findScalar("c");
        $this->assertTrue($bFound);

        $iFoundAtPos;
        $bFound = $aArray->findScalar("d", $iFoundAtPos);
        $this->assertTrue($bFound);
        $this->assertTrue( $iFoundAtPos == 3 );

        $bFound = $aArray->findScalar("C");
        $this->assertFalse($bFound);

        $bFound = $aArray->findScalar("f");
        $this->assertFalse($bFound);

        // Special case.
        $aArray = new CArrayObject();
        $bFound = $aArray->findScalar("a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindBinary ()
    {
        $aArray = a("oua", "vnf", "fnf", "aod", "tvi", "nbt", "jny", "vor", "rfd", "cvm", "hyh",
            "kng", "ggo", "uea", "hkb", "qbk", "xla", "uod", "jzi", "chw", "ssy", "olr", "bzl", "oux", "ltk", "bah",
            "khu", "msr", "pqv", "npb", "mtb", "eku", "vcv", "vbv", "wuo", "lrw", "bkw", "ezz", "jtc", "dwk", "dsq",
            "kzu", "oey", "vbi", "seh", "klz", "asj", "gzg", "ccs", "qop");
        $aArrayOrig = clone $aArray;
        $iLen = $aArrayOrig->length();

        // Sort the array first.
        $aArray->sort(CComparator::ORDER_ASC);

        // Using the default comparators.
        for ($i = 0; $i < $iLen; $i += 3)
        {
            $sString = $aArrayOrig[$i];

            $iFoundAtPos0;
            $aArray->find($sString, CComparator::EQUALITY, $iFoundAtPos0);
            $iFoundAtPos1;
            $bFound = $aArray->findBinary($sString, CComparator::ORDER_ASC, $iFoundAtPos1);
            $this->assertTrue($bFound);
            $this->assertTrue( $iFoundAtPos1 == $iFoundAtPos0 );
        }

        // Using custom comparators.
        $fnComparatorEquality = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $fnComparatorOrderAsc = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->compare($sString1->toLowerCase());
            };
        for ($i = 0; $i < $iLen; $i += 5)
        {
            $sString = $aArrayOrig[$i]->toUpperCase();

            $iFoundAtPos0;
            $aArray->find($sString, $fnComparatorEquality, $iFoundAtPos0);
            $iFoundAtPos1;
            $bFound = $aArray->findBinary($sString, $fnComparatorOrderAsc, $iFoundAtPos1);
            $this->assertTrue($bFound);
            $this->assertTrue( $iFoundAtPos1 == $iFoundAtPos0 );
        }

        // Special cases.

        $aArray = a("a", "b");
        $bFound = $aArray->findBinary("a");
        $this->assertTrue($bFound);
        $bFound = $aArray->findBinary("b");
        $this->assertTrue($bFound);
        $bFound = $aArray->findBinary("c");
        $this->assertFalse($bFound);

        $aArray = a("a");
        $bFound = $aArray->findBinary("a");
        $this->assertTrue($bFound);

        $aArray = new CArrayObject();
        $bFound = $aArray->findBinary("a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountElement ()
    {
        $aArray = a("a", "c", "b", "c", "d", "e", "c", "c", "f", "g", "h", "c");

        // Using the default comparator.
        $this->assertTrue( $aArray->countElement("c") == 5 );

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $this->assertTrue( $aArray->countElement(u("C"), $fnComparator) == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetLength ()
    {
        $aArray = a("a", "b");
        $aArray->setLength(3);
        $this->assertTrue( $aArray->length() == 3 && $aArray[0]->equals("a") && $aArray[1]->equals("b") );
        $aArray->setLength(1);
        $this->assertTrue( $aArray->length() == 1 && $aArray[0]->equals("a") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPush ()
    {
        $aArray = a("a", "b", "c");
        $iNewLength = $aArray->push("d");
        $this->assertTrue($aArray->equals(a("a", "b", "c", "d")));
        $this->assertTrue( $iNewLength == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPop ()
    {
        $aArray = a("a", "b", "c");
        $sPoppedString = $aArray->pop();
        $this->assertTrue($aArray->equals(a("a", "b")));
        $this->assertTrue($sPoppedString->equals("c"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPushArray ()
    {
        $aArray = a("a", "b", "c");
        $iNewLength = $aArray->pushArray(a("d", "e"));
        $this->assertTrue($aArray->equals(a("a", "b", "c", "d", "e")));
        $this->assertTrue( $iNewLength == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnshift ()
    {
        $aArray = a("a", "b", "c");
        $iNewLength = $aArray->unshift("d");
        $this->assertTrue($aArray->equals(a("d", "a", "b", "c")));
        $this->assertTrue( $iNewLength == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShift ()
    {
        $aArray = a("a", "b", "c");
        $sPoppedString = $aArray->shift();
        $this->assertTrue($aArray->equals(a("b", "c")));
        $this->assertTrue($sPoppedString->equals("a"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnshiftArray ()
    {
        $aArray = a("a", "b", "c");
        $iNewLength = $aArray->unshiftArray(a("d", "e"));
        $this->assertTrue($aArray->equals(a("d", "e", "a", "b", "c")));
        $this->assertTrue( $iNewLength == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsert ()
    {
        $aArray = a("a", "b", "c", "d", "e");
        $aArray->insert(3, "x");
        $this->assertTrue($aArray->equals(a("a", "b", "c", "x", "d", "e")));

        $aArray = a("a", "b", "c", "d", "e");
        $aArray->insert(0, "x");
        $this->assertTrue($aArray->equals(a("x", "a", "b", "c", "d", "e")));

        // Special cases.

        $aArray = a("a", "b", "c", "d", "e");
        $aArray->insert(5, "x");
        $this->assertTrue($aArray->equals(a("a", "b", "c", "d", "e", "x")));

        $aArray = new CArrayObject();
        $aArray->insert(0, "x");
        $this->assertTrue($aArray->equals(a("x")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsertArray ()
    {
        $aArray = a("a", "b", "c", "d", "e");
        $aArray->insertArray(3, a("x", "y"));
        $this->assertTrue($aArray->equals(a("a", "b", "c", "x", "y", "d", "e")));

        $aArray = a("a", "b", "c", "d", "e");
        $aArray->insertArray(0, a("x", "y"));
        $this->assertTrue($aArray->equals(a("x", "y", "a", "b", "c", "d", "e")));

        // Special cases.

        $aArray = a("a", "b", "c", "d", "e");
        $aArray->insertArray(5, a("x", "y"));
        $this->assertTrue($aArray->equals(a("a", "b", "c", "d", "e", "x", "y")));

        $aArray = new CArrayObject();
        $aArray->insertArray(0, a("x", "y"));
        $this->assertTrue($aArray->equals(a("x", "y")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadStart ()
    {
        $aArray = a("a", "b", "c");
        $aArray->padStart(" ", 5);
        $this->assertTrue($aArray->equals(a(" ", " ", "a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadEnd ()
    {
        $aArray = a("a", "b", "c");
        $aArray->padEnd(" ", 5);
        $this->assertTrue($aArray->equals(a("a", "b", "c", " ", " ")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $aArray = a("a", "b", "c", "d", "e");
        $aArray->remove(2);
        $this->assertTrue($aArray->equals(a("a", "b", "d", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveByValue ()
    {
        // Using the default comparator.

        $aArray = a("a", "b", "c", "d", "e");
        $bAnyRemoval = $aArray->removeByValue("d");
        $this->assertTrue($aArray->equals(a("a", "b", "c", "e")));
        $this->assertTrue($bAnyRemoval);

        $aArray = a("a", "b", "c", "d", "e", "c", "d", "e", "c", "d", "e");
        $bAnyRemoval = $aArray->removeByValue("d");
        $this->assertTrue($aArray->equals(a("a", "b", "c", "e", "c", "e", "c", "e")));
        $this->assertTrue($bAnyRemoval);

        $aArray = a("a", "b", "c");
        $bAnyRemoval = $aArray->removeByValue("d");
        $this->assertFalse($bAnyRemoval);

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $aArray = a("a", "b", "c", "d", "e");
        $bAnyRemoval = $aArray->removeByValue(u("D"), $fnComparator);
        $this->assertTrue($aArray->equals(a("a", "b", "c", "e")));
        $this->assertTrue($bAnyRemoval);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubarray ()
    {
        $aArray = a("a", "b", "c", "d", "e");
        $aArray->removeSubarray(3);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));

        $aArray = a("a", "b", "c", "d", "e");
        $aArray->removeSubarray(1, 3);
        $this->assertTrue($aArray->equals(a("a", "e")));

        // Special cases.

        $aArray = a("a", "b", "c");
        $aArray->removeSubarray(3);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));

        $aArray = a("a", "b", "c");
        $aArray->removeSubarray(3, 0);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));

        $aArray = a("a", "b", "c");
        $aArray->removeSubarray(0, 0);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));

        $aArray = a("a", "b", "c");
        $aArray->removeSubarray(1, 0);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplice ()
    {
        $aArray = a("a", "b", "c", "d", "e");
        $aRemArray = $aArray->splice(3);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));
        $this->assertTrue($aRemArray->equals(a("d", "e")));

        $aArray = a("a", "b", "c", "d", "e");
        $aRemArray = $aArray->splice(1, 3);
        $this->assertTrue($aArray->equals(a("a", "e")));
        $this->assertTrue($aRemArray->equals(a("b", "c", "d")));

        // Special cases.

        $aArray = a("a", "b", "c");
        $aRemArray = $aArray->splice(3);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));
        $this->assertTrue($aRemArray->equals(new CArrayObject()));

        $aArray = a("a", "b", "c");
        $aRemArray = $aArray->splice(3, 0);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));
        $this->assertTrue($aRemArray->equals(new CArrayObject()));

        $aArray = a("a", "b", "c");
        $aRemArray = $aArray->splice(0, 0);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));
        $this->assertTrue($aRemArray->equals(new CArrayObject()));

        $aArray = a("a", "b", "c");
        $aRemArray = $aArray->splice(1, 0);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));
        $this->assertTrue($aRemArray->equals(new CArrayObject()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubarrayByRange ()
    {
        $aArray = a("a", "b", "c", "d", "e");
        $aArray->removeSubarrayByRange(3, 5);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));

        $aArray = a("a", "b", "c", "d", "e");
        $aArray->removeSubarrayByRange(1, 4);
        $this->assertTrue($aArray->equals(a("a", "e")));

        // Special cases.

        $aArray = a("a", "b", "c");
        $aArray->removeSubarrayByRange(3, 3);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));

        $aArray = a("a", "b", "c");
        $aArray->removeSubarrayByRange(0, 0);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));

        $aArray = a("a", "b", "c");
        $aArray->removeSubarrayByRange(1, 1);
        $this->assertTrue($aArray->equals(a("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReverse ()
    {
        $aArray = a("a", "b", "c", "d", "e");
        $aArray->reverse();
        $this->assertTrue($aArray->equals(a("e", "d", "c", "b", "a")));

        $aArray = a("a", "b", "c", "d", "e", "f");
        $aArray->reverse();
        $this->assertTrue($aArray->equals(a("f", "e", "d", "c", "b", "a")));

        // Special cases.

        $aArray = a("a");
        $aArray->reverse();
        $this->assertTrue($aArray->equals(a("a")));

        $aArray = new CArrayObject();
        $aArray->reverse();
        $this->assertTrue($aArray->equals(new CArrayObject()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShuffle ()
    {
        $aArray = a("a", "b", "c", "d", "e", "f", "g", "h", "i");
        $aArrayOrig = clone $aArray;
        $aArray->shuffle();
        $this->assertTrue($aArray->isSubsetOf($aArrayOrig));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSort ()
    {
        $aArray = a("oua", "vnf", "fnf", "aod", "tvi", "nbt", "jny", "vor", "rfd", "cvm", "hyh",
            "kng", "ggo", "uea", "hkb", "qbk", "xla", "uod", "jzi", "chw", "ssy", "olr", "bzl", "oux", "ltk", "bah",
            "khu", "msr", "pqv", "npb", "mtb", "eku", "vcv", "vbv", "wuo", "lrw", "bkw", "ezz", "jtc", "dwk", "dsq",
            "kzu", "oey", "vbi", "seh", "klz", "asj", "gzg", "ccs", "qop");

        $aArray->sort(CComparator::ORDER_ASC);
        $this->assertTrue($aArray->equals(a("aod", "asj", "bah", "bkw", "bzl", "ccs", "chw",
            "cvm", "dsq", "dwk", "eku", "ezz", "fnf", "ggo", "gzg", "hkb", "hyh", "jny", "jtc", "jzi", "khu", "klz",
            "kng", "kzu", "lrw", "ltk", "msr", "mtb", "nbt", "npb", "oey", "olr", "oua", "oux", "pqv", "qbk", "qop",
            "rfd", "seh", "ssy", "tvi", "uea", "uod", "vbi", "vbv", "vcv", "vnf", "vor", "wuo", "xla")));

        $aArray->sort(CComparator::ORDER_DESC);
        $this->assertTrue($aArray->equals(a("xla", "wuo", "vor", "vnf", "vcv", "vbv", "vbi",
            "uod", "uea", "tvi", "ssy", "seh", "rfd", "qop", "qbk", "pqv", "oux", "oua", "olr", "oey", "npb", "nbt",
            "mtb", "msr", "ltk", "lrw", "kzu", "kng", "klz", "khu", "jzi", "jtc", "jny", "hyh", "hkb", "gzg", "ggo",
            "fnf", "ezz", "eku", "dwk", "dsq", "cvm", "chw", "ccs", "bzl", "bkw", "bah", "asj", "aod")));

        $aArray = a(5, 2, 1, 3, 4);
        $aArray->sort(CComparator::ORDER_ASC);
        $this->assertTrue($aArray->equals(a(1, 2, 3, 4, 5)));

        // Special cases.

        $aArray = a("a");
        $aArray->sort(CComparator::ORDER_ASC);
        $this->assertTrue($aArray->equals(a("a")));

        $aArray = new CArrayObject();
        $aArray->sort(CComparator::ORDER_ASC);
        $this->assertTrue($aArray->equals(new CArrayObject()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortOn ()
    {
        $aArray = a(
            new ClassForSortingObj(u("d")),
            new ClassForSortingObj(u("e")),
            new ClassForSortingObj(u("a")),
            new ClassForSortingObj(u("c")),
            new ClassForSortingObj(u("b")));
        $aArray->sortOn("value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $aArray[0]->value()->equals("a") &&
            $aArray[1]->value()->equals("b") &&
            $aArray[2]->value()->equals("c") &&
            $aArray[3]->value()->equals("d") &&
            $aArray[4]->value()->equals("e") );

        $aArray = a(
            new ClassForSortingObj(5),
            new ClassForSortingObj(2),
            new ClassForSortingObj(1),
            new ClassForSortingObj(3),
            new ClassForSortingObj(4));
        $aArray->sortOn("value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $aArray[0]->value() == 1 &&
            $aArray[1]->value() == 2 &&
            $aArray[2]->value() == 3 &&
            $aArray[3]->value() == 4 &&
            $aArray[4]->value() == 5 );

        $aArray = a(
            new ClassForSortingObj(u("d")),
            new ClassForSortingObj(u("e")),
            new ClassForSortingObj(u("a")),
            new ClassForSortingObj(u("c")),
            new ClassForSortingObj(u("b")));
        $aArray->sortOn("value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $aArray[0]->value()->equals("a") &&
            $aArray[1]->value()->equals("b") &&
            $aArray[2]->value()->equals("c") &&
            $aArray[3]->value()->equals("d") &&
            $aArray[4]->value()->equals("e") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStrings ()
    {
        $aArray = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        $aArray->sortUStrings(CUString::COLLATION_DEFAULT);
        $this->assertTrue($aArray->equals(a(
            "a", "A", "b", "B", "c", "C", "d", "D", "e", "E")));

        $aArray = a(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
        $aArray->sortUStrings(CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue($aArray->equals(a(
            "á", "A", "b", "B", "č", "C", "d", "D", "ê", "E")));

        $aArray = a(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
        $aArray->sortUStrings(CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue($aArray->equals(a(
            "?a", "[A", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E")));

        $aArray = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        $aArray->sortUStrings(CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue($aArray->equals(a(
            "A", "a", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsCi ()
    {
        $aArray = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        $aArray->sortUStringsCi(CUString::COLLATION_DEFAULT);
        $this->assertTrue($aArray->equals(a(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));

        $aArray = a(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
        $aArray->sortUStringsCi(CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue($aArray->equals(a(
            "á", "A", "B", "b", "C", "č", "D", "d", "E", "ê")));

        $aArray = a(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
        $aArray->sortUStringsCi(CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue($aArray->equals(a(
            "?a", "[A", ",B", "(b", "!C", " c", ";D", ".d", ":E", "\"e")));

        $aArray = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        $aArray->sortUStringsCi(CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue($aArray->equals(a(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsNat ()
    {
        $aArray = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        $aArray->sortUStringsNat(CUString::COLLATION_DEFAULT);
        $this->assertTrue($aArray->equals(a(
            "a", "A3", "A20", "A100", "b", "B", "c", "C", "d", "D", "e", "E")));

        $aArray = a(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A3", "A20", "A100");
        $aArray->sortUStringsNat(CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue($aArray->equals(a(
            "á", "A3", "A20", "A100", "b", "B", "č", "C", "d", "D", "ê", "E")));

        $aArray = a(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A3", "[A20", "[A100");
        $aArray->sortUStringsNat(CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue($aArray->equals(a(
            "?a", "[A3", "[A20", "[A100", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E")));

        $aArray = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        $aArray->sortUStringsNat(CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue($aArray->equals(a(
            "a", "A3", "A20", "A100", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsNatCi ()
    {
        $aArray = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        $aArray->sortUStringsNatCi(CUString::COLLATION_DEFAULT);
        $this->assertTrue($aArray->equals(a(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));

        $aArray = a(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A3", "A20", "A100");
        $aArray->sortUStringsNatCi(CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue($aArray->equals(a(
            "á", "A3", "A20", "A100", "b", "B", "C", "č", "d", "D", "ê", "E")));

        $aArray = a(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A3", "[A20", "[A100");
        $aArray->sortUStringsNatCi(CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue($aArray->equals(a(
            "?a", "[A3", "[A20", "[A100", "(b", ",B", "!C", " c", ".d", ";D", "\"e", ":E")));

        $aArray = a(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        $aArray->sortUStringsNatCi(CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue($aArray->equals(a(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $aArray = a(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $aArray = $aArray->filter(function ($iElement)
            {
                return CMathi::isEven($iElement);
            });
        $this->assertTrue($aArray->equals(a(2, 4, 6, 8, 10)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnique ()
    {
        // Using the default comparator.
        $aArray = a("a", "b", "c", "d", "e", "a", "c", "e");
        $aArray = $aArray->unique();
        $this->assertTrue($aArray->equals(a("a", "b", "c", "d", "e")));

        // Using a custom comparator.
        $aArray = a("A", "b", "C", "d", "E", "a", "c", "e");
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $aArray = $aArray->unique($fnComparator);
        $this->assertTrue($aArray->equals(a("A", "b", "C", "d", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testElementsSum ()
    {
        $aArray = a(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $this->assertTrue( $aArray->elementsSum() == 55 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testElementsProduct ()
    {
        $aArray = a(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $this->assertTrue( $aArray->elementsProduct() == 3628800 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsSubsetOf ()
    {
        // Using the default comparator.

        $aArray = a("a", "b", "c", "a", "b", "c", "a", "b", "c");
        $this->assertTrue($aArray->isSubsetOf(a("a", "b", "c")));

        $aArray = a("a", "b", "c", "a", "b", "c", "a", "d", "b", "c");
        $this->assertFalse($aArray->isSubsetOf(a("a", "b", "c")));

        // Using a custom comparator.
        $aArray = a("a", "b", "c", "a", "b", "c", "a", "b", "c");
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $this->assertTrue($aArray->isSubsetOf(a("A", "B", "C"), $fnComparator));

        // Special case.
        $aArray = new CArrayObject();
        $this->assertFalse($aArray->isSubsetOf(a("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnion ()
    {
        $aArray0 = a("a", "b", "c");
        $aArray1 = a("d", "e", "f");
        $aArray2 = a("g", "h", "i");
        $aArray = $aArray0->union($aArray1, $aArray2);
        $this->assertTrue($aArray->equals(a("a", "b", "c", "d", "e", "f", "g", "h", "i")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIntersection ()
    {
        // Using the default comparator.
        $aArray0 = a("a", "b", "c", "d", "e", "f");
        $aArray1 = a("g", "b", "h", "d", "i", "f");
        $aArray = $aArray0->intersection($aArray1);
        $this->assertTrue($aArray->equals(a("b", "d", "f")));

        // Using a custom comparator.
        $aArray0 = a("a", "b", "c", "d", "e", "f");
        $aArray1 = a("G", "B", "H", "D", "I", "F");
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $aArray = $aArray0->intersection($aArray1, $fnComparator);
        $this->assertTrue($aArray->equals(a("b", "d", "f")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDifference ()
    {
        // Using the default comparator.
        $aArray0 = a("a", "b", "c", "d", "e", "f");
        $aArray1 = a("g", "b", "h", "d", "i", "f");
        $aArray = $aArray0->difference($aArray1);
        $this->assertTrue($aArray->equals(a("a", "c", "e")));

        // Using a custom comparator.
        $aArray0 = a("a", "b", "c", "d", "e", "f");
        $aArray1 = a("G", "B", "H", "D", "I", "F");
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $aArray = $aArray0->difference($aArray1, $fnComparator);
        $this->assertTrue($aArray->equals(a("a", "c", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSymmetricDifference ()
    {
        // Using the default comparator.
        $aArray0 = a("a", "b", "c", "d", "e", "f");
        $aArray1 = a("g", "b", "h", "d", "i", "f");
        $aArray = $aArray0->symmetricDifference($aArray1);
        $this->assertTrue($aArray->equals(a("a", "c", "e", "g", "h", "i")));

        // Using a custom comparator.
        $aArray0 = a("a", "b", "c", "d", "e", "f");
        $aArray1 = a("G", "B", "H", "D", "I", "F");
        $fnComparator = function ($sString0, $sString1)
            {
                return $sString0->toLowerCase()->equals($sString1->toLowerCase());
            };
        $aArray = $aArray0->symmetricDifference($aArray1, $fnComparator);
        $this->assertTrue($aArray->equals(a("a", "c", "e", "G", "H", "I")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRepeat ()
    {
        $aArray = CArrayObject::repeat("a", 5);
        $this->assertTrue($aArray->equals(a("a", "a", "a", "a", "a")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromArguments ()
    {
        $aArray = CArrayObject::fromArguments(["a", "b", "c"]);
        $this->assertTrue( $aArray[0]->equals("a") && $aArray[1]->equals("b") && $aArray[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromSplArray ()
    {
        $aArray = CArrayObject::fromSplArray(SplFixedArray::fromArray([u("a"), u("b"), u("c")]));
        $this->assertTrue( $aArray[0]->equals("a") && $aArray[1]->equals("b") && $aArray[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToSplArray ()
    {
        $aArray = a("a", "b", "c");
        $aSplArray = $aArray->toSplArray();
        $this->assertTrue( $aSplArray[0]->equals("a") && $aSplArray[1]->equals("b") && $aSplArray[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetExists ()
    {
        $aArray = a("a", "b", "c");
        $this->assertTrue(isset($aArray[0]));
        $this->assertFalse(isset($aArray[3]));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetGet ()
    {
        $aArray = a("a", "b", "c");
        $this->assertTrue( $aArray[0]->equals("a") && $aArray[1]->equals("b") && $aArray[2]->equals("c") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testOffsetSet ()
    {
        $aArray = a("a", "b", "c");
        $aArray[0] = "d";
        $this->assertTrue($aArray[0]->equals("d"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCount ()
    {
        $aArray = a("a", "b", "c");
        $this->assertTrue( count($aArray) == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClone ()
    {
        $aArray0 = a("a", "b", "c");
        $aArray1 = clone $aArray0;
        $this->assertTrue(
            $aArray0[0]->equals($aArray1[0]) && $aArray0[1]->equals($aArray1[1]) && $aArray0[2]->equals($aArray1[2]) );
        $aArray0[0] = "d";
        $this->assertTrue($aArray1[0]->equals("a"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testForeach ()
    {
        $aArray = a("a", "b", "c");
        $i = 0;
        foreach ($aArray as $sElement)
        {
            $this->assertTrue($sElement->equals($aArray[$i]));
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
    public function __construct ($xSortOnValue)
    {
        $this->m_xSortOnValue = $xSortOnValue;
    }
    public function value ()
    {
        return $this->m_xSortOnValue;
    }
    protected $m_xSortOnValue;
}
