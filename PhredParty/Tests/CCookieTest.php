<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
        $cookie = new CCookie("name", "value");
        $this->assertTrue( $cookie->name()->equals("name") && $cookie->value()->equals("value") );

        $cookie = new CCookie("name", true);
        $this->assertTrue( $cookie->name()->equals("name") && $cookie->value()->equals("1") );

        $cookie = new CCookie("name", 1234);
        $this->assertTrue( $cookie->name()->equals("name") && $cookie->value()->equals("1234") );

        $cookie = new CCookie("name", 12.34);
        $this->assertTrue( $cookie->name()->equals("name") && $cookie->value()->equals("12.34") );

        $cookie = new CCookie("name", CArray::fe("a", "b", "c"));
        $this->assertTrue( $cookie->name()->equals("name") && $cookie->value()->equals("[\"a\",\"b\",\"c\"]") );

        $cookie = new CCookie("name", ["one" => "a", "two" => "b", "three" => "c"]);
        $this->assertTrue( $cookie->name()->equals("name") &&
            $cookie->value()->equals("{\"one\":\"a\",\"two\":\"b\",\"three\":\"c\"}") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetExpireTime ()
    {
        $cookie = new CCookie("name", "value");
        $cookie->setExpireTime(new CTime(1234567890));
        $this->assertTrue( $cookie->expireUTime() == 1234567890 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPath ()
    {
        $cookie = new CCookie("name", "value");
        $cookie->setPath("/path");
        $this->assertTrue($cookie->path()->equals("/path"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetDomain ()
    {
        $cookie = new CCookie("name", "value");
        $cookie->setDomain("example.com");
        $this->assertTrue($cookie->domain()->equals("example.com"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetSecure ()
    {
        $cookie = new CCookie("name", "value");
        $cookie->setSecure(true);
        $this->assertTrue( $cookie->secure() == true );

        $cookie = new CCookie("name", "value");
        $cookie->setSecure(false);
        $this->assertTrue( $cookie->secure() == false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetHttpOnly ()
    {
        $cookie = new CCookie("name", "value");
        $cookie->setHttpOnly(true);
        $this->assertTrue( $cookie->httpOnly() == true );

        $cookie = new CCookie("name", "value");
        $cookie->setHttpOnly(false);
        $this->assertTrue( $cookie->httpOnly() == false );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
