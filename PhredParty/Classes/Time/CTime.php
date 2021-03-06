<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   __construct ($UTime, $MTime = 0)
//   static CTime now ()
//   static CTime fromFTime ($FTime)
//   static CTime fromString ($string, $pattern = null)
//   static bool areComponentsValid ($year, $month, $day, $hour = 0, $minute = 0, $second = 0, $millisecond = 0)
//   static CTime fromComponentsUtc ($year, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0,
//     $millisecond = 0)
//   static CTime fromComponentsInTimeZone (CTimeZone $timeZone, $year, $month = 1, $day = 1, $hour = 0,
//     $minute = 0, $second = 0, $millisecond = 0)
//   static CTime fromComponentsLocal ($year, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0,
//     $millisecond = 0)
//   CUStringObject toStringUtc ($pattern = self::PATTERN_DEFAULT)
//   CUStringObject toStringInTimeZone (CTimeZone $timeZone, $pattern = self::PATTERN_DEFAULT)
//   CUStringObject toStringLocal ($pattern = self::PATTERN_DEFAULT)
//   int UTime ()
//   int MTime ()
//   float FTime ()
//   bool equals ($toTime)
//   bool isBefore (CTime $time)
//   bool isAfter (CTime $time)
//   int compare ($toTime)
//   static int currentUTime ()
//   static int currentDayOfYearUtc ()
//   static int currentDayOfYearInTimeZone (CTimeZone $timeZone)
//   static int currentDayOfYearLocal ()
//   void componentsUtc (&$year, &$month = null, &$day = null, &$hour = null, &$minute = null,
//     &$second = null, &$millisecond = null, &$dayOfWeek = null)
//   int yearUtc ()
//   int monthUtc ()
//   int dayUtc ()
//   int hourUtc ()
//   int minuteUtc ()
//   int secondUtc ()
//   int millisecondUtc ()
//   enum dayOfWeekUtc ()
//   void componentsInTimeZone (CTimeZone $timeZone, &$year, &$month = null, &$day = null, &$hour = null,
//     &$minute = null, &$second = null, &$millisecond = null, &$dayOfWeek = null)
//   int yearInTimeZone (CTimeZone $timeZone)
//   int monthInTimeZone (CTimeZone $timeZone)
//   int dayInTimeZone (CTimeZone $timeZone)
//   int hourInTimeZone (CTimeZone $timeZone)
//   int minuteInTimeZone (CTimeZone $timeZone)
//   int secondInTimeZone (CTimeZone $timeZone)
//   int millisecondInTimeZone (CTimeZone $timeZone)
//   enum dayOfWeekInTimeZone (CTimeZone $timeZone)
//   void componentsLocal (&$year, &$month = null, &$day = null, &$hour = null, &$minute = null,
//     &$second = null, &$millisecond = null, &$dayOfWeek = null)
//   int yearLocal ()
//   int monthLocal ()
//   int dayLocal ()
//   int hourLocal ()
//   int minuteLocal ()
//   int secondLocal ()
//   int millisecondLocal ()
//   enum dayOfWeekLocal ()
//   int diff (CTime $fromTime, $timeUnit)
//   int diffInSeconds (CTime $fromTime)
//   int diffInMinutes (CTime $fromTime)
//   int diffInHours (CTime $fromTime)
//   int diffInDays (CTime $fromTime)
//   int diffInWeeks (CTime $fromTime)
//   int diffInMonths (CTime $fromTime)
//   int diffInYears (CTime $fromTime)
//   void diffUnits (CTime $fromTime, &$numSeconds, &$numMinutes = null, &$numHours = null, &$numDays = null,
//     &$numWeeks = null, &$numMonths = null, &$numYears = null)
//   int signedDiff (CTime $fromTime, $timeUnit)
//   int signedDiffInSeconds (CTime $fromTime)
//   int signedDiffInMinutes (CTime $fromTime)
//   int signedDiffInHours (CTime $fromTime)
//   int signedDiffInDays (CTime $fromTime)
//   int signedDiffInWeeks (CTime $fromTime)
//   int signedDiffInMonths (CTime $fromTime)
//   int signedDiffInYears (CTime $fromTime)
//   CTime shiftedUtc ($timeUnit, $quantity)
//   CTime shiftedBySecondsUtc ($quantity)
//   CTime shiftedByMinutesUtc ($quantity)
//   CTime shiftedByHoursUtc ($quantity)
//   CTime shiftedByDaysUtc ($quantity)
//   CTime shiftedByWeeksUtc ($quantity)
//   CTime shiftedByMonthsUtc ($quantity)
//   CTime shiftedByYearsUtc ($quantity)
//   CTime shiftedInTimeZone (CTimeZone $timeZone, $timeUnit, $quantity)
//   CTime shiftedBySecondsInTimeZone (CTimeZone $timeZone, $quantity)
//   CTime shiftedByMinutesInTimeZone (CTimeZone $timeZone, $quantity)
//   CTime shiftedByHoursInTimeZone (CTimeZone $timeZone, $quantity)
//   CTime shiftedByDaysInTimeZone (CTimeZone $timeZone, $quantity)
//   CTime shiftedByWeeksInTimeZone (CTimeZone $timeZone, $quantity)
//   CTime shiftedByMonthsInTimeZone (CTimeZone $timeZone, $quantity)
//   CTime shiftedByYearsInTimeZone (CTimeZone $timeZone, $quantity)
//   CTime shiftedLocal ($timeUnit, $quantity)
//   CTime shiftedBySecondsLocal ($quantity)
//   CTime shiftedByMinutesLocal ($quantity)
//   CTime shiftedByHoursLocal ($quantity)
//   CTime shiftedByDaysLocal ($quantity)
//   CTime shiftedByWeeksLocal ($quantity)
//   CTime shiftedByMonthsLocal ($quantity)
//   CTime shiftedByYearsLocal ($quantity)
//   CTime withMillisecondUtc ($newMillisecond)
//   CTime withSecondUtc ($newSecond)
//   CTime withMinuteUtc ($newMinute)
//   CTime withHourUtc ($newHour)
//   CTime withDayUtc ($newDay)
//   CTime withMonthUtc ($newMonth)
//   CTime withYearUtc ($newYear)
//   CTime withMillisecondInTimeZone (CTimeZone $timeZone, $newMillisecond)
//   CTime withSecondInTimeZone (CTimeZone $timeZone, $newSecond)
//   CTime withMinuteInTimeZone (CTimeZone $timeZone, $newMinute)
//   CTime withHourInTimeZone (CTimeZone $timeZone, $newHour)
//   CTime withDayInTimeZone (CTimeZone $timeZone, $newDay)
//   CTime withMonthInTimeZone (CTimeZone $timeZone, $newMonth)
//   CTime withYearInTimeZone (CTimeZone $timeZone, $newYear)
//   CTime withMillisecondLocal ($newMillisecond)
//   CTime withSecondLocal ($newSecond)
//   CTime withMinuteLocal ($newMinute)
//   CTime withHourLocal ($newHour)
//   CTime withDayLocal ($newDay)
//   CTime withMonthLocal ($newMonth)
//   CTime withYearLocal ($newYear)

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
     * @param  int $UTime The "Unix time" (the number of seconds relative to the midnight of January 1, 1970 UTC).
     * @param  int $MTime **OPTIONAL. Default is** `0`. The number of milliseconds in either positive or negative
     * direction starting from the moment set by the "Unix time" seconds. Can be a value from 0 to 999 or from 0 to
     * -999.
     */

    public function __construct ($UTime, $MTime = 0, $_noinit = false)
    {
        if ( $_noinit )
        {
            return;
        }

        // If neither is zero, `UTime` and `MTime` must be of the same sign.

        assert( 'is_int($UTime) && is_int($MTime)', vs(isset($this), get_defined_vars()) );
        assert( '$UTime == 0 || $MTime == 0 || CMathi::sign($UTime) == CMathi::sign($MTime)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= CMathi::abs($MTime) && CMathi::abs($MTime) <= 999', vs(isset($this), get_defined_vars()) );

        $this->m_FTime = (float)$UTime + $MTime/1000;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the current time.
     *
     * @return CTime The current time.
     */

    public static function now ()
    {
        $time = new self(null, null, true);
        $time->m_FTime = microtime(true);
        return $time;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from the "Unix time" specified as a floating-point number, which possibly indicates the
     * milliseconds in its fractional part.
     *
     * The "Unix time" is positive for any point in time after the midnight of January 1, 1970 UTC and is negative for
     * any point in time before it.
     *
     * @param  float $FTime The "Unix time" (the number of seconds relative to the midnight of January 1, 1970 UTC),
     * as a floating-point number capable of milliseconds.
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromFTime ($FTime)
    {
        assert( 'is_float($FTime)', vs(isset($this), get_defined_vars()) );

        $time = new self(null, null, true);
        $time->m_FTime = $FTime;
        return $time;
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
     * @param  string $string The string to be parsed.
     * @param  enum|string $pattern **OPTIONAL. Default is** *take a guess*. The pattern according to which the string
     * is formatted. See [Summary](#summary) for the enumerands of the most widespread patterns or
     * [date](http://www.php.net/manual/en/function.date.php) in the PHP Manual for the format characters that you can
     * use to compose a custom pattern.
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromString ($string, $pattern = null)
    {
        assert( 'is_cstring($string) && (!isset($pattern) || is_enum($pattern) || is_cstring($pattern))',
            vs(isset($this), get_defined_vars()) );

        $UTime;
        if ( isset($pattern) )
        {
            // Parse the string using the pattern provided.
            $strPattern = ( is_enum($pattern) ) ? self::patternEnumToString($pattern) : $pattern;
            $dt = DateTime::createFromFormat($strPattern, $string, new DateTimeZone(CTimeZone::UTC));
            if ( !is_object($dt) )
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
            }
            $UTime = $dt->getTimestamp();
        }
        else
        {
            // Guess.
            $sysDefTz = date_default_timezone_get();
            date_default_timezone_set(CTimeZone::UTC);
            $UTime = strtotime($string);
            date_default_timezone_set($sysDefTz);
            if ( !is_int($UTime) )
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
            }
        }
        return new self($UTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if specified date/time components are valid in separate and as a combination.
     *
     * For example, the combination of February 29 with the year of 2000 is valid but, if combined with the year of
     * 2005, is not.
     *
     * @param  int $year The year.
     * @param  int $month The number of the month (the valid range is from 1 to 12).
     * @param  int $day The number of the day (the valid range is from 1 to 31).
     * @param  int $hour **OPTIONAL. Default is** `0`. The number of the hour (the valid range is from 0 to 23).
     * @param  int $minute **OPTIONAL. Default is** `0`. The number of the minute (the valid range is from 0 to 59).
     * @param  int $second **OPTIONAL. Default is** `0`. The number of the second (the valid range is from 0 to 59).
     * @param  int $millisecond **OPTIONAL. Default is** `0`. The number of the millisecond (the valid range is from 0
     * to 999).
     *
     * @return bool `true` if the components are valid in separate and as a combination, `false` otherwise.
     */

    public static function areComponentsValid ($year, $month, $day, $hour = 0, $minute = 0, $second = 0,
        $millisecond = 0)
    {
        assert( 'is_int($year) && is_int($month) && is_int($day) && is_int($hour) && is_int($minute) && ' .
                'is_int($second) && is_int($millisecond)', vs(isset($this), get_defined_vars()) );

        if ( !(1 <= $month && $month <= 12) )
        {
            return false;
        }
        if ( !(1 <= $day && $day <= 31) )
        {
            return false;
        }
        if ( !(0 <= $hour && $hour <= 23) )
        {
            return false;
        }
        if ( !(0 <= $minute && $minute <= 59) )
        {
            return false;
        }
        if ( !(0 <= $second && $second <= 59) )
        {
            return false;
        }
        if ( !(0 <= $millisecond && $millisecond <= 999) )
        {
            return false;
        }
        return checkdate($month, $day, $year);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from date/time components as they would appear in the UTC time zone.
     *
     * @param  int $year The year.
     * @param  int $month **OPTIONAL. Default is** `1`. The number of the month (from 1 to 12).
     * @param  int $day **OPTIONAL. Default is** `1`. The number of the day (from 1 to 31).
     * @param  int $hour **OPTIONAL. Default is** `0`. The number of the hour (from 0 to 23).
     * @param  int $minute **OPTIONAL. Default is** `0`. The number of the minute (from 0 to 59).
     * @param  int $second **OPTIONAL. Default is** `0`. The number of the second (from 0 to 59).
     * @param  int $millisecond **OPTIONAL. Default is** `0`. The number of the millisecond (from 0 to 999).
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromComponentsUtc ($year, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0,
        $millisecond = 0)
    {
        assert( 'is_int($year) && is_int($month) && is_int($day) && is_int($hour) && is_int($minute) && ' .
                'is_int($second) && is_int($millisecond)', vs(isset($this), get_defined_vars()) );

        return self::componentsInTimeZoneToTime($year, $month, $day, $hour, $minute, $second, $millisecond,
            CTimeZone::UTC);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from date/time components as they would appear in a specified time zone.
     *
     * @param  CTimeZone $timeZone The time zone in which the components got their values.
     * @param  int $year The year.
     * @param  int $month **OPTIONAL. Default is** `1`. The number of the month (from 1 to 12).
     * @param  int $day **OPTIONAL. Default is** `1`. The number of the day (from 1 to 31).
     * @param  int $hour **OPTIONAL. Default is** `0`. The number of the hour (from 0 to 23).
     * @param  int $minute **OPTIONAL. Default is** `0`. The number of the minute (from 0 to 59).
     * @param  int $second **OPTIONAL. Default is** `0`. The number of the second (from 0 to 59).
     * @param  int $millisecond **OPTIONAL. Default is** `0`. The number of the millisecond (from 0 to 999).
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromComponentsInTimeZone (CTimeZone $timeZone, $year, $month = 1, $day = 1, $hour = 0,
        $minute = 0, $second = 0, $millisecond = 0)
    {
        assert( 'is_int($year) && is_int($month) && is_int($day) && is_int($hour) && is_int($minute) && ' .
                'is_int($second) && is_int($millisecond)', vs(isset($this), get_defined_vars()) );

        return self::componentsInTimeZoneToTime($year, $month, $day, $hour, $minute, $second, $millisecond,
            $timeZone);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a point in time from date/time components as they would appear in the PHP's default time zone.
     *
     * @param  int $year The year.
     * @param  int $month **OPTIONAL. Default is** `1`. The number of the month (from 1 to 12).
     * @param  int $day **OPTIONAL. Default is** `1`. The number of the day (from 1 to 31).
     * @param  int $hour **OPTIONAL. Default is** `0`. The number of the hour (from 0 to 23).
     * @param  int $minute **OPTIONAL. Default is** `0`. The number of the minute (from 0 to 59).
     * @param  int $second **OPTIONAL. Default is** `0`. The number of the second (from 0 to 59).
     * @param  int $millisecond **OPTIONAL. Default is** `0`. The number of the millisecond (from 0 to 999).
     *
     * @return CTime The corresponding point in time.
     */

    public static function fromComponentsLocal ($year, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0,
        $millisecond = 0)
    {
        assert( 'is_int($year) && is_int($month) && is_int($day) && is_int($hour) && is_int($minute) && ' .
                'is_int($second) && is_int($millisecond)', vs(isset($this), get_defined_vars()) );

        return self::componentsInTimeZoneToTime($year, $month, $day, $hour, $minute, $second, $millisecond,
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
     * @param  enum|string $pattern **OPTIONAL. Default is** `PATTERN_DEFAULT`. The pattern to be used for formatting.
     * See [Summary](#summary) for the enumerands of the most widespread patterns or
     * [date](http://www.php.net/manual/en/function.date.php) in the PHP Manual for the format characters that you can
     * use to compose a custom pattern.
     *
     * @return CUStringObject The resulting string.
     */

    public function toStringUtc ($pattern = self::PATTERN_DEFAULT)
    {
        assert( 'is_enum($pattern) || is_cstring($pattern)', vs(isset($this), get_defined_vars()) );

        if ( is_enum($pattern) && $pattern == self::PATTERN_HTTP_HEADER_GMT )
        {
            // A special case.
            $time = gmdate(self::patternEnumToString(self::PATTERN_HTTP_HEADER_GMT), $this->UTime());
            assert( 'is_cstring($time)', vs(isset($this), get_defined_vars()) );
            return $time;
        }
        return self::timeToStringInTimeZone($this, $pattern, CTimeZone::UTC);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a point in time into a string according to a formatting pattern, so that the values of the date/time
     * components in the resulting string appear in a specified time zone.
     *
     * For locale-aware formatting of points in time as strings, which lets users from almost any country read time in
     * the way that they are accustomed to, you may want to try the [CUFormat](CUFormat.html) class.
     *
     * @param  CTimeZone $timeZone The time zone in which the components in the resulting string are to appear.
     * @param  enum|string $pattern **OPTIONAL. Default is** `PATTERN_DEFAULT`. The pattern to be used for formatting.
     * See [Summary](#summary) for the enumerands of the most widespread patterns or
     * [date](http://www.php.net/manual/en/function.date.php) in the PHP Manual for the format characters that you can
     * use to compose a custom pattern.
     *
     * @return CUStringObject The resulting string.
     */

    public function toStringInTimeZone (CTimeZone $timeZone, $pattern = self::PATTERN_DEFAULT)
    {
        assert( 'is_enum($pattern) || is_cstring($pattern)', vs(isset($this), get_defined_vars()) );
        return self::timeToStringInTimeZone($this, $pattern, $timeZone);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a point in time into a string according to a formatting pattern, so that the values of the date/time
     * components in the resulting string appear in the PHP's default time zone.
     *
     * For locale-aware formatting of points in time as strings, which lets users from almost any country read time in
     * the way that they are accustomed to, you may want to try the [CUFormat](CUFormat.html) class.
     *
     * @param  enum|string $pattern **OPTIONAL. Default is** `PATTERN_DEFAULT`. The pattern to be used for formatting.
     * See [Summary](#summary) for the enumerands of the most widespread patterns or
     * [date](http://www.php.net/manual/en/function.date.php) in the PHP Manual for the format characters that you can
     * use to compose a custom pattern.
     *
     * @return CUStringObject The resulting string.
     */

    public function toStringLocal ($pattern = self::PATTERN_DEFAULT)
    {
        assert( 'is_enum($pattern) || is_cstring($pattern)', vs(isset($this), get_defined_vars()) );
        return self::timeToStringInTimeZone($this, $pattern, date_default_timezone_get());
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
        return (int)$this->m_FTime;
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
        return CMathi::round(($this->m_FTime - (int)$this->m_FTime)*1000);
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
        return $this->m_FTime;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a point in time is equal to another such point.
     *
     * If any of the two points in time goes as far as milliseconds in its time resolution, the method also compares
     * the milliseconds of the points.
     *
     * @param  CTime $toTime The second point in time for comparison.
     *
     * @return bool `true` if the two points in time are equal, `false` otherwise.
     */

    public function equals ($toTime)
    {
        // Parameter type hinting is not used for the purpose of interface compatibility.
        assert( 'is_ctime($toTime)', vs(isset($this), get_defined_vars()) );
        return ( $this->UTime() == $toTime->UTime() && $this->MTime() == $toTime->MTime() );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a point in time happened or will happen before another such point.
     *
     * If any of the two points in time goes as far as milliseconds in its time resolution, the method also compares
     * the milliseconds of the points.
     *
     * @param  CTime $time The second point in time for comparison.
     *
     * @return bool `true` if *this* point in time precedes the second point in time, `false` otherwise.
     */

    public function isBefore (CTime $time)
    {
        return ( $this->m_FTime < $time->m_FTime );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a point in time happened or will happen after another such point.
     *
     * If any of the two points in time goes as far as milliseconds in its time resolution, the method also compares
     * the milliseconds of the points.
     *
     * @param  CTime $time The second point in time for comparison.
     *
     * @return bool `true` if *this* point in time succeeds the second point in time, `false` otherwise.
     */

    public function isAfter (CTime $time)
    {
        return ( $this->m_FTime > $time->m_FTime );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two points in time should appear in a place where it matters.
     *
     * If any of the two points in time goes as far as milliseconds in its time resolution, the method also compares
     * the milliseconds of the points.
     *
     * @param  CTime $toTime The second point in time for comparison.
     *
     * @return int A negative value (typically `-1`) if *this* point in time should go before the second point in time,
     * a positive value (typically `1`) if the other way around, and `0` if the two points in time are equal.
     */

    public function compare ($toTime)
    {
        // Parameter type hinting is not used for the purpose of interface compatibility.
        assert( 'is_ctime($toTime)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign($this->m_FTime - $toTime->m_FTime);
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
     * @param  CTimeZone $timeZone The time zone in which the current day is to get its number.
     *
     * @return int The number of the current day of the year.
     */

    public static function currentDayOfYearInTimeZone (CTimeZone $timeZone)
    {
        return CString::toInt(self::timeToStringInTimeZone(self::now(), "z", $timeZone));
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
     * @param  reference $year **OUTPUT.** The year, of type `int`.
     * @param  reference $month **OPTIONAL. OUTPUT.** The number of the month (from 1 to 12), of type `int`.
     * @param  reference $day **OPTIONAL. OUTPUT.** The number of the day (from 1 to 31), of type `int`.
     * @param  reference $hour **OPTIONAL. OUTPUT.** The number of the hour (from 0 to 23), of type `int`.
     * @param  reference $minute **OPTIONAL. OUTPUT.** The number of the minute (from 0 to 59), of type `int`.
     * @param  reference $second **OPTIONAL. OUTPUT.** The number of the second (from 0 to 59), of type `int`.
     * @param  reference $millisecond **OPTIONAL. OUTPUT.** The number of the millisecond (from 0 to 999), of type
     * `int`.
     * @param  reference $dayOfWeek **OPTIONAL. OUTPUT.** The day of the week, of type `enum`. Can be `SUNDAY`,
     * `MONDAY`, `TUESDAY`, `WEDNESDAY`, `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @return void
     */

    public function componentsUtc (&$year, &$month = null, &$day = null, &$hour = null, &$minute = null,
        &$second = null, &$millisecond = null, &$dayOfWeek = null)
    {
        self::timeToComponentsInTimeZone($this, CTimeZone::UTC, $year, $month, $day, $hour, $minute,
            $second, $millisecond, $dayOfWeek);
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
        $year;
        $this->componentsUtc($year);
        return $year;
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
        $year;
        $month;
        $this->componentsUtc($year, $month);
        return $month;
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
        $year;
        $month;
        $day;
        $this->componentsUtc($year, $month, $day);
        return $day;
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
        $year;
        $month;
        $day;
        $hour;
        $this->componentsUtc($year, $month, $day, $hour);
        return $hour;
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
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $this->componentsUtc($year, $month, $day, $hour, $minute);
        return $minute;
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
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second);
        return $second;
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
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        return $millisecond;
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
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond, $dayOfWeek);
        return $dayOfWeek;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Outputs the date/time components that define a point in time in a specified time zone.
     *
     * You can use one of the dedicated methods when you only want to know the value of a single component, e.g.
     * `monthInTimeZone`.
     *
     * @param  CTimeZone $timeZone The time zone in which the components are to get their values.
     * @param  reference $year **OUTPUT.** The year, of type `int`.
     * @param  reference $month **OPTIONAL. OUTPUT.** The number of the month (from 1 to 12), of type `int`.
     * @param  reference $day **OPTIONAL. OUTPUT.** The number of the day (from 1 to 31), of type `int`.
     * @param  reference $hour **OPTIONAL. OUTPUT.** The number of the hour (from 0 to 23), of type `int`.
     * @param  reference $minute **OPTIONAL. OUTPUT.** The number of the minute (from 0 to 59), of type `int`.
     * @param  reference $second **OPTIONAL. OUTPUT.** The number of the second (from 0 to 59), of type `int`.
     * @param  reference $millisecond **OPTIONAL. OUTPUT.** The number of the millisecond (from 0 to 999), of type
     * `int`.
     * @param  reference $dayOfWeek **OPTIONAL. OUTPUT.** The day of the week, of type `enum`. Can be `SUNDAY`,
     * `MONDAY`, `TUESDAY`, `WEDNESDAY`, `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @return void
     */

    public function componentsInTimeZone (CTimeZone $timeZone, &$year, &$month = null, &$day = null,
        &$hour = null, &$minute = null, &$second = null, &$millisecond = null, &$dayOfWeek = null)
    {
        self::timeToComponentsInTimeZone($this, $timeZone, $year, $month, $day, $hour, $minute, $second,
            $millisecond, $dayOfWeek);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the year to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to get its value.
     *
     * @return int The year of the point in time.
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function yearInTimeZone (CTimeZone $timeZone)
    {
        $year;
        $this->componentsInTimeZone($timeZone, $year);
        return $year;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the month to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the month of the point in time (from 1 to 12).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function monthInTimeZone (CTimeZone $timeZone)
    {
        $year;
        $month;
        $this->componentsInTimeZone($timeZone, $year, $month);
        return $month;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the day to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the day of the point in time (from 1 to 31).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function dayInTimeZone (CTimeZone $timeZone)
    {
        $year;
        $month;
        $day;
        $this->componentsInTimeZone($timeZone, $year, $month, $day);
        return $day;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the hour to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the hour of the point in time (from 0 to 23).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function hourInTimeZone (CTimeZone $timeZone)
    {
        $year;
        $month;
        $day;
        $hour;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour);
        return $hour;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the minute to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the minute of the point in time (from 0 to 59).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function minuteInTimeZone (CTimeZone $timeZone)
    {
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute);
        return $minute;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the second to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the second of the point in time (from 0 to 59).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function secondInTimeZone (CTimeZone $timeZone)
    {
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second);
        return $second;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the millisecond to which a point in time belongs.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to get its value.
     *
     * @return int The number of the millisecond of the point in time (from 0 to 999).
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function millisecondInTimeZone (CTimeZone $timeZone)
    {
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond);
        return $millisecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the day-of-week to which a point in time belongs in a specified time zone.
     *
     * You can use `componentsInTimeZone` method when you want to know the values of multiple date/time components.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to get its value.
     *
     * @return enum The day-of-week of the point in time. Can be `SUNDAY`, `MONDAY`, `TUESDAY`, `WEDNESDAY`,
     * `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @link   #method_componentsInTimeZone componentsInTimeZone
     */

    public function dayOfWeekInTimeZone (CTimeZone $timeZone)
    {
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond,
            $dayOfWeek);
        return $dayOfWeek;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Outputs the date/time components that define a point in time in the PHP's default time zone.
     *
     * You can use one of the dedicated methods when you only want to know the value of a single component, e.g.
     * `monthLocal`.
     *
     * @param  reference $year **OUTPUT.** The year, of type `int`.
     * @param  reference $month **OPTIONAL. OUTPUT.** The number of the month (from 1 to 12), of type `int`.
     * @param  reference $day **OPTIONAL. OUTPUT.** The number of the day (from 1 to 31), of type `int`.
     * @param  reference $hour **OPTIONAL. OUTPUT.** The number of the hour (from 0 to 23), of type `int`.
     * @param  reference $minute **OPTIONAL. OUTPUT.** The number of the minute (from 0 to 59), of type `int`.
     * @param  reference $second **OPTIONAL. OUTPUT.** The number of the second (from 0 to 59), of type `int`.
     * @param  reference $millisecond **OPTIONAL. OUTPUT.** The number of the millisecond (from 0 to 999), of type
     * `int`.
     * @param  reference $dayOfWeek **OPTIONAL. OUTPUT.** The day of the week, of type `enum`. Can be `SUNDAY`,
     * `MONDAY`, `TUESDAY`, `WEDNESDAY`, `THURSDAY`, `FRIDAY`, or `SATURDAY`.
     *
     * @return void
     */

    public function componentsLocal (&$year, &$month = null, &$day = null, &$hour = null, &$minute = null,
        &$second = null, &$millisecond = null, &$dayOfWeek = null)
    {
        self::timeToComponentsInTimeZone($this, date_default_timezone_get(), $year, $month, $day, $hour,
            $minute, $second, $millisecond, $dayOfWeek);
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
        $year;
        $this->componentsLocal($year);
        return $year;
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
        $year;
        $month;
        $this->componentsLocal($year, $month);
        return $month;
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
        $year;
        $month;
        $day;
        $this->componentsLocal($year, $month, $day);
        return $day;
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
        $year;
        $month;
        $day;
        $hour;
        $this->componentsLocal($year, $month, $day, $hour);
        return $hour;
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
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $this->componentsLocal($year, $month, $day, $hour, $minute);
        return $minute;
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
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second);
        return $second;
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
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        return $millisecond;
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
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $dayOfWeek;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond, $dayOfWeek);
        return $dayOfWeek;
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
     * @param  CTime $fromTime The second point in time for comparison.
     * @param  enum $timeUnit The time unit in which the difference is to be calculated. Can be `SECOND`, `MINUTE`,
     * `HOUR`, `DAY`, `WEEK`, `MONTH`, or `YEAR`.
     *
     * @return int The absolute difference between the two points in time, measured in the time unit specified.
     */

    public function diff (CTime $fromTime, $timeUnit)
    {
        assert( 'is_enum($timeUnit)', vs(isset($this), get_defined_vars()) );

        $dtz = new DateTimeZone(CTimeZone::UTC);
        $thisDt = new DateTime();
        $thisDt->setTimestamp($this->UTime());
        $thisDt->setTimezone($dtz);
        $fromDt = new DateTime();
        $fromDt->setTimestamp($fromTime->UTime());
        $fromDt->setTimezone($dtz);
        $dti = $fromDt->diff($thisDt, true);
        assert( 'is_number($dti->days)', vs(isset($this), get_defined_vars()) );

        $intDiffInSeconds =
            CMathi::abs($dti->days)*self::SECONDS_PER_DAY +
            CMathi::abs($dti->h)*self::SECONDS_PER_HOUR +
            CMathi::abs($dti->i)*self::SECONDS_PER_MINUTE +
            CMathi::abs($dti->s);
        $floatDiffInSeconds = (float)$intDiffInSeconds;
        switch ( $timeUnit )
        {
        case self::SECOND:
            return $intDiffInSeconds;
        case self::MINUTE:
            return CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_MINUTE);
        case self::HOUR:
            return CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_HOUR);
        case self::DAY:
            return CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_DAY);
        case self::WEEK:
            return CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_WEEK);
        case self::MONTH:
            return CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_MONTH);
        case self::YEAR:
            return CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_YEAR);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in seconds.
     */

    public function diffInSeconds (CTime $fromTime)
    {
        return $this->diff($fromTime, self::SECOND);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in minutes.
     */

    public function diffInMinutes (CTime $fromTime)
    {
        return $this->diff($fromTime, self::MINUTE);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in hours.
     */

    public function diffInHours (CTime $fromTime)
    {
        return $this->diff($fromTime, self::HOUR);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in days.
     */

    public function diffInDays (CTime $fromTime)
    {
        return $this->diff($fromTime, self::DAY);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in weeks.
     */

    public function diffInWeeks (CTime $fromTime)
    {
        return $this->diff($fromTime, self::WEEK);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in months.
     */

    public function diffInMonths (CTime $fromTime)
    {
        return $this->diff($fromTime, self::MONTH);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The absolute difference between the two points in time, measured in years.
     */

    public function diffInYears (CTime $fromTime)
    {
        return $this->diff($fromTime, self::YEAR);
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
     * @param  CTime $fromTime The second point in time for comparison.
     * @param  reference $numSeconds **OUTPUT.** The number of seconds (from 0 to 59), of type `int`.
     * @param  reference $numMinutes **OPTIONAL. OUTPUT.** The number of minutes (from 0 to 59), of type `int`.
     * @param  reference $numHours **OPTIONAL. OUTPUT.** The number of hours (from 0 to 23), of type `int`.
     * @param  reference $numDays **OPTIONAL. OUTPUT.** The number of days (from 0 to 30), of type `int`.
     * @param  reference $numWeeks **OPTIONAL. OUTPUT.** The number of weeks, of type `int`.
     * @param  reference $numMonths **OPTIONAL. OUTPUT.** The number of months (from 0 to 11), of type `int`.
     * @param  reference $numYears **OPTIONAL. OUTPUT.** The number of years, of type `int`.
     *
     * @return void
     */

    public function diffUnits (CTime $fromTime, &$numSeconds, &$numMinutes = null, &$numHours = null,
        &$numDays = null, &$numWeeks = null, &$numMonths = null, &$numYears = null)
    {
        $secondQty = $this->diffInSeconds($fromTime);

        if ( $secondQty < self::SECONDS_PER_MINUTE )
        {
            $numSeconds = $secondQty;
            $numMinutes = 0;
            $numHours = 0;
            $numDays = 0;
            $numWeeks = 0;
            $numMonths = 0;
            $numYears = 0;
        }
        else if ( $secondQty < self::SECONDS_PER_HOUR )
        {
            $numSeconds = $secondQty % self::SECONDS_PER_MINUTE;
            $numMinutes = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_MINUTE);
            $numHours = 0;
            $numDays = 0;
            $numWeeks = 0;
            $numMonths = 0;
            $numYears = 0;
        }
        else if ( $secondQty < self::SECONDS_PER_DAY )
        {
            $numSeconds = $secondQty % self::SECONDS_PER_MINUTE;
            $numMinutes = CMathi::floor(((float)($secondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $numHours = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_HOUR);
            $numDays = 0;
            $numWeeks = 0;
            $numMonths = 0;
            $numYears = 0;
        }
        else if ( $secondQty < self::SECONDS_PER_WEEK )
        {
            $numSeconds = $secondQty % self::SECONDS_PER_MINUTE;
            $numMinutes = CMathi::floor(((float)($secondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $numHours = CMathi::floor(((float)($secondQty % self::SECONDS_PER_DAY))/self::SECONDS_PER_HOUR);
            $numDays = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_DAY);
            $numWeeks = 0;
            $numMonths = 0;
            $numYears = 0;
        }
        else if ( $secondQty < self::SECONDS_PER_MONTH )
        {
            $numSeconds = $secondQty % self::SECONDS_PER_MINUTE;
            $numMinutes = CMathi::floor(((float)($secondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $numHours = CMathi::floor(((float)($secondQty % self::SECONDS_PER_DAY))/self::SECONDS_PER_HOUR);
            $numDays = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_DAY);
            $numWeeks = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_WEEK);
            $numMonths = 0;
            $numYears = 0;
        }
        else if ( $secondQty < self::SECONDS_PER_YEAR )
        {
            $numSeconds = $secondQty % self::SECONDS_PER_MINUTE;
            $numMinutes = CMathi::floor(((float)($secondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $numHours = CMathi::floor(((float)($secondQty % self::SECONDS_PER_DAY))/self::SECONDS_PER_HOUR);
            $numDays = CMathi::floor(((float)($secondQty % self::SECONDS_PER_MONTH))/self::SECONDS_PER_DAY);
            $numWeeks = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_WEEK);
            $numMonths = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_MONTH);
            $numYears = 0;
        }
        else  // $secondQty >= self::SECONDS_PER_YEAR
        {
            $numSeconds = $secondQty % self::SECONDS_PER_MINUTE;
            $numMinutes = CMathi::floor(((float)($secondQty % self::SECONDS_PER_HOUR))/self::SECONDS_PER_MINUTE);
            $numHours = CMathi::floor(((float)($secondQty % self::SECONDS_PER_DAY))/self::SECONDS_PER_HOUR);
            $numDays = CMathi::floor(((float)($secondQty % self::SECONDS_PER_MONTH))/self::SECONDS_PER_DAY);
            $numWeeks = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_WEEK);
            $numMonths = CMathi::floor(((float)($secondQty % self::SECONDS_PER_YEAR))/self::SECONDS_PER_MONTH);
            $numYears = CMathi::floor(((float)$secondQty)/self::SECONDS_PER_YEAR);
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
     * @param  CTime $fromTime The second point in time for comparison.
     * @param  enum $timeUnit The time unit in which the difference is to be calculated. Can be `SECOND`, `MINUTE`,
     * `HOUR`, `DAY`, `WEEK`, `MONTH`, or `YEAR`.
     *
     * @return int The difference between the two points in time, measured in the time unit specified and as a positive
     * value if *this* point in time goes after the second one and as a negative value if *this* point in time goes
     * before the second one (except if the result is zero).
     */

    public function signedDiff (CTime $fromTime, $timeUnit)
    {
        assert( 'is_enum($timeUnit)', vs(isset($this), get_defined_vars()) );

        $dtz = new DateTimeZone(CTimeZone::UTC);
        $thisDt = new DateTime();
        $thisDt->setTimestamp($this->UTime());
        $thisDt->setTimezone($dtz);
        $fromDt = new DateTime();
        $fromDt->setTimestamp($fromTime->UTime());
        $fromDt->setTimezone($dtz);
        $dti = $fromDt->diff($thisDt, false);
        assert( 'is_number($dti->days)', vs(isset($this), get_defined_vars()) );

        $intDiffInSeconds =
            CMathi::abs($dti->days)*self::SECONDS_PER_DAY +
            CMathi::abs($dti->h)*self::SECONDS_PER_HOUR +
            CMathi::abs($dti->i)*self::SECONDS_PER_MINUTE +
            CMathi::abs($dti->s);
        $floatDiffInSeconds = (float)$intDiffInSeconds;
        $diff;
        switch ( $timeUnit )
        {
        case self::SECOND:
            $diff = $intDiffInSeconds;
            break;
        case self::MINUTE:
            $diff = CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_MINUTE);
            break;
        case self::HOUR:
            $diff = CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_HOUR);
            break;
        case self::DAY:
            $diff = CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_DAY);
            break;
        case self::WEEK:
            $diff = CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_WEEK);
            break;
        case self::MONTH:
            $diff = CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_MONTH);
            break;
        case self::YEAR:
            $diff = CMathi::floor($floatDiffInSeconds/self::SECONDS_PER_YEAR);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        if ( $dti->invert == 1 )
        {
            $diff = -$diff;
        }
        return $diff;
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in seconds as a positive value if *this*
     * point in time goes after the second one and as a negative value if *this* point in time goes before the second
     * one (except if the result is zero).
     */

    public function signedDiffInSeconds (CTime $fromTime)
    {
        return $this->signedDiff($fromTime, self::SECOND);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in minutes as a positive value if *this*
     * point in time goes after the second one and as a negative value if *this* point in time goes before the second
     * one (except if the result is zero).
     */

    public function signedDiffInMinutes (CTime $fromTime)
    {
        return $this->signedDiff($fromTime, self::MINUTE);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in hours as a positive value if *this* point
     * in time goes after the second one and as a negative value if *this* point in time goes before the second one
     * (except if the result is zero).
     */

    public function signedDiffInHours (CTime $fromTime)
    {
        return $this->signedDiff($fromTime, self::HOUR);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in days as a positive value if *this* point
     * in time goes after the second one and as a negative value if *this* point in time goes before the second one
     * (except if the result is zero).
     */

    public function signedDiffInDays (CTime $fromTime)
    {
        return $this->signedDiff($fromTime, self::DAY);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in weeks as a positive value if *this* point
     * in time goes after the second one and as a negative value if *this* point in time goes before the second one
     * (except if the result is zero).
     */

    public function signedDiffInWeeks (CTime $fromTime)
    {
        return $this->signedDiff($fromTime, self::WEEK);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in months as a positive value if *this*
     * point in time goes after the second one and as a negative value if *this* point in time goes before the second
     * one (except if the result is zero).
     */

    public function signedDiffInMonths (CTime $fromTime)
    {
        return $this->signedDiff($fromTime, self::MONTH);
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
     * @param  CTime $fromTime The second point in time for comparison.
     *
     * @return int The difference between the two points in time, measured in years as a positive value if *this* point
     * in time goes after the second one and as a negative value if *this* point in time goes before the second one
     * (except if the result is zero).
     */

    public function signedDiffInYears (CTime $fromTime)
    {
        return $this->signedDiff($fromTime, self::YEAR);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of time units and
     * returns the new point in time.
     *
     * @param  enum $timeUnit The time unit to be used for shifting. Can be `SECOND`, `MINUTE`, `HOUR`, `DAY`, `WEEK`,
     * `MONTH`, or `YEAR`.
     * @param  int $quantity The number of units by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedUtc ($timeUnit, $quantity)
    {
        assert( 'is_enum($timeUnit) && is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return self::shiftTimeInTimeZone($this, $timeUnit, $quantity, CTimeZone::UTC);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of seconds and returns
     * the new point in time.
     *
     * @param  int $quantity The number of seconds by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedBySecondsUtc ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::SECOND, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of minutes and returns
     * the new point in time.
     *
     * @param  int $quantity The number of minutes by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMinutesUtc ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::MINUTE, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of hours and returns
     * the new point in time.
     *
     * @param  int $quantity The number of hours by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByHoursUtc ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::HOUR, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of days and returns
     * the new point in time.
     *
     * @param  int $quantity The number of days by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByDaysUtc ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::DAY, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of weeks and returns
     * the new point in time.
     *
     * @param  int $quantity The number of weeks by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByWeeksUtc ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::WEEK, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of months and returns
     * the new point in time.
     *
     * @param  int $quantity The number of months by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMonthsUtc ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::MONTH, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the UTC time zone by a specified number of years and returns
     * the new point in time.
     *
     * @param  int $quantity The number of years by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByYearsUtc ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedUtc(self::YEAR, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of time units and
     * returns the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the point in time is to be shifted.
     * @param  enum $timeUnit The time unit to be used for shifting. Can be `SECOND`, `MINUTE`, `HOUR`, `DAY`, `WEEK`,
     * `MONTH`, or `YEAR`.
     * @param  int $quantity The number of units by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedInTimeZone (CTimeZone $timeZone, $timeUnit, $quantity)
    {
        assert( 'is_enum($timeUnit) && is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return self::shiftTimeInTimeZone($this, $timeUnit, $quantity, $timeZone);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of seconds and
     * returns the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the point in time is to be shifted.
     * @param  int $quantity The number of seconds by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedBySecondsInTimeZone (CTimeZone $timeZone, $quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($timeZone, self::SECOND, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of minutes and
     * returns the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the point in time is to be shifted.
     * @param  int $quantity The number of minutes by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMinutesInTimeZone (CTimeZone $timeZone, $quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($timeZone, self::MINUTE, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of hours and
     * returns the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the point in time is to be shifted.
     * @param  int $quantity The number of hours by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByHoursInTimeZone (CTimeZone $timeZone, $quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($timeZone, self::HOUR, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of days and
     * returns the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the point in time is to be shifted.
     * @param  int $quantity The number of days by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByDaysInTimeZone (CTimeZone $timeZone, $quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($timeZone, self::DAY, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of weeks and
     * returns the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the point in time is to be shifted.
     * @param  int $quantity The number of weeks by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByWeeksInTimeZone (CTimeZone $timeZone, $quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($timeZone, self::WEEK, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of months and
     * returns the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the point in time is to be shifted.
     * @param  int $quantity The number of months by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMonthsInTimeZone (CTimeZone $timeZone, $quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($timeZone, self::MONTH, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of a specified time zone by a specified number of years and
     * returns the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the point in time is to be shifted.
     * @param  int $quantity The number of years by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByYearsInTimeZone (CTimeZone $timeZone, $quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedInTimeZone($timeZone, self::YEAR, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of time
     * units and returns the new point in time.
     *
     * @param  enum $timeUnit The time unit to be used for shifting. Can be `SECOND`, `MINUTE`, `HOUR`, `DAY`, `WEEK`,
     * `MONTH`, or `YEAR`.
     * @param  int $quantity The number of units by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedLocal ($timeUnit, $quantity)
    {
        assert( 'is_enum($timeUnit) && is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return self::shiftTimeInTimeZone($this, $timeUnit, $quantity, date_default_timezone_get());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of seconds
     * and returns the new point in time.
     *
     * @param  int $quantity The number of seconds by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedBySecondsLocal ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::SECOND, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of minutes
     * and returns the new point in time.
     *
     * @param  int $quantity The number of minutes by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMinutesLocal ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::MINUTE, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of hours and
     * returns the new point in time.
     *
     * @param  int $quantity The number of hours by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByHoursLocal ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::HOUR, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of days and
     * returns the new point in time.
     *
     * @param  int $quantity The number of days by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByDaysLocal ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::DAY, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of weeks and
     * returns the new point in time.
     *
     * @param  int $quantity The number of weeks by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByWeeksLocal ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::WEEK, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of months
     * and returns the new point in time.
     *
     * @param  int $quantity The number of months by which the point in time is to be shifted. This is a positive
     * value for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByMonthsLocal ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::MONTH, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Shifts a point in time into the future or past of the PHP's default time zone by a specified number of years and
     * returns the new point in time.
     *
     * @param  int $quantity The number of years by which the point in time is to be shifted. This is a positive value
     * for shifting into the future or a negative value for shifting into the past.
     *
     * @return CTime The shifted point in time.
     */

    public function shiftedByYearsLocal ($quantity)
    {
        assert( 'is_int($quantity)', vs(isset($this), get_defined_vars()) );
        return $this->shiftedLocal(self::YEAR, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the millisecond component of a point in time and returns the new point in time.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     *
     * @param  int $newMillisecond The new number of the millisecond (from 0 to 999).
     *
     * @return CTime The modified point in time.
     */

    public function withMillisecondUtc ($newMillisecond)
    {
        assert( '0 <= $newMillisecond && $newMillisecond <= 999', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        $millisecond = $newMillisecond;
        return self::fromComponentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the second component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $newSecond The new number of the second (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withSecondUtc ($newSecond)
    {
        assert( '0 <= $newSecond && $newSecond <= 59', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        $second = $newSecond;
        return self::fromComponentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the minute component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $newMinute The new number of the minute (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withMinuteUtc ($newMinute)
    {
        assert( '0 <= $newMinute && $newMinute <= 59', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        $minute = $newMinute;
        return self::fromComponentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the hour component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $newHour The new number of the hour (from 0 to 23).
     *
     * @return CTime The modified point in time.
     */

    public function withHourUtc ($newHour)
    {
        assert( '0 <= $newHour && $newHour <= 23', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        $hour = $newHour;
        return self::fromComponentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the day component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $newDay The new number of the day (from 1 to 31).
     *
     * @return CTime The modified point in time.
     */

    public function withDayUtc ($newDay)
    {
        assert( '1 <= $newDay && $newDay <= 31', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        $day = $newDay;
        return self::fromComponentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the month component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $newMonth The new number of the month (from 1 to 12).
     *
     * @return CTime The modified point in time.
     */

    public function withMonthUtc ($newMonth)
    {
        assert( '1 <= $newMonth && $newMonth <= 12', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        $month = $newMonth;
        return self::fromComponentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the year component of a point in time, assuming the UTC time zone for its components, and returns the
     * new point in time.
     *
     * @param  int $newYear The new number of the year.
     *
     * @return CTime The modified point in time.
     */

    public function withYearUtc ($newYear)
    {
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
        $year = $newYear;
        return self::fromComponentsUtc($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the millisecond component of a point in time and returns the new point in time.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to be modified.
     * @param  int $newMillisecond The new number of the millisecond (from 0 to 999).
     *
     * @return CTime The modified point in time.
     */

    public function withMillisecondInTimeZone (CTimeZone $timeZone, $newMillisecond)
    {
        assert( '0 <= $newMillisecond && $newMillisecond <= 999', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond);
        $millisecond = $newMillisecond;
        return self::fromComponentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second,
            $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the second component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to be modified.
     * @param  int $newSecond The new number of the second (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withSecondInTimeZone (CTimeZone $timeZone, $newSecond)
    {
        assert( '0 <= $newSecond && $newSecond <= 59', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond);
        $second = $newSecond;
        return self::fromComponentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second,
            $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the minute component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to be modified.
     * @param  int $newMinute The new number of the minute (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withMinuteInTimeZone (CTimeZone $timeZone, $newMinute)
    {
        assert( '0 <= $newMinute && $newMinute <= 59', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond);
        $minute = $newMinute;
        return self::fromComponentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second,
            $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the hour component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to be modified.
     * @param  int $newHour The new number of the hour (from 0 to 23).
     *
     * @return CTime The modified point in time.
     */

    public function withHourInTimeZone (CTimeZone $timeZone, $newHour)
    {
        assert( '0 <= $newHour && $newHour <= 23', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond);
        $hour = $newHour;
        return self::fromComponentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second,
            $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the day component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to be modified.
     * @param  int $newDay The new number of the day (from 1 to 31).
     *
     * @return CTime The modified point in time.
     */

    public function withDayInTimeZone (CTimeZone $timeZone, $newDay)
    {
        assert( '1 <= $newDay && $newDay <= 31', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond);
        $day = $newDay;
        return self::fromComponentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second,
            $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the month component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to be modified.
     * @param  int $newMonth The new number of the month (from 1 to 12).
     *
     * @return CTime The modified point in time.
     */

    public function withMonthInTimeZone (CTimeZone $timeZone, $newMonth)
    {
        assert( '1 <= $newMonth && $newMonth <= 12', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond);
        $month = $newMonth;
        return self::fromComponentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second,
            $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the year component of a point in time, assuming a specified time zone for its components, and returns
     * the new point in time.
     *
     * @param  CTimeZone $timeZone The time zone in which the component is to be modified.
     * @param  int $newYear The new number of the year.
     *
     * @return CTime The modified point in time.
     */

    public function withYearInTimeZone (CTimeZone $timeZone, $newYear)
    {
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second, $millisecond);
        $year = $newYear;
        return self::fromComponentsInTimeZone($timeZone, $year, $month, $day, $hour, $minute, $second,
            $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the millisecond component of a point in time and returns the new point in time.
     *
     * This method only exists for consistency among the methods (the milliseconds are the same across all time zones).
     *
     * @param  int $newMillisecond The new number of the millisecond (from 0 to 999).
     *
     * @return CTime The modified point in time.
     */

    public function withMillisecondLocal ($newMillisecond)
    {
        assert( '0 <= $newMillisecond && $newMillisecond <= 999', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        $millisecond = $newMillisecond;
        return self::fromComponentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the second component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $newSecond The new number of the second (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withSecondLocal ($newSecond)
    {
        assert( '0 <= $newSecond && $newSecond <= 59', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        $second = $newSecond;
        return self::fromComponentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the minute component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $newMinute The new number of the minute (from 0 to 59).
     *
     * @return CTime The modified point in time.
     */

    public function withMinuteLocal ($newMinute)
    {
        assert( '0 <= $newMinute && $newMinute <= 59', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        $minute = $newMinute;
        return self::fromComponentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the hour component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $newHour The new number of the hour (from 0 to 23).
     *
     * @return CTime The modified point in time.
     */

    public function withHourLocal ($newHour)
    {
        assert( '0 <= $newHour && $newHour <= 23', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        $hour = $newHour;
        return self::fromComponentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the day component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $newDay The new number of the day (from 1 to 31).
     *
     * @return CTime The modified point in time.
     */

    public function withDayLocal ($newDay)
    {
        assert( '1 <= $newDay && $newDay <= 31', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        $day = $newDay;
        return self::fromComponentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the month component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $newMonth The new number of the month (from 1 to 12).
     *
     * @return CTime The modified point in time.
     */

    public function withMonthLocal ($newMonth)
    {
        assert( '1 <= $newMonth && $newMonth <= 12', vs(isset($this), get_defined_vars()) );

        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        $month = $newMonth;
        return self::fromComponentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Modifies the year component of a point in time, assuming the PHP's default time zone for its components, and
     * returns the new point in time.
     *
     * @param  int $newYear The new number of the year.
     *
     * @return CTime The modified point in time.
     */

    public function withYearLocal ($newYear)
    {
        $year;
        $month;
        $day;
        $hour;
        $minute;
        $second;
        $millisecond;
        $this->componentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
        $year = $newYear;
        return self::fromComponentsLocal($year, $month, $day, $hour, $minute, $second, $millisecond);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function componentsInTimeZoneToTime ($year, $month, $day, $hour, $minute, $second,
        $millisecond, $timeZone)
    {
        assert( 'self::areComponentsValid($year, $month, $day, $hour, $minute, $second, $millisecond)',
            vs(isset($this), get_defined_vars()) );
        assert( '1000 <= $year && $year <= 9999', vs(isset($this), get_defined_vars()) );

        $strYear = CString::fromInt($year);
        $strMonth = CString::fromInt($month);
        $strDay = CString::fromInt($day);
        $strHour = CString::padStart(CString::fromInt($hour), "0", 2);
        $strMinute = CString::padStart(CString::fromInt($minute), "0", 2);
        $strSecond = CString::padStart(CString::fromInt($second), "0", 2);
        $dt = DateTime::createFromFormat("Y,m,d,H,i,s", "$strYear,$strMonth,$strDay,$strHour,$strMinute,$strSecond",
            ( is_cstring($timeZone) ) ? new DateTimeZone($timeZone) : $timeZone->DTimeZone());
        if ( !is_object($dt) )
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }

        $UTime = $dt->getTimestamp();
        $MTime;
        if ( !($UTime < 0 && $millisecond != 0) )
        {
            $MTime = $millisecond;
        }
        else
        {
            $UTime++;
            $MTime = $millisecond - 1000;
        }
        return new self($UTime, $MTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function timeToStringInTimeZone (CTime $time, $pattern, $timeZone)
    {
        $dt = new DateTime();
        $dt->setTimestamp($time->UTime());
        $dt->setTimezone(( is_cstring($timeZone) ) ? new DateTimeZone($timeZone) : $timeZone->DTimeZone());
        $strPattern = ( is_enum($pattern) ) ? self::patternEnumToString($pattern) : $pattern;
        $time = $dt->format($strPattern);
        if ( is_cstring($time) )
        {
            return $time;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function timeToComponentsInTimeZone (CTime $time, $timeZone, &$year, &$month, &$day,
        &$hour, &$minute, &$second, &$millisecond, &$dayOfWeek)
    {
        $FTime = $time->FTime();
        $UTime = $time->UTime();
        $MTime = $time->MTime();
        $negMsCase = false;

        if ( $FTime < 0.0 && $MTime != 0 )
        {
            $negMsCase = true;
            $UTime--;
        }

        $time = self::timeToStringInTimeZone(new self($UTime), "Y,m,d,H,i,s,w", $timeZone);
        $components = CString::split($time, ",");
        $year =      CString::toInt($components[0]);
        $month =     CString::toInt($components[1]);
        $day =       CString::toInt($components[2]);
        $hour =      CString::toInt($components[3]);
        $minute =    CString::toInt($components[4]);
        $second =    CString::toInt($components[5]);
        $dayOfWeek = CString::toInt($components[6]);

        if ( !$negMsCase )
        {
            $millisecond = $MTime;
        }
        else
        {
            $millisecond = $MTime + 1000;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function shiftTimeInTimeZone (CTime $time, $timeUnit, $quantity, $timeZone)
    {
        $units;
        switch ( $timeUnit )
        {
        case self::SECOND:
            $units = "seconds";
            break;
        case self::MINUTE:
            $units = "minutes";
            break;
        case self::HOUR:
            $units = "hours";
            break;
        case self::DAY:
            $units = "days";
            break;
        case self::WEEK:
            $units = "weeks";
            break;
        case self::MONTH:
            $units = "months";
            break;
        case self::YEAR:
            $units = "years";
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        $dt = new DateTime();
        $dt->setTimestamp($time->UTime());
        $dt->setTimezone(( is_cstring($timeZone) ) ? new DateTimeZone($timeZone) : $timeZone->DTimeZone());
        $sign = ( $quantity < 0 ) ? "-" : "+";
        $absQty = CString::fromInt(CMathi::abs($quantity));
        $dt->modify("$sign$absQty $units");

        $UTime = $dt->getTimestamp();
        $MTime = $time->MTime();
        if ( $UTime != 0 && $MTime != 0 && CMathi::sign($UTime) != CMathi::sign($MTime) )
        {
            if ( $UTime < 0 )
            {
                // $MTime > 0
                $UTime++;
                $MTime -= 1000;
            }
            else
            {
                // $MTime < 0
                $UTime--;
                $MTime += 1000;
            }
        }
        return new self($UTime, $MTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function patternEnumToString ($pattern)
    {
        switch ( $pattern )
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

    protected $m_FTime;
}
