<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that is dealing with IP addresses.
 */

// Method signatures:
//   static bool isValidV4 ($sIp, $bfOptions = self::VALIDATION_DEFAULT)
//   static bool isValidV6 ($sIp, $bfOptions = self::VALIDATION_DEFAULT)
//   static bool isValidV4Or6 ($sIp, $bfOptions = self::VALIDATION_DEFAULT)

class CIp extends CRootClass
{
    // Validation flags.
    /**
     * `bitfield` None of the below validation flags (`0`). Both private and reserved IP ranges are allowed for a valid
     * IP address.
     *
     * @var bitfield
     */
    const VALIDATION_DEFAULT = CBitField::ALL_UNSET;
    /**
     * `bitfield` Disallow a valid IP address to fall into a private IP range.
     *
     * @var bitfield
     */
    const DISALLOW_PRIVATE_RANGE = CBitField::SET_0;
    /**
     * `bitfield` Disallow a valid IP address to fall into a reserved IP range.
     *
     * @var bitfield
     */
    const DISALLOW_RESERVED_RANGE = CBitField::SET_1;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an IPv4 address is valid.
     *
     * @param  string $sIp The IPv4 address to be looked into.
     * @param  bitfield $bfOptions **OPTIONAL. Default is** `VALIDATION_DEFAULT`. The validation option(s) to be used.
     * The available options are `DISALLOW_PRIVATE_RANGE` and `DISALLOW_RESERVED_RANGE`.
     *
     * @return bool `true` if the IP address is valid, `false` otherwise.
     */

    public static function isValidV4 ($sIp, $bfOptions = self::VALIDATION_DEFAULT)
    {
        assert( 'is_cstring($sIp) && is_bitfield($bfOptions)', vs(isset($this), get_defined_vars()) );

        $bfFilterFlags = FILTER_FLAG_IPV4;
        self::filterFlagsFromOptions($bfOptions, $bfFilterFlags);
        return is_cstring(filter_var($sIp, FILTER_VALIDATE_IP, $bfFilterFlags));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an IPv6 address is valid.
     *
     * @param  string $sIp The IPv6 address to be looked into.
     * @param  bitfield $bfOptions **OPTIONAL. Default is** `VALIDATION_DEFAULT`. The validation option(s) to be used.
     * The available options are `DISALLOW_PRIVATE_RANGE` and `DISALLOW_RESERVED_RANGE`.
     *
     * @return bool `true` if the IP address is valid, `false` otherwise.
     */

    public static function isValidV6 ($sIp, $bfOptions = self::VALIDATION_DEFAULT)
    {
        assert( 'is_cstring($sIp) && is_bitfield($bfOptions)', vs(isset($this), get_defined_vars()) );

        $bfFilterFlags = FILTER_FLAG_IPV6;
        self::filterFlagsFromOptions($bfOptions, $bfFilterFlags);
        return is_cstring(filter_var($sIp, FILTER_VALIDATE_IP, $bfFilterFlags));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an IP address is valid as either IPv4 or IPv6.
     *
     * @param  string $sIp The IP address to be looked into.
     * @param  bitfield $bfOptions **OPTIONAL. Default is** `VALIDATION_DEFAULT`. The validation option(s) to be used.
     * The available options are `DISALLOW_PRIVATE_RANGE` and `DISALLOW_RESERVED_RANGE`.
     *
     * @return bool `true` if the IP address is valid, `false` otherwise.
     */

    public static function isValidV4Or6 ($sIp, $bfOptions = self::VALIDATION_DEFAULT)
    {
        assert( 'is_cstring($sIp) && is_bitfield($bfOptions)', vs(isset($this), get_defined_vars()) );
        return ( self::isValidV4($sIp, $bfOptions) || self::isValidV6($sIp, $bfOptions) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function filterFlagsFromOptions ($bfOptions, &$rbfFilterFlags)
    {
        if ( CBitField::isBitSet($bfOptions, self::DISALLOW_PRIVATE_RANGE) )
        {
            $rbfFilterFlags |= FILTER_FLAG_NO_PRIV_RANGE;
        }
        if ( CBitField::isBitSet($bfOptions, self::DISALLOW_RESERVED_RANGE) )
        {
            $rbfFilterFlags |= FILTER_FLAG_NO_RES_RANGE;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
