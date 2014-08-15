<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CULocaleTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $oLocale = new CULocale(CULocale::GERMAN_GERMANY);
        $this->assertTrue($oLocale->name()->equals("de_DE"));

        $oLocale = new CULocale("DE_de");
        $this->assertTrue($oLocale->name()->equals("de_DE"));

        $oLocale = new CULocale("DE_lATN_de_variant0_variant1@KEYWORD0=VALUE0;KEYWORD1=VALUE1");
        $this->assertTrue($oLocale->name()->equals("de_Latn_DE_VARIANT0_VARIANT1@keyword0=VALUE0;keyword1=VALUE1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCountryCode ()
    {
        $oLocale = CULocale::fromCountryCode("DE");
        $this->assertTrue($oLocale->name()->equals("de_DE"));

        $oLocale = CULocale::fromCountryCode("de");
        $this->assertTrue($oLocale->name()->equals("de_DE"));

        $oLocale = CULocale::fromCountryCode("US");
        $this->assertTrue($oLocale->name()->equals("en_US"));

        $oLocale = CULocale::fromCountryCode("GB");
        $this->assertTrue($oLocale->name()->equals("en_GB"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromComponents ()
    {
        $oLocale = CULocale::fromComponents("de");
        $this->assertTrue($oLocale->name()->equals("de"));

        $oLocale = CULocale::fromComponents("DE");
        $this->assertTrue($oLocale->name()->equals("de"));

        $oLocale = CULocale::fromComponents("de", "BE");
        $this->assertTrue($oLocale->name()->equals("de_BE"));

        $oLocale = CULocale::fromComponents("de", "be", "Latn");
        $this->assertTrue($oLocale->name()->equals("de_Latn_BE"));

        $oLocale = CULocale::fromComponents("de", "BE", "Latn", "VARIANT");
        $this->assertTrue($oLocale->name()->equals("de_Latn_BE_VARIANT"));

        $oLocale = CULocale::fromComponents("de", "BE", "latn", "VARIANT0", "VARIANT1");
        $this->assertTrue($oLocale->name()->equals("de_Latn_BE_VARIANT0_VARIANT1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromRfc2616 ()
    {
        $oLocale = CULocale::fromRfc2616("en-us");
        $this->assertTrue($oLocale->name()->equals("en_US"));

        $oLocale = CULocale::fromRfc2616("de-de");
        $this->assertTrue($oLocale->name()->equals("de_DE"));

        $oLocale = CULocale::fromRfc2616("fr");
        $this->assertTrue($oLocale->name()->equals("fr"));

        $bSuccess;
        $oLocale = CULocale::fromRfc2616("de_de", $bSuccess);
        $this->assertTrue( $oLocale->name()->equals("de_DE") && $bSuccess );

        $bSuccess;
        $oLocale = CULocale::fromRfc2616("\x00", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMakeDefault ()
    {
        $oLocale = CULocale::makeDefault();
        $this->assertTrue($oLocale->name()->equals("en_US"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testName ()
    {
        $oLocale = new CULocale(CULocale::GERMAN_GERMANY);
        $this->assertTrue($oLocale->name()->equals("de_DE"));

        $oLocale = new CULocale("az_latn_az");
        $this->assertTrue($oLocale->name()->equals("az_Latn_AZ"));

        $oLocale = new CULocale("az_latn_az_variant");
        $this->assertTrue($oLocale->name()->equals("az_Latn_AZ_VARIANT"));

        $oLocale = new CULocale("az_latn_az_variant@keyword=value");
        $this->assertTrue($oLocale->name()->equals("az_Latn_AZ_VARIANT@keyword=value"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLanguageCode ()
    {
        $oLocale = new CULocale(CULocale::GERMAN_GERMANY);
        $this->assertTrue($oLocale->languageCode()->equals("de"));

        $oLocale = new CULocale("EN_US");
        $this->assertTrue($oLocale->languageCode()->equals("en"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasRegionCode ()
    {
        $oLocale = new CULocale("de_DE");
        $this->assertTrue($oLocale->hasRegionCode());

        $oLocale = new CULocale("de");
        $this->assertFalse($oLocale->hasRegionCode());

        $oLocale = new CULocale("de_Latn");
        $this->assertFalse($oLocale->hasRegionCode());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRegionCode ()
    {
        $oLocale = new CULocale("de_DE");
        $this->assertTrue($oLocale->regionCode()->equals("DE"));

        $oLocale = new CULocale("en_US");
        $this->assertTrue($oLocale->regionCode()->equals("US"));

        $oLocale = new CULocale("en_Latn_gb");
        $this->assertTrue($oLocale->regionCode()->equals("GB"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasScriptCode ()
    {
        $oLocale = new CULocale("de_Latn_DE");
        $this->assertTrue($oLocale->hasScriptCode());

        $oLocale = new CULocale("de_DE");
        $this->assertFalse($oLocale->hasScriptCode());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testScriptCode ()
    {
        $oLocale = new CULocale("de_Latn_DE");
        $this->assertTrue($oLocale->scriptCode()->equals("Latn"));

        $oLocale = new CULocale("sr_Cyrl");
        $this->assertTrue($oLocale->scriptCode()->equals("Cyrl"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasVariants ()
    {
        $oLocale = new CULocale("de_DE_VARIANT");
        $this->assertTrue($oLocale->hasVariants());

        $oLocale = new CULocale("en_GB");
        $this->assertFalse($oLocale->hasVariants());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testVariants ()
    {
        $oLocale = new CULocale("de_DE_VARIANT");
        $this->assertTrue($oLocale->variants()->equals(a("VARIANT")));

        $oLocale = new CULocale("de_DE_VARIANT0_VARIANT1");
        $this->assertTrue($oLocale->variants()->equals(a("VARIANT0", "VARIANT1")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasKeywords ()
    {
        $oLocale = new CULocale("de_DE@keyword=value");
        $this->assertTrue($oLocale->hasKeywords());

        $oLocale = new CULocale("de_DE");
        $this->assertFalse($oLocale->hasKeywords());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKeywords ()
    {
        $oLocale = new CULocale("de_DE@keyword=value");
        $this->assertTrue($oLocale->keywords()->equals(m(["keyword" => "value"])));

        $oLocale = new CULocale("de_DE@keyword0=value0;keyword1=value1");
        $this->assertTrue($oLocale->keywords()->equals(m(["keyword0" => "value0", "keyword1" => "value1"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponents ()
    {
        $oLocale = new CULocale("de_Latn_DE_VARIANT0_VARIANT1@keyword0=value0;keyword1=value1");
        $sLanguage;
        $sRegion;
        $sScript;
        $aVariants;
        $mKeywords;
        $oLocale->components($sLanguage, $sRegion, $sScript, $aVariants, $mKeywords);
        $this->assertTrue( $sLanguage->equals("de") && $sRegion->equals("DE") && $sScript->equals("Latn") &&
            $aVariants->equals(a("VARIANT0", "VARIANT1")) &&
            $mKeywords->equals(m(["keyword0" => "value0", "keyword1" => "value1"])) );

        $oLocale = new CULocale("DE_lATN_de_variant0_variant1@KEYWORD0=VALUE0;KEYWORD1=VALUE1");
        $sLanguage;
        $sRegion;
        $sScript;
        $aVariants;
        $mKeywords;
        $oLocale->components($sLanguage, $sRegion, $sScript, $aVariants, $mKeywords);
        $this->assertTrue( $sLanguage->equals("de") && $sRegion->equals("DE") && $sScript->equals("Latn") &&
            $aVariants->equals(a("VARIANT0", "VARIANT1")) &&
            $mKeywords->equals(m(["keyword0" => "VALUE0", "keyword1" => "VALUE1"])) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispName ()
    {
        $oLocale = new CULocale("de_DE");
        $this->assertTrue($oLocale->dispName()->equals("German (Germany)"));

        $oLocale = new CULocale("de_DE");
        $this->assertTrue($oLocale->dispName(new CULocale("de_DE"))->equals("Deutsch (Deutschland)"));

        $oLocale = new CULocale("de_Latn_DE_VARIANT0_VARIANT1@keyword0=value0;keyword1=value1");
        $this->assertTrue($oLocale->dispName()->equals(
            "German (Latin, Germany, VARIANT0_VARIANT1, keyword0=value0, keyword1=value1)"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispLanguage ()
    {
        $oLocale = new CULocale("de_DE");
        $this->assertTrue($oLocale->dispLanguage()->equals("German"));

        $oLocale = new CULocale("de_DE");
        $this->assertTrue($oLocale->dispLanguage(new CULocale("de_DE"))->equals("Deutsch"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispRegion ()
    {
        $oLocale = new CULocale("de_DE");
        $this->assertTrue($oLocale->dispRegion()->equals("Germany"));

        $oLocale = new CULocale("de_DE");
        $this->assertTrue($oLocale->dispRegion(new CULocale("de_DE"))->equals("Deutschland"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispScript ()
    {
        $oLocale = new CULocale("de_Latn_DE");
        $this->assertTrue($oLocale->dispScript()->equals("Latin"));

        $oLocale = new CULocale("de_Latn_DE");
        $this->assertTrue($oLocale->dispScript(new CULocale("de_DE"))->equals("Lateinisch"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispVariants ()
    {
        $oLocale = new CULocale("de_DE_VARIANT0_VARIANT1");
        $this->assertTrue($oLocale->dispVariants()->equals("VARIANT0_VARIANT1"));

        $oLocale = new CULocale("de_DE_VARIANT0_VARIANT1");
        $this->assertTrue($oLocale->dispVariants(new CULocale("de_DE"))->equals("VARIANT0_VARIANT1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAddKeyword ()
    {
        $oLocale = new CULocale("de_DE");
        $oLocale->addKeyword("keyword0", "value0");
        $oLocale->addKeyword("keyword1", "value1");
        $this->assertTrue($oLocale->name()->equals("de_DE@keyword0=value0;keyword1=value1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $oLocale0 = new CULocale("de_DE");
        $oLocale1 = new CULocale("de_de");
        $this->assertTrue($oLocale0->equals($oLocale1));

        $oLocale0 = new CULocale("de_DE");
        $oLocale1 = new CULocale("de_BE");
        $this->assertFalse($oLocale0->equals($oLocale1));

        $oLocale0 = new CULocale("de_DE");
        $oLocale1 = new CULocale("de_Latn_DE");
        $this->assertFalse($oLocale0->equals($oLocale1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownCountryCodes ()
    {
        $aCodes = CULocale::knownCountryCodes();
        $this->assertTrue( $aCodes->find("DE") && $aCodes->find("BE") && $aCodes->find("US") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsCountryCodeKnown ()
    {
        $this->assertTrue(CULocale::isCountryCodeKnown("DE"));
        $this->assertTrue(CULocale::isCountryCodeKnown("BE"));
        $this->assertTrue(CULocale::isCountryCodeKnown("US"));
        $this->assertTrue(CULocale::isCountryCodeKnown("us"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountryEnNameForCountryCode ()
    {
        $this->assertTrue(CULocale::countryEnNameForCountryCode("DE")->equals("Germany"));
        $this->assertTrue(CULocale::countryEnNameForCountryCode("BE")->equals("Belgium"));
        $this->assertTrue(CULocale::countryEnNameForCountryCode("US")->equals("United States"));
        $this->assertTrue(CULocale::countryEnNameForCountryCode("us")->equals("United States"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCurrencyForCountryCode ()
    {
        $this->assertTrue(CULocale::currencyForCountryCode("DE")->equals("EUR"));
        $this->assertTrue(CULocale::currencyForCountryCode("BE")->equals("EUR"));
        $this->assertTrue(CULocale::currencyForCountryCode("US")->equals("USD"));
        $this->assertTrue(CULocale::currencyForCountryCode("us")->equals("USD"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsValid ()
    {
        $this->assertTrue(CULocale::isValid("de_DE"));
        $this->assertTrue(CULocale::isValid("en_US"));
        $this->assertTrue(CULocale::isValid("en_gb"));
        $this->assertTrue(CULocale::isValid("en-gb"));
        $this->assertTrue(CULocale::isValid("de_Latn_DE"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDefaultLocaleName ()
    {
        $this->assertTrue(CULocale::defaultLocaleName()->equals("en_US"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
