<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
        $bfBitfield = 2112;
        $this->assertTrue(CBitField::isBitSet($bfBitfield, CBitField::SET_6));
        $this->assertTrue(CBitField::isBitSet($bfBitfield, CBitField::SET_11));

        $bfBitfield = 0;
        $bfBitfield = CBitField::setBit($bfBitfield, CBitField::SET_6);
        $bfBitfield = CBitField::setBit($bfBitfield, CBitField::SET_11);
        $this->assertTrue( ($bfBitfield & CBitField::SET_6) != 0 );
        $this->assertTrue( ($bfBitfield & CBitField::SET_11) != 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnsetBit ()
    {
        $bfBitfield = ~2112;
        $this->assertFalse(CBitField::isBitSet($bfBitfield, CBitField::SET_6));
        $this->assertFalse(CBitField::isBitSet($bfBitfield, CBitField::SET_11));

        $bfBitfield = ~0;
        $bfBitfield = CBitField::unsetBit($bfBitfield, CBitField::SET_6);
        $bfBitfield = CBitField::unsetBit($bfBitfield, CBitField::SET_11);
        $this->assertTrue( ($bfBitfield & CBitField::SET_6) == 0 );
        $this->assertTrue( ($bfBitfield & CBitField::SET_11) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToggleBit ()
    {
        $bfBitfield = 8388608;
        $bfBitfield = CBitField::toggleBit($bfBitfield, CBitField::SET_10);
        $bfBitfield = CBitField::toggleBit($bfBitfield, CBitField::SET_23);
        $bfBitfield = CBitField::toggleBit($bfBitfield, CBitField::SET_24);
        $this->assertTrue( ($bfBitfield & CBitField::SET_10) != 0 );
        $this->assertTrue( ($bfBitfield & CBitField::SET_23) == 0 );
        $this->assertTrue( ($bfBitfield & CBitField::SET_24) != 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumBitsSet ()
    {
        $bfBitfield = 1431655765;
        $this->assertTrue( CBitField::numBitsSet($bfBitfield) == 16 );
        $bfBitfield = 1152419982;
        $this->assertTrue( CBitField::numBitsSet($bfBitfield) == 11 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
