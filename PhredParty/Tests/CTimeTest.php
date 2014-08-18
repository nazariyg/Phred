<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
        $time = new CTime(0);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1970-01-01 00:00:00"));

        $time = new CTime(1234567890);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $time = new CTime(-1234567890);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30"));

        $time = new CTime(1234567890, 250);
        $this->assertTrue( $time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30") &&
            $time->FTime() === 1234567890.250 && $time->UTime() === 1234567890 && $time->MTime() === 250 );

        $time = new CTime(-1234567890, -250);
        $this->assertTrue( $time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30") &&
            $time->FTime() === -1234567890.250 && $time->UTime() === -1234567890 && $time->MTime() === -250 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromFTime ()
    {
        $time = CTime::fromFTime(0.250);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1970-01-01 00:00:00"));

        $time = CTime::fromFTime(1234567890.750);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $time = CTime::fromFTime(1234567890.250);
        $this->assertTrue( $time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30") &&
            $time->UTime() === 1234567890 && $time->MTime() === 250 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromString ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $time = CTime::fromString("2009-02-13T23:31:30+00:00", CTime::PATTERN_W3C);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $time = CTime::fromString("11/18/1930 00:28:30");
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAreComponentsValid ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $this->assertTrue(CTime::areComponentsValid($year, $month, $day));

        $year = 2009;
        $month = 2;
        $day = 29;
        $this->assertFalse(CTime::areComponentsValid($year, $month, $day));

        $year = 2000;
        $month = 2;
        $day = 29;
        $this->assertTrue(CTime::areComponentsValid($year, $month, $day));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $this->assertTrue(CTime::areComponentsValid($year, $month, $day, $hour, $minute, $second));

        $year = 2009;
        $month = 0;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $this->assertFalse(CTime::areComponentsValid($year, $month, $day, $hour, $minute, $second));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 25;
        $minute = 31;
        $second = 30;
        $this->assertFalse(CTime::areComponentsValid($year, $month, $day, $hour, $minute, $second));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = -1;
        $second = 30;
        $this->assertFalse(CTime::areComponentsValid($year, $month, $day, $hour, $minute, $second));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 60;
        $this->assertFalse(CTime::areComponentsValid($year, $month, $day, $hour, $minute, $second));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $millisecond = 250;
        $this->assertTrue(CTime::areComponentsValid($year, $month, $day, $hour, $minute, $second, $millisecond));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $millisecond = 1000;
        $this->assertFalse(CTime::areComponentsValid($year, $month, $day, $hour, $minute, $second,
            $millisecond));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromComponentsUtc ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsUtc($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $year = 1930;
        $month = 11;
        $day = 18;
        $hour = 0;
        $minute = 28;
        $second = 30;
        $time = CTime::fromComponentsUtc($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $time = CTime::fromComponentsUtc($year, $month, $day);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 00:00:00"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromComponentsInTimeZone ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour,
            $minute, $second);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:30"));

        $year = 1930;
        $month = 11;
        $day = 18;
        $hour = 0;
        $minute = 28;
        $second = 30;
        $time = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour,
            $minute, $second);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "1930-11-18 00:28:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $time = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 00:00:00"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromComponentsLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $year = 1930;
        $month = 11;
        $day = 18;
        $hour = 0;
        $minute = 28;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("1930-11-18 00:28:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $time = CTime::fromComponentsLocal($year, $month, $day);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 00:00:00"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToStringUtc ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsUtc($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsUtc($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_W3C)->equals("2009-02-13T23:31:30+00:00"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsUtc($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_HTTP_HEADER_GMT)->equals("Fri, 13 Feb 2009 23:31:30 GMT"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsUtc($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringUtc("m/d/Y H:i:s")->equals("02/13/2009 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToStringInTimeZone ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour,
            $minute, $second);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour,
            $minute, $second);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_W3C)->equals(
            "2009-02-13T23:31:30+02:00"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour,
            $minute, $second);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), "m/d/Y H:i:s")->equals(
            "02/13/2009 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToStringLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue($time->toStringLocal("m/d/Y H:i:s")->equals("02/13/2009 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUTime ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->UTime() === 1234567890 );

        $time = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->UTime() === -1234567890 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMTime ()
    {
        $time = CTime::fromFTime(1234567890.250);
        $this->assertTrue( $time->MTime() === 250 );

        $time = CTime::fromFTime(-1234567890.750);
        $this->assertTrue( $time->MTime() === -750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFTime ()
    {
        $time = CTime::fromFTime(1234567890.250);
        $this->assertTrue( $time->FTime() === 1234567890.250 );

        $time = CTime::fromFTime(-1234567890.750);
        $this->assertTrue( $time->FTime() === -1234567890.750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = new CTime(1234567890);
        $this->assertTrue($time0->equals($time1));

        $time0 = CTime::fromFTime(1234567890.250);
        $time1 = CTime::fromFTime(1234567890.250);
        $this->assertTrue($time0->equals($time1));

        $time0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromFTime(-1234567890.0);
        $this->assertTrue($time0->equals($time1));

        $time0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertFalse($time0->equals($time1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsBefore ()
    {
        $time0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue($time0->isBefore($time1));

        $time0 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:15:00", CTime::PATTERN_MYSQL);
        $this->assertTrue($time0->isBefore($time1));

        $time0 = CTime::fromString("2009-02-13 23:31:25", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue($time0->isBefore($time1));

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertFalse($time0->isBefore($time1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsAfter ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue($time0->isAfter($time1));

        $time0 = CTime::fromString("2009-02-13 00:15:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue($time0->isAfter($time1));

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:25", CTime::PATTERN_MYSQL);
        $this->assertTrue($time0->isAfter($time1));

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertFalse($time0->isAfter($time1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->compare($time1) == 0 );

        $time0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->compare($time1) < 0 );

        $time0 = CTime::fromString("2009-02-13 23:31:25", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->compare($time1) < 0 );

        $time0 = CTime::fromString("2009-02-14 18:00:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 18:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->compare($time1) > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponentsUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $time->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond, $dayOfWeek);
        $this->assertTrue( $year == 2009 && $month == 2 && $day == 13 && $hour == 23 && $minute == 31 &&
            $second == 30 && $millisecond == 0 && $dayOfWeek == CTime::FRIDAY );

        $time = new CTime(0);
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $time->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond, $dayOfWeek);
        $this->assertTrue( $year == 1970 && $month == 1 && $day == 1 && $hour == 0 && $minute == 0 &&
            $second == 0 && $millisecond == 0 && $dayOfWeek == CTime::THURSDAY );

        $time = CTime::fromFTime(-1234567890.250);
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $time->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond, $dayOfWeek);
        $this->assertTrue( $year == 1930 && $month == 11 && $day == 18 && $hour == 0 && $minute == 28 &&
            $second == 29 && $millisecond == 750 && $dayOfWeek == CTime::TUESDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testYearUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->yearUtc() == 2009 );

        $time = new CTime(0);
        $this->assertTrue( $time->yearUtc() == 1970 );

        $time = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->yearUtc() == 1930 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMonthUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->monthUtc() == 2 );

        $time = new CTime(0);
        $this->assertTrue( $time->monthUtc() == 1 );

        $time = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->monthUtc() == 11 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->dayUtc() == 13 );

        $time = new CTime(0);
        $this->assertTrue( $time->dayUtc() == 1 );

        $time = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->dayUtc() == 18 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHourUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->hourUtc() == 23 );

        $time = new CTime(0);
        $this->assertTrue( $time->hourUtc() == 0 );

        $time = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->hourUtc() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMinuteUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->minuteUtc() == 31 );

        $time = new CTime(0);
        $this->assertTrue( $time->minuteUtc() == 0 );

        $time = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->minuteUtc() == 28 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSecondUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->secondUtc() == 30 );

        $time = new CTime(0);
        $this->assertTrue( $time->secondUtc() == 0 );

        $time = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->secondUtc() == 30 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMillisecondUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->millisecondUtc() == 0 );

        $time = new CTime(0, 250);
        $this->assertTrue( $time->millisecondUtc() == 250 );

        $time = CTime::fromFTime(-1234567890.250);
        $this->assertTrue( $time->millisecondUtc() == 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayOfWeekUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->dayOfWeekUtc() == CTime::FRIDAY );

        $time = new CTime(0);
        $this->assertTrue( $time->dayOfWeekUtc() == CTime::THURSDAY );

        $time = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time->dayOfWeekUtc() == CTime::TUESDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponentsInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $time->componentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour, $minute,
            $second, $millisecond, $dayOfWeek);
        $this->assertTrue( $year == 2009 && $month == 2 && $day == 13 && $hour == 23 && $minute == 31 &&
            $second == 30 && $millisecond == 0 && $dayOfWeek == CTime::FRIDAY );

        $time = new CTime(0);
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $time->componentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour, $minute,
            $second, $millisecond, $dayOfWeek);
        $this->assertTrue( $year == 1970 && $month == 1 && $day == 1 && $hour == 2 && $minute == 0 &&
            $second == 0 && $millisecond == 0 && $dayOfWeek == CTime::THURSDAY );

        $time = CTime::fromFTime(-1234567890.250);
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $time->componentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour, $minute,
            $second, $millisecond, $dayOfWeek);
        $this->assertTrue( $year == 1930 && $month == 11 && $day == 18 && $hour == 2 && $minute == 28 &&
            $second == 29 && $millisecond == 750 && $dayOfWeek == CTime::TUESDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testYearInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $time->yearInTimeZone(new CTimeZone("Europe/Helsinki")) == 2009 );

        $time = new CTime(0);
        $this->assertTrue( $time->yearInTimeZone(new CTimeZone("Europe/Helsinki")) == 1970 );

        $time = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $time->yearInTimeZone(new CTimeZone("Europe/Helsinki")) == 1930 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMonthInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $time->monthInTimeZone(new CTimeZone("Europe/Helsinki")) == 2 );

        $time = new CTime(0);
        $this->assertTrue( $time->monthInTimeZone(new CTimeZone("Europe/Helsinki")) == 1 );

        $time = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $time->monthInTimeZone(new CTimeZone("Europe/Helsinki")) == 11 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $time->dayInTimeZone(new CTimeZone("Europe/Helsinki")) == 13 );

        $time = new CTime(0);
        $this->assertTrue( $time->dayInTimeZone(new CTimeZone("Europe/Helsinki")) == 1 );

        $time = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $time->dayInTimeZone(new CTimeZone("Europe/Helsinki")) == 18 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHourInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $time->hourInTimeZone(new CTimeZone("Europe/Helsinki")) == 23 );

        $time = new CTime(0);
        $this->assertTrue( $time->hourInTimeZone(new CTimeZone("Europe/Helsinki")) == 2 );

        $time = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $time->hourInTimeZone(new CTimeZone("Europe/Helsinki")) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMinuteInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $time->minuteInTimeZone(new CTimeZone("Europe/Helsinki")) == 31 );

        $time = new CTime(0);
        $this->assertTrue( $time->minuteInTimeZone(new CTimeZone("Europe/Helsinki")) == 0 );

        $time = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $time->minuteInTimeZone(new CTimeZone("Europe/Helsinki")) == 28 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSecondInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $time->secondInTimeZone(new CTimeZone("Europe/Helsinki")) == 30 );

        $time = new CTime(0);
        $this->assertTrue( $time->secondInTimeZone(new CTimeZone("Europe/Helsinki")) == 0 );

        $time = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $time->secondInTimeZone(new CTimeZone("Europe/Helsinki")) == 30 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMillisecondInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $time->millisecondInTimeZone(new CTimeZone("Europe/Helsinki")) == 0 );

        $time = new CTime(0, 250);
        $this->assertTrue( $time->millisecondInTimeZone(new CTimeZone("Europe/Helsinki")) == 250 );

        $time = CTime::fromFTime(-1234567890.250);
        $this->assertTrue( $time->millisecondInTimeZone(new CTimeZone("Europe/Helsinki")) == 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayOfWeekInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $this->assertTrue( $time->dayOfWeekInTimeZone(new CTimeZone("Europe/Helsinki")) == CTime::FRIDAY );

        $time = new CTime(0);
        $this->assertTrue( $time->dayOfWeekInTimeZone(new CTimeZone("Europe/Helsinki")) == CTime::THURSDAY );

        $time = CTime::fromString("1930-11-18 00:28:30 Europe/Helsinki");
        $this->assertTrue( $time->dayOfWeekInTimeZone(new CTimeZone("Europe/Helsinki")) == CTime::TUESDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponentsLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $millisecond;
        $dayOfWeek;
        $time->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond, $dayOfWeek);
        $this->assertTrue( $year == 2009 && $month == 2 && $day == 13 && $hour == 23 && $minute == 31 &&
            $second == 30 && $millisecond == 0 && $dayOfWeek == CTime::FRIDAY );

        $year = 1970;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $millisecond;
        $dayOfWeek;
        $time->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond, $dayOfWeek);
        $this->assertTrue( $year == 1970 && $month == 1 && $day == 1 && $hour == 0 && $minute == 0 &&
            $second == 0 && $millisecond == 0 && $dayOfWeek == CTime::THURSDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testYearLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->yearLocal() == 2009 );

        $year = 1970;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->yearLocal() == 1970 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMonthLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->monthLocal() == 2 );

        $year = 1970;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->monthLocal() == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->dayLocal() == 13 );

        $year = 1970;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->dayLocal() == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHourLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->hourLocal() == 23 );

        $year = 1970;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->hourLocal() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMinuteLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->minuteLocal() == 31 );

        $year = 1970;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->minuteLocal() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSecondLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->secondLocal() == 30 );

        $year = 1970;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->secondLocal() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMillisecondLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->millisecondLocal() == 0 );

        $time = new CTime(0, 250);
        $this->assertTrue( $time->millisecondLocal() == 250 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDayOfWeekLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->dayOfWeekLocal() == CTime::FRIDAY );

        $year = 1970;
        $month = 1;
        $day = 1;
        $hour = 0;
        $minute = 0;
        $second = 0;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $this->assertTrue( $time->dayOfWeekLocal() == CTime::THURSDAY );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiff ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diff($time1, CTime::SECOND) == 15 );

        $time0 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diff($time1, CTime::MINUTE) == 4 );

        $time0 = CTime::fromString("2010-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diff($time1, CTime::DAY) == 365 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diff($time1, CTime::DAY) == 1269 );

        $time0 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-04-15 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diff($time1, CTime::MONTH) == 2 );

        $time0 = CTime::fromString("2009-04-27 00:00:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diff($time1, CTime::MONTH) == 2 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diff($time1, CTime::YEAR) == 78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInSeconds ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInSeconds($time1) == 15 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInSeconds($time1) == 15 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInMinutes ()
    {
        $time0 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInMinutes($time1) == 4 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInMinutes($time1) == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInHours ()
    {
        $time0 = CTime::fromString("2009-02-14 01:45:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInHours($time1) == 2 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-14 01:45:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInHours($time1) == 2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInDays ()
    {
        $time0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInDays($time1) == 1269 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2012-08-05 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInDays($time1) == 1269 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInWeeks ()
    {
        $time0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInWeeks($time1) == 181 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInWeeks($time1) == 181 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInMonths ()
    {
        $time0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInMonths($time1) == 41 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInMonths($time1) == 41 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffInYears ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInYears($time1) == 78 );

        $time0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->diffInYears($time1) == 78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiffUnits ()
    {
        $time0 = CTime::fromString("2009-02-13 00:00:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $numSeconds;
        $numMinutes;
        $numHours;
        $numDays;
        $numWeeks;
        $numMonths;
        $numYears;
        $time0->diffUnits($time1, $numSeconds, $numMinutes, $numHours, $numDays, $numWeeks, $numMonths,
            $numYears);
        $this->assertTrue(
            $numSeconds == 45 &&
            $numMinutes == 0 &&
            $numHours == 0 &&
            $numDays == 0 &&
            $numWeeks == 0 &&
            $numMonths == 0 &&
            $numYears == 0 );

        $time0 = CTime::fromString("2009-02-13 00:02:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $numSeconds;
        $numMinutes;
        $numHours;
        $numDays;
        $numWeeks;
        $numMonths;
        $numYears;
        $time0->diffUnits($time1, $numSeconds, $numMinutes, $numHours, $numDays, $numWeeks, $numMonths,
            $numYears);
        $this->assertTrue(
            $numSeconds == 45 &&
            $numMinutes == 2 &&
            $numHours == 0 &&
            $numDays == 0 &&
            $numWeeks == 0 &&
            $numMonths == 0 &&
            $numYears == 0 );

        $time0 = CTime::fromString("2009-02-13 05:02:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $numSeconds;
        $numMinutes;
        $numHours;
        $numDays;
        $numWeeks;
        $numMonths;
        $numYears;
        $time0->diffUnits($time1, $numSeconds, $numMinutes, $numHours, $numDays, $numWeeks, $numMonths,
            $numYears);
        $this->assertTrue(
            $numSeconds == 45 &&
            $numMinutes == 2 &&
            $numHours == 5 &&
            $numDays == 0 &&
            $numWeeks == 0 &&
            $numMonths == 0 &&
            $numYears == 0 );

        $time0 = CTime::fromString("2009-02-14 05:02:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $numSeconds;
        $numMinutes;
        $numHours;
        $numDays;
        $numWeeks;
        $numMonths;
        $numYears;
        $time0->diffUnits($time1, $numSeconds, $numMinutes, $numHours, $numDays, $numWeeks, $numMonths,
            $numYears);
        $this->assertTrue(
            $numSeconds == 45 &&
            $numMinutes == 2 &&
            $numHours == 5 &&
            $numDays == 1 &&
            $numWeeks == 0 &&
            $numMonths == 0 &&
            $numYears == 0 );

        $time0 = CTime::fromString("2009-02-23 05:02:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $numSeconds;
        $numMinutes;
        $numHours;
        $numDays;
        $numWeeks;
        $numMonths;
        $numYears;
        $time0->diffUnits($time1, $numSeconds, $numMinutes, $numHours, $numDays, $numWeeks, $numMonths,
            $numYears);
        $this->assertTrue(
            $numSeconds == 45 &&
            $numMinutes == 2 &&
            $numHours == 5 &&
            $numDays == 10 &&
            $numWeeks == 1 &&
            $numMonths == 0 &&
            $numYears == 0 );

        $time0 = CTime::fromString("2009-09-23 05:02:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $numSeconds;
        $numMinutes;
        $numHours;
        $numDays;
        $numWeeks;
        $numMonths;
        $numYears;
        $time0->diffUnits($time1, $numSeconds, $numMinutes, $numHours, $numDays, $numWeeks, $numMonths,
            $numYears);
        $this->assertTrue(
            $numSeconds == 45 &&
            $numMinutes == 2 &&
            $numHours == 5 &&
            $numDays == 9 &&
            $numWeeks == 31 &&
            $numMonths == 7 &&
            $numYears == 0 );

        $time0 = CTime::fromString("2012-09-23 05:02:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $numSeconds;
        $numMinutes;
        $numHours;
        $numDays;
        $numWeeks;
        $numMonths;
        $numYears;
        $time0->diffUnits($time1, $numSeconds, $numMinutes, $numHours, $numDays, $numWeeks, $numMonths,
            $numYears);
        $this->assertTrue(
            $numSeconds == 45 &&
            $numMinutes == 2 &&
            $numHours == 5 &&
            $numDays == 9 &&
            $numWeeks == 188 &&
            $numMonths == 7 &&
            $numYears == 3 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $numSeconds;
        $numMinutes;
        $numHours;
        $numDays;
        $numWeeks;
        $numMonths;
        $numYears;
        $time0->diffUnits($time1, $numSeconds, $numMinutes, $numHours, $numDays, $numWeeks, $numMonths,
            $numYears);
        $this->assertTrue(
            $numSeconds == 0 &&
            $numMinutes == 3 &&
            $numHours == 23 &&
            $numDays == 28 &&
            $numWeeks == 4082 &&
            $numMonths == 2 &&
            $numYears == 78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiff ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiff($time1, CTime::SECOND) == -15 );

        $time0 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiff($time1, CTime::MINUTE) == 4 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2010-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiff($time1, CTime::DAY) == -365 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiff($time1, CTime::DAY) == -1269 );

        $time0 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-04-15 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiff($time1, CTime::MONTH) == -2 );

        $time0 = CTime::fromString("2009-04-27 00:00:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 00:00:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiff($time1, CTime::MONTH) == 2 );

        $time0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiff($time1, CTime::YEAR) == -78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInSeconds ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInSeconds($time1) == 15 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:45", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInSeconds($time1) == -15 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInMinutes ()
    {
        $time0 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInMinutes($time1) == 4 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:35:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInMinutes($time1) == -4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInHours ()
    {
        $time0 = CTime::fromString("2009-02-14 01:45:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInHours($time1) == 2 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-14 01:45:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInHours($time1) == -2 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInDays ()
    {
        $time0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInDays($time1) == 1269 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2012-08-05 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInDays($time1) == -1269 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInWeeks ()
    {
        $time0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInWeeks($time1) == 181 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInWeeks($time1) == -181 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInMonths ()
    {
        $time0 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInMonths($time1) == 41 );

        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2012-08-05 23:39:00", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInMonths($time1) == -41 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSignedDiffInYears ()
    {
        $time0 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInYears($time1) == 78 );

        $time0 = CTime::fromString("1930-11-18 00:28:30", CTime::PATTERN_MYSQL);
        $time1 = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $this->assertTrue( $time0->signedDiffInYears($time1) == -78 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::SECOND, 15);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:45"));

        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::MINUTE, -4);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:27:30"));

        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::HOUR, 5);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-14 04:31:30"));

        $time = CTime::fromString("2010-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::DAY, -365);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $time = CTime::fromString("2012-08-05 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::DAY, -1273);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-09 23:31:30"));

        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::WEEK, 30);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-09-11 23:31:30"));

        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::MONTH, -3);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2008-11-13 23:31:30"));

        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::MONTH, 18);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2010-08-13 23:31:30"));

        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedUtc(CTime::YEAR, -79);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedBySecondsUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedBySecondsUtc(15);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:45"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMinutesUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedByMinutesUtc(-4);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:27:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByHoursUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedByHoursUtc(5);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-14 04:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByDaysUtc ()
    {
        $time = CTime::fromString("2012-08-05 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedByDaysUtc(-1273);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-09 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByWeeksUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedByWeeksUtc(30);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-09-11 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMonthsUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedByMonthsUtc(18);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2010-08-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByYearsUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->shiftedByYearsUtc(-79);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::SECOND, 15);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:45"));

        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::MINUTE, -4);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:27:30"));

        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::HOUR, 5);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-14 04:31:30"));

        $time = CTime::fromString("2010-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::DAY, -365);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:30"));

        $time = CTime::fromString("2012-08-05 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::DAY, -1273);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-09 23:31:30"));

        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::WEEK, 30);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-09-11 23:31:30"));

        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::MONTH, -3);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2008-11-13 23:31:30"));

        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::MONTH, 18);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2010-08-13 23:31:30"));

        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::YEAR, -79);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedBySecondsInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedBySecondsInTimeZone(new CTimeZone("Europe/Helsinki"), 15);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:45"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMinutesInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedByMinutesInTimeZone(new CTimeZone("Europe/Helsinki"), -4);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:27:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByHoursInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedByHoursInTimeZone(new CTimeZone("Europe/Helsinki"), 5);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-14 04:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByDaysInTimeZone ()
    {
        $time = CTime::fromString("2012-08-05 23:31:30 Europe/Helsinki");
        $time = $time->shiftedByDaysInTimeZone(new CTimeZone("Europe/Helsinki"), -1273);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-09 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByWeeksInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedByWeeksInTimeZone(new CTimeZone("Europe/Helsinki"), 30);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-09-11 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMonthsInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedByMonthsInTimeZone(new CTimeZone("Europe/Helsinki"), 18);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2010-08-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByYearsInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->shiftedByYearsInTimeZone(new CTimeZone("Europe/Helsinki"), -79);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::SECOND, 15);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:45"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::MINUTE, -4);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:27:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::HOUR, 5);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-14 04:31:30"));

        $year = 2010;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::DAY, -365);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30"));

        $year = 2012;
        $month = 8;
        $day = 5;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::DAY, -1273);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-09 23:31:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::WEEK, 30);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-09-11 23:31:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::MONTH, -3);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2008-11-13 23:31:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::MONTH, 18);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2010-08-13 23:31:30"));

        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedLocal(CTime::YEAR, -79);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedBySecondsLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedBySecondsLocal(15);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:45"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMinutesLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedByMinutesLocal(-4);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:27:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByHoursLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedByHoursLocal(5);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-14 04:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByDaysLocal ()
    {
        $year = 2012;
        $month = 8;
        $day = 5;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedByDaysLocal(-1273);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-09 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByWeeksLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedByWeeksLocal(30);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-09-11 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByMonthsLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedByMonthsLocal(18);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2010-08-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShiftedByYearsLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->shiftedByYearsLocal(-79);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMillisecondUtc ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $millisecond = 250;
        $time = CTime::fromComponentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        $time = $time->withMillisecondUtc(750);
        $this->assertTrue( $time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30") &&
            $time->MTime() === 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithSecondUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->withSecondUtc(59);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:59"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMinuteUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->withMinuteUtc(10);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:10:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithHourUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->withHourUtc(11);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-13 11:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithDayUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->withDayUtc(23);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-02-23 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMonthUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->withMonthUtc(5);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("2009-05-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithYearUtc ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30", CTime::PATTERN_MYSQL);
        $time = $time->withYearUtc(1930);
        $this->assertTrue($time->toStringUtc(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMillisecondInTimeZone ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $millisecond = 250;
        $time = CTime::fromComponentsInTimeZone(new CTimeZone("Europe/Helsinki"), $year, $month, $day, $hour,
            $minute, $second, $millisecond);
        $time = $time->withMillisecondInTimeZone(new CTimeZone("Europe/Helsinki"), 750);
        $this->assertTrue( $time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:30") && $time->MTime() === 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithSecondInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->withSecondInTimeZone(new CTimeZone("Europe/Helsinki"), 59);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:31:59"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMinuteInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->withMinuteInTimeZone(new CTimeZone("Europe/Helsinki"), 10);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 23:10:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithHourInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->withHourInTimeZone(new CTimeZone("Europe/Helsinki"), 11);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-13 11:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithDayInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->withDayInTimeZone(new CTimeZone("Europe/Helsinki"), 23);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-02-23 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMonthInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->withMonthInTimeZone(new CTimeZone("Europe/Helsinki"), 5);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "2009-05-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithYearInTimeZone ()
    {
        $time = CTime::fromString("2009-02-13 23:31:30 Europe/Helsinki");
        $time = $time->withYearInTimeZone(new CTimeZone("Europe/Helsinki"), 1930);
        $this->assertTrue($time->toStringInTimeZone(new CTimeZone("Europe/Helsinki"), CTime::PATTERN_MYSQL)->equals(
            "1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMillisecondLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $millisecond = 250;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        $time = $time->withMillisecondLocal(750);
        $this->assertTrue( $time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:30") &&
            $time->MTime() === 750 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithSecondLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->withSecondLocal(59);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:31:59"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMinuteLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->withMinuteLocal(10);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 23:10:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithHourLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->withHourLocal(11);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-13 11:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithDayLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->withDayLocal(23);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-02-23 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithMonthLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->withMonthLocal(5);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("2009-05-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testWithYearLocal ()
    {
        $year = 2009;
        $month = 2;
        $day = 13;
        $hour = 23;
        $minute = 31;
        $second = 30;
        $time = CTime::fromComponentsLocal($year, $month, $day, $hour, $minute, $second);
        $time = $time->withYearLocal(1930);
        $this->assertTrue($time->toStringLocal(CTime::PATTERN_MYSQL)->equals("1930-02-13 23:31:30"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
