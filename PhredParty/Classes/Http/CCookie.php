<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class of HTTP cookies when they are a part of an HTTP response.
 *
 * **Defaults:**
 *
 * * The cookie expires together with the end of the user agent's session.
 * * The cookie's path is global ("/").
 * * The cookie's domain is not specified (should default to the domain from which the cookie was set).
 * * The cookie is not secure (not limited to just encrypted connections).
 * * The cookie is HttpOnly (not accessible from JavaScript).
 */

// Method signatures:
//   __construct ($sCookieName, $xValue)
//   void setExpireInterval ($eTimeUnit, $iQuantity)
//   void setExpireTime (CTime $oTime)
//   void setPath ($sPath)
//   void setDomain ($sDomain)
//   void setSecure ($bEnable)
//   void setHttpOnly ($bEnable)
//   CUStringObject name ()
//   CUStringObject value ()
//   int expireUTime ()
//   CUStringObject path ()
//   CUStringObject domain ()
//   bool secure ()
//   bool httpOnly ()

class CCookie extends CRootClass
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an HTTP cookie to be sent along with an HTTP response.
     *
     * Internally, the value of a cookie is always stored as a string. If the cookie's value was provided as an array
     * or map, it's encoded into JSON and is stored as a JSON string. Boolean values are stored as "1" for `true` and
     * as "0" for `false`.
     *
     * @param  string $sCookieName The cookie's name.
     * @param  mixed $xValue The cookie's value. This can be a string, a `bool`, an `int`, a `float`, an array, or a
     * map.
     */

    public function __construct ($sCookieName, $xValue)
    {
        assert( 'is_cstring($sCookieName) && (is_cstring($xValue) || is_bool($xValue) || is_int($xValue) || ' .
                'is_float($xValue) || is_collection($xValue))', vs(isset($this), get_defined_vars()) );

        $this->m_sName = $sCookieName;

        if ( is_cstring($xValue) )
        {
            $this->m_sValue = $xValue;
        }
        else if ( is_bool($xValue) )
        {
            $this->m_sValue = CString::fromBool10($xValue);
        }
        else if ( is_int($xValue) )
        {
            $this->m_sValue = CString::fromInt($xValue);
        }
        else if ( is_float($xValue) )
        {
            $this->m_sValue = CString::fromFloat($xValue);
        }
        else  // a collection
        {
            $oJson = new CJson($xValue);
            $this->m_sValue = $oJson->encode();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the point in time after which a cookie should be considered expired, as a time interval since the current
     * time.
     *
     * @param  enum $eTimeUnit The time unit in which the interval is to be calculated. Can be `CTime::SECOND`,
     * `CTime::MINUTE`, `CTime::HOUR`, `CTime::DAY`, `CTime::WEEK`, `CTime::MONTH`, or `CTime::YEAR`.
     * @param  int $iQuantity The quantity of the specified time units.
     *
     * @return void
     */

    public function setExpireInterval ($eTimeUnit, $iQuantity)
    {
        assert( 'is_enum($eTimeUnit) && is_int($iQuantity)', vs(isset($this), get_defined_vars()) );
        assert( '$iQuantity > 0', vs(isset($this), get_defined_vars()) );

        $oCurrTime = CTime::now();
        $oExpireTime = $oCurrTime->shiftedUtc($eTimeUnit, $iQuantity);
        $this->m_iExpireUTime = $oExpireTime->UTime();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the point in time after which a cookie should be considered expired, as a known moment.
     *
     * @param  CTime $oTime The point in time when the cookie should expire.
     *
     * @return void
     */

    public function setExpireTime (CTime $oTime)
    {
        $this->m_iExpireUTime = $oTime->UTime();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the path of a cookie.
     *
     * @param  string $sPath The path.
     *
     * @return void
     */

    public function setPath ($sPath)
    {
        assert( 'is_cstring($sPath)', vs(isset($this), get_defined_vars()) );
        $this->m_sPath = $sPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the domain of a cookie.
     *
     * @param  string $sDomain The domain.
     *
     * @return void
     */

    public function setDomain ($sDomain)
    {
        assert( 'is_cstring($sDomain)', vs(isset($this), get_defined_vars()) );
        $this->m_sDomain = $sDomain;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether the sending of a cookie back to the server should be limited to just encrypted connections.
     *
     * @param  bool $bEnable `true` if the cookie should be limited to just encrypted connections, `false` if not.
     *
     * @return void
     */

    public function setSecure ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bSecure = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether the access to a cookie should be limited to just HTTP/HTTPS methods of access.
     *
     * @param  bool $bEnable `true` if the access to the cookie should be limited to just HTTP/HTTPS methods of access,
     * `false` if not.
     *
     * @return void
     */

    public function setHttpOnly ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bHttpOnly = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the name of a cookie.
     *
     * @return CUStringObject The cookie's name.
     */

    public function name ()
    {
        return $this->m_sName;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a cookie.
     *
     * @return CUStringObject The cookie's value.
     */

    public function value ()
    {
        return $this->m_sValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the "Unix time" of the moment when a cookie is set to expire, which is the number of seconds relative to
     * the midnight of January 1, 1970 UTC.
     *
     * @return int The "Unix time" of the cookie's expiration.
     */

    public function expireUTime ()
    {
        return $this->m_iExpireUTime;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path of a cookie.
     *
     * @return CUStringObject The cookie's path.
     */

    public function path ()
    {
        return $this->m_sPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the domain of a cookie.
     *
     * @return CUStringObject The cookie's domain.
     */

    public function domain ()
    {
        return $this->m_sDomain;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells whether a cookie is limited to just encrypted connections.
     *
     * @return bool `true` if the cookie is limited to just encrypted connections, `false` otherwise.
     */

    public function secure ()
    {
        return $this->m_bSecure;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells whether the access to a cookie is limited to just HTTP/HTTPS methods of access.
     *
     * @return bool `true` if the access to the cookie is limited to just HTTP/HTTPS methods of access, `false`
     * otherwise.
     */

    public function httpOnly ()
    {
        return $this->m_bHttpOnly;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected $m_sName;
    protected $m_sValue;
    protected $m_iExpireUTime = 0;
    protected $m_sPath = "/";
    protected $m_sDomain = "";
    protected $m_bSecure = false;
    protected $m_bHttpOnly = true;
}
