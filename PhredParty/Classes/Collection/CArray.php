<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The hub for static methods that take care of objects of the SplFixedArray class, which is also known as CArray.
 *
 * **In the OOP mode, you would likely never need to use this class.**
 *
 * *Just as the PHP's associative array is called CMap to avoid confusion with the regular array, the SplFixedArray
 * class is called CArray.*
 *
 * The [SplFixedArray](http://www.php.net/SplFixedArray) class comes with the Standard PHP Library (SPL), which is an
 * integral part of PHP. One of the SPL's goals is to provide efficient implementations for interfaces and classes that
 * have to do with datastructures in PHP, such as arrays.
 *
 * The efficiency of the SplFixedArray class, which under the hood is implemented in the language of C, resides in its
 * minimalistic simplicity. Unlike the PHP's native array, which is an *associative* array, the implementation of the
 * SplFixedArray class does not have to bother with hash maps or any other thingamajigs that create unnecessary
 * overhead when reading elements from an array or storing elements to an array with integer indexes. By not
 * oversimplifying it, the SplFixedArray class brings us a gift of better performance, with less memory usage and less
 * time consumption.
 *
 * In terms of JavaScript, the PHP's associative array is a soulmate to the JavaScript object that can similarly store
 * values under arbitrary keys and the soulmate of the JavaScript array is the SplFixedArray class. Despite of the
 * class' name, the length of an SplFixedArray is not fixed and any such array can be resized, which leaves a room for
 * guessing that "fixed" is actually referring to the PHP's native array as being fixed, at last.
 *
 * Little makes CArray different from the JavaScript array. Same as with JavaScript arrays, the first element in a
 * CArray is located at the position (index) of `0`, the second element at the position of `1`, and so forth. Another
 * striking resemblance would be that you can access an element in a CArray using `[ ]` notation, putting the element's
 * position between the brackets. You would usually go over the elements in a CArray with a `for` loop, but `foreach`
 * would work too; the SPL library, however, currently does not let the elements in a CArray be iterated by reference
 * with `foreach` loop.
 *
 * You can make a new instance of CArray as an empty array to be grown later, as an array with a number of blank
 * elements ready to be assigned, or from existing values using `fromElements` method or, a shorter one, `fe` method.
 * Something that is true for any array implementation is that, if the length of an array is known beforehand, it takes
 * less time to preallocate the elements and then assign values to them as opposed to adding the elements one by one
 * with `push` method.
 */

// Method signatures:
//   static CArray make ($length = 0)
//   static CArray makeDim2 ($lengthDim1, $lengthDim2 = 0)
//   static CArray makeDim3 ($lengthDim1, $lengthDim2, $lengthDim3 = 0)
//   static CArray makeDim4 ($lengthDim1, $lengthDim2, $lengthDim3, $lengthDim4 = 0)
//   static CArray makeCopy ($array)
//   static CArray fromPArray ($map)
//   static CArray fromElements (/*element0, element1, ...*/)
//   static CArray fe (/*element0, element1, ...*/)
//   static CMap toPArray ($array)
//   static string join ($array, $binder)
//   static int length ($array)
//   static bool isEmpty ($array)
//   static bool equals ($array, $toArray, $comparator = CComparator::EQUALITY)
//   static int compare ($array, $toArray, $comparator = CComparator::ORDER_ASC)
//   static mixed first ($array)
//   static mixed last ($array)
//   static mixed random ($array)
//   static CArray subar ($array, $startPos, $length = null)
//   static CArray subarray ($array, $startPos, $endPos)
//   static CArray slice ($array, $startPos, $endPos)
//   static bool find ($array, $whatElement, $comparator = CComparator::EQUALITY, &$foundAtPos = null)
//   static bool findScalar ($array, $whatElement, &$foundAtPos = null)
//   static bool findBinary ($array, $whatElement, $comparator = CComparator::ORDER_ASC, &$foundAtPos = null)
//   static int countElement ($array, $element, $comparator = CComparator::EQUALITY)
//   static void setLength ($array, $newLength)
//   static int push ($array, $element)
//   static mixed pop ($array)
//   static int pushArray ($array, $pushArray)
//   static int unshift ($array, $element)
//   static mixed shift ($array)
//   static int unshiftArray ($array, $addArray)
//   static void insert ($array, $atPos, $insertElement)
//   static void insertArray ($array, $atPos, $insertArray)
//   static void padStart ($array, $paddingElement, $newLength)
//   static void padEnd ($array, $paddingElement, $newLength)
//   static void remove ($array, $elementPos)
//   static bool removeByValue ($array, $elementValue, $comparator = CComparator::EQUALITY)
//   static void removeSubarray ($array, $startPos, $length = null)
//   static CArray splice ($array, $startPos, $length = null)
//   static void removeSubarrayByRange ($array, $startPos, $endPos)
//   static void reverse ($array)
//   static void shuffle ($array)
//   static void sort ($array, $comparator = CComparator::ORDER_ASC)
//   static void sortOn ($array, $onMethodName, $comparator = CComparator::ORDER_ASC/*, methodArg0, methodArg1,
//     ...*/)
//   static void sortStrings ($array)
//   static void sortStringsCi ($array)
//   static void sortStringsNat ($array)
//   static void sortStringsNatCi ($array)
//   static void sortUStrings ($array, $collationFlags = CUString::COLLATION_DEFAULT, CULocale $inLocale = null)
//   static void sortUStringsCi ($array, $collationFlags = CUString::COLLATION_DEFAULT, CULocale $inLocale = null)
//   static void sortUStringsNat ($array, $collationFlags = CUString::COLLATION_DEFAULT, CULocale $inLocale = null)
//   static void sortUStringsNatCi ($array, $collationFlags = CUString::COLLATION_DEFAULT,
//     CULocale $inLocale = null)
//   static CArray filter ($array, $filter)
//   static CArray unique ($array, $comparator = CComparator::EQUALITY)
//   static number elementsSum ($array)
//   static number elementsProduct ($array)
//   static bool isSubsetOf ($array, $ofArray, $comparator = CComparator::EQUALITY)
//   static CArray union (/*addendArray0, addendArray1, ...*/)
//   static CArray intersection ($leftArray, $rightArray, $comparator = CComparator::EQUALITY)
//   static CArray difference ($minuendArray, $subtrahendArray, $comparator = CComparator::EQUALITY)
//   static CArray symmetricDifference ($leftArray, $rightArray, $comparator = CComparator::EQUALITY)
//   static CArray repeat ($element, $times)

class CArray extends CRootClass implements ICopyingStatic, IEqualityAndOrderStatic
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) as either an empty array or an array having a
     * specified length.
     *
     * @param  int $length **OPTIONAL. Default is** `0`. The length of the array. Don't forget to give values to all
     * of the allocated elements when using this parameter.
     *
     * @return CArray The new array.
     */

    public static function make ($length = 0)
    {
        assert( 'is_int($length)', vs(isset($this), get_defined_vars()) );
        assert( '$length >= 0', vs(isset($this), get_defined_vars()) );

        return new SplFixedArray($length);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) as a two-dimensional array.
     *
     * @param  int $lengthDim1 The length of the array at the first level.
     * @param  int $lengthDim2 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the second level.
     *
     * @return CArray The new array.
     */

    public static function makeDim2 ($lengthDim1, $lengthDim2 = 0)
    {
        assert( 'is_int($lengthDim1) && is_int($lengthDim2)', vs(isset($this), get_defined_vars()) );
        assert( '$lengthDim1 >= 0 && $lengthDim2 >= 0', vs(isset($this), get_defined_vars()) );

        $array = self::make($lengthDim1);
        for ($i = 0; $i < $lengthDim1; $i++)
        {
            $array[$i] = self::make($lengthDim2);
        }
        return $array;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) as a three-dimensional array.
     *
     * @param  int $lengthDim1 The length of the array at the first level.
     * @param  int $lengthDim2 The length(s) of the array(s) at the second level.
     * @param  int $lengthDim3 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the third level.
     *
     * @return CArray The new array.
     */

    public static function makeDim3 ($lengthDim1, $lengthDim2, $lengthDim3 = 0)
    {
        assert( 'is_int($lengthDim1) && is_int($lengthDim2) && is_int($lengthDim3)',
            vs(isset($this), get_defined_vars()) );
        assert( '$lengthDim1 >= 0 && $lengthDim2 >= 0 && $lengthDim3 >= 0', vs(isset($this), get_defined_vars()) );

        $array = self::make($lengthDim1);
        for ($i0 = 0; $i0 < $lengthDim1; $i0++)
        {
            $array[$i0] = self::make($lengthDim2);
            for ($i1 = 0; $i1 < $lengthDim2; $i1++)
            {
                $array[$i0][$i1] = self::make($lengthDim3);
            }
        }
        return $array;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) as a four-dimensional array.
     *
     * @param  int $lengthDim1 The length of the array at the first level.
     * @param  int $lengthDim2 The length(s) of the array(s) at the second level.
     * @param  int $lengthDim3 The length(s) of the array(s) at the third level.
     * @param  int $lengthDim4 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the fourth level.
     *
     * @return CArray The new array.
     */

    public static function makeDim4 ($lengthDim1, $lengthDim2, $lengthDim3, $lengthDim4 = 0)
    {
        assert( 'is_int($lengthDim1) && is_int($lengthDim2) && is_int($lengthDim3) && is_int($lengthDim4)',
            vs(isset($this), get_defined_vars()) );
        assert( '$lengthDim1 >= 0 && $lengthDim2 >= 0 && $lengthDim3 >= 0 && $lengthDim4 >= 0',
            vs(isset($this), get_defined_vars()) );

        $array = self::make($lengthDim1);
        for ($i0 = 0; $i0 < $lengthDim1; $i0++)
        {
            $array[$i0] = self::make($lengthDim2);
            for ($i1 = 0; $i1 < $lengthDim2; $i1++)
            {
                $array[$i0][$i1] = self::make($lengthDim3);
                for ($i2 = 0; $i2 < $lengthDim3; $i2++)
                {
                    $array[$i0][$i1][$i2] = self::make($lengthDim4);
                }
            }
        }
        return $array;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes an independent copy of an array.
     *
     * @param  array $array The array to be copied.
     *
     * @return CArray A copy of the array.
     */

    public static function makeCopy ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        return clone $array;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a PHP's associative array into an SplFixedArray object (called CArray).
     *
     * All keys in the PHP's associative array are discarded and indexes for the resulting array are generated
     * according to the order in which values appear in the associative array, without gaps.
     *
     * @param  map $map The PHP's associative array to be converted.
     *
     * @return CArray The resulting array.
     */

    public static function fromPArray ($map)
    {
        assert( 'is_cmap($map)', vs(isset($this), get_defined_vars()) );

        $map = parray($map);

        return SplFixedArray::fromArray($map, false);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) from given elements.
     *
     * The new array contains the same elements that were passed as arguments to this method, in the same order.
     *
     * @return CArray The new array.
     */

    public static function fromElements (/*element0, element1, ...*/)
    {
        $funcNumArgs = func_num_args();
        assert( '$funcNumArgs != 0', vs(isset($this), get_defined_vars()) );
        return self::fromPArray(func_get_args());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * An alias for the previous method.
     *
     * Constructs an object of the SplFixedArray class (called CArray) from given elements.
     *
     * The new array contains the same elements that were passed as arguments to this method, in the same order.
     *
     * @return CArray The new array.
     */

    public static function fe (/*element0, element1, ...*/)
    {
        $funcNumArgs = func_num_args();
        assert( '$funcNumArgs != 0', vs(isset($this), get_defined_vars()) );
        return self::fromPArray(func_get_args());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an SplFixedArray object (called CArray) into a PHP's associative array.
     *
     * The first element from the source array is put into the resulting associative array under the integer key of
     * `0`, the second element under `1`, and so forth.
     *
     * @param  array $array The array to be converted.
     *
     * @return CMap The resulting associative array.
     */

    public static function toPArray ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        return $array->toArray();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Joins the elements of an array into a string.
     *
     * The elements in the source array are not required to be all strings and, in addition to types that know how to
     * become a string, an element can be `int`, `float`, or `bool`. In the resulting string, a boolean value of
     * `true` becomes "1" and `false` becomes "0".
     *
     * As a special case, the array is allowed to be empty.
     *
     * @param  array $array The array containing the elements to be joined.
     * @param  string $binder The string to be put between any two elements in the resulting string, such as ", ".
     * Can be empty.
     *
     * @return string The resulting string.
     */

    public static function join ($array, $binder)
    {
        assert( 'is_carray($array) && is_cstring($binder)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $array = self::makeCopy($array);
        for ($i = 0; $i < $array->getSize(); $i++)
        {
            if ( !is_cstring($array[$i]) )
            {
                if ( is_bool($array[$i]) )
                {
                    $array[$i] = CString::fromBool10($array[$i]);
                }
                else if ( is_int($array[$i]) )
                {
                    $array[$i] = CString::fromInt($array[$i]);
                }
                else if ( is_float($array[$i]) )
                {
                    $array[$i] = CString::fromFloat($array[$i]);
                }
            }
        }
        return implode($binder, self::toPArray($array));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many elements there are in an array.
     *
     * @param  array $array The array to be looked into.
     *
     * @return int The length of the array.
     */

    public static function length ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        return $array->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array is empty.
     *
     * @param  array $array The array to be looked into.
     *
     * @return bool `true` if the array is empty, `false` otherwise.
     */

    public static function isEmpty ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        return ( $array->getSize() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two arrays are equal.
     *
     * For any two arrays to be equal, they need to have the same number of the same elements arranged in the same
     * order.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CArray and CMap. See the [CComparator](CComparator.html)
     * class for more on this.
     *
     * @param  array $array The first array for comparison.
     * @param  array $toArray The second array for comparison.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the first array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return bool `true` if the two arrays are equal, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function equals ($array, $toArray, $comparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($array) && is_carray($toArray) && is_callable($comparator)',
            vs(isset($this), get_defined_vars()) );

        $array = splarray($array);
        $toArray = splarray($toArray);

        if ( $array->getSize() != $toArray->getSize() )
        {
            return false;
        }
        for ($i = 0; $i < $array->getSize(); $i++)
        {
            if ( !call_user_func($comparator, $array[$i], $toArray[$i]) )
            {
                return false;
            }
        }
        return true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two arrays should appear in a place where it matters.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool` in the ascending
     * order or in the descending order if you use `CComparator::ORDER_DESC`. And the default comparator is smart
     * enough to know how to compare objects of those classes that conform to the IEqualityAndOrder interface (static
     * or not), including CArray and CMap. See the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $array The first array for comparison.
     * @param  array $toArray The second array for comparison.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the first array and the second parameter being an
     * element from the second array, and return `-1` if the element from the first array would need to go before the
     * element from the second array if the two were being ordered in separate, `1` if the other way around, and `0` if
     * the two elements are equal.
     *
     * @return int A negative value (typically `-1`) if the first array should go before the second array, a positive
     * value (typically `1`) if the other way around, and `0` if the two arrays are equal.
     *
     * @link   CComparator.html CComparator
     */

    public static function compare ($array, $toArray, $comparator = CComparator::ORDER_ASC)
    {
        assert( 'is_carray($array) && is_carray($toArray) && is_callable($comparator)',
            vs(isset($this), get_defined_vars()) );

        $array = splarray($array);
        $toArray = splarray($toArray);

        if ( $array->getSize() != $toArray->getSize() )
        {
            return CMathi::sign($array->getSize() - $toArray->getSize());
        }
        for ($i = 0; $i < $array->getSize(); $i++)
        {
            $compRes = call_user_func($comparator, $array[$i], $toArray[$i]);
            if ( $compRes != 0 )
            {
                return $compRes;
            }
        }
        return 0;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the first element from an array.
     *
     * @param  array $array The array to be looked into.
     *
     * @return mixed The first element in the array.
     */

    public static function first ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        return $array[0];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the last element from an array.
     *
     * @param  array $array The array to be looked into.
     *
     * @return mixed The last element in the array.
     */

    public static function last ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        return $array[$array->getSize() - 1];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a randomly picked element from an array.
     *
     * @param  array $array The array to be looked into.
     *
     * @return mixed A randomly picked element in the array.
     */

    public static function random ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        return $array[CMathi::intervalRandom(0, $array->getSize() - 1)];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a sequence of elements from an array, as another array.
     *
     * This method works much like `substr` method of the CString and CUString classes.
     *
     * The method allows for an empty array to be returned.
     *
     * @param  array $array The array to be looked into.
     * @param  int $startPos The position of the first element in the sequence to be copied.
     * @param  int $length **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be copied.
     *
     * @return CArray The array containing the copied elements.
     */

    public static function subar ($array, $startPos, $length = null)
    {
        assert( 'is_carray($array) && is_int($startPos) && (!isset($length) || is_int($length))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < $array->getSize()) || ($startPos == $array->getSize() && ' .
                '(!isset($length) || $length == 0))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($length) || ($length >= 0 && $startPos + $length <= $array->getSize())',
            vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $subarray;
        if ( !isset($length) )
        {
            if ( $startPos == $array->getSize() )
            {
                return self::make();
            }
            $subarray = array_slice(self::toPArray($array), $startPos);
        }
        else
        {
            if ( $length == 0 )
            {
                return self::make();
            }
            $subarray = array_slice(self::toPArray($array), $startPos, $length);
        }
        return self::fromPArray($subarray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a sequence of elements from an array, as another array, with both starting and ending positions
     * specified.
     *
     * This method works much like `substring` method of the CString and CUString classes.
     *
     * The method allows for an empty array to be returned.
     *
     * @param  array $array The array to be looked into.
     * @param  int $startPos The position of the first element in the sequence to be copied.
     * @param  int $endPos The position of the element that *follows* the last element in the sequence to be copied.
     *
     * @return CArray The array containing the copied elements.
     */

    public static function subarray ($array, $startPos, $endPos)
    {
        assert( 'is_carray($array) && is_int($startPos) && is_int($endPos)', vs(isset($this), get_defined_vars()) );
        return self::subar($array, $startPos, $endPos - $startPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * An alias for the previous method.
     *
     * Returns a sequence of elements from an array, as another array, with both starting and ending positions
     * specified.
     *
     * This method works much like `substring` method of the CString and CUString classes.
     *
     * The method allows for an empty array to be returned.
     *
     * @param  array $array The array to be looked into.
     * @param  int $startPos The position of the first element in the sequence to be copied.
     * @param  int $endPos The position of the element that *follows* the last element in the sequence to be copied.
     *
     * @return CArray The array containing the copied elements.
     */

    public static function slice ($array, $startPos, $endPos)
    {
        assert( 'is_carray($array) && is_int($startPos) && is_int($endPos)', vs(isset($this), get_defined_vars()) );
        return self::subarray($array, $startPos, $endPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array contains an element with a specified value, using linear search.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CArray and CMap. See the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $array The array to be looked into.
     * @param  mixed $whatElement The value of the searched element.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of element values while searching. If this parameter is provided, the comparator should
     * take two parameters, with the first parameter being an element from the array and the second parameter being the
     * searched element, and return `true` if the two elements are equal and `false` otherwise.
     * @param  reference $foundAtPos **OPTIONAL. OUTPUT.** If an element has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `int`, indicates the position of the first
     * found element, i.e. the element at the leftmost position if the array contains more than one of such elements.
     *
     * @return bool `true` if such element was found in the array, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function find ($array, $whatElement, $comparator = CComparator::EQUALITY, &$foundAtPos = null)
    {
        // Linear search.

        assert( 'is_carray($array) && is_callable($comparator)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        for ($i = 0; $i < $array->getSize(); $i++)
        {
            if ( call_user_func($comparator, $array[$i], $whatElement) )
            {
                $foundAtPos = $i;
                return true;
            }
        }
        return false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array contains an element with a specified scalar value, using linear search.
     *
     * If an array only contains values of scalar types i.e. `int`, `float`, `bool`, `string` (ASCII only), or `null`,
     * this method allows for faster searches compared to `find` method. In case of `string` type, the search is
     * case-sensitive.
     *
     * @param  array $array The array to be looked into.
     * @param  mixed $whatElement The value of the searched element.
     * @param  reference $foundAtPos **OPTIONAL. OUTPUT.** If an element has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `int`, indicates the position of the first
     * found element, i.e. the element at the leftmost position if the array contains more than one of such elements.
     *
     * @return bool `true` if such element was found in the array, `false` otherwise.
     *
     * @link   #method_find find
     */

    public static function findScalar ($array, $whatElement, &$foundAtPos = null)
    {
        // Linear search.

        assert( 'is_carray($array) && (is_scalar($whatElement) || is_null($whatElement))',
            vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $res = array_search($whatElement, $array->toArray(), true);
        if ( $res !== false )
        {
            $foundAtPos = (int)$res;
            return true;
        }
        else
        {
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array contains an element with a specified value, using faster binary search.
     *
     * Binary search is much faster than linear search inside an array that was preliminary sorted. So using binary
     * search makes sense when many searches need to be run over the same large array.
     *
     * When looking for elements with this method, it's important to use the same comparator that was used for sorting
     * the array. As a cookbook recipe for string searching, use "CString::compare" string as the comparator for `sort`
     * method of this class to sort the elements in an array if they are all ASCII strings (or `sortStrings` method as
     * a shortcut) and then use the same "CString::compare" string as the comparator for this method, but use
     * "CUString::compare" string in the very same roles for Unicode strings (or `sortUStrings` method as a shortcut
     * for sorting). In case if the elements in an array are all objects, use `sortOn` method of this class with
     * "CString::compare" comparator when sorting on ASCII strings as return values and then provide the same
     * comparator to this method, and do it with "CUString::compare" comparator when sorting on Unicode strings.
     *
     * @param  array $array The array to be looked into.
     * @param  mixed $whatElement The value of the searched element.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of element values while searching. If this parameter is provided, the comparator should
     * take two parameters, with the first parameter being an element from the array and the second parameter being the
     * searched element, and return `-1` if the value of the first parameter would need to go before the value of the
     * second parameter if the two were being ordered in separate, `1` if the other way around, and `0` if the two
     * elements are equal.
     * @param  reference $foundAtPos **OPTIONAL. OUTPUT.** If an element has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `int`, indicates the position of the first
     * found element (if the searched element is not unique in the array, the position can differ from that with linear
     * search).
     *
     * @return bool `true` if such element was found in the array, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function findBinary ($array, $whatElement, $comparator = CComparator::ORDER_ASC,
        &$foundAtPos = null)
    {
        // Binary search.

        assert( 'is_carray($array) && is_callable($comparator)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        if ( self::isEmpty($array) )
        {
            return false;
        }

        $startPos = 0;
        $endPos = $array->getSize();
        while ( true )
        {
            $spanLength = $endPos - $startPos;
            if ( $spanLength == 1 )
            {
                break;
            }
            $pivotPos = $startPos + ($spanLength >> 1);
            // The `-` is because of the reversed argument order.
            if ( -call_user_func($comparator, $array[$pivotPos], $whatElement) < 0 )
            {
                // Go left.
                $endPos = $pivotPos;
            }
            else
            {
                // Go right.
                $startPos = $pivotPos;
            }
        }
        if ( call_user_func($comparator, $array[$startPos], $whatElement) == 0 )
        {
            $foundAtPos = $startPos;
            return true;
        }
        else
        {
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many elements with a specified value there are in an array.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CArray and CMap. See the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $array The array to be looked into.
     * @param  mixed $element The value of the searched element.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of element values while searching. If this parameter is provided, the comparator should
     * take two parameters, with the first parameter being an element from the array and the second parameter being the
     * searched element, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return int The number of such elements in the array.
     *
     * @link   CComparator.html CComparator
     */

    public static function countElement ($array, $element, $comparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($array) && is_callable($comparator)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $count = 0;
        for ($i = 0; $i < $array->getSize(); $i++)
        {
            if ( call_user_func($comparator, $array[$i], $element) )
            {
                $count++;
            }
        }
        return $count;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Resizes an array to a new length.
     *
     * No elements are lost if the array grows in size and all elements that are allowed by the new length are kept if
     * the array shrinks.
     *
     * @param  array $array The array to be resized.
     * @param  int $newLength The new length.
     *
     * @return void
     */

    public static function setLength ($array, $newLength)
    {
        assert( 'is_carray($array) && is_int($newLength)', vs(isset($this), get_defined_vars()) );
        assert( '$newLength >= 0', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $array->setSize($newLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an element to the end of an array.
     *
     * @param  array $array The array to be grown.
     * @param  mixed $element The element to be added.
     *
     * @return int The array's new length.
     */

    public static function push ($array, $element)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $len = $array->getSize();
        $array->setSize($len + 1);
        $array[$len] = $element;
        return $array->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from the end of an array.
     *
     * @param  array $array The array to be shrunk.
     *
     * @return mixed The removed element.
     */

    public static function pop ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $lenMinusOne = $array->getSize() - 1;
        $element = $array[$lenMinusOne];
        $array->setSize($lenMinusOne);
        return $element;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the elements of an array to the end of another array.
     *
     * @param  array $array The array to be grown.
     * @param  array $pushArray The array containing the elements to be added.
     *
     * @return int The array's new length.
     */

    public static function pushArray ($array, $pushArray)
    {
        assert( 'is_carray($array) && is_carray($pushArray)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);
        $pushArray = splarray($pushArray);

        self::assignArrayToArray($array, self::union($array, $pushArray));
        return $array->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an element to the start of an array.
     *
     * @param  array $array The array to be grown.
     * @param  mixed $element The element to be added.
     *
     * @return int The array's new length.
     */

    public static function unshift ($array, $element)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        self::insert($array, 0, $element);
        return $array->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from the start of an array.
     *
     * @param  array $array The array to be shrunk.
     *
     * @return mixed The removed element.
     */

    public static function shift ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $element = $array[0];
        self::remove($array, 0);
        return $element;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the elements of an array to the start of another array.
     *
     * @param  array $array The array to be grown.
     * @param  array $addArray The array containing the elements to be added.
     *
     * @return int The array's new length.
     */

    public static function unshiftArray ($array, $addArray)
    {
        assert( 'is_carray($array) && is_carray($addArray)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);
        $addArray = splarray($addArray);

        self::assignArrayToArray($array, self::union($addArray, $array));
        return $array->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts an element into an array.
     *
     * As a special case, the position of insertion can be equal to the array's length, which would make this method
     * work like `push` method.
     *
     * @param  array $array The array to be modified.
     * @param  int $atPos The position at which the new element is to be inserted.
     * @param  mixed $insertElement The new element.
     *
     * @return void
     */

    public static function insert ($array, $atPos, $insertElement)
    {
        assert( 'is_carray($array) && is_int($atPos)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $atPos && $atPos <= $array->getSize()', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        self::assignArrayToArray($array, self::union(self::subar($array, 0, $atPos),
            self::fromElements($insertElement), self::subar($array, $atPos)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts the elements of an array into another array.
     *
     * As a special case, the position of insertion can be equal to the array's length, which would make this method
     * work like `pushArray` method.
     *
     * @param  array $array The array to be modified.
     * @param  int $atPos The position at which the new elements are to be inserted.
     * @param  array $insertArray The array containing the elements to be inserted.
     *
     * @return void
     */

    public static function insertArray ($array, $atPos, $insertArray)
    {
        assert( 'is_carray($array) && is_int($atPos) && is_carray($insertArray)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $atPos && $atPos <= $array->getSize()', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);
        $insertArray = splarray($insertArray);

        self::assignArrayToArray($array, self::union(self::subar($array, 0, $atPos), $insertArray,
            self::subar($array, $atPos)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds elements to the start of an array to make it grow to a specified length.
     *
     * @param  array $array The array to be padded.
     * @param  mixed $paddingElement The element to be used for padding.
     * @param  int $newLength The array's new length.
     *
     * @return void
     */

    public static function padStart ($array, $paddingElement, $newLength)
    {
        assert( 'is_carray($array) && is_int($newLength)', vs(isset($this), get_defined_vars()) );
        assert( '$newLength >= $array->getSize()', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $diff = $newLength - $array->getSize();
        $padding = self::make($diff);
        for ($i = 0; $i < $diff; $i++)
        {
            $padding[$i] = $paddingElement;
        }
        self::assignArrayToArray($array, self::union($padding, $array));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds elements to the end of an array to make it grow to a specified length.
     *
     * @param  array $array The array to be padded.
     * @param  mixed $paddingElement The element to be used for padding.
     * @param  int $newLength The array's new length.
     *
     * @return void
     */

    public static function padEnd ($array, $paddingElement, $newLength)
    {
        assert( 'is_carray($array) && is_int($newLength)', vs(isset($this), get_defined_vars()) );
        assert( '$newLength >= $array->getSize()', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $diff = $newLength - $array->getSize();
        $padding = self::make($diff);
        for ($i = 0; $i < $diff; $i++)
        {
            $padding[$i] = $paddingElement;
        }
        self::assignArrayToArray($array, self::union($array, $padding));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from an array.
     *
     * @param  array $array The array to be modified.
     * @param  int $elementPos The position of the element to be removed.
     *
     * @return void
     */

    public static function remove ($array, $elementPos)
    {
        assert( 'is_carray($array) && is_int($elementPos)', vs(isset($this), get_defined_vars()) );
        self::removeSubarray($array, $elementPos, 1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From an array, removes all elements that have a specified value.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CArray and CMap. See the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $array The array to be modified.
     * @param  mixed $elementValue The value that is common to the elements to be removed.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of element values while searching. If this parameter is provided, the comparator should
     * take two parameters, with the first parameter being an element from the array and the second parameter being the
     * searched element, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return bool `true` if any elements were removed, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function removeByValue ($array, $elementValue, $comparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($array) && is_callable($comparator)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $anyRemoval = false;
        while ( true )
        {
            $foundAtPos;
            $isIn = self::find($array, $elementValue, $comparator, $foundAtPos);
            if ( $isIn )
            {
                self::remove($array, $foundAtPos);
                $anyRemoval = true;
            }
            else
            {
                return $anyRemoval;
            }
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  array $array The array to be modified.
     * @param  int $startPos The position of the first element in the sequence to be removed.
     * @param  int $length **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be removed.
     *
     * @return void
     */

    public static function removeSubarray ($array, $startPos, $length = null)
    {
        assert( 'is_carray($array) && is_int($startPos) && (!isset($length) || is_int($length))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < $array->getSize()) || ($startPos == $array->getSize() && ' .
                '(!isset($length) || $length == 0))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($length) || ($length >= 0 && $startPos + $length <= $array->getSize())',
            vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $resArray;
        if ( !isset($length) )
        {
            if ( $startPos == $array->getSize() )
            {
                return;
            }
            $resArray = self::toPArray($array);
            array_splice($resArray, $startPos);
        }
        else
        {
            if ( $length == 0 )
            {
                return;
            }
            $resArray = self::toPArray($array);
            array_splice($resArray, $startPos, $length);
        }
        self::assignArrayToMap($array, $resArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array and returns the removed elements, as another array.
     *
     * Use `removeSubarray` method if you don't care about what happens to the elements after they are removed.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  array $array The array to be modified.
     * @param  int $startPos The position of the first element in the sequence to be removed.
     * @param  int $length **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be removed.
     *
     * @return CArray The array containing the removed elements.
     *
     * @link   #method_removeSubarray removeSubarray
     */

    public static function splice ($array, $startPos, $length = null)
    {
        assert( 'is_carray($array) && is_int($startPos) && (!isset($length) || is_int($length))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < $array->getSize()) || ($startPos == $array->getSize() && ' .
                '(!isset($length) || $length == 0))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($length) || ($length >= 0 && $startPos + $length <= $array->getSize())',
            vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $resArray;
        $retArray;
        if ( !isset($length) )
        {
            if ( $startPos == $array->getSize() )
            {
                return self::make();
            }
            $resArray = self::toPArray($array);
            $retArray = array_splice($resArray, $startPos);
        }
        else
        {
            if ( $length == 0 )
            {
                return self::make();
            }
            $resArray = self::toPArray($array);
            $retArray = array_splice($resArray, $startPos, $length);
        }
        self::assignArrayToMap($array, $resArray);
        return self::fromPArray($retArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array, with both starting and ending positions specified.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  array $array The array to be modified.
     * @param  int $startPos The position of the first element in the sequence to be removed.
     * @param  int $endPos The position of the element that *follows* the last element in the sequence to be removed.
     *
     * @return void
     */

    public static function removeSubarrayByRange ($array, $startPos, $endPos)
    {
        assert( 'is_carray($array) && is_int($startPos) && is_int($endPos)', vs(isset($this), get_defined_vars()) );
        self::removeSubarray($array, $startPos, $endPos - $startPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reverses the order of the elements in an array.
     *
     * @param  array $array The array to be reversed.
     *
     * @return void
     */

    public static function reverse ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $lenDiv2 = $array->getSize() >> 1;
        for ($i0 = 0, $i1 = $array->getSize() - 1; $i0 < $lenDiv2; $i0++, $i1--)
        {
            $save = $array[$i0];
            $array[$i0] = $array[$i1];
            $array[$i1] = $save;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Randomizes the positions of the elements in an array.
     *
     * @param  array $array The array to be shuffled.
     *
     * @return void
     */

    public static function shuffle ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        // The Fisher-Yates algorithm.
        for ($i = $array->getSize() - 1; $i > 0; $i--)
        {
            $exchangeIdx = CMathi::intervalRandom(0, $i);
            $save = $array[$exchangeIdx];
            $array[$exchangeIdx] = $array[$i];
            $array[$i] = $save;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array.
     *
     * You can use your own comparator for element comparison, but the default comparators, such as
     * `CComparator::ORDER_ASC` and `CComparator::ORDER_DESC`, have got you covered when sorting scalar values, such as
     * `string`, `int`, `float`, and `bool` in the ascending or descending order respectively. And the default
     * comparators are smart enough to know how to compare objects of those classes that conform to the
     * IEqualityAndOrder interface (static or not), including CArray and CMap. See the [CComparator](CComparator.html)
     * class for more on this.
     *
     * @param  array $array The array to be sorted.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, which are the two elements being compared, and return `-1` if the first element needs to go before
     * the second element in the sorted array, `1` if the other way around, and `0` if the two elements are equal.
     *
     * @return void
     *
     * @link   CComparator.html CComparator
     */

    public static function sort ($array, $comparator = CComparator::ORDER_ASC)
    {
        assert( 'is_carray($array) && is_callable($comparator)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $pArray = self::toPArray($array);
        $res = usort($pArray, $comparator);
        assert( '$res', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($array, $pArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array by comparing return values from a specified method being called on each element.
     *
     * For example, if you had an array of UserClass objects and UserClass had a public method named `numPosts` that
     * would return an integer value, you could sort the users by the number of posts they've made by calling this
     * method with "numPosts" as the value of `$onMethodName` parameter.
     *
     * If you want to pass any custom arguments to the method that the array needs to be sorted on, just add them after
     * the comparator parameter when calling this method and the custom arguments will be passed to that method in the
     * same order.
     *
     * The method on which the array needs to be sorted can be static and, in this case, every element for which that
     * method is called is passed to that method as the first argument, in front of any custom arguments if they are
     * used.
     *
     * You can use your own comparator for the comparison of return values for the elements in the array, but the
     * default comparators, such as `CComparator::ORDER_ASC` and `CComparator::ORDER_DESC`, have got you covered when
     * sorting by scalar values, such as `string`, `int`, `float`, and `bool` in the ascending or descending order
     * respectively. And the default comparators are smart enough to know how to compare objects of those classes that
     * conform to the IEqualityAndOrder interface (static or not), including CArray and CMap. See the
     * [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $array The array to be sorted.
     * @param  string $onMethodName The name of the method on which the elements are to be sorted.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two return values. If this parameter is provided, the comparator should take two
     * parameters, which are the return values for the two elements being compared, and return `-1` if the first
     * element needs to go before the second element in the sorted array, `1` if the other way around, and `0` if the
     * two elements are considered equal based on the return values.
     *
     * @return void
     *
     * @link   CComparator.html CComparator
     */

    public static function sortOn ($array, $onMethodName, $comparator = CComparator::ORDER_ASC/*, methodArg0,
        methodArg1, ...*/)
    {
        assert( 'is_carray($array) && is_cstring($onMethodName) && is_callable($comparator)',
            vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        static $s_methodArgsStartPos = 3;
        $methodArgs = ( func_num_args() == $s_methodArgsStartPos ) ? [] :
            array_slice(func_get_args(), $s_methodArgsStartPos);
        $useComparator = function ($element0, $element1) use ($onMethodName, $comparator, $methodArgs)
            {
                $className;
                $typeIsNonScalar = phred_classify_duo($element0, $element1, $className);
                if ( CDebug::isDebugModeOn() )
                {
                    // Except for a few special cases, any two elements being compared must be objects of the same
                    // class.
                    assert( '$typeIsNonScalar', vs(isset($this), get_defined_vars()) );
                    $className0;
                    $className1;
                    phred_classify($element0, $className0);
                    phred_classify($element1, $className1);
                    assert( 'CString::equals($className0, $className1)', vs(isset($this), get_defined_vars()) );
                }
                $reflClass = new ReflectionClass($className);

                assert( '$reflClass->hasMethod($onMethodName)', vs(isset($this), get_defined_vars()) );
                $reflMethod = $reflClass->getMethod($onMethodName);
                assert( '$reflMethod->isPublic()', vs(isset($this), get_defined_vars()) );
                $methodInvokeRes0;
                $methodInvokeRes1;
                if ( !$reflMethod->isStatic() )
                {
                    if ( empty($methodArgs) )
                    {
                        $methodInvokeRes0 = $reflMethod->invoke($element0);
                        $methodInvokeRes1 = $reflMethod->invoke($element1);
                    }
                    else
                    {
                        $methodInvokeRes0 = $reflMethod->invokeArgs($element0, $methodArgs);
                        $methodInvokeRes1 = $reflMethod->invokeArgs($element1, $methodArgs);
                    }
                }
                else
                {
                    if ( empty($methodArgs) )
                    {
                        $methodInvokeRes0 = $reflMethod->invoke(null, $element0);
                        $methodInvokeRes1 = $reflMethod->invoke(null, $element1);
                    }
                    else
                    {
                        $methodInvokeRes0 = $reflMethod->invokeArgs(null, $element0, $methodArgs);
                        $methodInvokeRes1 = $reflMethod->invokeArgs(null, $element1, $methodArgs);
                    }
                }
                return call_user_func($comparator, $methodInvokeRes0, $methodInvokeRes1);
            };
        self::sort($array, $useComparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of ASCII strings, in the ascending order, case-sensitively.
     *
     * For an array that only contains strings, this method is usually preferred over more omnivorous `sort` method.
     *
     * **NOTE.** This method puts all uppercase in front of all lowercase (because it's dealing with ASCII text), so
     * you may consider using `sortUStrings` method instead if you don't want this.
     *
     * @param  array $array The array to be sorted.
     *
     * @return void
     *
     * @link   #method_sort sort
     * @link   #method_sortUStrings sortUStrings
     */

    public static function sortStrings ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );
        self::sort($array, "CString::compare");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of ASCII strings, in the ascending order, case-insensitively.
     *
     * @param  array $array The array to be sorted.
     *
     * @return void
     */

    public static function sortStringsCi ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );
        self::sort($array, "CString::compareCi");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of ASCII strings, in the ascending order, case-sensitively, using natural order
     * comparison.
     *
     * To illustrate natural order with an example, an array with strings "a100", "a20", "a3" would be sorted into the
     * same array with `sortStrings` method, but as "a3", "a20", "a100" with this method, which is the order a human
     * being would choose.
     *
     * **NOTE.** This method puts all uppercase in front of all lowercase (because it's dealing with ASCII text), so
     * you may consider using `sortUStringsNat` method instead if you don't want this.
     *
     * @param  array $array The array to be sorted.
     *
     * @return void
     *
     * @link   #method_sortUStringsNat sortUStringsNat
     */

    public static function sortStringsNat ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );
        self::sort($array, "CString::compareNat");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of ASCII strings, in the ascending order, case-insensitively, using natural order
     * comparison.
     *
     * To illustrate natural order with an example, an array with strings "a100", "a20", "a3" would be sorted into the
     * same array with `sortStringsCi` method, but as "a3", "a20", "a100" with this method, which is the order a human
     * being would choose.
     *
     * @param  array $array The array to be sorted.
     *
     * @return void
     */

    public static function sortStringsNatCi ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );
        self::sort($array, "CString::compareNatCi");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of Unicode or ASCII strings, in the ascending order, case-sensitively.
     *
     * For an array that only contains strings, this method is usually preferred over more omnivorous `sort` method.
     *
     * **NOTE.** This method puts lowercase in front of uppercase on per-character basis.
     *
     * @param  array $array The array to be sorted.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `CUString::COLLATION_DEFAULT`. The Unicode collation
     * option(s) to be used for string comparison. See the [CUString](CUString.html) class for information on collation
     * options.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUString.html CUString
     * @link   #method_sort sort
     */

    public static function sortUStrings ($array, $collationFlags = CUString::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $coll = CUString::collatorObject(false, false, $locale, $collationFlags);
        $pArray = self::toPArray($array);
        $res = $coll->sort($pArray);
        assert( '$res', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($array, $pArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of Unicode or ASCII strings, in the ascending order, case-insensitively.
     *
     * @param  array $array The array to be sorted.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `CUString::COLLATION_DEFAULT`. The Unicode collation
     * option(s) to be used for string comparison. See the [CUString](CUString.html) class for information on collation
     * options.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUString.html CUString
     */

    public static function sortUStringsCi ($array, $collationFlags = CUString::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $coll = CUString::collatorObject(true, false, $locale, $collationFlags);
        $pArray = self::toPArray($array);
        $res = $coll->sort($pArray);
        assert( '$res', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($array, $pArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of Unicode or ASCII strings, in the ascending order, case-sensitively, using
     * natural order comparison.
     *
     * To illustrate natural order with an example, an array with strings "a100", "a20", "a3" would be sorted into the
     * same array with `sortUStrings` method, but as "a3", "a20", "a100" with this method, which is the order a human
     * being would choose.
     *
     * **NOTE.** This method puts lowercase in front of uppercase on per-character basis.
     *
     * @param  array $array The array to be sorted.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `CUString::COLLATION_DEFAULT`. The Unicode collation
     * option(s) to be used for string comparison. See the [CUString](CUString.html) class for information on collation
     * options.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUString.html CUString
     */

    public static function sortUStringsNat ($array, $collationFlags = CUString::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $coll = CUString::collatorObject(false, true, $locale, $collationFlags);
        $pArray = self::toPArray($array);
        $res = $coll->sort($pArray);
        assert( '$res', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($array, $pArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of Unicode or ASCII strings, in the ascending order, case-insensitively, using
     * natural order comparison.
     *
     * To illustrate natural order with an example, an array with strings "a100", "a20", "a3" would be sorted into the
     * same array with `sortUStringsCi` method, but as "a3", "a20", "a100" with this method, which is the order a human
     * being would choose.
     *
     * @param  array $array The array to be sorted.
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `CUString::COLLATION_DEFAULT`. The Unicode collation
     * option(s) to be used for string comparison. See the [CUString](CUString.html) class for information on collation
     * options.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUString.html CUString
     */

    public static function sortUStringsNatCi ($array, $collationFlags = CUString::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $coll = CUString::collatorObject(true, true, $locale, $collationFlags);
        $pArray = self::toPArray($array);
        $res = $coll->sort($pArray);
        assert( '$res', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($array, $pArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Filters the elements in an array by calling a function or method on each element and returns a new array with
     * only those elements that were let through by the filter.
     *
     * The input array is not modified by this method.
     *
     * @param  array $array The array to be filtered.
     * @param  callable $filter The function or method to be used for filtering. The filter should take an element as
     * a parameter and return `true` if the element should make its way into the resulting array and `false` if not.
     *
     * @return CArray The filtered array.
     */

    public static function filter ($array, $filter)
    {
        assert( 'is_carray($array) && is_callable($filter)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        return self::fromPArray(array_filter(self::toPArray($array), $filter));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes repeating elements from an array so that only unique elements are kept and returns the new array.
     *
     * The input array is not modified by this method.
     *
     * You can use your own comparator for element comparison, but the default comparator has got you covered when
     * comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart
     * enough to know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder
     * interface (static or not), including CArray and CMap. See the [CComparator](CComparator.html) class for more on
     * this.
     *
     * @param  array $array The array to be cleaned up.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for element comparison. If this parameter is provided, the comparator should take two parameters, which are
     * the two elements being compared, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArray The cleaned up array.
     *
     * @link   CComparator.html CComparator
     */

    public static function unique ($array, $comparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($array) && is_callable($comparator)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $resArray = self::make();
        for ($i0 = 0; $i0 < $array->getSize(); $i0++)
        {
            $element = $array[$i0];
            $isIn = false;
            for ($i1 = 0; $i1 < $i0; $i1++)
            {
                if ( call_user_func($comparator, $element, $array[$i1]) )
                {
                    $isIn = true;
                    break;
                }
            }
            if ( !$isIn )
            {
                self::push($resArray, $element);
            }
        }
        return $resArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sums up all the elements in an array and returns the result.
     *
     * @param  array $array The array to be looked into.
     *
     * @return number The sum of all the elements in the array.
     */

    public static function elementsSum ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $sum = 0;
        for ($i = 0; $i < $array->getSize(); $i++)
        {
            $sum += $array[$i];
        }
        return $sum;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Multiplies all the elements in an array and returns the result.
     *
     * @param  array $array The array to be looked into.
     *
     * @return number The product of all the elements in the array.
     */

    public static function elementsProduct ($array)
    {
        assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($array)', vs(isset($this), get_defined_vars()) );

        $array = splarray($array);

        $product = 1;
        for ($i = 0; $i < $array->getSize(); $i++)
        {
            $product *= $array[$i];
        }
        return $product;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array is a subset of another array.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CArray and CMap. See the [CComparator](CComparator.html)
     * class for more on this.
     *
     * @param  array $array The array to be looked into.
     * @param  array $ofArray The reference array.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the array and the second parameter being an element
     * from the reference array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return bool `true` if the array is a subset of the reference array, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function isSubsetOf ($array, $ofArray, $comparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($array) && is_carray($ofArray) && is_callable($comparator)',
            vs(isset($this), get_defined_vars()) );

        $array = splarray($array);
        $ofArray = splarray($ofArray);

        if ( self::isEmpty($array) && !self::isEmpty($ofArray) )
        {
            // Special case.
            return false;
        }

        for ($i0 = 0; $i0 < $array->getSize(); $i0++)
        {
            $isIn = false;
            for ($i1 = 0; $i1 < $ofArray->getSize(); $i1++)
            {
                if ( call_user_func($comparator, $array[$i0], $ofArray[$i1]) )
                {
                    $isIn = true;
                    break;
                }
            }
            if ( !$isIn )
            {
                return false;
            }
        }
        return true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Unites two or more arrays together and returns the new array.
     *
     * The arrays that need to be united are passed as arguments to this method. The order in which the arrays are
     * passed determines the order of elements in the resulting array.
     *
     * None of the source arrays is modified by this method.
     *
     * @return CArray The union array.
     */

    public static function union (/*addendArray0, addendArray1, ...*/)
    {
        $funcNumArgs = func_num_args();
        assert( '$funcNumArgs >= 2', vs(isset($this), get_defined_vars()) );

        $resArray = CMap::make();
        $arguments = func_get_args();
        foreach ($arguments as $array)
        {
            assert( 'is_carray($array)', vs(isset($this), get_defined_vars()) );

            $array = splarray($array);

            $resArray = array_merge($resArray, self::toPArray($array));
        }
        return self::fromPArray($resArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Compares elements from one array with elements from another array and returns the common elements as a new
     * array.
     *
     * None of the source arrays is modified by this method.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CArray and CMap. See the [CComparator](CComparator.html)
     * class for more on this.
     *
     * @param  array $leftArray The first array.
     * @param  array $rightArray The second array.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the first array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArray The intersection array of the two arrays.
     *
     * @link   CComparator.html CComparator
     */

    public static function intersection ($leftArray, $rightArray, $comparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($leftArray) && is_carray($rightArray) && is_callable($comparator)',
            vs(isset($this), get_defined_vars()) );

        $leftArray = splarray($leftArray);
        $rightArray = splarray($rightArray);

        $resArray = self::make();
        for ($i0 = 0; $i0 < $leftArray->getSize(); $i0++)
        {
            $element = $leftArray[$i0];
            $isIn = false;
            for ($i1 = 0; $i1 < $rightArray->getSize(); $i1++)
            {
                if ( call_user_func($comparator, $element, $rightArray[$i1]) )
                {
                    $isIn = true;
                    break;
                }
            }
            if ( $isIn )
            {
                self::push($resArray, $element);
            }
        }
        return $resArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From an array, removes only those elements that are present in another array and returns the new array.
     *
     * None of the source arrays is modified by this method.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CArray and CMap. See the [CComparator](CComparator.html)
     * class for more on this.
     *
     * @param  array $minuendArray The minuend array.
     * @param  array $subtrahendArray The subtrahend array.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the minuend array and the second parameter being an
     * element from the subtrahend array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArray The difference array after subtracting the subtrahend array from the minuend array.
     *
     * @link   CComparator.html CComparator
     */

    public static function difference ($minuendArray, $subtrahendArray, $comparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($minuendArray) && is_carray($subtrahendArray) && is_callable($comparator)',
            vs(isset($this), get_defined_vars()) );

        $minuendArray = splarray($minuendArray);
        $subtrahendArray = splarray($subtrahendArray);

        $resArray = self::make();
        for ($i0 = 0; $i0 < $minuendArray->getSize(); $i0++)
        {
            $element = $minuendArray[$i0];
            $isIn = false;
            for ($i1 = 0; $i1 < $subtrahendArray->getSize(); $i1++)
            {
                if ( call_user_func($comparator, $element, $subtrahendArray[$i1]) )
                {
                    $isIn = true;
                    break;
                }
            }
            if ( !$isIn )
            {
                self::push($resArray, $element);
            }
        }
        return $resArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From two arrays, returns only those elements that are present in one of the arrays but not in the other, as a
     * new array.
     *
     * None of the source arrays is modified by this method.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CArray and CMap. See the [CComparator](CComparator.html)
     * class for more on this.
     *
     * @param  array $leftArray The first array.
     * @param  array $rightArray The second array.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the first array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArray The symmetric difference array between the two arrays.
     *
     * @link   CComparator.html CComparator
     */

    public static function symmetricDifference ($leftArray, $rightArray, $comparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($leftArray) && is_carray($rightArray) && is_callable($comparator)',
            vs(isset($this), get_defined_vars()) );

        $leftArray = splarray($leftArray);
        $rightArray = splarray($rightArray);

        $unionArray = self::union($leftArray, $rightArray);
        $intersectionArray = self::intersection($leftArray, $rightArray, $comparator);
        return self::difference($unionArray, $intersectionArray, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Repeats an element for a specified number of times and returns the resulting array.
     *
     * For instance, an element of "a" repeated three times would result in an array of "a", "a", "a".
     *
     * @param  mixed $element The element to be repeated.
     * @param  int $times The length of the resulting array.
     *
     * @return CArray The resulting array.
     */

    public static function repeat ($element, $times)
    {
        assert( 'is_int($times)', vs(isset($this), get_defined_vars()) );
        assert( '$times > 0', vs(isset($this), get_defined_vars()) );

        $resArray = self::make($times);
        for ($i = 0; $i < $times; $i++)
        {
            $resArray[$i] = $element;
        }
        return $resArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function assignArrayToArray ($array0, $array1)
    {
        $len = $array1->getSize();
        $array0->setSize(0);
        $array0->setSize($len);
        for ($i = 0; $i < $len; $i++)
        {
            $array0[$i] = $array1[$i];
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function assignArrayToMap ($array, $map)
    {
        $array->setSize(0);
        $array->setSize(count($map));
        $i = 0;
        foreach ($map as $value)
        {
            $array[$i++] = $value;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
