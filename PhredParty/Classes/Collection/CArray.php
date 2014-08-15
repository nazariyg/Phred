<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
//   static CArray make ($iLength = 0)
//   static CArray makeDim2 ($iLengthDim1, $iLengthDim2 = 0)
//   static CArray makeDim3 ($iLengthDim1, $iLengthDim2, $iLengthDim3 = 0)
//   static CArray makeDim4 ($iLengthDim1, $iLengthDim2, $iLengthDim3, $iLengthDim4 = 0)
//   static CArray makeCopy ($aArray)
//   static CArray fromPArray ($mMap)
//   static CArray fromElements (/*element0, element1, ...*/)
//   static CArray fe (/*element0, element1, ...*/)
//   static CMap toPArray ($aArray)
//   static string join ($aArray, $sBinder)
//   static int length ($aArray)
//   static bool isEmpty ($aArray)
//   static bool equals ($aArray, $aToArray, $fnComparator = CComparator::EQUALITY)
//   static int compare ($aArray, $aToArray, $fnComparator = CComparator::ORDER_ASC)
//   static mixed first ($aArray)
//   static mixed last ($aArray)
//   static mixed random ($aArray)
//   static CArray subar ($aArray, $iStartPos, $iLength = null)
//   static CArray subarray ($aArray, $iStartPos, $iEndPos)
//   static CArray slice ($aArray, $iStartPos, $iEndPos)
//   static bool find ($aArray, $xWhatElement, $fnComparator = CComparator::EQUALITY, &$riFoundAtPos = null)
//   static bool findScalar ($aArray, $xWhatElement, &$riFoundAtPos = null)
//   static bool findBinary ($aArray, $xWhatElement, $fnComparator = CComparator::ORDER_ASC, &$riFoundAtPos = null)
//   static int countElement ($aArray, $xElement, $fnComparator = CComparator::EQUALITY)
//   static void setLength ($aArray, $iNewLength)
//   static int push ($aArray, $xElement)
//   static mixed pop ($aArray)
//   static int pushArray ($aArray, $aPushArray)
//   static int unshift ($aArray, $xElement)
//   static mixed shift ($aArray)
//   static int unshiftArray ($aArray, $aAddArray)
//   static void insert ($aArray, $iAtPos, $xInsertElement)
//   static void insertArray ($aArray, $iAtPos, $aInsertArray)
//   static void padStart ($aArray, $xPaddingElement, $iNewLength)
//   static void padEnd ($aArray, $xPaddingElement, $iNewLength)
//   static void remove ($aArray, $iElementPos)
//   static bool removeByValue ($aArray, $xElementValue, $fnComparator = CComparator::EQUALITY)
//   static void removeSubarray ($aArray, $iStartPos, $iLength = null)
//   static CArray splice ($aArray, $iStartPos, $iLength = null)
//   static void removeSubarrayByRange ($aArray, $iStartPos, $iEndPos)
//   static void reverse ($aArray)
//   static void shuffle ($aArray)
//   static void sort ($aArray, $fnComparator = CComparator::ORDER_ASC)
//   static void sortOn ($aArray, $sOnMethodName, $fnComparator = CComparator::ORDER_ASC/*, methodArg0, methodArg1,
//     ...*/)
//   static void sortStrings ($aArray)
//   static void sortStringsCi ($aArray)
//   static void sortStringsNat ($aArray)
//   static void sortStringsNatCi ($aArray)
//   static void sortUStrings ($aArray, $bfCollationFlags = CUString::COLLATION_DEFAULT, CULocale $oInLocale = null)
//   static void sortUStringsCi ($aArray, $bfCollationFlags = CUString::COLLATION_DEFAULT, CULocale $oInLocale = null)
//   static void sortUStringsNat ($aArray, $bfCollationFlags = CUString::COLLATION_DEFAULT, CULocale $oInLocale = null)
//   static void sortUStringsNatCi ($aArray, $bfCollationFlags = CUString::COLLATION_DEFAULT,
//     CULocale $oInLocale = null)
//   static CArray filter ($aArray, $fnFilter)
//   static CArray unique ($aArray, $fnComparator = CComparator::EQUALITY)
//   static number elementsSum ($aArray)
//   static number elementsProduct ($aArray)
//   static bool isSubsetOf ($aArray, $aOfArray, $fnComparator = CComparator::EQUALITY)
//   static CArray union (/*addendArray0, addendArray1, ...*/)
//   static CArray intersection ($aLeftArray, $aRightArray, $fnComparator = CComparator::EQUALITY)
//   static CArray difference ($aMinuendArray, $aSubtrahendArray, $fnComparator = CComparator::EQUALITY)
//   static CArray symmetricDifference ($aLeftArray, $aRightArray, $fnComparator = CComparator::EQUALITY)
//   static CArray repeat ($xElement, $iTimes)

class CArray extends CRootClass implements ICopyingStatic, IEqualityAndOrderStatic
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) as either an empty array or an array having a
     * specified length.
     *
     * @param  int $iLength **OPTIONAL. Default is** `0`. The length of the array. Don't forget to give values to all
     * of the allocated elements when using this parameter.
     *
     * @return CArray The new array.
     */

    public static function make ($iLength = 0)
    {
        assert( 'is_int($iLength)', vs(isset($this), get_defined_vars()) );
        assert( '$iLength >= 0', vs(isset($this), get_defined_vars()) );

        return new SplFixedArray($iLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) as a two-dimensional array.
     *
     * @param  int $iLengthDim1 The length of the array at the first level.
     * @param  int $iLengthDim2 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the second level.
     *
     * @return CArray The new array.
     */

    public static function makeDim2 ($iLengthDim1, $iLengthDim2 = 0)
    {
        assert( 'is_int($iLengthDim1) && is_int($iLengthDim2)', vs(isset($this), get_defined_vars()) );
        assert( '$iLengthDim1 >= 0 && $iLengthDim2 >= 0', vs(isset($this), get_defined_vars()) );

        $aArray = self::make($iLengthDim1);
        for ($i = 0; $i < $iLengthDim1; $i++)
        {
            $aArray[$i] = self::make($iLengthDim2);
        }
        return $aArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) as a three-dimensional array.
     *
     * @param  int $iLengthDim1 The length of the array at the first level.
     * @param  int $iLengthDim2 The length(s) of the array(s) at the second level.
     * @param  int $iLengthDim3 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the third level.
     *
     * @return CArray The new array.
     */

    public static function makeDim3 ($iLengthDim1, $iLengthDim2, $iLengthDim3 = 0)
    {
        assert( 'is_int($iLengthDim1) && is_int($iLengthDim2) && is_int($iLengthDim3)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iLengthDim1 >= 0 && $iLengthDim2 >= 0 && $iLengthDim3 >= 0', vs(isset($this), get_defined_vars()) );

        $aArray = self::make($iLengthDim1);
        for ($i0 = 0; $i0 < $iLengthDim1; $i0++)
        {
            $aArray[$i0] = self::make($iLengthDim2);
            for ($i1 = 0; $i1 < $iLengthDim2; $i1++)
            {
                $aArray[$i0][$i1] = self::make($iLengthDim3);
            }
        }
        return $aArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Constructs an object of the SplFixedArray class (called CArray) as a four-dimensional array.
     *
     * @param  int $iLengthDim1 The length of the array at the first level.
     * @param  int $iLengthDim2 The length(s) of the array(s) at the second level.
     * @param  int $iLengthDim3 The length(s) of the array(s) at the third level.
     * @param  int $iLengthDim4 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the fourth level.
     *
     * @return CArray The new array.
     */

    public static function makeDim4 ($iLengthDim1, $iLengthDim2, $iLengthDim3, $iLengthDim4 = 0)
    {
        assert( 'is_int($iLengthDim1) && is_int($iLengthDim2) && is_int($iLengthDim3) && is_int($iLengthDim4)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iLengthDim1 >= 0 && $iLengthDim2 >= 0 && $iLengthDim3 >= 0 && $iLengthDim4 >= 0',
            vs(isset($this), get_defined_vars()) );

        $aArray = self::make($iLengthDim1);
        for ($i0 = 0; $i0 < $iLengthDim1; $i0++)
        {
            $aArray[$i0] = self::make($iLengthDim2);
            for ($i1 = 0; $i1 < $iLengthDim2; $i1++)
            {
                $aArray[$i0][$i1] = self::make($iLengthDim3);
                for ($i2 = 0; $i2 < $iLengthDim3; $i2++)
                {
                    $aArray[$i0][$i1][$i2] = self::make($iLengthDim4);
                }
            }
        }
        return $aArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes an independent copy of an array.
     *
     * @param  array $aArray The array to be copied.
     *
     * @return CArray A copy of the array.
     */

    public static function makeCopy ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        return clone $aArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a PHP's associative array into an SplFixedArray object (called CArray).
     *
     * All keys in the PHP's associative array are discarded and indexes for the resulting array are generated
     * according to the order in which values appear in the associative array, without gaps.
     *
     * @param  map $mMap The PHP's associative array to be converted.
     *
     * @return CArray The resulting array.
     */

    public static function fromPArray ($mMap)
    {
        assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        return SplFixedArray::fromArray($mMap, false);
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
        $iFuncNumArgs = func_num_args();
        assert( '$iFuncNumArgs != 0', vs(isset($this), get_defined_vars()) );
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
        $iFuncNumArgs = func_num_args();
        assert( '$iFuncNumArgs != 0', vs(isset($this), get_defined_vars()) );
        return self::fromPArray(func_get_args());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an SplFixedArray object (called CArray) into a PHP's associative array.
     *
     * The first element from the source array is put into the resulting associative array under the integer key of
     * `0`, the second element under `1`, and so forth.
     *
     * @param  array $aArray The array to be converted.
     *
     * @return CMap The resulting associative array.
     */

    public static function toPArray ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        return $aArray->toArray();
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
     * @param  array $aArray The array containing the elements to be joined.
     * @param  string $sBinder The string to be put between any two elements in the resulting string, such as ", ".
     * Can be empty.
     *
     * @return string The resulting string.
     */

    public static function join ($aArray, $sBinder)
    {
        assert( 'is_carray($aArray) && is_cstring($sBinder)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $aArray = self::makeCopy($aArray);
        for ($i = 0; $i < $aArray->getSize(); $i++)
        {
            if ( !is_cstring($aArray[$i]) )
            {
                if ( is_bool($aArray[$i]) )
                {
                    $aArray[$i] = CString::fromBool10($aArray[$i]);
                }
                else if ( is_int($aArray[$i]) )
                {
                    $aArray[$i] = CString::fromInt($aArray[$i]);
                }
                else if ( is_float($aArray[$i]) )
                {
                    $aArray[$i] = CString::fromFloat($aArray[$i]);
                }
            }
        }
        return implode($sBinder, self::toPArray($aArray));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many elements there are in an array.
     *
     * @param  array $aArray The array to be looked into.
     *
     * @return int The length of the array.
     */

    public static function length ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        return $aArray->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array is empty.
     *
     * @param  array $aArray The array to be looked into.
     *
     * @return bool `true` if the array is empty, `false` otherwise.
     */

    public static function isEmpty ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        return ( $aArray->getSize() == 0 );
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
     * @param  array $aArray The first array for comparison.
     * @param  array $aToArray The second array for comparison.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the first array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return bool `true` if the two arrays are equal, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function equals ($aArray, $aToArray, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($aArray) && is_carray($aToArray) && is_callable($fnComparator)',
            vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);
        $aToArray = splarray($aToArray);

        if ( $aArray->getSize() != $aToArray->getSize() )
        {
            return false;
        }
        for ($i = 0; $i < $aArray->getSize(); $i++)
        {
            if ( !call_user_func($fnComparator, $aArray[$i], $aToArray[$i]) )
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
     * @param  array $aArray The first array for comparison.
     * @param  array $aToArray The second array for comparison.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
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

    public static function compare ($aArray, $aToArray, $fnComparator = CComparator::ORDER_ASC)
    {
        assert( 'is_carray($aArray) && is_carray($aToArray) && is_callable($fnComparator)',
            vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);
        $aToArray = splarray($aToArray);

        if ( $aArray->getSize() != $aToArray->getSize() )
        {
            return CMathi::sign($aArray->getSize() - $aToArray->getSize());
        }
        for ($i = 0; $i < $aArray->getSize(); $i++)
        {
            $iCompRes = call_user_func($fnComparator, $aArray[$i], $aToArray[$i]);
            if ( $iCompRes != 0 )
            {
                return $iCompRes;
            }
        }
        return 0;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the first element from an array.
     *
     * @param  array $aArray The array to be looked into.
     *
     * @return mixed The first element in the array.
     */

    public static function first ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        return $aArray[0];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the last element from an array.
     *
     * @param  array $aArray The array to be looked into.
     *
     * @return mixed The last element in the array.
     */

    public static function last ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        return $aArray[$aArray->getSize() - 1];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a randomly picked element from an array.
     *
     * @param  array $aArray The array to be looked into.
     *
     * @return mixed A randomly picked element in the array.
     */

    public static function random ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        return $aArray[CMathi::intervalRandom(0, $aArray->getSize() - 1)];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a sequence of elements from an array, as another array.
     *
     * This method works much like `substr` method of the CString and CUString classes.
     *
     * The method allows for an empty array to be returned.
     *
     * @param  array $aArray The array to be looked into.
     * @param  int $iStartPos The position of the first element in the sequence to be copied.
     * @param  int $iLength **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be copied.
     *
     * @return CArray The array containing the copied elements.
     */

    public static function subar ($aArray, $iStartPos, $iLength = null)
    {
        assert( 'is_carray($aArray) && is_int($iStartPos) && (!isset($iLength) || is_int($iLength))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $iStartPos && $iStartPos < $aArray->getSize()) || ($iStartPos == $aArray->getSize() && ' .
                '(!isset($iLength) || $iLength == 0))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($iLength) || ($iLength >= 0 && $iStartPos + $iLength <= $aArray->getSize())',
            vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $mSubarray;
        if ( !isset($iLength) )
        {
            if ( $iStartPos == $aArray->getSize() )
            {
                return self::make();
            }
            $mSubarray = array_slice(self::toPArray($aArray), $iStartPos);
        }
        else
        {
            if ( $iLength == 0 )
            {
                return self::make();
            }
            $mSubarray = array_slice(self::toPArray($aArray), $iStartPos, $iLength);
        }
        return self::fromPArray($mSubarray);
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
     * @param  array $aArray The array to be looked into.
     * @param  int $iStartPos The position of the first element in the sequence to be copied.
     * @param  int $iEndPos The position of the element that *follows* the last element in the sequence to be copied.
     *
     * @return CArray The array containing the copied elements.
     */

    public static function subarray ($aArray, $iStartPos, $iEndPos)
    {
        assert( 'is_carray($aArray) && is_int($iStartPos) && is_int($iEndPos)', vs(isset($this), get_defined_vars()) );
        return self::subar($aArray, $iStartPos, $iEndPos - $iStartPos);
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
     * @param  array $aArray The array to be looked into.
     * @param  int $iStartPos The position of the first element in the sequence to be copied.
     * @param  int $iEndPos The position of the element that *follows* the last element in the sequence to be copied.
     *
     * @return CArray The array containing the copied elements.
     */

    public static function slice ($aArray, $iStartPos, $iEndPos)
    {
        assert( 'is_carray($aArray) && is_int($iStartPos) && is_int($iEndPos)', vs(isset($this), get_defined_vars()) );
        return self::subarray($aArray, $iStartPos, $iEndPos);
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
     * @param  array $aArray The array to be looked into.
     * @param  mixed $xWhatElement The value of the searched element.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of element values while searching. If this parameter is provided, the comparator should
     * take two parameters, with the first parameter being an element from the array and the second parameter being the
     * searched element, and return `true` if the two elements are equal and `false` otherwise.
     * @param  reference $riFoundAtPos **OPTIONAL. OUTPUT.** If an element has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `int`, indicates the position of the first
     * found element, i.e. the element at the leftmost position if the array contains more than one of such elements.
     *
     * @return bool `true` if such element was found in the array, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function find ($aArray, $xWhatElement, $fnComparator = CComparator::EQUALITY, &$riFoundAtPos = null)
    {
        // Linear search.

        assert( 'is_carray($aArray) && is_callable($fnComparator)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        for ($i = 0; $i < $aArray->getSize(); $i++)
        {
            if ( call_user_func($fnComparator, $aArray[$i], $xWhatElement) )
            {
                $riFoundAtPos = $i;
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
     * @param  array $aArray The array to be looked into.
     * @param  mixed $xWhatElement The value of the searched element.
     * @param  reference $riFoundAtPos **OPTIONAL. OUTPUT.** If an element has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `int`, indicates the position of the first
     * found element, i.e. the element at the leftmost position if the array contains more than one of such elements.
     *
     * @return bool `true` if such element was found in the array, `false` otherwise.
     *
     * @link   #method_find find
     */

    public static function findScalar ($aArray, $xWhatElement, &$riFoundAtPos = null)
    {
        // Linear search.

        assert( 'is_carray($aArray) && (is_scalar($xWhatElement) || is_null($xWhatElement))',
            vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $xRes = array_search($xWhatElement, $aArray->toArray(), true);
        if ( $xRes !== false )
        {
            $riFoundAtPos = (int)$xRes;
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
     * @param  array $aArray The array to be looked into.
     * @param  mixed $xWhatElement The value of the searched element.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of element values while searching. If this parameter is provided, the comparator should
     * take two parameters, with the first parameter being an element from the array and the second parameter being the
     * searched element, and return `-1` if the value of the first parameter would need to go before the value of the
     * second parameter if the two were being ordered in separate, `1` if the other way around, and `0` if the two
     * elements are equal.
     * @param  reference $riFoundAtPos **OPTIONAL. OUTPUT.** If an element has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `int`, indicates the position of the first
     * found element (if the searched element is not unique in the array, the position can differ from that with linear
     * search).
     *
     * @return bool `true` if such element was found in the array, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function findBinary ($aArray, $xWhatElement, $fnComparator = CComparator::ORDER_ASC,
        &$riFoundAtPos = null)
    {
        // Binary search.

        assert( 'is_carray($aArray) && is_callable($fnComparator)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        if ( self::isEmpty($aArray) )
        {
            return false;
        }

        $iStartPos = 0;
        $iEndPos = $aArray->getSize();
        while ( true )
        {
            $iSpanLength = $iEndPos - $iStartPos;
            if ( $iSpanLength == 1 )
            {
                break;
            }
            $iPivotPos = $iStartPos + ($iSpanLength >> 1);
            // The `-` is because of the reversed argument order.
            if ( -call_user_func($fnComparator, $aArray[$iPivotPos], $xWhatElement) < 0 )
            {
                // Go left.
                $iEndPos = $iPivotPos;
            }
            else
            {
                // Go right.
                $iStartPos = $iPivotPos;
            }
        }
        if ( call_user_func($fnComparator, $aArray[$iStartPos], $xWhatElement) == 0 )
        {
            $riFoundAtPos = $iStartPos;
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
     * @param  array $aArray The array to be looked into.
     * @param  mixed $xElement The value of the searched element.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of element values while searching. If this parameter is provided, the comparator should
     * take two parameters, with the first parameter being an element from the array and the second parameter being the
     * searched element, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return int The number of such elements in the array.
     *
     * @link   CComparator.html CComparator
     */

    public static function countElement ($aArray, $xElement, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($aArray) && is_callable($fnComparator)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $iCount = 0;
        for ($i = 0; $i < $aArray->getSize(); $i++)
        {
            if ( call_user_func($fnComparator, $aArray[$i], $xElement) )
            {
                $iCount++;
            }
        }
        return $iCount;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Resizes an array to a new length.
     *
     * No elements are lost if the array grows in size and all elements that are allowed by the new length are kept if
     * the array shrinks.
     *
     * @param  array $aArray The array to be resized.
     * @param  int $iNewLength The new length.
     *
     * @return void
     */

    public static function setLength ($aArray, $iNewLength)
    {
        assert( 'is_carray($aArray) && is_int($iNewLength)', vs(isset($this), get_defined_vars()) );
        assert( '$iNewLength >= 0', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $aArray->setSize($iNewLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an element to the end of an array.
     *
     * @param  array $aArray The array to be grown.
     * @param  mixed $xElement The element to be added.
     *
     * @return int The array's new length.
     */

    public static function push ($aArray, $xElement)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $iLen = $aArray->getSize();
        $aArray->setSize($iLen + 1);
        $aArray[$iLen] = $xElement;
        return $aArray->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from the end of an array.
     *
     * @param  array $aArray The array to be shrunk.
     *
     * @return mixed The removed element.
     */

    public static function pop ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $iLenMinusOne = $aArray->getSize() - 1;
        $xElement = $aArray[$iLenMinusOne];
        $aArray->setSize($iLenMinusOne);
        return $xElement;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the elements of an array to the end of another array.
     *
     * @param  array $aArray The array to be grown.
     * @param  array $aPushArray The array containing the elements to be added.
     *
     * @return int The array's new length.
     */

    public static function pushArray ($aArray, $aPushArray)
    {
        assert( 'is_carray($aArray) && is_carray($aPushArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);
        $aPushArray = splarray($aPushArray);

        self::assignArrayToArray($aArray, self::union($aArray, $aPushArray));
        return $aArray->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an element to the start of an array.
     *
     * @param  array $aArray The array to be grown.
     * @param  mixed $xElement The element to be added.
     *
     * @return int The array's new length.
     */

    public static function unshift ($aArray, $xElement)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        self::insert($aArray, 0, $xElement);
        return $aArray->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from the start of an array.
     *
     * @param  array $aArray The array to be shrunk.
     *
     * @return mixed The removed element.
     */

    public static function shift ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $xElement = $aArray[0];
        self::remove($aArray, 0);
        return $xElement;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the elements of an array to the start of another array.
     *
     * @param  array $aArray The array to be grown.
     * @param  array $aAddArray The array containing the elements to be added.
     *
     * @return int The array's new length.
     */

    public static function unshiftArray ($aArray, $aAddArray)
    {
        assert( 'is_carray($aArray) && is_carray($aAddArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);
        $aAddArray = splarray($aAddArray);

        self::assignArrayToArray($aArray, self::union($aAddArray, $aArray));
        return $aArray->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts an element into an array.
     *
     * As a special case, the position of insertion can be equal to the array's length, which would make this method
     * work like `push` method.
     *
     * @param  array $aArray The array to be modified.
     * @param  int $iAtPos The position at which the new element is to be inserted.
     * @param  mixed $xInsertElement The new element.
     *
     * @return void
     */

    public static function insert ($aArray, $iAtPos, $xInsertElement)
    {
        assert( 'is_carray($aArray) && is_int($iAtPos)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iAtPos && $iAtPos <= $aArray->getSize()', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        self::assignArrayToArray($aArray, self::union(self::subar($aArray, 0, $iAtPos),
            self::fromElements($xInsertElement), self::subar($aArray, $iAtPos)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts the elements of an array into another array.
     *
     * As a special case, the position of insertion can be equal to the array's length, which would make this method
     * work like `pushArray` method.
     *
     * @param  array $aArray The array to be modified.
     * @param  int $iAtPos The position at which the new elements are to be inserted.
     * @param  array $aInsertArray The array containing the elements to be inserted.
     *
     * @return void
     */

    public static function insertArray ($aArray, $iAtPos, $aInsertArray)
    {
        assert( 'is_carray($aArray) && is_int($iAtPos) && is_carray($aInsertArray)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $iAtPos && $iAtPos <= $aArray->getSize()', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);
        $aInsertArray = splarray($aInsertArray);

        self::assignArrayToArray($aArray, self::union(self::subar($aArray, 0, $iAtPos), $aInsertArray,
            self::subar($aArray, $iAtPos)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds elements to the start of an array to make it grow to a specified length.
     *
     * @param  array $aArray The array to be padded.
     * @param  mixed $xPaddingElement The element to be used for padding.
     * @param  int $iNewLength The array's new length.
     *
     * @return void
     */

    public static function padStart ($aArray, $xPaddingElement, $iNewLength)
    {
        assert( 'is_carray($aArray) && is_int($iNewLength)', vs(isset($this), get_defined_vars()) );
        assert( '$iNewLength >= $aArray->getSize()', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $iDiff = $iNewLength - $aArray->getSize();
        $aPadding = self::make($iDiff);
        for ($i = 0; $i < $iDiff; $i++)
        {
            $aPadding[$i] = $xPaddingElement;
        }
        self::assignArrayToArray($aArray, self::union($aPadding, $aArray));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds elements to the end of an array to make it grow to a specified length.
     *
     * @param  array $aArray The array to be padded.
     * @param  mixed $xPaddingElement The element to be used for padding.
     * @param  int $iNewLength The array's new length.
     *
     * @return void
     */

    public static function padEnd ($aArray, $xPaddingElement, $iNewLength)
    {
        assert( 'is_carray($aArray) && is_int($iNewLength)', vs(isset($this), get_defined_vars()) );
        assert( '$iNewLength >= $aArray->getSize()', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $iDiff = $iNewLength - $aArray->getSize();
        $aPadding = self::make($iDiff);
        for ($i = 0; $i < $iDiff; $i++)
        {
            $aPadding[$i] = $xPaddingElement;
        }
        self::assignArrayToArray($aArray, self::union($aArray, $aPadding));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from an array.
     *
     * @param  array $aArray The array to be modified.
     * @param  int $iElementPos The position of the element to be removed.
     *
     * @return void
     */

    public static function remove ($aArray, $iElementPos)
    {
        assert( 'is_carray($aArray) && is_int($iElementPos)', vs(isset($this), get_defined_vars()) );
        self::removeSubarray($aArray, $iElementPos, 1);
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
     * @param  array $aArray The array to be modified.
     * @param  mixed $xElementValue The value that is common to the elements to be removed.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of element values while searching. If this parameter is provided, the comparator should
     * take two parameters, with the first parameter being an element from the array and the second parameter being the
     * searched element, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return bool `true` if any elements were removed, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function removeByValue ($aArray, $xElementValue, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($aArray) && is_callable($fnComparator)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $bAnyRemoval = false;
        while ( true )
        {
            $iFoundAtPos;
            $bIsIn = self::find($aArray, $xElementValue, $fnComparator, $iFoundAtPos);
            if ( $bIsIn )
            {
                self::remove($aArray, $iFoundAtPos);
                $bAnyRemoval = true;
            }
            else
            {
                return $bAnyRemoval;
            }
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  array $aArray The array to be modified.
     * @param  int $iStartPos The position of the first element in the sequence to be removed.
     * @param  int $iLength **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be removed.
     *
     * @return void
     */

    public static function removeSubarray ($aArray, $iStartPos, $iLength = null)
    {
        assert( 'is_carray($aArray) && is_int($iStartPos) && (!isset($iLength) || is_int($iLength))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $iStartPos && $iStartPos < $aArray->getSize()) || ($iStartPos == $aArray->getSize() && ' .
                '(!isset($iLength) || $iLength == 0))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($iLength) || ($iLength >= 0 && $iStartPos + $iLength <= $aArray->getSize())',
            vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $mResArray;
        if ( !isset($iLength) )
        {
            if ( $iStartPos == $aArray->getSize() )
            {
                return;
            }
            $mResArray = self::toPArray($aArray);
            array_splice($mResArray, $iStartPos);
        }
        else
        {
            if ( $iLength == 0 )
            {
                return;
            }
            $mResArray = self::toPArray($aArray);
            array_splice($mResArray, $iStartPos, $iLength);
        }
        self::assignArrayToMap($aArray, $mResArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array and returns the removed elements, as another array.
     *
     * Use `removeSubarray` method if you don't care about what happens to the elements after they are removed.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  array $aArray The array to be modified.
     * @param  int $iStartPos The position of the first element in the sequence to be removed.
     * @param  int $iLength **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be removed.
     *
     * @return CArray The array containing the removed elements.
     *
     * @link   #method_removeSubarray removeSubarray
     */

    public static function splice ($aArray, $iStartPos, $iLength = null)
    {
        assert( 'is_carray($aArray) && is_int($iStartPos) && (!isset($iLength) || is_int($iLength))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $iStartPos && $iStartPos < $aArray->getSize()) || ($iStartPos == $aArray->getSize() && ' .
                '(!isset($iLength) || $iLength == 0))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($iLength) || ($iLength >= 0 && $iStartPos + $iLength <= $aArray->getSize())',
            vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $mResArray;
        $mRetArray;
        if ( !isset($iLength) )
        {
            if ( $iStartPos == $aArray->getSize() )
            {
                return self::make();
            }
            $mResArray = self::toPArray($aArray);
            $mRetArray = array_splice($mResArray, $iStartPos);
        }
        else
        {
            if ( $iLength == 0 )
            {
                return self::make();
            }
            $mResArray = self::toPArray($aArray);
            $mRetArray = array_splice($mResArray, $iStartPos, $iLength);
        }
        self::assignArrayToMap($aArray, $mResArray);
        return self::fromPArray($mRetArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array, with both starting and ending positions specified.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  array $aArray The array to be modified.
     * @param  int $iStartPos The position of the first element in the sequence to be removed.
     * @param  int $iEndPos The position of the element that *follows* the last element in the sequence to be removed.
     *
     * @return void
     */

    public static function removeSubarrayByRange ($aArray, $iStartPos, $iEndPos)
    {
        assert( 'is_carray($aArray) && is_int($iStartPos) && is_int($iEndPos)', vs(isset($this), get_defined_vars()) );
        self::removeSubarray($aArray, $iStartPos, $iEndPos - $iStartPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reverses the order of the elements in an array.
     *
     * @param  array $aArray The array to be reversed.
     *
     * @return void
     */

    public static function reverse ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $iLenDiv2 = $aArray->getSize() >> 1;
        for ($i0 = 0, $i1 = $aArray->getSize() - 1; $i0 < $iLenDiv2; $i0++, $i1--)
        {
            $xSave = $aArray[$i0];
            $aArray[$i0] = $aArray[$i1];
            $aArray[$i1] = $xSave;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Randomizes the positions of the elements in an array.
     *
     * @param  array $aArray The array to be shuffled.
     *
     * @return void
     */

    public static function shuffle ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        // The Fisher-Yates algorithm.
        for ($i = $aArray->getSize() - 1; $i > 0; $i--)
        {
            $iExchangeIdx = CMathi::intervalRandom(0, $i);
            $xSave = $aArray[$iExchangeIdx];
            $aArray[$iExchangeIdx] = $aArray[$i];
            $aArray[$i] = $xSave;
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
     * @param  array $aArray The array to be sorted.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, which are the two elements being compared, and return `-1` if the first element needs to go before
     * the second element in the sorted array, `1` if the other way around, and `0` if the two elements are equal.
     *
     * @return void
     *
     * @link   CComparator.html CComparator
     */

    public static function sort ($aArray, $fnComparator = CComparator::ORDER_ASC)
    {
        assert( 'is_carray($aArray) && is_callable($fnComparator)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $mArray = self::toPArray($aArray);
        $bRes = usort($mArray, $fnComparator);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($aArray, $mArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array by comparing return values from a specified method being called on each element.
     *
     * For example, if you had an array of UserClass objects and UserClass had a public method named `numPosts` that
     * would return an integer value, you could sort the users by the number of posts they've made by calling this
     * method with "numPosts" as the value of `$sOnMethodName` parameter.
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
     * @param  array $aArray The array to be sorted.
     * @param  string $sOnMethodName The name of the method on which the elements are to be sorted.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two return values. If this parameter is provided, the comparator should take two
     * parameters, which are the return values for the two elements being compared, and return `-1` if the first
     * element needs to go before the second element in the sorted array, `1` if the other way around, and `0` if the
     * two elements are considered equal based on the return values.
     *
     * @return void
     *
     * @link   CComparator.html CComparator
     */

    public static function sortOn ($aArray, $sOnMethodName, $fnComparator = CComparator::ORDER_ASC/*, methodArg0,
        methodArg1, ...*/)
    {
        assert( 'is_carray($aArray) && is_cstring($sOnMethodName) && is_callable($fnComparator)',
            vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        static $s_iMethodArgsStartPos = 3;
        $mMethodArgs = ( func_num_args() == $s_iMethodArgsStartPos ) ? [] :
            array_slice(func_get_args(), $s_iMethodArgsStartPos);
        $fnUseComparator = function ($xElement0, $xElement1) use ($sOnMethodName, $fnComparator, $mMethodArgs)
            {
                $sClassName;
                $bTypeIsNonScalar = phred_classify_duo($xElement0, $xElement1, $sClassName);
                if ( CDebug::isDebugModeOn() )
                {
                    // Except for a few special cases, any two elements being compared must be objects of the same
                    // class.
                    assert( '$bTypeIsNonScalar', vs(isset($this), get_defined_vars()) );
                    $sClassName0;
                    $sClassName1;
                    phred_classify($xElement0, $sClassName0);
                    phred_classify($xElement1, $sClassName1);
                    assert( 'CString::equals($sClassName0, $sClassName1)', vs(isset($this), get_defined_vars()) );
                }
                $oReflClass = new ReflectionClass($sClassName);

                assert( '$oReflClass->hasMethod($sOnMethodName)', vs(isset($this), get_defined_vars()) );
                $oReflMethod = $oReflClass->getMethod($sOnMethodName);
                assert( '$oReflMethod->isPublic()', vs(isset($this), get_defined_vars()) );
                $xMethodInvokeRes0;
                $xMethodInvokeRes1;
                if ( !$oReflMethod->isStatic() )
                {
                    if ( empty($mMethodArgs) )
                    {
                        $xMethodInvokeRes0 = $oReflMethod->invoke($xElement0);
                        $xMethodInvokeRes1 = $oReflMethod->invoke($xElement1);
                    }
                    else
                    {
                        $xMethodInvokeRes0 = $oReflMethod->invokeArgs($xElement0, $mMethodArgs);
                        $xMethodInvokeRes1 = $oReflMethod->invokeArgs($xElement1, $mMethodArgs);
                    }
                }
                else
                {
                    if ( empty($mMethodArgs) )
                    {
                        $xMethodInvokeRes0 = $oReflMethod->invoke(null, $xElement0);
                        $xMethodInvokeRes1 = $oReflMethod->invoke(null, $xElement1);
                    }
                    else
                    {
                        $xMethodInvokeRes0 = $oReflMethod->invokeArgs(null, $xElement0, $mMethodArgs);
                        $xMethodInvokeRes1 = $oReflMethod->invokeArgs(null, $xElement1, $mMethodArgs);
                    }
                }
                return call_user_func($fnComparator, $xMethodInvokeRes0, $xMethodInvokeRes1);
            };
        self::sort($aArray, $fnUseComparator);
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
     * @param  array $aArray The array to be sorted.
     *
     * @return void
     *
     * @link   #method_sort sort
     * @link   #method_sortUStrings sortUStrings
     */

    public static function sortStrings ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );
        self::sort($aArray, "CString::compare");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of ASCII strings, in the ascending order, case-insensitively.
     *
     * @param  array $aArray The array to be sorted.
     *
     * @return void
     */

    public static function sortStringsCi ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );
        self::sort($aArray, "CString::compareCi");
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
     * @param  array $aArray The array to be sorted.
     *
     * @return void
     *
     * @link   #method_sortUStringsNat sortUStringsNat
     */

    public static function sortStringsNat ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );
        self::sort($aArray, "CString::compareNat");
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
     * @param  array $aArray The array to be sorted.
     *
     * @return void
     */

    public static function sortStringsNatCi ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );
        self::sort($aArray, "CString::compareNatCi");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of Unicode or ASCII strings, in the ascending order, case-sensitively.
     *
     * For an array that only contains strings, this method is usually preferred over more omnivorous `sort` method.
     *
     * **NOTE.** This method puts lowercase in front of uppercase on per-character basis.
     *
     * @param  array $aArray The array to be sorted.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `CUString::COLLATION_DEFAULT`. The Unicode collation
     * option(s) to be used for string comparison. See the [CUString](CUString.html) class for information on collation
     * options.
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUString.html CUString
     * @link   #method_sort sort
     */

    public static function sortUStrings ($aArray, $bfCollationFlags = CUString::COLLATION_DEFAULT,
        CULocale $oInLocale = null)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $oColl = CUString::collatorObject(false, false, $sLocale, $bfCollationFlags);
        $mArray = self::toPArray($aArray);
        $bRes = $oColl->sort($mArray);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($aArray, $mArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of Unicode or ASCII strings, in the ascending order, case-insensitively.
     *
     * @param  array $aArray The array to be sorted.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `CUString::COLLATION_DEFAULT`. The Unicode collation
     * option(s) to be used for string comparison. See the [CUString](CUString.html) class for information on collation
     * options.
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUString.html CUString
     */

    public static function sortUStringsCi ($aArray, $bfCollationFlags = CUString::COLLATION_DEFAULT,
        CULocale $oInLocale = null)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $oColl = CUString::collatorObject(true, false, $sLocale, $bfCollationFlags);
        $mArray = self::toPArray($aArray);
        $bRes = $oColl->sort($mArray);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($aArray, $mArray);
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
     * @param  array $aArray The array to be sorted.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `CUString::COLLATION_DEFAULT`. The Unicode collation
     * option(s) to be used for string comparison. See the [CUString](CUString.html) class for information on collation
     * options.
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUString.html CUString
     */

    public static function sortUStringsNat ($aArray, $bfCollationFlags = CUString::COLLATION_DEFAULT,
        CULocale $oInLocale = null)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $oColl = CUString::collatorObject(false, true, $sLocale, $bfCollationFlags);
        $mArray = self::toPArray($aArray);
        $bRes = $oColl->sort($mArray);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($aArray, $mArray);
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
     * @param  array $aArray The array to be sorted.
     * @param  bitfield $bfCollationFlags **OPTIONAL. Default is** `CUString::COLLATION_DEFAULT`. The Unicode collation
     * option(s) to be used for string comparison. See the [CUString](CUString.html) class for information on collation
     * options.
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUString.html CUString
     */

    public static function sortUStringsNatCi ($aArray, $bfCollationFlags = CUString::COLLATION_DEFAULT,
        CULocale $oInLocale = null)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $oColl = CUString::collatorObject(true, true, $sLocale, $bfCollationFlags);
        $mArray = self::toPArray($aArray);
        $bRes = $oColl->sort($mArray);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        self::assignArrayToMap($aArray, $mArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Filters the elements in an array by calling a function or method on each element and returns a new array with
     * only those elements that were let through by the filter.
     *
     * The input array is not modified by this method.
     *
     * @param  array $aArray The array to be filtered.
     * @param  callable $fnFilter The function or method to be used for filtering. The filter should take an element as
     * a parameter and return `true` if the element should make its way into the resulting array and `false` if not.
     *
     * @return CArray The filtered array.
     */

    public static function filter ($aArray, $fnFilter)
    {
        assert( 'is_carray($aArray) && is_callable($fnFilter)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        return self::fromPArray(array_filter(self::toPArray($aArray), $fnFilter));
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
     * @param  array $aArray The array to be cleaned up.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for element comparison. If this parameter is provided, the comparator should take two parameters, which are
     * the two elements being compared, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArray The cleaned up array.
     *
     * @link   CComparator.html CComparator
     */

    public static function unique ($aArray, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($aArray) && is_callable($fnComparator)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $aResArray = self::make();
        for ($i0 = 0; $i0 < $aArray->getSize(); $i0++)
        {
            $xElement = $aArray[$i0];
            $bIsIn = false;
            for ($i1 = 0; $i1 < $i0; $i1++)
            {
                if ( call_user_func($fnComparator, $xElement, $aArray[$i1]) )
                {
                    $bIsIn = true;
                    break;
                }
            }
            if ( !$bIsIn )
            {
                self::push($aResArray, $xElement);
            }
        }
        return $aResArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sums up all the elements in an array and returns the result.
     *
     * @param  array $aArray The array to be looked into.
     *
     * @return number The sum of all the elements in the array.
     */

    public static function elementsSum ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $nSum = 0;
        for ($i = 0; $i < $aArray->getSize(); $i++)
        {
            $nSum += $aArray[$i];
        }
        return $nSum;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Multiplies all the elements in an array and returns the result.
     *
     * @param  array $aArray The array to be looked into.
     *
     * @return number The product of all the elements in the array.
     */

    public static function elementsProduct ($aArray)
    {
        assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($aArray)', vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);

        $nProduct = 1;
        for ($i = 0; $i < $aArray->getSize(); $i++)
        {
            $nProduct *= $aArray[$i];
        }
        return $nProduct;
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
     * @param  array $aArray The array to be looked into.
     * @param  array $aOfArray The reference array.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the array and the second parameter being an element
     * from the reference array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return bool `true` if the array is a subset of the reference array, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function isSubsetOf ($aArray, $aOfArray, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($aArray) && is_carray($aOfArray) && is_callable($fnComparator)',
            vs(isset($this), get_defined_vars()) );

        $aArray = splarray($aArray);
        $aOfArray = splarray($aOfArray);

        if ( self::isEmpty($aArray) && !self::isEmpty($aOfArray) )
        {
            // Special case.
            return false;
        }

        for ($i0 = 0; $i0 < $aArray->getSize(); $i0++)
        {
            $bIsIn = false;
            for ($i1 = 0; $i1 < $aOfArray->getSize(); $i1++)
            {
                if ( call_user_func($fnComparator, $aArray[$i0], $aOfArray[$i1]) )
                {
                    $bIsIn = true;
                    break;
                }
            }
            if ( !$bIsIn )
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
        $iFuncNumArgs = func_num_args();
        assert( '$iFuncNumArgs >= 2', vs(isset($this), get_defined_vars()) );

        $mResArray = CMap::make();
        $mArguments = func_get_args();
        foreach ($mArguments as $aArray)
        {
            assert( 'is_carray($aArray)', vs(isset($this), get_defined_vars()) );

            $aArray = splarray($aArray);

            $mResArray = array_merge($mResArray, self::toPArray($aArray));
        }
        return self::fromPArray($mResArray);
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
     * @param  array $aLeftArray The first array.
     * @param  array $aRightArray The second array.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the first array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArray The intersection array of the two arrays.
     *
     * @link   CComparator.html CComparator
     */

    public static function intersection ($aLeftArray, $aRightArray, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($aLeftArray) && is_carray($aRightArray) && is_callable($fnComparator)',
            vs(isset($this), get_defined_vars()) );

        $aLeftArray = splarray($aLeftArray);
        $aRightArray = splarray($aRightArray);

        $aResArray = self::make();
        for ($i0 = 0; $i0 < $aLeftArray->getSize(); $i0++)
        {
            $xElement = $aLeftArray[$i0];
            $bIsIn = false;
            for ($i1 = 0; $i1 < $aRightArray->getSize(); $i1++)
            {
                if ( call_user_func($fnComparator, $xElement, $aRightArray[$i1]) )
                {
                    $bIsIn = true;
                    break;
                }
            }
            if ( $bIsIn )
            {
                self::push($aResArray, $xElement);
            }
        }
        return $aResArray;
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
     * @param  array $aMinuendArray The minuend array.
     * @param  array $aSubtrahendArray The subtrahend array.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the minuend array and the second parameter being an
     * element from the subtrahend array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArray The difference array after subtracting the subtrahend array from the minuend array.
     *
     * @link   CComparator.html CComparator
     */

    public static function difference ($aMinuendArray, $aSubtrahendArray, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($aMinuendArray) && is_carray($aSubtrahendArray) && is_callable($fnComparator)',
            vs(isset($this), get_defined_vars()) );

        $aMinuendArray = splarray($aMinuendArray);
        $aSubtrahendArray = splarray($aSubtrahendArray);

        $aResArray = self::make();
        for ($i0 = 0; $i0 < $aMinuendArray->getSize(); $i0++)
        {
            $xElement = $aMinuendArray[$i0];
            $bIsIn = false;
            for ($i1 = 0; $i1 < $aSubtrahendArray->getSize(); $i1++)
            {
                if ( call_user_func($fnComparator, $xElement, $aSubtrahendArray[$i1]) )
                {
                    $bIsIn = true;
                    break;
                }
            }
            if ( !$bIsIn )
            {
                self::push($aResArray, $xElement);
            }
        }
        return $aResArray;
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
     * @param  array $aLeftArray The first array.
     * @param  array $aRightArray The second array.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the first array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArray The symmetric difference array between the two arrays.
     *
     * @link   CComparator.html CComparator
     */

    public static function symmetricDifference ($aLeftArray, $aRightArray, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_carray($aLeftArray) && is_carray($aRightArray) && is_callable($fnComparator)',
            vs(isset($this), get_defined_vars()) );

        $aLeftArray = splarray($aLeftArray);
        $aRightArray = splarray($aRightArray);

        $aUnionArray = self::union($aLeftArray, $aRightArray);
        $aIntersectionArray = self::intersection($aLeftArray, $aRightArray, $fnComparator);
        return self::difference($aUnionArray, $aIntersectionArray, $fnComparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Repeats an element for a specified number of times and returns the resulting array.
     *
     * For instance, an element of "a" repeated three times would result in an array of "a", "a", "a".
     *
     * @param  mixed $xElement The element to be repeated.
     * @param  int $iTimes The length of the resulting array.
     *
     * @return CArray The resulting array.
     */

    public static function repeat ($xElement, $iTimes)
    {
        assert( 'is_int($iTimes)', vs(isset($this), get_defined_vars()) );
        assert( '$iTimes > 0', vs(isset($this), get_defined_vars()) );

        $aResArray = self::make($iTimes);
        for ($i = 0; $i < $iTimes; $i++)
        {
            $aResArray[$i] = $xElement;
        }
        return $aResArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function assignArrayToArray ($aArray0, $aArray1)
    {
        $iLen = $aArray1->getSize();
        $aArray0->setSize(0);
        $aArray0->setSize($iLen);
        for ($i = 0; $i < $iLen; $i++)
        {
            $aArray0[$i] = $aArray1[$i];
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function assignArrayToMap ($aArray, $mMap)
    {
        $aArray->setSize(0);
        $aArray->setSize(count($mMap));
        $i = 0;
        foreach ($mMap as $xValue)
        {
            $aArray[$i++] = $xValue;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
