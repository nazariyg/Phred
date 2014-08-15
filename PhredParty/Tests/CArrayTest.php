<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CArrayTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $aArray = CArray::make();
        $this->assertTrue( $aArray->getSize() == 0 );
        $aArray = CArray::make(10);
        $this->assertTrue( $aArray->getSize() == 10 );

        $aArray = CArray::makeDim2(2, 3);
        $this->assertTrue(
            $aArray->getSize() == 2 &&
            $aArray[0]->getSize() == 3 && $aArray[1]->getSize() == 3 );

        $aArray = CArray::makeDim3(2, 3, 2);
        $this->assertTrue(
            $aArray->getSize() == 2 &&
            $aArray[0]->getSize() == 3 && $aArray[1]->getSize() == 3 &&
            $aArray[0][0]->getSize() == 2 && $aArray[0][1]->getSize() == 2 && $aArray[0][2]->getSize() == 2 );

        $aArray = CArray::makeDim4(2, 3, 2, 1);
        $this->assertTrue(
            $aArray->getSize() == 2 &&
            $aArray[0]->getSize() == 3 && $aArray[1]->getSize() == 3 &&
            $aArray[0][0]->getSize() == 2 && $aArray[0][1]->getSize() == 2 && $aArray[0][2]->getSize() == 2 &&
            $aArray[0][0][0]->getSize() == 1 && $aArray[0][0][1]->getSize() == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMakeCopy ()
    {
        $aArray0 = CArray::make(3);
        $aArray0[0] = "a";
        $aArray0[1] = "b";
        $aArray0[2] = "c";
        $aArray1 = CArray::makeCopy($aArray0);
        $this->assertTrue(CArray::equals($aArray0, $aArray1));
        $aArray0[0] = "d";
        $this->assertTrue( $aArray1[0] === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromPArray ()
    {
        $mMap = [0 => "a", 1 => "b", 3 => "c", 9 => "d"];
        $aArray = CArray::fromPArray($mMap);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "d")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromElements ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $this->assertTrue( $aArray[0] === "a" && $aArray[1] === "b" && $aArray[2] === "c" );

        $aArray = CArray::fe("a", "b", "c");
        $this->assertTrue( $aArray[0] === "a" && $aArray[1] === "b" && $aArray[2] === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToPArray ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $mMap = CArray::toPArray($aArray);
        $this->assertTrue( $mMap[0] === "a" && $mMap[1] === "b" && $mMap[2] === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testJoin ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $sJoined = CArray::join($aArray, ", ");
        $this->assertTrue( $sJoined === "a, b, c" );

        $aArray = CArray::fromElements(true, false, true);
        $sJoined = CArray::join($aArray, ", ");
        $this->assertTrue( $sJoined === "1, 0, 1" );

        $aArray = CArray::fromElements(12, 34, 56);
        $sJoined = CArray::join($aArray, ", ");
        $this->assertTrue( $sJoined === "12, 34, 56" );

        $aArray = CArray::fromElements(1.2, 3.4, 5.6);
        $sJoined = CArray::join($aArray, ", ");
        $this->assertTrue( $sJoined === "1.2, 3.4, 5.6" );

        $aArray = CArray::fromElements("a", "b", "c");
        $sJoined = CArray::join($aArray, "");
        $this->assertTrue( $sJoined === "abc" );

        // Special cases.

        $aArray = CArray::make();
        $sJoined = CArray::join($aArray, "");
        $this->assertTrue( $sJoined === "" );

        $aArray = CArray::make();
        $sJoined = CArray::join($aArray, ", ");
        $this->assertTrue( $sJoined === "" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $aArray = CArray::make();
        $this->assertTrue( CArray::length($aArray) == 0 );

        $aArray = CArray::make(2);
        $this->assertTrue( CArray::length($aArray) == 2 );

        $aArray = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::length($aArray) == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $aArray = CArray::make();
        $this->assertTrue(CArray::isEmpty($aArray));

        $aArray = CArray::make(2);
        $this->assertFalse(CArray::isEmpty($aArray));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        // Using the default comparator.

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("a", "b", "c");
        $this->assertTrue(CArray::equals($aArray0, $aArray1));

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("a", "b", "d");
        $this->assertFalse(CArray::equals($aArray0, $aArray1));

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("a", "b", "C");
        $this->assertFalse(CArray::equals($aArray0, $aArray1));

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("a", "b", "c", "d");
        $this->assertFalse(CArray::equals($aArray0, $aArray1));

        $aArray0 = CArray::fromElements(1, 2, 3);
        $aArray1 = CArray::fromElements(1, 2, 3);
        $this->assertTrue(CArray::equals($aArray0, $aArray1));

        $aArray0 = CArray::fromElements(1.2, 3.4, 5.6);
        $aArray1 = CArray::fromElements(1.2, 3.4, 5.6);
        $this->assertTrue(CArray::equals($aArray0, $aArray1));

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements(u("a"), u("b"), u("c"));
        $this->assertTrue(CArray::equals($aArray0, $aArray1));

        $aArray0 = CArray::make();
        $aArray1 = CArray::fromElements("a", "b", "c");
        $this->assertFalse(CArray::equals($aArray0, $aArray1));

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::make();
        $this->assertFalse(CArray::equals($aArray0, $aArray1));

        // Using a custom comparator.
        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("A", "B", "C");
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $this->assertTrue(CArray::equals($aArray0, $aArray1, $fnComparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        // Using the default comparator.

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::compare($aArray0, $aArray1) == 0 );

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("d", "e", "f");
        $this->assertTrue( CArray::compare($aArray0, $aArray1) < 0 );

        $aArray0 = CArray::fromElements("d", "e", "f");
        $aArray1 = CArray::fromElements("a", "e", "f");
        $this->assertTrue( CArray::compare($aArray0, $aArray1) > 0 );

        $aArray0 = CArray::fromElements("a", "b");
        $aArray1 = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::compare($aArray0, $aArray1) < 0 );

        $aArray0 = CArray::fromElements(1, 2, 3);
        $aArray1 = CArray::fromElements(1, 2, 3);
        $this->assertTrue( CArray::compare($aArray0, $aArray1) == 0 );

        $aArray0 = CArray::fromElements(1, 2, 3);
        $aArray1 = CArray::fromElements(4, 5, 6);
        $this->assertTrue( CArray::compare($aArray0, $aArray1) < 0 );

        $aArray0 = CArray::fromElements(4, 5, 6);
        $aArray1 = CArray::fromElements(3, 5, 6);
        $this->assertTrue( CArray::compare($aArray0, $aArray1) > 0 );

        $aArray0 = CArray::fromElements(1.2, 3.4, 5.6);
        $aArray1 = CArray::fromElements(1.2, 3.4, 5.6);
        $this->assertTrue( CArray::compare($aArray0, $aArray1) == 0 );

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements(u("a"), u("b"), u("c"));
        $this->assertTrue( CArray::compare($aArray0, $aArray1) == 0 );

        // Using a custom comparator.

        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("A", "B", "C");
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) ) ? 0 : -1;
            };
        $this->assertTrue( CArray::compare($aArray0, $aArray1, $fnComparator) == 0 );

        $aArray0 = CArray::fromElements(1, 3, 5);
        $aArray1 = CArray::fromElements(2, 4, 6);
        $fnComparator = function ($iValue0, $iValue1)
            {
                return ( CMathi::isOdd($iValue0) && CMathi::isEven($iValue1) ) ? 1 : -1;
            };
        $this->assertTrue( CArray::compare($aArray0, $aArray1, $fnComparator) > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFirst ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::first($aArray) === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLast ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::last($aArray) === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubar ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");

        $aSubarray = CArray::subar($aArray, 3);
        $this->assertTrue(CArray::equals($aSubarray, CArray::fromElements("d", "e")));

        $aSubarray = CArray::subar($aArray, 1, 3);
        $this->assertTrue(CArray::equals($aSubarray, CArray::fromElements("b", "c", "d")));

        // Special cases.

        $aSubarray = CArray::subar($aArray, 5);
        $this->assertTrue($aSubarray->getSize() == 0);

        $aSubarray = CArray::subar($aArray, 5, 0);
        $this->assertTrue($aSubarray->getSize() == 0);

        $aSubarray = CArray::subar($aArray, 0, 0);
        $this->assertTrue($aSubarray->getSize() == 0);

        $aSubarray = CArray::subar($aArray, 2, 0);
        $this->assertTrue($aSubarray->getSize() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubarray ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");

        $aSubarray = CArray::subarray($aArray, 1, 4);
        $this->assertTrue(CArray::equals($aSubarray, CArray::fromElements("b", "c", "d")));

        $aSubarray = CArray::subarray($aArray, 3, 5);
        $this->assertTrue(CArray::equals($aSubarray, CArray::fromElements("d", "e")));

        // Special cases.

        $aSubarray = CArray::subarray($aArray, 5, 5);
        $this->assertTrue($aSubarray->getSize() == 0);

        $aSubarray = CArray::subarray($aArray, 0, 0);
        $this->assertTrue($aSubarray->getSize() == 0);

        $aSubarray = CArray::subarray($aArray, 2, 2);
        $this->assertTrue($aSubarray->getSize() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSlice ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");

        $aSubarray = CArray::slice($aArray, 1, 4);
        $this->assertTrue(CArray::equals($aSubarray, CArray::fromElements("b", "c", "d")));

        $aSubarray = CArray::slice($aArray, 3, 5);
        $this->assertTrue(CArray::equals($aSubarray, CArray::fromElements("d", "e")));

        // Special cases.

        $aSubarray = CArray::slice($aArray, 5, 5);
        $this->assertTrue($aSubarray->getSize() == 0);

        $aSubarray = CArray::slice($aArray, 0, 0);
        $this->assertTrue($aSubarray->getSize() == 0);

        $aSubarray = CArray::slice($aArray, 2, 2);
        $this->assertTrue($aSubarray->getSize() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");

        // Using the default comparator.

        $bFound = CArray::find($aArray, "c");
        $this->assertTrue($bFound);

        $iFoundAtPos;
        $bFound = CArray::find($aArray, "d", CComparator::EQUALITY, $iFoundAtPos);
        $this->assertTrue($bFound);
        $this->assertTrue( $iFoundAtPos == 3 );

        $bFound = CArray::find($aArray, "C");
        $this->assertFalse($bFound);

        $bFound = CArray::find($aArray, "f");
        $this->assertFalse($bFound);

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $iFoundAtPos;
        $bFound = CArray::find($aArray, "C", $fnComparator, $iFoundAtPos);
        $this->assertTrue($bFound);
        $this->assertTrue( $iFoundAtPos == 2 );

        // Special case.
        $aArray = CArray::make();
        $bFound = CArray::find($aArray, "a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindScalar ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");

        $bFound = CArray::findScalar($aArray, "c");
        $this->assertTrue($bFound);

        $iFoundAtPos;
        $bFound = CArray::findScalar($aArray, "d", $iFoundAtPos);
        $this->assertTrue($bFound);
        $this->assertTrue( $iFoundAtPos == 3 );

        $bFound = CArray::findScalar($aArray, "C");
        $this->assertFalse($bFound);

        $bFound = CArray::findScalar($aArray, "f");
        $this->assertFalse($bFound);

        // Special case.
        $aArray = CArray::make();
        $bFound = CArray::findScalar($aArray, "a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindBinary ()
    {
        $aArray = CArray::fromElements("oua", "vnf", "fnf", "aod", "tvi", "nbt", "jny", "vor", "rfd", "cvm", "hyh",
            "kng", "ggo", "uea", "hkb", "qbk", "xla", "uod", "jzi", "chw", "ssy", "olr", "bzl", "oux", "ltk", "bah",
            "khu", "msr", "pqv", "npb", "mtb", "eku", "vcv", "vbv", "wuo", "lrw", "bkw", "ezz", "jtc", "dwk", "dsq",
            "kzu", "oey", "vbi", "seh", "klz", "asj", "gzg", "ccs", "qop");
        $aArrayOrig = CArray::makeCopy($aArray);
        $iLen = CArray::length($aArrayOrig);

        // Sort the array first.
        CArray::sort($aArray, CComparator::ORDER_ASC);

        // Using the default comparators.
        for ($i = 0; $i < $iLen; $i += 3)
        {
            $sString = $aArrayOrig[$i];

            $iFoundAtPos0;
            CArray::find($aArray, $sString, CComparator::EQUALITY, $iFoundAtPos0);
            $iFoundAtPos1;
            $bFound = CArray::findBinary($aArray, $sString, CComparator::ORDER_ASC, $iFoundAtPos1);
            $this->assertTrue($bFound);
            $this->assertTrue( $iFoundAtPos1 == $iFoundAtPos0 );
        }

        // Using custom comparators.
        $fnComparatorEquality = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $fnComparatorOrderAsc = function ($sString0, $sString1)
            {
                return CString::compare(CString::toLowerCase($sString0), CString::toLowerCase($sString1));
            };
        for ($i = 0; $i < $iLen; $i += 5)
        {
            $sString = CString::toUpperCase($aArrayOrig[$i]);

            $iFoundAtPos0;
            CArray::find($aArray, $sString, $fnComparatorEquality, $iFoundAtPos0);
            $iFoundAtPos1;
            $bFound = CArray::findBinary($aArray, $sString, $fnComparatorOrderAsc, $iFoundAtPos1);
            $this->assertTrue($bFound);
            $this->assertTrue( $iFoundAtPos1 == $iFoundAtPos0 );
        }

        // Special cases.

        $aArray = CArray::fromElements("a", "b");
        $bFound = CArray::findBinary($aArray, "a");
        $this->assertTrue($bFound);
        $bFound = CArray::findBinary($aArray, "b");
        $this->assertTrue($bFound);
        $bFound = CArray::findBinary($aArray, "c");
        $this->assertFalse($bFound);

        $aArray = CArray::fromElements("a");
        $bFound = CArray::findBinary($aArray, "a");
        $this->assertTrue($bFound);

        $aArray = CArray::make();
        $bFound = CArray::findBinary($aArray, "a");
        $this->assertFalse($bFound);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountElement ()
    {
        $aArray = CArray::fromElements("a", "c", "b", "c", "d", "e", "c", "c", "f", "g", "h", "c");

        // Using the default comparator.
        $this->assertTrue( CArray::countElement($aArray, "c") == 5 );

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $this->assertTrue( CArray::countElement($aArray, "C", $fnComparator) == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetLength ()
    {
        $aArray = CArray::fromElements("a", "b");
        CArray::setLength($aArray, 3);
        $this->assertTrue( $aArray->getSize() == 3 && $aArray[0] === "a" && $aArray[1] === "b" );
        CArray::setLength($aArray, 1);
        $this->assertTrue( $aArray->getSize() == 1 && $aArray[0] === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPush ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $iNewLength = CArray::push($aArray, "d");
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "d")));
        $this->assertTrue( $iNewLength == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPop ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $sPoppedString = CArray::pop($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b")));
        $this->assertTrue( $sPoppedString === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPushArray ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $iNewLength = CArray::pushArray($aArray, CArray::fromElements("d", "e"));
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "d", "e")));
        $this->assertTrue( $iNewLength == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnshift ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $iNewLength = CArray::unshift($aArray, "d");
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("d", "a", "b", "c")));
        $this->assertTrue( $iNewLength == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShift ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $sPoppedString = CArray::shift($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("b", "c")));
        $this->assertTrue( $sPoppedString === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnshiftArray ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        $iNewLength = CArray::unshiftArray($aArray, CArray::fromElements("d", "e"));
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("d", "e", "a", "b", "c")));
        $this->assertTrue( $iNewLength == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsert ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insert($aArray, 3, "x");
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "x", "d", "e")));

        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insert($aArray, 0, "x");
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("x", "a", "b", "c", "d", "e")));

        // Special cases.

        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insert($aArray, 5, "x");
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "d", "e", "x")));

        $aArray = CArray::make();
        CArray::insert($aArray, 0, "x");
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("x")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsertArray ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insertArray($aArray, 3, CArray::fromElements("x", "y"));
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "x", "y", "d", "e")));

        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insertArray($aArray, 0, CArray::fromElements("x", "y"));
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("x", "y", "a", "b", "c", "d", "e")));

        // Special cases.

        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insertArray($aArray, 5, CArray::fromElements("x", "y"));
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "d", "e", "x", "y")));

        $aArray = CArray::make();
        CArray::insertArray($aArray, 0, CArray::fromElements("x", "y"));
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("x", "y")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadStart ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        CArray::padStart($aArray, " ", 5);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(" ", " ", "a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadEnd ()
    {
        $aArray = CArray::fromElements("a", "b", "c");
        CArray::padEnd($aArray, " ", 5);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", " ", " ")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::remove($aArray, 2);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "d", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveByValue ()
    {
        // Using the default comparator.

        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        $bAnyRemoval = CArray::removeByValue($aArray, "d");
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "e")));
        $this->assertTrue($bAnyRemoval);

        $aArray = CArray::fromElements("a", "b", "c", "d", "e", "c", "d", "e", "c", "d", "e");
        $bAnyRemoval = CArray::removeByValue($aArray, "d");
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "e", "c", "e", "c", "e")));
        $this->assertTrue($bAnyRemoval);

        $aArray = CArray::fromElements("a", "b", "c");
        $bAnyRemoval = CArray::removeByValue($aArray, "d");
        $this->assertFalse($bAnyRemoval);

        // Using a custom comparator.
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        $bAnyRemoval = CArray::removeByValue($aArray, "D", $fnComparator);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "e")));
        $this->assertTrue($bAnyRemoval);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubarray ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::removeSubarray($aArray, 3);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));

        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::removeSubarray($aArray, 1, 3);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "e")));

        // Special cases.

        $aArray = CArray::fromElements("a", "b", "c");
        CArray::removeSubarray($aArray, 3);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));

        $aArray = CArray::fromElements("a", "b", "c");
        CArray::removeSubarray($aArray, 3, 0);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));

        $aArray = CArray::fromElements("a", "b", "c");
        CArray::removeSubarray($aArray, 0, 0);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));

        $aArray = CArray::fromElements("a", "b", "c");
        CArray::removeSubarray($aArray, 1, 0);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplice ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        $aRemArray = CArray::splice($aArray, 3);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($aRemArray, CArray::fromElements("d", "e")));

        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        $aRemArray = CArray::splice($aArray, 1, 3);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "e")));
        $this->assertTrue(CArray::equals($aRemArray, CArray::fromElements("b", "c", "d")));

        // Special cases.

        $aArray = CArray::fromElements("a", "b", "c");
        $aRemArray = CArray::splice($aArray, 3);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($aRemArray, CArray::make()));

        $aArray = CArray::fromElements("a", "b", "c");
        $aRemArray = CArray::splice($aArray, 3, 0);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($aRemArray, CArray::make()));

        $aArray = CArray::fromElements("a", "b", "c");
        $aRemArray = CArray::splice($aArray, 0, 0);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($aRemArray, CArray::make()));

        $aArray = CArray::fromElements("a", "b", "c");
        $aRemArray = CArray::splice($aArray, 1, 0);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($aRemArray, CArray::make()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubarrayByRange ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::removeSubarrayByRange($aArray, 3, 5);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));

        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::removeSubarrayByRange($aArray, 1, 4);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "e")));

        // Special cases.

        $aArray = CArray::fromElements("a", "b", "c");
        CArray::removeSubarrayByRange($aArray, 3, 3);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));

        $aArray = CArray::fromElements("a", "b", "c");
        CArray::removeSubarrayByRange($aArray, 0, 0);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));

        $aArray = CArray::fromElements("a", "b", "c");
        CArray::removeSubarrayByRange($aArray, 1, 1);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReverse ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::reverse($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("e", "d", "c", "b", "a")));

        $aArray = CArray::fromElements("a", "b", "c", "d", "e", "f");
        CArray::reverse($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("f", "e", "d", "c", "b", "a")));

        // Special cases.

        $aArray = CArray::fromElements("a");
        CArray::reverse($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a")));

        $aArray = CArray::make();
        CArray::reverse($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::make()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShuffle ()
    {
        $aArray = CArray::fromElements("a", "b", "c", "d", "e", "f", "g", "h", "i");
        $aArrayOrig = CArray::makeCopy($aArray);
        CArray::shuffle($aArray);
        $this->assertTrue(CArray::isSubsetOf($aArray, $aArrayOrig));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSort ()
    {
        $aArray = CArray::fromElements("oua", "vnf", "fnf", "aod", "tvi", "nbt", "jny", "vor", "rfd", "cvm", "hyh",
            "kng", "ggo", "uea", "hkb", "qbk", "xla", "uod", "jzi", "chw", "ssy", "olr", "bzl", "oux", "ltk", "bah",
            "khu", "msr", "pqv", "npb", "mtb", "eku", "vcv", "vbv", "wuo", "lrw", "bkw", "ezz", "jtc", "dwk", "dsq",
            "kzu", "oey", "vbi", "seh", "klz", "asj", "gzg", "ccs", "qop");

        CArray::sort($aArray, CComparator::ORDER_ASC);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("aod", "asj", "bah", "bkw", "bzl", "ccs", "chw",
            "cvm", "dsq", "dwk", "eku", "ezz", "fnf", "ggo", "gzg", "hkb", "hyh", "jny", "jtc", "jzi", "khu", "klz",
            "kng", "kzu", "lrw", "ltk", "msr", "mtb", "nbt", "npb", "oey", "olr", "oua", "oux", "pqv", "qbk", "qop",
            "rfd", "seh", "ssy", "tvi", "uea", "uod", "vbi", "vbv", "vcv", "vnf", "vor", "wuo", "xla")));

        CArray::sort($aArray, CComparator::ORDER_DESC);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("xla", "wuo", "vor", "vnf", "vcv", "vbv", "vbi",
            "uod", "uea", "tvi", "ssy", "seh", "rfd", "qop", "qbk", "pqv", "oux", "oua", "olr", "oey", "npb", "nbt",
            "mtb", "msr", "ltk", "lrw", "kzu", "kng", "klz", "khu", "jzi", "jtc", "jny", "hyh", "hkb", "gzg", "ggo",
            "fnf", "ezz", "eku", "dwk", "dsq", "cvm", "chw", "ccs", "bzl", "bkw", "bah", "asj", "aod")));

        $aArray = CArray::fromElements(5, 2, 1, 3, 4);
        CArray::sort($aArray, CComparator::ORDER_ASC);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(1, 2, 3, 4, 5)));

        // Special cases.

        $aArray = CArray::fromElements("a");
        CArray::sort($aArray, CComparator::ORDER_ASC);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a")));

        $aArray = CArray::make();
        CArray::sort($aArray, CComparator::ORDER_ASC);
        $this->assertTrue(CArray::equals($aArray, CArray::make()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortOn ()
    {
        $aArray = CArray::fromElements(
            new ClassForSorting("d"),
            new ClassForSorting("e"),
            new ClassForSorting("a"),
            new ClassForSorting("c"),
            new ClassForSorting("b"));
        CArray::sortOn($aArray, "value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $aArray[0]->value() === "a" &&
            $aArray[1]->value() === "b" &&
            $aArray[2]->value() === "c" &&
            $aArray[3]->value() === "d" &&
            $aArray[4]->value() === "e" );

        $aArray = CArray::fromElements(
            new ClassForSorting(5),
            new ClassForSorting(2),
            new ClassForSorting(1),
            new ClassForSorting(3),
            new ClassForSorting(4));
        CArray::sortOn($aArray, "value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $aArray[0]->value() == 1 &&
            $aArray[1]->value() == 2 &&
            $aArray[2]->value() == 3 &&
            $aArray[3]->value() == 4 &&
            $aArray[4]->value() == 5 );

        $aArray = CArray::fromElements(
            new ClassForSorting(u("d")),
            new ClassForSorting(u("e")),
            new ClassForSorting(u("a")),
            new ClassForSorting(u("c")),
            new ClassForSorting(u("b")));
        CArray::sortOn($aArray, "value", CComparator::ORDER_ASC);
        $this->assertTrue(
            CUString::equals($aArray[0]->value(), "a") &&
            CUString::equals($aArray[1]->value(), "b") &&
            CUString::equals($aArray[2]->value(), "c") &&
            CUString::equals($aArray[3]->value(), "d") &&
            CUString::equals($aArray[4]->value(), "e") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortStrings ()
    {
        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortStrings($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "A", "B", "C", "D", "E", "a", "b", "c", "d", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortStringsCi ()
    {
        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortStringsCi($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortStringsNat ()
    {
        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A100", "A20", "A3");
        CArray::sortStringsNat($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "A3", "A20", "A100", "B", "C", "D", "E", "a", "b", "c", "d", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortStringsNatCi ()
    {
        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A100", "A20", "A3");
        CArray::sortStringsNatCi($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStrings ()
    {
        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortUStrings($aArray, CUString::COLLATION_DEFAULT);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A", "b", "B", "c", "C", "d", "D", "e", "E")));

        $aArray = CArray::fromElements(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
        CArray::sortUStrings($aArray, CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "á", "A", "b", "B", "č", "C", "d", "D", "ê", "E")));

        $aArray = CArray::fromElements(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
        CArray::sortUStrings($aArray, CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "?a", "[A", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E")));

        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortUStrings($aArray, CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "A", "a", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsCi ()
    {
        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortUStringsCi($aArray, CUString::COLLATION_DEFAULT);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));

        $aArray = CArray::fromElements(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
        CArray::sortUStringsCi($aArray, CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "á", "A", "B", "b", "C", "č", "D", "d", "E", "ê")));

        $aArray = CArray::fromElements(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
        CArray::sortUStringsCi($aArray, CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "?a", "[A", ",B", "(b", "!C", " c", ";D", ".d", ":E", "\"e")));

        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortUStringsCi($aArray, CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsNat ()
    {
        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        CArray::sortUStringsNat($aArray, CUString::COLLATION_DEFAULT);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A3", "A20", "A100", "b", "B", "c", "C", "d", "D", "e", "E")));

        $aArray = CArray::fromElements(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A3", "A20", "A100");
        CArray::sortUStringsNat($aArray, CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "á", "A3", "A20", "A100", "b", "B", "č", "C", "d", "D", "ê", "E")));

        $aArray = CArray::fromElements(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A3", "[A20", "[A100");
        CArray::sortUStringsNat($aArray, CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "?a", "[A3", "[A20", "[A100", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E")));

        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        CArray::sortUStringsNat($aArray, CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A3", "A20", "A100", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsNatCi ()
    {
        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        CArray::sortUStringsNatCi($aArray, CUString::COLLATION_DEFAULT);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));

        $aArray = CArray::fromElements(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A3", "A20", "A100");
        CArray::sortUStringsNatCi($aArray, CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "á", "A3", "A20", "A100", "b", "B", "C", "č", "d", "D", "ê", "E")));

        $aArray = CArray::fromElements(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A3", "[A20", "[A100");
        CArray::sortUStringsNatCi($aArray, CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "?a", "[A3", "[A20", "[A100", "(b", ",B", "!C", " c", ".d", ";D", "\"e", ":E")));

        $aArray = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        CArray::sortUStringsNatCi($aArray, CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $aArray = CArray::fromElements(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $aArray = CArray::filter($aArray, function ($iElement)
            {
                return CMathi::isEven($iElement);
            });
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements(2, 4, 6, 8, 10)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnique ()
    {
        // Using the default comparator.
        $aArray = CArray::fromElements("a", "b", "c", "d", "e", "a", "c", "e");
        $aArray = CArray::unique($aArray);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "d", "e")));

        // Using a custom comparator.
        $aArray = CArray::fromElements("A", "b", "C", "d", "E", "a", "c", "e");
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $aArray = CArray::unique($aArray, $fnComparator);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("A", "b", "C", "d", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testElementsSum ()
    {
        $aArray = CArray::fromElements(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $this->assertTrue( CArray::elementsSum($aArray) == 55 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testElementsProduct ()
    {
        $aArray = CArray::fromElements(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $this->assertTrue( CArray::elementsProduct($aArray) == 3628800 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsSubsetOf ()
    {
        // Using the default comparator.

        $aArray = CArray::fromElements("a", "b", "c", "a", "b", "c", "a", "b", "c");
        $this->assertTrue(CArray::isSubsetOf($aArray, CArray::fromElements("a", "b", "c")));

        $aArray = CArray::fromElements("a", "b", "c", "a", "b", "c", "a", "d", "b", "c");
        $this->assertFalse(CArray::isSubsetOf($aArray, CArray::fromElements("a", "b", "c")));

        // Using a custom comparator.
        $aArray = CArray::fromElements("a", "b", "c", "a", "b", "c", "a", "b", "c");
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $this->assertTrue(CArray::isSubsetOf($aArray, CArray::fromElements("A", "B", "C"), $fnComparator));

        // Special case.
        $aArray = CArray::make();
        $this->assertFalse(CArray::isSubsetOf($aArray, CArray::fromElements("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnion ()
    {
        $aArray0 = CArray::fromElements("a", "b", "c");
        $aArray1 = CArray::fromElements("d", "e", "f");
        $aArray2 = CArray::fromElements("g", "h", "i");
        $aArray = CArray::union($aArray0, $aArray1, $aArray2);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "b", "c", "d", "e", "f", "g", "h", "i")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIntersection ()
    {
        // Using the default comparator.
        $aArray0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $aArray1 = CArray::fromElements("g", "b", "h", "d", "i", "f");
        $aArray = CArray::intersection($aArray0, $aArray1);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("b", "d", "f")));

        // Using a custom comparator.
        $aArray0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $aArray1 = CArray::fromElements("G", "B", "H", "D", "I", "F");
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $aArray = CArray::intersection($aArray0, $aArray1, $fnComparator);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("b", "d", "f")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDifference ()
    {
        // Using the default comparator.
        $aArray0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $aArray1 = CArray::fromElements("g", "b", "h", "d", "i", "f");
        $aArray = CArray::difference($aArray0, $aArray1);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "c", "e")));

        // Using a custom comparator.
        $aArray0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $aArray1 = CArray::fromElements("G", "B", "H", "D", "I", "F");
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $aArray = CArray::difference($aArray0, $aArray1, $fnComparator);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "c", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSymmetricDifference ()
    {
        // Using the default comparator.
        $aArray0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $aArray1 = CArray::fromElements("g", "b", "h", "d", "i", "f");
        $aArray = CArray::symmetricDifference($aArray0, $aArray1);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "c", "e", "g", "h", "i")));

        // Using a custom comparator.
        $aArray0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $aArray1 = CArray::fromElements("G", "B", "H", "D", "I", "F");
        $fnComparator = function ($sString0, $sString1)
            {
                return ( CString::toLowerCase($sString0) === CString::toLowerCase($sString1) );
            };
        $aArray = CArray::symmetricDifference($aArray0, $aArray1, $fnComparator);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "c", "e", "G", "H", "I")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRepeat ()
    {
        $aArray = CArray::repeat("a", 5);
        $this->assertTrue(CArray::equals($aArray, CArray::fromElements("a", "a", "a", "a", "a")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}

/**
 * @ignore
 */

class ClassForSorting
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
