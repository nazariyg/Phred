<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_cstring ($value)
{
    return is_string($value);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_number ($value)
{
    return ( is_int($value) || is_float($value) );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_enum ($value)
{
    return is_int($value);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_bitfield ($value)
{
    return is_int($value);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_carray ($value)
{
    return ( $value instanceof SplFixedArray || $value instanceof CArrayObject );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_cmap ($value)
{
    return ( is_array($value) || $value instanceof CMapObject );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_collection ($value)
{
    return ( is_carray($value) || is_cmap($value) );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_ctime ($value)
{
    // Used when parameter type hinting is inapplicable.
    return $value instanceof CTime;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_ctimezone ($value)
{
    // Used when parameter type hinting is inapplicable.
    return $value instanceof CTimeZone;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_cfile ($value)
{
    // Used when parameter type hinting is inapplicable.
    return $value instanceof CFile;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_culocale ($value)
{
    // Used when parameter type hinting is inapplicable.
    return $value instanceof CULocale;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function a ()
{
    return CArrayObject::fromArguments(func_get_args());
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function m ()
{
    return CMapObject::fromArguments(func_get_args());
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function to_oop ($value)
{
    if ( is_carray($value) )
    {
        $value = splarray($value);
        $len = CArray::length($value);
        for ($i = 0; $i < $len; $i++)
        {
            $value[$i] = to_oop($value[$i]);
        }
        $value = CArrayObject::fromSplArray($value);
        return $value;
    }
    if ( is_cmap($value) )
    {
        $value = parray($value);
        foreach ($value as &$mapValue)
        {
            $mapValue = to_oop($mapValue);
        } unset($mapValue);
        $value = CMapObject::fromPArray($value);
        return $value;
    }
    return $value;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function splarray ($array)
{
    return ( $array instanceof SplFixedArray ) ? $array : $array->toSplArray();
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function parray ($map)
{
    return ( is_array($map) ) ? $map : $map->toPArray();
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function oop_a ($array)
{
    return ( is_oop_on() && $array instanceof SplFixedArray ) ? CArrayObject::fromSplArray($array) : $array;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function oop_m ($map)
{
    return ( is_oop_on() && is_array($map) ) ? CMapObject::fromPArray($map) : $map;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function oop_x ($value)
{
    return ( is_oop_on() ) ? to_oop($value) : $value;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function phred_classify ($value, &$className)
{
    // This function is only used internally and it tries to find out the class name for a known type.
    if ( is_string($value) )
    {
        $className = "CUString";
        return true;
    }
    if ( is_int($value) || is_float($value) || is_bool($value) )
    {
        return false;
    }
    if ( is_carray($value) )
    {
        $className = "CArray";
        return true;
    }
    if ( is_cmap($value) )
    {
        $className = "CMap";
        return true;
    }
    if ( is_object($value) )
    {
        $className = get_class($value);
        return true;
    }
    return false;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function phred_classify_duo ($value0, $value1, &$className)
{
    if ( !is_object($value0) || !is_object($value1) )
    {
        return phred_classify($value0, $className);
    }

    if ( $value0 instanceof CArrayObject && $value1 instanceof CArrayObject )
    {
        $className = "CArrayObject";
        return true;
    }
    if ( $value0 instanceof CMapObject && $value1 instanceof CMapObject )
    {
        $className = "CMapObject";
        return true;
    }

    return phred_classify($value0, $className);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function _from_oop_tp ($value)
{
    // Only used with OOP wrapping for third-party components.
    if ( is_carray($value) )
    {
        $value = splarray($value);
        $len = CArray::length($value);
        for ($i = 0; $i < $len; $i++)
        {
            $value[$i] = _from_oop_tp($value[$i]);
        }
        return $value->toArray();
    }
    if ( is_cmap($value) )
    {
        $value = parray($value);
        foreach ($value as &$mapValue)
        {
            $mapValue = _from_oop_tp($mapValue);
        } unset($mapValue);
        return $value;
    }
    return $value;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function _to_oop_tp ($value)
{
    // Only used with OOP wrapping for third-party components.
    if ( is_array($value) )
    {
        foreach ($value as &$mapValue)
        {
            $mapValue = _to_oop_tp($mapValue);
        } unset($mapValue);
        if ( !CMap::areKeysSequential($value) )
        {
            $value = oop_m($value);
        }
        else
        {
            $value = oop_a(CArray::fromPArray($value));
        }
        return $value;
    }
    if ( $value instanceof SplFixedArray )
    {
        $len = CArray::length($value);
        for ($i = 0; $i < $len; $i++)
        {
            $value[$i] = _to_oop_tp($value[$i]);
        }
        return oop_a($value);
    }
    return $value;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
