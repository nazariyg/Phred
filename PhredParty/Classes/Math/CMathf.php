<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A set of static methods that perform mathematic operations by taking floating-point or integer numbers as parameters
 * but always outputting floating-point numbers.
 *
 * **You can refer to this class by its alias, which is** `Mf`.
 */

// Method signatures:
//   static float floor ($value)
//   static float ceil ($value)
//   static float round ($value, $fracLength = 0)
//   static float abs ($value)
//   static float sign ($value)
//   static float min ($value0, $value1)
//   static float max ($value0, $value1)
//   static void minMax ($value0, $value1, &$min, &$max)
//   static float clamp ($value, $rangeMin, $rangeMax)
//   static float unitClamp ($value)
//   static float mod ($x, $y)
//   static float sqr ($value)
//   static float sqrt ($value)
//   static float pow ($base, $exponent)
//   static float exp ($value)
//   static float log ($value)
//   static float log10 ($value)
//   static float log2 ($value)
//   static float sin ($value)
//   static float cos ($value)
//   static float tan ($value)
//   static float asin ($value)
//   static float acos ($value)
//   static float atan ($value)
//   static float atan2 ($y, $x)
//   static float degToRad ($degrees)
//   static float radToDeg ($radians)
//   static float random ()
//   static float symmetricRandom ()
//   static float intervalRandom ($min, $max)
//   static void seedRandom ($seed)
//   static float fromIntAndFracParts ($intPart, $fracPart)
//   static bool equalsZt ($value0, $value1)

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
     * @param  number $value The number to be rounded.
     *
     * @return float The rounded number.
     */

    public static function floor ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return floor($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number up to the next greater integer and returns it as a floating-point value.
     *
     * If the number is already rounded, the same (floating-point) number is returned.
     *
     * @param  number $value The number to be rounded.
     *
     * @return float The rounded number.
     */

    public static function ceil ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return ceil($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number to the nearest integer or to a specified number of digits after the decimal point, and returns
     * it as a floating-point value.
     *
     * @param  number $value The number to be rounded.
     * @param  int $fracLength **OPTIONAL. Default is** `0`. The length of the fractional part in the rounded number,
     * which is the number of digits after the decimal point.
     *
     * @return float The rounded number.
     */

    public static function round ($value, $fracLength = 0)
    {
        assert( 'is_number($value) && is_int($fracLength)', vs(isset($this), get_defined_vars()) );
        return round($value, $fracLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the absolute value of a number.
     *
     * @param  number $value The input number.
     *
     * @return float The absolute value of the number.
     */

    public static function abs ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (float)abs($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the sign of a number, as a floating-point value.
     *
     * The method returns `-1.0` if the number is negative, `1.0` if the number is positive, and `0.0` if the number is
     * exactly `0.0`.
     *
     * @param  number $value The input number.
     *
     * @return float The sign of the number.
     */

    public static function sign ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (float)((int)( $value > 0.0 ) - (int)( $value < 0.0 ));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the smallest out of two numbers.
     *
     * @param  number $value0 The first number for comparison.
     * @param  number $value1 The second number for comparison.
     *
     * @return float The smallest number.
     */

    public static function min ($value0, $value1)
    {
        assert( 'is_number($value0) && is_number($value1)', vs(isset($this), get_defined_vars()) );
        return (float)(( $value0 < $value1 ) ? $value0 : $value1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the largest out of two numbers.
     *
     * @param  number $value0 The first number for comparison.
     * @param  number $value1 The second number for comparison.
     *
     * @return float The largest number.
     */

    public static function max ($value0, $value1)
    {
        assert( 'is_number($value0) && is_number($value1)', vs(isset($this), get_defined_vars()) );
        return (float)(( $value0 > $value1 ) ? $value0 : $value1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines which out of two numbers is smallest and which is largest.
     *
     * @param  number $value0 The first number for comparison.
     * @param  number $value1 The second number for comparison.
     * @param  reference $min **OUTPUT.** After the method is called, the value of this parameter, which is of type
     * `float`, is the smallest number.
     * @param  reference $max **OUTPUT.** After the method is called, the value of this parameter, which is of type
     * `float`, is the largest number.
     *
     * @return void
     */

    public static function minMax ($value0, $value1, &$min, &$max)
    {
        assert( 'is_number($value0) && is_number($value1)', vs(isset($this), get_defined_vars()) );

        if ( $value0 < $value1 )
        {
            $min = (float)$value0;
            $max = (float)$value1;
        }
        else
        {
            $min = (float)$value1;
            $max = (float)$value0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Clamps a number to a specified range.
     *
     * The original (floating-point) number is returned if it's already within the range.
     *
     * @param  number $value The number to be clamped.
     * @param  number $rangeMin The smallest acceptable number for output. Any number less than this value will be
     * clamped to it.
     * @param  number $rangeMax The largest acceptable number for output. Any number greater than this value will
     * be clamped to it.
     *
     * @return float The clamped number.
     */

    public static function clamp ($value, $rangeMin, $rangeMax)
    {
        assert( 'is_number($value) && is_number($rangeMin) && is_number($rangeMax)',
            vs(isset($this), get_defined_vars()) );

        if ( $value > $rangeMax )
        {
            return (float)$rangeMax;
        }
        if ( $value < $rangeMin )
        {
            return (float)$rangeMin;
        }
        return (float)$value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Clamps a number to the unit range from `0.0` to `1.0`.
     *
     * This method clamps any number less than `0.0` to `0.0`, any number greater than `1.0` to `1.0`, and returns the
     * original (floating-point) number if it's already within the unit range.
     *
     * @param  number $value The number to be clamped.
     *
     * @return float The clamped number.
     */

    public static function unitClamp ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );

        if ( $value > 1.0 )
        {
            return 1.0;
        }
        if ( $value < 0.0 )
        {
            return 0.0;
        }
        return (float)$value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the reminder of the division of two numbers.
     *
     * @param  number $x The dividend.
     * @param  number $y The divisor (cannot be zero).
     *
     * @return float The reminder of the division.
     */

    public static function mod ($x, $y)
    {
        assert( 'is_number($x) && is_number($y)', vs(isset($this), get_defined_vars()) );
        assert( 'abs($y) > self::ZERO_TOLERANCE', vs(isset($this), get_defined_vars()) );

        return fmod($x, $y);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the square of a number.
     *
     * This method serves as an alternative to multiplying a number by itself manually with `*` operator.
     *
     * @param  number $value The input number.
     *
     * @return float The squared number.
     */

    public static function sqr ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (float)($value*$value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the square root of a number.
     *
     * @param  number $value The input number (should be positive or zero).
     *
     * @return float The number's square root.
     */

    public static function sqrt ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        assert( '$value >= 0.0', vs(isset($this), get_defined_vars()) );

        return sqrt($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the result of raising of a number to the power of a specified exponent.
     *
     * @param  number $base The number to be raised.
     * @param  number $exponent The exponent.
     *
     * @return float The raised number.
     */

    public static function pow ($base, $exponent)
    {
        assert( 'is_number($base) && is_number($exponent)', vs(isset($this), get_defined_vars()) );
        return (float)pow($base, $exponent);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the result of raising of **e** to the power of a specified number.
     *
     * @param  number $value The number to be used as the exponent.
     *
     * @return float The raised number.
     */

    public static function exp ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return exp($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the natural logarithm of a number.
     *
     * @param  number $value The input number (should be positive, non-zero).
     *
     * @return float The natural logarithm of the number.
     */

    public static function log ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        assert( '$value > self::ZERO_TOLERANCE', vs(isset($this), get_defined_vars()) );

        return log($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the logarithm of a number, calculated against the base of 10.
     *
     * @param  number $value The input number (should be positive, non-zero).
     *
     * @return float The base-10 logarithm of the number.
     */

    public static function log10 ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        assert( '$value > self::ZERO_TOLERANCE', vs(isset($this), get_defined_vars()) );

        return self::INV_LN_10*log($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the logarithm of a number, calculated against the base of 2.
     *
     * @param  number $value The input number (should be positive, non-zero).
     *
     * @return float The base-2 logarithm of the number.
     */

    public static function log2 ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        assert( '$value > self::ZERO_TOLERANCE', vs(isset($this), get_defined_vars()) );

        return self::INV_LN_2*log($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the sine of a number.
     *
     * @param  number $value The input number, in radians.
     *
     * @return float The sine of the number.
     */

    public static function sin ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return sin($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the cosine of a number.
     *
     * @param  number $value The input number, in radians.
     *
     * @return float The cosine of the number.
     */

    public static function cos ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return cos($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the tangent of a number.
     *
     * @param  number $value The input number, in radians.
     *
     * @return float The tangent of the number.
     */

    public static function tan ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return tan($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the arc sine of a number.
     *
     * As special cases, the method returns -**π** /2 if the number is less than or equal to `-1.0` and **π** /2 if the
     * number is greater than or equal to `1.0`.
     *
     * @param  number $value The input number.
     *
     * @return float The arc sine of the number, in radians.
     */

    public static function asin ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );

        if ( $value > -1.0 )
        {
            if ( $value < 1.0 )
            {
                return asin($value);
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
     * @param  number $value The input number.
     *
     * @return float The arc cosine of the number, in radians.
     */

    public static function acos ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );

        if ( $value > -1.0 )
        {
            if ( $value < 1.0 )
            {
                return acos($value);
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
     * @param  number $value The input number.
     *
     * @return float The arc tangent of the number, in radians.
     */

    public static function atan ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return atan($value);
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
     * @param  number $y The Y coordinate of the point.
     * @param  number $x The X coordinate of the point.
     *
     * @return float The angle, in radians. The range is [-**π**, **π**].
     */

    public static function atan2 ($y, $x)
    {
        assert( 'is_number($y) && is_number($x)', vs(isset($this), get_defined_vars()) );
        assert( '!(abs($y) <= self::ZERO_TOLERANCE && abs($x) <= self::ZERO_TOLERANCE)',
            vs(isset($this), get_defined_vars()) );

        return atan2($y, $x);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a number from degrees to radians.
     *
     * @param  number $degrees The number to be converted.
     *
     * @return float The converted number.
     */

    public static function degToRad ($degrees)
    {
        assert( 'is_number($degrees)', vs(isset($this), get_defined_vars()) );
        return $degrees*self::DEG_TO_RAD;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a number from radians to degrees.
     *
     * @param  number $radians The number to be converted.
     *
     * @return float The converted number.
     */

    public static function radToDeg ($radians)
    {
        assert( 'is_number($radians)', vs(isset($this), get_defined_vars()) );
        return $radians*self::RAD_TO_DEG;
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
     * @param  number $min The lower bound of the range, inclusively.
     * @param  number $max The higher bound of the range, non-inclusively.
     *
     * @return float A random number from the range specified.
     */

    public static function intervalRandom ($min, $max)
    {
        assert( 'is_number($min) && is_number($max)', vs(isset($this), get_defined_vars()) );
        return $min + ($max - $min)*self::random();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Seeds the random number generator.
     *
     * @param  int $seed The value with which the generator is to be seeded.
     *
     * @return void
     */

    public static function seedRandom ($seed)
    {
        assert( 'is_int($seed)', vs(isset($this), get_defined_vars()) );
        mt_srand($seed);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a number composed from specified integer and fractional parts.
     *
     * @param  int $intPart The integer part.
     * @param  int $fracPart The fractional part.
     *
     * @return float The resulting floating-point number.
     */

    public static function fromIntAndFracParts ($intPart, $fracPart)
    {
        assert( 'is_int($intPart) && is_int($fracPart)', vs(isset($this), get_defined_vars()) );
        return (float)"$intPart.$fracPart";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two numbers can be considered equal by comparing their difference with the value of
     * `ZERO_TOLERANCE` constant.
     *
     * Since the accuracy of floating-point arithmetics is not ideal, this method can be useful for finding out whether
     * two floating-point numbers are equal in situations when exact equality is not required.
     *
     * @param  number $value0 The first number for comparison.
     * @param  number $value1 The second number for comparison.
     *
     * @return bool `true` if the absolute difference between the two numbers is less than or equal to the value of
     * `ZERO_TOLERANCE` constant of the class (which is `1e-08`), `false` otherwise.
     */

    public static function equalsZt ($value0, $value1)
    {
        assert( 'is_number($value0) && is_number($value1)', vs(isset($this), get_defined_vars()) );
        return ( abs($value0 - $value1) <= self::ZERO_TOLERANCE );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
