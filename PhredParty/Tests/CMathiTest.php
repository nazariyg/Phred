<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CMathiTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFloor ()
    {
        $this->assertTrue( CMathi::floor(1.2) === 1 );
        $this->assertTrue( CMathi::floor(2.8) === 2 );
        $this->assertTrue( CMathi::floor(3.0) === 3 );
        $this->assertTrue( CMathi::floor(-4.2) === -5 );
        $this->assertTrue( CMathi::floor(-5.0) === -5 );

        $this->assertTrue( CMathi::floor(3) === 3 );
        $this->assertTrue( CMathi::floor(-4) === -4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCeil ()
    {
        $this->assertTrue( CMathi::ceil(1.2) === 2 );
        $this->assertTrue( CMathi::ceil(2.8) === 3 );
        $this->assertTrue( CMathi::ceil(3.0) === 3 );
        $this->assertTrue( CMathi::ceil(-4.2) === -4 );
        $this->assertTrue( CMathi::ceil(-5.0) === -5 );

        $this->assertTrue( CMathi::ceil(3) === 3 );
        $this->assertTrue( CMathi::ceil(-4) === -4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRound ()
    {
        $this->assertTrue( CMathi::round(1.2) === 1 );
        $this->assertTrue( CMathi::round(2.8) === 3 );
        $this->assertTrue( CMathi::round(3.5) === 4 );
        $this->assertTrue( CMathi::round(-1.2) === -1 );
        $this->assertTrue( CMathi::round(-2.8) === -3 );

        $this->assertTrue( CMathi::round(3) === 3 );
        $this->assertTrue( CMathi::round(-4) === -4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAbs ()
    {
        $this->assertTrue( CMathi::abs(-1234) === 1234 );
        $this->assertTrue( CMathi::abs(5678) === 5678 );

        $this->assertTrue( CMathi::abs(-1234.01) === 1234 );
        $this->assertTrue( CMathi::abs(5678.01) === 5678 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSign ()
    {
        $this->assertTrue( CMathi::sign(-1234) === -1 );
        $this->assertTrue( CMathi::sign(5678) === 1 );
        $this->assertTrue( CMathi::sign(0) === 0 );

        $this->assertTrue( CMathi::sign(-1234.5) === -1 );
        $this->assertTrue( CMathi::sign(5678.5) === 1 );
        $this->assertTrue( CMathi::sign(0.0) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMin ()
    {
        $this->assertTrue( CMathi::min(1234, 5678) === 1234 );
        $this->assertTrue( CMathi::min(-1234, -5678) === -5678 );

        $this->assertTrue( CMathi::min(1234.01, 5678.01) === 1234 );
        $this->assertTrue( CMathi::min(-1234.01, -5678.01) === -5678 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMax ()
    {
        $this->assertTrue( CMathi::max(1234, 5678) === 5678 );
        $this->assertTrue( CMathi::max(-1234, -5678) === -1234 );

        $this->assertTrue( CMathi::max(1234.01, 5678.01) === 5678 );
        $this->assertTrue( CMathi::max(-1234.01, -5678.01) === -1234 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMinMax ()
    {
        $min;
        $max;
        CMathi::minMax(1234, 5678, $min, $max);
        $this->assertTrue( $min === 1234 && $max === 5678 );

        $min;
        $max;
        CMathi::minMax(-1234, -5678, $min, $max);
        $this->assertTrue( $min === -5678 && $max === -1234 );

        $min;
        $max;
        CMathi::minMax(1234.01, 5678.01, $min, $max);
        $this->assertTrue( $min === 1234 && $max === 5678 );

        $min;
        $max;
        CMathi::minMax(-1234.01, -5678.01, $min, $max);
        $this->assertTrue( $min === -5678 && $max === -1234 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClamp ()
    {
        $this->assertTrue( CMathi::clamp(11, 0, 10) === 10 );
        $this->assertTrue( CMathi::clamp(-2, 0, 10) === 0 );
        $this->assertTrue( CMathi::clamp(5, 0, 10) === 5 );

        $this->assertTrue( CMathi::clamp(11.01, 0, 10) === 10 );
        $this->assertTrue( CMathi::clamp(-2.01, 0, 10) === 0 );
        $this->assertTrue( CMathi::clamp(5.01, 0, 10) === 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDiv ()
    {
        $quotient;
        $reminder;
        CMathi::div(11, 5, $quotient, $reminder);
        $this->assertTrue( $quotient === 2 && $reminder === 1 );

        $quotient;
        $reminder;
        CMathi::div(9, 3, $quotient, $reminder);
        $this->assertTrue( $quotient === 3 && $reminder === 0 );

        $quotient;
        $reminder;
        CMathi::div(-25, 7, $quotient, $reminder);
        $this->assertTrue( $quotient === -3 && $reminder === -4 );

        $quotient;
        $reminder;
        CMathi::div(-25, -7, $quotient, $reminder);
        $this->assertTrue( $quotient === 3 && $reminder === -4 );

        $quotient;
        $reminder;
        CMathi::div(11.01, 5.01, $quotient, $reminder);
        $this->assertTrue( $quotient === 2 && $reminder === 1 );

        $quotient;
        $reminder;
        CMathi::div(9.01, 3.01, $quotient, $reminder);
        $this->assertTrue( $quotient === 3 && $reminder === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEven ()
    {
        $this->assertTrue(CMathi::isEven(2));
        $this->assertTrue(CMathi::isEven(4));
        $this->assertTrue(CMathi::isEven(-6));
        $this->assertTrue(CMathi::isEven(0));
        $this->assertFalse(CMathi::isEven(1));
        $this->assertFalse(CMathi::isEven(3));
        $this->assertFalse(CMathi::isEven(-5));

        $this->assertTrue(CMathi::isEven(2.01));
        $this->assertTrue(CMathi::isEven(4.01));
        $this->assertFalse(CMathi::isEven(1.01));
        $this->assertFalse(CMathi::isEven(3.01));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsOdd ()
    {
        $this->assertTrue(CMathi::isOdd(1));
        $this->assertTrue(CMathi::isOdd(3));
        $this->assertTrue(CMathi::isOdd(-5));
        $this->assertFalse(CMathi::isOdd(2));
        $this->assertFalse(CMathi::isOdd(4));
        $this->assertFalse(CMathi::isOdd(-6));
        $this->assertFalse(CMathi::isOdd(0));

        $this->assertTrue(CMathi::isOdd(1.01));
        $this->assertTrue(CMathi::isOdd(3.01));
        $this->assertFalse(CMathi::isOdd(2.01));
        $this->assertFalse(CMathi::isOdd(4.01));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsDivisible ()
    {
        $this->assertTrue(CMathi::isDivisible(10, 5));
        $this->assertTrue(CMathi::isDivisible(64, 8));
        $this->assertFalse(CMathi::isDivisible(7, 4));

        $this->assertTrue(CMathi::isDivisible(10.01, 5.01));
        $this->assertTrue(CMathi::isDivisible(64.01, 8.01));
        $this->assertFalse(CMathi::isDivisible(7.01, 4.01));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSqr ()
    {
        $this->assertTrue( CMathi::sqr(4) === 16 );
        $this->assertTrue( CMathi::sqr(-11) === 121 );

        $this->assertTrue( CMathi::sqr(4.01) === 16 );
        $this->assertTrue( CMathi::sqr(-11.01) === 121 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPow ()
    {
        $this->assertTrue( CMathi::pow(4, 2) === 16 );
        $this->assertTrue( CMathi::pow(5, 3) === 125 );
        $this->assertTrue( CMathi::pow(5, 0) === 1 );

        $this->assertTrue( CMathi::pow(4.01, 2.01) === 16 );
        $this->assertTrue( CMathi::pow(5.01, 3.01) === 125 );
        $this->assertTrue( CMathi::pow(5.01, 0.01) === 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLog2 ()
    {
        $this->assertTrue( CMathi::log2(16) === 4 );
        $this->assertTrue( CMathi::log2(256) === 8 );
        $this->assertTrue( CMathi::log2(511) === 8 );

        $this->assertTrue( CMathi::log2(16.01) === 4 );
        $this->assertTrue( CMathi::log2(256.01) === 8 );
        $this->assertTrue( CMathi::log2(511.01) === 8 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIntervalRandom ()
    {
        for ($i = 0; $i < 10; $i++)
        {
            $rnd = CMathi::intervalRandom(12, 34);
            $this->assertTrue( 12 <= $rnd && $rnd <= 34 );
        }

        for ($i = 0; $i < 10; $i++)
        {
            $rnd = CMathi::intervalRandom(56, 78);
            $this->assertTrue( 56 <= $rnd && $rnd <= 78 );
        }

        for ($i = 0; $i < 10; $i++)
        {
            $rnd = CMathi::intervalRandom(56.01, 78.01);
            $this->assertTrue( 56 <= $rnd && $rnd <= 78 );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsPow2 ()
    {
        $this->assertTrue(CMathi::isPow2(2));
        $this->assertTrue(CMathi::isPow2(4));
        $this->assertTrue(CMathi::isPow2(8));
        $this->assertTrue(CMathi::isPow2(1024));
        $this->assertFalse(CMathi::isPow2(0));
        $this->assertFalse(CMathi::isPow2(-8));
        $this->assertFalse(CMathi::isPow2(17));

        $this->assertTrue(CMathi::isPow2(2.01));
        $this->assertTrue(CMathi::isPow2(4.01));
        $this->assertTrue(CMathi::isPow2(8.01));
        $this->assertTrue(CMathi::isPow2(1024.01));
        $this->assertFalse(CMathi::isPow2(0.01));
        $this->assertFalse(CMathi::isPow2(-8.01));
        $this->assertFalse(CMathi::isPow2(17.01));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRoundToPow2Down ()
    {
        $this->assertTrue( CMathi::roundToPow2Down(3) === 2 );
        $this->assertTrue( CMathi::roundToPow2Down(7) === 4 );
        $this->assertTrue( CMathi::roundToPow2Down(8) === 8 );
        $this->assertTrue( CMathi::roundToPow2Down(600) === 512 );

        $this->assertTrue( CMathi::roundToPow2Down(3.01) === 2 );
        $this->assertTrue( CMathi::roundToPow2Down(7.01) === 4 );
        $this->assertTrue( CMathi::roundToPow2Down(8.01) === 8 );
        $this->assertTrue( CMathi::roundToPow2Down(600.01) === 512 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRoundToPow2Up ()
    {
        $this->assertTrue( CMathi::roundToPow2Up(3) === 4 );
        $this->assertTrue( CMathi::roundToPow2Up(5) === 8 );
        $this->assertTrue( CMathi::roundToPow2Up(8) === 8 );
        $this->assertTrue( CMathi::roundToPow2Up(600) === 1024 );

        $this->assertTrue( CMathi::roundToPow2Up(3.01) === 4 );
        $this->assertTrue( CMathi::roundToPow2Up(5.01) === 8 );
        $this->assertTrue( CMathi::roundToPow2Up(8.01) === 8 );
        $this->assertTrue( CMathi::roundToPow2Up(600.01) === 1024 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
