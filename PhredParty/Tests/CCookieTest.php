<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CCookieTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $oCookie = new CCookie("name", "value");
        $this->assertTrue( $oCookie->name()->equals("name") && $oCookie->value()->equals("value") );

        $oCookie = new CCookie("name", true);
        $this->assertTrue( $oCookie->name()->equals("name") && $oCookie->value()->equals("1") );

        $oCookie = new CCookie("name", 1234);
        $this->assertTrue( $oCookie->name()->equals("name") && $oCookie->value()->equals("1234") );

        $oCookie = new CCookie("name", 12.34);
        $this->assertTrue( $oCookie->name()->equals("name") && $oCookie->value()->equals("12.34") );

        $oCookie = new CCookie("name", CArray::fe("a", "b", "c"));
        $this->assertTrue( $oCookie->name()->equals("name") && $oCookie->value()->equals("[\"a\",\"b\",\"c\"]") );

        $oCookie = new CCookie("name", ["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $oCookie->name()->equals("name") &&
            $oCookie->value()->equals("{\"one\":\"a\",\"two\":\"b\",\"three\":\"c\"}") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetExpireTime ()
    {
        $oCookie = new CCookie("name", "value");
        $oCookie->setExpireTime(new CTime(1234567890));
        $this->assertTrue( $oCookie->expireUTime() == 1234567890 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPath ()
    {
        $oCookie = new CCookie("name", "value");
        $oCookie->setPath("/path");
        $this->assertTrue($oCookie->path()->equals("/path"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetDomain ()
    {
        $oCookie = new CCookie("name", "value");
        $oCookie->setDomain("example.com");
        $this->assertTrue($oCookie->domain()->equals("example.com"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetSecure ()
    {
        $oCookie = new CCookie("name", "value");
        $oCookie->setSecure(true);
        $this->assertTrue( $oCookie->secure() == true );

        $oCookie = new CCookie("name", "value");
        $oCookie->setSecure(false);
        $this->assertTrue( $oCookie->secure() == false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetHttpOnly ()
    {
        $oCookie = new CCookie("name", "value");
        $oCookie->setHttpOnly(true);
        $this->assertTrue( $oCookie->httpOnly() == true );

        $oCookie = new CCookie("name", "value");
        $oCookie->setHttpOnly(false);
        $this->assertTrue( $oCookie->httpOnly() == false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
