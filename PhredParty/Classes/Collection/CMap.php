<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The hub for static methods that take care of instances of the PHP's associative array, which is also known as CMap.
 *
 * **In the OOP mode, you would likely never need to use this class.**
 *
 * *The [PHP's associative array](http://www.php.net/arrays) is called CMap to avoid any confusion with the regular
 * array, which is kindly represented by the SplFixedArray class.*
 *
 * What a CMap does is that it contains arbitrary values associated with keys by which those values can be found. The
 * keys are unique within a CMap. This is effectively the same what objects do in JavaScript.
 *
 * As imposed by PHP, the type of a key in an associative array can be either `string` or `int`. When a string that is
 * going to be used as a key looks like an integer, it's actually converted into the corresponding integer and used as
 * such to access the value (except if the string begins with one or more "0"). A value of type `float`, `bool`, or
 * `null` is converted into an integer too when trying to use it for a key. A key cannot be an empty string in a PHP's
 * associative array, and any empty string becomes "_" from the CMap's point of view when trying to use it as a key.
 *
 * Similar to regular arrays, you can access a value in a CMap or insert a new key-value pair with `[ ]` notation,
 * putting the key with which the value is associated between the brackets. With `valueByPath` and `setValueByPath`
 * methods, you can also access a value in a multi-dimensional (or one-dimensional) CMap by its key path, which is a
 * string hierarchically indicating the keys following which the value can be found, chained together with "."
 * separator, as in "level1key.level2key". The `merge` method is proud of being able to merge maps in the *correct*
 * way.
 */

// Method signatures:
//   static CMap make ()
//   static CMap makeCopy ($mMap)
//   static int length ($mMap)
//   static bool isEmpty ($mMap)
//   static bool equals ($mMap, $mToMap, $fnComparator = CComparator::EQUALITY)
//   static int compare ($mMap, $mToMap, $fnComparator = CComparator::ORDER_ASC)
//   static bool hasKey ($mMap, $xKey)
//   static bool hasPath ($mMap, $sPath)
//   static mixed valueByPath ($mMap, $sPath)
//   static void setValueByPath (&$rmMap, $sPath, $xValue)
//   static bool find ($mMap, $xWhatValue, $fnComparator = CComparator::EQUALITY, &$rxFoundUnderKey = null)
//   static bool findScalar ($mMap, $xWhatValue, &$rxFoundUnderKey = null)
//   static int countValue ($mMap, $xValue, $fnComparator = CComparator::EQUALITY)
//   static void remove (&$rmMap, $xKey)
//   static CMap filter ($mMap, $fnFilter)
//   static CArray keys ($mMap)
//   static CArray values ($mMap)
//   static CMap merge (/*map, withMap0, withMap1, ...*/)
//   static bool areKeysSequential ($mMap)
//   static void insertValue (&$rmMap, $xValue)

class CMap extends CRootClass implements ICopyingStatic, IEqualityAndOrderStatic
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates and returns an empty PHP's associative array (called CMap).
     *
     * @return CMap The new map.
     */

    public static function make ()
    {
        return [];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes a copy of a map.
     *
     * This method exists to mirror the same-named method of the CArray class.
     *
     * @param  map $mMap The map to be copied.
     *
     * @return CMap A copy of the map.
     */

    public static function makeCopy ($mMap)
    {
        assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        return $mMap;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many key-value pairs there are in a map.
     *
     * @param  map $mMap The map to be looked into.
     *
     * @return int The length of the map.
     */

    public static function length ($mMap)
    {
        assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        return count($mMap);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map is empty.
     *
     * @param  map $mMap The map to be looked into.
     *
     * @return bool `true` if the map is empty, `false` otherwise.
     */

    public static function isEmpty ($mMap)
    {
        assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        return ( count($mMap) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two maps are equal.
     *
     * For any two maps to be equal, they need to have the same key-value pairs.
     *
     * You can use your own comparator for the comparison of the values in the maps, but the default comparator has got
     * you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool`. And the default
     * comparator is smart enough to know how to compare objects of those classes that conform to the IEquality or
     * IEqualityAndOrder interface (static or not), including CArray and CMap. See the [CComparator](CComparator.html)
     * class for more on this.
     *
     * @param  map $mMap The first map for comparison.
     * @param  map $mToMap The second map for comparison.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of any two values. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being a value from the first map and the second parameter being a value
     * from the second map, and return `true` if the two values are equal and `false` otherwise.
     *
     * @return bool `true` if the two maps are equal, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function equals ($mMap, $mToMap, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_cmap($mMap) && is_cmap($mToMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);
        $mToMap = parray($mToMap);

        // Compare the keys.
        $mKeys = array_keys($mMap);
        $mToKeys = array_keys($mToMap);
        if ( count($mKeys) != count($mToKeys) )
        {
            return false;
        }
        for ($i = 0; $i < count($mKeys); $i++)
        {
            if ( $mKeys[$i] !== $mToKeys[$i] )
            {
                return false;
            }
        }

        // Compare the values. The quantities should match at this point.
        $mValues = array_values($mMap);
        $mToValues = array_values($mToMap);
        for ($i = 0; $i < count($mValues); $i++)
        {
            if ( !call_user_func($fnComparator, $mValues[$i], $mToValues[$i]) )
            {
                return false;
            }
        }

        return true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two maps should appear in a place where it matters.
     *
     * You can use your own comparator for the comparison of the values in the maps, but the default comparator has got
     * you covered when comparing scalar values, such as `string`, `int`, `float`, and `bool` in the ascending order or
     * in the descending order if you use `CComparator::ORDER_DESC`. And the default comparator is smart enough to know
     * how to compare objects of those classes that conform to the IEqualityAndOrder interface (static or not),
     * including CArray and CMap. See the [CComparator](CComparator.html) class for more on this.
     *
     * @param  map $mMap The first map for comparison.
     * @param  map $mToMap The second map for comparison.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::ORDER_ASC`. The function or method to be
     * used for the comparison of any two values. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being a value from the first map and the second parameter being a value
     * from the second map, and return `-1` if the value from the first map would need to go before the value from the
     * second map if the two were being ordered in separate, `1` if the other way around, and `0` if the two values are
     * equal.
     *
     * @return int A negative value (typically `-1`) if the first map should go before the second map, a positive value
     * (typically `1`) if the other way around, and `0` if the two maps are equal.
     *
     * @link   CComparator.html CComparator
     */

    public static function compare ($mMap, $mToMap, $fnComparator = CComparator::ORDER_ASC)
    {
        assert( 'is_cmap($mMap) && is_cmap($mToMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);
        $mToMap = parray($mToMap);

        // Compare the keys.
        $mKeys = array_keys($mMap);
        $mToKeys = array_keys($mToMap);
        if ( count($mKeys) != count($mToKeys) )
        {
            return CMathi::sign(count($mKeys) - count($mToKeys));
        }
        for ($i = 0; $i < count($mKeys); $i++)
        {
            if ( $mKeys[$i] !== $mToKeys[$i] )
            {
                return ( $mKeys[$i] < $mToKeys[$i] ) ? -1 : 1;
            }
        }

        // Compare the values. The quantities should match at this point.
        $mValues = array_values($mMap);
        $mToValues = array_values($mToMap);
        for ($i = 0; $i < count($mValues); $i++)
        {
            $iCompRes = call_user_func($fnComparator, $mValues[$i], $mToValues[$i]);
            if ( $iCompRes != 0 )
            {
                return $iCompRes;
            }
        }

        return 0;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map contains a value under a specified key.
     *
     * @param  map $mMap The map to be looked into.
     * @param  mixed $xKey The key to be looked for.
     *
     * @return bool `true` if the map contains a value under such key, `false` otherwise.
     */

    public static function hasKey ($mMap, $xKey)
    {
        assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        return array_key_exists($xKey, $mMap);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map contains a value by a specified key path.
     *
     * @param  map $mMap The map to be looked into.
     * @param  string $sPath The key path to be looked by, e.g. "level1key.level2key".
     *
     * @return bool `true` if the map contains a value by such key path, `false` otherwise.
     */

    public static function hasPath ($mMap, $sPath)
    {
        assert( 'is_cmap($mMap) && is_cstring($sPath)', vs(isset($this), get_defined_vars()) );

        $mPathComps = explode(".", $sPath);
        $iNumLevels = count($mPathComps) - 1;
        $mCurrMap = parray($mMap);
        for ($i = 0; $i < $iNumLevels; $i++)
        {
            $sComp = $mPathComps[$i];
            if ( !array_key_exists($sComp, $mCurrMap) )
            {
                return false;
            }
            $mCurrMap = $mCurrMap[$sComp];
            if ( !is_cmap($mCurrMap) )
            {
                return false;
            }
            $mCurrMap = parray($mCurrMap);
        }
        return array_key_exists($mPathComps[$iNumLevels], $mCurrMap);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a map, returns the value found by a specified key path.
     *
     * @param  map $mMap The map to be looked into.
     * @param  string $sPath The key path by which the value is to be found, e.g. "level1key.level2key".
     *
     * @return mixed The value found by the key path.
     */

    public static function valueByPath ($mMap, $sPath)
    {
        assert( 'is_cmap($mMap) && is_cstring($sPath)', vs(isset($this), get_defined_vars()) );

        $mPathComps = explode(".", $sPath);
        $iNumLevels = count($mPathComps) - 1;
        $mCurrMap = parray($mMap);
        for ($i = 0; $i < $iNumLevels; $i++)
        {
            $mCurrMap = $mCurrMap[$mPathComps[$i]];
            assert( 'is_cmap($mCurrMap)', vs(isset($this), get_defined_vars()) );
            $mCurrMap = parray($mCurrMap);
        }
        return $mCurrMap[$mPathComps[$iNumLevels]];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a map, modifies the value found by a specified key path.
     *
     * @param  map $rmMap The map to be modified.
     * @param  string $sPath The key path by which the value is to be found, e.g. "level1key.level2key".
     * @param  mixed $xValue The new value.
     *
     * @return void
     */

    public static function setValueByPath (&$rmMap, $sPath, $xValue)
    {
        assert( 'is_cmap($rmMap) && is_cstring($sPath)', vs(isset($this), get_defined_vars()) );

        $mPathComps = explode(".", $sPath);
        $iNumLevels = count($mPathComps) - 1;
        $rmCurrMap = &$rmMap;
        if ( !is_array($rmCurrMap) )
        {
            // CMapObject
            $rmCurrMap = &$rmCurrMap->toPArray();
        }
        for ($i = 0; $i < $iNumLevels; $i++)
        {
            $rmCurrMap = &$rmCurrMap[$mPathComps[$i]];
            if ( !is_array($rmCurrMap) )
            {
                // CMapObject
                $rmCurrMap = &$rmCurrMap->toPArray();
            }
            assert( 'is_cmap($rmCurrMap)', vs(isset($this), get_defined_vars()) );
        }
        $rmCurrMap[$mPathComps[$iNumLevels]] = $xValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map contains a specified value.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CArray and CMap. See the [CComparator](CComparator.html) class for more on this.
     *
     * @param  map $mMap The map to be looked into.
     * @param  mixed $xWhatValue The searched value.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of values while searching. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being a value from the map and the second parameter being the searched
     * value, and return `true` if the two values are equal and `false` otherwise.
     * @param  reference $rxFoundUnderKey **OPTIONAL. OUTPUT.** If a value has been found after the method was called
     * with this parameter provided, the parameter's value is being the first key under which the value was seen.
     *
     * @return bool `true` if such value was found in the map, `false` otherwise.
     *
     * @link   CComparator.html CComparator
     */

    public static function find ($mMap, $xWhatValue, $fnComparator = CComparator::EQUALITY, &$rxFoundUnderKey = null)
    {
        assert( 'is_cmap($mMap) && is_callable($fnComparator)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        foreach ($mMap as $xMapKey => $xMapValue)
        {
            if ( call_user_func($fnComparator, $xMapValue, $xWhatValue) )
            {
                $rxFoundUnderKey = $xMapKey;
                return true;
            }
        }
        return false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a map contains a specified scalar value.
     *
     * If a map only contains values of scalar types i.e. `int`, `float`, `bool`, `string` (ASCII only), or `null`,
     * this method allows for faster searches compared to `find` method. In case of `string` type, the search is
     * case-sensitive.
     *
     * @param  map $mMap The map to be looked into.
     * @param  mixed $xWhatValue The searched value.
     * @param  reference $rxFoundUnderKey **OPTIONAL. OUTPUT.** If a value has been found after the method was called
     * with this parameter provided, the parameter's value is being the first key under which the value was seen.
     *
     * @return bool `true` if such value was found in the map, `false` otherwise.
     *
     * @link   #method_find find
     */

    public static function findScalar ($mMap, $xWhatValue, &$rxFoundUnderKey = null)
    {
        assert( 'is_cmap($mMap) && (is_scalar($xWhatValue) || is_null($xWhatValue))',
            vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        $xRes = array_search($xWhatValue, $mMap, true);
        if ( $xRes !== false )
        {
            $rxFoundUnderKey = $xRes;
            return true;
        }
        else
        {
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many occurrences of a specified value there are in a map.
     *
     * You can use your own comparator for the search, but the default comparator has got you covered when searching
     * for scalar values, such as `string`, `int`, `float`, and `bool`. And the default comparator is smart enough to
     * know how to compare objects of those classes that conform to the IEquality or IEqualityAndOrder interface
     * (static or not), including CArray and CMap. See the [CComparator](CComparator.html) class for more on this.
     *
     * @param  map $mMap The map to be looked into.
     * @param  mixed $xValue The searched value.
     * @param  callable $fnComparator **OPTIONAL. Default is** `CComparator::EQUALITY`. The function or method to be
     * used for the comparison of values while searching. If this parameter is provided, the comparator should take two
     * parameters, with the first parameter being a value from the map and the second parameter being the searched
     * value, and return `true` if the two values are equal and `false` otherwise.
     *
     * @return int The number of such values in the map.
     *
     * @link   CComparator.html CComparator
     */

    public static function countValue ($mMap, $xValue, $fnComparator = CComparator::EQUALITY)
    {
        assert( 'is_cmap($mMap) && is_callable($fnComparator)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        $iCount = 0;
        foreach ($mMap as $xMapValue)
        {
            if ( call_user_func($fnComparator, $xMapValue, $xValue) )
            {
                $iCount++;
            }
        }
        return $iCount;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a key-value pair from a map.
     *
     * @param  map $rmMap The map to be modified.
     * @param  mixed $xKey The key of the key-value pair to be removed.
     *
     * @return void
     */

    public static function remove (&$rmMap, $xKey)
    {
        assert( 'is_cmap($rmMap)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasKey($rmMap, $xKey)', vs(isset($this), get_defined_vars()) );

        if ( is_array($rmMap) )
        {
            unset($rmMap[$xKey]);
        }
        else  // CMapObject
        {
            $rmPArray = &$rmMap->toPArray();
            unset($rmPArray[$xKey]);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Filters the key-value pairs in a map by calling a function or method on each value and returns a new map with
     * only those key-value pairs that were let through by the filter.
     *
     * The input map is not modified by this method.
     *
     * @param  map $mMap The map to be filtered.
     * @param  callable $fnFilter The function or method to be used for filtering. The filter should take a value as a
     * parameter and return `true` if the corresponding key-value pair should make its way into the resulting map and
     * `false` if not.
     *
     * @return CMap The filtered map.
     */

    public static function filter ($mMap, $fnFilter)
    {
        assert( 'is_cmap($mMap) && is_callable($fnFilter)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        return array_filter($mMap, $fnFilter);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Extracts and returns the keys from a map, as an array.
     *
     * @param  map $mMap The map to be looked into.
     *
     * @return CArray The map's keys.
     */

    public static function keys ($mMap)
    {
        assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        return CArray::fromPArray(array_keys($mMap));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Extracts and returns the values from a map, as an array.
     *
     * @param  map $mMap The map to be looked into.
     *
     * @return CArray The map's values.
     */

    public static function values ($mMap)
    {
        assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );
        return CArray::fromPArray($mMap);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Merges two or more maps together and returns the new map.
     *
     * The maps that need to be merged are passed as arguments to this method. The order in which the maps are passed
     * determines the order in which values are overridden for matching keys, if any.
     *
     * Different from the PHP's `array_merge` function, maps with this method get merged like you would expect them to.
     * Whether it's a string key or a numeric one, its value is overridden by the value of an equal key if such key is
     * present in any of the subsequent maps, regardless of the level at which the map is located if the source maps
     * are multi-dimensional and contain deeper maps stored under equal keys. And new key-value pairs are added by
     * subsequent maps to the resulting map for any map that did not have them before, no matter how deep the map is
     * located in the map hierarchy as long as the maps' keys match at that level.
     *
     * None of the source maps is modified by this method.
     *
     * @return CMap The resulting map.
     */

    public static function merge (/*map, withMap0, withMap1, ...*/)
    {
        $iFuncNumArgs = func_num_args();
        assert( '$iFuncNumArgs >= 2', vs(isset($this), get_defined_vars()) );

        $mResMap = self::make();
        $mArguments = func_get_args();
        foreach ($mArguments as $mMap)
        {
            assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );
            self::recurseMergingMaps($mResMap, $mMap);
        }
        return $mResMap;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the keys in a map are all integer and sequential.
     *
     * Sequential keys are a sequence of integers that start with a `0` and go up, incrementing exactly by one.
     *
     * This method could be useful to see if a PHP's associative array (a map) can be naturally converted into a
     * regular array (SplFixedArray) when dealing with interfaces that are using the PHP's associative array in the
     * role of a regular array.
     *
     * As a special case, the method returns `true` for an empty map.
     *
     * @param  map $mMap The map to be looked into.
     *
     * @return bool `true` if all keys in the map are integer and sequential, `false` otherwise.
     */

    public static function areKeysSequential ($mMap)
    {
        assert( 'is_cmap($mMap)', vs(isset($this), get_defined_vars()) );

        $mMap = parray($mMap);

        $aKeys = self::keys($mMap);
        $iLen = CArray::length($aKeys);
        for ($i = 0; $i < $iLen; $i++)
        {
            if ( !is_int($aKeys[$i]) || $aKeys[$i] != $i )
            {
                return false;
            }
        }
        return true;
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
     * @param  map $rmMap The map to be modified.
     * @param  mixed $xValue The value to be inserted.
     *
     * @return void
     */

    public static function insertValue (&$rmMap, $xValue)
    {
        assert( 'is_cmap($rmMap)', vs(isset($this), get_defined_vars()) );

        if ( is_array($rmMap) )
        {
            array_push($rmMap, $xValue);
        }
        else  // CMapObject
        {
            $rmPArray = &$rmMap->toPArray();
            array_push($rmPArray, $xValue);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseMergingMaps (&$rmThisMap, $mThatMap)
    {
        if ( !is_array($rmThisMap) )
        {
            $rmThisMap = &$rmThisMap->toPArray();
        }
        $mThatMap = parray($mThatMap);
        foreach ($mThatMap as $xThatKey => $xThatValue)
        {
            $bGoDeeper = false;
            $rxThisValue;
            if ( is_cmap($xThatValue) && self::hasKey($rmThisMap, $xThatKey) )
            {
                $rxThisValue = &$rmThisMap[$xThatKey];
                if ( is_cmap($rxThisValue) )
                {
                    $bGoDeeper = true;
                }
            }
            if ( $bGoDeeper )
            {
                self::recurseMergingMaps($rxThisValue, $xThatValue);
            }
            else
            {
                $rmThisMap[$xThatKey] = $xThatValue;
            }
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
