<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A set of static methods that perform mathematic operations by taking floating-point or integer numbers as parameters
 * but always outputting floating-point numbers.
 *
 * **You can refer to this class by its alias, which is** `Mf`.
 */

// Method signatures:
//   static float floor ($nValue)
//   static float ceil ($nValue)
//   static float round ($nValue, $iFracLength = 0)
//   static float abs ($nValue)
//   static float sign ($nValue)
//   static float min ($nValue0, $nValue1)
//   static float max ($nValue0, $nValue1)
//   static void minMax ($nValue0, $nValue1, &$rfMin, &$rfMax)
//   static float clamp ($nValue, $nRangeMin, $nRangeMax)
//   static float unitClamp ($nValue)
//   static float mod ($nX, $nY)
//   static float sqr ($nValue)
//   static float sqrt ($nValue)
//   static float pow ($nBase, $nExponent)
//   static float exp ($nValue)
//   static float log ($nValue)
//   static float log10 ($nValue)
//   static float log2 ($nValue)
//   static float sin ($nValue)
//   static float cos ($nValue)
//   static float tan ($nValue)
//   static float asin ($nValue)
//   static float acos ($nValue)
//   static float atan ($nValue)
//   static float atan2 ($nY, $nX)
//   static float degToRad ($nDegrees)
//   static float radToDeg ($nRadians)
//   static float random ()
//   static float symmetricRandom ()
//   static float intervalRandom ($nMin, $nMax)
//   static void seedRandom ($iSeed)
//   static float fromIntAndFracParts ($iIntPart, $iFracPart)
//   static bool equalsZt ($nValue0, $nValue1)

class CMathf extends CRootClass
{
    /**
     * `float` `1e-08` The maximum absolute difference between two floating-point numbers for them to be considered
     * equal in situations when exact equality is not required or simply impossible to achieve with limited-precision
     * floating-point arithmetics that is used by any CPU.
     *
     * @var float
     */
    const ZERO_TOLERANCE = 1e-08;

    /**
     * `float` `0.69314718055995` ln(2)
     *
     * @var float
     */
    const LN_2 = 0.69314718055995;

    /**
     * `float` `2.302585092994` ln(10)
     *
     * @var float
     */
    const LN_10 = 2.302585092994;

    /**
     * `float` `1.4142135623731` sqrt(2)
     *
     * @var float
     */
    const SQRT_2 = 1.4142135623731;

    /**
     * `float` `1.442695040889` 1/ln(2)
     *
     * @var float
     */
    const INV_LN_2 = 1.442695040889;

    /**
     * `float` `0.43429448190325` 1/ln(10)
     *
     * @var float
     */
    const INV_LN_10 = 0.43429448190325;

    /**
     * `float` `0.70710678118655` 1/sqrt(2)
     *
     * @var float
     */
    const INV_SQRT_2 = 0.70710678118655;

    /**
     * `float` `3.1415926535898` **π**
     *
     * @var float
     */
    const PI = 3.1415926535898;

    /**
     * `float` `1.5707963267949` **π** /2
     *
     * @var float
     */
    const HALF_PI = 1.5707963267949;

    /**
     * `float` `0.31830988618379` 1/**π**
     *
     * @var float
     */
    const INV_PI = 0.31830988618379;

    /**
     * `float` `0.017453292519943` Used by `degToRad` method.
     *
     * @var float
     */
    const DEG_TO_RAD = 0.017453292519943;

    /**
     * `float` `57.295779513082` Used by `radToDeg` method.
     *
     * @var float
     */
    const RAD_TO_DEG = 57.295779513082;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number down to the next lesser integer and returns it as a floating-point value.
     *
     * If the number is already rounded, the same (floating-point) number is returned.
     *
     * @param  number $nValue The number to be rounded.
     *
     * @return float The rounded number.
     */

    public static function floor ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return floor($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number up to the next greater integer and returns it as a floating-point value.
     *
     * If the number is already rounded, the same (floating-point) number is returned.
     *
     * @param  number $nValue The number to be rounded.
     *
     * @return float The rounded number.
     */

    public static function ceil ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return ceil($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number to the nearest integer or to a specified number of digits after the decimal point, and returns
     * it as a floating-point value.
     *
     * @param  number $nValue The number to be rounded.
     * @param  int $iFracLength **OPTIONAL. Default is** `0`. The length of the fractional part in the rounded number,
     * which is the number of digits after the decimal point.
     *
     * @return float The rounded number.
     */

    public static function round ($nValue, $iFracLength = 0)
    {
        assert( 'is_number($nValue) && is_int($iFracLength)', vs(isset($this), get_defined_vars()) );
        return round($nValue, $iFracLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the absolute value of a number.
     *
     * @param  number $nValue The input number.
     *
     * @return float The absolute value of the number.
     */

    public static function abs ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (float)abs($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the sign of a number, as a floating-point value.
     *
     * The method returns `-1.0` if the number is negative, `1.0` if the number is positive, and `0.0` if the number is
     * exactly `0.0`.
     *
     * @param  number $nValue The input number.
     *
     * @return float The sign of the number.
     */

    public static function sign ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (float)((int)( $nValue > 0.0 ) - (int)( $nValue < 0.0 ));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the smallest out of two numbers.
     *
     * @param  number $nValue0 The first number for comparison.
     * @param  number $nValue1 The second number for comparison.
     *
     * @return float The smallest number.
     */

    public static function min ($nValue0, $nValue1)
    {
        assert( 'is_number($nValue0) && is_number($nValue1)', vs(isset($this), get_defined_vars()) );
        return (float)(( $nValue0 < $nValue1 ) ? $nValue0 : $nValue1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the largest out of two numbers.
     *
     * @param  number $nValue0 The first number for comparison.
     * @param  number $nValue1 The second number for comparison.
     *
     * @return float The largest number.
     */

    public static function max ($nValue0, $nValue1)
    {
        assert( 'is_number($nValue0) && is_number($nValue1)', vs(isset($this), get_defined_vars()) );
        return (float)(( $nValue0 > $nValue1 ) ? $nValue0 : $nValue1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines which out of two numbers is smallest and which is largest.
     *
     * @param  number $nValue0 The first number for comparison.
     * @param  number $nValue1 The second number for comparison.
     * @param  reference $rfMin **OUTPUT.** After the method is called, the value of this parameter, which is of type
     * `float`, is the smallest number.
     * @param  reference $rfMax **OUTPUT.** After the method is called, the value of this parameter, which is of type
     * `float`, is the largest number.
     *
     * @return void
     */

    public static function minMax ($nValue0, $nValue1, &$rfMin, &$rfMax)
    {
        assert( 'is_number($nValue0) && is_number($nValue1)', vs(isset($this), get_defined_vars()) );

        if ( $nValue0 < $nValue1 )
        {
            $rfMin = (float)$nValue0;
            $rfMax = (float)$nValue1;
        }
        else
        {
            $rfMin = (float)$nValue1;
            $rfMax = (float)$nValue0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Clamps a number to a specified range.
     *
     * The original (floating-point) number is returned if it's already within the range.
     *
     * @param  number $nValue The number to be clamped.
     * @param  number $nRangeMin The smallest acceptable number for output. Any number less than this value will be
     * clamped to it.
     * @param  number $nRangeMax The largest acceptable number for output. Any number greater than this value will
     * be clamped to it.
     *
     * @return float The clamped number.
     */

    public static function clamp ($nValue, $nRangeMin, $nRangeMax)
    {
        assert( 'is_number($nValue) && is_number($nRangeMin) && is_number($nRangeMax)',
            vs(isset($this), get_defined_vars()) );

        if ( $nValue > $nRangeMax )
        {
            return (float)$nRangeMax;
        }
        if ( $nValue < $nRangeMin )
        {
            return (float)$nRangeMin;
        }
        return (float)$nValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Clamps a number to the unit range from `0.0` to `1.0`.
     *
     * This method clamps any number less than `0.0` to `0.0`, any number greater than `1.0` to `1.0`, and returns the
     * original (floating-point) number if it's already within the unit range.
     *
     * @param  number $nValue The number to be clamped.
     *
     * @return float The clamped number.
     */

    public static function unitClamp ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );

        if ( $nValue > 1.0 )
        {
            return 1.0;
        }
        if ( $nValue < 0.0 )
        {
            return 0.0;
        }
        return (float)$nValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the reminder of the division of two numbers.
     *
     * @param  number $nX The dividend.
     * @param  number $nY The divisor (cannot be zero).
     *
     * @return float The reminder of the division.
     */

    public static function mod ($nX, $nY)
    {
        assert( 'is_number($nX) && is_number($nY)', vs(isset($this), get_defined_vars()) );
        assert( 'abs($nY) > self::ZERO_TOLERANCE', vs(isset($this), get_defined_vars()) );

        return fmod($nX, $nY);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the square of a number.
     *
     * This method serves as an alternative to multiplying a number by itself manually with `*` operator.
     *
     * @param  number $nValue The input number.
     *
     * @return float The squared number.
     */

    public static function sqr ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (float)($nValue*$nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the square root of a number.
     *
     * @param  number $nValue The input number (should be positive or zero).
     *
     * @return float The number's square root.
     */

    public static function sqrt ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        assert( '$nValue >= 0.0', vs(isset($this), get_defined_vars()) );

        return sqrt($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the result of raising of a number to the power of a specified exponent.
     *
     * @param  number $nBase The number to be raised.
     * @param  number $nExponent The exponent.
     *
     * @return float The raised number.
     */

    public static function pow ($nBase, $nExponent)
    {
        assert( 'is_number($nBase) && is_number($nExponent)', vs(isset($this), get_defined_vars()) );
        return (float)pow($nBase, $nExponent);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the result of raising of **e** to the power of a specified number.
     *
     * @param  number $nValue The number to be used as the exponent.
     *
     * @return float The raised number.
     */

    public static function exp ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return exp($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the natural logarithm of a number.
     *
     * @param  number $nValue The input number (should be positive, non-zero).
     *
     * @return float The natural logarithm of the number.
     */

    public static function log ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        assert( '$nValue > self::ZERO_TOLERANCE', vs(isset($this), get_defined_vars()) );

        return log($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the logarithm of a number, calculated against the base of 10.
     *
     * @param  number $nValue The input number (should be positive, non-zero).
     *
     * @return float The base-10 logarithm of the number.
     */

    public static function log10 ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        assert( '$nValue > self::ZERO_TOLERANCE', vs(isset($this), get_defined_vars()) );

        return self::INV_LN_10*log($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the logarithm of a number, calculated against the base of 2.
     *
     * @param  number $nValue The input number (should be positive, non-zero).
     *
     * @return float The base-2 logarithm of the number.
     */

    public static function log2 ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        assert( '$nValue > self::ZERO_TOLERANCE', vs(isset($this), get_defined_vars()) );

        return self::INV_LN_2*log($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the sine of a number.
     *
     * @param  number $nValue The input number, in radians.
     *
     * @return float The sine of the number.
     */

    public static function sin ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return sin($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the cosine of a number.
     *
     * @param  number $nValue The input number, in radians.
     *
     * @return float The cosine of the number.
     */

    public static function cos ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return cos($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the tangent of a number.
     *
     * @param  number $nValue The input number, in radians.
     *
     * @return float The tangent of the number.
     */

    public static function tan ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return tan($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the arc sine of a number.
     *
     * As special cases, the method returns -**π** /2 if the number is less than or equal to `-1.0` and **π** /2 if the
     * number is greater than or equal to `1.0`.
     *
     * @param  number $nValue The input number.
     *
     * @return float The arc sine of the number, in radians.
     */

    public static function asin ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );

        if ( $nValue > -1.0 )
        {
            if ( $nValue < 1.0 )
            {
                return asin($nValue);
            }
            else
            {
                return self::HALF_PI;
            }
        }
        else
        {
            return -self::HALF_PI;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the arc cosine of a number.
     *
     * As special cases, the method returns **π** if the number is less than or equal to `-1.0` and `0.0` if the number
     * is greater than or equal to `1.0`.
     *
     * @param  number $nValue The input number.
     *
     * @return float The arc cosine of the number, in radians.
     */

    public static function acos ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );

        if ( $nValue > -1.0 )
        {
            if ( $nValue < 1.0 )
            {
                return acos($nValue);
            }
            else
            {
                return 0.0;
            }
        }
        else
        {
            return self::PI;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the arc tangent of a number.
     *
     * @param  number $nValue The input number.
     *
     * @return float The arc tangent of the number, in radians.
     */

    public static function atan ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return atan($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the angle derived from the coordinates of a point.
     *
     * The angle is measured starting from the X axis and, assuming that the Y axis goes down, in the clockwise
     * direction. The minimum value of a returned angle is -**π** and the maximum value is **π**.
     *
     * It's a common convention to have the Y coordinate going before the X coordinate in the parameter list. The
     * coordinates cannot be both zeros.
     *
     * @param  number $nY The Y coordinate of the point.
     * @param  number $nX The X coordinate of the point.
     *
     * @return float The angle, in radians. The range is [-**π**, **π**].
     */

    public static function atan2 ($nY, $nX)
    {
        assert( 'is_number($nY) && is_number($nX)', vs(isset($this), get_defined_vars()) );
        assert( '!(abs($nY) <= self::ZERO_TOLERANCE && abs($nX) <= self::ZERO_TOLERANCE)',
            vs(isset($this), get_defined_vars()) );

        return atan2($nY, $nX);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a number from degrees to radians.
     *
     * @param  number $nDegrees The number to be converted.
     *
     * @return float The converted number.
     */

    public static function degToRad ($nDegrees)
    {
        assert( 'is_number($nDegrees)', vs(isset($this), get_defined_vars()) );
        return $nDegrees*self::DEG_TO_RAD;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a number from radians to degrees.
     *
     * @param  number $nRadians The number to be converted.
     *
     * @return float The converted number.
     */

    public static function radToDeg ($nRadians)
    {
        assert( 'is_number($nRadians)', vs(isset($this), get_defined_vars()) );
        return $nRadians*self::RAD_TO_DEG;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a random number in the unit range from `0.0` to `1.0`.
     *
     * Random numbers returned by this method are in the range from `0.0`, inclusively, and up to `1.0`, but
     * non-inclusively for `1.0` (same as in JavaScript).
     *
     * The algorithm behind the random number generator is Mersenne twister.
     *
     * @return float A random number from the unit range [`0.0`, `1.0`).
     */

    public static function random ()
    {
        return mt_rand()/(mt_getrandmax() + 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a random number in the range from `-1.0` to `1.0`.
     *
     * The value of `1.0` is non-inclusive among returned random numbers.
     *
     * The algorithm behind the random number generator is Mersenne twister.
     *
     * @return float A random number from the range [`-1.0`, `1.0`).
     */

    public static function symmetricRandom ()
    {
        return 2.0*self::random() - 1.0;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a random number in the specified range.
     *
     * The algorithm behind the random number generator is Mersenne twister.
     *
     * @param  number $nMin The lower bound of the range, inclusively.
     * @param  number $nMax The higher bound of the range, non-inclusively.
     *
     * @return float A random number from the range specified.
     */

    public static function intervalRandom ($nMin, $nMax)
    {
        assert( 'is_number($nMin) && is_number($nMax)', vs(isset($this), get_defined_vars()) );
        return $nMin + ($nMax - $nMin)*self::random();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Seeds the random number generator.
     *
     * @param  int $iSeed The value with which the generator is to be seeded.
     *
     * @return void
     */

    public static function seedRandom ($iSeed)
    {
        assert( 'is_int($iSeed)', vs(isset($this), get_defined_vars()) );
        mt_srand($iSeed);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a number composed from specified integer and fractional parts.
     *
     * @param  int $iIntPart The integer part.
     * @param  int $iFracPart The fractional part.
     *
     * @return float The resulting floating-point number.
     */

    public static function fromIntAndFracParts ($iIntPart, $iFracPart)
    {
        assert( 'is_int($iIntPart) && is_int($iFracPart)', vs(isset($this), get_defined_vars()) );
        return (float)"$iIntPart.$iFracPart";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two numbers can be considered equal by comparing their difference with the value of
     * `ZERO_TOLERANCE` constant.
     *
     * Since the accuracy of floating-point arithmetics is not ideal, this method can be useful for finding out whether
     * two floating-point numbers are equal in situations when exact equality is not required.
     *
     * @param  number $nValue0 The first number for comparison.
     * @param  number $nValue1 The second number for comparison.
     *
     * @return bool `true` if the absolute difference between the two numbers is less than or equal to the value of
     * `ZERO_TOLERANCE` constant of the class (which is `1e-08`), `false` otherwise.
     */

    public static function equalsZt ($nValue0, $nValue1)
    {
        assert( 'is_number($nValue0) && is_number($nValue1)', vs(isset($this), get_defined_vars()) );
        return ( abs($nValue0 - $nValue1) <= self::ZERO_TOLERANCE );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
