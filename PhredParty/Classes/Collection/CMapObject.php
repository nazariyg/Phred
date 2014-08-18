<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class of any OOP map, which wraps over the PHP's associative array.
 *
 * **You can refer to this class by its alias, which is** `Ma`.
 *
 * What a map does is that it contains arbitrary values associated with keys by which those values can be found. The
 * keys are unique within a map. This is effectively the same what objects do in JavaScript.
 *
 * As imposed by PHP, the type of a key in an associative array can be either `string` or `int`. When a string that is
 * going to be used as a key looks like an integer, it's actually converted into the corresponding integer and used as
 * such to access the value (except if the string begins with one or more "0"). A value of type `float`, `bool`, or
 * `null` is converted into an integer too when trying to use it for a key. A key cannot be an empty string in a PHP's
 * associative array, and any empty string becomes "_" from the map's point of view when trying to use it as a key.
 *
 * Similar to regular arrays, you can access a value in an OOP map or insert a new key-value pair with `[ ]` notation,
 * putting the key with which the value is associated between the brackets. With `valueByPath` and `setValueByPath`
 * methods, you can also access a value in a multi-dimensional (or one-dimensional) map by its key path, which is a
 * string hierarchically indicating the keys following which the value can be found, chained together with "."
 * separator, as in "level1key.level2key". The `merge` method is proud of being able to merge maps in the *correct*
 * way. You can make a copy of an OOP map using `clone` keyword like so:
 *
 * ```
 * $mapCopy = clone $map;
 * ```
 */

// Method signatures:
//   __construct ()
//   int length ()
//   bool isEmpty ()
//   bool equals ($toMap, $comparator = CComparator::EQUALITY)
//   int compare ($toMap, $comparator = CComparator::ORDER_ASC)
//   bool hasKey ($key)
//   bool hasPath ($path)
//   mixed valueByPath ($path)
//   void setValueByPath ($path, $value)
//   bool find ($whatValue, $comparator = CComparator::EQUALITY, &$foundUnderKey = null)
//   bool findScalar ($whatValue, &$foundUnderKey = null)
//   int countValue ($value, $comparator = CComparator::EQUALITY)
//   void remove ($key)
//   CMapObject filter ($filter)
//   CArrayObject keys ()
//   CArrayObject values ()
//   CMapObject merge (/*withMap0, withMap1, ...*/)
//   bool areKeysSequential ()
//   void insertValue ($value)

class CMapObject extends CRootClass implements IEqualityAndOrder, ArrayAccess, IteratorAggregate, Countable
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an empty OOP map.
     */

    public function __construct ($_noinit = false)
    {
        if ( $_noinit )
        {
            return;
        }

        $this->m_map = [];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many key-value pairs there are in a map.
     *
     * @return int The length of the map.
     */

    public function length ()
    {
        return count($this->m_map);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map is empty.
     *
     * @return bool `true` if the map is empty, `false` otherwise.
     */

    public function isEmpty ()
    {
        return ( count($this->m_map) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map is equal to another map.
     *
     * For any two maps to be equal, they need to have the same key-value pairs.
     *
     * You can use your own comparator for the comparison of the values in the maps, but the default comparator has got
     * you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See
     * the [CComparator](CComparator.html) class for more on this.
     *
     * @param  map $toMap The second map for comparison.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two values. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being a value from *this* map and the second parameter being a value from
     * the second map, and return `true` if the two values are equal and `false` otherwise.
     *
     * @return bool `true` if the two maps are equal, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public function equals ($toMap, $comparator = CComparator::EQUALITY)
    {
        return CMap::equals($this->m_map, $toMap, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two maps should appear in a place where it matters.
     *
     * You can use your own comparator for the comparison of the values in the maps, but the default comparator has got
     * you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool` in the ascending order or
     * in the descending order if you use `CComparator::ORDER_DESC`. And the default comparator is smart enough to know
     * how to compare objects of those classes that conform to the IEqualityAndOrder interface (static or not),
     * including CUStringObject, CArrayObject, CMapObject, CTime etc. See the [CComparator](CComparator.html) class for
     * more on this.
     *
     * @param  map $toMap The second map for comparison.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two values. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being a value from *this* map and the second parameter being a value from
     * the second map, and return `-1` if the value from *this* map would need to go before the value from the second
     * map if the two were being ordered in separate, `1` if the other way around, and `0` if the two values are equal.
     *
     * @return int A negative value (typically `-1`) if *this* map should go before the second map, a positive value
     * (typically `1`) if the other way around, and `0` if the two maps are equal.
     *
     * @link   CComparator.html CComparator
     */

    public function compare ($toMap, $comparator = CComparator::ORDER_ASC)
    {
        return CMap::compare($this->m_map, $toMap, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map contains a value under a specified key.
     *
     * @param  mixed $key The key to be looked for.
     *
     * @return bool `true` if the map contains a value under such key, `false` otherwise.
     */

    public function hasKey ($key)
    {
        return array_key_exists($key, $this->m_map);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map contains a value by a specified key path.
     *
     * @param  string $path The key path to be looked by, e.g. "level1key.level2key".
     *
     * @return bool `true` if the map contains a value by such key path, `false` otherwise.
     */

    public function hasPath ($path)
    {
        return CMap::hasPath($this->m_map, $path);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a map, returns the value found by a specified key path.
     *
     * @param  string $path The key path by which the value is to be found, e.g. "level1key.level2key".
     *
     * @return mixed The value found by the key path.
     */

    public function valueByPath ($path)
    {
        return CMap::valueByPath($this->m_map, $path);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a map, modifies the value found by a specified key path.
     *
     * @param  string $path The key path by which the value is to be found, e.g. "level1key.level2key".
     * @param  mixed $value The new value.
     *
     * @return void
     */

    public function setValueByPath ($path, $value)
    {
        CMap::setValueByPath($this->m_map, $path, $value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map contains a specified value.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See the
     * [CComparator](CComparator.html) class for more on this.
     *
     * @param  mixed $whatValue The searched value.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of values while searching. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being a value from the map and the second parameter being the searched
     * value, and return `true` if the two values are equal and `false` otherwise.
     * @param  reference $foundUnderKey **OPTIONAL. OUTPUT.** If a value has been found after the method was called
     * with this parameter provided, the parameter's value is being the first key under which the value was seen. If
     * the key is a string, its output type is `CUStringObject`.
     *
     * @return bool `true` if such value was found in the map, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public function find ($whatValue, $comparator = CComparator::EQUALITY, &$foundUnderKey = null)
    {
        $found = CMap::find($this->m_map, $whatValue, $comparator, $foundUnderKey);
        return $found;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map contains a specified scalar value.
     *
     * If a map only contains values of scalar types i.e. `int`, `float`, `bool`, `string` (ASCII only), or `null`,
     * this method allows for faster searches compared to `find` method. In case of `string` type, the search is
     * case-sensitive.
     *
     * The `find` method would be of better service in searching for Unicode strings (the default comparator used by
     * `find` method is Unicode-aware) and it was made flexible for you to be able to set your own comparison rules,
     * such as making the search case-insensitive for any kind of strings.
     *
     * @param  mixed $whatValue The searched value.
     * @param  reference $foundUnderKey **OPTIONAL. OUTPUT.** If a value has been found after the method was called
     * with this parameter provided, the parameter's value is being the first key under which the value was seen. If
     * the key is a string, its output type is `CUStringObject`.
     *
     * @return bool `true` if such value was found in the map, `false` otherwise.
     *
     * @link   #method_find find
     */

    public function findScalar ($whatValue, &$foundUnderKey = null)
    {
        $found = CMap::findScalar($this->m_map, $whatValue, $foundUnderKey);
        return $found;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many occurrences of a specified value there are in a map.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CUStringObject, CArrayObject, CMapObject, CTime etc. See the
     * [CComparator](CComparator.html) class for more on this.
     *
     * @param  mixed $value The searched value.
     * @param  callable $comparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of values while searching. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being a value from the map and the second parameter being the searched
     * value, and return `true` if the two values are equal and `false` otherwise.
     *
     * @return int The number of such values in the map.
     *
     * @link   CComparator.html CComparator
     */

    public function countValue ($value, $comparator = CComparator::EQUALITY)
    {
        return CMap::countValue($this->m_map, $value, $comparator);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a key-value pair from a map.
     *
     * @param  mixed $key The key of the key-value pair to be removed.
     *
     * @return void
     */

    public function remove ($key)
    {
        CMap::remove($this->m_map, $key);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Filters the key-value pairs in a map by calling a function or method on each value and returns a new map with
     * only those key-value pairs that were let through by the filter.
     *
     * The map is not modified by this method.
     *
     * @param  callable $filter The function or method to be used for filtering. The filter should take a value as a
     * parameter and return `true` if the corresponding key-value pair should make its way into the resulting map and
     * `false` if not.
     *
     * @return CMapObject The filtered map.
     */

    public function filter ($filter)
    {
        return self::fromPArray(CMap::filter($this->m_map, $filter));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Extracts and returns the keys from a map, as an array.
     *
     * @return CArrayObject The map's keys.
     */

    public function keys ()
    {
        return CArrayObject::fromSplArray(CMap::keys($this->m_map));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Extracts and returns the values from a map, as an array.
     *
     * @return CArrayObject The map's values.
     */

    public function values ()
    {
        return CArrayObject::fromSplArray(CMap::values($this->m_map));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Merges a map with one or more other maps and returns the new map.
     *
     * The maps that need to be merged with *this* map are passed as arguments to this method. The order in which the
     * maps are passed determines the order in which values are overridden for matching keys, if any.
     *
     * Different from the PHP's `array_merge` function, maps with this method get merged like you would expect them to.
     * Whether it's a string key or a numeric one, its value is overridden by the value of an equal key if such key is
     * present in any of the subsequent maps (with *this* map being the first map), regardless of the level at which
     * the map is located if the source maps are multi-dimensional and contain deeper maps stored under equal keys. And
     * new key-value pairs are added by subsequent maps to the resulting map for any map that did not have them before,
     * no matter how deep the map is located in the map hierarchy as long as the maps' keys match at that level.
     *
     * None of the source maps is modified by this method.
     *
     * @return CMapObject The resulting map.
     */

    public function merge (/*withMap0, withMap1, ...*/)
    {
        $arguments = func_get_args();
        array_unshift($arguments, $this->m_map);
        return self::fromPArray(call_user_func_array("CMap::merge", $arguments));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the keys in a map are all integer and sequential.
     *
     * Sequential keys are a sequence of integers that start with a `0` and go up, incrementing exactly by one.
     *
     * This method could be useful to see if a PHP's associative array (a map) can be naturally converted into a
     * regular array (an OOP array) when dealing with interfaces that are using the PHP's associative array in the role
     * of a regular array.
     *
     * As a special case, the method returns `true` for an empty map.
     *
     * @return bool `true` if all keys in the map are integer and sequential, `false` otherwise.
     */

    public function areKeysSequential ()
    {
        return CMap::areKeysSequential($this->m_map);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts a value into a map under the next integer key.
     *
     * This method could serve as a more eloquent alternative when dealing with interfaces that are using the PHP's
     * associative array in the role of a regular array.
     *
     * The new value is inserted under the integer key that is greater by one compared to the greatest integer key
     * already in the map or under `0` if there were no integer keys.
     *
     * @param  mixed $value The value to be inserted.
     *
     * @return void
     */

    public function insertValue ($value)
    {
        CMap::insertValue($this->m_map, $value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function fromArguments ($arguments)
    {
        assert( 'empty($arguments) || (count($arguments) == 1 && is_array($arguments[0]))',
            vs(isset($this), get_defined_vars()) );

        $map = new self(true);

        if ( empty($arguments) )
        {
            $map->m_map = [];
        }
        else
        {
            $map->m_map = $arguments[0];
        }

        return $map;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function fromPArray ($pArray)
    {
        assert( 'is_array($pArray)', vs(isset($this), get_defined_vars()) );

        $map = new self(true);
        $map->m_map = $pArray;
        return $map;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function &toPArray ()
    {
        return $this->m_map;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function offsetExists ($offset)
    {
        return isset($this->m_map[$offset]);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function offsetGet ($offset)
    {
        return $this->m_map[$offset];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function offsetSet ($offset, $value)
    {
        $this->m_map[$offset] = $value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function offsetUnset ($offset)
    {
        unset($this->m_map[$offset]);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function getIterator ()
    {
        return $this->generateIterator();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function count ()
    {
        return count($this->m_map);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function &generateIterator ()
    {
        foreach ($this->m_map as $key => &$value)
        {
            yield $key => $value;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    // public function __debugInfo ()
    // {
    //     return $this->m_map;
    // }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_map;
}
