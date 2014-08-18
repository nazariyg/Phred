<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that is dealing with IP addresses.
 */

// Method signatures:
//   static bool isValidV4 ($ip, $options = self::VALIDATION_DEFAULT)
//   static bool isValidV6 ($ip, $options = self::VALIDATION_DEFAULT)
//   static bool isValidV4Or6 ($ip, $options = self::VALIDATION_DEFAULT)

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
     * @param  string $ip The IPv4 address to be looked into.
     * @param  bitfield $options **OPTIONAL. Default is** `VALIDATION_DEFAULT`. The validation option(s) to be used.
     * The available options are `DISALLOW_PRIVATE_RANGE` and `DISALLOW_RESERVED_RANGE`.
     *
     * @return bool `true` if the IP address is valid, `false` otherwise.
     */

    public static function isValidV4 ($ip, $options = self::VALIDATION_DEFAULT)
    {
        assert( 'is_cstring($ip) && is_bitfield($options)', vs(isset($this), get_defined_vars()) );

        $filterFlags = FILTER_FLAG_IPV4;
        self::filterFlagsFromOptions($options, $filterFlags);
        return is_cstring(filter_var($ip, FILTER_VALIDATE_IP, $filterFlags));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an IPv6 address is valid.
     *
     * @param  string $ip The IPv6 address to be looked into.
     * @param  bitfield $options **OPTIONAL. Default is** `VALIDATION_DEFAULT`. The validation option(s) to be used.
     * The available options are `DISALLOW_PRIVATE_RANGE` and `DISALLOW_RESERVED_RANGE`.
     *
     * @return bool `true` if the IP address is valid, `false` otherwise.
     */

    public static function isValidV6 ($ip, $options = self::VALIDATION_DEFAULT)
    {
        assert( 'is_cstring($ip) && is_bitfield($options)', vs(isset($this), get_defined_vars()) );

        $filterFlags = FILTER_FLAG_IPV6;
        self::filterFlagsFromOptions($options, $filterFlags);
        return is_cstring(filter_var($ip, FILTER_VALIDATE_IP, $filterFlags));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if an IP address is valid as either IPv4 or IPv6.
     *
     * @param  string $ip The IP address to be looked into.
     * @param  bitfield $options **OPTIONAL. Default is** `VALIDATION_DEFAULT`. The validation option(s) to be used.
     * The available options are `DISALLOW_PRIVATE_RANGE` and `DISALLOW_RESERVED_RANGE`.
     *
     * @return bool `true` if the IP address is valid, `false` otherwise.
     */

    public static function isValidV4Or6 ($ip, $options = self::VALIDATION_DEFAULT)
    {
        assert( 'is_cstring($ip) && is_bitfield($options)', vs(isset($this), get_defined_vars()) );
        return ( self::isValidV4($ip, $options) || self::isValidV6($ip, $options) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function filterFlagsFromOptions ($options, &$filterFlags)
    {
        if ( CBitField::isBitSet($options, self::DISALLOW_PRIVATE_RANGE) )
        {
            $filterFlags |= FILTER_FLAG_NO_PRIV_RANGE;
        }
        if ( CBitField::isBitSet($options, self::DISALLOW_RESERVED_RANGE) )
        {
            $filterFlags |= FILTER_FLAG_NO_RES_RANGE;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
