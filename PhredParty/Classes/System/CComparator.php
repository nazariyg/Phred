<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The home of the static methods that serve as the default comparators to be used by methods that depend on comparing
 * values of unspecified types.
 *
 * **You can refer to this class by its alias, which is** `Cmpr`.
 *
 * To complete their missions, some of the methods in Phred need to compare values of types that are unknown to them.
 * The examples would be the methods for sorting an array and finding a value in a map. Even though there exist ways of
 * detecting the type of the elements in an array, the question of how the values of the elements would be affecting
 * the resulting order would still remain. For instance, if an array contained elements of `int` type, one of the
 * simplest types there are, it would enable for at least two ways of sorting the array, in the ascending or descending
 * order. So along with the role of type handler, a comparator that is passed as an argument to a method can also play
 * the role of an insertion point for custom behavior.
 *
 * The comparators that were made available by this class are the comparator for equality, which is referred to by
 * `EQUALITY` constant of the class, the comparator for the ascending order, which is `ORDER_ASC`, and the comparator
 * for the descending order, which is `ORDER_DESC`. All of them can handle any type that is a scalar type i.e. `int`,
 * `float`, `bool`, or `string`, `null` type, SplFixedArray class or PHP's associative array, or a class that
 * conforms to one of the comparison interfaces, namely [IEquality](IEquality.html) and
 * [IEqualityAndOrder](IEqualityAndOrder.html), such as `CUStringObject`, `CArrayObject`, or `CMapObject`. The
 * difference between the interfaces is that objects of the IEquality interface can only be compared for equality with
 * one another, while objects of the IEqualityAndOrder interface can also be compared for order.
 *
 * You can let objects of your own class be compared by one or more of the default comparators via implementing one of
 * the comparison interfaces for the class. Implementing the IEqualityAndOrder interface would only be needed if it
 * would make sense to arrange objects of your class in the ascending or descending order.
 *
 * The classes that currently implement the **IEqualityAndOrder** interface are:
 *
 * * CUStringObject
 * * CArrayObject
 * * CMapObject
 * * CTime
 *
 * And the classes that currently implement the **IEquality** interface are:
 *
 * * CTimeZone
 * * CULocale
 * * CUrl
 * * CFile
 */

// Method signatures:
//   static bool equality ($xValue0, $xValue1)
//   static int orderAsc ($xValue0, $xValue1)
//   static int orderDesc ($xValue0, $xValue1)

class CComparator extends CRootClass
{
    /**
     * `callable` The callable id of the comparator for equality (`equality` method of the class).
     *
     * @var  callable
     *
     * @link #method_equality equality
     */
    const EQUALITY = "CComparator::equality";

    /**
     * `callable` The callable id of the comparator for the ascending order (`orderAsc` method of the class).
     *
     * @var  callable
     *
     * @link #method_orderAsc orderAsc
     */
    const ORDER_ASC = "CComparator::orderAsc";

    /**
     * `callable` The callable id of the comparator for the descending order (`orderDesc` method of the class).
     *
     * @var  callable
     *
     * @link #method_orderDesc orderDesc
     */
    const ORDER_DESC = "CComparator::orderDesc";

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two values are equal.
     *
     * If the values are objects of your custom class, the class should conform to the [IEquality](IEquality.html)
     * interface or to the [IEqualityAndOrder](IEqualityAndOrder.html) interface.
     *
     * @param  mixed $xValue0 The first value for comparison.
     * @param  mixed $xValue1 The second value for comparison.
     *
     * @return bool `true` if the two values are equal, `false` otherwise.
     *
     * @link   IEquality.html IEquality
     * @link   IEqualityAndOrder.html IEqualityAndOrder
     */

    public static function equality ($xValue0, $xValue1)
    {
        if ( CDebug::isDebugModeOn() )
        {
            if ( !((is_cstring($xValue0) && is_cstring($xValue1)) ||
                   (is_carray($xValue0) && is_carray($xValue1)) ||
                   (is_cmap($xValue0) && is_cmap($xValue1))) )
            {
                // With the above exceptions, the two values should be both either scalars or objects of the same
                // class.
                assert( 'is_object($xValue0) == is_object($xValue1)', vs(isset($this), get_defined_vars()) );
                assert( '!is_object($xValue0) || CString::equals(get_class($xValue0), get_class($xValue1))',
                    vs(isset($this), get_defined_vars()) );
            }
        }

        $sClassName;
        if ( !phred_classify_duo($xValue0, $xValue1, $sClassName) )
        {
            // Compare the values as scalars.
            assert( '(is_scalar($xValue0) || is_null($xValue0)) && (is_scalar($xValue1) || is_null($xValue1))',
                vs(isset($this), get_defined_vars()) );
            return ( $xValue0 === $xValue1 );
        }
        else
        {
            // Compare the values as objects that may conform to one of the comparison interfaces.
            $oReflClass = new ReflectionClass($sClassName);
            if ( $oReflClass->implementsInterface("IEqualityStatic") )
            {
                $bRes = call_user_func([$sClassName, "equals"], $xValue0, $xValue1);
                assert( 'is_bool($bRes)', vs(isset($this), get_defined_vars()) );
                return $bRes;
            }
            if ( $oReflClass->implementsInterface("IEquality") )
            {
                $bRes = call_user_func([$xValue0, "equals"], $xValue1);
                assert( 'is_bool($bRes)', vs(isset($this), get_defined_vars()) );
                return $bRes;
            }
            if ( $oReflClass->implementsInterface("IEqualityAndOrderStatic") )
            {
                $iRes = call_user_func([$sClassName, "compare"], $xValue0, $xValue1);
                assert( 'is_int($iRes)', vs(isset($this), get_defined_vars()) );
                return ( $iRes == 0 );
            }
            if ( $oReflClass->implementsInterface("IEqualityAndOrder") )
            {
                $iRes = call_user_func([$xValue0, "compare"], $xValue1);
                assert( 'is_int($iRes)', vs(isset($this), get_defined_vars()) );
                return ( $iRes == 0 );
            }

            // The class of the objects being compared does not implement any applicable comparison interfaces.
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two values should appear in a place where it matters, assuming the ascending
     * order.
     *
     * If the values are objects of your custom class, the class should conform to the
     * [IEqualityAndOrder](IEqualityAndOrder.html) interface.
     *
     * @param  mixed $xValue0 The first value for comparison.
     * @param  mixed $xValue1 The second value for comparison.
     *
     * @return int A negative value (typically `-1`) if the first value should go before the second value, a positive
     * value (typically `1`) if the other way around, and `0` if the two values are equal.
     *
     * @link   IEqualityAndOrder.html IEqualityAndOrder
     */

    public static function orderAsc ($xValue0, $xValue1)
    {
        if ( CDebug::isDebugModeOn() )
        {
            if ( !((is_cstring($xValue0) && is_cstring($xValue1)) ||
                   (is_carray($xValue0) && is_carray($xValue1)) ||
                   (is_cmap($xValue0) && is_cmap($xValue1))) )
            {
                // With the above exceptions, the two values should be both either scalars or objects of the same
                // class.
                assert( 'is_object($xValue0) == is_object($xValue1)', vs(isset($this), get_defined_vars()) );
                assert( '!is_object($xValue0) || CString::equals(get_class($xValue0), get_class($xValue1))',
                    vs(isset($this), get_defined_vars()) );
            }
        }

        $sClassName;
        if ( !phred_classify_duo($xValue0, $xValue1, $sClassName) )
        {
            // Compare the values as scalars.
            assert( '(is_scalar($xValue0) || is_null($xValue0)) && (is_scalar($xValue1) || is_null($xValue1))',
                vs(isset($this), get_defined_vars()) );
            return ( $xValue0 === $xValue1 ) ? 0 : (( $xValue0 < $xValue1 ) ? -1 : 1);
        }
        else
        {
            // Compare the values as objects that may conform to one of the comparison interfaces.
            $oReflClass = new ReflectionClass($sClassName);
            if ( $oReflClass->implementsInterface("IEqualityAndOrderStatic") )
            {
                $iRes = call_user_func([$sClassName, "compare"], $xValue0, $xValue1);
                assert( 'is_int($iRes)', vs(isset($this), get_defined_vars()) );
                return $iRes;
            }
            if ( $oReflClass->implementsInterface("IEqualityAndOrder") )
            {
                $iRes = call_user_func([$xValue0, "compare"], $xValue1);
                assert( 'is_int($iRes)', vs(isset($this), get_defined_vars()) );
                return $iRes;
            }

            // The class of the objects being compared does not implement any applicable comparison interfaces.
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two values should appear in a place where it matters, assuming the descending
     * order.
     *
     * If the values are objects of your custom class, the class should conform to the
     * [IEqualityAndOrder](IEqualityAndOrder.html) interface.
     *
     * @param  mixed $xValue0 The first value for comparison.
     * @param  mixed $xValue1 The second value for comparison.
     *
     * @return int A positive value (typically `1`) if the first value should go before the second value, a negative
     * value (typically `-1`) if the other way around, and `0` if the two values are equal.
     *
     * @link   IEqualityAndOrder.html IEqualityAndOrder
     */

    public static function orderDesc ($xValue0, $xValue1)
    {
        return -self::orderAsc($xValue0, $xValue1);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
