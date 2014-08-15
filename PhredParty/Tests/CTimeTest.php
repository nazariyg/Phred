<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CTimeTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $oTime = new CTime(0);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1970-01-01 00:00:00"));

        $oTime = new CTime(1234567890);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $oTime = new CTime(-1234567890);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30"));

        $oTime = new CTime(1234567890, 250);
        $this->assertTrue( $oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30") &&
            $oTime->FTime() === 1234567890.250 && $oTime->UTime() === 1234567890 && $oTime->MTime() === 250 );

        $oTime = new CTime(-1234567890, -250);
        $this->assertTrue( $oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30") &&
            $oTime->FTime() === -1234567890.250 && $oTime->UTime() === -1234567890 && $oTime->MTime() === -250 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromFTime ()
    {
        $oTime = CTime::fromFTime(0.250);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1970-01-01 00:00:00"));

        $oTime = CTime::fromFTime(1234567890.750);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $oTime = CTime::fromFTime(1234567890.250);
        $this->assertTrue( $oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30") &&
            $oTime->UTime() === 1234567890 && $oTime->MTime() === 250 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromString ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $oTime = CTime::fromString("2009-02-13T23:31:30+00:00", CTime::PATTERN_W3C);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $oTime = CTime::fromString("11/18/1930 00:28:30");
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAreComponentsValid ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $this->assertTrue(CTime::areComponentsValid($iYear, $iMonth, $iDay));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 29;
        $this->assertFalse(CTime::areComponentsValid($iYear, $iMonth, $iDay));

        $iYear = 2000;
        $iMonth = 2;
        $iDay = 29;
        $this->assertTrue(CTime::areComponentsValid($iYear, $iMonth, $iDay));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $this->assertTrue(CTime::areComponentsValid($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond));

        $iYear = 2009;
        $iMonth = 0;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $this->assertFalse(CTime::areComponentsValid($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 25;
        $iMinute = 31;
        $iSecond = 30;
        $this->assertFalse(CTime::areComponentsValid($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = -1;
        $iSecond = 30;
        $this->assertFalse(CTime::areComponentsValid($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 60;
        $this->assertFalse(CTime::areComponentsValid($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $iMillisecond = 250;
        $this->assertTrue(CTime::areComponentsValid($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $iMillisecond = 1000;
        $this->assertFalse(CTime::areComponentsValid($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
            $iMillisecond));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromComponentsUtc ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $iYear = 1930;
        $iMonth = 11;
        $iDay = 18;
        $iHour = 0;
        $iMinute = 28;
        $iSecond = 30;
        $oTime = CTime::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $oTime = CTime::fromComponentsUtc($iYear, $iMonth, $iDay);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 00:00:00"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromComponentsInTimeZone ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour,
            $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:30"));

        $iYear = 1930;
        $iMonth = 11;
        $iDay = 18;
        $iHour = 0;
        $iMinute = 28;
        $iSecond = 30;
        $oTime = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour,
            $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "1930-11-18 00:28:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $oTime = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 00:00:00"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromComponentsLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $iYear = 1930;
        $iMonth = 11;
        $iDay = 18;
        $iHour = 0;
        $iMinute = 28;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 00:00:00"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToStringUtc ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_W3C)->equals("2009-02-13T23:31:30+00:00"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_HTTP_HEADER_GMT)->equals("Fri, 13 Feb 2009 23:31:30 GMT"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringUtc("m/d/Y H:i:s")->equals("02/13/2009 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToStringInTimeZone ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour,
            $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour,
            $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_W3C)->equals(
            "2009-02-13T23:31:30+02:00"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour,
            $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), "m/d/Y H:i:s")->equals(
            "02/13/2009 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToStringLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue($oTime->toStringLocal("m/d/Y H:i:s")->equals("02/13/2009 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUTime ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->UTime() === 1234567890 );

        $oTime = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->UTime() === -1234567890 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMTime ()
    {
        $oTime = CTime::fromFTime(1234567890.250);
        $this->assertTrue( $oTime->MTime() === 250 );

        $oTime = CTime::fromFTime(-1234567890.750);
        $this->assertTrue( $oTime->MTime() === -750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFTime ()
    {
        $oTime = CTime::fromFTime(1234567890.250);
        $this->assertTrue( $oTime->FTime() === 1234567890.250 );

        $oTime = CTime::fromFTime(-1234567890.750);
        $this->assertTrue( $oTime->FTime() === -1234567890.750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = new CTime(1234567890);
        $this->assertTrue($oTime0->equals($oTime1));

        $oTime0 = CTime::fromFTime(1234567890.250);
        $oTime1 = CTime::fromFTime(1234567890.250);
        $this->assertTrue($oTime0->equals($oTime1));

        $oTime0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromFTime(-1234567890.0);
        $this->assertTrue($oTime0->equals($oTime1));

        $oTime0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertFalse($oTime0->equals($oTime1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsBefore ()
    {
        $oTime0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue($oTime0->isBefore($oTime1));

        $oTime0 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:15:00", CTime::PATTERN_MYSQL);
        $this->assertTrue($oTime0->isBefore($oTime1));

        $oTime0 = CTime::fromString("2009-02-13 23:31:25", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue($oTime0->isBefore($oTime1));

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertFalse($oTime0->isBefore($oTime1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsAfter ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue($oTime0->isAfter($oTime1));

        $oTime0 = CTime::fromString("2009-02-13 00:15:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue($oTime0->isAfter($oTime1));

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:25", CTime::PATTERN_MYSQL);
        $this->assertTrue($oTime0->isAfter($oTime1));

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertFalse($oTime0->isAfter($oTime1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->compare($oTime1) == 0 );

        $oTime0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->compare($oTime1) < 0 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:25", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->compare($oTime1) < 0 );

        $oTime0 = CTime::fromString("2009-02-14 18:00:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 18:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->compare($oTime1) > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponentsUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $oTime->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond, $eDayOfWeek);
        $this->assertTrue( $iYear == 2009 && $iMonth == 2 && $iDay == 13 && $iHour == 23 && $iMinute == 31 &&
            $iSecond == 30 && $iMillisecond == 0 && $eDayOfWeek == CTime::FRIDAY );

        $oTime = new CTime(0);
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $oTime->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond, $eDayOfWeek);
        $this->assertTrue( $iYear == 1970 && $iMonth == 1 && $iDay == 1 && $iHour == 0 && $iMinute == 0 &&
            $iSecond == 0 && $iMillisecond == 0 && $eDayOfWeek == CTime::THURSDAY );

        $oTime = CTime::fromFTime(-1234567890.250);
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $oTime->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond, $eDayOfWeek);
        $this->assertTrue( $iYear == 1930 && $iMonth == 11 && $iDay == 18 && $iHour == 0 && $iMinute == 28 &&
            $iSecond == 29 && $iMillisecond == 750 && $eDayOfWeek == CTime::TUESDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testYearUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->yearUtc() == 2009 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->yearUtc() == 1970 );

        $oTime = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->yearUtc() == 1930 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMonthUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->monthUtc() == 2 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->monthUtc() == 1 );

        $oTime = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->monthUtc() == 11 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->dayUtc() == 13 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->dayUtc() == 1 );

        $oTime = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->dayUtc() == 18 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHourUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->hourUtc() == 23 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->hourUtc() == 0 );

        $oTime = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->hourUtc() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMinuteUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->minuteUtc() == 31 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->minuteUtc() == 0 );

        $oTime = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->minuteUtc() == 28 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSecondUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->secondUtc() == 30 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->secondUtc() == 0 );

        $oTime = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->secondUtc() == 30 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMillisecondUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->millisecondUtc() == 0 );

        $oTime = new CTime(0, 250);
        $this->assertTrue( $oTime->millisecondUtc() == 250 );

        $oTime = CTime::fromFTime(-1234567890.250);
        $this->assertTrue( $oTime->millisecondUtc() == 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayOfWeekUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->dayOfWeekUtc() == CTime::FRIDAY );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->dayOfWeekUtc() == CTime::THURSDAY );

        $oTime = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime->dayOfWeekUtc() == CTime::TUESDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponentsInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $oTime->componentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour, $iMinute,
            $iSecond, $iMillisecond, $eDayOfWeek);
        $this->assertTrue( $iYear == 2009 && $iMonth == 2 && $iDay == 13 && $iHour == 23 && $iMinute == 31 &&
            $iSecond == 30 && $iMillisecond == 0 && $eDayOfWeek == CTime::FRIDAY );

        $oTime = new CTime(0);
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $oTime->componentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour, $iMinute,
            $iSecond, $iMillisecond, $eDayOfWeek);
        $this->assertTrue( $iYear == 1970 && $iMonth == 1 && $iDay == 1 && $iHour == 2 && $iMinute == 0 &&
            $iSecond == 0 && $iMillisecond == 0 && $eDayOfWeek == CTime::THURSDAY );

        $oTime = CTime::fromFTime(-1234567890.250);
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $oTime->componentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour, $iMinute,
            $iSecond, $iMillisecond, $eDayOfWeek);
        $this->assertTrue( $iYear == 1930 && $iMonth == 11 && $iDay == 18 && $iHour == 2 && $iMinute == 28 &&
            $iSecond == 29 && $iMillisecond == 750 && $eDayOfWeek == CTime::TUESDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testYearInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $oTime->yearInTimeZone(new CTimeZone("Europe/Helsinki")) == 2009 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->yearInTimeZone(new CTimeZone("Europe/Helsinki")) == 1970 );

        $oTime = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $oTime->yearInTimeZone(new CTimeZone("Europe/Helsinki")) == 1930 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMonthInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $oTime->monthInTimeZone(new CTimeZone("Europe/Helsinki")) == 2 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->monthInTimeZone(new CTimeZone("Europe/Helsinki")) == 1 );

        $oTime = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $oTime->monthInTimeZone(new CTimeZone("Europe/Helsinki")) == 11 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $oTime->dayInTimeZone(new CTimeZone("Europe/Helsinki")) == 13 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->dayInTimeZone(new CTimeZone("Europe/Helsinki")) == 1 );

        $oTime = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $oTime->dayInTimeZone(new CTimeZone("Europe/Helsinki")) == 18 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHourInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $oTime->hourInTimeZone(new CTimeZone("Europe/Helsinki")) == 23 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->hourInTimeZone(new CTimeZone("Europe/Helsinki")) == 2 );

        $oTime = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $oTime->hourInTimeZone(new CTimeZone("Europe/Helsinki")) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMinuteInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $oTime->minuteInTimeZone(new CTimeZone("Europe/Helsinki")) == 31 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->minuteInTimeZone(new CTimeZone("Europe/Helsinki")) == 0 );

        $oTime = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $oTime->minuteInTimeZone(new CTimeZone("Europe/Helsinki")) == 28 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSecondInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $oTime->secondInTimeZone(new CTimeZone("Europe/Helsinki")) == 30 );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->secondInTimeZone(new CTimeZone("Europe/Helsinki")) == 0 );

        $oTime = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $oTime->secondInTimeZone(new CTimeZone("Europe/Helsinki")) == 30 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMillisecondInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $oTime->millisecondInTimeZone(new CTimeZone("Europe/Helsinki")) == 0 );

        $oTime = new CTime(0, 250);
        $this->assertTrue( $oTime->millisecondInTimeZone(new CTimeZone("Europe/Helsinki")) == 250 );

        $oTime = CTime::fromFTime(-1234567890.250);
        $this->assertTrue( $oTime->millisecondInTimeZone(new CTimeZone("Europe/Helsinki")) == 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayOfWeekInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $oTime->dayOfWeekInTimeZone(new CTimeZone("Europe/Helsinki")) == CTime::FRIDAY );

        $oTime = new CTime(0);
        $this->assertTrue( $oTime->dayOfWeekInTimeZone(new CTimeZone("Europe/Helsinki")) == CTime::THURSDAY );

        $oTime = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $oTime->dayOfWeekInTimeZone(new CTimeZone("Europe/Helsinki")) == CTime::TUESDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponentsLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $iMillisecond;
        $eDayOfWeek;
        $oTime->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond, $eDayOfWeek);
        $this->assertTrue( $iYear == 2009 && $iMonth == 2 && $iDay == 13 && $iHour == 23 && $iMinute == 31 &&
            $iSecond == 30 && $iMillisecond == 0 && $eDayOfWeek == CTime::FRIDAY );

        $iYear = 1970;
        $iMonth = 1;
        $iDay = 1;
        $iHour = 0;
        $iMinute = 0;
        $iSecond = 0;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $iMillisecond;
        $eDayOfWeek;
        $oTime->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond, $eDayOfWeek);
        $this->assertTrue( $iYear == 1970 && $iMonth == 1 && $iDay == 1 && $iHour == 0 && $iMinute == 0 &&
            $iSecond == 0 && $iMillisecond == 0 && $eDayOfWeek == CTime::THURSDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testYearLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->yearLocal() == 2009 );

        $iYear = 1970;
        $iMonth = 1;
        $iDay = 1;
        $iHour = 0;
        $iMinute = 0;
        $iSecond = 0;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->yearLocal() == 1970 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMonthLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->monthLocal() == 2 );

        $iYear = 1970;
        $iMonth = 1;
        $iDay = 1;
        $iHour = 0;
        $iMinute = 0;
        $iSecond = 0;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->monthLocal() == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->dayLocal() == 13 );

        $iYear = 1970;
        $iMonth = 1;
        $iDay = 1;
        $iHour = 0;
        $iMinute = 0;
        $iSecond = 0;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->dayLocal() == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHourLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->hourLocal() == 23 );

        $iYear = 1970;
        $iMonth = 1;
        $iDay = 1;
        $iHour = 0;
        $iMinute = 0;
        $iSecond = 0;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->hourLocal() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMinuteLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->minuteLocal() == 31 );

        $iYear = 1970;
        $iMonth = 1;
        $iDay = 1;
        $iHour = 0;
        $iMinute = 0;
        $iSecond = 0;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->minuteLocal() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSecondLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->secondLocal() == 30 );

        $iYear = 1970;
        $iMonth = 1;
        $iDay = 1;
        $iHour = 0;
        $iMinute = 0;
        $iSecond = 0;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->secondLocal() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMillisecondLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->millisecondLocal() == 0 );

        $oTime = new CTime(0, 250);
        $this->assertTrue( $oTime->millisecondLocal() == 250 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayOfWeekLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->dayOfWeekLocal() == CTime::FRIDAY );

        $iYear = 1970;
        $iMonth = 1;
        $iDay = 1;
        $iHour = 0;
        $iMinute = 0;
        $iSecond = 0;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $this->assertTrue( $oTime->dayOfWeekLocal() == CTime::THURSDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiff ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diff($oTime1, CTime::SECOND) == 15 );

        $oTime0 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diff($oTime1, CTime::MINUTE) == 4 );

        $oTime0 = CTime::fromString("2010-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diff($oTime1, CTime::DAY) == 365 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diff($oTime1, CTime::DAY) == 1269 );

        $oTime0 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-04-15 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diff($oTime1, CTime::MONTH) == 2 );

        $oTime0 = CTime::fromString("2009-04-27 00:00:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diff($oTime1, CTime::MONTH) == 2 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diff($oTime1, CTime::YEAR) == 78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInSeconds ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInSeconds($oTime1) == 15 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInSeconds($oTime1) == 15 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInMinutes ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInMinutes($oTime1) == 4 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInMinutes($oTime1) == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInHours ()
    {
        $oTime0 = CTime::fromString("2009-02-14 01:45:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInHours($oTime1) == 2 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-14 01:45:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInHours($oTime1) == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInDays ()
    {
        $oTime0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInDays($oTime1) == 1269 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2012-08-05 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInDays($oTime1) == 1269 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInWeeks ()
    {
        $oTime0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInWeeks($oTime1) == 181 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInWeeks($oTime1) == 181 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInMonths ()
    {
        $oTime0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInMonths($oTime1) == 41 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInMonths($oTime1) == 41 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInYears ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInYears($oTime1) == 78 );

        $oTime0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->diffInYears($oTime1) == 78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffUnits ()
    {
        $oTime0 = CTime::fromString("2009-02-13 00:00:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $iNumSeconds;
        $iNumMinutes;
        $iNumHours;
        $iNumDays;
        $iNumWeeks;
        $iNumMonths;
        $iNumYears;
        $oTime0->diffUnits($oTime1, $iNumSeconds, $iNumMinutes, $iNumHours, $iNumDays, $iNumWeeks, $iNumMonths,
            $iNumYears);
        $this->assertTrue(
            $iNumSeconds == 45 &&
            $iNumMinutes == 0 &&
            $iNumHours == 0 &&
            $iNumDays == 0 &&
            $iNumWeeks == 0 &&
            $iNumMonths == 0 &&
            $iNumYears == 0 );

        $oTime0 = CTime::fromString("2009-02-13 00:02:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $iNumSeconds;
        $iNumMinutes;
        $iNumHours;
        $iNumDays;
        $iNumWeeks;
        $iNumMonths;
        $iNumYears;
        $oTime0->diffUnits($oTime1, $iNumSeconds, $iNumMinutes, $iNumHours, $iNumDays, $iNumWeeks, $iNumMonths,
            $iNumYears);
        $this->assertTrue(
            $iNumSeconds == 45 &&
            $iNumMinutes == 2 &&
            $iNumHours == 0 &&
            $iNumDays == 0 &&
            $iNumWeeks == 0 &&
            $iNumMonths == 0 &&
            $iNumYears == 0 );

        $oTime0 = CTime::fromString("2009-02-13 05:02:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $iNumSeconds;
        $iNumMinutes;
        $iNumHours;
        $iNumDays;
        $iNumWeeks;
        $iNumMonths;
        $iNumYears;
        $oTime0->diffUnits($oTime1, $iNumSeconds, $iNumMinutes, $iNumHours, $iNumDays, $iNumWeeks, $iNumMonths,
            $iNumYears);
        $this->assertTrue(
            $iNumSeconds == 45 &&
            $iNumMinutes == 2 &&
            $iNumHours == 5 &&
            $iNumDays == 0 &&
            $iNumWeeks == 0 &&
            $iNumMonths == 0 &&
            $iNumYears == 0 );

        $oTime0 = CTime::fromString("2009-02-14 05:02:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $iNumSeconds;
        $iNumMinutes;
        $iNumHours;
        $iNumDays;
        $iNumWeeks;
        $iNumMonths;
        $iNumYears;
        $oTime0->diffUnits($oTime1, $iNumSeconds, $iNumMinutes, $iNumHours, $iNumDays, $iNumWeeks, $iNumMonths,
            $iNumYears);
        $this->assertTrue(
            $iNumSeconds == 45 &&
            $iNumMinutes == 2 &&
            $iNumHours == 5 &&
            $iNumDays == 1 &&
            $iNumWeeks == 0 &&
            $iNumMonths == 0 &&
            $iNumYears == 0 );

        $oTime0 = CTime::fromString("2009-02-23 05:02:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $iNumSeconds;
        $iNumMinutes;
        $iNumHours;
        $iNumDays;
        $iNumWeeks;
        $iNumMonths;
        $iNumYears;
        $oTime0->diffUnits($oTime1, $iNumSeconds, $iNumMinutes, $iNumHours, $iNumDays, $iNumWeeks, $iNumMonths,
            $iNumYears);
        $this->assertTrue(
            $iNumSeconds == 45 &&
            $iNumMinutes == 2 &&
            $iNumHours == 5 &&
            $iNumDays == 10 &&
            $iNumWeeks == 1 &&
            $iNumMonths == 0 &&
            $iNumYears == 0 );

        $oTime0 = CTime::fromString("2009-09-23 05:02:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $iNumSeconds;
        $iNumMinutes;
        $iNumHours;
        $iNumDays;
        $iNumWeeks;
        $iNumMonths;
        $iNumYears;
        $oTime0->diffUnits($oTime1, $iNumSeconds, $iNumMinutes, $iNumHours, $iNumDays, $iNumWeeks, $iNumMonths,
            $iNumYears);
        $this->assertTrue(
            $iNumSeconds == 45 &&
            $iNumMinutes == 2 &&
            $iNumHours == 5 &&
            $iNumDays == 9 &&
            $iNumWeeks == 31 &&
            $iNumMonths == 7 &&
            $iNumYears == 0 );

        $oTime0 = CTime::fromString("2012-09-23 05:02:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $iNumSeconds;
        $iNumMinutes;
        $iNumHours;
        $iNumDays;
        $iNumWeeks;
        $iNumMonths;
        $iNumYears;
        $oTime0->diffUnits($oTime1, $iNumSeconds, $iNumMinutes, $iNumHours, $iNumDays, $iNumWeeks, $iNumMonths,
            $iNumYears);
        $this->assertTrue(
            $iNumSeconds == 45 &&
            $iNumMinutes == 2 &&
            $iNumHours == 5 &&
            $iNumDays == 9 &&
            $iNumWeeks == 188 &&
            $iNumMonths == 7 &&
            $iNumYears == 3 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $iNumSeconds;
        $iNumMinutes;
        $iNumHours;
        $iNumDays;
        $iNumWeeks;
        $iNumMonths;
        $iNumYears;
        $oTime0->diffUnits($oTime1, $iNumSeconds, $iNumMinutes, $iNumHours, $iNumDays, $iNumWeeks, $iNumMonths,
            $iNumYears);
        $this->assertTrue(
            $iNumSeconds == 0 &&
            $iNumMinutes == 3 &&
            $iNumHours == 23 &&
            $iNumDays == 28 &&
            $iNumWeeks == 4082 &&
            $iNumMonths == 2 &&
            $iNumYears == 78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiff ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiff($oTime1, CTime::SECOND) == -15 );

        $oTime0 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiff($oTime1, CTime::MINUTE) == 4 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2010-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiff($oTime1, CTime::DAY) == -365 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiff($oTime1, CTime::DAY) == -1269 );

        $oTime0 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-04-15 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiff($oTime1, CTime::MONTH) == -2 );

        $oTime0 = CTime::fromString("2009-04-27 00:00:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiff($oTime1, CTime::MONTH) == 2 );

        $oTime0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiff($oTime1, CTime::YEAR) == -78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInSeconds ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInSeconds($oTime1) == 15 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInSeconds($oTime1) == -15 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInMinutes ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInMinutes($oTime1) == 4 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInMinutes($oTime1) == -4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInHours ()
    {
        $oTime0 = CTime::fromString("2009-02-14 01:45:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInHours($oTime1) == 2 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-14 01:45:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInHours($oTime1) == -2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInDays ()
    {
        $oTime0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInDays($oTime1) == 1269 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2012-08-05 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInDays($oTime1) == -1269 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInWeeks ()
    {
        $oTime0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInWeeks($oTime1) == 181 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInWeeks($oTime1) == -181 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInMonths ()
    {
        $oTime0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInMonths($oTime1) == 41 );

        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInMonths($oTime1) == -41 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInYears ()
    {
        $oTime0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInYears($oTime1) == 78 );

        $oTime0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $oTime1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $oTime0->signedDiffInYears($oTime1) == -78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::SECOND, 15);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:45"));

        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::MINUTE, -4);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:27:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::HOUR, 5);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-14 04:31:30"));

        $oTime = CTime::fromString("2010-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::DAY, -365);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $oTime = CTime::fromString("2012-08-05 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::DAY, -1273);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-09 23:31:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::WEEK, 30);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-09-11 23:31:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::MONTH, -3);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2008-11-13 23:31:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::MONTH, 18);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2010-08-13 23:31:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedUtc(CTime::YEAR, -79);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedBySecondsUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedBySecondsUtc(15);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:45"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMinutesUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedByMinutesUtc(-4);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:27:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByHoursUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedByHoursUtc(5);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-14 04:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByDaysUtc ()
    {
        $oTime = CTime::fromString("2012-08-05 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedByDaysUtc(-1273);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-09 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByWeeksUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedByWeeksUtc(30);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-09-11 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMonthsUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedByMonthsUtc(18);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2010-08-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByYearsUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->shiftedByYearsUtc(-79);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::SECOND, 15);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:45"));

        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::MINUTE, -4);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:27:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::HOUR, 5);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-14 04:31:30"));

        $oTime = CTime::fromString("2010-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::DAY, -365);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:30"));

        $oTime = CTime::fromString("2012-08-05 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::DAY, -1273);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-09 23:31:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::WEEK, 30);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-09-11 23:31:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::MONTH, -3);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2008-11-13 23:31:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::MONTH, 18);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2010-08-13 23:31:30"));

        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::YEAR, -79);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedBySecondsInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedBySecondsInTimeZone(new CTimeZone("Europe/Helsinki"), 15);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:45"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMinutesInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedByMinutesInTimeZone(new CTimeZone("Europe/Helsinki"), -4);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:27:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByHoursInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedByHoursInTimeZone(new CTimeZone("Europe/Helsinki"), 5);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-14 04:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByDaysInTimeZone ()
    {
        $oTime = CTime::fromString("2012-08-05 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedByDaysInTimeZone(new CTimeZone("Europe/Helsinki"), -1273);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-09 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByWeeksInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedByWeeksInTimeZone(new CTimeZone("Europe/Helsinki"), 30);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-09-11 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMonthsInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedByMonthsInTimeZone(new CTimeZone("Europe/Helsinki"), 18);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2010-08-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByYearsInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->shiftedByYearsInTimeZone(new CTimeZone("Europe/Helsinki"), -79);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::SECOND, 15);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:45"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::MINUTE, -4);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:27:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::HOUR, 5);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-14 04:31:30"));

        $iYear = 2010;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::DAY, -365);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $iYear = 2012;
        $iMonth = 8;
        $iDay = 5;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::DAY, -1273);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-09 23:31:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::WEEK, 30);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-09-11 23:31:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::MONTH, -3);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2008-11-13 23:31:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::MONTH, 18);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2010-08-13 23:31:30"));

        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedLocal(CTime::YEAR, -79);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedBySecondsLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedBySecondsLocal(15);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:45"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMinutesLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedByMinutesLocal(-4);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:27:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByHoursLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedByHoursLocal(5);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-14 04:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByDaysLocal ()
    {
        $iYear = 2012;
        $iMonth = 8;
        $iDay = 5;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedByDaysLocal(-1273);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-09 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByWeeksLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedByWeeksLocal(30);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-09-11 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMonthsLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedByMonthsLocal(18);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2010-08-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByYearsLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->shiftedByYearsLocal(-79);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMillisecondUtc ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $iMillisecond = 250;
        $oTime = CTime::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $oTime = $oTime->withMillisecondUtc(750);
        $this->assertTrue( $oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30") &&
            $oTime->MTime() === 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithSecondUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->withSecondUtc(59);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:59"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMinuteUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->withMinuteUtc(10);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:10:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithHourUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->withHourUtc(11);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 11:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithDayUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->withDayUtc(23);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-23 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMonthUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->withMonthUtc(5);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-05-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithYearUtc ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $oTime = $oTime->withYearUtc(1930);
        $this->assertTrue($oTime->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMillisecondInTimeZone ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $iMillisecond = 250;
        $oTime = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $iYear, $iMonth, $iDay, $iHour,
            $iMinute, $iSecond, $iMillisecond);
        $oTime = $oTime->withMillisecondInTimeZone(new CTimeZone("Europe/Helsinki"), 750);
        $this->assertTrue( $oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:30") && $oTime->MTime() === 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithSecondInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->withSecondInTimeZone(new CTimeZone("Europe/Helsinki"), 59);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:59"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMinuteInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->withMinuteInTimeZone(new CTimeZone("Europe/Helsinki"), 10);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:10:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithHourInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->withHourInTimeZone(new CTimeZone("Europe/Helsinki"), 11);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 11:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithDayInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->withDayInTimeZone(new CTimeZone("Europe/Helsinki"), 23);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-23 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMonthInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->withMonthInTimeZone(new CTimeZone("Europe/Helsinki"), 5);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-05-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithYearInTimeZone ()
    {
        $oTime = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $oTime = $oTime->withYearInTimeZone(new CTimeZone("Europe/Helsinki"), 1930);
        $this->assertTrue($oTime->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMillisecondLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $iMillisecond = 250;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $oTime = $oTime->withMillisecondLocal(750);
        $this->assertTrue( $oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30") &&
            $oTime->MTime() === 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithSecondLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->withSecondLocal(59);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:59"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMinuteLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->withMinuteLocal(10);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:10:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithHourLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->withHourLocal(11);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 11:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithDayLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->withDayLocal(23);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-23 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMonthLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->withMonthLocal(5);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-05-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithYearLocal ()
    {
        $iYear = 2009;
        $iMonth = 2;
        $iDay = 13;
        $iHour = 23;
        $iMinute = 31;
        $iSecond = 30;
        $oTime = CTime::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        $oTime = $oTime->withYearLocal(1930);
        $this->assertTrue($oTime->toStringLocal(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
