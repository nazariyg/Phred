<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CIpTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsValidV4 ()
    {
        $this->assertTrue(CIp::isValidV4("12.34.56.78"));
        $this->assertTrue(CIp::isValidV4("172.16.0.0"));
        $this->assertTrue(CIp::isValidV4("192.0.2.0"));
        $this->assertFalse(CIp::isValidV4(" 192.0.2.0 "));
        $this->assertTrue(CIp::isValidV4("0.0.0.0"));
        $this->assertFalse(CIp::isValidV4("12.34.56"));
        $this->assertTrue(CIp::isValidV4("12.34.56.78", CIp::DISALLOW_PRIVATE_RANGE));
        $this->assertFalse(CIp::isValidV4("172.16.0.0", CIp::DISALLOW_PRIVATE_RANGE));
        $this->assertTrue(CIp::isValidV4("12.34.56.78", CIp::DISALLOW_RESERVED_RANGE));
        $this->assertFalse(CIp::isValidV4("192.0.2.0", CIp::DISALLOW_RESERVED_RANGE));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsValidV6 ()
    {
        $this->assertTrue(CIp::isValidV6("0:0:0:0:0:FFFF:5DB8:D877"));
        $this->assertTrue(CIp::isValidV6("FD:0:0:0:0:FFFF:5DB8:D877"));
        $this->assertFalse(CIp::isValidV6("0:0:0:0:0:FFFF:5DB8"));
        $this->assertTrue(CIp::isValidV6("0:0:0:0:0:FFFF:5DB8:D877", CIp::DISALLOW_PRIVATE_RANGE));
        $this->assertFalse(CIp::isValidV6("FD:0:0:0:0:FFFF:5DB8:D877", CIp::DISALLOW_PRIVATE_RANGE));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsValidV4Or6 ()
    {
        $this->assertTrue(CIp::isValidV4Or6("12.34.56.78"));
        $this->assertTrue(CIp::isValidV4Or6("0:0:0:0:0:FFFF:5DB8:D877"));
        $this->assertTrue(CIp::isValidV4Or6("12.34.56.78", CIp::DISALLOW_PRIVATE_RANGE));
        $this->assertFalse(CIp::isValidV4Or6("172.16.0.0", CIp::DISALLOW_PRIVATE_RANGE));
        $this->assertTrue(CIp::isValidV4Or6("0:0:0:0:0:FFFF:5DB8:D877", CIp::DISALLOW_PRIVATE_RANGE));
        $this->assertFalse(CIp::isValidV4Or6("FD:0:0:0:0:FFFF:5DB8:D877", CIp::DISALLOW_PRIVATE_RANGE));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
