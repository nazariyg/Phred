<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class of any OOP array, which wraps over the SplFixedArray class for high-performance array functionality.
 *
 * **You can refer to this class by its alias, which is** `Ar`.
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
 * values under arbitrary keys and the soulmate of the JavaScript array is the SplFixedArray class, as well as the
 * CArrayObject class that wraps around it. Despite of the class' name, the length of an SplFixedArray is not fixed and
 * any such array can be resized, which leaves a room for guessing that "fixed" is actually referring to the PHP's
 * native array as being fixed, at last.
 *
 * Little makes the OOP array different from the JavaScript array. Same as with JavaScript arrays, the first element in
 * an OOP array is located at the position (index) of `0`, the second element at the position of `1`, and so forth.
 * Another striking resemblance would be that you can access an element in an OOP array using `[ ]` notation, putting
 * the element's position between the brackets. You would usually go over the elements in an OOP array with a `for`
 * loop, but `foreach` would work too; the SPL library, however, currently does not let the elements in an OOP array be
 * iterated by reference with `foreach` loop.
 *
 * You can create a new OOP array as an empty array to be grown later, as an array with a number of blank elements
 * ready to be assigned, or from existing values using `a` function. Something that is true for any array
 * implementation is that, if the length of an array is known beforehand, it takes less time to preallocate the
 * elements and then assign values to them as opposed to adding the elements one by one with `push` method. You can
 * make a copy of an OOP array using `clone` keyword like so:
 *
 * ```
 * $arrayCopy = clone $array;
 * ```
 */

// Method signatures:
//   __construct ($length = 0)
//   static CArrayObject makeDim2 ($lengthDim1, $lengthDim2 = 0)
//   static CArrayObject makeDim3 ($lengthDim1, $lengthDim2, $lengthDim3 = 0)
//   static CArrayObject makeDim4 ($lengthDim1, $lengthDim2, $lengthDim3, $lengthDim4 = 0)
//   static CArrayObject fromPArray ($map)
//   CMap toPArray ()
//   CUStringObject join ($binder)
//   int length ()
//   bool isEmpty ()
//   bool equals ($toArray, $comparator = CComparator::EQUALITY)
//   int compare ($toArray, $comparator = CComparator::ORDER_ASC)
//   mixed first ()
//   mixed last ()
//   mixed random ()
//   CArrayObject subar ($startPos, $length = null)
//   CArrayObject subarray ($startPos, $endPos)
//   CArrayObject slice ($startPos, $endPos)
//   bool find ($whatElement, $comparator = CComparator::EQUALITY, &$foundAtPos = null)
//   bool findScalar ($whatElement, &$foundAtPos = null)
//   bool findBinary ($whatElement, $comparator = CComparator::ORDER_ASC, &$foundAtPos = null)
//   int countElement ($element, $comparator = CComparator::EQUALITY)
//   void setLength ($newLength)
//   int push ($element)
//   mixed pop ()
//   int pushArray ($pushArray)
//   int unshift ($element)
//   mixed shift ()
//   int unshiftArray ($addArray)
//   void insert ($atPos, $insertElement)
//   void insertArray ($atPos, $insertArray)
//   void padStart ($paddingElement, $newLength)
//   void padEnd ($paddingElement, $newLength)
//   void remove ($elementPos)
//   bool removeByValue ($elementValue, $comparator = CComparator::EQUALITY)
//   void removeSubarray ($startPos, $length = null)
//   CArrayObject splice ($startPos, $length = null)
//   void removeSubarrayByRange ($startPos, $endPos)
//   void reverse ()
//   void shuffle ()
//   void sort ($comparator = CComparator::ORDER_ASC)
//   void sortOn ($onMethodName, $comparator = CComparator::ORDER_ASC/*, methodArg0, methodArg1, ...*/)
//   void sortUStrings ($collationFlags = CUStringObject::COLLATION_DEFAULT, CULocale $inLocale = null)
//   void sortUStringsCi ($collationFlags = CUStringObject::COLLATION_DEFAULT, CULocale $inLocale = null)
//   void sortUStringsNat ($collationFlags = CUStringObject::COLLATION_DEFAULT, CULocale $inLocale = null)
//   void sortUStringsNatCi ($collationFlags = CUStringObject::COLLATION_DEFAULT, CULocale $inLocale = null)
//   CArrayObject filter ($filter)
//   CArrayObject unique ($comparator = CComparator::EQUALITY)
//   number elementsSum ()
//   number elementsProduct ()
//   bool isSubsetOf ($ofArray, $comparator = CComparator::EQUALITY)
//   CArrayObject union (/*addendArray0, addendArray1, ...*/)
//   CArrayObject intersection ($withArray, $comparator = CComparator::EQUALITY)
//   CArrayObject difference ($subtrahendArray, $comparator = CComparator::EQUALITY)
//   CArrayObject symmetricDifference ($fromArray, $comparator = CComparator::EQUALITY)
//   static CArrayObject repeat ($element, $times)

class CArrayObject extends CRootClass implements IEqualityAndOrder, ArrayAccess, IteratorAggregate, Countable
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an OOP array as either empty or having a specified length.
     *
     * @param  int $length **OPTIONAL. Default is** `0`. The length of the array. Don't forget to give values to all
     * of the allocated elements when using this parameter.
     */

    public function __construct ($length = 0, $_noinit = false)
    {
        if ( $_noinit )
        {
            return;
        }

        $this->m_splArray = CArray::make($length);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a two-dimensional OOP array.
     *
     * @param  int $lengthDim1 The length of the array at the first level.
     * @param  int $lengthDim2 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the second level.
     *
     * @return CArrayObject The new array.
     */

    public static function makeDim2 ($lengthDim1, $lengthDim2 = 0)
    {
        return to_oop(CArray::makeDim2($lengthDim1, $lengthDim2));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a three-dimensional OOP array.
     *
     * @param  int $lengthDim1 The length of the array at the first level.
     * @param  int $lengthDim2 The length(s) of the array(s) at the second level.
     * @param  int $lengthDim3 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the third level.
     *
     * @return CArrayObject The new array.
     */

    public static function makeDim3 ($lengthDim1, $lengthDim2, $lengthDim3 = 0)
    {
        return to_oop(CArray::makeDim3($lengthDim1, $lengthDim2, $lengthDim3));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a four-dimensional OOP array.
     *
     * @param  int $lengthDim1 The length of the array at the first level.
     * @param  int $lengthDim2 The length(s) of the array(s) at the second level.
     * @param  int $lengthDim3 The length(s) of the array(s) at the third level.
     * @param  int $lengthDim4 **OPTIONAL. Default is** `0`. The length(s) of the array(s) at the fourth level.
     *
     * @return CArrayObject The new array.
     */

    public static function makeDim4 ($lengthDim1, $lengthDim2, $lengthDim3, $lengthDim4 = 0)
    {
        return to_oop(CArray::makeDim4($lengthDim1, $lengthDim2, $lengthDim3, $lengthDim4));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a PHP's associative array or an OOP map into an OOP array.
     *
     * @param  map $map The PHP's associative array or the OOP map to be converted.
     *
     * @return CArrayObject The resulting array.
     */

    public static function fromPArray ($map)
    {
        return self::fromSplArray(CArray::fromPArray($map));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an array into a PHP's associative array.
     *
     * The first element from the array is put into the resulting associative array under the integer key of `0`, the
     * second element under `1`, and so forth.
     *
     * @return CMap The resulting associative array.
     */

    public function toPArray ()
    {
        return $this->m_splArray->toArray();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Joins the elements of an array into an OOP string.
     *
     * The elements in the array are not required to be all strings and, in addition to types that know how to become a
     * string, an element can be `int`, `float`, or `bool`. In the resulting string, a boolean value of `true` becomes
     * "1" and `false` becomes "0".
     *
     * As a special case, the array is allowed to be empty.
     *
     * @param  string $binder The string to be put between any two elements in the resulting string, such as ", ".
     * Can be empty.
     *
     * @return CUStringObject The resulting string.
     */

    public function join ($binder)
    {
        return CArray::join($this->m_splArray, $binder);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many elements there are in an array.
     *
     * @return int The length of the array.
     */

    public function length ()
    {
        return $this->m_splArray->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array is empty.
     *
     * @return bool `true` if the array is empty, `false` otherwise.
     */

    public function isEmpty ()
    {
        return ( $this->m_splArray->getSize() == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array is equal to another array.
     *
     * For any two arrays to be equal, they need to have the same number of the same elements arranged in the same
     * order.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See
     * the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $toArray The second array for comparison.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from *this* array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return bool `true` if the two arrays are equal, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public function equals ($toArray, $comparator = CComparator::EQUALITY)
    {
        return CArray::equals($this->m_splArray, $toArray, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two arrays should appear in a place where it matters.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool` in the ascending
     * order or in the descending order if you use `CComparator::ORDER_DESC`. And the default comparator is smart
     * enough to know how to compare objects of those classes that conform to the IEqualityAndOrder interface (static
     * or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See the [CComparator](CComparator.html)
     * class for more on this.
     *
     * @param  array $toArray The second array for comparison.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from *this* array and the second parameter being an
     * element from the second array, and return `-1` if the element from *this* array would need to go before the
     * element from the second array if the two were being ordered in separate, `1` if the other way around, and `0` if
     * the two elements are equal.
     *
     * @return int A negative value (typically `-1`) if *this* array should go before the second array, a positive
     * value (typically `1`) if the other way around, and `0` if the two arrays are equal.
     *
     * @link   CComparator.html CComparator
     */

    public function compare ($toArray, $comparator = CComparator::ORDER_ASC)
    {
        return CArray::compare($this->m_splArray, $toArray, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the first element from an array.
     *
     * @return mixed The first element in the array.
     */

    public function first ()
    {
        return CArray::first($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the last element from an array.
     *
     * @return mixed The last element in the array.
     */

    public function last ()
    {
        return CArray::last($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a randomly picked element from an array.
     *
     * @return mixed A randomly picked element in the array.
     */

    public function random ()
    {
        return CArray::random($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a sequence of elements from an array, as another array.
     *
     * This method works much like `substr` method of the CUStringObject class.
     *
     * The method allows for an empty array to be returned.
     *
     * @param  int $startPos The position of the first element in the sequence to be copied.
     * @param  int $length **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be copied.
     *
     * @return CArrayObject The array containing the copied elements.
     */

    public function subar ($startPos, $length = null)
    {
        return self::fromSplArray(CArray::subar($this->m_splArray, $startPos, $length));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a sequence of elements from an array, as another array, with both starting and ending positions
     * specified.
     *
     * This method works much like `substring` method of the CUStringObject class.
     *
     * The method allows for an empty array to be returned.
     *
     * @param  int $startPos The position of the first element in the sequence to be copied.
     * @param  int $endPos The position of the element that *follows* the last element in the sequence to be copied.
     *
     * @return CArrayObject The array containing the copied elements.
     */

    public function subarray ($startPos, $endPos)
    {
        return self::fromSplArray(CArray::subarray($this->m_splArray, $startPos, $endPos));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * An alias for the previous method.
     *
     * Returns a sequence of elements from an array, as another array, with both starting and ending positions
     * specified.
     *
     * This method works much like `substring` method of the CUStringObject class.
     *
     * The method allows for an empty array to be returned.
     *
     * @param  int $startPos The position of the first element in the sequence to be copied.
     * @param  int $endPos The position of the element that *follows* the last element in the sequence to be copied.
     *
     * @return CArrayObject The array containing the copied elements.
     */

    public function slice ($startPos, $endPos)
    {
        return self::fromSplArray(CArray::slice($this->m_splArray, $startPos, $endPos));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array contains an element with a specified value, using linear search.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See the
     * [CComparator](CComparator.html) class for more on this.
     *
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

    public function find ($whatElement, $comparator = CComparator::EQUALITY, &$foundAtPos = null)
    {
        return CArray::find($this->m_splArray, $whatElement, $comparator, $foundAtPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array contains an element with a specified scalar value, using linear search.
     *
     * If an array only contains values of scalar types i.e. `int`, `float`, `bool`, `string` (ASCII only), or `null`,
     * this method allows for faster searches compared to `find` method. In case of `string` type, the search is
     * case-sensitive.
     *
     * The `find` method would be of better service in searching for Unicode strings (the default comparator used by
     * `find` method is Unicode-aware) and it was made flexible for you to be able to set your own comparison rules,
     * such as making the search case-insensitive for any kind of strings.
     *
     * @param  mixed $whatElement The value of the searched element.
     * @param  reference $foundAtPos **OPTIONAL. OUTPUT.** If an element has been found after the method was called
     * with this parameter provided, the parameter's value, which is of type `int`, indicates the position of the first
     * found element, i.e. the element at the leftmost position if the array contains more than one of such elements.
     *
     * @return bool `true` if such element was found in the array, `false` otherwise.
     *
     * @link   #method_find find
     */

    public function findScalar ($whatElement, &$foundAtPos = null)
    {
        return CArray::findScalar($this->m_splArray, $whatElement, $foundAtPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array contains an element with a specified value, using faster binary search.
     *
     * Binary search is much faster than linear search inside an array that was preliminary sorted. So using binary
     * search makes sense when many searches need to be run over the same large array. When looking for elements with
     * this method, you should be using the same comparator that was used for sorting the array (typically
     * `CComparator::ORDER_ASC`).
     *
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

    public function findBinary ($whatElement, $comparator = CComparator::ORDER_ASC, &$foundAtPos = null)
    {
        return CArray::findBinary($this->m_splArray, $whatElement, $comparator, $foundAtPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many elements with a specified value there are in an array.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See the
     * [CComparator](CComparator.html) class for more on this.
     *
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

    public function countElement ($element, $comparator = CComparator::EQUALITY)
    {
        return CArray::countElement($this->m_splArray, $element, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Resizes an array to a new length.
     *
     * No elements are lost if the array grows in size and all elements that are allowed by the new length are kept if
     * the array shrinks.
     *
     * @param  int $newLength The new length.
     *
     * @return void
     */

    public function setLength ($newLength)
    {
        CArray::setLength($this->m_splArray, $newLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an element to the end of an array.
     *
     * @param  mixed $element The element to be added.
     *
     * @return int The array's new length.
     */

    public function push ($element)
    {
        return CArray::push($this->m_splArray, $element);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from the end of an array.
     *
     * @return mixed The removed element.
     */

    public function pop ()
    {
        return CArray::pop($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the elements of an array to the end of another array.
     *
     * It is *this* array that grows with the operation.
     *
     * @param  array $pushArray The array containing the elements to be added.
     *
     * @return int The array's new length.
     */

    public function pushArray ($pushArray)
    {
        return CArray::pushArray($this->m_splArray, $pushArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an element to the start of an array.
     *
     * @param  mixed $element The element to be added.
     *
     * @return int The array's new length.
     */

    public function unshift ($element)
    {
        return CArray::unshift($this->m_splArray, $element);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from the start of an array.
     *
     * @return mixed The removed element.
     */

    public function shift ()
    {
        return CArray::shift($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds the elements of an array to the start of another array.
     *
     * It is *this* array that grows with the operation.
     *
     * @param  array $addArray The array containing the elements to be added.
     *
     * @return int The array's new length.
     */

    public function unshiftArray ($addArray)
    {
        return CArray::unshiftArray($this->m_splArray, $addArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts an element into an array.
     *
     * As a special case, the position of insertion can be equal to the array's length, which would make this method
     * work like `push` method.
     *
     * @param  int $atPos The position at which the new element is to be inserted.
     * @param  mixed $insertElement The new element.
     *
     * @return void
     */

    public function insert ($atPos, $insertElement)
    {
        CArray::insert($this->m_splArray, $atPos, $insertElement);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts the elements of an array into another array.
     *
     * As a special case, the position of insertion can be equal to the array's length, which would make this method
     * work like `pushArray` method.
     *
     * @param  int $atPos The position at which the new elements are to be inserted.
     * @param  array $insertArray The array containing the elements to be inserted.
     *
     * @return void
     */

    public function insertArray ($atPos, $insertArray)
    {
        CArray::insertArray($this->m_splArray, $atPos, $insertArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds elements to the start of an array to make it grow to a specified length.
     *
     * @param  mixed $paddingElement The element to be used for padding.
     * @param  int $newLength The array's new length.
     *
     * @return void
     */

    public function padStart ($paddingElement, $newLength)
    {
        CArray::padStart($this->m_splArray, $paddingElement, $newLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds elements to the end of an array to make it grow to a specified length.
     *
     * @param  mixed $paddingElement The element to be used for padding.
     * @param  int $newLength The array's new length.
     *
     * @return void
     */

    public function padEnd ($paddingElement, $newLength)
    {
        CArray::padEnd($this->m_splArray, $paddingElement, $newLength);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an element from an array.
     *
     * @param  int $elementPos The position of the element to be removed.
     *
     * @return void
     */

    public function remove ($elementPos)
    {
        CArray::remove($this->m_splArray, $elementPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From an array, removes all elements that have a specified value.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See the
     * [CComparator](CComparator.html) class for more on this.
     *
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

    public function removeByValue ($elementValue, $comparator = CComparator::EQUALITY)
    {
        return CArray::removeByValue($this->m_splArray, $elementValue, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  int $startPos The position of the first element in the sequence to be removed.
     * @param  int $length **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be removed.
     *
     * @return void
     */

    public function removeSubarray ($startPos, $length = null)
    {
        CArray::removeSubarray($this->m_splArray, $startPos, $length);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array and returns the removed elements, as another array.
     *
     * Use `removeSubarray` method if you don't care about what happens to the elements after they are removed.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  int $startPos The position of the first element in the sequence to be removed.
     * @param  int $length **OPTIONAL. Default is** *as many elements as the starting element is followed by*. The
     * number of elements in the sequence to be removed.
     *
     * @return CArrayObject The array containing the removed elements.
     *
     * @link   #method_removeSubarray removeSubarray
     */

    public function splice ($startPos, $length = null)
    {
        return self::fromSplArray(CArray::splice($this->m_splArray, $startPos, $length));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a sequence of elements from an array, with both starting and ending positions specified.
     *
     * The method allows for the targeted sequence to be empty.
     *
     * @param  int $startPos The position of the first element in the sequence to be removed.
     * @param  int $endPos The position of the element that *follows* the last element in the sequence to be removed.
     *
     * @return void
     */

    public function removeSubarrayByRange ($startPos, $endPos)
    {
        CArray::removeSubarrayByRange($this->m_splArray, $startPos, $endPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Reverses the order of the elements in an array.
     *
     * @return void
     */

    public function reverse ()
    {
        CArray::reverse($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Randomizes the positions of the elements in an array.
     *
     * @return void
     */

    public function shuffle ()
    {
        CArray::shuffle($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array.
     *
     * You can use your own comparator for element comparison, but the default comparators, such as
     * `CComparator::ORDER_ASC` and `CComparator::ORDER_DESC`, have got you covered when sorting scalar values, such as
     * `string`, `int`, `float`, and `bool` in the ascending or descending order respectively. And the default
     * comparators are smart enough to know how to compare objects of those classes that conform to the
     * IEqualityAndOrder interface (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See
     * the [CComparator](CComparator.html) class for more on this.
     *
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, which are the two elements being compared, and return `-1` if the first element needs to go before
     * the second element in the sorted array, `1` if the other way around, and `0` if the two elements are equal.
     *
     * @return void
     *
     * @link   CComparator.html CComparator
     */

    public function sort ($comparator = CComparator::ORDER_ASC)
    {
        CArray::sort($this->m_splArray, $comparator);
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
     * conform to the IEqualityAndOrder interface (static or not), including CUStringObject, CArrayObject, CMapObject,
     * CTime etc. See the [CComparator](CComparator.html) class for more on this.
     *
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

    public function sortOn ($onMethodName, $comparator = CComparator::ORDER_ASC/*, methodArg0, methodArg1, ...*/)
    {
        $arguments = func_get_args();
        array_unshift($arguments, $this->m_splArray);
        call_user_func_array("CArray::sortOn", $arguments);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of Unicode or ASCII strings, in the ascending order, case-sensitively.
     *
     * For an array that only contains strings, this method is usually preferred over more omnivorous `sort` method.
     *
     * **NOTE.** This method puts lowercase in front of uppercase on per-character basis.
     *
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `CUStringObject::COLLATION_DEFAULT`. The Unicode
     * collation option(s) to be used for string comparison. See the [CUStringObject](CUStringObject.html) class for
     * information on collation options.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUStringObject.html CUStringObject
     * @link   #method_sort sort
     */

    public function sortUStrings ($collationFlags = CUStringObject::COLLATION_DEFAULT, CULocale $inLocale = null)
    {
        CArray::sortUStrings($this->m_splArray, $collationFlags, $inLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sorts the elements in an array of Unicode or ASCII strings, in the ascending order, case-insensitively.
     *
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `CUStringObject::COLLATION_DEFAULT`. The Unicode
     * collation option(s) to be used for string comparison. See the [CUStringObject](CUStringObject.html) class for
     * information on collation options.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUStringObject.html CUStringObject
     */

    public function sortUStringsCi ($collationFlags = CUStringObject::COLLATION_DEFAULT, CULocale $inLocale = null)
    {
        CArray::sortUStringsCi($this->m_splArray, $collationFlags, $inLocale);
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
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `CUStringObject::COLLATION_DEFAULT`. The Unicode
     * collation option(s) to be used for string comparison. See the [CUStringObject](CUStringObject.html) class for
     * information on collation options.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUStringObject.html CUStringObject
     */

    public function sortUStringsNat ($collationFlags = CUStringObject::COLLATION_DEFAULT, CULocale $inLocale = null)
    {
        CArray::sortUStringsNat($this->m_splArray, $collationFlags, $inLocale);
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
     * @param  bitfield $collationFlags **OPTIONAL. Default is** `CUStringObject::COLLATION_DEFAULT`. The Unicode
     * collation option(s) to be used for string comparison. See the [CUStringObject](CUStringObject.html) class for
     * information on collation options.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which
     * strings are to be compared with each other.
     *
     * @return void
     *
     * @link   CUStringObject.html CUStringObject
     */

    public function sortUStringsNatCi ($collationFlags = CUStringObject::COLLATION_DEFAULT,
        CULocale $inLocale = null)
    {
        CArray::sortUStringsNatCi($this->m_splArray, $collationFlags, $inLocale);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Filters the elements in an array by calling a function or method on each element and returns a new array with
     * only those elements that were let through by the filter.
     *
     * The array is not modified by this method.
     *
     * @param  callable $filter The function or method to be used for filtering. The filter should take an element as
     * a parameter and return `true` if the element should make its way into the resulting array and `false` if not.
     *
     * @return CArrayObject The filtered array.
     */

    public function filter ($filter)
    {
        return self::fromSplArray(CArray::filter($this->m_splArray, $filter));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes repeating elements from an array so that only unique elements are kept and returns the new array.
     *
     * The array is not modified by this method.
     *
     * You can use your own comparator for element comparison, but the default comparator has got you covered when
     * comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart
     * enough to know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder
     * interface (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See the
     * [CComparator](CComparator.html) class for more on this.
     *
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for element comparison. If this parameter is provided, the comparator should take two parameters, which are
     * the two elements being compared, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArrayObject The cleaned up array.
     *
     * @link   CComparator.html CComparator
     */

    public function unique ($comparator = CComparator::EQUALITY)
    {
        return self::fromSplArray(CArray::unique($this->m_splArray, $comparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sums up all the elements in an array and returns the result.
     *
     * @return number The sum of all the elements in the array.
     */

    public function elementsSum ()
    {
        return CArray::elementsSum($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Multiplies all the elements in an array and returns the result.
     *
     * @return number The product of all the elements in the array.
     */

    public function elementsProduct ()
    {
        return CArray::elementsProduct($this->m_splArray);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an array is a subset of another array.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See
     * the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $ofArray The reference array.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from *this* array and the second parameter being an element
     * from the reference array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return bool `true` if *this* array is a subset of the reference array, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public function isSubsetOf ($ofArray, $comparator = CComparator::EQUALITY)
    {
        return CArray::isSubsetOf($this->m_splArray, $ofArray, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Unites an array with one or more other arrays and returns the new array.
     *
     * The arrays that need to be united with *this* array are passed as arguments to this method. The order in which
     * the arrays are passed determines the order of elements in the resulting array, with the elements of *this* array
     * going in front of the elements of other arrays.
     *
     * None of the source arrays is modified by this method.
     *
     * You can use `pushArray` method if you want *this* array to get modified by the operation.
     *
     * @return CArrayObject The union array.
     *
     * @link   #method_pushArray pushArray
     */

    public function union (/*addendArray0, addendArray1, ...*/)
    {
        $arguments = func_get_args();
        array_unshift($arguments, $this->m_splArray);
        return self::fromSplArray(call_user_func_array("CArray::union", $arguments));
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
     * IEqualityAndOrder interface (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See
     * the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $withArray The second array.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from *this* array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArrayObject The intersection array of the two arrays.
     *
     * @link   CComparator.html CComparator
     */

    public function intersection ($withArray, $comparator = CComparator::EQUALITY)
    {
        return self::fromSplArray(CArray::intersection($this->m_splArray, $withArray, $comparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From an array, removes only those elements that are present in another array and returns the new array.
     *
     * In the operation, *this* array is the minuend and the parameter array is the subtrahend.
     *
     * None of the source arrays is modified by this method.
     *
     * You can use your own comparator for the comparison of the elements in the arrays, but the default comparator has
     * got you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See
     * the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $subtrahendArray The subtrahend array.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from the minuend array and the second parameter being an
     * element from the subtrahend array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArrayObject The difference array after subtracting the subtrahend array from the minuend array.
     *
     * @link   CComparator.html CComparator
     */

    public function difference ($subtrahendArray, $comparator = CComparator::EQUALITY)
    {
        return self::fromSplArray(CArray::difference($this->m_splArray, $subtrahendArray, $comparator));
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
     * IEqualityAndOrder interface (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See
     * the [CComparator](CComparator.html) class for more on this.
     *
     * @param  array $fromArray The second array.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two elements. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being an element from *this* array and the second parameter being an
     * element from the second array, and return `true` if the two elements are equal and `false` otherwise.
     *
     * @return CArrayObject The symmetric difference array between the two arrays.
     *
     * @link   CComparator.html CComparator
     */

    public function symmetricDifference ($fromArray, $comparator = CComparator::EQUALITY)
    {
        return self::fromSplArray(CArray::symmetricDifference($this->m_splArray, $fromArray, $comparator));
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
     * @return CArrayObject The resulting array.
     */

    public static function repeat ($element, $times)
    {
        return self::fromSplArray(CArray::repeat($element, $times));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function fromArguments ($arguments)
    {
        $array = new self(0, true);

        if ( empty($arguments) )
        {
            $array->m_splArray = new SplFixedArray();
        }
        else
        {
            $array->m_splArray = SplFixedArray::fromArray($arguments, false);
        }

        return $array;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function fromSplArray ($splArray)
    {
        assert( '$splArray instanceof SplFixedArray', vs(isset($this), get_defined_vars()) );

        $array = new self(0, true);
        $array->m_splArray = $splArray;
        return $array;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function &toSplArray ()
    {
        return $this->m_splArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function offsetExists ($offset)
    {
        assert( 'is_int($offset)', vs(isset($this), get_defined_vars()) );
        return $this->m_splArray->offsetExists($offset);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function offsetGet ($offset)
    {
        return $this->m_splArray->offsetGet($offset);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function offsetSet ($offset, $element)
    {
        $this->m_splArray->offsetSet($offset, $element);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function offsetUnset ($offset)
    {
        assert( 'false', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function getIterator ()
    {
        return $this->m_splArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function count ()
    {
        return $this->m_splArray->getSize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __clone ()
    {
        $this->m_splArray = clone $this->m_splArray;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    // public function __debugInfo ()
    // {
    //     return $this->m_splArray->toArray();
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_splArray;
}
