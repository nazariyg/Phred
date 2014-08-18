<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
        $locale = new CULocale(CULocale::GERMAN_GERMANY);
        $this->assertTrue($locale->name()->equals("de_DE"));

        $locale = new CULocale("DE_de");
        $this->assertTrue($locale->name()->equals("de_DE"));

        $locale = new CULocale("DE_lATN_de_variant0_variant1@KEYWORD0=VALUE0;KEYWORD1=VALUE1");
        $this->assertTrue($locale->name()->equals("de_Latn_DE_VARIANT0_VARIANT1@keyword0=VALUE0;keyword1=VALUE1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromCountryCode ()
    {
        $locale = CULocale::fromCountryCode("DE");
        $this->assertTrue($locale->name()->equals("de_DE"));

        $locale = CULocale::fromCountryCode("de");
        $this->assertTrue($locale->name()->equals("de_DE"));

        $locale = CULocale::fromCountryCode("US");
        $this->assertTrue($locale->name()->equals("en_US"));

        $locale = CULocale::fromCountryCode("GB");
        $this->assertTrue($locale->name()->equals("en_GB"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromComponents ()
    {
        $locale = CULocale::fromComponents("de");
        $this->assertTrue($locale->name()->equals("de"));

        $locale = CULocale::fromComponents("DE");
        $this->assertTrue($locale->name()->equals("de"));

        $locale = CULocale::fromComponents("de", "BE");
        $this->assertTrue($locale->name()->equals("de_BE"));

        $locale = CULocale::fromComponents("de", "be", "Latn");
        $this->assertTrue($locale->name()->equals("de_Latn_BE"));

        $locale = CULocale::fromComponents("de", "BE", "Latn", "VARIANT");
        $this->assertTrue($locale->name()->equals("de_Latn_BE_VARIANT"));

        $locale = CULocale::fromComponents("de", "BE", "latn", "VARIANT0", "VARIANT1");
        $this->assertTrue($locale->name()->equals("de_Latn_BE_VARIANT0_VARIANT1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromRfc2616 ()
    {
        $locale = CULocale::fromRfc2616("en-us");
        $this->assertTrue($locale->name()->equals("en_US"));

        $locale = CULocale::fromRfc2616("de-de");
        $this->assertTrue($locale->name()->equals("de_DE"));

        $locale = CULocale::fromRfc2616("fr");
        $this->assertTrue($locale->name()->equals("fr"));

        $success;
        $locale = CULocale::fromRfc2616("de_de", $success);
        $this->assertTrue( $locale->name()->equals("de_DE") && $success );

        $success;
        $locale = CULocale::fromRfc2616("\x00", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMakeDefault ()
    {
        $locale = CULocale::makeDefault();
        $this->assertTrue($locale->name()->equals("en_US"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testName ()
    {
        $locale = new CULocale(CULocale::GERMAN_GERMANY);
        $this->assertTrue($locale->name()->equals("de_DE"));

        $locale = new CULocale("az_latn_az");
        $this->assertTrue($locale->name()->equals("az_Latn_AZ"));

        $locale = new CULocale("az_latn_az_variant");
        $this->assertTrue($locale->name()->equals("az_Latn_AZ_VARIANT"));

        $locale = new CULocale("az_latn_az_variant@keyword=value");
        $this->assertTrue($locale->name()->equals("az_Latn_AZ_VARIANT@keyword=value"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLanguageCode ()
    {
        $locale = new CULocale(CULocale::GERMAN_GERMANY);
        $this->assertTrue($locale->languageCode()->equals("de"));

        $locale = new CULocale("EN_US");
        $this->assertTrue($locale->languageCode()->equals("en"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasRegionCode ()
    {
        $locale = new CULocale("de_DE");
        $this->assertTrue($locale->hasRegionCode());

        $locale = new CULocale("de");
        $this->assertFalse($locale->hasRegionCode());

        $locale = new CULocale("de_Latn");
        $this->assertFalse($locale->hasRegionCode());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRegionCode ()
    {
        $locale = new CULocale("de_DE");
        $this->assertTrue($locale->regionCode()->equals("DE"));

        $locale = new CULocale("en_US");
        $this->assertTrue($locale->regionCode()->equals("US"));

        $locale = new CULocale("en_Latn_gb");
        $this->assertTrue($locale->regionCode()->equals("GB"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasScriptCode ()
    {
        $locale = new CULocale("de_Latn_DE");
        $this->assertTrue($locale->hasScriptCode());

        $locale = new CULocale("de_DE");
        $this->assertFalse($locale->hasScriptCode());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testScriptCode ()
    {
        $locale = new CULocale("de_Latn_DE");
        $this->assertTrue($locale->scriptCode()->equals("Latn"));

        $locale = new CULocale("sr_Cyrl");
        $this->assertTrue($locale->scriptCode()->equals("Cyrl"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasVariants ()
    {
        $locale = new CULocale("de_DE_VARIANT");
        $this->assertTrue($locale->hasVariants());

        $locale = new CULocale("en_GB");
        $this->assertFalse($locale->hasVariants());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testVariants ()
    {
        $locale = new CULocale("de_DE_VARIANT");
        $this->assertTrue($locale->variants()->equals(a("VARIANT")));

        $locale = new CULocale("de_DE_VARIANT0_VARIANT1");
        $this->assertTrue($locale->variants()->equals(a("VARIANT0", "VARIANT1")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasKeywords ()
    {
        $locale = new CULocale("de_DE@keyword=value");
        $this->assertTrue($locale->hasKeywords());

        $locale = new CULocale("de_DE");
        $this->assertFalse($locale->hasKeywords());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKeywords ()
    {
        $locale = new CULocale("de_DE@keyword=value");
        $this->assertTrue($locale->keywords()->equals(m(["keyword" => "value"])));

        $locale = new CULocale("de_DE@keyword0=value0;keyword1=value1");
        $this->assertTrue($locale->keywords()->equals(m(["keyword0" => "value0", "keyword1" => "value1"])));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponents ()
    {
        $locale = new CULocale("de_Latn_DE_VARIANT0_VARIANT1@keyword0=value0;keyword1=value1");
        $language;
        $region;
        $script;
        $variants;
        $keywords;
        $locale->components($language, $region, $script, $variants, $keywords);
        $this->assertTrue( $language->equals("de") && $region->equals("DE") && $script->equals("Latn") &&
            $variants->equals(a("VARIANT0", "VARIANT1")) &&
            $keywords->equals(m(["keyword0" => "value0", "keyword1" => "value1"])) );

        $locale = new CULocale("DE_lATN_de_variant0_variant1@KEYWORD0=VALUE0;KEYWORD1=VALUE1");
        $language;
        $region;
        $script;
        $variants;
        $keywords;
        $locale->components($language, $region, $script, $variants, $keywords);
        $this->assertTrue( $language->equals("de") && $region->equals("DE") && $script->equals("Latn") &&
            $variants->equals(a("VARIANT0", "VARIANT1")) &&
            $keywords->equals(m(["keyword0" => "VALUE0", "keyword1" => "VALUE1"])) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispName ()
    {
        $locale = new CULocale("de_DE");
        $this->assertTrue($locale->dispName()->equals("German (Germany)"));

        $locale = new CULocale("de_DE");
        $this->assertTrue($locale->dispName(new CULocale("de_DE"))->equals("Deutsch (Deutschland)"));

        $locale = new CULocale("de_Latn_DE_VARIANT0_VARIANT1@keyword0=value0;keyword1=value1");
        $this->assertTrue($locale->dispName()->equals(
            "German (Latin, Germany, VARIANT0_VARIANT1, keyword0=value0, keyword1=value1)"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispLanguage ()
    {
        $locale = new CULocale("de_DE");
        $this->assertTrue($locale->dispLanguage()->equals("German"));

        $locale = new CULocale("de_DE");
        $this->assertTrue($locale->dispLanguage(new CULocale("de_DE"))->equals("Deutsch"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispRegion ()
    {
        $locale = new CULocale("de_DE");
        $this->assertTrue($locale->dispRegion()->equals("Germany"));

        $locale = new CULocale("de_DE");
        $this->assertTrue($locale->dispRegion(new CULocale("de_DE"))->equals("Deutschland"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispScript ()
    {
        $locale = new CULocale("de_Latn_DE");
        $this->assertTrue($locale->dispScript()->equals("Latin"));

        $locale = new CULocale("de_Latn_DE");
        $this->assertTrue($locale->dispScript(new CULocale("de_DE"))->equals("Lateinisch"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDispVariants ()
    {
        $locale = new CULocale("de_DE_VARIANT0_VARIANT1");
        $this->assertTrue($locale->dispVariants()->equals("VARIANT0_VARIANT1"));

        $locale = new CULocale("de_DE_VARIANT0_VARIANT1");
        $this->assertTrue($locale->dispVariants(new CULocale("de_DE"))->equals("VARIANT0_VARIANT1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAddKeyword ()
    {
        $locale = new CULocale("de_DE");
        $locale->addKeyword("keyword0", "value0");
        $locale->addKeyword("keyword1", "value1");
        $this->assertTrue($locale->name()->equals("de_DE@keyword0=value0;keyword1=value1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $locale0 = new CULocale("de_DE");
        $locale1 = new CULocale("de_de");
        $this->assertTrue($locale0->equals($locale1));

        $locale0 = new CULocale("de_DE");
        $locale1 = new CULocale("de_BE");
        $this->assertFalse($locale0->equals($locale1));

        $locale0 = new CULocale("de_DE");
        $locale1 = new CULocale("de_Latn_DE");
        $this->assertFalse($locale0->equals($locale1));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testKnownCountryCodes ()
    {
        $codes = CULocale::knownCountryCodes();
        $this->assertTrue( $codes->find("DE") && $codes->find("BE") && $codes->find("US") );
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
