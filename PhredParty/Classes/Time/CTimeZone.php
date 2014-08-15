<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that represents time zones around the world and is the best friend to the CTime class.
 *
 * **You can refer to this class by its alias, which is** `Tz`.
 *
 * Time zones is the reason why the local time in Finland is always different from that in California. And time
 * differences are not determined by just the distance between geographical locations but also by the time keeping
 * policies used by the countries to which the locations belong, such as daylight saving time. Because of this variety
 * in local times, a special time zone exists, called universal time or UTC (short for Coordinated Universal Time), and
 * it usually serves as the reference time zone for any moments in time to be compared with one another. You can create
 * a UTC time zone with a convenience method, which is `makeUtc` static method of the class.
 *
 * A time zone is primarily identified by its name and the same time zone can have multiple names. Unlike locale names,
 * time zone names are case-sensitive. You can find out the known time zone names with `knownNames` static method. Most
 * of the time zone names are organized into regions, which are the Americas, Europe, Asia and so on. The known time
 * zone regions can be obtained with `knownRegions` static method or by simply looking at the constants of the class
 * that start with `REGION`.
 */

// Method signatures:
//   __construct ($sName)
//   static CTimeZone makeUtc ()
//   CUStringObject name ()
//   CUStringObject dispName ($eStyle = self::STYLE_LONG, CULocale $oInLocale = null)
//   CUStringObject dispEnName ()
//   CUStringObject dispEnNameWithoutRegion ()
//   int currentOffsetSeconds ()
//   int standardOffsetSeconds ()
//   bool equals ($oToTimeZone)
//   static CUStringObject dispEnRegion ($eRegion)
//   static CArrayObject knownRegions ()
//   static CArrayObject knownNames ()
//   static CArrayObject knownNamesWithBc ()
//   static CArrayObject knownNamesForRegion ($eRegion)
//   static CArrayObject knownNamesForCountry ($sCountryCode)
//   static bool isNameKnown ($sName)

class CTimeZone extends CRootClass implements IEquality
{
    /**
     * `string` "UTC" The name of the UTC time zone.
     *
     * @var string
     */
    const UTC = "UTC";

    // Time zone regions.
    /**
     * `enum` Africa.
     *
     * @var enum
     */
    const REGION_AFRICA = 0;
    /**
     * `enum` The Americas.
     *
     * @var enum
     */
    const REGION_AMERICA = 1;
    /**
     * `enum` Antarctica.
     *
     * @var enum
     */
    const REGION_ANTARCTICA = 2;
    /**
     * `enum` Arctic.
     *
     * @var enum
     */
    const REGION_ARCTIC = 3;
    /**
     * `enum` Asia.
     *
     * @var enum
     */
    const REGION_ASIA = 4;
    /**
     * `enum` The Atlantic Ocean.
     *
     * @var enum
     */
    const REGION_ATLANTIC = 5;
    /**
     * `enum` Australia.
     *
     * @var enum
     */
    const REGION_AUSTRALIA = 6;
    /**
     * `enum` Europe.
     *
     * @var enum
     */
    const REGION_EUROPE = 7;
    /**
     * `enum` The Indian Ocean.
     *
     * @var enum
     */
    const REGION_INDIAN = 8;
    /**
     * `enum` The Pacific Ocean.
     *
     * @var enum
     */
    const REGION_PACIFIC = 9;

    // Time zone display styles.
    /**
     * `enum` Use the short style for the name of a time zone when displaying it in a locale.
     *
     * @var enum
     */
    const STYLE_SHORT = 0;
    /**
     * `enum` Use the long style for the name of a time zone when displaying it in a locale.
     *
     * @var enum
     */
    const STYLE_LONG = 1;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a time zone with a specified name.
     *
     * @param  string $sName The name of the time zone (case-sensitive).
     */

    public function __construct ($sName)
    {
        assert( 'is_cstring($sName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::isNameKnown($sName)', vs(isset($this), get_defined_vars()) );

        $this->m_oDtz = new DateTimeZone($sName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates and returns a UTC time zone.
     *
     * @return CTimeZone A UTC time zone.
     */

    public static function makeUtc ()
    {
        return new self(self::UTC);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of a time zone.
     *
     * A returned name is a technical one and is not always suitable for displaying to a human. If the time zone's name
     * needs to be shown on a screen, consider using `dispName` method instead.
     *
     * @return CUStringObject The name of the time zone.
     *
     * @link   #method_dispName dispName
     */

    public function name ()
    {
        return $this->m_oDtz->getName();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the human-readable name of a time zone, written in the language of a specified locale.
     *
     * @param  enum $eStyle **OPTIONAL. Default is** `STYLE_LONG`. The display style of the name. The available styles
     * are `STYLE_SHORT` and `STYLE_LONG`.
     * @param  CULocale $oInLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * name is to be displayed.
     *
     * @return CUStringObject The human-readable name of the time zone.
     */

    public function dispName ($eStyle = self::STYLE_LONG, CULocale $oInLocale = null)
    {
        assert( 'is_enum($eStyle)', vs(isset($this), get_defined_vars()) );

        $iItzStyle;
        switch ( $eStyle )
        {
        case self::STYLE_SHORT:
            $iItzStyle = IntlTimeZone::DISPLAY_SHORT;
            break;
        case self::STYLE_LONG:
            $iItzStyle = IntlTimeZone::DISPLAY_LONG;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        $oItz = $this->ITimeZone();
        $sLocale = ( isset($oInLocale) ) ? $oInLocale->name() : CULocale::defaultLocaleName();
        $sDispName = $oItz->getDisplayName(false, $iItzStyle, $sLocale);
        if ( is_cstring($sDispName) )
        {
            return $sDispName;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the human-readable name of a time zone, deriving it from the technical English name.
     *
     * For example, if the name of the time zone is "Europe/Helsinki", this method returns "Europe, Helsinki" (the
     * order of components is not reversed for better sorting).
     *
     * You may also consider using `dispName` method.
     *
     * @return CUStringObject The human-readable name of the time zone.
     *
     * @link   #method_dispName dispName
     */

    public function dispEnName ()
    {
        $sDispName = $this->name();
        $sDispName = self::makeEnName($sDispName);
        return $sDispName;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the human-readable name of a time zone, deriving it from the technical English name and leaving out the
     * region part.
     *
     * For example, if the name of the time zone is "Europe/Helsinki", this method returns "Helsinki".
     *
     * You may also consider using `dispName` method.
     *
     * @return CUStringObject The human-readable name of the time zone.
     *
     * @link   #method_dispName dispName
     */

    public function dispEnNameWithoutRegion ()
    {
        $sDispName = $this->name();
        $sDispName = CRegex::remove($sDispName, "/^.*?\\//");
        $sDispName = self::makeEnName($sDispName);
        return $sDispName;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the offset of a time zone from UTC, as it is at the time being, adjusting the offset for daylight saving
     * time if it's currently effective in the time zone.
     *
     * The offset is negative for the time zones located west of UTC (Greenwich, UK) and positive for the eastern ones.
     *
     * @return int The time zone's current offset from UTC, in seconds.
     */

    public function currentOffsetSeconds ()
    {
        $oItz = $this->ITimeZone();
        $iOffset;
        $iDstOffset;
        $oItz->getOffset(time()*1000, false, $iOffset, $iDstOffset);
        return CMathi::round(((float)($iOffset + $iDstOffset))/1000);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the offset of a time zone from UTC, without adjusting it for daylight saving time.
     *
     * The offset is negative for the time zones located west of UTC (Greenwich, UK) and positive for the eastern ones.
     *
     * @return int The time zone's standard offset from UTC, in seconds.
     */

    public function standardOffsetSeconds ()
    {
        $oItz = $this->ITimeZone();
        $iOffset;
        $iDstOffset;
        $oItz->getOffset(time()*1000, false, $iOffset, $iDstOffset);
        return CMathi::round(((float)$iOffset)/1000);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a time zone is equal to another time zone, comparing them by name.
     *
     * @param  CTimeZone $oToTimeZone The second time zone for comparison.
     *
     * @return bool `true` if *this* time zone is equal to the second time zone, `false` otherwise.
     */

    public function equals ($oToTimeZone)
    {
        // Parameter type hinting is not used for the purpose of interface compatibility.
        assert( 'is_ctimezone($oToTimeZone)', vs(isset($this), get_defined_vars()) );
        return CString::equals($this->name(), $oToTimeZone->name());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the human-readable name of a time zone region, in English.
     *
     * @param  enum $eRegion The time zone region (see [Summary](#summary)).
     *
     * @return CUStringObject The human-readable name of the time zone region.
     */

    public static function dispEnRegion ($eRegion)
    {
        assert( 'is_enum($eRegion)', vs(isset($this), get_defined_vars()) );

        switch ( $eRegion )
        {
        case self::REGION_AFRICA:
            return "Africa";
        case self::REGION_AMERICA:
            return "America";
        case self::REGION_ANTARCTICA:
            return "Antarctica";
        case self::REGION_ARCTIC:
            return "Arctic";
        case self::REGION_ASIA:
            return "Asia";
        case self::REGION_ATLANTIC:
            return "Atlantic";
        case self::REGION_AUSTRALIA:
            return "Australia";
        case self::REGION_EUROPE:
            return "Europe";
        case self::REGION_INDIAN:
            return "Indian";
        case self::REGION_PACIFIC:
            return "Pacific";
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the known time zone regions.
     *
     * @return CArrayObject The known time zone regions of type `enum` (see [Summary](#summary)).
     */

    public static function knownRegions ()
    {
        return oop_a(CArray::fromElements(
            self::REGION_AFRICA,
            self::REGION_AMERICA,
            self::REGION_ANTARCTICA,
            self::REGION_ARCTIC,
            self::REGION_ASIA,
            self::REGION_ATLANTIC,
            self::REGION_AUSTRALIA,
            self::REGION_EUROPE,
            self::REGION_INDIAN,
            self::REGION_PACIFIC));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the known time zone names.
     *
     * @return CArrayObject The known time zone names of type `CUStringObject`.
     */

    public static function knownNames ()
    {
        $mNames = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $mNames = CMap::filter($mNames, "CTimeZone::isNameIcuCompatible");
        return oop_a(CArray::fromPArray($mNames));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the known time zone names, together with the backward compatible ones.
     *
     * The backward compatible time zone names are those names that are out of common use but are still (partially)
     * supported.
     *
     * @return CArrayObject The known time zone names, including the backward compatible ones, all of type
     * `CUStringObject`.
     */

    public static function knownNamesWithBc ()
    {
        $mNames = DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC);
        $mNames = CMap::filter($mNames, "CTimeZone::isNameIcuCompatible");
        return oop_a(CArray::fromPArray($mNames));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the names of the time zones that are known for a specified region.
     *
     * @param  enum $eRegion The time zone region (see [Summary](#summary)).
     *
     * @return CArrayObject The known time zone names for the region specified, of type `CUStringObject`.
     */

    public static function knownNamesForRegion ($eRegion)
    {
        assert( 'is_enum($eRegion)', vs(isset($this), get_defined_vars()) );

        $iDtzRegion;
        switch ( $eRegion )
        {
        case self::REGION_AFRICA:
            $iDtzRegion = DateTimeZone::AFRICA;
            break;
        case self::REGION_AMERICA:
            $iDtzRegion = DateTimeZone::AMERICA;
            break;
        case self::REGION_ANTARCTICA:
            $iDtzRegion = DateTimeZone::ANTARCTICA;
            break;
        case self::REGION_ARCTIC:
            $iDtzRegion = DateTimeZone::ARCTIC;
            break;
        case self::REGION_ASIA:
            $iDtzRegion = DateTimeZone::ASIA;
            break;
        case self::REGION_ATLANTIC:
            $iDtzRegion = DateTimeZone::ATLANTIC;
            break;
        case self::REGION_AUSTRALIA:
            $iDtzRegion = DateTimeZone::AUSTRALIA;
            break;
        case self::REGION_EUROPE:
            $iDtzRegion = DateTimeZone::EUROPE;
            break;
        case self::REGION_INDIAN:
            $iDtzRegion = DateTimeZone::INDIAN;
            break;
        case self::REGION_PACIFIC:
            $iDtzRegion = DateTimeZone::PACIFIC;
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }
        $mNames = DateTimeZone::listIdentifiers($iDtzRegion);
        $mNames = CMap::filter($mNames, "CTimeZone::isNameIcuCompatible");
        return oop_a(CArray::fromPArray($mNames));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the names of the time zones that are known for a specified country.
     *
     * If the country's code is not recognized for any reason, the entire list of the known time zone names is
     * returned.
     *
     * @param  string $sCountryCode The two-letter code of the country, as provided by ISO 3166.
     *
     * @return CArrayObject The known time zone names for the country specified, of type `CUStringObject`.
     */

    public static function knownNamesForCountry ($sCountryCode)
    {
        assert( 'is_cstring($sCountryCode)', vs(isset($this), get_defined_vars()) );

        $mNames = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $sCountryCode);
        $mNames = CMap::filter($mNames, "CTimeZone::isNameIcuCompatible");
        $aNames = CArray::fromPArray($mNames);
        if ( is_cmap($mNames) && !CArray::isEmpty($aNames) )
        {
            return oop_a($aNames);
        }
        else
        {
            return oop_a(self::knownNames());
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a time zone name is a known one.
     *
     * The method also searches among the backward compatible names.
     *
     * @param  string $sName The time zone name to be looked for (case-sensitive).
     *
     * @return bool `true` if the name is known, `false` otherwise.
     */

    public static function isNameKnown ($sName)
    {
        assert( 'is_cstring($sName)', vs(isset($this), get_defined_vars()) );

        $mKnownNamesWithBc = DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC);
        return ( CMap::find($mKnownNamesWithBc, $sName) && self::isNameIcuCompatible($sName) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public static function isNameIcuCompatible ($sName)
    {
        assert( 'is_cstring($sName)', vs(isset($this), get_defined_vars()) );

        $oItz = IntlTimeZone::createTimeZone($sName);
        return CString::equals($sName, $oItz->getID());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function DTimeZone ()
    {
        return $this->m_oDtz;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function ITimeZone ()
    {
        return IntlTimeZone::createTimeZone($this->name());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __clone ()
    {
        $this->m_oDtz = clone $this->m_oDtz;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function makeEnName ($sName)
    {
        $sName = CString::replace($sName, "/", ", ");
        $sName = CString::replace($sName, "_", " ");
        $sName = CRegex::replace($sName, "/[^A-Z0-9\\-+,]/i", " ");
        $sName = CString::normSpacing($sName);
        return $sName;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_oDtz;
}
