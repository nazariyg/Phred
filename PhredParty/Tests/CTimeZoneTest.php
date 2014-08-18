<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CTimeZoneTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $timeZone = new CTimeZone("UTC");
        $this->assertTrue($timeZone->name()->equals(CTimeZone::makeUtc()->name()));

        $timeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($timeZone->name()->equals("Europe/Helsinki"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testName ()
    {
        $timeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($timeZone->name()->equals("Europe/Helsinki"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispName ()
    {
        // $timeZone = new CTimeZone("Europe/Helsinki");
        // $this->assertTrue($timeZone->dispName(CTimeZone::STYLE_LONG)->equals("Eastern European Time"));
        // $timeZone = new CTimeZone("America/Los_Angeles");
        // $this->assertTrue($timeZone->dispName(CTimeZone::STYLE_LONG)->equals("Pacific Standard Time"));
        // $timeZone = CTimeZone::makeUtc();
        // $this->assertTrue($timeZone->dispName(CTimeZone::STYLE_LONG)->equals("GMT+00:00"));

        // $timeZone = new CTimeZone("Europe/Helsinki");
        // $this->assertTrue($timeZone->dispName(CTimeZone::STYLE_SHORT)->equals("EET"));
        // $timeZone = new CTimeZone("America/Los_Angeles");
        // $this->assertTrue($timeZone->dispName(CTimeZone::STYLE_SHORT)->equals("PST"));
        // $timeZone = CTimeZone::makeUtc();
        // $this->assertTrue($timeZone->dispName(CTimeZone::STYLE_SHORT)->equals("GMT+00:00"));

        // $timeZone = new CTimeZone("Europe/Helsinki");
        // $this->assertTrue(
        //     $timeZone->dispName(CTimeZone::STYLE_LONG, new CULocale(CULocale::FINNISH_FINLAND))->equals(
        //     "Itä-Euroopan normaaliaika"));
        // $timeZone = new CTimeZone("America/Los_Angeles");
        // $this->assertTrue(
        //     $timeZone->dispName(CTimeZone::STYLE_LONG, new CULocale(CULocale::FINNISH_FINLAND))->equals(
        //     "Yhdysvaltain Tyynenmeren normaaliaika"));
        // $timeZone = CTimeZone::makeUtc();
        // $this->assertTrue(
        //     $timeZone->dispName(CTimeZone::STYLE_LONG, new CULocale(CULocale::FINNISH_FINLAND))->equals(
        //     "UTC+0.00"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispEnName ()
    {
        $timeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($timeZone->dispEnName()->equals("Europe, Helsinki"));

        $timeZone = CTimeZone::makeUtc();
        $this->assertTrue($timeZone->dispEnName()->equals("UTC"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispEnNameWithoutRegion ()
    {
        $timeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($timeZone->dispEnNameWithoutRegion()->equals("Helsinki"));

        $timeZone = CTimeZone::makeUtc();
        $this->assertTrue($timeZone->dispEnNameWithoutRegion()->equals("UTC"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCurrentOffsetSeconds ()
    {
        $timeZone = CTimeZone::makeUtc();
        $this->assertTrue( $timeZone->currentOffsetSeconds() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStandardOffsetSeconds ()
    {
        $timeZone = CTimeZone::makeUtc();
        $this->assertTrue( $timeZone->standardOffsetSeconds() == 0*3600 );

        $timeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue( $timeZone->standardOffsetSeconds() == 2*3600 );

        $timeZone = new CTimeZone("America/Los_Angeles");
        $this->assertTrue( $timeZone->standardOffsetSeconds() == -8*3600 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $timeZone0 = new CTimeZone("Europe/Helsinki");
        $timeZone1 = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($timeZone0->equals($timeZone1));

        $timeZone0 = new CTimeZone("Europe/Helsinki");
        $timeZone1 = new CTimeZone("America/Los_Angeles");
        $this->assertFalse($timeZone0->equals($timeZone1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispEnRegion ()
    {
        $this->assertTrue(CTimeZone::dispEnRegion(CTimeZone::REGION_ASIA)->equals("Asia"));
        $this->assertTrue(CTimeZone::dispEnRegion(CTimeZone::REGION_AUSTRALIA)->equals("Australia"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownRegions ()
    {
        $regions = CTimeZone::knownRegions();
        $this->assertTrue($regions->find(CTimeZone::REGION_ASIA));
        $this->assertTrue($regions->find(CTimeZone::REGION_AUSTRALIA));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownNames ()
    {
        $names = CTimeZone::knownNames();
        $names->sort("CString::compare");
        $this->assertTrue($names->findBinary("Europe/Helsinki"));
        $this->assertTrue($names->findBinary("America/Los_Angeles"));
        $this->assertTrue($names->findBinary("UTC"));
        $this->assertFalse($names->findBinary("Navajo"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownNamesWithBc ()
    {
        $names = CTimeZone::knownNamesWithBc();
        $names->sort("CString::compare");
        $this->assertTrue($names->findBinary("Europe/Helsinki", "CString::compare"));
        $this->assertTrue($names->findBinary("America/Los_Angeles", "CString::compare"));
        $this->assertTrue($names->findBinary("UTC", "CString::compare"));
        $this->assertTrue($names->findBinary("Navajo", "CString::compare"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownNamesForRegion ()
    {
        $names = CTimeZone::knownNamesForRegion(CTimeZone::REGION_EUROPE);
        $this->assertTrue($names->find("Europe/Helsinki"));
        $this->assertFalse($names->find("America/Los_Angeles"));

        $names = CTimeZone::knownNamesForRegion(CTimeZone::REGION_AMERICA);
        $this->assertTrue($names->find("America/Los_Angeles"));
        $this->assertFalse($names->find("Europe/Helsinki"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownNamesForCountry ()
    {
        $names = CTimeZone::knownNamesForCountry("FI");
        $this->assertTrue($names->find("Europe/Helsinki"));
        $this->assertFalse($names->find("America/Los_Angeles"));

        $names = CTimeZone::knownNamesForCountry("US");
        $this->assertTrue($names->find("America/Los_Angeles"));
        $this->assertFalse($names->find("Europe/Helsinki"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsNameKnown ()
    {
        $this->assertTrue(CTimeZone::isNameKnown("Europe/Helsinki"));
        $this->assertTrue(CTimeZone::isNameKnown("America/Los_Angeles"));
        $this->assertFalse(CTimeZone::isNameKnown("Neverland/Nevertown"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
