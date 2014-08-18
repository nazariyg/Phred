<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * This class works with bitfields, which are mere integers used for the compact storage of related boolean values,
 * sometimes called flags.
 *
 * Since an integer is made up of placeholders for `1`s and `0`s, just like any other information nowadays, it makes an
 * integer a very efficient place to store multiple values if those values are boolean, i.e. can be either `true` or
 * `false`. When talking about a bitfield, bits whose values are `1` are called *set* and bits that are `0` are called
 * *unset*. It's not too convenient to look into a bitfield to see which bit is set or to modify a bit using the
 * bitwise operations available PHP, so this class exists to make things bit easier.
 *
 * Each bit has its own position so that the first bit is located at the position of 0, the second at the position of
 * 1, and so on. When you need a bitfield where a single bit is set and you only know the bit's position, just use
 * `SET_X` constant of the class, where `X` is the position of that bit.
 */

// Method signatures:
//   static bool isBitSet ($field, $bit)
//   static bitfield setBit ($field, $bit)
//   static bitfield unsetBit ($field, $bit)
//   static bitfield toggleBit ($field, $bit)
//   static int numBitsSet ($field)

class CBitField extends CRootClass
{
    /**
     * `bitfield` A predefined bitfield where all of the bits are unset (each bit is `0`).
     *
     * @var bitfield
     */
    const ALL_UNSET = 0;

    /**
     * `bitfield` A predefined bitfield where all of the bits are set (each bit is `1`).
     *
     * @var bitfield
     */
    const ALL_SET = 4294967295;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 0 is set.
     *
     * @var bitfield
     */
    const SET_0 = 1;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 1 is set.
     *
     * @var bitfield
     */
    const SET_1 = 2;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 2 is set.
     *
     * @var bitfield
     */
    const SET_2 = 4;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 3 is set.
     *
     * @var bitfield
     */
    const SET_3 = 8;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 4 is set.
     *
     * @var bitfield
     */
    const SET_4 = 16;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 5 is set.
     *
     * @var bitfield
     */
    const SET_5 = 32;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 6 is set.
     *
     * @var bitfield
     */
    const SET_6 = 64;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 7 is set.
     *
     * @var bitfield
     */
    const SET_7 = 128;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 8 is set.
     *
     * @var bitfield
     */
    const SET_8 = 256;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 9 is set.
     *
     * @var bitfield
     */
    const SET_9 = 512;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 10 is set.
     *
     * @var bitfield
     */
    const SET_10 = 1024;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 11 is set.
     *
     * @var bitfield
     */
    const SET_11 = 2048;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 12 is set.
     *
     * @var bitfield
     */
    const SET_12 = 4096;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 13 is set.
     *
     * @var bitfield
     */
    const SET_13 = 8192;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 14 is set.
     *
     * @var bitfield
     */
    const SET_14 = 16384;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 15 is set.
     *
     * @var bitfield
     */
    const SET_15 = 32768;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 16 is set.
     *
     * @var bitfield
     */
    const SET_16 = 65536;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 17 is set.
     *
     * @var bitfield
     */
    const SET_17 = 131072;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 18 is set.
     *
     * @var bitfield
     */
    const SET_18 = 262144;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 19 is set.
     *
     * @var bitfield
     */
    const SET_19 = 524288;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 20 is set.
     *
     * @var bitfield
     */
    const SET_20 = 1048576;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 21 is set.
     *
     * @var bitfield
     */
    const SET_21 = 2097152;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 22 is set.
     *
     * @var bitfield
     */
    const SET_22 = 4194304;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 23 is set.
     *
     * @var bitfield
     */
    const SET_23 = 8388608;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 24 is set.
     *
     * @var bitfield
     */
    const SET_24 = 16777216;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 25 is set.
     *
     * @var bitfield
     */
    const SET_25 = 33554432;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 26 is set.
     *
     * @var bitfield
     */
    const SET_26 = 67108864;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 27 is set.
     *
     * @var bitfield
     */
    const SET_27 = 134217728;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 28 is set.
     *
     * @var bitfield
     */
    const SET_28 = 268435456;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 29 is set.
     *
     * @var bitfield
     */
    const SET_29 = 536870912;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 30 is set.
     *
     * @var bitfield
     */
    const SET_30 = 1073741824;

    /**
     * `bitfield` A predefined bitfield where only the bit at the position of 31 is set.
     *
     * @var bitfield
     */
    const SET_31 = 2147483648;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a bit is set in a bitfield.
     *
     * @param  bitfield $field The bitfield to be looked into.
     * @param  bitfield $bit A bitfield where exactly one bit is set, which is the bit to be tested against the bits
     * in `$field`. This is something you often get as a constant e.g. `SOME_OPTION` and you test it against e.g.
     * `AVAILABLE_OPTIONS` to see if the option is enabled.
     *
     * @return bool `true` if the bit is set in the bitfield, `false` otherwise.
     */

    public static function isBitSet ($field, $bit)
    {
        assert( 'is_bitfield($field) && is_bitfield($bit)', vs(isset($this), get_defined_vars()) );
        return ( ($field & $bit) != 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets a bit in a bitfield and returns the new bitfield.
     *
     * @param  bitfield $field The bitfield to be modified.
     * @param  bitfield $bit A bitfield where exactly one bit is set, which indicates the position of the bit to be
     * set in `$field`.
     *
     * @return bitfield The new bitfield.
     */

    public static function setBit ($field, $bit)
    {
        assert( 'is_bitfield($field) && is_bitfield($bit)', vs(isset($this), get_defined_vars()) );
        return $field | $bit;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Unsets a bit in a bitfield and returns the new bitfield.
     *
     * @param  bitfield $field The bitfield to be modified.
     * @param  bitfield $bit A bitfield where exactly one bit is set, which indicates the position of the bit to be
     * unset in `$field`.
     *
     * @return bitfield The new bitfield.
     */

    public static function unsetBit ($field, $bit)
    {
        assert( 'is_bitfield($field) && is_bitfield($bit)', vs(isset($this), get_defined_vars()) );
        return $field & ~$bit;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Toggles the value of a bit in a bitfield and returns the new bitfield.
     *
     * If the bit is `0` (unset), it becomes `1` (set), and vice versa.
     *
     * @param  bitfield $field The bitfield to be modified.
     * @param  bitfield $bit A bitfield where exactly one bit is set, which indicates the position of the bit to be
     * toggled in `$field`.
     *
     * @return bitfield The new bitfield.
     */

    public static function toggleBit ($field, $bit)
    {
        assert( 'is_bitfield($field) && is_bitfield($bit)', vs(isset($this), get_defined_vars()) );
        return $field ^ $bit;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Counts the number of bits that are set in a bitfield.
     *
     * @param  bitfield $field The bitfield to be looked into.
     *
     * @return int The number of bits set.
     */

    public static function numBitsSet ($field)
    {
        assert( 'is_bitfield($field)', vs(isset($this), get_defined_vars()) );

        $quantity = 0;
        while ( $field != 0 )
        {
            $quantity += $field & 1;
            $field >>= 1;
        }
        return $quantity;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
