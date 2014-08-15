<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
        $oTimeZone = new CTimeZone("UTC");
        $this->assertTrue($oTimeZone->name()->equals(CTimeZone::makeUtc()->name()));

        $oTimeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($oTimeZone->name()->equals("Europe/Helsinki"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testName ()
    {
        $oTimeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($oTimeZone->name()->equals("Europe/Helsinki"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispName ()
    {
        // $oTimeZone = new CTimeZone("Europe/Helsinki");
        // $this->assertTrue($oTimeZone->dispName(CTimeZone::STYLE_LONG)->equals("Eastern European Time"));
        // $oTimeZone = new CTimeZone("America/Los_Angeles");
        // $this->assertTrue($oTimeZone->dispName(CTimeZone::STYLE_LONG)->equals("Pacific Standard Time"));
        // $oTimeZone = CTimeZone::makeUtc();
        // $this->assertTrue($oTimeZone->dispName(CTimeZone::STYLE_LONG)->equals("GMT+00:00"));

        // $oTimeZone = new CTimeZone("Europe/Helsinki");
        // $this->assertTrue($oTimeZone->dispName(CTimeZone::STYLE_SHORT)->equals("EET"));
        // $oTimeZone = new CTimeZone("America/Los_Angeles");
        // $this->assertTrue($oTimeZone->dispName(CTimeZone::STYLE_SHORT)->equals("PST"));
        // $oTimeZone = CTimeZone::makeUtc();
        // $this->assertTrue($oTimeZone->dispName(CTimeZone::STYLE_SHORT)->equals("GMT+00:00"));

        // $oTimeZone = new CTimeZone("Europe/Helsinki");
        // $this->assertTrue(
        //     $oTimeZone->dispName(CTimeZone::STYLE_LONG, new CULocale(CULocale::FINNISH_FINLAND))->equals(
        //     "Itä-Euroopan normaaliaika"));
        // $oTimeZone = new CTimeZone("America/Los_Angeles");
        // $this->assertTrue(
        //     $oTimeZone->dispName(CTimeZone::STYLE_LONG, new CULocale(CULocale::FINNISH_FINLAND))->equals(
        //     "Yhdysvaltain Tyynenmeren normaaliaika"));
        // $oTimeZone = CTimeZone::makeUtc();
        // $this->assertTrue(
        //     $oTimeZone->dispName(CTimeZone::STYLE_LONG, new CULocale(CULocale::FINNISH_FINLAND))->equals(
        //     "UTC+0.00"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispEnName ()
    {
        $oTimeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($oTimeZone->dispEnName()->equals("Europe, Helsinki"));

        $oTimeZone = CTimeZone::makeUtc();
        $this->assertTrue($oTimeZone->dispEnName()->equals("UTC"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispEnNameWithoutRegion ()
    {
        $oTimeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($oTimeZone->dispEnNameWithoutRegion()->equals("Helsinki"));

        $oTimeZone = CTimeZone::makeUtc();
        $this->assertTrue($oTimeZone->dispEnNameWithoutRegion()->equals("UTC"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCurrentOffsetSeconds ()
    {
        $oTimeZone = CTimeZone::makeUtc();
        $this->assertTrue( $oTimeZone->currentOffsetSeconds() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testStandardOffsetSeconds ()
    {
        $oTimeZone = CTimeZone::makeUtc();
        $this->assertTrue( $oTimeZone->standardOffsetSeconds() == 0*3600 );

        $oTimeZone = new CTimeZone("Europe/Helsinki");
        $this->assertTrue( $oTimeZone->standardOffsetSeconds() == 2*3600 );

        $oTimeZone = new CTimeZone("America/Los_Angeles");
        $this->assertTrue( $oTimeZone->standardOffsetSeconds() == -8*3600 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $oTimeZone0 = new CTimeZone("Europe/Helsinki");
        $oTimeZone1 = new CTimeZone("Europe/Helsinki");
        $this->assertTrue($oTimeZone0->equals($oTimeZone1));

        $oTimeZone0 = new CTimeZone("Europe/Helsinki");
        $oTimeZone1 = new CTimeZone("America/Los_Angeles");
        $this->assertFalse($oTimeZone0->equals($oTimeZone1));
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
        $aRegions = CTimeZone::knownRegions();
        $this->assertTrue($aRegions->find(CTimeZone::REGION_ASIA));
        $this->assertTrue($aRegions->find(CTimeZone::REGION_AUSTRALIA));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownNames ()
    {
        $aNames = CTimeZone::knownNames();
        $aNames->sort("CString::compare");
        $this->assertTrue($aNames->findBinary("Europe/Helsinki"));
        $this->assertTrue($aNames->findBinary("America/Los_Angeles"));
        $this->assertTrue($aNames->findBinary("UTC"));
        $this->assertFalse($aNames->findBinary("Navajo"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownNamesWithBc ()
    {
        $aNames = CTimeZone::knownNamesWithBc();
        $aNames->sort("CString::compare");
        $this->assertTrue($aNames->findBinary("Europe/Helsinki", "CString::compare"));
        $this->assertTrue($aNames->findBinary("America/Los_Angeles", "CString::compare"));
        $this->assertTrue($aNames->findBinary("UTC", "CString::compare"));
        $this->assertTrue($aNames->findBinary("Navajo", "CString::compare"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownNamesForRegion ()
    {
        $aNames = CTimeZone::knownNamesForRegion(CTimeZone::REGION_EUROPE);
        $this->assertTrue($aNames->find("Europe/Helsinki"));
        $this->assertFalse($aNames->find("America/Los_Angeles"));

        $aNames = CTimeZone::knownNamesForRegion(CTimeZone::REGION_AMERICA);
        $this->assertTrue($aNames->find("America/Los_Angeles"));
        $this->assertFalse($aNames->find("Europe/Helsinki"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownNamesForCountry ()
    {
        $aNames = CTimeZone::knownNamesForCountry("FI");
        $this->assertTrue($aNames->find("Europe/Helsinki"));
        $this->assertFalse($aNames->find("America/Los_Angeles"));

        $aNames = CTimeZone::knownNamesForCountry("US");
        $this->assertTrue($aNames->find("America/Los_Angeles"));
        $this->assertFalse($aNames->find("Europe/Helsinki"));
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
