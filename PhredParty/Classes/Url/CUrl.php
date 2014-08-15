<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * This class lets you parse URLs into constituting parts, normalize and compare URLs, compose URLs from parts, ensure
 * protocols for URLs, validate URLs, and put strings through percent-encoding (URL encoding) in either direction.
 *
 * To parse a URL, you would construct an object of this class from a URL string, and to compose a URL string, you
 * would use `makeUrlString` static method.
 *
 * URL normalization, which is the concern of `normalizedUrlWithoutFragmentId` and `normalizedUrl` methods and of
 * `normalize` static method, can be useful in URL comparison and collation. The normalization includes converting the
 * protocol and host parts into lowercase, decoding any percent-encoded characters for which being encoded is not
 * required by the URL standard, ensuring that hexadecimal letters in percent-encoded characters appear uppercased,
 * removing duplicate fields from the query string and adding "=" after any field name that goes without a value and is
 * not followed by "=", sorting fields in the query string, and removing the port number if that number is the default
 * one for the URL's protocol. Removing dot-segments ("..", ".") from the path part is avoided as it may completely
 * change semantics for URLs that, instead of pointing to a file or directory on the server, use the path's components
 * as messages to the server's software.
 *
 * When parsing a URL string into parts, `normalizedProtocol` method can be called even if the URL appears without a
 * protocol, in which case the method will return the default protocol ("http"). Likewise, `normalizedPathString` and
 * `path` methods can be called and will return "/" path even if the URL does not specify any path.
 */

// Method signatures:
//   __construct ($sUrl)
//   CUStringObject url ()
//   bool hasProtocol ()
//   CUStringObject protocol ()
//   CUStringObject normalizedProtocol ()
//   CUStringObject host ()
//   CUStringObject normalizedHost ()
//   bool hasPort ()
//   int port ()
//   bool hasPath ()
//   CUStringObject pathString ($bDecode = false)
//   CUStringObject normalizedPathString ($bDecode = false)
//   CUrlPath path ()
//   bool hasQuery ()
//   CUStringObject queryString ($bDecode = false)
//   CUStringObject normalizedQueryString ($bDecode = false)
//   CUrlQuery query ()
//   bool hasFragmentId ()
//   CUStringObject fragmentId ($bDecode = false)
//   CUStringObject normalizedFragmentId ($bDecode = false)
//   bool hasUser ()
//   CUStringObject user ()
//   bool hasPassword ()
//   CUStringObject password ()
//   CUStringObject normalizedUrlWithoutFragmentId ()
//   CUStringObject normalizedUrl ()
//   bool equals ($oToUrl, $bIgnoreFragmentId = true)
//   static CUStringObject ensureProtocol ($sUrl, $sDefaultProtocol = self::DEFAULT_PROTOCOL)
//   static bool isValid ($sUrl, $bIgnoreProtocolAbsence = false)
//   static CUStringObject normalize ($sUrl, $bKeepFragmentId)
//   static CUStringObject makeUrlString ($sHost, $sProtocol = self::DEFAULT_PROTOCOL, $xPath = null, $xQuery = null,
//     $sFragmentId = null, $iPort = null, $sUser = null, $sPassword = null)
//   static CUStringObject enterTdNew ($sString)
//   static CUStringObject leaveTdNew ($sString)
//   static CUStringObject enterTdOld ($sString)
//   static CUStringObject leaveTdOld ($sString)

class CUrl extends CRootClass implements IEquality
{
    /**
     * `string` "http" The default protocol used in the parsing and composing of URLs whenever needed.
     *
     * @var string
     */
    const DEFAULT_PROTOCOL = "http";

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a parsed URL from a URL string.
     *
     * The URL string is expected to be by all means valid, with characters being percent-encoded where it is required
     * by the URL standard and without any leading or trailing whitespace. It is only when the URL string does not
     * indicate any protocol that the URL may still be considered valid and the default protocol is assigned to the
     * URL, which is "http".
     *
     * @param  string $sUrl The URL string.
     */

    public function __construct ($sUrl)
    {
        assert( 'is_cstring($sUrl)', vs(isset($this), get_defined_vars()) );
        assert( 'self::isValid($sUrl, true)', vs(isset($this), get_defined_vars()) );

        $this->m_sUrl = $sUrl;

        $mParsedUrl = parse_url($sUrl);
        assert( 'is_cmap($mParsedUrl)', vs(isset($this), get_defined_vars()) );  // should not rise for a valid URL

        // Protocol (scheme).
        if ( CMap::hasKey($mParsedUrl, "scheme") )
        {
            $this->m_bHasProtocol = true;
            $this->m_sProtocol = $mParsedUrl["scheme"];

            // Normalize by converting to lowercase.
            $this->m_sNormProtocol = CString::toLowerCase($this->m_sProtocol);
        }
        else
        {
            $this->m_bHasProtocol = false;
            $this->m_sNormProtocol = self::DEFAULT_PROTOCOL;

            if ( !CMap::hasKey($mParsedUrl, "host") )
            {
                // Most likely, `parse_url` function has not parsed the host because the protocol (scheme) is absent
                // and there are no "//" in the front, so try parsing the host with the default protocol in the URL.
                $mParsedUrl = parse_url(self::ensureProtocol($sUrl));
                assert( 'is_cmap($mParsedUrl)', vs(isset($this), get_defined_vars()) );
                CMap::remove($mParsedUrl, "scheme");
            }
        }

        // Host (domain).
        $this->m_bHostIsInBrackets = false;
        if ( CMap::hasKey($mParsedUrl, "host") )
        {
            $this->m_sHost = $mParsedUrl["host"];

            if ( CRegex::find($this->m_sHost, "/^\\[.*\\]\\z/") )
            {
                // Most likely, an IPv6 enclosed in "[]".
                $this->m_bHostIsInBrackets = true;
                $this->m_sHost = CString::substr($this->m_sHost, 1, CString::length($this->m_sHost) - 2);
            }

            // Normalize by converting to lowercase.
            $this->m_sNormHost = CString::toLowerCase($this->m_sHost);
        }
        else
        {
            // Same as invalid.
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }

        // Port.
        if ( CMap::hasKey($mParsedUrl, "port") )
        {
            $this->m_bHasPort = true;
            $this->m_iPort = $mParsedUrl["port"];

            // Should be `int`, but look into the type just in case.
            if ( is_cstring($this->m_iPort) )
            {
                $this->m_iPort = CString::toInt($this->m_iPort);
            }
        }
        else
        {
            $this->m_bHasPort = false;
        }

        // Path.
        if ( CMap::hasKey($mParsedUrl, "path") )
        {
            $this->m_bHasPath = true;
            $this->m_sPath = $mParsedUrl["path"];

            // Normalize by replacing percent-encoded bytes of unreserved characters with their literal equivalents and
            // ensuring that all percent-encoded parts are in uppercase.
            $sPathDelimitersReEsc = CRegex::enterTd(self::$ms_sDelimiters);
            $this->m_sNormPath = CRegex::replaceWithCallback($this->m_sPath, "/[^$sPathDelimitersReEsc]+/",
                function ($mMatches)
                {
                    return CUrl::enterTdNew(CUrl::leaveTdNew($mMatches[0]));
                });
        }
        else
        {
            $this->m_bHasPath = false;
            $this->m_sNormPath = "/";
        }
        $this->m_oUrlPath = new CUrlPath($this->m_sNormPath);

        // Query string.
        $this->m_bHasQuery = false;
        if ( CMap::hasKey($mParsedUrl, "query") )
        {
            $this->m_bHasQuery = true;
            $this->m_sQueryString = $mParsedUrl["query"];

            $bParsingWasFruitful;
            $this->m_oUrlQuery = new CUrlQuery($this->m_sQueryString, $bParsingWasFruitful);
            if ( $bParsingWasFruitful )
            {
                $this->m_bHasQuery = true;
                $this->m_sNormQueryString = $this->m_oUrlQuery->queryString(true);
            }
        }

        // Fragment ID.
        if ( CMap::hasKey($mParsedUrl, "fragment") )
        {
            $this->m_bHasFragmentId = true;
            $this->m_sFragmentId = $mParsedUrl["fragment"];

            // Normalize by replacing percent-encoded bytes of unreserved characters with their literal equivalents and
            // ensuring that all percent-encoded parts are in uppercase.
            $sFiDelimitersReEsc = CRegex::enterTd(self::$ms_sDelimiters);
            $this->m_sNormFragmentId = CRegex::replaceWithCallback($this->m_sFragmentId, "/[^$sFiDelimitersReEsc]+/",
                function ($mMatches)
                {
                    // Use the newer flavor of percent-encoding.
                    return CUrl::enterTdNew(CUrl::leaveTdNew($mMatches[0]));
                });
        }
        else
        {
            $this->m_bHasFragmentId = false;
        }

        // User.
        if ( CMap::hasKey($mParsedUrl, "user") )
        {
            $this->m_bHasUser = true;
            $this->m_sUser = $mParsedUrl["user"];
        }
        else
        {
            $this->m_bHasUser = false;
        }

        // Password.
        if ( CMap::hasKey($mParsedUrl, "pass") )
        {
            $this->m_bHasPassword = true;
            $this->m_sPassword = $mParsedUrl["pass"];
        }
        else
        {
            $this->m_bHasPassword = false;
        }

        // Compose the normalized URL string.
        $this->m_sNormUrl = "";
        $this->m_sNormUrl .= $this->m_sNormProtocol . "://";
        if ( $this->m_bHasUser )
        {
            $this->m_sNormUrl .= $this->m_sUser;
        }
        if ( $this->m_bHasPassword )
        {
            $this->m_sNormUrl .= ":" . $this->m_sPassword;
        }
        if ( $this->m_bHasUser || $this->m_bHasPassword )
        {
            $this->m_sNormUrl .= "@";
        }
        if ( !$this->m_bHostIsInBrackets )
        {
            $this->m_sNormUrl .= $this->m_sNormHost;
        }
        else
        {
            $this->m_sNormUrl .= "[" . $this->m_sNormHost . "]";
        }
        if ( $this->m_bHasPort )
        {
            // Normalize by skipping port indication if the port is the default one for the protocol.
            if ( !(CMap::hasKey(self::$ms_mKnownProtocolToDefaultPort, $this->m_sNormProtocol) &&
                 self::$ms_mKnownProtocolToDefaultPort[$this->m_sNormProtocol] == $this->m_iPort) )
            {
                $this->m_sNormUrl .= ":" . $this->m_iPort;
            }
        }
        $this->m_sNormUrl .= $this->m_sNormPath;
        if ( $this->m_bHasQuery )
        {
            $this->m_sNormUrl .= "?" . $this->m_sNormQueryString;
        }

        $this->m_sNormUrlWithoutFragmentId = $this->m_sNormUrl;

        if ( $this->m_bHasFragmentId )
        {
            $this->m_sNormUrl .= "#" . $this->m_sNormFragmentId;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the original URL string from which a URL object was constructed.
     *
     * @return CUStringObject The original URL string.
     */

    public function url ()
    {
        return $this->m_sUrl;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a protocol.
     *
     * @return bool `true` if the URL specifies a protocol, `false` otherwise.
     */

    public function hasProtocol ()
    {
        return $this->m_bHasProtocol;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the protocol of a URL.
     *
     * This method returns the protocol as it was specified originally. For the normalized protocol, where all
     * characters are always in lowercase, you can call `normalizedProtocol` method.
     *
     * This method should not be called if `hasProtocol` method reports `false`.
     *
     * @return CUStringObject The URL's protocol.
     *
     * @link   #method_hasProtocol hasProtocol
     * @link   #method_normalizedProtocol normalizedProtocol
     */

    public function protocol ()
    {
        assert( '$this->hasProtocol()', vs(isset($this), get_defined_vars()) );
        return $this->m_sProtocol;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the normalized protocol of a URL.
     *
     * In a normalized protocol, all characters appear in lowercase.
     *
     * This method can be called even if `hasProtocol` method reports `false`, in which case it will return the default
     * protocol ("http").
     *
     * @return CUStringObject The URL's protocol after normalization.
     */

    public function normalizedProtocol ()
    {
        return $this->m_sNormProtocol;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the host part of a URL.
     *
     * The host part of a URL includes the domain as well as the subdomains, if any. For the normalized host, where all
     * characters are always in lowercase, you can call `normalizedHost` method.
     *
     * If the host is an IPv6, which usually appears enclosed in square brackets, the method returns just the IP
     * address, without any brackets.
     *
     * @return CUStringObject The URL's host.
     *
     * @link   #method_normalizedHost normalizedHost
     */

    public function host ()
    {
        return $this->m_sHost;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the normalized host part of a URL.
     *
     * The host part of a URL includes the domain as well as the subdomains, if any. In a normalized host, all
     * characters appear in lowercase.
     *
     * If the host is an IPv6, which usually appears enclosed in square brackets, the method returns just the IP
     * address, without any brackets.
     *
     * @return CUStringObject The URL's host after normalization.
     */

    public function normalizedHost ()
    {
        return $this->m_sNormHost;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a destination port.
     *
     * @return bool `true` if the URL specifies a destination port, `false` otherwise.
     */

    public function hasPort ()
    {
        return $this->m_bHasPort;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the number of the destination port of a URL.
     *
     * This method should not be called if `hasPort` method reports `false`.
     *
     * @return int The URL's destination port.
     *
     * @link   #method_hasPort hasPort
     */

    public function port ()
    {
        assert( '$this->hasPort()', vs(isset($this), get_defined_vars()) );
        return $this->m_iPort;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a path.
     *
     * @return bool `true` if the URL specifies a path, `false` otherwise.
     */

    public function hasPath ()
    {
        return $this->m_bHasPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path string of a URL.
     *
     * For the normalized path string, where only those characters are percent-encoded for which it is required by the
     * URL standard and where hexadecimal letters in percent-encoded characters are always uppercased, you can call
     * `normalizedPathString` method.
     *
     * This method should not be called if `hasPath` method reports `false`.
     *
     * @param  bool $bDecode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the path
     * string should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's path string.
     *
     * @link   #method_hasPath hasPath
     * @link   #method_normalizedPathString normalizedPathString
     */

    public function pathString ($bDecode = false)
    {
        assert( 'is_bool($bDecode)', vs(isset($this), get_defined_vars()) );
        assert( '$this->hasPath()', vs(isset($this), get_defined_vars()) );

        return ( !$bDecode ) ? $this->m_sPath : self::leaveTdNew($this->m_sPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the normalized path string of a URL.
     *
     * In a normalized path string, only those characters are percent-encoded for which it is required by the URL
     * standard and hexadecimal letters in percent-encoded characters appear in uppercase.
     *
     * This method can be called even if `hasPath` method reports `false`, in which case it will return "/".
     *
     * @param  bool $bDecode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the path
     * string should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's path string after normalization.
     */

    public function normalizedPathString ($bDecode = false)
    {
        assert( 'is_bool($bDecode)', vs(isset($this), get_defined_vars()) );
        return ( !$bDecode ) ? $this->m_sNormPath : self::leaveTdNew($this->m_sNormPath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path of a URL as a CUrlPath object.
     *
     * This method can be called even if `hasPath` method reports `false`, in which case the path is "/".
     *
     * @return CUrlPath The URL's path.
     */

    public function path ()
    {
        return clone $this->m_oUrlPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a query.
     *
     * @return bool `true` if the URL specifies a query, `false` otherwise.
     */

    public function hasQuery ()
    {
        return $this->m_bHasQuery;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the query string of a URL.
     *
     * For the normalized query string, where only those characters are percent-encoded for which it is required by the
     * URL standard and where hexadecimal letters in percent-encoded characters are always uppercased, you can call
     * `normalizedQueryString` method.
     *
     * This method should not be called if `hasQuery` method reports `false`.
     *
     * @param  bool $bDecode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the
     * query string should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's query string.
     *
     * @link   #method_hasQuery hasQuery
     * @link   #method_normalizedQueryString normalizedQueryString
     */

    public function queryString ($bDecode = false)
    {
        assert( '$this->hasQuery()', vs(isset($this), get_defined_vars()) );
        return ( !$bDecode ) ? $this->m_sQueryString : self::leaveTdOld($this->m_sQueryString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the normalized query string of a URL.
     *
     * In a normalized query string, only those characters are percent-encoded for which it is required by the URL
     * standard and hexadecimal letters in percent-encoded characters appear in uppercase. Also, no duplicate fields
     * are produced in the normalized query string and "=" is added after any field name that goes without a value and
     * is not followed by "=".
     *
     * This method should not be called if `hasQuery` method reports `false`.
     *
     * @param  bool $bDecode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the
     * query string should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's query string after normalization.
     *
     * @link   #method_hasQuery hasQuery
     */

    public function normalizedQueryString ($bDecode = false)
    {
        assert( '$this->hasQuery()', vs(isset($this), get_defined_vars()) );
        return ( !$bDecode ) ? $this->m_sNormQueryString : self::leaveTdOld($this->m_sNormQueryString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the query of a URL as a CUrlQuery object.
     *
     * This method should not be called if `hasQuery` method reports `false`.
     *
     * @return CUrlQuery The URL's query.
     *
     * @link   #method_hasQuery hasQuery
     */

    public function query ()
    {
        assert( '$this->hasQuery()', vs(isset($this), get_defined_vars()) );
        return clone $this->m_oUrlQuery;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a fragment ID.
     *
     * @return bool `true` if the URL specifies a fragment ID, `false` otherwise.
     */

    public function hasFragmentId ()
    {
        return $this->m_bHasFragmentId;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the fragment ID of a URL.
     *
     * For the normalized fragment ID, where only those characters are percent-encoded for which it is required by the
     * URL standard and where hexadecimal letters in percent-encoded characters are always uppercased, you can call
     * `normalizedFragmentId` method.
     *
     * This method should not be called if `hasFragmentId` method reports `false`.
     *
     * @param  bool $bDecode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the
     * fragment ID should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's fragment ID.
     *
     * @link   #method_hasFragmentId hasFragmentId
     * @link   #method_normalizedFragmentId normalizedFragmentId
     */

    public function fragmentId ($bDecode = false)
    {
        assert( 'is_bool($bDecode)', vs(isset($this), get_defined_vars()) );
        assert( '$this->hasFragmentId()', vs(isset($this), get_defined_vars()) );

        return ( !$bDecode ) ? $this->m_sFragmentId : self::leaveTdNew($this->m_sFragmentId);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the normalized fragment ID of a URL.
     *
     * In a normalized fragment ID, only those characters are percent-encoded for which it is required by the URL
     * standard and hexadecimal letters in percent-encoded characters appear in uppercase.
     *
     * This method should not be called if `hasFragmentId` method reports `false`.
     *
     * @param  bool $bDecode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the
     * fragment ID should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's fragment ID after normalization.
     *
     * @link   #method_hasFragmentId hasFragmentId
     */

    public function normalizedFragmentId ($bDecode = false)
    {
        assert( 'is_bool($bDecode)', vs(isset($this), get_defined_vars()) );
        assert( '$this->hasFragmentId()', vs(isset($this), get_defined_vars()) );

        return ( !$bDecode ) ? $this->m_sNormFragmentId : self::leaveTdNew($this->m_sNormFragmentId);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a user name.
     *
     * @return bool `true` if the URL specifies a user name, `false` otherwise.
     */

    public function hasUser ()
    {
        return $this->m_bHasUser;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the user name specified by a URL.
     *
     * This method should not be called if `hasUser` method reports `false`.
     *
     * @return CUStringObject The user name specified by the URL.
     *
     * @link   #method_hasUser hasUser
     */

    public function user ()
    {
        assert( '$this->hasUser()', vs(isset($this), get_defined_vars()) );
        return $this->m_sUser;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a password.
     *
     * @return bool `true` if the URL specifies a password, `false` otherwise.
     */

    public function hasPassword ()
    {
        return $this->m_bHasPassword;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the password specified by a URL.
     *
     * This method should not be called if `hasPassword` method reports `false`.
     *
     * @return CUStringObject The password specified by the URL.
     *
     * @link   #method_hasPassword hasPassword
     */

    public function password ()
    {
        assert( '$this->hasPassword()', vs(isset($this), get_defined_vars()) );
        return $this->m_sPassword;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the normalized URL string of a URL, without any fragment ID.
     *
     * Since fragment IDs in URLs are typically used to tell the browser the place to which the page needs to be
     * scrolled on the initial view, which is only a matter of presentation, it makes the comparison of URLs by their
     * normalized strings more consistent if fragment IDs are not taken into account.
     *
     * In a normalized URL string, the protocol is always indicated and always in lowercase, with "http" protocol being
     * the default one, the host part is always in lowercase too, a path is always present, with the default path being
     * "/", and the query string does not contain duplicate fields and "=" is added in the query string after any field
     * name that goes without a value and is not followed by "=". Also in a normalized URL string, only those
     * characters are percent-encoded for which it is required by the URL standard and hexadecimal letters in
     * percent-encoded characters appear in uppercase. For instance, "WWW.EXAMPLE.COM" is normalized to
     * "http://www.example.com/" and "HTTP: //www.example.com/path/to/some%3dite%6D" is normalized to
     * "http://www.example.com/path/to/some%3Ditem".
     *
     * @return CUStringObject The normalized URL string of the URL.
     */

    public function normalizedUrlWithoutFragmentId ()
    {
        return $this->m_sNormUrlWithoutFragmentId;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the normalized URL string of a URL, together with the fragment ID, if any.
     *
     * In a normalized URL string, the protocol is always indicated and always in lowercase, with "http" protocol being
     * the default one, the host part is always in lowercase too, a path is always present, with the default path being
     * "/", and the query string does not contain duplicate fields and "=" is added in the query string after any field
     * name that goes without a value and is not followed by "=". Also in a normalized URL string, only those
     * characters are percent-encoded for which it is required by the URL standard and hexadecimal letters in
     * percent-encoded characters appear in uppercase. For instance, "WWW.EXAMPLE.COM" is normalized to
     * "http://www.example.com/" and "HTTP: //www.example.com/path/to/some%3dite%6D" is normalized to
     * "http://www.example.com/path/to/some%3Ditem".
     *
     * @return CUStringObject The normalized URL string of the URL.
     */

    public function normalizedUrl ()
    {
        return $this->m_sNormUrl;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL is equal to another URL, comparing them by normalized URL strings.
     *
     * @param  CUrl $oToUrl The second URL for comparison.
     * @param  bool $bIgnoreFragmentId **OPTIONAL. Default is** `true`. Tells whether the fragment IDs of the URLs, if
     * any, should be ignored during the comparison.
     *
     * @return bool `true` if *this* URL is equal to the second URL, `false` otherwise.
     */

    public function equals ($oToUrl, $bIgnoreFragmentId = true)
    {
        assert( '$oToUrl instanceof CUrl && is_bool($bIgnoreFragmentId)', vs(isset($this), get_defined_vars()) );

        if ( $bIgnoreFragmentId )
        {
            return CString::equals($this->m_sNormUrlWithoutFragmentId, $oToUrl->m_sNormUrlWithoutFragmentId);
        }
        else
        {
            return CString::equals($this->m_sNormUrl, $oToUrl->m_sNormUrl);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Assigns the default protocol to a specified URL string if it does not indicate any or leaves the URL string
     * unchanged if it does, and returns the URL string.
     *
     * @param  string $sUrl The URL string.
     * @param  string $sDefaultProtocol **OPTIONAL. Default is** "http". The default protocol to be used if the URL
     * string doesn't indicate any.
     *
     * @return CUStringObject The processed URL string.
     */

    public static function ensureProtocol ($sUrl, $sDefaultProtocol = self::DEFAULT_PROTOCOL)
    {
        assert( 'is_cstring($sUrl) && is_cstring($sDefaultProtocol)', vs(isset($this), get_defined_vars()) );

        $mParsedUrl = parse_url($sUrl);
        if ( is_cmap($mParsedUrl) && !CMap::hasKey($mParsedUrl, "scheme") )
        {
            $sUrl = "$sDefaultProtocol://$sUrl";
        }
        return $sUrl;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the URL in a specified string is valid.
     *
     * @param  string $sUrl The URL string to be looked into.
     * @param  bool $bIgnoreProtocolAbsence **OPTIONAL. Default is** `false`. Tells whether the URL in the string may
     * still be considered valid even if it does not indicate any protocol.
     *
     * @return bool `true` if the URL in the string is valid, `false` otherwise.
     */

    public static function isValid ($sUrl, $bIgnoreProtocolAbsence = false)
    {
        assert( 'is_cstring($sUrl) && is_bool($bIgnoreProtocolAbsence)', vs(isset($this), get_defined_vars()) );

        $mParsedUrl = parse_url($sUrl);
        if ( !is_cmap($mParsedUrl) )
        {
            return false;
        }
        if ( $bIgnoreProtocolAbsence && !CMap::hasKey($mParsedUrl, "scheme") )
        {
            // No protocol seems to be specified, try with the default one.
            $sUrl = self::DEFAULT_PROTOCOL . "://$sUrl";
            $mParsedUrl = parse_url($sUrl);
            if ( !is_cmap($mParsedUrl) )
            {
                return false;
            }
            if ( !CMap::hasKey($mParsedUrl, "scheme") )
            {
                return false;
            }
        }
        if ( is_cstring(filter_var($sUrl, FILTER_VALIDATE_URL)) )
        {
            return true;
        }
        else if ( CMap::hasKey($mParsedUrl, "host") )
        {
            // The `filter_var` function could fail to recognize an IPv6 as the URL's host (enclosed in square
            // brackets), so, in case of a valid IPv6 being the host, replace it with an IPv4 and give the URL another
            // try.
            $sHost = $mParsedUrl["host"];
            if ( CRegex::find($sHost, "/^\\[.*\\]\\z/") )
            {
                $sHost = CString::substr($sHost, 1, CString::length($sHost) - 2);
                if ( CIp::isValidV6($sHost) )
                {
                    // Should not influence the validity if the string is present anywhere else.
                    $sUrl = CString::replace($sUrl, "[$sHost]", "127.0.0.1");
                    if ( is_cstring(filter_var($sUrl, FILTER_VALIDATE_URL)) && is_cmap(parse_url($sUrl)) )
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the URL in a specified string and returns the new string.
     *
     * In a normalized URL string, the protocol is always indicated and always in lowercase, with "http" protocol being
     * the default one, the host part is always in lowercase too, a path is always present, with the default path being
     * "/", and the query string does not contain duplicate fields and "=" is added in the query string after any field
     * name that goes without a value and is not followed by "=". Also in a normalized URL string, only those
     * characters are percent-encoded for which it is required by the URL standard and hexadecimal letters in
     * percent-encoded characters appear in uppercase. For instance, "WWW.EXAMPLE.COM" is normalized to
     * "http://www.example.com/" and "HTTP: //www.example.com/path/to/some%3dite%6D" is normalized to
     * "http://www.example.com/path/to/some%3Ditem".
     *
     * Since fragment IDs in URLs are typically used to tell the browser the place to which the page needs to be
     * scrolled on the initial view, which is only a matter of presentation, it makes the comparison of URLs by their
     * normalized strings more consistent if fragment IDs are not taken into account.
     *
     * @param  string $sUrl The string with the URL to be normalized.
     * @param  bool $bKeepFragmentId Tells whether the fragment ID, if the URL contains any, should be normalized too
     * and put into the normalized URL.
     *
     * @return CUStringObject A string with the normalized URL.
     */

    public static function normalize ($sUrl, $bKeepFragmentId)
    {
        assert( 'is_cstring($sUrl) && is_bool($bKeepFragmentId)', vs(isset($this), get_defined_vars()) );

        $oUrl = new self($sUrl);
        return ( !$bKeepFragmentId ) ? $oUrl->normalizedUrlWithoutFragmentId() : $oUrl->normalizedUrl();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Composes a URL string from specified URL parts and returns it.
     *
     * If the URL string needs to be normalized after having been composed, you can use `normalize` static method for
     * that.
     *
     * @param  string $sHost The host part. This includes the domain and the subdomains, if any.
     * @param  string $sProtocol **OPTIONAL. Default is** "http". The protocol.
     * @param  string|CUrlPath $xPath **OPTIONAL. Default is** "/". The path. Should start with "/".
     * @param  string|CUrlQuery $xQuery **OPTIONAL. Default is** *none*. The query.
     * @param  string $sFragmentId **OPTIONAL. Default is** *none*. The fragment ID.
     * @param  int $iPort **OPTIONAL. Default is** *none*. The number of the destination port.
     * @param  string $sUser **OPTIONAL. Default is** *none*. The user name.
     * @param  string $sPassword **OPTIONAL. Default is** *none*. The password.
     *
     * @return CUStringObject The composed URL string.
     *
     * @link   #method_normalize normalize
     */

    public static function makeUrlString ($sHost, $sProtocol = self::DEFAULT_PROTOCOL, $xPath = null, $xQuery = null,
        $sFragmentId = null, $iPort = null, $sUser = null, $sPassword = null)
    {
        assert( 'is_cstring($sHost) && is_cstring($sProtocol) && ' .
                '(!isset($xPath) || is_cstring($xPath) || $xPath instanceof CUrlPath) && ' .
                '(!isset($xQuery) || is_cstring($xQuery) || $xQuery instanceof CUrlQuery) && ' .
                '(!isset($sFragmentId) || is_cstring($sFragmentId)) && (!isset($iPort) || is_int($iPort)) && ' .
                '(!isset($sUser) || is_cstring($sUser)) && (!isset($sPassword) || is_cstring($sPassword))',
            vs(isset($this), get_defined_vars()) );

        $sUrl = "";

        // Protocol.
        $sUrl .= "$sProtocol://";

        // User and password.
        if ( isset($sUser) )
        {
            $sUrl .= $sUser;
        }
        if ( isset($sPassword) )
        {
            $sUrl .= ":$sPassword";
        }
        if ( isset($sUser) || isset($sPassword) )
        {
            $sUrl .= "@";
        }

        // Host.
        if ( !CIp::isValidV6($sHost) )
        {
            $sUrl .= $sHost;
        }
        else
        {
            $sUrl .= "[$sHost]";
        }

        // Port.
        if ( isset($iPort) )
        {
            $sUrl .= ":$iPort";
        }

        // Path.
        if ( !isset($xPath) )
        {
            $sUrl .= "/";
        }
        else if ( is_cstring($xPath) )
        {
            assert( 'CString::startsWith($xPath, "/")', vs(isset($this), get_defined_vars()) );
            $sUrl .= $xPath;
        }
        else  // a CUrlPath
        {
            $sUrl .= $xPath->pathString();
        }

        // Query string.
        if ( isset($xQuery) )
        {
            $sQueryString;
            if ( is_cstring($xQuery) )
            {
                $sQueryString = $xQuery;
            }
            else  // a CUrlQuery
            {
                $sQueryString = $xQuery->queryString(false);
            }
            $sUrl .= "?$sQueryString";
        }

        // Fragment ID.
        if ( isset($sFragmentId) )
        {
            $sUrl .= "#$sFragmentId";
        }

        return $sUrl;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, applies percent-encoding (URL encoding) to all characters that would be invalid in the URL text
     * domain with their literal representations according to the newer flavor of percent-encoding and returns the new
     * string.
     *
     * According to the newer flavor of percent-encoding, the space character is encoded as "%20", "+" as "%2B", and
     * "~" is not encoded.
     *
     * @param  string $sString The string to be translated into the URL text domain.
     *
     * @return CUStringObject The translated string.
     */

    public static function enterTdNew ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return rawurlencode($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, removes percent-encoding (URL encoding) from all characters encoded according to the newer flavor
     * of percent-encoding and returns the new string.
     *
     * According to the newer flavor of percent-encoding, the space character is decoded from "%20" only (literal
     * spaces are not permitted in a valid URL), "+" from either "%2B" or "+", and "~" from either "~" or "%7E".
     *
     * @param  string $sString The string to be translated out of the URL text domain.
     *
     * @return CUStringObject The translated string.
     */

    public static function leaveTdNew ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return rawurldecode($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, applies percent-encoding (URL encoding) to all characters that would be invalid in the URL text
     * domain with their literal representations according to the older flavor of percent-encoding and returns the new
     * string.
     *
     * According to the older flavor of percent-encoding, the space character is encoded as "+", "+" as "%2B", and "~"
     * as "%7E". This flavor of percent-encoding is primarily used with query strings in URLs and with
     * "application/x-www-form-urlencoded" data in POST requests. In any other place, the newer flavor of
     * percent-encoding is usually preferred.
     *
     * @param  string $sString The string to be translated into the URL text domain.
     *
     * @return CUStringObject The translated string.
     */

    public static function enterTdOld ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return urlencode($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, removes percent-encoding (URL encoding) from all characters encoded according to the older flavor
     * of percent-encoding and returns the new string.
     *
     * According to the older flavor of percent-encoding, the space character is decoded from either "+" or "%20"
     * (literal spaces are not permitted in a valid URL), "+" from "%2B" only, and "~" from either "%7E" or "~". This
     * flavor of percent-encoding is primarily used with query strings in URLs and with
     * "application/x-www-form-urlencoded" data in POST requests. In any other place, the newer flavor of
     * percent-encoding is usually preferred.
     *
     * @param  string $sString The string to be translated out of the URL text domain.
     *
     * @return CUStringObject The translated string.
     */

    public static function leaveTdOld ($sString)
    {
        assert( 'is_cstring($sString)', vs(isset($this), get_defined_vars()) );
        return urldecode($sString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_sUrl;
    protected $m_bHasProtocol;
    protected $m_sProtocol;
    protected $m_sNormProtocol;
    protected $m_bHostIsInBrackets;
    protected $m_sHost;
    protected $m_sNormHost;
    protected $m_bHasPort;
    protected $m_iPort;
    protected $m_bHasPath;
    protected $m_sPath;
    protected $m_sNormPath;
    protected $m_oUrlPath;
    protected $m_bHasQuery;
    protected $m_sQueryString;
    protected $m_sNormQueryString;
    protected $m_oUrlQuery;
    protected $m_bHasFragmentId;
    protected $m_sFragmentId;
    protected $m_sNormFragmentId;
    protected $m_bHasUser;
    protected $m_sUser;
    protected $m_bHasPassword;
    protected $m_sPassword;
    protected $m_sNormUrlWithoutFragmentId;
    protected $m_sNormUrl;

    // Used by port normalization.
    protected static $ms_mKnownProtocolToDefaultPort = [
        "http" => 80,
        "https" => 443,
        "ftp" => 21];

    // The reserved characters that are commonly used as delimiters in URLs.
    // "gen-delims": :/?#[]@
    // "sub-delims": !$&'()*+,;=
    // Reference:
    // http://www.apps.ietf.org/rfc/rfc3986.html#sec-2.2
    protected static $ms_sDelimiters = ":/?#[]@!$&'()*+,;=";
}
