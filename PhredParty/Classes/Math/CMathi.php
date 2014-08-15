<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A set of static methods that perform mathematic operations by taking integer or floating-point numbers as parameters
 * but always outputting integer numbers.
 *
 * **You can refer to this class by its alias, which is** `Mi`.
 */

// Method signatures:
//   static int floor ($nValue)
//   static int ceil ($nValue)
//   static int round ($nValue)
//   static int abs ($nValue)
//   static int sign ($nValue)
//   static int min ($nValue0, $nValue1)
//   static int max ($nValue0, $nValue1)
//   static void minMax ($nValue0, $nValue1, &$riMin, &$riMax)
//   static int clamp ($nValue, $nRangeMin, $nRangeMax)
//   static void div ($nValue, $nDivisor, &$riQuotient, &$riReminder)
//   static bool isEven ($nValue)
//   static bool isOdd ($nValue)
//   static bool isDivisible ($nValue, $nDivisor)
//   static int sqr ($nValue)
//   static int pow ($nBase, $nExponent)
//   static int log2 ($nValue)
//   static int intervalRandom ($nMin, $nMax)
//   static void seedRandom ($iSeed)
//   static bool isPow2 ($nValue)
//   static int roundToPow2Down ($nValue)
//   static int roundToPow2Up ($nValue)

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
     * @param  number $nValue The number to be rounded.
     *
     * @return int The rounded number.
     */

    public static function floor ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (int)floor($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number up to the next greater integer and returns it as an integer value.
     *
     * If the number is already rounded, the same number is returned as an integer value.
     *
     * @param  number $nValue The number to be rounded.
     *
     * @return int The rounded number.
     */

    public static function ceil ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (int)ceil($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number to the nearest integer and returns it as an integer value.
     *
     * @param  number $nValue The number to be rounded.
     *
     * @return int The rounded number.
     */

    public static function round ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (int)round($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the absolute value of a number.
     *
     * @param  number $nValue The input number.
     *
     * @return int The absolute value of the number.
     */

    public static function abs ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (int)abs($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the sign of a number, as an integer value.
     *
     * The method returns `-1` if the number is negative, `1` if the number is positive, and `0` if the number is zero.
     *
     * @param  number $nValue The input number.
     *
     * @return int The sign of the number.
     */

    public static function sign ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (int)( $nValue > 0 ) - (int)( $nValue < 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the smallest out of two numbers.
     *
     * @param  number $nValue0 The first number for comparison.
     * @param  number $nValue1 The second number for comparison.
     *
     * @return int The smallest number.
     */

    public static function min ($nValue0, $nValue1)
    {
        assert( 'is_number($nValue0) && is_number($nValue1)', vs(isset($this), get_defined_vars()) );
        return (int)(( $nValue0 < $nValue1 ) ? $nValue0 : $nValue1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the largest out of two numbers.
     *
     * @param  number $nValue0 The first number for comparison.
     * @param  number $nValue1 The second number for comparison.
     *
     * @return int The largest number.
     */

    public static function max ($nValue0, $nValue1)
    {
        assert( 'is_number($nValue0) && is_number($nValue1)', vs(isset($this), get_defined_vars()) );
        return (int)(( $nValue0 > $nValue1 ) ? $nValue0 : $nValue1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines which out of two numbers is smallest and which is largest.
     *
     * @param  number $nValue0 The first number for comparison.
     * @param  number $nValue1 The second number for comparison.
     * @param  reference $riMin **OUTPUT.** After the method is called, the value of this parameter, which is of type
     * `int`, is the smallest number.
     * @param  reference $riMax **OUTPUT.** After the method is called, the value of this parameter, which is of type
     * `int`, is the largest number.
     *
     * @return void
     */

    public static function minMax ($nValue0, $nValue1, &$riMin, &$riMax)
    {
        assert( 'is_number($nValue0) && is_number($nValue1)', vs(isset($this), get_defined_vars()) );

        if ( $nValue0 < $nValue1 )
        {
            $riMin = (int)$nValue0;
            $riMax = (int)$nValue1;
        }
        else
        {
            $riMin = (int)$nValue1;
            $riMax = (int)$nValue0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Clamps a number to a specified range.
     *
     * The original (integer) number is returned if it's already within the range.
     *
     * @param  number $nValue The number to be clamped.
     * @param  number $nRangeMin The smallest acceptable number for output. Any number less than this value will be
     * clamped to it.
     * @param  number $nRangeMax The largest acceptable number for output. Any number greater than this value will be
     * clamped to it.
     *
     * @return int The clamped number.
     */

    public static function clamp ($nValue, $nRangeMin, $nRangeMax)
    {
        assert( 'is_number($nValue) && is_number($nRangeMin) && is_number($nRangeMax)',
            vs(isset($this), get_defined_vars()) );

        if ( $nValue > $nRangeMax )
        {
            return (int)$nRangeMax;
        }
        if ( $nValue < $nRangeMin )
        {
            return (int)$nRangeMin;
        }
        return (int)$nValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Divides a number by another number and outputs the resulting quotient and reminder.
     *
     * @param  number $nValue The dividend.
     * @param  number $nDivisor The divisor.
     * @param  reference $riQuotient **OUTPUT.** After the method is called, the value of this parameter, which is of
     * type `int`, is the resulting quotient.
     * @param  reference $riReminder **OUTPUT.** After the method is called, the value of this parameter, which is of
     * type `int`, is the resulting reminder.
     *
     * @return void
     */

    public static function div ($nValue, $nDivisor, &$riQuotient, &$riReminder)
    {
        assert( 'is_number($nValue) && is_number($nDivisor)', vs(isset($this), get_defined_vars()) );

        $iValue = (int)$nValue;
        $iDivisor = (int)$nDivisor;
        $riQuotient = (int)($iValue/$iDivisor);
        $riReminder = $iValue - $iDivisor*$riQuotient;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a number is even.
     *
     * As a special case, the method returns `true` for zero.
     *
     * @param  number $nValue The input number.
     *
     * @return bool `true` if the number is even, `false` otherwise.
     */

    public static function isEven ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return ( (((int)$nValue) & 1) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a number is odd.
     *
     * As a special case, the method returns `false` for zero.
     *
     * @param  number $nValue The input number.
     *
     * @return bool `true` if the number is odd, `false` otherwise.
     */

    public static function isOdd ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return ( (((int)$nValue) & 1) != 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a number is divisible by another number.
     *
     * @param  number $nValue The dividend.
     * @param  number $nDivisor The divisor.
     *
     * @return bool `true` if the number is divisible, `false` otherwise.
     */

    public static function isDivisible ($nValue, $nDivisor)
    {
        assert( 'is_number($nValue) && is_number($nDivisor)', vs(isset($this), get_defined_vars()) );
        return ( ((int)$nValue) % ((int)$nDivisor) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the square of a number.
     *
     * This method serves as an alternative to multiplying a number by itself manually with `*` operator.
     *
     * @param  number $nValue The input number.
     *
     * @return int The squared number.
     */

    public static function sqr ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        return (int)($nValue*$nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the result of raising of a number to the power of a specified exponent.
     *
     * @param  number $nBase The number to be raised.
     * @param  number $nExponent The exponent (should be positive or zero).
     *
     * @return int The raised number.
     */

    public static function pow ($nBase, $nExponent)
    {
        assert( 'is_number($nBase) && is_number($nExponent)', vs(isset($this), get_defined_vars()) );
        assert( '$nExponent >= 0', vs(isset($this), get_defined_vars()) );

        $iExponent = (int)$nExponent;
        $nProduct = 1;
        while ( $iExponent-- != 0 )
        {
            $nProduct *= $nBase;
        }
        return (int)$nProduct;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the integer logarithm of a number, calculated against the base of 2.
     *
     * To rephrase, if the returned number is incremented by one, it indicates the minimum number of bits required to
     * store the value of the input number.
     *
     * @param  number $nValue The input number (should be positive, non-zero).
     *
     * @return int The integer base-2 logarithm of the number.
     */

    public static function log2 ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        assert( '$nValue > 0', vs(isset($this), get_defined_vars()) );

        $iValue = (int)$nValue;
        $iResult = 0;
        while ( true )
        {
            $iValue >>= 1;
            if ( $iValue == 0 )
            {
                return $iResult;
            }
            $iResult++;
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
     * @param  number $nMin The lower bound of the range, inclusively.
     * @param  number $nMax The higher bound of the range, inclusively.
     *
     * @return int A random number from the range specified.
     */

    public static function intervalRandom ($nMin, $nMax)
    {
        assert( 'is_number($nMin) && is_number($nMax)', vs(isset($this), get_defined_vars()) );

        $iMin = (int)$nMin;
        $iMax = (int)$nMax;
        return $iMin + (int)(($iMax - $iMin + 1)*CMathf::random());
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
        CMathf::seedRandom($iSeed);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a number is a power of 2.
     *
     * As a special case, the method returns `false` for zero.
     *
     * @param  number $nValue The input number.
     *
     * @return bool `true` if the number is a positive power of 2, `false` otherwise.
     */

    public static function isPow2 ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );

        $iValue = (int)$nValue;
        return ( $iValue != 0 && ($iValue & ($iValue - 1)) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number down to the next lesser power of 2 and returns it.
     *
     * If the number is already rounded, the same (integer) number is returned.
     *
     * @param  number $nValue The number to be rounded (should be positive, non-zero).
     *
     * @return int The rounded number.
     */

    public static function roundToPow2Down ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        assert( '$nValue > 0', vs(isset($this), get_defined_vars()) );

        return 1 << self::log2($nValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Rounds a number up to the next greater power of 2 and returns it.
     *
     * If the number is already rounded, the same (integer) number is returned.
     *
     * @param  number $nValue The number to be rounded (should be positive or zero).
     *
     * @return int The rounded number.
     */

    public static function roundToPow2Up ($nValue)
    {
        assert( 'is_number($nValue)', vs(isset($this), get_defined_vars()) );
        assert( '$nValue >= 0', vs(isset($this), get_defined_vars()) );

        $iValue = (int)$nValue;
        if ( $iValue == 0 )
        {
            return 1;
        }
        return ( self::isPow2($iValue) ) ? $iValue : 1 << (self::log2($iValue) + 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
