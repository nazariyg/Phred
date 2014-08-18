<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
        $filter = new CInputFilter(CInputFilter::BOOL);
        $success;
        $filteredValue = $filter->filter("1", $success);
        $this->assertTrue( $success && $filteredValue === true );

        $filter = new CInputFilter(CInputFilter::BOOL);
        $success;
        $filteredValue = $filter->filter(" on ", $success);
        $this->assertTrue( $success && $filteredValue === true );

        $filter = new CInputFilter(CInputFilter::BOOL);
        $success;
        $filteredValue = $filter->filter(" no ", $success);
        $this->assertTrue( $success && $filteredValue === false );

        $filter = new CInputFilter(CInputFilter::BOOL);
        $filter->setDefault(true);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( !$success && $filteredValue === true );

        $filter = new CInputFilter(CInputFilter::INT);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setDefault(5678);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( !$success && $filteredValue === 5678 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $success;
        $filteredValue = $filter->filter(" 1.234e1 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setDefault(56.78);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( !$success && $filteredValue === 56.78 );

        $filter = new CInputFilter(CInputFilter::CSTRING);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( $success && $filteredValue->equals("hello") );

        $filter = new CInputFilter(CInputFilter::CSTRING);
        $filter->setDefault("default");
        $success;
        $filteredValue = $filter->filter("ブリテネイ スペアルス", $success);
        $this->assertTrue( !$success && $filteredValue->equals("default") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $success;
        $filteredValue = $filter->filter("ブリテネイ スペアルス", $success);
        $this->assertTrue( $success && $filteredValue->equals("ブリテネイ スペアルス") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $filter->setDefault("deƒault");
        $success;
        $filteredValue = $filter->filter("\xFF", $success);
        $this->assertTrue( !$success && $filteredValue->equals("deƒault") );

        $filter = new CInputFilter(CInputFilter::EMAIL);
        $success;
        $filteredValue = $filter->filter(" user@example.com ", $success);
        $this->assertTrue( $success && $filteredValue->equals("user@example.com") );

        $filter = new CInputFilter(CInputFilter::EMAIL);
        $success;
        $filteredValue = $filter->filter("user@@example.com", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::URL);
        $success;
        $filteredValue = $filter->filter(" http://example.com ", $success);
        $this->assertTrue( $success && $filteredValue->equals("http://example.com") );

        $filter = new CInputFilter(CInputFilter::URL);
        $success;
        $filteredValue = $filter->filter("Not a URL", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter(" 93.184.216.119 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("93.184.216.119") );

        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter("Not an IP", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setIpV4OrV6(true);
        $success;
        $filteredValue = $filter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("0:0:0:0:0:FFFF:5DB8:D877") );

        $filter = new CInputFilter(CInputFilter::CARRAY,
            a(new CInputFilter(CInputFilter::BOOL), new CInputFilter(CInputFilter::INT)));
        $success;
        $filteredValue = $filter->filter(a(" on ", " 1234 "), $success);
        $this->assertTrue( $success && $filteredValue->equals(a(true, 1234)) );

        $filter = new CInputFilter(CInputFilter::CARRAY,
            a(new CInputFilter(CInputFilter::BOOL), new CInputFilter(CInputFilter::INT)));
        $success;
        $filteredValue = $filter->filter(a(" on ", " 12.34 "), $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::CMAP,
            m(["key0" => new CInputFilter(CInputFilter::BOOL), "key1" => new CInputFilter(CInputFilter::INT)]));
        $success;
        $filteredValue = $filter->filter(m(["key0" => " on ", "key1" => " 1234 "]), $success);
        $this->assertTrue( $success && $filteredValue->equals(m(["key0" => true, "key1" => 1234])) );

        $filter = new CInputFilter(CInputFilter::CMAP,
            m(["key0" => new CInputFilter(CInputFilter::BOOL), "key1" => new CInputFilter(CInputFilter::INT)]));
        $success;
        $filteredValue = $filter->filter(m(["key0" => " on ", "hello" => " 1234 "]), $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetDefault ()
    {
        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setDefault(56.78);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( !$success && $filteredValue === 56.78 );

        $filter = new CInputFilter(CInputFilter::CSTRING);
        $filter->setDefault("default");
        $success;
        $filteredValue = $filter->filter("ブリテネイ スペアルス", $success);
        $this->assertTrue( !$success && $filteredValue->equals("default") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testExpectedType ()
    {
        $filter = new CInputFilter(CInputFilter::FLOAT);
        $this->assertTrue( $filter->expectedType() == CInputFilter::FLOAT );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $this->assertTrue( $filter->expectedType() == CInputFilter::CUSTRING );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDefaultValue ()
    {
        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setDefault(56.78);
        $this->assertTrue( $filter->defaultValue() === 56.78 );

        $filter = new CInputFilter(CInputFilter::CSTRING);
        $filter->setDefault("default");
        $this->assertTrue($filter->defaultValue()->equals("default"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValidMin ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setValidMin(1000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setValidMin(2000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setValidMin(10.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setValidMin(20.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValidMax ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setValidMax(2000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setValidMax(1000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setValidMax(20.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setValidMax(10.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetValidMinMax ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setValidMinMax(1000, 2000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setValidMinMax(2000, 3000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setValidMinMax(10.0, 20.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setValidMinMax(20.0, 30.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowLeadingZeros ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setAllowLeadingZeros(false);
        $success;
        $filteredValue = $filter->filter(" 01234 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setAllowLeadingZeros(false);
        $success;
        $filteredValue = $filter->filter(" 012.34 ", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowComma ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $success;
        $filteredValue = $filter->filter(" 1,234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setAllowComma(false);
        $success;
        $filteredValue = $filter->filter(" 1,234 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $success;
        $filteredValue = $filter->filter(" 1,234.5 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234.5 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setAllowComma(false);
        $success;
        $filteredValue = $filter->filter(" 1,234.5 ", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetIgnoreProtocolAbsence ()
    {
        $filter = new CInputFilter(CInputFilter::URL);
        $success;
        $filteredValue = $filter->filter(" example.com ", $success);
        $this->assertTrue( $success && $filteredValue->equals("http://example.com") );

        $filter = new CInputFilter(CInputFilter::URL);
        $filter->setIgnoreProtocolAbsence(false);
        $success;
        $filteredValue = $filter->filter(" example.com ", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowHex ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $success;
        $filteredValue = $filter->filter(" 0x80 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setAllowHex(true);
        $success;
        $filteredValue = $filter->filter(" 0x100 ", $success);
        $this->assertTrue( $success && $filteredValue === 256 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetIpV6 ()
    {
        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter(" 93.184.216.119 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("93.184.216.119") );

        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setIpV6(true);
        $success;
        $filteredValue = $filter->filter(" 93.184.216.119 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setIpV6(true);
        $success;
        $filteredValue = $filter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("0:0:0:0:0:FFFF:5DB8:D877") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetIpV4OrV6 ()
    {
        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter(" 93.184.216.119 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("93.184.216.119") );

        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setIpV4OrV6(true);
        $success;
        $filteredValue = $filter->filter(" 93.184.216.119 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("93.184.216.119") );

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setIpV4OrV6(true);
        $success;
        $filteredValue = $filter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("0:0:0:0:0:FFFF:5DB8:D877") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowPrivateRange ()
    {
        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter(" 10.0.0.0 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("10.0.0.0") );

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setAllowPrivateRange(false);
        $success;
        $filteredValue = $filter->filter(" 10.0.0.0 ", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setIpV6(true);
        $success;
        $filteredValue = $filter->filter(" FD:0:0:0:0:FFFF:5DB8:D877 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("FD:0:0:0:0:FFFF:5DB8:D877") );

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setIpV6(true);
        $filter->setAllowPrivateRange(false);
        $success;
        $filteredValue = $filter->filter(" FD:0:0:0:0:FFFF:5DB8:D877 ", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetAllowReservedRange ()
    {
        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter(" 224.0.0.0 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("224.0.0.0") );

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setAllowReservedRange(false);
        $success;
        $filteredValue = $filter->filter(" 224.0.0.0 ", $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetClampingMin ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setClampingMin(1000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setClampingMin(2000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 2000 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setClampingMin(10.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setClampingMin(20.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 20.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetClampingMax ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setClampingMax(2000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setClampingMax(1000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1000 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setClampingMax(20.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setClampingMax(10.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 10.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetClampingMinMax ()
    {
        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setClampingMinMax(1000, 2000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setClampingMinMax(2000, 3000);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 2000 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setClampingMinMax(10.0, 20.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setClampingMinMax(20.0, 30.0);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 20.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepAbnormalNewlines ()
    {
        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $success;
        $filteredValue = $filter->filter("Hello\r\nthere!\n", $success);
        $this->assertTrue( $success && $filteredValue->equals("Hello\nthere!\n") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $filter->setKeepAbnormalNewlines(true);
        $success;
        $filteredValue = $filter->filter("Hello\r\nthere!\n", $success);
        $this->assertTrue( $success && $filteredValue->equals("Hello\r\nthere!\n") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepNonPrintable ()
    {
        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $success;
        $filteredValue = $filter->filter("\x00Hello there!", $success);
        $this->assertTrue( $success && $filteredValue->equals("Hello there!") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $filter->setKeepNonPrintable(true);
        $success;
        $filteredValue = $filter->filter("\x00Hello there!", $success);
        $this->assertTrue( $success && $filteredValue->equals("\x00Hello there!") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepTabsAndNewlines ()
    {
        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $success;
        $filteredValue = $filter->filter("Hello\tthere!\n", $success);
        $this->assertTrue( $success && $filteredValue->equals("Hello\tthere!\n") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $filter->setKeepTabsAndNewlines(false);
        $success;
        $filteredValue = $filter->filter("Hello\tthere!\n", $success);
        $this->assertTrue( $success && $filteredValue->equals("Hellothere!") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepSideSpacing ()
    {
        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $success;
        $filteredValue = $filter->filter(" Hello  there! ", $success);
        $this->assertTrue( $success && $filteredValue->equals(" Hello  there! ") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $filter->setKeepSideSpacing(false);
        $success;
        $filteredValue = $filter->filter(" Hello  there! ", $success);
        $this->assertTrue( $success && $filteredValue->equals("Hello  there!") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetKeepExtraSpacing ()
    {
        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $success;
        $filteredValue = $filter->filter(" Hello  there! ", $success);
        $this->assertTrue( $success && $filteredValue->equals(" Hello  there! ") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $filter->setKeepExtraSpacing(false);
        $success;
        $filteredValue = $filter->filter(" Hello  there! ", $success);
        $this->assertTrue( $success && $filteredValue->equals("Hello there!") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $filter = new CInputFilter(CInputFilter::BOOL);
        $success;
        $filteredValue = $filter->filter("1", $success);
        $this->assertTrue( $success && $filteredValue === true );

        $filter = new CInputFilter(CInputFilter::BOOL);
        $success;
        $filteredValue = $filter->filter(" on ", $success);
        $this->assertTrue( $success && $filteredValue === true );

        $filter = new CInputFilter(CInputFilter::BOOL);
        $success;
        $filteredValue = $filter->filter(" no ", $success);
        $this->assertTrue( $success && $filteredValue === false );

        $filter = new CInputFilter(CInputFilter::BOOL);
        $filter->setDefault(true);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( !$success && $filteredValue === true );

        $filter = new CInputFilter(CInputFilter::INT);
        $success;
        $filteredValue = $filter->filter(" 1234 ", $success);
        $this->assertTrue( $success && $filteredValue === 1234 );

        $filter = new CInputFilter(CInputFilter::INT);
        $filter->setDefault(5678);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( !$success && $filteredValue === 5678 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $success;
        $filteredValue = $filter->filter(" 12.34 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $success;
        $filteredValue = $filter->filter(" 1.234e1 ", $success);
        $this->assertTrue( $success && $filteredValue === 12.34 );

        $filter = new CInputFilter(CInputFilter::FLOAT);
        $filter->setDefault(56.78);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( !$success && $filteredValue === 56.78 );

        $filter = new CInputFilter(CInputFilter::CSTRING);
        $success;
        $filteredValue = $filter->filter("hello", $success);
        $this->assertTrue( $success && $filteredValue->equals("hello") );

        $filter = new CInputFilter(CInputFilter::CSTRING);
        $filter->setDefault("default");
        $success;
        $filteredValue = $filter->filter("ブリテネイ スペアルス", $success);
        $this->assertTrue( !$success && $filteredValue->equals("default") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $success;
        $filteredValue = $filter->filter("ブリテネイ スペアルス", $success);
        $this->assertTrue( $success && $filteredValue->equals("ブリテネイ スペアルス") );

        $filter = new CInputFilter(CInputFilter::CUSTRING);
        $filter->setDefault("deƒault");
        $success;
        $filteredValue = $filter->filter("\xFF", $success);
        $this->assertTrue( !$success && $filteredValue->equals("deƒault") );

        $filter = new CInputFilter(CInputFilter::EMAIL);
        $success;
        $filteredValue = $filter->filter(" user@example.com ", $success);
        $this->assertTrue( $success && $filteredValue->equals("user@example.com") );

        $filter = new CInputFilter(CInputFilter::EMAIL);
        $success;
        $filteredValue = $filter->filter("user@@example.com", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::URL);
        $success;
        $filteredValue = $filter->filter(" http://example.com ", $success);
        $this->assertTrue( $success && $filteredValue->equals("http://example.com") );

        $filter = new CInputFilter(CInputFilter::URL);
        $success;
        $filteredValue = $filter->filter("Not a URL", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter(" 93.184.216.119 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("93.184.216.119") );

        $filter = new CInputFilter(CInputFilter::IP);
        $success;
        $filteredValue = $filter->filter("Not an IP", $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::IP);
        $filter->setIpV4OrV6(true);
        $success;
        $filteredValue = $filter->filter(" 0:0:0:0:0:FFFF:5DB8:D877 ", $success);
        $this->assertTrue( $success && $filteredValue->equals("0:0:0:0:0:FFFF:5DB8:D877") );

        $filter = new CInputFilter(CInputFilter::CARRAY,
            a(new CInputFilter(CInputFilter::BOOL), new CInputFilter(CInputFilter::INT)));
        $success;
        $filteredValue = $filter->filter(a(" on ", " 1234 "), $success);
        $this->assertTrue( $success && $filteredValue->equals(a(true, 1234)) );

        $filter = new CInputFilter(CInputFilter::CARRAY,
            a(new CInputFilter(CInputFilter::BOOL), new CInputFilter(CInputFilter::INT)));
        $success;
        $filteredValue = $filter->filter(a(" on ", " 12.34 "), $success);
        $this->assertFalse($success);

        $filter = new CInputFilter(CInputFilter::CMAP,
            m(["key0" => new CInputFilter(CInputFilter::BOOL), "key1" => new CInputFilter(CInputFilter::INT)]));
        $success;
        $filteredValue = $filter->filter(m(["key0" => " on ", "key1" => " 1234 "]), $success);
        $this->assertTrue( $success && $filteredValue->equals(m(["key0" => true, "key1" => 1234])) );

        $filter = new CInputFilter(CInputFilter::CMAP,
            m(["key0" => new CInputFilter(CInputFilter::BOOL), "key1" => new CInputFilter(CInputFilter::INT)]));
        $success;
        $filteredValue = $filter->filter(m(["key0" => " on ", "hello" => " 1234 "]), $success);
        $this->assertFalse($success);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
