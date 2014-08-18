<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CBitFieldTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetBit ()
    {
        $bitfield = 2112;
        $this->assertTrue(CBitField::isBitSet($bitfield, CBitField::SET_6));
        $this->assertTrue(CBitField::isBitSet($bitfield, CBitField::SET_11));

        $bitfield = 0;
        $bitfield = CBitField::setBit($bitfield, CBitField::SET_6);
        $bitfield = CBitField::setBit($bitfield, CBitField::SET_11);
        $this->assertTrue( ($bitfield & CBitField::SET_6) != 0 );
        $this->assertTrue( ($bitfield & CBitField::SET_11) != 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnsetBit ()
    {
        $bitfield = ~2112;
        $this->assertFalse(CBitField::isBitSet($bitfield, CBitField::SET_6));
        $this->assertFalse(CBitField::isBitSet($bitfield, CBitField::SET_11));

        $bitfield = ~0;
        $bitfield = CBitField::unsetBit($bitfield, CBitField::SET_6);
        $bitfield = CBitField::unsetBit($bitfield, CBitField::SET_11);
        $this->assertTrue( ($bitfield & CBitField::SET_6) == 0 );
        $this->assertTrue( ($bitfield & CBitField::SET_11) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToggleBit ()
    {
        $bitfield = 8388608;
        $bitfield = CBitField::toggleBit($bitfield, CBitField::SET_10);
        $bitfield = CBitField::toggleBit($bitfield, CBitField::SET_23);
        $bitfield = CBitField::toggleBit($bitfield, CBitField::SET_24);
        $this->assertTrue( ($bitfield & CBitField::SET_10) != 0 );
        $this->assertTrue( ($bitfield & CBitField::SET_23) == 0 );
        $this->assertTrue( ($bitfield & CBitField::SET_24) != 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumBitsSet ()
    {
        $bitfield = 1431655765;
        $this->assertTrue( CBitField::numBitsSet($bitfield) == 16 );
        $bitfield = 1152419982;
        $this->assertTrue( CBitField::numBitsSet($bitfield) == 11 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
