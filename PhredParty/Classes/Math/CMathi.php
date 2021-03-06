<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A set of static methods that perform mathematic operations by taking integer or floating-point numbers as parameters
 * but always outputting integer numbers.
 *
 * **You can refer to this class by its alias, which is** `Mi`.
 */

// Method signatures:
//   static int floor ($value)
//   static int ceil ($value)
//   static int round ($value)
//   static int abs ($value)
//   static int sign ($value)
//   static int min ($value0, $value1)
//   static int max ($value0, $value1)
//   static void minMax ($value0, $value1, &$min, &$max)
//   static int clamp ($value, $rangeMin, $rangeMax)
//   static void div ($value, $divisor, &$quotient, &$reminder)
//   static bool isEven ($value)
//   static bool isOdd ($value)
//   static bool isDivisible ($value, $divisor)
//   static int sqr ($value)
//   static int pow ($base, $exponent)
//   static int log2 ($value)
//   static int intervalRandom ($min, $max)
//   static void seedRandom ($seed)
//   static bool isPow2 ($value)
//   static int roundToPow2Down ($value)
//   static int roundToPow2Up ($value)

class CMathi extends CRootClass
{
    /**
     * `int` The largest possible value that can be held by `int` type.
     *
     * @var int
     */
    const INT_MAX = PHP_INT_MAX;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number down to the next lesser integer and returns it as an integer value.
     *
     * If the number is already rounded, the same number is returned as an integer value.
     *
     * @param  number $value The number to be rounded.
     *
     * @return int The rounded number.
     */

    public static function floor ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (int)floor($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number up to the next greater integer and returns it as an integer value.
     *
     * If the number is already rounded, the same number is returned as an integer value.
     *
     * @param  number $value The number to be rounded.
     *
     * @return int The rounded number.
     */

    public static function ceil ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (int)ceil($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number to the nearest integer and returns it as an integer value.
     *
     * @param  number $value The number to be rounded.
     *
     * @return int The rounded number.
     */

    public static function round ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (int)round($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the absolute value of a number.
     *
     * @param  number $value The input number.
     *
     * @return int The absolute value of the number.
     */

    public static function abs ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (int)abs($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the sign of a number, as an integer value.
     *
     * The method returns `-1` if the number is negative, `1` if the number is positive, and `0` if the number is zero.
     *
     * @param  number $value The input number.
     *
     * @return int The sign of the number.
     */

    public static function sign ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (int)( $value > 0 ) - (int)( $value < 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the smallest out of two numbers.
     *
     * @param  number $value0 The first number for comparison.
     * @param  number $value1 The second number for comparison.
     *
     * @return int The smallest number.
     */

    public static function min ($value0, $value1)
    {
        assert( 'is_number($value0) && is_number($value1)', vs(isset($this), get_defined_vars()) );
        return (int)(( $value0 < $value1 ) ? $value0 : $value1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the largest out of two numbers.
     *
     * @param  number $value0 The first number for comparison.
     * @param  number $value1 The second number for comparison.
     *
     * @return int The largest number.
     */

    public static function max ($value0, $value1)
    {
        assert( 'is_number($value0) && is_number($value1)', vs(isset($this), get_defined_vars()) );
        return (int)(( $value0 > $value1 ) ? $value0 : $value1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines which out of two numbers is smallest and which is largest.
     *
     * @param  number $value0 The first number for comparison.
     * @param  number $value1 The second number for comparison.
     * @param  reference $min **OUTPUT.** After the method is called, the value of this parameter, which is of type
     * `int`, is the smallest number.
     * @param  reference $max **OUTPUT.** After the method is called, the value of this parameter, which is of type
     * `int`, is the largest number.
     *
     * @return void
     */

    public static function minMax ($value0, $value1, &$min, &$max)
    {
        assert( 'is_number($value0) && is_number($value1)', vs(isset($this), get_defined_vars()) );

        if ( $value0 < $value1 )
        {
            $min = (int)$value0;
            $max = (int)$value1;
        }
        else
        {
            $min = (int)$value1;
            $max = (int)$value0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Clamps a number to a specified range.
     *
     * The original (integer) number is returned if it's already within the range.
     *
     * @param  number $value The number to be clamped.
     * @param  number $rangeMin The smallest acceptable number for output. Any number less than this value will be
     * clamped to it.
     * @param  number $rangeMax The largest acceptable number for output. Any number greater than this value will be
     * clamped to it.
     *
     * @return int The clamped number.
     */

    public static function clamp ($value, $rangeMin, $rangeMax)
    {
        assert( 'is_number($value) && is_number($rangeMin) && is_number($rangeMax)',
            vs(isset($this), get_defined_vars()) );

        if ( $value > $rangeMax )
        {
            return (int)$rangeMax;
        }
        if ( $value < $rangeMin )
        {
            return (int)$rangeMin;
        }
        return (int)$value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Divides a number by another number and outputs the resulting quotient and reminder.
     *
     * @param  number $value The dividend.
     * @param  number $divisor The divisor.
     * @param  reference $quotient **OUTPUT.** After the method is called, the value of this parameter, which is of
     * type `int`, is the resulting quotient.
     * @param  reference $reminder **OUTPUT.** After the method is called, the value of this parameter, which is of
     * type `int`, is the resulting reminder.
     *
     * @return void
     */

    public static function div ($value, $divisor, &$quotient, &$reminder)
    {
        assert( 'is_number($value) && is_number($divisor)', vs(isset($this), get_defined_vars()) );

        $intValue = (int)$value;
        $intDivisor = (int)$divisor;
        $quotient = (int)($intValue/$intDivisor);
        $reminder = $intValue - $intDivisor*$quotient;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a number is even.
     *
     * As a special case, the method returns `true` for zero.
     *
     * @param  number $value The input number.
     *
     * @return bool `true` if the number is even, `false` otherwise.
     */

    public static function isEven ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return ( (((int)$value) & 1) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a number is odd.
     *
     * As a special case, the method returns `false` for zero.
     *
     * @param  number $value The input number.
     *
     * @return bool `true` if the number is odd, `false` otherwise.
     */

    public static function isOdd ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return ( (((int)$value) & 1) != 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a number is divisible by another number.
     *
     * @param  number $value The dividend.
     * @param  number $divisor The divisor.
     *
     * @return bool `true` if the number is divisible, `false` otherwise.
     */

    public static function isDivisible ($value, $divisor)
    {
        assert( 'is_number($value) && is_number($divisor)', vs(isset($this), get_defined_vars()) );
        return ( ((int)$value) % ((int)$divisor) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the square of a number.
     *
     * This method serves as an alternative to multiplying a number by itself manually with `*` operator.
     *
     * @param  number $value The input number.
     *
     * @return int The squared number.
     */

    public static function sqr ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        return (int)($value*$value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the result of raising of a number to the power of a specified exponent.
     *
     * @param  number $base The number to be raised.
     * @param  number $exponent The exponent (should be positive or zero).
     *
     * @return int The raised number.
     */

    public static function pow ($base, $exponent)
    {
        assert( 'is_number($base) && is_number($exponent)', vs(isset($this), get_defined_vars()) );
        assert( '$exponent >= 0', vs(isset($this), get_defined_vars()) );

        $intExponent = (int)$exponent;
        $product = 1;
        while ( $intExponent-- != 0 )
        {
            $product *= $base;
        }
        return (int)$product;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the integer logarithm of a number, calculated against the base of 2.
     *
     * To rephrase, if the returned number is incremented by one, it indicates the minimum number of bits required to
     * store the value of the input number.
     *
     * @param  number $value The input number (should be positive, non-zero).
     *
     * @return int The integer base-2 logarithm of the number.
     */

    public static function log2 ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        assert( '$value > 0', vs(isset($this), get_defined_vars()) );

        $intValue = (int)$value;
        $result = 0;
        while ( true )
        {
            $intValue >>= 1;
            if ( $intValue == 0 )
            {
                return $result;
            }
            $result++;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a random number in the specified range.
     *
     * The values of both of the parameters can possibly be returned by the method.
     *
     * The algorithm behind the random number generator is Mersenne twister.
     *
     * @param  number $min The lower bound of the range, inclusively.
     * @param  number $max The higher bound of the range, inclusively.
     *
     * @return int A random number from the range specified.
     */

    public static function intervalRandom ($min, $max)
    {
        assert( 'is_number($min) && is_number($max)', vs(isset($this), get_defined_vars()) );

        $intMin = (int)$min;
        $intMax = (int)$max;
        return $intMin + (int)(($intMax - $intMin + 1)*CMathf::random());
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
        CMathf::seedRandom($seed);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a number is a power of 2.
     *
     * As a special case, the method returns `false` for zero.
     *
     * @param  number $value The input number.
     *
     * @return bool `true` if the number is a positive power of 2, `false` otherwise.
     */

    public static function isPow2 ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );

        $intValue = (int)$value;
        return ( $intValue != 0 && ($intValue & ($intValue - 1)) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number down to the next lesser power of 2 and returns it.
     *
     * If the number is already rounded, the same (integer) number is returned.
     *
     * @param  number $value The number to be rounded (should be positive, non-zero).
     *
     * @return int The rounded number.
     */

    public static function roundToPow2Down ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        assert( '$value > 0', vs(isset($this), get_defined_vars()) );

        return 1 << self::log2($value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number up to the next greater power of 2 and returns it.
     *
     * If the number is already rounded, the same (integer) number is returned.
     *
     * @param  number $value The number to be rounded (should be positive or zero).
     *
     * @return int The rounded number.
     */

    public static function roundToPow2Up ($value)
    {
        assert( 'is_number($value)', vs(isset($this), get_defined_vars()) );
        assert( '$value >= 0', vs(isset($this), get_defined_vars()) );

        $intValue = (int)$value;
        if ( $intValue == 0 )
        {
            return 1;
        }
        return ( self::isPow2($intValue) ) ? $intValue : 1 << (self::log2($intValue) + 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
