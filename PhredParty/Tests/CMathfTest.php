<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CMathfTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFloor ()
    {
        $this->assertTrue( CMathf::floor(1.2) === 1.0 );
        $this->assertTrue( CMathf::floor(2.8) === 2.0 );
        $this->assertTrue( CMathf::floor(3.0) === 3.0 );
        $this->assertTrue( CMathf::floor(-4.2) === -5.0 );
        $this->assertTrue( CMathf::floor(-5.0) === -5.0 );

        $this->assertTrue( CMathf::floor(1) === 1.0 );
        $this->assertTrue( CMathf::floor(2) === 2.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCeil ()
    {
        $this->assertTrue( CMathf::ceil(1.2) === 2.0 );
        $this->assertTrue( CMathf::ceil(2.8) === 3.0 );
        $this->assertTrue( CMathf::ceil(3.0) === 3.0 );
        $this->assertTrue( CMathf::ceil(-4.2) === -4.0 );
        $this->assertTrue( CMathf::ceil(-5.0) === -5.0 );

        $this->assertTrue( CMathf::ceil(1) === 1.0 );
        $this->assertTrue( CMathf::ceil(-2) === -2.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRound ()
    {
        $this->assertTrue( CMathf::round(1.2) === 1.0 );
        $this->assertTrue( CMathf::round(2.8) === 3.0 );
        $this->assertTrue( CMathf::round(3.5) === 4.0 );
        $this->assertTrue( CMathf::round(-1.2) === -1.0 );
        $this->assertTrue( CMathf::round(-2.8) === -3.0 );

        $this->assertTrue( CMathf::round(1.2345678, 2) === 1.23 );
        $this->assertTrue( CMathf::round(1.2345678, 3) === 1.235 );
        $this->assertTrue( CMathf::round(1.2345678, 4) === 1.2346 );
        $this->assertTrue( CMathf::round(-1.2345678, 2) === -1.23 );
        $this->assertTrue( CMathf::round(-1.2345678, 3) === -1.235 );
        $this->assertTrue( CMathf::round(-1.2345678, 4) === -1.2346 );

        $this->assertTrue( CMathf::round(1) === 1.0 );
        $this->assertTrue( CMathf::round(-2) === -2.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAbs ()
    {
        $this->assertTrue( CMathf::abs(-1.2) === 1.2 );
        $this->assertTrue( CMathf::abs(1.2) === 1.2 );

        $this->assertTrue( CMathf::abs(-1) === 1.0 );
        $this->assertTrue( CMathf::abs(1) === 1.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSign ()
    {
        $this->assertTrue( CMathf::sign(2.5) === 1.0 );
        $this->assertTrue( CMathf::sign(-2.5) === -1.0 );
        $this->assertTrue( CMathf::sign(0.0) === 0.0 );

        $this->assertTrue( CMathf::sign(2) === 1.0 );
        $this->assertTrue( CMathf::sign(-2) === -1.0 );
        $this->assertTrue( CMathf::sign(0) === 0.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMin ()
    {
        $this->assertTrue( CMathf::min(1.2, 2.8) === 1.2 );
        $this->assertTrue( CMathf::min(-1.2, -2.8) === -2.8 );

        $this->assertTrue( CMathf::min(1, 2) === 1.0 );
        $this->assertTrue( CMathf::min(-1, -2) === -2.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMax ()
    {
        $this->assertTrue( CMathf::max(1.2, 2.8) === 2.8 );
        $this->assertTrue( CMathf::max(-1.2, -2.8) === -1.2 );

        $this->assertTrue( CMathf::max(1, 2) === 2.0 );
        $this->assertTrue( CMathf::max(-1, -2) === -1.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMinMax ()
    {
        $min;
        $max;
        CMathf::minMax(1.2, 2.8, $min, $max);
        $this->assertTrue( $min === 1.2 && $max === 2.8 );

        $min;
        $max;
        CMathf::minMax(-1.2, -2.8, $min, $max);
        $this->assertTrue( $min === -2.8 && $max === -1.2 );

        $min;
        $max;
        CMathf::minMax(1, 2, $min, $max);
        $this->assertTrue( $min === 1.0 && $max === 2.0 );

        $min;
        $max;
        CMathf::minMax(-1, -2, $min, $max);
        $this->assertTrue( $min === -2.0 && $max === -1.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClamp ()
    {
        $this->assertTrue( CMathf::clamp(11.0, 0.0, 10.0) === 10.0 );
        $this->assertTrue( CMathf::clamp(-2.0, 0.0, 10.0) === 0.0 );
        $this->assertTrue( CMathf::clamp(5.0, 0.0, 10.0) === 5.0 );

        $this->assertTrue( CMathf::clamp(11, 0, 10) === 10.0 );
        $this->assertTrue( CMathf::clamp(-2, 0, 10) === 0.0 );
        $this->assertTrue( CMathf::clamp(5, 0, 10) === 5.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnitClamp ()
    {
        $this->assertTrue( CMathf::unitClamp(1.1) === 1.0 );
        $this->assertTrue( CMathf::unitClamp(-0.2) === 0.0 );
        $this->assertTrue( CMathf::unitClamp(0.5) === 0.5 );

        $this->assertTrue( CMathf::unitClamp(1) === 1.0 );
        $this->assertTrue( CMathf::unitClamp(0) === 0.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMod ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::mod(11.0, 5.0), 1.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::mod(9.0, 3.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::mod(2.5, 0.2), 0.1));

        $this->assertTrue( CMathf::mod(11, 5) === 1.0 );
        $this->assertTrue( CMathf::mod(9, 5) === 4.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSqr ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::sqr(4.0), 16.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::sqr(-5.5), 30.25));

        $this->assertTrue( CMathf::sqr(4) === 16.0 );
        $this->assertTrue( CMathf::sqr(-5) === 25.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSqrt ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::sqrt(16.0), 4.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::sqrt(30.25), 5.5));

        $this->assertTrue( CMathf::sqrt(16) === 4.0 );
        $this->assertTrue( CMathf::sqrt(49) === 7.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPow ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::pow(4.0, 2.0), 16.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::pow(5.0, 3.0), 125.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::pow(2.0, 0.5), 1.414213562));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::pow(2.0, -0.5), 0.707106781));

        $this->assertTrue( CMathf::pow(4, 2) === 16.0 );
        $this->assertTrue( CMathf::pow(5, 3) === 125.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testExp ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::exp(1.0), 2.718281828));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::exp(5.0), 148.413159103));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::exp(1), 2.718281828));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::exp(5), 148.413159103));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLog ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log(2.718281828), 1.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log(148.413159103), 5.0));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::log(2), 0.69314718));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log(148), 4.997212274));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLog10 ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log10(10.0), 1.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log10(100.0), 2.0));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::log10(10), 1.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log10(100), 2.0));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLog2 ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log2(16.0), 4.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log2(256.0), 8.0));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::log2(16), 4.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::log2(256), 8.0));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSin ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::sin(0.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::sin(CMathf::HALF_PI), 1.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::sin(CMathf::PI/4), 0.707106781));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::sin(CMathf::PI/6), 0.5));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::sin(10), -0.54402111));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCos ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::cos(0.0), 1.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::cos(CMathf::HALF_PI), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::cos(CMathf::PI/4), 0.707106781));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::cos(CMathf::PI/6), 0.866025404));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::cos(10), -0.839071529));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testTan ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::tan(0.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::tan(1.0), 1.557407725));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::tan(0.5), 0.54630249));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::tan(10), 0.6483608275));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAsin ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(0.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(1.0), CMathf::HALF_PI));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(0.707106781), CMathf::PI/4));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(0.5), CMathf::PI/6));

        // Special cases.
        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(1.5), CMathf::HALF_PI));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(2.0), CMathf::HALF_PI));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(-3.0), -CMathf::HALF_PI));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(-10.0), -CMathf::HALF_PI));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::asin(1), 1.570796327));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAcos ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(1.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(0.0), CMathf::HALF_PI));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(0.707106781), CMathf::PI/4));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(0.866025404), CMathf::PI/6));

        // Special cases.
        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(1.5), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(2.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(-3.0), CMathf::PI));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(-10.0), CMathf::PI));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::acos(0), 1.570796327));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAtan ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::atan(0.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::atan(1.557407725), 1.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::atan(0.54630249), 0.5));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::atan(0), 0.0));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAtan2 ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::atan2(0.0, 1.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::atan2(0.707106781, 0.707106781), CMathf::PI/4));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::atan2(0.707106781, -0.707106781), CMathf::PI*3/4));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::atan2(3, 4), 0.643501109));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDegToRad ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::degToRad(0.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::degToRad(90.0), CMathf::HALF_PI));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::degToRad(180.0), CMathf::PI));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::degToRad(360.0), CMathf::PI*2));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::degToRad(90), CMathf::HALF_PI));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRadToDeg ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::radToDeg(0.0), 0.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::radToDeg(CMathf::HALF_PI), 90.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::radToDeg(CMathf::PI), 180.0));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::radToDeg(CMathf::PI*2), 360.0));

        $this->assertTrue(
            CMathf::equalsZt(CMathf::radToDeg(10), 572.95779513));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRandom ()
    {
        for ($i = 0; $i < 10; $i++)
        {
            $rnd = CMathf::random();
            $this->assertTrue( 0.0 <= $rnd && $rnd < 1.0 );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSymmetricRandom ()
    {
        for ($i = 0; $i < 10; $i++)
        {
            $rnd = CMathf::symmetricRandom();
            $this->assertTrue( -1.0 <= $rnd && $rnd < 1.0 );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIntervalRandom ()
    {
        for ($i = 0; $i < 10; $i++)
        {
            $rnd = CMathf::intervalRandom(12.0, 34.0);
            $this->assertTrue( 12.0 <= $rnd && $rnd < 34.0 );
        }

        for ($i = 0; $i < 10; $i++)
        {
            $rnd = CMathf::intervalRandom(56.0, 78.0);
            $this->assertTrue( 56.0 <= $rnd && $rnd < 78.0 );
        }

        $rnd = CMathf::intervalRandom(0, 0);
        $this->assertTrue( $rnd === 0.0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromIntAndFracParts ()
    {
        $this->assertTrue(
            CMathf::equalsZt(CMathf::fromIntAndFracParts(12, 34), 12.34));
        $this->assertTrue(
            CMathf::equalsZt(CMathf::fromIntAndFracParts(-1234, 5678), -1234.5678));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEqualsZt ()
    {
        $this->assertTrue(
            CMathf::equalsZt(1.234, 1.234));
        $this->assertTrue(
            CMathf::equalsZt(1.2345678, 1.2345678));

        $this->assertFalse(
            CMathf::equalsZt(1.2345678, 1.2345679));
        $this->assertFalse(
            CMathf::equalsZt(1.2345678, 1.2345678 + CMathf::ZERO_TOLERANCE*2));

        $this->assertTrue(
            CMathf::equalsZt(10, 10));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
