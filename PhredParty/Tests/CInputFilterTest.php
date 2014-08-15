<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CInputFilterTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $oFilter = new CInputFilter(CInputFilter::BOOL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("1", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === true );

        $oFilter = new CInputFilter(CInputFilter::BOOL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" on ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === true );

        $oFilter = new CInputFilter(CInputFilter::BOOL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" no ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === false );

        $oFilter = new CInputFilter(CInputFilter::BOOL);
        $oFilter->setDefault(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue === true );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setDefault(5678);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue === 5678 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1.234e1 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setDefault(56.78);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue === 56.78 );

        $oFilter = new CInputFilter(CInputFilter::CSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("hello") );

        $oFilter = new CInputFilter(CInputFilter::CSTRING);
        $oFilter->setDefault("default");
        $bSuccess;
        $xFilteredValue = $oFilter->filter("ブリテネイ スペアルス", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue->equals("default") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("ブリテネイ スペアルス", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("ブリテネイ スペアルス") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $oFilter->setDefault("deƒault");
        $bSuccess;
        $xFilteredValue = $oFilter->filter("\xFF", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue->equals("deƒault") );

        $oFilter = new CInputFilter(CInputFilter::EMAIL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" user@example.com ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("user@example.com") );

        $oFilter = new CInputFilter(CInputFilter::EMAIL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("user@@example.com", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::URL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" http://example.com ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("http://example.com") );

        $oFilter = new CInputFilter(CInputFilter::URL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("Not a URL", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 93.184.216.119 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("93.184.216.119") );

        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("Not an IP", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setIpV4OrV6(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("0:0:0:0:0:FFFF:5DB8:D877") );

        $oFilter = new CInputFilter(CInputFilter::CARRAY,
            a(new CInputFilter(CInputFilter::BOOL), new CInputFilter(CInputFilter::INT)));
        $bSuccess;
        $xFilteredValue = $oFilter->filter(a(" on ", " 1234 "), $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals(a(true, 1234)) );

        $oFilter = new CInputFilter(CInputFilter::CARRAY,
            a(new CInputFilter(CInputFilter::BOOL), new CInputFilter(CInputFilter::INT)));
        $bSuccess;
        $xFilteredValue = $oFilter->filter(a(" on ", " 12.34 "), $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::CMAP,
            m(["key0" => new CInputFilter(CInputFilter::BOOL), "key1" => new CInputFilter(CInputFilter::INT)]));
        $bSuccess;
        $xFilteredValue = $oFilter->filter(m(["key0" => " on ", "key1" => " 1234 "]), $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals(m(["key0" => true, "key1" => 1234])) );

        $oFilter = new CInputFilter(CInputFilter::CMAP,
            m(["key0" => new CInputFilter(CInputFilter::BOOL), "key1" => new CInputFilter(CInputFilter::INT)]));
        $bSuccess;
        $xFilteredValue = $oFilter->filter(m(["key0" => " on ", "hello" => " 1234 "]), $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetDefault ()
    {
        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setDefault(56.78);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue === 56.78 );

        $oFilter = new CInputFilter(CInputFilter::CSTRING);
        $oFilter->setDefault("default");
        $bSuccess;
        $xFilteredValue = $oFilter->filter("ブリテネイ スペアルス", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue->equals("default") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testExpectedType ()
    {
        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $this->assertTrue( $oFilter->expectedType() == CInputFilter::FLOAT );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $this->assertTrue( $oFilter->expectedType() == CInputFilter::CUSTRING );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDefaultValue ()
    {
        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setDefault(56.78);
        $this->assertTrue( $oFilter->defaultValue() === 56.78 );

        $oFilter = new CInputFilter(CInputFilter::CSTRING);
        $oFilter->setDefault("default");
        $this->assertTrue($oFilter->defaultValue()->equals("default"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValidMin ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setValidMin(1000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setValidMin(2000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setValidMin(10.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setValidMin(20.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValidMax ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setValidMax(2000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setValidMax(1000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setValidMax(20.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setValidMax(10.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValidMinMax ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setValidMinMax(1000, 2000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setValidMinMax(2000, 3000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setValidMinMax(10.0, 20.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setValidMinMax(20.0, 30.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowLeadingZeros ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setAllowLeadingZeros(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 01234 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setAllowLeadingZeros(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 012.34 ", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowComma ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1,234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setAllowComma(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1,234 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1,234.5 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234.5 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setAllowComma(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1,234.5 ", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetIgnoreProtocolAbsence ()
    {
        $oFilter = new CInputFilter(CInputFilter::URL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" example.com ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("http://example.com") );

        $oFilter = new CInputFilter(CInputFilter::URL);
        $oFilter->setIgnoreProtocolAbsence(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" example.com ", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowHex ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 0x80 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setAllowHex(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 0x100 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 256 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetIpV6 ()
    {
        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 93.184.216.119 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("93.184.216.119") );

        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setIpV6(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 93.184.216.119 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setIpV6(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("0:0:0:0:0:FFFF:5DB8:D877") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetIpV4OrV6 ()
    {
        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 93.184.216.119 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("93.184.216.119") );

        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setIpV4OrV6(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 93.184.216.119 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("93.184.216.119") );

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setIpV4OrV6(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("0:0:0:0:0:FFFF:5DB8:D877") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowPrivateRange ()
    {
        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 10.0.0.0 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("10.0.0.0") );

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setAllowPrivateRange(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 10.0.0.0 ", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setIpV6(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" FD:0:0:0:0:FFFF:5DB8:D877 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("FD:0:0:0:0:FFFF:5DB8:D877") );

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setIpV6(true);
        $oFilter->setAllowPrivateRange(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" FD:0:0:0:0:FFFF:5DB8:D877 ", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowReservedRange ()
    {
        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 224.0.0.0 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("224.0.0.0") );

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setAllowReservedRange(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 224.0.0.0 ", $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetClampingMin ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setClampingMin(1000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setClampingMin(2000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 2000 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setClampingMin(10.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setClampingMin(20.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 20.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetClampingMax ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setClampingMax(2000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setClampingMax(1000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1000 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setClampingMax(20.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setClampingMax(10.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 10.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetClampingMinMax ()
    {
        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setClampingMinMax(1000, 2000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setClampingMinMax(2000, 3000);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 2000 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setClampingMinMax(10.0, 20.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setClampingMinMax(20.0, 30.0);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 20.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepAbnormalNewlines ()
    {
        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("Hello\r\nthere!\n", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("Hello\nthere!\n") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $oFilter->setKeepAbnormalNewlines(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("Hello\r\nthere!\n", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("Hello\r\nthere!\n") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepNonPrintable ()
    {
        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("\x00Hello there!", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("Hello there!") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $oFilter->setKeepNonPrintable(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("\x00Hello there!", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("\x00Hello there!") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepTabsAndNewlines ()
    {
        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("Hello\tthere!\n", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("Hello\tthere!\n") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $oFilter->setKeepTabsAndNewlines(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("Hello\tthere!\n", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("Hellothere!") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepSideSpacing ()
    {
        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" Hello  there! ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals(" Hello  there! ") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $oFilter->setKeepSideSpacing(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" Hello  there! ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("Hello  there!") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepExtraSpacing ()
    {
        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" Hello  there! ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals(" Hello  there! ") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $oFilter->setKeepExtraSpacing(false);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" Hello  there! ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("Hello there!") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $oFilter = new CInputFilter(CInputFilter::BOOL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("1", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === true );

        $oFilter = new CInputFilter(CInputFilter::BOOL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" on ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === true );

        $oFilter = new CInputFilter(CInputFilter::BOOL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" no ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === false );

        $oFilter = new CInputFilter(CInputFilter::BOOL);
        $oFilter->setDefault(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue === true );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1234 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 1234 );

        $oFilter = new CInputFilter(CInputFilter::INT);
        $oFilter->setDefault(5678);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue === 5678 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 12.34 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 1.234e1 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue === 12.34 );

        $oFilter = new CInputFilter(CInputFilter::FLOAT);
        $oFilter->setDefault(56.78);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue === 56.78 );

        $oFilter = new CInputFilter(CInputFilter::CSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("hello", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("hello") );

        $oFilter = new CInputFilter(CInputFilter::CSTRING);
        $oFilter->setDefault("default");
        $bSuccess;
        $xFilteredValue = $oFilter->filter("ブリテネイ スペアルス", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue->equals("default") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("ブリテネイ スペアルス", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("ブリテネイ スペアルス") );

        $oFilter = new CInputFilter(CInputFilter::CUSTRING);
        $oFilter->setDefault("deƒault");
        $bSuccess;
        $xFilteredValue = $oFilter->filter("\xFF", $bSuccess);
        $this->assertTrue( !$bSuccess && $xFilteredValue->equals("deƒault") );

        $oFilter = new CInputFilter(CInputFilter::EMAIL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" user@example.com ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("user@example.com") );

        $oFilter = new CInputFilter(CInputFilter::EMAIL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("user@@example.com", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::URL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" http://example.com ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("http://example.com") );

        $oFilter = new CInputFilter(CInputFilter::URL);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("Not a URL", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 93.184.216.119 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("93.184.216.119") );

        $oFilter = new CInputFilter(CInputFilter::IP);
        $bSuccess;
        $xFilteredValue = $oFilter->filter("Not an IP", $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::IP);
        $oFilter->setIpV4OrV6(true);
        $bSuccess;
        $xFilteredValue = $oFilter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals("0:0:0:0:0:FFFF:5DB8:D877") );

        $oFilter = new CInputFilter(CInputFilter::CARRAY,
            a(new CInputFilter(CInputFilter::BOOL), new CInputFilter(CInputFilter::INT)));
        $bSuccess;
        $xFilteredValue = $oFilter->filter(a(" on ", " 1234 "), $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals(a(true, 1234)) );

        $oFilter = new CInputFilter(CInputFilter::CARRAY,
            a(new CInputFilter(CInputFilter::BOOL), new CInputFilter(CInputFilter::INT)));
        $bSuccess;
        $xFilteredValue = $oFilter->filter(a(" on ", " 12.34 "), $bSuccess);
        $this->assertFalse($bSuccess);

        $oFilter = new CInputFilter(CInputFilter::CMAP,
            m(["key0" => new CInputFilter(CInputFilter::BOOL), "key1" => new CInputFilter(CInputFilter::INT)]));
        $bSuccess;
        $xFilteredValue = $oFilter->filter(m(["key0" => " on ", "key1" => " 1234 "]), $bSuccess);
        $this->assertTrue( $bSuccess && $xFilteredValue->equals(m(["key0" => true, "key1" => 1234])) );

        $oFilter = new CInputFilter(CInputFilter::CMAP,
            m(["key0" => new CInputFilter(CInputFilter::BOOL), "key1" => new CInputFilter(CInputFilter::INT)]));
        $bSuccess;
        $xFilteredValue = $oFilter->filter(m(["key0" => " on ", "hello" => " 1234 "]), $bSuccess);
        $this->assertFalse($bSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
