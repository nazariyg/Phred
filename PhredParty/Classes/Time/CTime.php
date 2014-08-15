<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class of any point in time.
 *
 * **You can refer to this class by its alias, which is** `Tm`.
 *
 * Although there exists a multitude of time zones around the world, any point in time that is represented by an object
 * of this class is referring to the very same moment in all of the time zones. A point in time is not defined by the
 * year, month, day, hour, minute, and second, because these date/time components vary from one time zone to another.
 * Instead, a point in time is absolutely defined by a single time coordinate called "Unix time".
 *
 * The "Unix time" is the number of seconds that have elapsed since the midnight of January 1, 1970 as if this moment
 * occurred in the UTC time zone, which is the time zone of the universal time and geographically matches the time zone
 * of Greenwich, UK. UTC used to be called GMT in the past and is still called GMT in some conventions. Internally, the
 * "Unix time" coordinate of any point in time is stored as a floating-point number, which is positive or zero, unless
 * the point belongs to a year before 1970 and the coordinate is negative. The signed "Unix time" is also known as
 * "UTime" in the context of this class and the signed number of milliseconds as "MTime".
 *
 * The supported range of years is from 1902 to 2037. The months are identified by their numbers, starting with 1 for
 * January and ending with 12 for December. The minimum number of a day is 1, but the maximum such number depends on
 * the actual month, with 31 being of course the largest possible number. Hours, minutes, and seconds are numbered in
 * the usual way, starting with 0 and ending with 23 for hours and with 59 for minutes and seconds. Milliseconds are
 * supported for any point in time and their numbers range from 0 to 999. The days of the week are referred to by the
 * `SUNDAY`, `MONDAY`, `TUESDAY`, `WEDNESDAY`, `THURSDAY`, `FRIDAY`, and `SATURDAY` enumerands of the class.
 *
 * Don't let the quantity of available methods look overwhelming to you. Many of them are just convenience methods
 * dedicated to the combination of a date/time component with a time zone. These methods can be seen to organize into
 * groups, such as the `fromComponents...` methods that create a point in time from date/time components as they would
 * appear on a clock in a time zone, the `toString...` methods that, as opposed to `fromString` method, format a point
 * in time as a string associated with a time zone, the group of methods that break a point in time into components
 * associated with a time zone or return a single component (like `componentsUtc` and `monthInTimeZone`), the `diff...`
 * and `signedDiff...` methods that calculate the difference between any two points in time, the `shifted...` methods
 * that return a point in time after shifting it by some amount of time in either direction and in any time zone, and
 * the `with...` methods that return a point in time after modifying one of its components in a time zone.
 */

// Method signatures:
//   __construct ($iUTime, $iMTime = 0)
//   static CTime now ()
//   static CTime fromFTime ($fFTime)
//   static CTime fromString ($sString, $xPattern = null)
//   static bool areComponentsValid ($iYear, $iMonth, $iDay, $iHour = 0, $iMinute = 0, $iSecond = 0, $iMillisecond = 0)
//   static CTime fromComponentsUtc ($iYear, $iMonth = 1, $iDay = 1, $iHour = 0, $iMinute = 0, $iSecond = 0,
//     $iMillisecond = 0)
//   static CTime fromComponentsInTimeZone (CTimeZone $oTimeZone, $iYear, $iMonth = 1, $iDay = 1, $iHour = 0,
//     $iMinute = 0, $iSecond = 0, $iMillisecond = 0)
//   static CTime fromComponentsLocal ($iYear, $iMonth = 1, $iDay = 1, $iHour = 0, $iMinute = 0, $iSecond = 0,
//     $iMillisecond = 0)
//   CUStringObject toStringUtc ($xPattern = self::PATTERN_DEFAULT)
//   CUStringObject toStringInTimeZone (CTimeZone $oTimeZone, $xPattern = self::PATTERN_DEFAULT)
//   CUStringObject toStringLocal ($xPattern = self::PATTERN_DEFAULT)
//   int UTime ()
//   int MTime ()
//   float FTime ()
//   bool equals ($oToTime)
//   bool isBefore (CTime $oTime)
//   bool isAfter (CTime $oTime)
//   int compare ($oToTime)
//   static int currentUTime ()
//   static int currentDayOfYearUtc ()
//   static int currentDayOfYearInTimeZone (CTimeZone $oTimeZone)
//   static int currentDayOfYearLocal ()
//   void componentsUtc (&$riYear, &$riMonth = null, &$riDay = null, &$riHour = null, &$riMinute = null,
//     &$riSecond = null, &$riMillisecond = null, &$reDayOfWeek = null)
//   int yearUtc ()
//   int monthUtc ()
//   int dayUtc ()
//   int hourUtc ()
//   int minuteUtc ()
//   int secondUtc ()
//   int millisecondUtc ()
//   enum dayOfWeekUtc ()
//   void componentsInTimeZone (CTimeZone $oTimeZone, &$riYear, &$riMonth = null, &$riDay = null, &$riHour = null,
//     &$riMinute = null, &$riSecond = null, &$riMillisecond = null, &$reDayOfWeek = null)
//   int yearInTimeZone (CTimeZone $oTimeZone)
//   int monthInTimeZone (CTimeZone $oTimeZone)
//   int dayInTimeZone (CTimeZone $oTimeZone)
//   int hourInTimeZone (CTimeZone $oTimeZone)
//   int minuteInTimeZone (CTimeZone $oTimeZone)
//   int secondInTimeZone (CTimeZone $oTimeZone)
//   int millisecondInTimeZone (CTimeZone $oTimeZone)
//   enum dayOfWeekInTimeZone (CTimeZone $oTimeZone)
//   void componentsLocal (&$riYear, &$riMonth = null, &$riDay = null, &$riHour = null, &$riMinute = null,
//     &$riSecond = null, &$riMillisecond = null, &$reDayOfWeek = null)
//   int yearLocal ()
//   int monthLocal ()
//   int dayLocal ()
//   int hourLocal ()
//   int minuteLocal ()
//   int secondLocal ()
//   int millisecondLocal ()
//   enum dayOfWeekLocal ()
//   int diff (CTime $oFromTime, $eTimeUnit)
//   int diffInSeconds (CTime $oFromTime)
//   int diffInMinutes (CTime $oFromTime)
//   int diffInHours (CTime $oFromTime)
//   int diffInDays (CTime $oFromTime)
//   int diffInWeeks (CTime $oFromTime)
//   int diffInMonths (CTime $oFromTime)
//   int diffInYears (CTime $oFromTime)
//   void diffUnits (CTime $oFromTime, &$riNumSeconds, &$riNumMinutes = null, &$riNumHours = null, &$riNumDays = null,
//     &$riNumWeeks = null, &$riNumMonths = null, &$riNumYears = null)
//   int signedDiff (CTime $oFromTime, $eTimeUnit)
//   int signedDiffInSeconds (CTime $oFromTime)
//   int signedDiffInMinutes (CTime $oFromTime)
//   int signedDiffInHours (CTime $oFromTime)
//   int signedDiffInDays (CTime $oFromTime)
//   int signedDiffInWeeks (CTime $oFromTime)
//   int signedDiffInMonths (CTime $oFromTime)
//   int signedDiffInYears (CTime $oFromTime)
//   CTime shiftedUtc ($eTimeUnit, $iQuantity)
//   CTime shiftedBySecondsUtc ($iQuantity)
//   CTime shiftedByMinutesUtc ($iQuantity)
//   CTime shiftedByHoursUtc ($iQuantity)
//   CTime shiftedByDaysUtc ($iQuantity)
//   CTime shiftedByWeeksUtc ($iQuantity)
//   CTime shiftedByMonthsUtc ($iQuantity)
//   CTime shiftedByYearsUtc ($iQuantity)
//   CTime shiftedInTimeZone (CTimeZone $oTimeZone, $eTimeUnit, $iQuantity)
//   CTime shiftedBySecondsInTimeZone (CTimeZone $oTimeZone, $iQuantity)
//   CTime shiftedByMinutesInTimeZone (CTimeZone $oTimeZone, $iQuantity)
//   CTime shiftedByHoursInTimeZone (CTimeZone $oTimeZone, $iQuantity)
//   CTime shiftedByDaysInTimeZone (CTimeZone $oTimeZone, $iQuantity)
//   CTime shiftedByWeeksInTimeZone (CTimeZone $oTimeZone, $iQuantity)
//   CTime shiftedByMonthsInTimeZone (CTimeZone $oTimeZone, $iQuantity)
//   CTime shiftedByYearsInTimeZone (CTimeZone $oTimeZone, $iQuantity)
//   CTime shiftedLocal ($eTimeUnit, $iQuantity)
//   CTime shiftedBySecondsLocal ($iQuantity)
//   CTime shiftedByMinutesLocal ($iQuantity)
//   CTime shiftedByHoursLocal ($iQuantity)
//   CTime shiftedByDaysLocal ($iQuantity)
//   CTime shiftedByWeeksLocal ($iQuantity)
//   CTime shiftedByMonthsLocal ($iQuantity)
//   CTime shiftedByYearsLocal ($iQuantity)
//   CTime withMillisecondUtc ($iNewMillisecond)
//   CTime withSecondUtc ($iNewSecond)
//   CTime withMinuteUtc ($iNewMinute)
//   CTime withHourUtc ($iNewHour)
//   CTime withDayUtc ($iNewDay)
//   CTime withMonthUtc ($iNewMonth)
//   CTime withYearUtc ($iNewYear)
//   CTime withMillisecondInTimeZone (CTimeZone $oTimeZone, $iNewMillisecond)
//   CTime withSecondInTimeZone (CTimeZone $oTimeZone, $iNewSecond)
//   CTime withMinuteInTimeZone (CTimeZone $oTimeZone, $iNewMinute)
//   CTime withHourInTimeZone (CTimeZone $oTimeZone, $iNewHour)
//   CTime withDayInTimeZone (CTimeZone $oTimeZone, $iNewDay)
//   CTime withMonthInTimeZone (CTimeZone $oTimeZone, $iNewMonth)
//   CTime withYearInTimeZone (CTimeZone $oTimeZone, $iNewYear)
//   CTime withMillisecondLocal ($iNewMillisecond)
//   CTime withSecondLocal ($iNewSecond)
//   CTime withMinuteLocal ($iNewMinute)
//   CTime withHourLocal ($iNewHour)
//   CTime withDayLocal ($iNewDay)
//   CTime withMonthLocal ($iNewMonth)
//   CTime withYearLocal ($iNewYear)

class CTime extends CRootClass implements IEqualityAndOrder
{
    // String formatting patterns.
    /**
     * `enum` The default pattern for formatting a point in time into a string, which is the same format used by MySQL,
     * only with the time zone indicated: "Y-m-d H:i:s e". For example, 23:31:30 on February 13, 2009 (UTC) is
     * formatted as "2009-02-13 23:31:30 UTC".
     *
     * @var enum
     */
    const PATTERN_DEFAULT = 0;
    /**
     * `enum` The default *date-only* pattern for formatting a point in time into a string: "Y-m-d". For example,
     * February 13, 2009 is formatted as "2009-02-13".
     *
     * @var enum
     */
    const PATTERN_DEFAULT_DATE = 1;
    /**
     * `enum` The default *time-only* pattern for formatting a point in time into a string: "H:i:s". For example,
     * 23:31:30 is formatted into the same "23:31:30".
     *
     * @var enum
     */
    const PATTERN_DEFAULT_TIME = 2;
    /**
     * `enum` Atom's formatting pattern: "Y-m-d\TH:i:sP". For example, 23:31:30 on February 13, 2009 (UTC) is formatted
     * as "2009-02-13T23:31:30+00:00".
     *
     * @var enum
     */
    const PATTERN_ATOM = 3;
    /**
     * `enum` "l, d-M-y H:i:s T" formatting pattern. For example, 23:31:30 on February 13, 2009 (UTC) is formatted as
     * "Friday, 13-Feb-09 23:31:30 UTC".
     *
     * @var enum
     */
    const PATTERN_COOKIE = 4;
    /**
     * `enum` The formatting pattern that is commonly used by HTTP headers, with "GMT" to indicate the UTC time zone:
     * "D, d M Y H:i:s T". For example, 23:31:30 on February 13, 2009 (UTC) is formatted as
     * "Fri, 13 Feb 2009 23:31:30 GMT".
     *
     * @var enum
     */
    const PATTERN_HTTP_HEADER_GMT = 5;
    /**
     * `enum` ISO 8601 formatting pattern: "Y-m-d\TH:i:sO". For example, 23:31:30 on February 13, 2009 (UTC) is
     * formatted as "2009-02-13T23:31:30+0000".
     *
     * @var enum
     */
    const PATTERN_ISO8601 = 6;
    /**
     * `enum` MySQL's formatting pattern (without a time zone indicated): "Y-m-d H:i:s". For example, 23:31:30 on
     * February 13, 2009 (UTC) is formatted as "2009-02-13 23:31:30".
     *
     * @var enum
     */
    const PATTERN_MYSQL = 7;
    /**
     * `enum` RFC 822 formatting pattern: "D, d M y H:i:s O". For example, 23:31:30 on February 13, 2009 (UTC) is
     * formatted as "Fri, 13 Feb 09 23:31:30 +0000".
     *
     * @var enum
     */
    const PATTERN_RFC822 = 8;
    /**
     * `enum` RFC 850 formatting pattern: "l, d-M-y H:i:s T". For example, 23:31:30 on February 13, 2009 (UTC) is
     * formatted as "Friday, 13-Feb-09 23:31:30 UTC".
     *
     * @var enum
     */
    const PATTERN_RFC850 = 9;
    /**
     * `enum` RFC 1036 formatting pattern: "D, d M y H:i:s O". For example, 23:31:30 on February 13, 2009 (UTC) is
     * formatted as "Fri, 13 Feb 09 23:31:30 +0000".
     *
     * @var enum
     */
    const PATTERN_RFC1036 = 10;
    /**
     * `enum` RFC 2822 formatting pattern, which is the most recent format specified for HTTP headers:
     * "D, d M Y H:i:s O". For example, 23:31:30 on February 13, 2009 (UTC) is formatted as
     * "Fri, 13 Feb 2009 23:31:30 +0000".
     *
     * @var enum
     */
    const PATTERN_RFC2822 = 11;
    /**
     * `enum` RFC 3339 formatting pattern: "Y-m-d\TH:i:sP". For example, 23:31:30 on February 13, 2009 (UTC) is
     * formatted as "2009-02-13T23:31:30+00:00".
     *
     * @var enum
     */
    const PATTERN_RFC3339 = 12;
    /**
     * `enum` RSS formatting pattern: "D, d M Y H:i:s O". For example, 23:31:30 on February 13, 2009 (UTC) is formatted
     * as "Fri, 13 Feb 2009 23:31:30 +0000".
     *
     * @var enum
     */
    const PATTERN_RSS = 13;
    /**
     * `enum` W3C's formatting pattern: "Y-m-d\TH:i:sP". For example, 23:31:30 on February 13, 2009 (UTC) is formatted
     * as "2009-02-13T23:31:30+00:00".
     *
     * @var enum
     */
    const PATTERN_W3C = 14;

    // The days of the week.
    /**
     * `enum` Sunday.
     *
     * @var enum
     */
    const SUNDAY = 0;
    /**
     * `enum` Monday.
     *
     * @var enum
     */
    const MONDAY = 1;
    /**
     * `enum` Tuesday.
     *
     * @var enum
     */
    const TUESDAY = 2;
    /**
     * `enum` Wednesday.
     *
     * @var enum
     */
    const WEDNESDAY = 3;
    /**
     * `enum` Thursday.
     *
     * @var enum
     */
    const THURSDAY = 4;
    /**
     * `enum` Friday.
     *
     * @var enum
     */
    const FRIDAY = 5;
    /**
     * `enum` Saturday.
     *
     * @var enum
     */
    const SATURDAY = 6;

    // Time units.
    /**
     * `enum` Second.
     *
     * @var enum
     */
    const SECOND = 0;
    /**
     * `enum` Minute.
     *
     * @var enum
     */
    const MINUTE = 1;
    /**
     * `enum` Hour.
     *
     * @var enum
     */
    const HOUR = 2;
    /**
     * `enum` Day.
     *
     * @var enum
     */
    const DAY = 3;
    /**
     * `enum` Week.
     *
     * @var enum
     */
    const WEEK = 4;
    /**
     * `enum` Month.
     *
     * @var enum
     */
    const MONTH = 5;
    /**
     * `enum` Year.
     *
     * @var enum
     */
    const YEAR = 6;

    // The numbers of seconds per each time unit. Also used by the time conversion methods of the CUUnit class.
    /**
     * `int` `60` The number of seconds per minute.
     *
     * @var int
     */
    const SECONDS_PER_MINUTE = 60;
    /**
     * `int` `3600` The number of seconds per hour.
     *
     * @var int
     */
    const SECONDS_PER_HOUR = 3600;
    /**
     * `int` `86400` The number of seconds per day. Use the appropriate methods of this class, which are related to a
     * particular time zone if such methods are available, to find out the difference between any two points in time or
     * to shift a point in time etc. and only use this value with confidence in what you are trying to achieve.
     *
     * @var int
     */
    const SECONDS_PER_DAY = 86400;
    /**
     * `int` `604800` The number of seconds per week. Use the appropriate methods of this class, which are related to a
     * particular time zone if such methods are available, to find out the difference between any two points in time or
     * to shift a point in time etc. and only use this value with confidence in what you are trying to achieve.
     *
     * @var int
     */
    const SECONDS_PER_WEEK = 604800;
    /**
     * `int` `2629746` The *average* number of seconds per month, assuming 30.436875 days per month. Use the
     * appropriate methods of this class, which are related to a particular time zone if such methods are available, to
     * find out the difference between any two points in time or to shift a point in time etc. and only use this value
     * with confidence in what you are trying to achieve.
     *
     * @var int
     */
    const SECONDS_PER_MONTH = 2629746;
    /**
     * `int` `31556952` The *average* number of seconds per year, assuming 365.2425 days per year. Use the appropriate
     * methods of this class, which are related to a particular time zone if such methods are available, to find out
     * the difference between any two points in time or to shift a point in time etc. and only use this value with
     * confidence in what you are trying to achieve.
     *
     * @var int
     */
    const SECONDS_PER_YEAR = 31556952;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from the "Unix time" and, optionally, from the milliseconds.
     *
     * The "Unix time" and, if specified, the number of milliseconds are both positive for any point in time after the
     * midnight of January 1, 1970 UTC and are both negative for any point in time before it (the number of
     * milliseconds can be zero in either case).
     *
     * @param  int $iUTime The "Unix time" (the number of seconds relative to the midnight of January 1, 1970 UTC).
     * @param  int $iMTime **OPTIONAL. Default is** `0`. The number of milliseconds in either positive or negative
     * direction starting from the moment set by the "Unix time" seconds. Can be a value from 0 to 999 or from 0 to
     * -999.
     */

    public function __construct ($iUTime, $iMTime = 0, $_noinit = false)
    {
        if ( $_noinit )
        {
            return;
        }

        // If neither is zero, `UTime` and `MTime` must be of the same sign.

        assert( 'is_int($iUTime) && is_int($iMTime)', vs(isset($this), get_defined_vars()) );
        assert( '$iUTime == 0 || $iMTime == 0 || CMathi::sign($iUTime) == CMathi::sign($iMTime)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= CMathi::abs($iMTime) && CMathi::abs($iMTime) <= 999', vs(isset($this), get_defined_vars()) );

        $this->m_fFTime = (float)$iUTime + $iMTime/1000;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the current time.
     *
     * @return CTime The current time.
     */

    public static function now ()
    {
        $oTime = new self(null, null, true);
        $oTime->m_fFTime = microtime(true);
        return $oTime;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from the "Unix time" specified as a floating-point number, which possibly indicates the
     * milliseconds in its fractional part.
     *
     * The "Unix time" is positive for any point in time after the midnight of January 1, 1970 UTC and is negative for
     * any point in time before it.
     *
     * @param  float $fFTime The "Unix time" (the number of seconds relative to the midnight of January 1, 1970 UTC),
     * as a floating-point number capable of milliseconds.
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromFTime ($fFTime)
    {
        assert( 'is_float($fFTime)', vs(isset($this), get_defined_vars()) );

        $oTime = new self(null, null, true);
        $oTime->m_fFTime = $fFTime;
        return $oTime;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time by parsing a string formatted according to a specified pattern or, if the pattern is
     * unknown, by guessing the format.
     *
     * For better performance and reliability, it's always preferable to tell the method the pattern according to which
     * the string is formatted whenever you happen to know that pattern. But if it's not provided, the method will try
     * its best to guess the format and is likely to succeed for nearly any valid textual representation of date/time
     * in English.
     *
     * If the string does not indicate any time zone, the time zone associated with the date/time components in the
     * string is assumed to be UTC.
     *
     * @param  string $sString The string to be parsed.
     * @param  enum|string $xPattern **OPTIONAL. Default is** *take a guess*. The pattern according to which the string
     * is formatted. See [Summary](#summary) for the enumerands of the most widespread patterns or
     * [date](http://www.php.net/manual/en/function.date.php) in the PHP Manual for the format characters that you can
     * use to compose a custom pattern.
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromString ($sString, $xPattern = null)
    {
        assert( 'is_cstring($sString) && (!isset($xPattern) || is_enum($xPattern) || is_cstring($xPattern))',
            vs(isset($this), get_defined_vars()) );

        $iUTime;
        if ( isset($xPattern) )
        {
            // Parse the string using the pattern provided.
            $sPattern = ( is_enum($xPattern) ) ? self::patternEnumToString($xPattern) : $xPattern;
            $oDt = DateTime::createFromFormat($sPattern, $sString, new DateTimeZone(CTimeZone::UTC));
            if ( !is_object($oDt) )
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
            }
            $iUTime = $oDt->getTimestamp();
        }
        else
        {
            // Guess.
            $sSysDefTz = date_default_timezone_get();
            date_default_timezone_set(CTimeZone::UTC);
            $iUTime = strtotime($sString);
            date_default_timezone_set($sSysDefTz);
            if ( !is_int($iUTime) )
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
            }
        }
        return new self($iUTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if specified date/time components are valid in separate and as a combination.
     *
     * For example, the combination of February 29 with the year of 2000 is valid but, if combined with the year of
     * 2005, is not.
     *
     * @param  int $iYear The year.
     * @param  int $iMonth The number of the month (the valid range is from 1 to 12).
     * @param  int $iDay The number of the day (the valid range is from 1 to 31).
     * @param  int $iHour **OPTIONAL. Default is** `0`. The number of the hour (the valid range is from 0 to 23).
     * @param  int $iMinute **OPTIONAL. Default is** `0`. The number of the minute (the valid range is from 0 to 59).
     * @param  int $iSecond **OPTIONAL. Default is** `0`. The number of the second (the valid range is from 0 to 59).
     * @param  int $iMillisecond **OPTIONAL. Default is** `0`. The number of the millisecond (the valid range is from 0
     * to 999).
     *
     * @return bool `true` if the components are valid in separate and as a combination, `false` otherwise.
     */

    public static function areComponentsValid ($iYear, $iMonth, $iDay, $iHour = 0, $iMinute = 0, $iSecond = 0,
        $iMillisecond = 0)
    {
        assert( 'is_int($iYear) && is_int($iMonth) && is_int($iDay) && is_int($iHour) && is_int($iMinute) && ' .
                'is_int($iSecond) && is_int($iMillisecond)', vs(isset($this), get_defined_vars()) );

        if ( !(1 <= $iMonth && $iMonth <= 12) )
        {
            return false;
        }
        if ( !(1 <= $iDay && $iDay <= 31) )
        {
            return false;
        }
        if ( !(0 <= $iHour && $iHour <= 23) )
        {
            return false;
        }
        if ( !(0 <= $iMinute && $iMinute <= 59) )
        {
            return false;
        }
        if ( !(0 <= $iSecond && $iSecond <= 59) )
        {
            return false;
        }
        if ( !(0 <= $iMillisecond && $iMillisecond <= 999) )
        {
            return false;
        }
        return checkdate($iMonth, $iDay, $iYear);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from date/time components as they would appear in the UTC time zone.
     *
     * @param  int $iYear The year.
     * @param  int $iMonth **OPTIONAL. Default is** `1`. The number of the month (from 1 to 12).
     * @param  int $iDay **OPTIONAL. Default is** `1`. The number of the day (from 1 to 31).
     * @param  int $iHour **OPTIONAL. Default is** `0`. The number of the hour (from 0 to 23).
     * @param  int $iMinute **OPTIONAL. Default is** `0`. The number of the minute (from 0 to 59).
     * @param  int $iSecond **OPTIONAL. Default is** `0`. The number of the second (from 0 to 59).
     * @param  int $iMillisecond **OPTIONAL. Default is** `0`. The number of the millisecond (from 0 to 999).
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromComponentsUtc ($iYear, $iMonth = 1, $iDay = 1, $iHour = 0, $iMinute = 0, $iSecond = 0,
        $iMillisecond = 0)
    {
        assert( 'is_int($iYear) && is_int($iMonth) && is_int($iDay) && is_int($iHour) && is_int($iMinute) && ' .
                'is_int($iSecond) && is_int($iMillisecond)', vs(isset($this), get_defined_vars()) );

        return self::componentsInTimeZoneToTime($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond,
            CTimeZone::UTC);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from date/time components as they would appear in a specified time zone.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the components got their values.
     * @param  int $iYear The year.
     * @param  int $iMonth **OPTIONAL. Default is** `1`. The number of the month (from 1 to 12).
     * @param  int $iDay **OPTIONAL. Default is** `1`. The number of the day (from 1 to 31).
     * @param  int $iHour **OPTIONAL. Default is** `0`. The number of the hour (from 0 to 23).
     * @param  int $iMinute **OPTIONAL. Default is** `0`. The number of the minute (from 0 to 59).
     * @param  int $iSecond **OPTIONAL. Default is** `0`. The number of the second (from 0 to 59).
     * @param  int $iMillisecond **OPTIONAL. Default is** `0`. The number of the millisecond (from 0 to 999).
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromComponentsInTimeZone (CTimeZone $oTimeZone, $iYear, $iMonth = 1, $iDay = 1, $iHour = 0,
        $iMinute = 0, $iSecond = 0, $iMillisecond = 0)
    {
        assert( 'is_int($iYear) && is_int($iMonth) && is_int($iDay) && is_int($iHour) && is_int($iMinute) && ' .
                'is_int($iSecond) && is_int($iMillisecond)', vs(isset($this), get_defined_vars()) );

        return self::componentsInTimeZoneToTime($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond,
            $oTimeZone);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from date/time components as they would appear in the PHP's default time zone.
     *
     * @param  int $iYear The year.
     * @param  int $iMonth **OPTIONAL. Default is** `1`. The number of the month (from 1 to 12).
     * @param  int $iDay **OPTIONAL. Default is** `1`. The number of the day (from 1 to 31).
     * @param  int $iHour **OPTIONAL. Default is** `0`. The number of the hour (from 0 to 23).
     * @param  int $iMinute **OPTIONAL. Default is** `0`. The number of the minute (from 0 to 59).
     * @param  int $iSecond **OPTIONAL. Default is** `0`. The number of the second (from 0 to 59).
     * @param  int $iMillisecond **OPTIONAL. Default is** `0`. The number of the millisecond (from 0 to 999).
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromComponentsLocal ($iYear, $iMonth = 1, $iDay = 1, $iHour = 0, $iMinute = 0, $iSecond = 0,
        $iMillisecond = 0)
    {
        assert( 'is_int($iYear) && is_int($iMonth) && is_int($iDay) && is_int($iHour) && is_int($iMinute) && ' .
                'is_int($iSecond) && is_int($iMillisecond)', vs(isset($this), get_defined_vars()) );

        return self::componentsInTimeZoneToTime($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond,
            date_default_timezone_get());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a point in time into a string according to a formatting pattern, so that the values of the date/time
     * components in the resulting string appear in the UTC time zone.
     *
     * For locale-aware formatting of points in time as strings, which lets users from almost any country read time in
     * the way that they are accustomed to, you may want to try the [CUFormat](CUFormat.html) class.
     *
     * @param  enum|string $xPattern **OPTIONAL. Default is** `PATTERN_DEFAULT`. The pattern to be used for formatting.
     * See [Summary](#summary) for the enumerands of the most widespread patterns or
     * [date](http://www.php.net/manual/en/function.date.php) in the PHP Manual for the format characters that you can
     * use to compose a custom pattern.
     *
     * @return CUStringObject The resulting string.
     */

    public function toStringUtc ($xPattern = self::PATTERN_DEFAULT)
    {
        assert( 'is_enum($xPattern) || is_cstring($xPattern)', vs(isset($this), get_defined_vars()) );

        if ( is_enum($xPattern) && $xPattern == self::PATTERN_HTTP_HEADER_GMT )
        {
            // A special case.
            $sTime = gmdate(self::patternEnumToString(self::PATTERN_HTTP_HEADER_GMT), $this->UTime());
            assert( 'is_cstring($sTime)', vs(isset($this), get_defined_vars()) );
            return $sTime;
        }
        return self::timeToStringInTimeZone($this, $xPattern, CTimeZone::UTC);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a point in time into a string according to a formatting pattern, so that the values of the date/time
     * components in the resulting string appear in a specified time zone.
     *
     * For locale-aware formatting of points in time as strings, which lets users from almost any country read time in
     * the way that they are accustomed to, you may want to try the [CUFormat](CUFormat.html) class.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the components in the resulting string are to appear.
     * @param  enum|string $xPattern **OPTIONAL. Default is** `PATTERN_DEFAULT`. The pattern to be used for formatting.
     * See [Summary](#summary) for the enumerands of the most widespread patterns or
     * [date](http://www.php.net/manual/en/function.date.php) in the PHP Manual for the format characters that you can
     * use to compose a custom pattern.
     *
     * @return CUStringObject The resulting string.
     */

    public function toStringInTimeZone (CTimeZone $oTimeZone, $xPattern = self::PATTERN_DEFAULT)
    {
        assert( 'is_enum($xPattern) || is_cstring($xPattern)', vs(isset($this), get_defined_vars()) );
        return self::timeToStringInTimeZone($this, $xPattern, $oTimeZone);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a point in time into a string according to a formatting pattern, so that the values of the date/time
     * components in the resulting string appear in the PHP's default time zone.
     *
     * For locale-aware formatting of points in time as strings, which lets users from almost any country read time in
     * the way that they are accustomed to, you may want to try the [CUFormat](CUFormat.html) class.
     *
     * @param  enum|string $xPattern **OPTIONAL. Default is** `PATTERN_DEFAULT`. The pattern to be used for formatting.
     * See [Summary](#summary) for the enumerands of the most widespread patterns or
     * [date](http://www.php.net/manual/en/function.date.php) in the PHP Manual for the format characters that you can
     * use to compose a custom pattern.
     *
     * @return CUStringObject The resulting string.
     */

    public function toStringLocal ($xPattern = self::PATTERN_DEFAULT)
    {
        assert( 'is_enum($xPattern) || is_cstring($xPattern)', vs(isset($this), get_defined_vars()) );
        return self::timeToStringInTimeZone($this, $xPattern, date_default_timezone_get());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the "Unix time" of a point in time.
     *
     * The "Unix time", which is the number of seconds relative to the midnight of January 1, 1970 UTC, is positive for
     * any point in time after that moment and is negative for any point in time before it.
     *
     * @return int The "Unix time" of the point in time.
     */

    public function UTime ()
    {
        return (int)$this->m_fFTime;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the milliseconds of a point in time.
     *
     * If not zero, the returned value is positive for any point in time after the midnight of January 1, 1970 UTC and
     * is negative for any point in time before it.
     *
     * @return int The milliseconds of the point in time (from 0 to 999 or from 0 to -999).
     */

    public function MTime ()
    {
        return CMathi::round(($this->m_fFTime - (int)$this->m_fFTime)*1000);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the "Unix time" of a point in time, as a floating-point number.
     *
     * The "Unix time", which is the number of seconds relative to the midnight of January 1, 1970 UTC, is positive for
     * any point in time after that moment and is negative for any point in time before it.
     *
     * @return float The "Unix time" of the point in time, possibly indicating the milliseconds in its fractional part.
     */

    public function FTime ()
    {
        return $this->m_fFTime;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a point in time is equal to another such point.
     *
     * If any of the two points in time goes as far as milliseconds in its time resolution, the method also compares
     * the milliseconds of the points.
     *
     * @param  CTime $oToTime The second point in time for comparison.
     *
     * @return bool `true` if the two points in time are equal, `false` otherwise.
     */

    public function equals ($oToTime)
    {
        // Parameter type hinting is not used for the purpose of interface compatibility.
        assert( 'is_ctime($oToTime)', vs(isset($this), get_defined_vars()) );
        return ( $this->UTime() == $oToTime->UTime() && $this->MTime() == $oToTime->MTime() );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a point in time happened or will happen before another such point.
     *
     * If any of the two points in time goes as far as milliseconds in its time resolution, the method also compares
     * the milliseconds of the points.
     *
     * @param  CTime $oTime The second point in time for comparison.
     *
     * @return bool `true` if *this* point in time precedes the second point in time, `false` otherwise.
     */

    public function isBefore (CTime $oTime)
    {
        return ( $this->m_fFTime < $oTime->m_fFTime );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a point in time happened or will happen after another such point.
     *
     * If any of the two points in time goes as far as milliseconds in its time resolution, the method also compares
     * the milliseconds of the points.
     *
     * @param  CTime $oTime The second point in time for comparison.
     *
     * @return bool `true` if *this* point in time succeeds the second point in time, `false` otherwise.
     */

    public function isAfter (CTime $oTime)
    {
        return ( $this->m_fFTime > $oTime->m_fFTime );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two points in time should appear in a place where it matters.
     *
     * If any of the two points in time goes as far as milliseconds in its time resolution, the method also compares
     * the milliseconds of the points.
     *
     * @param  CTime $oToTime The second point in time for comparison.
     *
     * @return int A negative value (typically `-1`) if *this* point in time should go before the second point in time,
     * a positive value (typically `1`) if the other way around, and `0` if the two points in time are equal.
     */

    public function compare ($oToTime)
    {
        // Parameter type hinting is not used for the purpose of interface compatibility.
        assert( 'is_ctime($oToTime)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign($this->m_fFTime - $oToTime->m_fFTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the current "Unix time", which is the number of seconds that have elapsed since the midnight of January
     * 1, 1970 UTC.
     *
     * @return int The current "Unix time".
     */

    public static function currentUTime ()
    {
        return time();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the number of the current day of the year in the UTC time zone.
     *
     * Days of year are numbered starting with 0.
     *
     * @return int The number of the current day of the year.
     */

    public static function currentDayOfYearUtc ()
    {
        return CString::toInt(gmdate("z"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the number of the current day of the year in a specified time zone.
     *
     * Days of year are numbered starting with 0.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the current day is to get its number.
     *
     * @return int The number of the current day of the year.
     */

    public static function currentDayOfYearInTimeZone (CTimeZone $oTimeZone)
    {
        return CString::toInt(self::timeToStringInTimeZone(self::now(), "z", $oTimeZone));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the number of the current day of the year in the PHP's default time zone.
     *
     * Days of year are numbered starting with 0.
     *
     * @return int The number of the current day of the year.
     */

    public static function currentDayOfYearLocal ()
    {
        return CString::toInt(date("z"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Outputs the date/time components that define a point in time in the UTC time zone.
     *
     * You can use one of the dedicated methods when you only want to know the value of a single component, e.g.
     * `monthUtc`.
     *
     * @param  reference $riYear **OUTPUT.** The year, of type `int`.
     * @param  reference $riMonth **OPTIONAL. OUTPUT.** The number of the month (from 1 to 12), of type `int`.
     * @param  reference $riDay **OPTIONAL. OUTPUT.** The number of the day (from 1 to 31), of type `int`.
     * @param  reference $riHour **OPTIONAL. OUTPUT.** The number of the hour (from 0 to 23), of type `int`.
     * @param  reference $riMinute **OPTIONAL. OUTPUT.** The number of the minute (from 0 to 59), of type `int`.
     * @param  reference $riSecond **OPTIONAL. OUTPUT.** The number of the second (from 0 to 59), of type `int`.
     * @param  reference $riMillisecond **OPTIONAL. OUTPUT.** The number of the millisecond (from 0 to 999), of type
     * `int`.
     * @param  reference $reDayOfWeek **OPTIONAL. OUTPUT.** The day of the week, of type `enum`. Can be `SUNDAY`,
     * `MONDAY`, `TUESDAY`, `WEDNESDAY`, `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @return void
     */

    public function componentsUtc (&$riYear, &$riMonth = null, &$riDay = null, &$riHour = null, &$riMinute = null,
        &$riSecond = null, &$riMillisecond = null, &$reDayOfWeek = null)
    {
        self::timeToComponentsInTimeZone($this, CTimeZone::UTC, $riYear, $riMonth, $riDay, $riHour, $riMinute,
            $riSecond, $riMillisecond, $reDayOfWeek);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the year to which a point in time belongs in the UTC time zone.
     *
     * You can use `componentsUtc` method when you want to know the values of multiple date/time components.
     *
     * @return int The year of the point in time.
     *
     * @link   #method_componentsUtc componentsUtc
     */

    public function yearUtc ()
    {
        $iYear;
        $this->componentsUtc($iYear);
        return $iYear;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the month to which a point in time belongs in the UTC time zone.
     *
     * You can use `componentsUtc` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the month of the point in time (from 1 to 12).
     *
     * @link   #method_componentsUtc componentsUtc
     */

    public function monthUtc ()
    {
        $iYear;
        $iMonth;
        $this->componentsUtc($iYear, $iMonth);
        return $iMonth;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the day to which a point in time belongs in the UTC time zone.
     *
     * You can use `componentsUtc` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the day of the point in time (from 1 to 31).
     *
     * @link   #method_componentsUtc componentsUtc
     */

    public function dayUtc ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $this->componentsUtc($iYear, $iMonth, $iDay);
        return $iDay;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the hour to which a point in time belongs in the UTC time zone.
     *
     * You can use `componentsUtc` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the hour of the point in time (from 0 to 23).
     *
     * @link   #method_componentsUtc componentsUtc
     */

    public function hourUtc ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour);
        return $iHour;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the minute to which a point in time belongs in the UTC time zone.
     *
     * You can use `componentsUtc` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the minute of the point in time (from 0 to 59).
     *
     * @link   #method_componentsUtc componentsUtc
     */

    public function minuteUtc ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute);
        return $iMinute;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the second to which a point in time belongs in the UTC time zone.
     *
     * You can use `componentsUtc` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the second of the point in time (from 0 to 59).
     *
     * @link   #method_componentsUtc componentsUtc
     */

    public function secondUtc ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        return $iSecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the millisecond to which a point in time belongs.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     * You can use `componentsUtc` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the millisecond of the point in time (from 0 to 999).
     *
     * @link   #method_componentsUtc componentsUtc
     */

    public function millisecondUtc ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        return $iMillisecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the day-of-week to which a point in time belongs in the UTC time zone.
     *
     * You can use `componentsUtc` method when you want to know the values of multiple date/time components.
     *
     * @return enum The day-of-week of the point in time. Can be `SUNDAY`, `MONDAY`, `TUESDAY`, `WEDNESDAY`,
     * `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @link   #method_componentsUtc componentsUtc
     */

    public function dayOfWeekUtc ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond, $eDayOfWeek);
        return $eDayOfWeek;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Outputs the date/time components that define a point in time in a specified time zone.
     *
     * You can use one of the dedicated methods when you only want to know the value of a single component, e.g.
     * `monthInTimeZone`.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the components are to get their values.
     * @param  reference $riYear **OUTPUT.** The year, of type `int`.
     * @param  reference $riMonth **OPTIONAL. OUTPUT.** The number of the month (from 1 to 12), of type `int`.
     * @param  reference $riDay **OPTIONAL. OUTPUT.** The number of the day (from 1 to 31), of type `int`.
     * @param  reference $riHour **OPTIONAL. OUTPUT.** The number of the hour (from 0 to 23), of type `int`.
     * @param  reference $riMinute **OPTIONAL. OUTPUT.** The number of the minute (from 0 to 59), of type `int`.
     * @param  reference $riSecond **OPTIONAL. OUTPUT.** The number of the second (from 0 to 59), of type `int`.
     * @param  reference $riMillisecond **OPTIONAL. OUTPUT.** The number of the millisecond (from 0 to 999), of type
     * `int`.
     * @param  reference $reDayOfWeek **OPTIONAL. OUTPUT.** The day of the week, of type `enum`. Can be `SUNDAY`,
     * `MONDAY`, `TUESDAY`, `WEDNESDAY`, `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @return void
     */

    public function componentsInTimeZone (CTimeZone $oTimeZone, &$riYear, &$riMonth = null, &$riDay = null,
        &$riHour = null, &$riMinute = null, &$riSecond = null, &$riMillisecond = null, &$reDayOfWeek = null)
    {
        self::timeToComponentsInTimeZone($this, $oTimeZone, $riYear, $riMonth, $riDay, $riHour, $riMinute, $riSecond,
            $riMillisecond, $reDayOfWeek);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the year to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to get its value.
     *
     * @return int The year of the point in time.
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function yearInTimeZone (CTimeZone $oTimeZone)
    {
        $iYear;
        $this->componentsInTimeZone($oTimeZone, $iYear);
        return $iYear;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the month to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the month of the point in time (from 1 to 12).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function monthInTimeZone (CTimeZone $oTimeZone)
    {
        $iYear;
        $iMonth;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth);
        return $iMonth;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the day to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the day of the point in time (from 1 to 31).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function dayInTimeZone (CTimeZone $oTimeZone)
    {
        $iYear;
        $iMonth;
        $iDay;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay);
        return $iDay;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the hour to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the hour of the point in time (from 0 to 23).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function hourInTimeZone (CTimeZone $oTimeZone)
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour);
        return $iHour;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the minute to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the minute of the point in time (from 0 to 59).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function minuteInTimeZone (CTimeZone $oTimeZone)
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute);
        return $iMinute;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the second to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the second of the point in time (from 0 to 59).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function secondInTimeZone (CTimeZone $oTimeZone)
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        return $iSecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the millisecond to which a point in time belongs.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the millisecond of the point in time (from 0 to 999).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function millisecondInTimeZone (CTimeZone $oTimeZone)
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        return $iMillisecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the day-of-week to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to get its value.
     *
     * @return enum The day-of-week of the point in time. Can be `SUNDAY`, `MONDAY`, `TUESDAY`, `WEDNESDAY`,
     * `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function dayOfWeekInTimeZone (CTimeZone $oTimeZone)
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond,
            $eDayOfWeek);
        return $eDayOfWeek;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Outputs the date/time components that define a point in time in the PHP's default time zone.
     *
     * You can use one of the dedicated methods when you only want to know the value of a single component, e.g.
     * `monthLocal`.
     *
     * @param  reference $riYear **OUTPUT.** The year, of type `int`.
     * @param  reference $riMonth **OPTIONAL. OUTPUT.** The number of the month (from 1 to 12), of type `int`.
     * @param  reference $riDay **OPTIONAL. OUTPUT.** The number of the day (from 1 to 31), of type `int`.
     * @param  reference $riHour **OPTIONAL. OUTPUT.** The number of the hour (from 0 to 23), of type `int`.
     * @param  reference $riMinute **OPTIONAL. OUTPUT.** The number of the minute (from 0 to 59), of type `int`.
     * @param  reference $riSecond **OPTIONAL. OUTPUT.** The number of the second (from 0 to 59), of type `int`.
     * @param  reference $riMillisecond **OPTIONAL. OUTPUT.** The number of the millisecond (from 0 to 999), of type
     * `int`.
     * @param  reference $reDayOfWeek **OPTIONAL. OUTPUT.** The day of the week, of type `enum`. Can be `SUNDAY`,
     * `MONDAY`, `TUESDAY`, `WEDNESDAY`, `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @return void
     */

    public function componentsLocal (&$riYear, &$riMonth = null, &$riDay = null, &$riHour = null, &$riMinute = null,
        &$riSecond = null, &$riMillisecond = null, &$reDayOfWeek = null)
    {
        self::timeToComponentsInTimeZone($this, date_default_timezone_get(), $riYear, $riMonth, $riDay, $riHour,
            $riMinute, $riSecond, $riMillisecond, $reDayOfWeek);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the year to which a point in time belongs in the PHP's default time zone.
     *
     * You can use `componentsLocal` method when you want to know the values of multiple date/time components.
     *
     * @return int The year of the point in time.
     *
     * @link   #method_componentsLocal componentsLocal
     */

    public function yearLocal ()
    {
        $iYear;
        $this->componentsLocal($iYear);
        return $iYear;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the month to which a point in time belongs in the PHP's default time zone.
     *
     * You can use `componentsLocal` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the month of the point in time (from 1 to 12).
     *
     * @link   #method_componentsLocal componentsLocal
     */

    public function monthLocal ()
    {
        $iYear;
        $iMonth;
        $this->componentsLocal($iYear, $iMonth);
        return $iMonth;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the day to which a point in time belongs in the PHP's default time zone.
     *
     * You can use `componentsLocal` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the day of the point in time (from 1 to 31).
     *
     * @link   #method_componentsLocal componentsLocal
     */

    public function dayLocal ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $this->componentsLocal($iYear, $iMonth, $iDay);
        return $iDay;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the hour to which a point in time belongs in the PHP's default time zone.
     *
     * You can use `componentsLocal` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the hour of the point in time (from 0 to 23).
     *
     * @link   #method_componentsLocal componentsLocal
     */

    public function hourLocal ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour);
        return $iHour;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the minute to which a point in time belongs in the PHP's default time zone.
     *
     * You can use `componentsLocal` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the minute of the point in time (from 0 to 59).
     *
     * @link   #method_componentsLocal componentsLocal
     */

    public function minuteLocal ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute);
        return $iMinute;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the second to which a point in time belongs in the PHP's default time zone.
     *
     * You can use `componentsLocal` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the second of the point in time (from 0 to 59).
     *
     * @link   #method_componentsLocal componentsLocal
     */

    public function secondLocal ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond);
        return $iSecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the millisecond to which a point in time belongs.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     * You can use `componentsLocal` method when you want to know the values of multiple date/time components.
     *
     * @return int The number of the millisecond of the point in time (from 0 to 999).
     *
     * @link   #method_componentsLocal componentsLocal
     */

    public function millisecondLocal ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        return $iMillisecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the day-of-week to which a point in time belongs in the PHP's default time zone.
     *
     * You can use `componentsLocal` method when you want to know the values of multiple date/time components.
     *
     * @return enum The day-of-week of the point in time. Can be `SUNDAY`, `MONDAY`, `TUESDAY`, `WEDNESDAY`,
     * `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @link   #method_componentsLocal componentsLocal
     */

    public function dayOfWeekLocal ()
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $eDayOfWeek;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond, $eDayOfWeek);
        return $eDayOfWeek;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in a specified time unit as
     * an absolute value.
     *
     * The difference is calculated as an integer number, rounding *down* from the fractional difference if needed.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     * @param  enum $eTimeUnit The time unit in which the difference is to be calculated. Can be `SECOND`, `MINUTE`,
     * `HOUR`, `DAY`, `WEEK`, `MONTH`, or `YEAR`.
     *
     * @return int The absolute difference between the two points in time, measured in the time unit specified.
     */

    public function diff (CTime $oFromTime, $eTimeUnit)
    {
        assert( 'is_enum($eTimeUnit)', vs(isset($this), get_defined_vars()) );

        $oDtz = new DateTimeZone(CTimeZone::UTC);
        $oThisDt = new DateTime();
        $oThisDt->setTimestamp($this->UTime());
        $oThisDt->setTimezone($oDtz);
        $oFromDt = new DateTime();
        $oFromDt->setTimestamp($oFromTime->UTime());
        $oFromDt->setTimezone($oDtz);
        $oDti = $oFromDt->diff($oThisDt, true);
        assert( 'is_number($oDti->days)', vs(isset($this), get_defined_vars()) );

        $iDiffInSeconds =
            CMathi::abs($oDti->days)*self::SECONDS_PER_DAY +
            CMathi::abs($oDti->h)*self::SECONDS_PER_HOUR +
            CMathi::abs($oDti->i)*self::SECONDS_PER_MINUTE +
            CMathi::abs($oDti->s);
        $fDiffInSeconds = (float)$iDiffInSeconds;
        switch ( $eTimeUnit )
        {
        case self::SECOND:
            return $iDiffInSeconds;
        case self::MINUTE:
            return CMathi::floor($fDiffInSeconds/self::SECONDS_PER_MINUTE);
        case self::HOUR:
            return CMathi::floor($fDiffInSeconds/self::SECONDS_PER_HOUR);
        case self::DAY:
            return CMathi::floor($fDiffInSeconds/self::SECONDS_PER_DAY);
        case self::WEEK:
            return CMathi::floor($fDiffInSeconds/self::SECONDS_PER_WEEK);
        case self::MONTH:
            return CMathi::floor($fDiffInSeconds/self::SECONDS_PER_MONTH);
        case self::YEAR:
            return CMathi::floor($fDiffInSeconds/self::SECONDS_PER_YEAR);
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in seconds as an absolute
     * value.
     *
     * The difference is calculated as an integer number, rounding *down* from the fractional difference if needed.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in seconds.
     */

    public function diffInSeconds (CTime $oFromTime)
    {
        return $this->diff($oFromTime, self::SECOND);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in minutes as an absolute
     * value.
     *
     * The difference is calculated as an integer number, rounding *down* from the fractional difference if needed.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in minutes.
     */

    public function diffInMinutes (CTime $oFromTime)
    {
        return $this->diff($oFromTime, self::MINUTE);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in hours as an absolute
     * value.
     *
     * The difference is calculated as an integer number, rounding *down* from the fractional difference if needed.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in hours.
     */

    public function diffInHours (CTime $oFromTime)
    {
        return $this->diff($oFromTime, self::HOUR);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in days as an absolute value.
     *
     * The difference is calculated as an integer number, rounding *down* from the fractional difference if needed.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in days.
     */

    public function diffInDays (CTime $oFromTime)
    {
        return $this->diff($oFromTime, self::DAY);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in weeks as an absolute
     * value.
     *
     * The difference is calculated as an integer number, rounding *down* from the fractional difference if needed.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in weeks.
     */

    public function diffInWeeks (CTime $oFromTime)
    {
        return $this->diff($oFromTime, self::WEEK);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in months as an absolute
     * value.
     *
     * The difference is calculated as an integer number, rounding *down* from the fractional difference if needed.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in months.
     */

    public function diffInMonths (CTime $oFromTime)
    {
        return $this->diff($oFromTime, self::MONTH);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in years as an absolute
     * value.
     *
     * The difference is calculated as an integer number, rounding *down* from the fractional difference if needed.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in years.
     */

    public function diffInYears (CTime $oFromTime)
    {
        return $this->diff($oFromTime, self::YEAR);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in every time unit as a
     * combined shift that would need to be made to get from the earlier point into the later one.
     *
     * It is only the number of weeks and the number of years that are open-ended, the others are restricted by their
     * respective limits (differences are zero-based, so days can vary from 0 to 30 and months from 0 to 11). The
     * outputted values are always positive.
     *
     * To illustrate, the difference between 00:00:00 on February 10, 2008 and 23:31:30 on February 13, 2009 is 1 year,
     * 0 months, 4 days (because 2008 is a leap year), 23 hours, 31 minutes, and 30 seconds, while the difference in
     * weeks is 52.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     * @param  reference $riNumSeconds **OUTPUT.** The number of seconds (from 0 to 59), of type `int`.
     * @param  reference $riNumMinutes **OPTIONAL. OUTPUT.** The number of minutes (from 0 to 59), of type `int`.
     * @param  reference $riNumHours **OPTIONAL. OUTPUT.** The number of hours (from 0 to 23), of type `int`.
     * @param  reference $riNumDays **OPTIONAL. OUTPUT.** The number of days (from 0 to 30), of type `int`.
     * @param  reference $riNumWeeks **OPTIONAL. OUTPUT.** The number of weeks, of type `int`.
     * @param  reference $riNumMonths **OPTIONAL. OUTPUT.** The number of months (from 0 to 11), of type `int`.
     * @param  reference $riNumYears **OPTIONAL. OUTPUT.** The number of years, of type `int`.
     *
     * @return void
     */

    public function diffUnits (CTime $oFromTime, &$riNumSeconds, &$riNumMinutes = null, &$riNumHours = null,
        &$riNumDays = null, &$riNumWeeks = null, &$riNumMonths = null, &$riNumYears = null)
    {
        $iSecondQty = $this->diffInSeconds($oFromTime);

        if ( $iSecondQty < self::SECONDS_PER_MINUTE )
        {
            $riNumSeconds = $iSecondQty;
            $riNumMinutes = 0;
            $riNumHours = 0;
            $riNumDays = 0;
            $riNumWeeks = 0;
            $riNumMonths = 0;
            $riNumYears = 0;
        }
        else if ( $iSecondQty < self::SECONDS_PER_HOUR )
        {
            $riNumSeconds = $iSecondQty % self::SECONDS_PER_MINUTE;
            $riNumMinutes = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_MINUTE);
            $riNumHours = 0;
            $riNumDays = 0;
            $riNumWeeks = 0;
            $riNumMonths = 0;
            $riNumYears = 0;
        }
        else if ( $iSecondQty < self::SECONDS_PER_DAY )
        {
            $riNumSeconds = $iSecondQty % self::SECONDS_PER_MINUTE;
            $riNumMinutes = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $riNumHours = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_HOUR);
            $riNumDays = 0;
            $riNumWeeks = 0;
            $riNumMonths = 0;
            $riNumYears = 0;
        }
        else if ( $iSecondQty < self::SECONDS_PER_WEEK )
        {
            $riNumSeconds = $iSecondQty % self::SECONDS_PER_MINUTE;
            $riNumMinutes = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $riNumHours = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_DAY))/self::SECONDS_PER_HOUR);
            $riNumDays = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_DAY);
            $riNumWeeks = 0;
            $riNumMonths = 0;
            $riNumYears = 0;
        }
        else if ( $iSecondQty < self::SECONDS_PER_MONTH )
        {
            $riNumSeconds = $iSecondQty % self::SECONDS_PER_MINUTE;
            $riNumMinutes = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $riNumHours = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_DAY))/self::SECONDS_PER_HOUR);
            $riNumDays = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_DAY);
            $riNumWeeks = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_WEEK);
            $riNumMonths = 0;
            $riNumYears = 0;
        }
        else if ( $iSecondQty < self::SECONDS_PER_YEAR )
        {
            $riNumSeconds = $iSecondQty % self::SECONDS_PER_MINUTE;
            $riNumMinutes = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $riNumHours = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_DAY))/self::SECONDS_PER_HOUR);
            $riNumDays = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_MONTH))/self::SECONDS_PER_DAY);
            $riNumWeeks = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_WEEK);
            $riNumMonths = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_MONTH);
            $riNumYears = 0;
        }
        else  // $iSecondQty >= self::SECONDS_PER_YEAR
        {
            $riNumSeconds = $iSecondQty % self::SECONDS_PER_MINUTE;
            $riNumMinutes = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $riNumHours = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_DAY))/self::SECONDS_PER_HOUR);
            $riNumDays = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_MONTH))/self::SECONDS_PER_DAY);
            $riNumWeeks = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_WEEK);
            $riNumMonths = CMathi::floor(((float)($iSecondQty % self::SECONDS_PER_YEAR))/self::SECONDS_PER_MONTH);
            $riNumYears = CMathi::floor(((float)$iSecondQty)/self::SECONDS_PER_YEAR);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in a specified time unit as
     * a positive or negative value.
     *
     * The difference is calculated by subtracting the provided point in time from *this* point in time and as an
     * integer number, rounding *down* from the fractional difference if needed for a positive value or rounding *up*
     * for a negative one.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     * @param  enum $eTimeUnit The time unit in which the difference is to be calculated. Can be `SECOND`, `MINUTE`,
     * `HOUR`, `DAY`, `WEEK`, `MONTH`, or `YEAR`.
     *
     * @return int The difference between the two points in time, measured in the time unit specified and as a positive
     * value if *this* point in time goes after the second one and as a negative value if *this* point in time goes
     * before the second one (except if the result is zero).
     */

    public function signedDiff (CTime $oFromTime, $eTimeUnit)
    {
        assert( 'is_enum($eTimeUnit)', vs(isset($this), get_defined_vars()) );

        $oDtz = new DateTimeZone(CTimeZone::UTC);
        $oThisDt = new DateTime();
        $oThisDt->setTimestamp($this->UTime());
        $oThisDt->setTimezone($oDtz);
        $oFromDt = new DateTime();
        $oFromDt->setTimestamp($oFromTime->UTime());
        $oFromDt->setTimezone($oDtz);
        $oDti = $oFromDt->diff($oThisDt, false);
        assert( 'is_number($oDti->days)', vs(isset($this), get_defined_vars()) );

        $iDiffInSeconds =
            CMathi::abs($oDti->days)*self::SECONDS_PER_DAY +
            CMathi::abs($oDti->h)*self::SECONDS_PER_HOUR +
            CMathi::abs($oDti->i)*self::SECONDS_PER_MINUTE +
            CMathi::abs($oDti->s);
        $fDiffInSeconds = (float)$iDiffInSeconds;
        $iDiff;
        switch ( $eTimeUnit )
        {
        case self::SECOND:
            $iDiff = $iDiffInSeconds;
            break;
        case self::MINUTE:
            $iDiff = CMathi::floor($fDiffInSeconds/self::SECONDS_PER_MINUTE);
            break;
        case self::HOUR:
            $iDiff = CMathi::floor($fDiffInSeconds/self::SECONDS_PER_HOUR);
            break;
        case self::DAY:
            $iDiff = CMathi::floor($fDiffInSeconds/self::SECONDS_PER_DAY);
            break;
        case self::WEEK:
            $iDiff = CMathi::floor($fDiffInSeconds/self::SECONDS_PER_WEEK);
            break;
        case self::MONTH:
            $iDiff = CMathi::floor($fDiffInSeconds/self::SECONDS_PER_MONTH);
            break;
        case self::YEAR:
            $iDiff = CMathi::floor($fDiffInSeconds/self::SECONDS_PER_YEAR);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        if ( $oDti->invert == 1 )
        {
            $iDiff = -$iDiff;
        }
        return $iDiff;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in seconds as a positive or
     * negative value.
     *
     * The difference is calculated by subtracting the provided point in time from *this* point in time and as an
     * integer number, rounding *down* from the fractional difference if needed for a positive value or rounding *up*
     * for a negative one.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in seconds as a positive value if *this*
     * point in time goes after the second one and as a negative value if *this* point in time goes before the second
     * one (except if the result is zero).
     */

    public function signedDiffInSeconds (CTime $oFromTime)
    {
        return $this->signedDiff($oFromTime, self::SECOND);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in minutes as a positive or
     * negative value.
     *
     * The difference is calculated by subtracting the provided point in time from *this* point in time and as an
     * integer number, rounding *down* from the fractional difference if needed for a positive value or rounding *up*
     * for a negative one.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in minutes as a positive value if *this*
     * point in time goes after the second one and as a negative value if *this* point in time goes before the second
     * one (except if the result is zero).
     */

    public function signedDiffInMinutes (CTime $oFromTime)
    {
        return $this->signedDiff($oFromTime, self::MINUTE);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in hours as a positive or
     * negative value.
     *
     * The difference is calculated by subtracting the provided point in time from *this* point in time and as an
     * integer number, rounding *down* from the fractional difference if needed for a positive value or rounding *up*
     * for a negative one.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in hours as a positive value if *this* point
     * in time goes after the second one and as a negative value if *this* point in time goes before the second one
     * (except if the result is zero).
     */

    public function signedDiffInHours (CTime $oFromTime)
    {
        return $this->signedDiff($oFromTime, self::HOUR);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in days as a positive or
     * negative value.
     *
     * The difference is calculated by subtracting the provided point in time from *this* point in time and as an
     * integer number, rounding *down* from the fractional difference if needed for a positive value or rounding *up*
     * for a negative one.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in days as a positive value if *this* point
     * in time goes after the second one and as a negative value if *this* point in time goes before the second one
     * (except if the result is zero).
     */

    public function signedDiffInDays (CTime $oFromTime)
    {
        return $this->signedDiff($oFromTime, self::DAY);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in weeks as a positive or
     * negative value.
     *
     * The difference is calculated by subtracting the provided point in time from *this* point in time and as an
     * integer number, rounding *down* from the fractional difference if needed for a positive value or rounding *up*
     * for a negative one.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in weeks as a positive value if *this* point
     * in time goes after the second one and as a negative value if *this* point in time goes before the second one
     * (except if the result is zero).
     */

    public function signedDiffInWeeks (CTime $oFromTime)
    {
        return $this->signedDiff($oFromTime, self::WEEK);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in months as a positive or
     * negative value.
     *
     * The difference is calculated by subtracting the provided point in time from *this* point in time and as an
     * integer number, rounding *down* from the fractional difference if needed for a positive value or rounding *up*
     * for a negative one.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in months as a positive value if *this*
     * point in time goes after the second one and as a negative value if *this* point in time goes before the second
     * one (except if the result is zero).
     */

    public function signedDiffInMonths (CTime $oFromTime)
    {
        return $this->signedDiff($oFromTime, self::MONTH);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Calculates the difference between a point in time and another such point, measured in years as a positive or
     * negative value.
     *
     * The difference is calculated by subtracting the provided point in time from *this* point in time and as an
     * integer number, rounding *down* from the fractional difference if needed for a positive value or rounding *up*
     * for a negative one.
     *
     * Differences between points in time are not tied to any time zone. Any days that were added by leap years between
     * the points are counted in.
     *
     * @param  CTime $oFromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in years as a positive value if *this* point
     * in time goes after the second one and as a negative value if *this* point in time goes before the second one
     * (except if the result is zero).
     */

    public function signedDiffInYears (CTime $oFromTime)
    {
        return $this->signedDiff($oFromTime, self::YEAR);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of time units and
     * returns the new point in time.
     *
     * @param  enum $eTimeUnit The time unit to be used for shifting. Can be `SECOND`, `MINUTE`, `HOUR`, `DAY`, `WEEK`,
     * `MONTH`, or `YEAR`.
     * @param  int $iQuantity The number of units by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedUtc ($eTimeUnit, $iQuantity)
    {
        assert( 'is_enum($eTimeUnit) && is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return self::shiftTimeInTimeZone($this, $eTimeUnit, $iQuantity, CTimeZone::UTC);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of seconds and returns
     * the new point in time.
     *
     * @param  int $iQuantity The number of seconds by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedBySecondsUtc ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::SECOND, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of minutes and returns
     * the new point in time.
     *
     * @param  int $iQuantity The number of minutes by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMinutesUtc ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::MINUTE, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of hours and returns
     * the new point in time.
     *
     * @param  int $iQuantity The number of hours by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByHoursUtc ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::HOUR, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of days and returns
     * the new point in time.
     *
     * @param  int $iQuantity The number of days by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByDaysUtc ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::DAY, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of weeks and returns
     * the new point in time.
     *
     * @param  int $iQuantity The number of weeks by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByWeeksUtc ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::WEEK, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of months and returns
     * the new point in time.
     *
     * @param  int $iQuantity The number of months by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMonthsUtc ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::MONTH, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of years and returns
     * the new point in time.
     *
     * @param  int $iQuantity The number of years by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByYearsUtc ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::YEAR, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of time units and
     * returns the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the point in time is to be shifted.
     * @param  enum $eTimeUnit The time unit to be used for shifting. Can be `SECOND`, `MINUTE`, `HOUR`, `DAY`, `WEEK`,
     * `MONTH`, or `YEAR`.
     * @param  int $iQuantity The number of units by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedInTimeZone (CTimeZone $oTimeZone, $eTimeUnit, $iQuantity)
    {
        assert( 'is_enum($eTimeUnit) && is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return self::shiftTimeInTimeZone($this, $eTimeUnit, $iQuantity, $oTimeZone);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of seconds and
     * returns the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the point in time is to be shifted.
     * @param  int $iQuantity The number of seconds by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedBySecondsInTimeZone (CTimeZone $oTimeZone, $iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($oTimeZone, self::SECOND, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of minutes and
     * returns the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the point in time is to be shifted.
     * @param  int $iQuantity The number of minutes by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMinutesInTimeZone (CTimeZone $oTimeZone, $iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($oTimeZone, self::MINUTE, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of hours and
     * returns the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the point in time is to be shifted.
     * @param  int $iQuantity The number of hours by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByHoursInTimeZone (CTimeZone $oTimeZone, $iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($oTimeZone, self::HOUR, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of days and
     * returns the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the point in time is to be shifted.
     * @param  int $iQuantity The number of days by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByDaysInTimeZone (CTimeZone $oTimeZone, $iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($oTimeZone, self::DAY, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of weeks and
     * returns the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the point in time is to be shifted.
     * @param  int $iQuantity The number of weeks by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByWeeksInTimeZone (CTimeZone $oTimeZone, $iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($oTimeZone, self::WEEK, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of months and
     * returns the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the point in time is to be shifted.
     * @param  int $iQuantity The number of months by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMonthsInTimeZone (CTimeZone $oTimeZone, $iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($oTimeZone, self::MONTH, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of years and
     * returns the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the point in time is to be shifted.
     * @param  int $iQuantity The number of years by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByYearsInTimeZone (CTimeZone $oTimeZone, $iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($oTimeZone, self::YEAR, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of time
     * units and returns the new point in time.
     *
     * @param  enum $eTimeUnit The time unit to be used for shifting. Can be `SECOND`, `MINUTE`, `HOUR`, `DAY`, `WEEK`,
     * `MONTH`, or `YEAR`.
     * @param  int $iQuantity The number of units by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedLocal ($eTimeUnit, $iQuantity)
    {
        assert( 'is_enum($eTimeUnit) && is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return self::shiftTimeInTimeZone($this, $eTimeUnit, $iQuantity, date_default_timezone_get());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of seconds
     * and returns the new point in time.
     *
     * @param  int $iQuantity The number of seconds by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedBySecondsLocal ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::SECOND, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of minutes
     * and returns the new point in time.
     *
     * @param  int $iQuantity The number of minutes by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMinutesLocal ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::MINUTE, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of hours and
     * returns the new point in time.
     *
     * @param  int $iQuantity The number of hours by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByHoursLocal ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::HOUR, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of days and
     * returns the new point in time.
     *
     * @param  int $iQuantity The number of days by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByDaysLocal ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::DAY, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of weeks and
     * returns the new point in time.
     *
     * @param  int $iQuantity The number of weeks by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByWeeksLocal ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::WEEK, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of months
     * and returns the new point in time.
     *
     * @param  int $iQuantity The number of months by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMonthsLocal ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::MONTH, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of years and
     * returns the new point in time.
     *
     * @param  int $iQuantity The number of years by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByYearsLocal ($iQuantity)
    {
        assert( 'is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::YEAR, $iQuantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the millisecond component of a point in time and returns the new point in time.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     *
     * @param  int $iNewMillisecond The new number of the millisecond (from 0 to 999).
     *
     * @return CTime The modified point in time.
     */

    public function withMillisecondUtc ($iNewMillisecond)
    {
        assert( '0 <= $iNewMillisecond && $iNewMillisecond <= 999', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMillisecond = $iNewMillisecond;
        return self::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the second component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $iNewSecond The new number of the second (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withSecondUtc ($iNewSecond)
    {
        assert( '0 <= $iNewSecond && $iNewSecond <= 59', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iSecond = $iNewSecond;
        return self::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the minute component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $iNewMinute The new number of the minute (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withMinuteUtc ($iNewMinute)
    {
        assert( '0 <= $iNewMinute && $iNewMinute <= 59', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMinute = $iNewMinute;
        return self::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the hour component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $iNewHour The new number of the hour (from 0 to 23).
     *
     * @return CTime The modified point in time.
     */

    public function withHourUtc ($iNewHour)
    {
        assert( '0 <= $iNewHour && $iNewHour <= 23', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iHour = $iNewHour;
        return self::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the day component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $iNewDay The new number of the day (from 1 to 31).
     *
     * @return CTime The modified point in time.
     */

    public function withDayUtc ($iNewDay)
    {
        assert( '1 <= $iNewDay && $iNewDay <= 31', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iDay = $iNewDay;
        return self::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the month component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $iNewMonth The new number of the month (from 1 to 12).
     *
     * @return CTime The modified point in time.
     */

    public function withMonthUtc ($iNewMonth)
    {
        assert( '1 <= $iNewMonth && $iNewMonth <= 12', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMonth = $iNewMonth;
        return self::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the year component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $iNewYear The new number of the year.
     *
     * @return CTime The modified point in time.
     */

    public function withYearUtc ($iNewYear)
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iYear = $iNewYear;
        return self::fromComponentsUtc($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the millisecond component of a point in time and returns the new point in time.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to be modified.
     * @param  int $iNewMillisecond The new number of the millisecond (from 0 to 999).
     *
     * @return CTime The modified point in time.
     */

    public function withMillisecondInTimeZone (CTimeZone $oTimeZone, $iNewMillisecond)
    {
        assert( '0 <= $iNewMillisecond && $iNewMillisecond <= 999', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMillisecond = $iNewMillisecond;
        return self::fromComponentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
            $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the second component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to be modified.
     * @param  int $iNewSecond The new number of the second (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withSecondInTimeZone (CTimeZone $oTimeZone, $iNewSecond)
    {
        assert( '0 <= $iNewSecond && $iNewSecond <= 59', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iSecond = $iNewSecond;
        return self::fromComponentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
            $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the minute component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to be modified.
     * @param  int $iNewMinute The new number of the minute (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withMinuteInTimeZone (CTimeZone $oTimeZone, $iNewMinute)
    {
        assert( '0 <= $iNewMinute && $iNewMinute <= 59', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMinute = $iNewMinute;
        return self::fromComponentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
            $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the hour component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to be modified.
     * @param  int $iNewHour The new number of the hour (from 0 to 23).
     *
     * @return CTime The modified point in time.
     */

    public function withHourInTimeZone (CTimeZone $oTimeZone, $iNewHour)
    {
        assert( '0 <= $iNewHour && $iNewHour <= 23', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iHour = $iNewHour;
        return self::fromComponentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
            $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the day component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to be modified.
     * @param  int $iNewDay The new number of the day (from 1 to 31).
     *
     * @return CTime The modified point in time.
     */

    public function withDayInTimeZone (CTimeZone $oTimeZone, $iNewDay)
    {
        assert( '1 <= $iNewDay && $iNewDay <= 31', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iDay = $iNewDay;
        return self::fromComponentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
            $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the month component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to be modified.
     * @param  int $iNewMonth The new number of the month (from 1 to 12).
     *
     * @return CTime The modified point in time.
     */

    public function withMonthInTimeZone (CTimeZone $oTimeZone, $iNewMonth)
    {
        assert( '1 <= $iNewMonth && $iNewMonth <= 12', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMonth = $iNewMonth;
        return self::fromComponentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
            $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the year component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $oTimeZone The time zone in which the component is to be modified.
     * @param  int $iNewYear The new number of the year.
     *
     * @return CTime The modified point in time.
     */

    public function withYearInTimeZone (CTimeZone $oTimeZone, $iNewYear)
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iYear = $iNewYear;
        return self::fromComponentsInTimeZone($oTimeZone, $iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
            $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the millisecond component of a point in time and returns the new point in time.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     *
     * @param  int $iNewMillisecond The new number of the millisecond (from 0 to 999).
     *
     * @return CTime The modified point in time.
     */

    public function withMillisecondLocal ($iNewMillisecond)
    {
        assert( '0 <= $iNewMillisecond && $iNewMillisecond <= 999', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMillisecond = $iNewMillisecond;
        return self::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the second component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $iNewSecond The new number of the second (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withSecondLocal ($iNewSecond)
    {
        assert( '0 <= $iNewSecond && $iNewSecond <= 59', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iSecond = $iNewSecond;
        return self::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the minute component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $iNewMinute The new number of the minute (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withMinuteLocal ($iNewMinute)
    {
        assert( '0 <= $iNewMinute && $iNewMinute <= 59', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMinute = $iNewMinute;
        return self::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the hour component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $iNewHour The new number of the hour (from 0 to 23).
     *
     * @return CTime The modified point in time.
     */

    public function withHourLocal ($iNewHour)
    {
        assert( '0 <= $iNewHour && $iNewHour <= 23', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iHour = $iNewHour;
        return self::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the day component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $iNewDay The new number of the day (from 1 to 31).
     *
     * @return CTime The modified point in time.
     */

    public function withDayLocal ($iNewDay)
    {
        assert( '1 <= $iNewDay && $iNewDay <= 31', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iDay = $iNewDay;
        return self::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the month component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $iNewMonth The new number of the month (from 1 to 12).
     *
     * @return CTime The modified point in time.
     */

    public function withMonthLocal ($iNewMonth)
    {
        assert( '1 <= $iNewMonth && $iNewMonth <= 12', vs(isset($this), get_defined_vars()) );

        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iMonth = $iNewMonth;
        return self::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the year component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $iNewYear The new number of the year.
     *
     * @return CTime The modified point in time.
     */

    public function withYearLocal ($iNewYear)
    {
        $iYear;
        $iMonth;
        $iDay;
        $iHour;
        $iMinute;
        $iSecond;
        $iMillisecond;
        $this->componentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
        $iYear = $iNewYear;
        return self::fromComponentsLocal($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function componentsInTimeZoneToTime ($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond,
        $iMillisecond, $xTimeZone)
    {
        assert( 'self::areComponentsValid($iYear, $iMonth, $iDay, $iHour, $iMinute, $iSecond, $iMillisecond)',
            vs(isset($this), get_defined_vars()) );
        assert( '1000 <= $iYear && $iYear <= 9999', vs(isset($this), get_defined_vars()) );

        $sYear = CString::fromInt($iYear);
        $sMonth = CString::fromInt($iMonth);
        $sDay = CString::fromInt($iDay);
        $sHour = CString::padStart(CString::fromInt($iHour), "0", 2);
        $sMinute = CString::padStart(CString::fromInt($iMinute), "0", 2);
        $sSecond = CString::padStart(CString::fromInt($iSecond), "0", 2);
        $oDt = DateTime::createFromFormat("Y,m,d,H,i,s", "$sYear,$sMonth,$sDay,$sHour,$sMinute,$sSecond",
            ( is_cstring($xTimeZone) ) ? new DateTimeZone($xTimeZone) : $xTimeZone->DTimeZone());
        if ( !is_object($oDt) )
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }

        $iUTime = $oDt->getTimestamp();
        $iMTime;
        if ( !($iUTime < 0 && $iMillisecond != 0) )
        {
            $iMTime = $iMillisecond;
        }
        else
        {
            $iUTime++;
            $iMTime = $iMillisecond - 1000;
        }
        return new self($iUTime, $iMTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function timeToStringInTimeZone (CTime $oTime, $xPattern, $xTimeZone)
    {
        $oDt = new DateTime();
        $oDt->setTimestamp($oTime->UTime());
        $oDt->setTimezone(( is_cstring($xTimeZone) ) ? new DateTimeZone($xTimeZone) : $xTimeZone->DTimeZone());
        $sPattern = ( is_enum($xPattern) ) ? self::patternEnumToString($xPattern) : $xPattern;
        $sTime = $oDt->format($sPattern);
        if ( is_cstring($sTime) )
        {
            return $sTime;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function timeToComponentsInTimeZone (CTime $oTime, $xTimeZone, &$riYear, &$riMonth, &$riDay,
        &$riHour, &$riMinute, &$riSecond, &$riMillisecond, &$reDayOfWeek)
    {
        $fFTime = $oTime->FTime();
        $iUTime = $oTime->UTime();
        $iMTime = $oTime->MTime();
        $bNegMsCase = false;

        if ( $fFTime < 0.0 && $iMTime != 0 )
        {
            $bNegMsCase = true;
            $iUTime--;
        }

        $sTime = self::timeToStringInTimeZone(new self($iUTime), "Y,m,d,H,i,s,w", $xTimeZone);
        $aComponents = CString::split($sTime, ",");
        $riYear =      CString::toInt($aComponents[0]);
        $riMonth =     CString::toInt($aComponents[1]);
        $riDay =       CString::toInt($aComponents[2]);
        $riHour =      CString::toInt($aComponents[3]);
        $riMinute =    CString::toInt($aComponents[4]);
        $riSecond =    CString::toInt($aComponents[5]);
        $reDayOfWeek = CString::toInt($aComponents[6]);

        if ( !$bNegMsCase )
        {
            $riMillisecond = $iMTime;
        }
        else
        {
            $riMillisecond = $iMTime + 1000;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function shiftTimeInTimeZone (CTime $oTime, $eTimeUnit, $iQuantity, $xTimeZone)
    {
        $sUnits;
        switch ( $eTimeUnit )
        {
        case self::SECOND:
            $sUnits = "seconds";
            break;
        case self::MINUTE:
            $sUnits = "minutes";
            break;
        case self::HOUR:
            $sUnits = "hours";
            break;
        case self::DAY:
            $sUnits = "days";
            break;
        case self::WEEK:
            $sUnits = "weeks";
            break;
        case self::MONTH:
            $sUnits = "months";
            break;
        case self::YEAR:
            $sUnits = "years";
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        $oDt = new DateTime();
        $oDt->setTimestamp($oTime->UTime());
        $oDt->setTimezone(( is_cstring($xTimeZone) ) ? new DateTimeZone($xTimeZone) : $xTimeZone->DTimeZone());
        $sSign = ( $iQuantity < 0 ) ? "-" : "+";
        $sAbsQty = CString::fromInt(CMathi::abs($iQuantity));
        $oDt->modify("$sSign$sAbsQty $sUnits");

        $iUTime = $oDt->getTimestamp();
        $iMTime = $oTime->MTime();
        if ( $iUTime != 0 && $iMTime != 0 && CMathi::sign($iUTime) != CMathi::sign($iMTime) )
        {
            if ( $iUTime < 0 )
            {
                // $iMTime > 0
                $iUTime++;
                $iMTime -= 1000;
            }
            else
            {
                // $iMTime < 0
                $iUTime--;
                $iMTime += 1000;
            }
        }
        return new self($iUTime, $iMTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function patternEnumToString ($ePattern)
    {
        switch ( $ePattern )
        {
        case self::PATTERN_DEFAULT:
            return "Y-m-d H:i:s e";
        case self::PATTERN_DEFAULT_DATE:
            return "Y-m-d";
        case self::PATTERN_DEFAULT_TIME:
            return "H:i:s";
        case self::PATTERN_ATOM:
            return "Y-m-d\TH:i:sP";
        case self::PATTERN_COOKIE:
            return "l, d-M-y H:i:s T";
        case self::PATTERN_HTTP_HEADER_GMT:
            return "D, d M Y H:i:s T";
        case self::PATTERN_ISO8601:
            return "Y-m-d\TH:i:sO";
        case self::PATTERN_MYSQL:
            return "Y-m-d H:i:s";
        case self::PATTERN_RFC822:
            return "D, d M y H:i:s O";
        case self::PATTERN_RFC850:
            return "l, d-M-y H:i:s T";
        case self::PATTERN_RFC1036:
            return "D, d M y H:i:s O";
        case self::PATTERN_RFC2822:
            return "D, d M Y H:i:s O";
        case self::PATTERN_RFC3339:
            return "Y-m-d\TH:i:sP";
        case self::PATTERN_RSS:
            return "D, d M Y H:i:s O";
        case self::PATTERN_W3C:
            return "Y-m-d\TH:i:sP";
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_fFTime;
}
