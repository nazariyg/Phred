<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A collection of static methods that convert amounts of digital storage, time, length, speed, temperature, area,
 * volume, and mass from one measurement unit into another.
 *
 * Those methods that end with an "i" take an integer value for input and return an integer value for output. Likewise,
 * those methods that end with an "f" take a floating-point value for input and return a floating-point value for
 * output. Integer conversion works best when the source unit is known to be integrally representable in the
 * destination unit without any (or little) loss of information, e.g. when converting from megabytes to bytes, while
 * floating-point conversion is usually more preferable in other cases.
 */

// Method signatures:
//   static int convertStoragei ($iQuantity, $eFromUnit, $eToUnit)
//   static float convertStoragef ($fQuantity, $eFromUnit, $eToUnit)
//   static int convertTimei ($iQuantity, $eFromUnit, $eToUnit)
//   static float convertTimef ($fQuantity, $eFromUnit, $eToUnit)
//   static int convertLengthi ($iQuantity, $eFromUnit, $eToUnit)
//   static float convertLengthf ($fQuantity, $eFromUnit, $eToUnit)
//   static int convertSpeedi ($iQuantity, $eFromUnit, $eToUnit)
//   static float convertSpeedf ($fQuantity, $eFromUnit, $eToUnit)
//   static int convertTemperaturei ($iQuantity, $eFromUnit, $eToUnit)
//   static float convertTemperaturef ($fQuantity, $eFromUnit, $eToUnit)
//   static int convertAreai ($iQuantity, $eFromUnit, $eToUnit)
//   static float convertAreaf ($fQuantity, $eFromUnit, $eToUnit)
//   static int convertVolumei ($iQuantity, $eFromUnit, $eToUnit)
//   static float convertVolumef ($fQuantity, $eFromUnit, $eToUnit)
//   static int convertMassi ($iQuantity, $eFromUnit, $eToUnit)
//   static float convertMassf ($fQuantity, $eFromUnit, $eToUnit)

class CUUnit extends CRootClass
{
    // Storage.
    /**
     * `enum` Bit.
     *
     * @var enum
     */
    const BIT = 0;
    /**
     * `enum` Byte.
     *
     * @var enum
     */
    const BYTE = 1;
    /**
     * `enum` Kilobit.
     *
     * @var enum
     */
    const KILOBIT = 2;
    /**
     * `enum` Kilobyte.
     *
     * @var enum
     */
    const KILOBYTE = 3;
    /**
     * `enum` Megabit.
     *
     * @var enum
     */
    const MEGABIT = 4;
    /**
     * `enum` Megabyte.
     *
     * @var enum
     */
    const MEGABYTE = 5;
    /**
     * `enum` Gigabit.
     *
     * @var enum
     */
    const GIGABIT = 6;
    /**
     * `enum` Gigabyte.
     *
     * @var enum
     */
    const GIGABYTE = 7;
    /**
     * `enum` Terabit.
     *
     * @var enum
     */
    const TERABIT = 8;
    /**
     * `enum` Terabyte.
     *
     * @var enum
     */
    const TERABYTE = 9;
    /**
     * `enum` Petabit.
     *
     * @var enum
     */
    const PETABIT = 10;
    /**
     * `enum` Petabyte.
     *
     * @var enum
     */
    const PETABYTE = 11;

    // Time.
    /**
     * `enum` Millisecond.
     *
     * @var enum
     */
    const MILLISECOND = 0;
    /**
     * `enum` Second.
     *
     * @var enum
     */
    const SECOND = 1;
    /**
     * `enum` Minute.
     *
     * @var enum
     */
    const MINUTE = 2;
    /**
     * `enum` Hour.
     *
     * @var enum
     */
    const HOUR = 3;
    /**
     * `enum` Day.
     *
     * @var enum
     */
    const DAY = 4;
    /**
     * `enum` Week.
     *
     * @var enum
     */
    const WEEK = 5;
    /**
     * `enum` Month.
     *
     * @var enum
     */
    const MONTH = 6;
    /**
     * `enum` Year.
     *
     * @var enum
     */
    const YEAR = 7;
    /**
     * `enum` Decade.
     *
     * @var enum
     */
    const DECADE = 8;

    // Length.
    /**
     * `enum` Millimeter.
     *
     * @var enum
     */
    const MILLIMETER = 0;
    /**
     * `enum` Centimeter.
     *
     * @var enum
     */
    const CENTIMETER = 1;
    /**
     * `enum` Meter.
     *
     * @var enum
     */
    const METER = 2;
    /**
     * `enum` Kilometer.
     *
     * @var enum
     */
    const KILOMETER = 3;
    /**
     * `enum` Inch.
     *
     * @var enum
     */
    const INCH = 4;
    /**
     * `enum` Foot.
     *
     * @var enum
     */
    const FOOT = 5;
    /**
     * `enum` Yard.
     *
     * @var enum
     */
    const YARD = 6;
    /**
     * `enum` Mile.
     *
     * @var enum
     */
    const MILE = 7;
    /**
     * `enum` Nautical mile.
     *
     * @var enum
     */
    const NAUTICAL_MILE = 8;

    // Speed.
    /**
     * `enum` Meters per second.
     *
     * @var enum
     */
    const METERS_PER_SECOND = 0;
    /**
     * `enum` Kilometers per hour.
     *
     * @var enum
     */
    const KILOMETERS_PER_HOUR = 1;
    /**
     * `enum` Feet per second.
     *
     * @var enum
     */
    const FEET_PER_SECOND = 2;
    /**
     * `enum` Miles per hour.
     *
     * @var enum
     */
    const MILES_PER_HOUR = 3;
    /**
     * `enum` Knot.
     *
     * @var enum
     */
    const KNOT = 4;

    // Temperature.
    /**
     * `enum` Celsius.
     *
     * @var enum
     */
    const CELSIUS = 0;
    /**
     * `enum` Fahrenheit.
     *
     * @var enum
     */
    const FAHRENHEIT = 1;
    /**
     * `enum` Kelvin.
     *
     * @var enum
     */
    const KELVIN = 2;

    // Area.
    /**
     * `enum` Square meter.
     *
     * @var enum
     */
    const SQUARE_METER = 0;
    /**
     * `enum` Hectare.
     *
     * @var enum
     */
    const HECTARE = 1;
    /**
     * `enum` Square kilometer.
     *
     * @var enum
     */
    const SQUARE_KILOMETER = 2;
    /**
     * `enum` Square inch.
     *
     * @var enum
     */
    const SQUARE_INCH = 3;
    /**
     * `enum` Square foot.
     *
     * @var enum
     */
    const SQUARE_FOOT = 4;
    /**
     * `enum` Square yard.
     *
     * @var enum
     */
    const SQUARE_YARD = 5;
    /**
     * `enum` Acre.
     *
     * @var enum
     */
    const ACRE = 6;
    /**
     * `enum` Square mile.
     *
     * @var enum
     */
    const SQUARE_MILE = 7;

    // Volume.
    /**
     * `enum` Milliliter.
     *
     * @var enum
     */
    const MILLILITER = 0;
    /**
     * `enum` Liter.
     *
     * @var enum
     */
    const LITER = 1;
    /**
     * `enum` Cubic meter.
     *
     * @var enum
     */
    const CUBIC_METER = 2;
    /**
     * `enum` Cubic inch.
     *
     * @var enum
     */
    const CUBIC_INCH = 3;
    /**
     * `enum` Cubic foot.
     *
     * @var enum
     */
    const CUBIC_FOOT = 4;
    /**
     * `enum` U.S. teaspoon.
     *
     * @var enum
     */
    const US_TEASPOON = 5;
    /**
     * `enum` U.S. tablespoon.
     *
     * @var enum
     */
    const US_TABLESPOON = 6;
    /**
     * `enum` U.S. fluid ounce.
     *
     * @var enum
     */
    const US_FLUID_OUNCE = 7;
    /**
     * `enum` U.S. cup.
     *
     * @var enum
     */
    const US_CUP = 8;
    /**
     * `enum` U.S. pint.
     *
     * @var enum
     */
    const US_PINT = 9;
    /**
     * `enum` U.S. quart.
     *
     * @var enum
     */
    const US_QUART = 10;
    /**
     * `enum` U.S. gallon.
     *
     * @var enum
     */
    const US_GALLON = 11;
    /**
     * `enum` Imperial teaspoon.
     *
     * @var enum
     */
    const IMPERIAL_TEASPOON = 12;
    /**
     * `enum` Imperial tablespoon.
     *
     * @var enum
     */
    const IMPERIAL_TABLESPOON = 13;
    /**
     * `enum` Imperial fluid ounce.
     *
     * @var enum
     */
    const IMPERIAL_FLUID_OUNCE = 14;
    /**
     * `enum` Imperial pint.
     *
     * @var enum
     */
    const IMPERIAL_PINT = 15;
    /**
     * `enum` Imperial quart.
     *
     * @var enum
     */
    const IMPERIAL_QUART = 16;
    /**
     * `enum` Imperial gallon.
     *
     * @var enum
     */
    const IMPERIAL_GALLON = 17;

    // Mass.
    /**
     * `enum` Milligram.
     *
     * @var enum
     */
    const MILLIGRAM = 0;
    /**
     * `enum` Gram.
     *
     * @var enum
     */
    const GRAM = 1;
    /**
     * `enum` Kilogram.
     *
     * @var enum
     */
    const KILOGRAM = 2;
    /**
     * `enum` Ton.
     *
     * @var enum
     */
    const TON = 3;
    /**
     * `enum` Ounce.
     *
     * @var enum
     */
    const OUNCE = 4;
    /**
     * `enum` Pound.
     *
     * @var enum
     */
    const POUND = 5;
    /**
     * `enum` Stone.
     *
     * @var enum
     */
    const STONE = 6;
    /**
     * `enum` Short ton.
     *
     * @var enum
     */
    const SHORT_TON = 7;
    /**
     * `enum` Long ton.
     *
     * @var enum
     */
    const LONG_TON = 8;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of digital storage from one unit into another and returns the result.
     *
     * @param  int $iQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertStoragei ($iQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_int($iQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $iQuantity;
        }

        $iBitQty;
        switch ( $eFromUnit )
        {
        case self::BIT:
            $iBitQty = $iQuantity;
            break;
        case self::BYTE:
            $iBitQty = $iQuantity*8;
            break;
        case self::KILOBIT:
            $iBitQty = $iQuantity*1024;
            break;
        case self::KILOBYTE:
            $iBitQty = $iQuantity*8192;
            break;
        case self::MEGABIT:
            $iBitQty = $iQuantity*1048576;
            break;
        case self::MEGABYTE:
            $iBitQty = $iQuantity*8388608;
            break;
        case self::GIGABIT:
            $iBitQty = $iQuantity*1073741824;
            break;
        case self::GIGABYTE:
            $iBitQty = $iQuantity*8589934592;
            break;
        case self::TERABIT:
            $iBitQty = $iQuantity*1099511627776;
            break;
        case self::TERABYTE:
            $iBitQty = $iQuantity*8796093022208;
            break;
        case self::PETABIT:
            $iBitQty = $iQuantity*1125899906842624;
            break;
        case self::PETABYTE:
            $iBitQty = $iQuantity*9007199254740992;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $iOutputQty;
        switch ( $eToUnit )
        {
        case self::BIT:
            $iOutputQty = $iBitQty;
            break;
        case self::BYTE:
            $iOutputQty = CMathi::round(((float)$iBitQty)/8);
            break;
        case self::KILOBIT:
            $iOutputQty = CMathi::round(((float)$iBitQty)/1024);
            break;
        case self::KILOBYTE:
            $iOutputQty = CMathi::round(((float)$iBitQty)/8192);
            break;
        case self::MEGABIT:
            $iOutputQty = CMathi::round(((float)$iBitQty)/1048576);
            break;
        case self::MEGABYTE:
            $iOutputQty = CMathi::round(((float)$iBitQty)/8388608);
            break;
        case self::GIGABIT:
            $iOutputQty = CMathi::round(((float)$iBitQty)/1073741824);
            break;
        case self::GIGABYTE:
            $iOutputQty = CMathi::round(((float)$iBitQty)/8589934592);
            break;
        case self::TERABIT:
            $iOutputQty = CMathi::round(((float)$iBitQty)/1099511627776);
            break;
        case self::TERABYTE:
            $iOutputQty = CMathi::round(((float)$iBitQty)/8796093022208);
            break;
        case self::PETABIT:
            $iOutputQty = CMathi::round(((float)$iBitQty)/1125899906842624);
            break;
        case self::PETABYTE:
            $iOutputQty = CMathi::round(((float)$iBitQty)/9007199254740992);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $iOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of digital storage from one unit into another and returns the result.
     *
     * @param  float $fQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertStoragef ($fQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_float($fQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$fQuantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $fQuantity;
        }

        $fBitQty;
        switch ( $eFromUnit )
        {
        case self::BIT:
            $fBitQty = $fQuantity;
            break;
        case self::BYTE:
            $fBitQty = $fQuantity*8;
            break;
        case self::KILOBIT:
            $fBitQty = $fQuantity*1024;
            break;
        case self::KILOBYTE:
            $fBitQty = $fQuantity*8192;
            break;
        case self::MEGABIT:
            $fBitQty = $fQuantity*1048576;
            break;
        case self::MEGABYTE:
            $fBitQty = $fQuantity*8388608;
            break;
        case self::GIGABIT:
            $fBitQty = $fQuantity*1073741824;
            break;
        case self::GIGABYTE:
            $fBitQty = $fQuantity*8589934592;
            break;
        case self::TERABIT:
            $fBitQty = $fQuantity*1099511627776;
            break;
        case self::TERABYTE:
            $fBitQty = $fQuantity*8796093022208;
            break;
        case self::PETABIT:
            $fBitQty = $fQuantity*1125899906842624;
            break;
        case self::PETABYTE:
            $fBitQty = $fQuantity*9007199254740992;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $fOutputQty;
        switch ( $eToUnit )
        {
        case self::BIT:
            $fOutputQty = $fBitQty;
            break;
        case self::BYTE:
            $fOutputQty = $fBitQty/8;
            break;
        case self::KILOBIT:
            $fOutputQty = $fBitQty/1024;
            break;
        case self::KILOBYTE:
            $fOutputQty = $fBitQty/8192;
            break;
        case self::MEGABIT:
            $fOutputQty = $fBitQty/1048576;
            break;
        case self::MEGABYTE:
            $fOutputQty = $fBitQty/8388608;
            break;
        case self::GIGABIT:
            $fOutputQty = $fBitQty/1073741824;
            break;
        case self::GIGABYTE:
            $fOutputQty = $fBitQty/8589934592;
            break;
        case self::TERABIT:
            $fOutputQty = $fBitQty/1099511627776;
            break;
        case self::TERABYTE:
            $fOutputQty = $fBitQty/8796093022208;
            break;
        case self::PETABIT:
            $fOutputQty = $fBitQty/1125899906842624;
            break;
        case self::PETABYTE:
            $fOutputQty = $fBitQty/9007199254740992;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $fOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of time from one unit into another and returns the result.
     *
     * @param  int $iQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertTimei ($iQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_int($iQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $iQuantity;
        }

        $iMillisecondQty;
        switch ( $eFromUnit )
        {
        case self::MILLISECOND:
            $iMillisecondQty = $iQuantity;
            break;
        case self::SECOND:
            $iMillisecondQty = $iQuantity*1000;
            break;
        case self::MINUTE:
            $iMillisecondQty = $iQuantity*1000*CTime::SECONDS_PER_MINUTE;
            break;
        case self::HOUR:
            $iMillisecondQty = $iQuantity*1000*CTime::SECONDS_PER_HOUR;
            break;
        case self::DAY:
            $iMillisecondQty = $iQuantity*1000*CTime::SECONDS_PER_DAY;
            break;
        case self::WEEK:
            $iMillisecondQty = $iQuantity*1000*CTime::SECONDS_PER_WEEK;
            break;
        case self::MONTH:
            $iMillisecondQty = $iQuantity*1000*CTime::SECONDS_PER_MONTH;
            break;
        case self::YEAR:
            $iMillisecondQty = $iQuantity*1000*CTime::SECONDS_PER_YEAR;
            break;
        case self::DECADE:
            $iMillisecondQty = $iQuantity*1000*CTime::SECONDS_PER_YEAR*10;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $iOutputQty;
        switch ( $eToUnit )
        {
        case self::MILLISECOND:
            $iOutputQty = $iMillisecondQty;
            break;
        case self::SECOND:
            $iOutputQty = CMathi::round(((float)$iMillisecondQty)/1000);
            break;
        case self::MINUTE:
            $iOutputQty = CMathi::round(((float)$iMillisecondQty)/(1000*CTime::SECONDS_PER_MINUTE));
            break;
        case self::HOUR:
            $iOutputQty = CMathi::round(((float)$iMillisecondQty)/(1000*CTime::SECONDS_PER_HOUR));
            break;
        case self::DAY:
            $iOutputQty = CMathi::round(((float)$iMillisecondQty)/(1000*CTime::SECONDS_PER_DAY));
            break;
        case self::WEEK:
            $iOutputQty = CMathi::round(((float)$iMillisecondQty)/(1000*CTime::SECONDS_PER_WEEK));
            break;
        case self::MONTH:
            $iOutputQty = CMathi::round(((float)$iMillisecondQty)/(1000*CTime::SECONDS_PER_MONTH));
            break;
        case self::YEAR:
            $iOutputQty = CMathi::round(((float)$iMillisecondQty)/(1000*CTime::SECONDS_PER_YEAR));
            break;
        case self::DECADE:
            $iOutputQty = CMathi::round(((float)$iMillisecondQty)/(1000*CTime::SECONDS_PER_YEAR*10));
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $iOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of time from one unit into another and returns the result.
     *
     * @param  float $fQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertTimef ($fQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_float($fQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$fQuantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $fQuantity;
        }

        $fMillisecondQty;
        switch ( $eFromUnit )
        {
        case self::MILLISECOND:
            $fMillisecondQty = $fQuantity;
            break;
        case self::SECOND:
            $fMillisecondQty = $fQuantity*1000;
            break;
        case self::MINUTE:
            $fMillisecondQty = $fQuantity*1000*CTime::SECONDS_PER_MINUTE;
            break;
        case self::HOUR:
            $fMillisecondQty = $fQuantity*1000*CTime::SECONDS_PER_HOUR;
            break;
        case self::DAY:
            $fMillisecondQty = $fQuantity*1000*CTime::SECONDS_PER_DAY;
            break;
        case self::WEEK:
            $fMillisecondQty = $fQuantity*1000*CTime::SECONDS_PER_WEEK;
            break;
        case self::MONTH:
            $fMillisecondQty = $fQuantity*1000*CTime::SECONDS_PER_MONTH;
            break;
        case self::YEAR:
            $fMillisecondQty = $fQuantity*1000*CTime::SECONDS_PER_YEAR;
            break;
        case self::DECADE:
            $fMillisecondQty = $fQuantity*1000*CTime::SECONDS_PER_YEAR*10;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $fOutputQty;
        switch ( $eToUnit )
        {
        case self::MILLISECOND:
            $fOutputQty = $fMillisecondQty;
            break;
        case self::SECOND:
            $fOutputQty = $fMillisecondQty/1000;
            break;
        case self::MINUTE:
            $fOutputQty = $fMillisecondQty/(1000*CTime::SECONDS_PER_MINUTE);
            break;
        case self::HOUR:
            $fOutputQty = $fMillisecondQty/(1000*CTime::SECONDS_PER_HOUR);
            break;
        case self::DAY:
            $fOutputQty = $fMillisecondQty/(1000*CTime::SECONDS_PER_DAY);
            break;
        case self::WEEK:
            $fOutputQty = $fMillisecondQty/(1000*CTime::SECONDS_PER_WEEK);
            break;
        case self::MONTH:
            $fOutputQty = $fMillisecondQty/(1000*CTime::SECONDS_PER_MONTH);
            break;
        case self::YEAR:
            $fOutputQty = $fMillisecondQty/(1000*CTime::SECONDS_PER_YEAR);
            break;
        case self::DECADE:
            $fOutputQty = $fMillisecondQty/(1000*CTime::SECONDS_PER_YEAR*10);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $fOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of length from one unit into another and returns the result.
     *
     * @param  int $iQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertLengthi ($iQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_int($iQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $iQuantity;
        }

        $fQuantity = (float)$iQuantity;

        $fMillimeterQty;
        switch ( $eFromUnit )
        {
        case self::MILLIMETER:
            $fMillimeterQty = $fQuantity;
            break;
        case self::CENTIMETER:
            $fMillimeterQty = $fQuantity*10;
            break;
        case self::METER:
            $fMillimeterQty = $fQuantity*1000;
            break;
        case self::KILOMETER:
            $fMillimeterQty = $fQuantity*1000000;
            break;
        case self::INCH:
            $fMillimeterQty = $fQuantity*25.4001;
            break;
        case self::FOOT:
            $fMillimeterQty = $fQuantity*304.8;
            break;
        case self::YARD:
            $fMillimeterQty = $fQuantity*914.4;
            break;
        case self::MILE:
            $fMillimeterQty = $fQuantity*1609344;
            break;
        case self::NAUTICAL_MILE:
            $fMillimeterQty = $fQuantity*1852000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $iOutputQty;
        switch ( $eToUnit )
        {
        case self::MILLIMETER:
            $iOutputQty = CMathi::round($fMillimeterQty);
            break;
        case self::CENTIMETER:
            $iOutputQty = CMathi::round($fMillimeterQty/10);
            break;
        case self::METER:
            $iOutputQty = CMathi::round($fMillimeterQty/1000);
            break;
        case self::KILOMETER:
            $iOutputQty = CMathi::round($fMillimeterQty/1000000);
            break;
        case self::INCH:
            $iOutputQty = CMathi::round($fMillimeterQty/25.4001);
            break;
        case self::FOOT:
            $iOutputQty = CMathi::round($fMillimeterQty/304.8);
            break;
        case self::YARD:
            $iOutputQty = CMathi::round($fMillimeterQty/914.4);
            break;
        case self::MILE:
            $iOutputQty = CMathi::round($fMillimeterQty/1609344);
            break;
        case self::NAUTICAL_MILE:
            $iOutputQty = CMathi::round($fMillimeterQty/1852000);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $iOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of length from one unit into another and returns the result.
     *
     * @param  float $fQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertLengthf ($fQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_float($fQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$fQuantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $fQuantity;
        }

        $fMillimeterQty;
        switch ( $eFromUnit )
        {
        case self::MILLIMETER:
            $fMillimeterQty = $fQuantity;
            break;
        case self::CENTIMETER:
            $fMillimeterQty = $fQuantity*10;
            break;
        case self::METER:
            $fMillimeterQty = $fQuantity*1000;
            break;
        case self::KILOMETER:
            $fMillimeterQty = $fQuantity*1000000;
            break;
        case self::INCH:
            $fMillimeterQty = $fQuantity*25.4001;
            break;
        case self::FOOT:
            $fMillimeterQty = $fQuantity*304.8;
            break;
        case self::YARD:
            $fMillimeterQty = $fQuantity*914.4;
            break;
        case self::MILE:
            $fMillimeterQty = $fQuantity*1609344;
            break;
        case self::NAUTICAL_MILE:
            $fMillimeterQty = $fQuantity*1852000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $fOutputQty;
        switch ( $eToUnit )
        {
        case self::MILLIMETER:
            $fOutputQty = $fMillimeterQty;
            break;
        case self::CENTIMETER:
            $fOutputQty = $fMillimeterQty/10;
            break;
        case self::METER:
            $fOutputQty = $fMillimeterQty/1000;
            break;
        case self::KILOMETER:
            $fOutputQty = $fMillimeterQty/1000000;
            break;
        case self::INCH:
            $fOutputQty = $fMillimeterQty/25.4001;
            break;
        case self::FOOT:
            $fOutputQty = $fMillimeterQty/304.8;
            break;
        case self::YARD:
            $fOutputQty = $fMillimeterQty/914.4;
            break;
        case self::MILE:
            $fOutputQty = $fMillimeterQty/1609344;
            break;
        case self::NAUTICAL_MILE:
            $fOutputQty = $fMillimeterQty/1852000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $fOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of speed from one unit into another and returns the result.
     *
     * @param  int $iQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertSpeedi ($iQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_int($iQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $iQuantity;
        }

        $iCentimetersPerHourQty;
        switch ( $eFromUnit )
        {
        case self::METERS_PER_SECOND:
            $iCentimetersPerHourQty = $iQuantity*360000;
            break;
        case self::KILOMETERS_PER_HOUR:
            $iCentimetersPerHourQty = $iQuantity*100000;
            break;
        case self::FEET_PER_SECOND:
            $iCentimetersPerHourQty = $iQuantity*109728;
            break;
        case self::MILES_PER_HOUR:
            $iCentimetersPerHourQty = $iQuantity*160935;
            break;
        case self::KNOT:
            $iCentimetersPerHourQty = $iQuantity*185200;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $iOutputQty;
        switch ( $eToUnit )
        {
        case self::METERS_PER_SECOND:
            $iOutputQty = CMathi::round(((float)$iCentimetersPerHourQty)/360000);
            break;
        case self::KILOMETERS_PER_HOUR:
            $iOutputQty = CMathi::round(((float)$iCentimetersPerHourQty)/100000);
            break;
        case self::FEET_PER_SECOND:
            $iOutputQty = CMathi::round(((float)$iCentimetersPerHourQty)/109728);
            break;
        case self::MILES_PER_HOUR:
            $iOutputQty = CMathi::round(((float)$iCentimetersPerHourQty)/160935);
            break;
        case self::KNOT:
            $iOutputQty = CMathi::round(((float)$iCentimetersPerHourQty)/185200);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $iOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of speed from one unit into another and returns the result.
     *
     * @param  float $fQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertSpeedf ($fQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_float($fQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$fQuantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $fQuantity;
        }

        $fCentimetersPerHourQty;
        switch ( $eFromUnit )
        {
        case self::METERS_PER_SECOND:
            $fCentimetersPerHourQty = $fQuantity*360000;
            break;
        case self::KILOMETERS_PER_HOUR:
            $fCentimetersPerHourQty = $fQuantity*100000;
            break;
        case self::FEET_PER_SECOND:
            $fCentimetersPerHourQty = $fQuantity*109728;
            break;
        case self::MILES_PER_HOUR:
            $fCentimetersPerHourQty = $fQuantity*160935;
            break;
        case self::KNOT:
            $fCentimetersPerHourQty = $fQuantity*185200;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $fOutputQty;
        switch ( $eToUnit )
        {
        case self::METERS_PER_SECOND:
            $fOutputQty = $fCentimetersPerHourQty/360000;
            break;
        case self::KILOMETERS_PER_HOUR:
            $fOutputQty = $fCentimetersPerHourQty/100000;
            break;
        case self::FEET_PER_SECOND:
            $fOutputQty = $fCentimetersPerHourQty/109728;
            break;
        case self::MILES_PER_HOUR:
            $fOutputQty = $fCentimetersPerHourQty/160935;
            break;
        case self::KNOT:
            $fOutputQty = $fCentimetersPerHourQty/185200;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $fOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of temperature from one unit into another and returns the result.
     *
     * @param  int $iQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertTemperaturei ($iQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_int($iQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $iQuantity;
        }

        $fQuantity = (float)$iQuantity;

        $fKelvinQty;
        switch ( $eFromUnit )
        {
        case self::CELSIUS:
            $fKelvinQty = $fQuantity + 273.15;
            break;
        case self::FAHRENHEIT:
            $fKelvinQty = ($fQuantity + 459.67)*5/9;
            break;
        case self::KELVIN:
            $fKelvinQty = $fQuantity;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $iOutputQty;
        switch ( $eToUnit )
        {
        case self::CELSIUS:
            $iOutputQty = CMathi::round($fKelvinQty - 273.15);
            break;
        case self::FAHRENHEIT:
            $iOutputQty = CMathi::round($fKelvinQty*9/5 - 459.67);
            break;
        case self::KELVIN:
            $iOutputQty = CMathi::round($fKelvinQty);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $iOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of temperature from one unit into another and returns the result.
     *
     * @param  float $fQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertTemperaturef ($fQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_float($fQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $fQuantity;
        }

        $fKelvinQty;
        switch ( $eFromUnit )
        {
        case self::CELSIUS:
            $fKelvinQty = $fQuantity + 273.15;
            break;
        case self::FAHRENHEIT:
            $fKelvinQty = ($fQuantity + 459.67)*5/9;
            break;
        case self::KELVIN:
            $fKelvinQty = $fQuantity;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $fOutputQty;
        switch ( $eToUnit )
        {
        case self::CELSIUS:
            $fOutputQty = $fKelvinQty - 273.15;
            break;
        case self::FAHRENHEIT:
            $fOutputQty = $fKelvinQty*9/5 - 459.67;
            break;
        case self::KELVIN:
            $fOutputQty = $fKelvinQty;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $fOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of area from one unit into another and returns the result.
     *
     * @param  int $iQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertAreai ($iQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_int($iQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $iQuantity;
        }

        $fQuantity = (float)$iQuantity;

        $fSquareCentimeterQty;
        switch ( $eFromUnit )
        {
        case self::SQUARE_METER:
            $fSquareCentimeterQty = $fQuantity*10000;
            break;
        case self::HECTARE:
            $fSquareCentimeterQty = $fQuantity*100000000;
            break;
        case self::SQUARE_KILOMETER:
            $fSquareCentimeterQty = $fQuantity*10000000000;
            break;
        case self::SQUARE_INCH:
            $fSquareCentimeterQty = $fQuantity*6.4516;
            break;
        case self::SQUARE_FOOT:
            $fSquareCentimeterQty = $fQuantity*929.03;
            break;
        case self::SQUARE_YARD:
            $fSquareCentimeterQty = $fQuantity*8361.27;
            break;
        case self::ACRE:
            $fSquareCentimeterQty = $fQuantity*40468600;
            break;
        case self::SQUARE_MILE:
            $fSquareCentimeterQty = $fQuantity*25899900000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $iOutputQty;
        switch ( $eToUnit )
        {
        case self::SQUARE_METER:
            $iOutputQty = CMathi::round($fSquareCentimeterQty/10000);
            break;
        case self::HECTARE:
            $iOutputQty = CMathi::round($fSquareCentimeterQty/100000000);
            break;
        case self::SQUARE_KILOMETER:
            $iOutputQty = CMathi::round($fSquareCentimeterQty/10000000000);
            break;
        case self::SQUARE_INCH:
            $iOutputQty = CMathi::round($fSquareCentimeterQty/6.4516);
            break;
        case self::SQUARE_FOOT:
            $iOutputQty = CMathi::round($fSquareCentimeterQty/929.03);
            break;
        case self::SQUARE_YARD:
            $iOutputQty = CMathi::round($fSquareCentimeterQty/8361.27);
            break;
        case self::ACRE:
            $iOutputQty = CMathi::round($fSquareCentimeterQty/40468600);
            break;
        case self::SQUARE_MILE:
            $iOutputQty = CMathi::round($fSquareCentimeterQty/25899900000);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $iOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of area from one unit into another and returns the result.
     *
     * @param  float $fQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertAreaf ($fQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_float($fQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$fQuantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $fQuantity;
        }

        $fSquareCentimeterQty;
        switch ( $eFromUnit )
        {
        case self::SQUARE_METER:
            $fSquareCentimeterQty = $fQuantity*10000;
            break;
        case self::HECTARE:
            $fSquareCentimeterQty = $fQuantity*100000000;
            break;
        case self::SQUARE_KILOMETER:
            $fSquareCentimeterQty = $fQuantity*10000000000;
            break;
        case self::SQUARE_INCH:
            $fSquareCentimeterQty = $fQuantity*6.4516;
            break;
        case self::SQUARE_FOOT:
            $fSquareCentimeterQty = $fQuantity*929.03;
            break;
        case self::SQUARE_YARD:
            $fSquareCentimeterQty = $fQuantity*8361.27;
            break;
        case self::ACRE:
            $fSquareCentimeterQty = $fQuantity*40468600;
            break;
        case self::SQUARE_MILE:
            $fSquareCentimeterQty = $fQuantity*25899900000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $fOutputQty;
        switch ( $eToUnit )
        {
        case self::SQUARE_METER:
            $fOutputQty = $fSquareCentimeterQty/10000;
            break;
        case self::HECTARE:
            $fOutputQty = $fSquareCentimeterQty/100000000;
            break;
        case self::SQUARE_KILOMETER:
            $fOutputQty = $fSquareCentimeterQty/10000000000;
            break;
        case self::SQUARE_INCH:
            $fOutputQty = $fSquareCentimeterQty/6.4516;
            break;
        case self::SQUARE_FOOT:
            $fOutputQty = $fSquareCentimeterQty/929.03;
            break;
        case self::SQUARE_YARD:
            $fOutputQty = $fSquareCentimeterQty/8361.27;
            break;
        case self::ACRE:
            $fOutputQty = $fSquareCentimeterQty/40468600;
            break;
        case self::SQUARE_MILE:
            $fOutputQty = $fSquareCentimeterQty/25899900000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $fOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of volume from one unit into another and returns the result.
     *
     * @param  int $iQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertVolumei ($iQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_int($iQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $iQuantity;
        }

        $fQuantity = (float)$iQuantity;

        $fMilliliterQty;
        switch ( $eFromUnit )
        {
        case self::MILLILITER:
            $fMilliliterQty = $fQuantity;
            break;
        case self::LITER:
            $fMilliliterQty = $fQuantity*1000;
            break;
        case self::CUBIC_METER:
            $fMilliliterQty = $fQuantity*1000000;
            break;
        case self::CUBIC_INCH:
            $fMilliliterQty = $fQuantity*16.3871;
            break;
        case self::CUBIC_FOOT:
            $fMilliliterQty = $fQuantity*28316.8;
            break;
        case self::US_TEASPOON:
            $fMilliliterQty = $fQuantity*4.928922;
            break;
        case self::US_TABLESPOON:
            $fMilliliterQty = $fQuantity*14.786765;
            break;
        case self::US_FLUID_OUNCE:
            $fMilliliterQty = $fQuantity*29.5735295625;
            break;
        case self::US_CUP:
            $fMilliliterQty = $fQuantity*236.588237;
            break;
        case self::US_PINT:
            $fMilliliterQty = $fQuantity*473.176473;
            break;
        case self::US_QUART:
            $fMilliliterQty = $fQuantity*946.352946;
            break;
        case self::US_GALLON:
            $fMilliliterQty = $fQuantity*3785.411784;
            break;
        case self::IMPERIAL_TEASPOON:
            $fMilliliterQty = $fQuantity*5.91939047;
            break;
        case self::IMPERIAL_TABLESPOON:
            $fMilliliterQty = $fQuantity*17.7581714;
            break;
        case self::IMPERIAL_FLUID_OUNCE:
            $fMilliliterQty = $fQuantity*28.4130742;
            break;
        case self::IMPERIAL_PINT:
            $fMilliliterQty = $fQuantity*568.261485;
            break;
        case self::IMPERIAL_QUART:
            $fMilliliterQty = $fQuantity*1136.52297;
            break;
        case self::IMPERIAL_GALLON:
            $fMilliliterQty = $fQuantity*4546.09188;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $iOutputQty;
        switch ( $eToUnit )
        {
        case self::MILLILITER:
            $iOutputQty = CMathi::round($fMilliliterQty);
            break;
        case self::LITER:
            $iOutputQty = CMathi::round($fMilliliterQty/1000);
            break;
        case self::CUBIC_METER:
            $iOutputQty = CMathi::round($fMilliliterQty/1000000);
            break;
        case self::CUBIC_INCH:
            $iOutputQty = CMathi::round($fMilliliterQty/16.3871);
            break;
        case self::CUBIC_FOOT:
            $iOutputQty = CMathi::round($fMilliliterQty/28316.8);
            break;
        case self::US_TEASPOON:
            $iOutputQty = CMathi::round($fMilliliterQty/4.928922);
            break;
        case self::US_TABLESPOON:
            $iOutputQty = CMathi::round($fMilliliterQty/14.786765);
            break;
        case self::US_FLUID_OUNCE:
            $iOutputQty = CMathi::round($fMilliliterQty/29.5735295625);
            break;
        case self::US_CUP:
            $iOutputQty = CMathi::round($fMilliliterQty/236.588237);
            break;
        case self::US_PINT:
            $iOutputQty = CMathi::round($fMilliliterQty/473.176473);
            break;
        case self::US_QUART:
            $iOutputQty = CMathi::round($fMilliliterQty/946.352946);
            break;
        case self::US_GALLON:
            $iOutputQty = CMathi::round($fMilliliterQty/3785.411784);
            break;
        case self::IMPERIAL_TEASPOON:
            $iOutputQty = CMathi::round($fMilliliterQty/5.91939047);
            break;
        case self::IMPERIAL_TABLESPOON:
            $iOutputQty = CMathi::round($fMilliliterQty/17.7581714);
            break;
        case self::IMPERIAL_FLUID_OUNCE:
            $iOutputQty = CMathi::round($fMilliliterQty/28.4130742);
            break;
        case self::IMPERIAL_PINT:
            $iOutputQty = CMathi::round($fMilliliterQty/568.261485);
            break;
        case self::IMPERIAL_QUART:
            $iOutputQty = CMathi::round($fMilliliterQty/1136.52297);
            break;
        case self::IMPERIAL_GALLON:
            $iOutputQty = CMathi::round($fMilliliterQty/4546.09188);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $iOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of volume from one unit into another and returns the result.
     *
     * @param  float $fQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertVolumef ($fQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_float($fQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$fQuantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $fQuantity;
        }

        $fMilliliterQty;
        switch ( $eFromUnit )
        {
        case self::MILLILITER:
            $fMilliliterQty = $fQuantity;
            break;
        case self::LITER:
            $fMilliliterQty = $fQuantity*1000;
            break;
        case self::CUBIC_METER:
            $fMilliliterQty = $fQuantity*1000000;
            break;
        case self::CUBIC_INCH:
            $fMilliliterQty = $fQuantity*16.3871;
            break;
        case self::CUBIC_FOOT:
            $fMilliliterQty = $fQuantity*28316.8;
            break;
        case self::US_TEASPOON:
            $fMilliliterQty = $fQuantity*4.928922;
            break;
        case self::US_TABLESPOON:
            $fMilliliterQty = $fQuantity*14.786765;
            break;
        case self::US_FLUID_OUNCE:
            $fMilliliterQty = $fQuantity*29.5735295625;
            break;
        case self::US_CUP:
            $fMilliliterQty = $fQuantity*236.588237;
            break;
        case self::US_PINT:
            $fMilliliterQty = $fQuantity*473.176473;
            break;
        case self::US_QUART:
            $fMilliliterQty = $fQuantity*946.352946;
            break;
        case self::US_GALLON:
            $fMilliliterQty = $fQuantity*3785.411784;
            break;
        case self::IMPERIAL_TEASPOON:
            $fMilliliterQty = $fQuantity*5.91939047;
            break;
        case self::IMPERIAL_TABLESPOON:
            $fMilliliterQty = $fQuantity*17.7581714;
            break;
        case self::IMPERIAL_FLUID_OUNCE:
            $fMilliliterQty = $fQuantity*28.4130742;
            break;
        case self::IMPERIAL_PINT:
            $fMilliliterQty = $fQuantity*568.261485;
            break;
        case self::IMPERIAL_QUART:
            $fMilliliterQty = $fQuantity*1136.52297;
            break;
        case self::IMPERIAL_GALLON:
            $fMilliliterQty = $fQuantity*4546.09188;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $fOutputQty;
        switch ( $eToUnit )
        {
        case self::MILLILITER:
            $fOutputQty = $fMilliliterQty;
            break;
        case self::LITER:
            $fOutputQty = $fMilliliterQty/1000;
            break;
        case self::CUBIC_METER:
            $fOutputQty = $fMilliliterQty/1000000;
            break;
        case self::CUBIC_INCH:
            $fOutputQty = $fMilliliterQty/16.3871;
            break;
        case self::CUBIC_FOOT:
            $fOutputQty = $fMilliliterQty/28316.8;
            break;
        case self::US_TEASPOON:
            $fOutputQty = $fMilliliterQty/4.928922;
            break;
        case self::US_TABLESPOON:
            $fOutputQty = $fMilliliterQty/14.786765;
            break;
        case self::US_FLUID_OUNCE:
            $fOutputQty = $fMilliliterQty/29.5735295625;
            break;
        case self::US_CUP:
            $fOutputQty = $fMilliliterQty/236.588237;
            break;
        case self::US_PINT:
            $fOutputQty = $fMilliliterQty/473.176473;
            break;
        case self::US_QUART:
            $fOutputQty = $fMilliliterQty/946.352946;
            break;
        case self::US_GALLON:
            $fOutputQty = $fMilliliterQty/3785.411784;
            break;
        case self::IMPERIAL_TEASPOON:
            $fOutputQty = $fMilliliterQty/5.91939047;
            break;
        case self::IMPERIAL_TABLESPOON:
            $fOutputQty = $fMilliliterQty/17.7581714;
            break;
        case self::IMPERIAL_FLUID_OUNCE:
            $fOutputQty = $fMilliliterQty/28.4130742;
            break;
        case self::IMPERIAL_PINT:
            $fOutputQty = $fMilliliterQty/568.261485;
            break;
        case self::IMPERIAL_QUART:
            $fOutputQty = $fMilliliterQty/1136.52297;
            break;
        case self::IMPERIAL_GALLON:
            $fOutputQty = $fMilliliterQty/4546.09188;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $fOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of mass from one unit into another and returns the result.
     *
     * @param  int $iQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertMassi ($iQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_int($iQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $iQuantity;
        }

        $fQuantity = (float)$iQuantity;

        $fMilligramQty;
        switch ( $eFromUnit )
        {
        case self::MILLIGRAM:
            $fMilligramQty = $fQuantity;
            break;
        case self::GRAM:
            $fMilligramQty = $fQuantity*1000;
            break;
        case self::KILOGRAM:
            $fMilligramQty = $fQuantity*1000000;
            break;
        case self::TON:
            $fMilligramQty = $fQuantity*1000000000;
            break;
        case self::OUNCE:
            $fMilligramQty = $fQuantity*28349.5231;
            break;
        case self::POUND:
            $fMilligramQty = $fQuantity*453592.37;
            break;
        case self::STONE:
            $fMilligramQty = $fQuantity*6350293.18;
            break;
        case self::SHORT_TON:
            $fMilligramQty = $fQuantity*907184740;
            break;
        case self::LONG_TON:
            $fMilligramQty = $fQuantity*1016046908.8;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $iOutputQty;
        switch ( $eToUnit )
        {
        case self::MILLIGRAM:
            $iOutputQty = CMathi::round($fMilligramQty);
            break;
        case self::GRAM:
            $iOutputQty = CMathi::round($fMilligramQty/1000);
            break;
        case self::KILOGRAM:
            $iOutputQty = CMathi::round($fMilligramQty/1000000);
            break;
        case self::TON:
            $iOutputQty = CMathi::round($fMilligramQty/1000000000);
            break;
        case self::OUNCE:
            $iOutputQty = CMathi::round($fMilligramQty/28349.5231);
            break;
        case self::POUND:
            $iOutputQty = CMathi::round($fMilligramQty/453592.37);
            break;
        case self::STONE:
            $iOutputQty = CMathi::round($fMilligramQty/6350293.18);
            break;
        case self::SHORT_TON:
            $iOutputQty = CMathi::round($fMilligramQty/907184740);
            break;
        case self::LONG_TON:
            $iOutputQty = CMathi::round($fMilligramQty/1016046908.8);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $iOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of mass from one unit into another and returns the result.
     *
     * @param  float $fQuantity The quantity to be converted.
     * @param  enum $eFromUnit The source unit.
     * @param  enum $eToUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertMassf ($fQuantity, $eFromUnit, $eToUnit)
    {
        assert( 'is_float($fQuantity) && is_enum($eFromUnit) && is_enum($eToUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$fQuantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $eFromUnit == $eToUnit )
        {
            return $fQuantity;
        }

        $fMilligramQty;
        switch ( $eFromUnit )
        {
        case self::MILLIGRAM:
            $fMilligramQty = $fQuantity;
            break;
        case self::GRAM:
            $fMilligramQty = $fQuantity*1000;
            break;
        case self::KILOGRAM:
            $fMilligramQty = $fQuantity*1000000;
            break;
        case self::TON:
            $fMilligramQty = $fQuantity*1000000000;
            break;
        case self::OUNCE:
            $fMilligramQty = $fQuantity*28349.5231;
            break;
        case self::POUND:
            $fMilligramQty = $fQuantity*453592.37;
            break;
        case self::STONE:
            $fMilligramQty = $fQuantity*6350293.18;
            break;
        case self::SHORT_TON:
            $fMilligramQty = $fQuantity*907184740;
            break;
        case self::LONG_TON:
            $fMilligramQty = $fQuantity*1016046908.8;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $fOutputQty;
        switch ( $eToUnit )
        {
        case self::MILLIGRAM:
            $fOutputQty = $fMilligramQty;
            break;
        case self::GRAM:
            $fOutputQty = $fMilligramQty/1000;
            break;
        case self::KILOGRAM:
            $fOutputQty = $fMilligramQty/1000000;
            break;
        case self::TON:
            $fOutputQty = $fMilligramQty/1000000000;
            break;
        case self::OUNCE:
            $fOutputQty = $fMilligramQty/28349.5231;
            break;
        case self::POUND:
            $fOutputQty = $fMilligramQty/453592.37;
            break;
        case self::STONE:
            $fOutputQty = $fMilligramQty/6350293.18;
            break;
        case self::SHORT_TON:
            $fOutputQty = $fMilligramQty/907184740;
            break;
        case self::LONG_TON:
            $fOutputQty = $fMilligramQty/1016046908.8;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $fOutputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
