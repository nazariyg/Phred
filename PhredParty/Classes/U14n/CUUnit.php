<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   static int convertStoragei ($quantity, $fromUnit, $toUnit)
//   static float convertStoragef ($quantity, $fromUnit, $toUnit)
//   static int convertTimei ($quantity, $fromUnit, $toUnit)
//   static float convertTimef ($quantity, $fromUnit, $toUnit)
//   static int convertLengthi ($quantity, $fromUnit, $toUnit)
//   static float convertLengthf ($quantity, $fromUnit, $toUnit)
//   static int convertSpeedi ($quantity, $fromUnit, $toUnit)
//   static float convertSpeedf ($quantity, $fromUnit, $toUnit)
//   static int convertTemperaturei ($quantity, $fromUnit, $toUnit)
//   static float convertTemperaturef ($quantity, $fromUnit, $toUnit)
//   static int convertAreai ($quantity, $fromUnit, $toUnit)
//   static float convertAreaf ($quantity, $fromUnit, $toUnit)
//   static int convertVolumei ($quantity, $fromUnit, $toUnit)
//   static float convertVolumef ($quantity, $fromUnit, $toUnit)
//   static int convertMassi ($quantity, $fromUnit, $toUnit)
//   static float convertMassf ($quantity, $fromUnit, $toUnit)

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
     * @param  int $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertStoragei ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_int($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $bitQty;
        switch ( $fromUnit )
        {
        case self::BIT:
            $bitQty = $quantity;
            break;
        case self::BYTE:
            $bitQty = $quantity*8;
            break;
        case self::KILOBIT:
            $bitQty = $quantity*1024;
            break;
        case self::KILOBYTE:
            $bitQty = $quantity*8192;
            break;
        case self::MEGABIT:
            $bitQty = $quantity*1048576;
            break;
        case self::MEGABYTE:
            $bitQty = $quantity*8388608;
            break;
        case self::GIGABIT:
            $bitQty = $quantity*1073741824;
            break;
        case self::GIGABYTE:
            $bitQty = $quantity*8589934592;
            break;
        case self::TERABIT:
            $bitQty = $quantity*1099511627776;
            break;
        case self::TERABYTE:
            $bitQty = $quantity*8796093022208;
            break;
        case self::PETABIT:
            $bitQty = $quantity*1125899906842624;
            break;
        case self::PETABYTE:
            $bitQty = $quantity*9007199254740992;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::BIT:
            $outputQty = $bitQty;
            break;
        case self::BYTE:
            $outputQty = CMathi::round(((float)$bitQty)/8);
            break;
        case self::KILOBIT:
            $outputQty = CMathi::round(((float)$bitQty)/1024);
            break;
        case self::KILOBYTE:
            $outputQty = CMathi::round(((float)$bitQty)/8192);
            break;
        case self::MEGABIT:
            $outputQty = CMathi::round(((float)$bitQty)/1048576);
            break;
        case self::MEGABYTE:
            $outputQty = CMathi::round(((float)$bitQty)/8388608);
            break;
        case self::GIGABIT:
            $outputQty = CMathi::round(((float)$bitQty)/1073741824);
            break;
        case self::GIGABYTE:
            $outputQty = CMathi::round(((float)$bitQty)/8589934592);
            break;
        case self::TERABIT:
            $outputQty = CMathi::round(((float)$bitQty)/1099511627776);
            break;
        case self::TERABYTE:
            $outputQty = CMathi::round(((float)$bitQty)/8796093022208);
            break;
        case self::PETABIT:
            $outputQty = CMathi::round(((float)$bitQty)/1125899906842624);
            break;
        case self::PETABYTE:
            $outputQty = CMathi::round(((float)$bitQty)/9007199254740992);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of digital storage from one unit into another and returns the result.
     *
     * @param  float $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertStoragef ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_float($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $bitQty;
        switch ( $fromUnit )
        {
        case self::BIT:
            $bitQty = $quantity;
            break;
        case self::BYTE:
            $bitQty = $quantity*8;
            break;
        case self::KILOBIT:
            $bitQty = $quantity*1024;
            break;
        case self::KILOBYTE:
            $bitQty = $quantity*8192;
            break;
        case self::MEGABIT:
            $bitQty = $quantity*1048576;
            break;
        case self::MEGABYTE:
            $bitQty = $quantity*8388608;
            break;
        case self::GIGABIT:
            $bitQty = $quantity*1073741824;
            break;
        case self::GIGABYTE:
            $bitQty = $quantity*8589934592;
            break;
        case self::TERABIT:
            $bitQty = $quantity*1099511627776;
            break;
        case self::TERABYTE:
            $bitQty = $quantity*8796093022208;
            break;
        case self::PETABIT:
            $bitQty = $quantity*1125899906842624;
            break;
        case self::PETABYTE:
            $bitQty = $quantity*9007199254740992;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::BIT:
            $outputQty = $bitQty;
            break;
        case self::BYTE:
            $outputQty = $bitQty/8;
            break;
        case self::KILOBIT:
            $outputQty = $bitQty/1024;
            break;
        case self::KILOBYTE:
            $outputQty = $bitQty/8192;
            break;
        case self::MEGABIT:
            $outputQty = $bitQty/1048576;
            break;
        case self::MEGABYTE:
            $outputQty = $bitQty/8388608;
            break;
        case self::GIGABIT:
            $outputQty = $bitQty/1073741824;
            break;
        case self::GIGABYTE:
            $outputQty = $bitQty/8589934592;
            break;
        case self::TERABIT:
            $outputQty = $bitQty/1099511627776;
            break;
        case self::TERABYTE:
            $outputQty = $bitQty/8796093022208;
            break;
        case self::PETABIT:
            $outputQty = $bitQty/1125899906842624;
            break;
        case self::PETABYTE:
            $outputQty = $bitQty/9007199254740992;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of time from one unit into another and returns the result.
     *
     * @param  int $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertTimei ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_int($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $millisecondQty;
        switch ( $fromUnit )
        {
        case self::MILLISECOND:
            $millisecondQty = $quantity;
            break;
        case self::SECOND:
            $millisecondQty = $quantity*1000;
            break;
        case self::MINUTE:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_MINUTE;
            break;
        case self::HOUR:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_HOUR;
            break;
        case self::DAY:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_DAY;
            break;
        case self::WEEK:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_WEEK;
            break;
        case self::MONTH:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_MONTH;
            break;
        case self::YEAR:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_YEAR;
            break;
        case self::DECADE:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_YEAR*10;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::MILLISECOND:
            $outputQty = $millisecondQty;
            break;
        case self::SECOND:
            $outputQty = CMathi::round(((float)$millisecondQty)/1000);
            break;
        case self::MINUTE:
            $outputQty = CMathi::round(((float)$millisecondQty)/(1000*CTime::SECONDS_PER_MINUTE));
            break;
        case self::HOUR:
            $outputQty = CMathi::round(((float)$millisecondQty)/(1000*CTime::SECONDS_PER_HOUR));
            break;
        case self::DAY:
            $outputQty = CMathi::round(((float)$millisecondQty)/(1000*CTime::SECONDS_PER_DAY));
            break;
        case self::WEEK:
            $outputQty = CMathi::round(((float)$millisecondQty)/(1000*CTime::SECONDS_PER_WEEK));
            break;
        case self::MONTH:
            $outputQty = CMathi::round(((float)$millisecondQty)/(1000*CTime::SECONDS_PER_MONTH));
            break;
        case self::YEAR:
            $outputQty = CMathi::round(((float)$millisecondQty)/(1000*CTime::SECONDS_PER_YEAR));
            break;
        case self::DECADE:
            $outputQty = CMathi::round(((float)$millisecondQty)/(1000*CTime::SECONDS_PER_YEAR*10));
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of time from one unit into another and returns the result.
     *
     * @param  float $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertTimef ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_float($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $millisecondQty;
        switch ( $fromUnit )
        {
        case self::MILLISECOND:
            $millisecondQty = $quantity;
            break;
        case self::SECOND:
            $millisecondQty = $quantity*1000;
            break;
        case self::MINUTE:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_MINUTE;
            break;
        case self::HOUR:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_HOUR;
            break;
        case self::DAY:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_DAY;
            break;
        case self::WEEK:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_WEEK;
            break;
        case self::MONTH:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_MONTH;
            break;
        case self::YEAR:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_YEAR;
            break;
        case self::DECADE:
            $millisecondQty = $quantity*1000*CTime::SECONDS_PER_YEAR*10;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::MILLISECOND:
            $outputQty = $millisecondQty;
            break;
        case self::SECOND:
            $outputQty = $millisecondQty/1000;
            break;
        case self::MINUTE:
            $outputQty = $millisecondQty/(1000*CTime::SECONDS_PER_MINUTE);
            break;
        case self::HOUR:
            $outputQty = $millisecondQty/(1000*CTime::SECONDS_PER_HOUR);
            break;
        case self::DAY:
            $outputQty = $millisecondQty/(1000*CTime::SECONDS_PER_DAY);
            break;
        case self::WEEK:
            $outputQty = $millisecondQty/(1000*CTime::SECONDS_PER_WEEK);
            break;
        case self::MONTH:
            $outputQty = $millisecondQty/(1000*CTime::SECONDS_PER_MONTH);
            break;
        case self::YEAR:
            $outputQty = $millisecondQty/(1000*CTime::SECONDS_PER_YEAR);
            break;
        case self::DECADE:
            $outputQty = $millisecondQty/(1000*CTime::SECONDS_PER_YEAR*10);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of length from one unit into another and returns the result.
     *
     * @param  int $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertLengthi ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_int($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $floatQuantity = (float)$quantity;

        $millimeterQty;
        switch ( $fromUnit )
        {
        case self::MILLIMETER:
            $millimeterQty = $floatQuantity;
            break;
        case self::CENTIMETER:
            $millimeterQty = $floatQuantity*10;
            break;
        case self::METER:
            $millimeterQty = $floatQuantity*1000;
            break;
        case self::KILOMETER:
            $millimeterQty = $floatQuantity*1000000;
            break;
        case self::INCH:
            $millimeterQty = $floatQuantity*25.4001;
            break;
        case self::FOOT:
            $millimeterQty = $floatQuantity*304.8;
            break;
        case self::YARD:
            $millimeterQty = $floatQuantity*914.4;
            break;
        case self::MILE:
            $millimeterQty = $floatQuantity*1609344;
            break;
        case self::NAUTICAL_MILE:
            $millimeterQty = $floatQuantity*1852000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::MILLIMETER:
            $outputQty = CMathi::round($millimeterQty);
            break;
        case self::CENTIMETER:
            $outputQty = CMathi::round($millimeterQty/10);
            break;
        case self::METER:
            $outputQty = CMathi::round($millimeterQty/1000);
            break;
        case self::KILOMETER:
            $outputQty = CMathi::round($millimeterQty/1000000);
            break;
        case self::INCH:
            $outputQty = CMathi::round($millimeterQty/25.4001);
            break;
        case self::FOOT:
            $outputQty = CMathi::round($millimeterQty/304.8);
            break;
        case self::YARD:
            $outputQty = CMathi::round($millimeterQty/914.4);
            break;
        case self::MILE:
            $outputQty = CMathi::round($millimeterQty/1609344);
            break;
        case self::NAUTICAL_MILE:
            $outputQty = CMathi::round($millimeterQty/1852000);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of length from one unit into another and returns the result.
     *
     * @param  float $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertLengthf ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_float($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $millimeterQty;
        switch ( $fromUnit )
        {
        case self::MILLIMETER:
            $millimeterQty = $quantity;
            break;
        case self::CENTIMETER:
            $millimeterQty = $quantity*10;
            break;
        case self::METER:
            $millimeterQty = $quantity*1000;
            break;
        case self::KILOMETER:
            $millimeterQty = $quantity*1000000;
            break;
        case self::INCH:
            $millimeterQty = $quantity*25.4001;
            break;
        case self::FOOT:
            $millimeterQty = $quantity*304.8;
            break;
        case self::YARD:
            $millimeterQty = $quantity*914.4;
            break;
        case self::MILE:
            $millimeterQty = $quantity*1609344;
            break;
        case self::NAUTICAL_MILE:
            $millimeterQty = $quantity*1852000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::MILLIMETER:
            $outputQty = $millimeterQty;
            break;
        case self::CENTIMETER:
            $outputQty = $millimeterQty/10;
            break;
        case self::METER:
            $outputQty = $millimeterQty/1000;
            break;
        case self::KILOMETER:
            $outputQty = $millimeterQty/1000000;
            break;
        case self::INCH:
            $outputQty = $millimeterQty/25.4001;
            break;
        case self::FOOT:
            $outputQty = $millimeterQty/304.8;
            break;
        case self::YARD:
            $outputQty = $millimeterQty/914.4;
            break;
        case self::MILE:
            $outputQty = $millimeterQty/1609344;
            break;
        case self::NAUTICAL_MILE:
            $outputQty = $millimeterQty/1852000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of speed from one unit into another and returns the result.
     *
     * @param  int $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertSpeedi ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_int($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $centimetersPerHourQty;
        switch ( $fromUnit )
        {
        case self::METERS_PER_SECOND:
            $centimetersPerHourQty = $quantity*360000;
            break;
        case self::KILOMETERS_PER_HOUR:
            $centimetersPerHourQty = $quantity*100000;
            break;
        case self::FEET_PER_SECOND:
            $centimetersPerHourQty = $quantity*109728;
            break;
        case self::MILES_PER_HOUR:
            $centimetersPerHourQty = $quantity*160935;
            break;
        case self::KNOT:
            $centimetersPerHourQty = $quantity*185200;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::METERS_PER_SECOND:
            $outputQty = CMathi::round(((float)$centimetersPerHourQty)/360000);
            break;
        case self::KILOMETERS_PER_HOUR:
            $outputQty = CMathi::round(((float)$centimetersPerHourQty)/100000);
            break;
        case self::FEET_PER_SECOND:
            $outputQty = CMathi::round(((float)$centimetersPerHourQty)/109728);
            break;
        case self::MILES_PER_HOUR:
            $outputQty = CMathi::round(((float)$centimetersPerHourQty)/160935);
            break;
        case self::KNOT:
            $outputQty = CMathi::round(((float)$centimetersPerHourQty)/185200);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of speed from one unit into another and returns the result.
     *
     * @param  float $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertSpeedf ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_float($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $centimetersPerHourQty;
        switch ( $fromUnit )
        {
        case self::METERS_PER_SECOND:
            $centimetersPerHourQty = $quantity*360000;
            break;
        case self::KILOMETERS_PER_HOUR:
            $centimetersPerHourQty = $quantity*100000;
            break;
        case self::FEET_PER_SECOND:
            $centimetersPerHourQty = $quantity*109728;
            break;
        case self::MILES_PER_HOUR:
            $centimetersPerHourQty = $quantity*160935;
            break;
        case self::KNOT:
            $centimetersPerHourQty = $quantity*185200;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::METERS_PER_SECOND:
            $outputQty = $centimetersPerHourQty/360000;
            break;
        case self::KILOMETERS_PER_HOUR:
            $outputQty = $centimetersPerHourQty/100000;
            break;
        case self::FEET_PER_SECOND:
            $outputQty = $centimetersPerHourQty/109728;
            break;
        case self::MILES_PER_HOUR:
            $outputQty = $centimetersPerHourQty/160935;
            break;
        case self::KNOT:
            $outputQty = $centimetersPerHourQty/185200;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of temperature from one unit into another and returns the result.
     *
     * @param  int $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertTemperaturei ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_int($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $floatQuantity = (float)$quantity;

        $kelvinQty;
        switch ( $fromUnit )
        {
        case self::CELSIUS:
            $kelvinQty = $floatQuantity + 273.15;
            break;
        case self::FAHRENHEIT:
            $kelvinQty = ($floatQuantity + 459.67)*5/9;
            break;
        case self::KELVIN:
            $kelvinQty = $floatQuantity;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::CELSIUS:
            $outputQty = CMathi::round($kelvinQty - 273.15);
            break;
        case self::FAHRENHEIT:
            $outputQty = CMathi::round($kelvinQty*9/5 - 459.67);
            break;
        case self::KELVIN:
            $outputQty = CMathi::round($kelvinQty);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of temperature from one unit into another and returns the result.
     *
     * @param  float $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertTemperaturef ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_float($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $kelvinQty;
        switch ( $fromUnit )
        {
        case self::CELSIUS:
            $kelvinQty = $quantity + 273.15;
            break;
        case self::FAHRENHEIT:
            $kelvinQty = ($quantity + 459.67)*5/9;
            break;
        case self::KELVIN:
            $kelvinQty = $quantity;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::CELSIUS:
            $outputQty = $kelvinQty - 273.15;
            break;
        case self::FAHRENHEIT:
            $outputQty = $kelvinQty*9/5 - 459.67;
            break;
        case self::KELVIN:
            $outputQty = $kelvinQty;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of area from one unit into another and returns the result.
     *
     * @param  int $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertAreai ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_int($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $floatQuantity = (float)$quantity;

        $squareCentimeterQty;
        switch ( $fromUnit )
        {
        case self::SQUARE_METER:
            $squareCentimeterQty = $floatQuantity*10000;
            break;
        case self::HECTARE:
            $squareCentimeterQty = $floatQuantity*100000000;
            break;
        case self::SQUARE_KILOMETER:
            $squareCentimeterQty = $floatQuantity*10000000000;
            break;
        case self::SQUARE_INCH:
            $squareCentimeterQty = $floatQuantity*6.4516;
            break;
        case self::SQUARE_FOOT:
            $squareCentimeterQty = $floatQuantity*929.03;
            break;
        case self::SQUARE_YARD:
            $squareCentimeterQty = $floatQuantity*8361.27;
            break;
        case self::ACRE:
            $squareCentimeterQty = $floatQuantity*40468600;
            break;
        case self::SQUARE_MILE:
            $squareCentimeterQty = $floatQuantity*25899900000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::SQUARE_METER:
            $outputQty = CMathi::round($squareCentimeterQty/10000);
            break;
        case self::HECTARE:
            $outputQty = CMathi::round($squareCentimeterQty/100000000);
            break;
        case self::SQUARE_KILOMETER:
            $outputQty = CMathi::round($squareCentimeterQty/10000000000);
            break;
        case self::SQUARE_INCH:
            $outputQty = CMathi::round($squareCentimeterQty/6.4516);
            break;
        case self::SQUARE_FOOT:
            $outputQty = CMathi::round($squareCentimeterQty/929.03);
            break;
        case self::SQUARE_YARD:
            $outputQty = CMathi::round($squareCentimeterQty/8361.27);
            break;
        case self::ACRE:
            $outputQty = CMathi::round($squareCentimeterQty/40468600);
            break;
        case self::SQUARE_MILE:
            $outputQty = CMathi::round($squareCentimeterQty/25899900000);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of area from one unit into another and returns the result.
     *
     * @param  float $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertAreaf ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_float($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $squareCentimeterQty;
        switch ( $fromUnit )
        {
        case self::SQUARE_METER:
            $squareCentimeterQty = $quantity*10000;
            break;
        case self::HECTARE:
            $squareCentimeterQty = $quantity*100000000;
            break;
        case self::SQUARE_KILOMETER:
            $squareCentimeterQty = $quantity*10000000000;
            break;
        case self::SQUARE_INCH:
            $squareCentimeterQty = $quantity*6.4516;
            break;
        case self::SQUARE_FOOT:
            $squareCentimeterQty = $quantity*929.03;
            break;
        case self::SQUARE_YARD:
            $squareCentimeterQty = $quantity*8361.27;
            break;
        case self::ACRE:
            $squareCentimeterQty = $quantity*40468600;
            break;
        case self::SQUARE_MILE:
            $squareCentimeterQty = $quantity*25899900000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::SQUARE_METER:
            $outputQty = $squareCentimeterQty/10000;
            break;
        case self::HECTARE:
            $outputQty = $squareCentimeterQty/100000000;
            break;
        case self::SQUARE_KILOMETER:
            $outputQty = $squareCentimeterQty/10000000000;
            break;
        case self::SQUARE_INCH:
            $outputQty = $squareCentimeterQty/6.4516;
            break;
        case self::SQUARE_FOOT:
            $outputQty = $squareCentimeterQty/929.03;
            break;
        case self::SQUARE_YARD:
            $outputQty = $squareCentimeterQty/8361.27;
            break;
        case self::ACRE:
            $outputQty = $squareCentimeterQty/40468600;
            break;
        case self::SQUARE_MILE:
            $outputQty = $squareCentimeterQty/25899900000;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of volume from one unit into another and returns the result.
     *
     * @param  int $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertVolumei ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_int($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $floatQuantity = (float)$quantity;

        $milliliterQty;
        switch ( $fromUnit )
        {
        case self::MILLILITER:
            $milliliterQty = $floatQuantity;
            break;
        case self::LITER:
            $milliliterQty = $floatQuantity*1000;
            break;
        case self::CUBIC_METER:
            $milliliterQty = $floatQuantity*1000000;
            break;
        case self::CUBIC_INCH:
            $milliliterQty = $floatQuantity*16.3871;
            break;
        case self::CUBIC_FOOT:
            $milliliterQty = $floatQuantity*28316.8;
            break;
        case self::US_TEASPOON:
            $milliliterQty = $floatQuantity*4.928922;
            break;
        case self::US_TABLESPOON:
            $milliliterQty = $floatQuantity*14.786765;
            break;
        case self::US_FLUID_OUNCE:
            $milliliterQty = $floatQuantity*29.5735295625;
            break;
        case self::US_CUP:
            $milliliterQty = $floatQuantity*236.588237;
            break;
        case self::US_PINT:
            $milliliterQty = $floatQuantity*473.176473;
            break;
        case self::US_QUART:
            $milliliterQty = $floatQuantity*946.352946;
            break;
        case self::US_GALLON:
            $milliliterQty = $floatQuantity*3785.411784;
            break;
        case self::IMPERIAL_TEASPOON:
            $milliliterQty = $floatQuantity*5.91939047;
            break;
        case self::IMPERIAL_TABLESPOON:
            $milliliterQty = $floatQuantity*17.7581714;
            break;
        case self::IMPERIAL_FLUID_OUNCE:
            $milliliterQty = $floatQuantity*28.4130742;
            break;
        case self::IMPERIAL_PINT:
            $milliliterQty = $floatQuantity*568.261485;
            break;
        case self::IMPERIAL_QUART:
            $milliliterQty = $floatQuantity*1136.52297;
            break;
        case self::IMPERIAL_GALLON:
            $milliliterQty = $floatQuantity*4546.09188;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::MILLILITER:
            $outputQty = CMathi::round($milliliterQty);
            break;
        case self::LITER:
            $outputQty = CMathi::round($milliliterQty/1000);
            break;
        case self::CUBIC_METER:
            $outputQty = CMathi::round($milliliterQty/1000000);
            break;
        case self::CUBIC_INCH:
            $outputQty = CMathi::round($milliliterQty/16.3871);
            break;
        case self::CUBIC_FOOT:
            $outputQty = CMathi::round($milliliterQty/28316.8);
            break;
        case self::US_TEASPOON:
            $outputQty = CMathi::round($milliliterQty/4.928922);
            break;
        case self::US_TABLESPOON:
            $outputQty = CMathi::round($milliliterQty/14.786765);
            break;
        case self::US_FLUID_OUNCE:
            $outputQty = CMathi::round($milliliterQty/29.5735295625);
            break;
        case self::US_CUP:
            $outputQty = CMathi::round($milliliterQty/236.588237);
            break;
        case self::US_PINT:
            $outputQty = CMathi::round($milliliterQty/473.176473);
            break;
        case self::US_QUART:
            $outputQty = CMathi::round($milliliterQty/946.352946);
            break;
        case self::US_GALLON:
            $outputQty = CMathi::round($milliliterQty/3785.411784);
            break;
        case self::IMPERIAL_TEASPOON:
            $outputQty = CMathi::round($milliliterQty/5.91939047);
            break;
        case self::IMPERIAL_TABLESPOON:
            $outputQty = CMathi::round($milliliterQty/17.7581714);
            break;
        case self::IMPERIAL_FLUID_OUNCE:
            $outputQty = CMathi::round($milliliterQty/28.4130742);
            break;
        case self::IMPERIAL_PINT:
            $outputQty = CMathi::round($milliliterQty/568.261485);
            break;
        case self::IMPERIAL_QUART:
            $outputQty = CMathi::round($milliliterQty/1136.52297);
            break;
        case self::IMPERIAL_GALLON:
            $outputQty = CMathi::round($milliliterQty/4546.09188);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of volume from one unit into another and returns the result.
     *
     * @param  float $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertVolumef ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_float($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $milliliterQty;
        switch ( $fromUnit )
        {
        case self::MILLILITER:
            $milliliterQty = $quantity;
            break;
        case self::LITER:
            $milliliterQty = $quantity*1000;
            break;
        case self::CUBIC_METER:
            $milliliterQty = $quantity*1000000;
            break;
        case self::CUBIC_INCH:
            $milliliterQty = $quantity*16.3871;
            break;
        case self::CUBIC_FOOT:
            $milliliterQty = $quantity*28316.8;
            break;
        case self::US_TEASPOON:
            $milliliterQty = $quantity*4.928922;
            break;
        case self::US_TABLESPOON:
            $milliliterQty = $quantity*14.786765;
            break;
        case self::US_FLUID_OUNCE:
            $milliliterQty = $quantity*29.5735295625;
            break;
        case self::US_CUP:
            $milliliterQty = $quantity*236.588237;
            break;
        case self::US_PINT:
            $milliliterQty = $quantity*473.176473;
            break;
        case self::US_QUART:
            $milliliterQty = $quantity*946.352946;
            break;
        case self::US_GALLON:
            $milliliterQty = $quantity*3785.411784;
            break;
        case self::IMPERIAL_TEASPOON:
            $milliliterQty = $quantity*5.91939047;
            break;
        case self::IMPERIAL_TABLESPOON:
            $milliliterQty = $quantity*17.7581714;
            break;
        case self::IMPERIAL_FLUID_OUNCE:
            $milliliterQty = $quantity*28.4130742;
            break;
        case self::IMPERIAL_PINT:
            $milliliterQty = $quantity*568.261485;
            break;
        case self::IMPERIAL_QUART:
            $milliliterQty = $quantity*1136.52297;
            break;
        case self::IMPERIAL_GALLON:
            $milliliterQty = $quantity*4546.09188;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::MILLILITER:
            $outputQty = $milliliterQty;
            break;
        case self::LITER:
            $outputQty = $milliliterQty/1000;
            break;
        case self::CUBIC_METER:
            $outputQty = $milliliterQty/1000000;
            break;
        case self::CUBIC_INCH:
            $outputQty = $milliliterQty/16.3871;
            break;
        case self::CUBIC_FOOT:
            $outputQty = $milliliterQty/28316.8;
            break;
        case self::US_TEASPOON:
            $outputQty = $milliliterQty/4.928922;
            break;
        case self::US_TABLESPOON:
            $outputQty = $milliliterQty/14.786765;
            break;
        case self::US_FLUID_OUNCE:
            $outputQty = $milliliterQty/29.5735295625;
            break;
        case self::US_CUP:
            $outputQty = $milliliterQty/236.588237;
            break;
        case self::US_PINT:
            $outputQty = $milliliterQty/473.176473;
            break;
        case self::US_QUART:
            $outputQty = $milliliterQty/946.352946;
            break;
        case self::US_GALLON:
            $outputQty = $milliliterQty/3785.411784;
            break;
        case self::IMPERIAL_TEASPOON:
            $outputQty = $milliliterQty/5.91939047;
            break;
        case self::IMPERIAL_TABLESPOON:
            $outputQty = $milliliterQty/17.7581714;
            break;
        case self::IMPERIAL_FLUID_OUNCE:
            $outputQty = $milliliterQty/28.4130742;
            break;
        case self::IMPERIAL_PINT:
            $outputQty = $milliliterQty/568.261485;
            break;
        case self::IMPERIAL_QUART:
            $outputQty = $milliliterQty/1136.52297;
            break;
        case self::IMPERIAL_GALLON:
            $outputQty = $milliliterQty/4546.09188;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer quantity of mass from one unit into another and returns the result.
     *
     * @param  int $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return int The converted quantity, after rounding to the nearest integer.
     */

    public static function convertMassi ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_int($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $floatQuantity = (float)$quantity;

        $milligramQty;
        switch ( $fromUnit )
        {
        case self::MILLIGRAM:
            $milligramQty = $floatQuantity;
            break;
        case self::GRAM:
            $milligramQty = $floatQuantity*1000;
            break;
        case self::KILOGRAM:
            $milligramQty = $floatQuantity*1000000;
            break;
        case self::TON:
            $milligramQty = $floatQuantity*1000000000;
            break;
        case self::OUNCE:
            $milligramQty = $floatQuantity*28349.5231;
            break;
        case self::POUND:
            $milligramQty = $floatQuantity*453592.37;
            break;
        case self::STONE:
            $milligramQty = $floatQuantity*6350293.18;
            break;
        case self::SHORT_TON:
            $milligramQty = $floatQuantity*907184740;
            break;
        case self::LONG_TON:
            $milligramQty = $floatQuantity*1016046908.8;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::MILLIGRAM:
            $outputQty = CMathi::round($milligramQty);
            break;
        case self::GRAM:
            $outputQty = CMathi::round($milligramQty/1000);
            break;
        case self::KILOGRAM:
            $outputQty = CMathi::round($milligramQty/1000000);
            break;
        case self::TON:
            $outputQty = CMathi::round($milligramQty/1000000000);
            break;
        case self::OUNCE:
            $outputQty = CMathi::round($milligramQty/28349.5231);
            break;
        case self::POUND:
            $outputQty = CMathi::round($milligramQty/453592.37);
            break;
        case self::STONE:
            $outputQty = CMathi::round($milligramQty/6350293.18);
            break;
        case self::SHORT_TON:
            $outputQty = CMathi::round($milligramQty/907184740);
            break;
        case self::LONG_TON:
            $outputQty = CMathi::round($milligramQty/1016046908.8);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point quantity of mass from one unit into another and returns the result.
     *
     * @param  float $quantity The quantity to be converted.
     * @param  enum $fromUnit The source unit.
     * @param  enum $toUnit The destination unit.
     *
     * @return float The converted quantity.
     */

    public static function convertMassf ($quantity, $fromUnit, $toUnit)
    {
        assert( 'is_float($quantity) && is_enum($fromUnit) && is_enum($toUnit)',
            vs(isset($this), get_defined_vars()) );
        assert( '$quantity >= 0.0', vs(isset($this), get_defined_vars()) );

        if ( $fromUnit == $toUnit )
        {
            return $quantity;
        }

        $milligramQty;
        switch ( $fromUnit )
        {
        case self::MILLIGRAM:
            $milligramQty = $quantity;
            break;
        case self::GRAM:
            $milligramQty = $quantity*1000;
            break;
        case self::KILOGRAM:
            $milligramQty = $quantity*1000000;
            break;
        case self::TON:
            $milligramQty = $quantity*1000000000;
            break;
        case self::OUNCE:
            $milligramQty = $quantity*28349.5231;
            break;
        case self::POUND:
            $milligramQty = $quantity*453592.37;
            break;
        case self::STONE:
            $milligramQty = $quantity*6350293.18;
            break;
        case self::SHORT_TON:
            $milligramQty = $quantity*907184740;
            break;
        case self::LONG_TON:
            $milligramQty = $quantity*1016046908.8;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        $outputQty;
        switch ( $toUnit )
        {
        case self::MILLIGRAM:
            $outputQty = $milligramQty;
            break;
        case self::GRAM:
            $outputQty = $milligramQty/1000;
            break;
        case self::KILOGRAM:
            $outputQty = $milligramQty/1000000;
            break;
        case self::TON:
            $outputQty = $milligramQty/1000000000;
            break;
        case self::OUNCE:
            $outputQty = $milligramQty/28349.5231;
            break;
        case self::POUND:
            $outputQty = $milligramQty/453592.37;
            break;
        case self::STONE:
            $outputQty = $milligramQty/6350293.18;
            break;
        case self::SHORT_TON:
            $outputQty = $milligramQty/907184740;
            break;
        case self::LONG_TON:
            $outputQty = $milligramQty/1016046908.8;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        return $outputQty;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
