<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_cstring ($xValue)
{
    return is_string($xValue);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_number ($xValue)
{
    return ( is_int($xValue) || is_float($xValue) );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_enum ($xValue)
{
    return is_int($xValue);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_bitfield ($xValue)
{
    return is_int($xValue);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_carray ($xValue)
{
    return ( $xValue instanceof SplFixedArray || $xValue instanceof CArrayObject );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_cmap ($xValue)
{
    return ( is_array($xValue) || $xValue instanceof CMapObject );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_collection ($xValue)
{
    return ( is_carray($xValue) || is_cmap($xValue) );
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_ctime ($xValue)
{
    // Used when parameter type hinting is inapplicable.
    return $xValue instanceof CTime;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_ctimezone ($xValue)
{
    // Used when parameter type hinting is inapplicable.
    return $xValue instanceof CTimeZone;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_cfile ($xValue)
{
    // Used when parameter type hinting is inapplicable.
    return $xValue instanceof CFile;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function is_culocale ($xValue)
{
    // Used when parameter type hinting is inapplicable.
    return $xValue instanceof CULocale;
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

function to_oop ($xValue)
{
    if ( is_carray($xValue) )
    {
        $xValue = splarray($xValue);
        $iLen = CArray::length($xValue);
        for ($i = 0; $i < $iLen; $i++)
        {
            $xValue[$i] = to_oop($xValue[$i]);
        }
        $xValue = CArrayObject::fromSplArray($xValue);
        return $xValue;
    }
    if ( is_cmap($xValue) )
    {
        $xValue = parray($xValue);
        foreach ($xValue as &$rxMapValue)
        {
            $rxMapValue = to_oop($rxMapValue);
        } unset($rxMapValue);
        $xValue = CMapObject::fromPArray($xValue);
        return $xValue;
    }
    return $xValue;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function splarray ($aArray)
{
    return ( $aArray instanceof SplFixedArray ) ? $aArray : $aArray->toSplArray();
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function parray ($mMap)
{
    return ( is_array($mMap) ) ? $mMap : $mMap->toPArray();
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function oop_a ($aArray)
{
    return ( is_oop_on() && $aArray instanceof SplFixedArray ) ? CArrayObject::fromSplArray($aArray) : $aArray;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function oop_m ($mMap)
{
    return ( is_oop_on() && is_array($mMap) ) ? CMapObject::fromPArray($mMap) : $mMap;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function oop_x ($xValue)
{
    return ( is_oop_on() ) ? to_oop($xValue) : $xValue;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function phred_classify ($xValue, &$rsClassName)
{
    // This function is only used internally and it tries to find out the class name for a known type.
    if ( is_string($xValue) )
    {
        $rsClassName = "CUString";
        return true;
    }
    if ( is_int($xValue) || is_float($xValue) || is_bool($xValue) )
    {
        return false;
    }
    if ( is_carray($xValue) )
    {
        $rsClassName = "CArray";
        return true;
    }
    if ( is_cmap($xValue) )
    {
        $rsClassName = "CMap";
        return true;
    }
    if ( is_object($xValue) )
    {
        $rsClassName = get_class($xValue);
        return true;
    }
    return false;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function phred_classify_duo ($xValue0, $xValue1, &$rsClassName)
{
    if ( !is_object($xValue0) || !is_object($xValue1) )
    {
        return phred_classify($xValue0, $rsClassName);
    }

    if ( $xValue0 instanceof CArrayObject && $xValue1 instanceof CArrayObject )
    {
        $rsClassName = "CArrayObject";
        return true;
    }
    if ( $xValue0 instanceof CMapObject && $xValue1 instanceof CMapObject )
    {
        $rsClassName = "CMapObject";
        return true;
    }

    return phred_classify($xValue0, $rsClassName);
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function _from_oop_tp ($xValue)
{
    // Only used with OOP wrapping for third-party components.
    if ( is_carray($xValue) )
    {
        $xValue = splarray($xValue);
        $iLen = CArray::length($xValue);
        for ($i = 0; $i < $iLen; $i++)
        {
            $xValue[$i] = _from_oop_tp($xValue[$i]);
        }
        return $xValue->toArray();
    }
    if ( is_cmap($xValue) )
    {
        $xValue = parray($xValue);
        foreach ($xValue as &$rxMapValue)
        {
            $rxMapValue = _from_oop_tp($rxMapValue);
        } unset($rxMapValue);
        return $xValue;
    }
    return $xValue;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
/**
 * @ignore
 */

function _to_oop_tp ($xValue)
{
    // Only used with OOP wrapping for third-party components.
    if ( is_array($xValue) )
    {
        foreach ($xValue as &$rxMapValue)
        {
            $rxMapValue = _to_oop_tp($rxMapValue);
        } unset($rxMapValue);
        if ( !CMap::areKeysSequential($xValue) )
        {
            $xValue = oop_m($xValue);
        }
        else
        {
            $xValue = oop_a(CArray::fromPArray($xValue));
        }
        return $xValue;
    }
    if ( $xValue instanceof SplFixedArray )
    {
        $iLen = CArray::length($xValue);
        for ($i = 0; $i < $iLen; $i++)
        {
            $xValue[$i] = _to_oop_tp($xValue[$i]);
        }
        return oop_a($xValue);
    }
    return $xValue;
}
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
