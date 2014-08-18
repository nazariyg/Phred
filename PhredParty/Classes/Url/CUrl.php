<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   __construct ($url)
//   CUStringObject url ()
//   bool hasProtocol ()
//   CUStringObject protocol ()
//   CUStringObject normalizedProtocol ()
//   CUStringObject host ()
//   CUStringObject normalizedHost ()
//   bool hasPort ()
//   int port ()
//   bool hasPath ()
//   CUStringObject pathString ($decode = false)
//   CUStringObject normalizedPathString ($decode = false)
//   CUrlPath path ()
//   bool hasQuery ()
//   CUStringObject queryString ($decode = false)
//   CUStringObject normalizedQueryString ($decode = false)
//   CUrlQuery query ()
//   bool hasFragmentId ()
//   CUStringObject fragmentId ($decode = false)
//   CUStringObject normalizedFragmentId ($decode = false)
//   bool hasUser ()
//   CUStringObject user ()
//   bool hasPassword ()
//   CUStringObject password ()
//   CUStringObject normalizedUrlWithoutFragmentId ()
//   CUStringObject normalizedUrl ()
//   bool equals ($toUrl, $ignoreFragmentId = true)
//   static CUStringObject ensureProtocol ($url, $defaultProtocol = self::DEFAULT_PROTOCOL)
//   static bool isValid ($url, $ignoreProtocolAbsence = false)
//   static CUStringObject normalize ($url, $keepFragmentId)
//   static CUStringObject makeUrlString ($host, $protocol = self::DEFAULT_PROTOCOL, $path = null, $query = null,
//     $fragmentId = null, $port = null, $user = null, $password = null)
//   static CUStringObject enterTdNew ($string)
//   static CUStringObject leaveTdNew ($string)
//   static CUStringObject enterTdOld ($string)
//   static CUStringObject leaveTdOld ($string)

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
     * @param  string $url The URL string.
     */

    public function __construct ($url)
    {
        assert( 'is_cstring($url)', vs(isset($this), get_defined_vars()) );
        assert( 'self::isValid($url, true)', vs(isset($this), get_defined_vars()) );

        $this->m_url = $url;

        $parsedUrl = parse_url($url);
        assert( 'is_cmap($parsedUrl)', vs(isset($this), get_defined_vars()) );  // should not rise for a valid URL

        // Protocol (scheme).
        if ( CMap::hasKey($parsedUrl, "scheme") )
        {
            $this->m_hasProtocol = true;
            $this->m_protocol = $parsedUrl["scheme"];

            // Normalize by converting to lowercase.
            $this->m_normProtocol = CString::toLowerCase($this->m_protocol);
        }
        else
        {
            $this->m_hasProtocol = false;
            $this->m_normProtocol = self::DEFAULT_PROTOCOL;

            if ( !CMap::hasKey($parsedUrl, "host") )
            {
                // Most likely, `parse_url` function has not parsed the host because the protocol (scheme) is absent
                // and there are no "//" in the front, so try parsing the host with the default protocol in the URL.
                $parsedUrl = parse_url(self::ensureProtocol($url));
                assert( 'is_cmap($parsedUrl)', vs(isset($this), get_defined_vars()) );
                CMap::remove($parsedUrl, "scheme");
            }
        }

        // Host (domain).
        $this->m_hostIsInBrackets = false;
        if ( CMap::hasKey($parsedUrl, "host") )
        {
            $this->m_host = $parsedUrl["host"];

            if ( CRegex::find($this->m_host, "/^\\[.*\\]\\z/") )
            {
                // Most likely, an IPv6 enclosed in "[]".
                $this->m_hostIsInBrackets = true;
                $this->m_host = CString::substr($this->m_host, 1, CString::length($this->m_host) - 2);
            }

            // Normalize by converting to lowercase.
            $this->m_normHost = CString::toLowerCase($this->m_host);
        }
        else
        {
            // Same as invalid.
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }

        // Port.
        if ( CMap::hasKey($parsedUrl, "port") )
        {
            $this->m_hasPort = true;
            $this->m_port = $parsedUrl["port"];

            // Should be `int`, but look into the type just in case.
            if ( is_cstring($this->m_port) )
            {
                $this->m_port = CString::toInt($this->m_port);
            }
        }
        else
        {
            $this->m_hasPort = false;
        }

        // Path.
        if ( CMap::hasKey($parsedUrl, "path") )
        {
            $this->m_hasPath = true;
            $this->m_path = $parsedUrl["path"];

            // Normalize by replacing percent-encoded bytes of unreserved characters with their literal equivalents and
            // ensuring that all percent-encoded parts are in uppercase.
            $pathDelimitersReEsc = CRegex::enterTd(self::$ms_delimiters);
            $this->m_normPath = CRegex::replaceWithCallback($this->m_path, "/[^$pathDelimitersReEsc]+/",
                function ($matches)
                {
                    return CUrl::enterTdNew(CUrl::leaveTdNew($matches[0]));
                });
        }
        else
        {
            $this->m_hasPath = false;
            $this->m_normPath = "/";
        }
        $this->m_urlPath = new CUrlPath($this->m_normPath);

        // Query string.
        $this->m_hasQuery = false;
        if ( CMap::hasKey($parsedUrl, "query") )
        {
            $this->m_hasQuery = true;
            $this->m_queryString = $parsedUrl["query"];

            $parsingWasFruitful;
            $this->m_urlQuery = new CUrlQuery($this->m_queryString, $parsingWasFruitful);
            if ( $parsingWasFruitful )
            {
                $this->m_hasQuery = true;
                $this->m_normQueryString = $this->m_urlQuery->queryString(true);
            }
        }

        // Fragment ID.
        if ( CMap::hasKey($parsedUrl, "fragment") )
        {
            $this->m_hasFragmentId = true;
            $this->m_fragmentId = $parsedUrl["fragment"];

            // Normalize by replacing percent-encoded bytes of unreserved characters with their literal equivalents and
            // ensuring that all percent-encoded parts are in uppercase.
            $fiDelimitersReEsc = CRegex::enterTd(self::$ms_delimiters);
            $this->m_normFragmentId = CRegex::replaceWithCallback($this->m_fragmentId, "/[^$fiDelimitersReEsc]+/",
                function ($matches)
                {
                    // Use the newer flavor of percent-encoding.
                    return CUrl::enterTdNew(CUrl::leaveTdNew($matches[0]));
                });
        }
        else
        {
            $this->m_hasFragmentId = false;
        }

        // User.
        if ( CMap::hasKey($parsedUrl, "user") )
        {
            $this->m_hasUser = true;
            $this->m_user = $parsedUrl["user"];
        }
        else
        {
            $this->m_hasUser = false;
        }

        // Password.
        if ( CMap::hasKey($parsedUrl, "pass") )
        {
            $this->m_hasPassword = true;
            $this->m_password = $parsedUrl["pass"];
        }
        else
        {
            $this->m_hasPassword = false;
        }

        // Compose the normalized URL string.
        $this->m_normUrl = "";
        $this->m_normUrl .= $this->m_normProtocol . "://";
        if ( $this->m_hasUser )
        {
            $this->m_normUrl .= $this->m_user;
        }
        if ( $this->m_hasPassword )
        {
            $this->m_normUrl .= ":" . $this->m_password;
        }
        if ( $this->m_hasUser || $this->m_hasPassword )
        {
            $this->m_normUrl .= "@";
        }
        if ( !$this->m_hostIsInBrackets )
        {
            $this->m_normUrl .= $this->m_normHost;
        }
        else
        {
            $this->m_normUrl .= "[" . $this->m_normHost . "]";
        }
        if ( $this->m_hasPort )
        {
            // Normalize by skipping port indication if the port is the default one for the protocol.
            if ( !(CMap::hasKey(self::$ms_knownProtocolToDefaultPort, $this->m_normProtocol) &&
                 self::$ms_knownProtocolToDefaultPort[$this->m_normProtocol] == $this->m_port) )
            {
                $this->m_normUrl .= ":" . $this->m_port;
            }
        }
        $this->m_normUrl .= $this->m_normPath;
        if ( $this->m_hasQuery )
        {
            $this->m_normUrl .= "?" . $this->m_normQueryString;
        }

        $this->m_normUrlWithoutFragmentId = $this->m_normUrl;

        if ( $this->m_hasFragmentId )
        {
            $this->m_normUrl .= "#" . $this->m_normFragmentId;
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
        return $this->m_url;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a protocol.
     *
     * @return bool `true` if the URL specifies a protocol, `false` otherwise.
     */

    public function hasProtocol ()
    {
        return $this->m_hasProtocol;
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
        return $this->m_protocol;
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
        return $this->m_normProtocol;
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
        return $this->m_host;
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
        return $this->m_normHost;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a destination port.
     *
     * @return bool `true` if the URL specifies a destination port, `false` otherwise.
     */

    public function hasPort ()
    {
        return $this->m_hasPort;
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
        return $this->m_port;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a path.
     *
     * @return bool `true` if the URL specifies a path, `false` otherwise.
     */

    public function hasPath ()
    {
        return $this->m_hasPath;
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
     * @param  bool $decode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the path
     * string should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's path string.
     *
     * @link   #method_hasPath hasPath
     * @link   #method_normalizedPathString normalizedPathString
     */

    public function pathString ($decode = false)
    {
        assert( 'is_bool($decode)', vs(isset($this), get_defined_vars()) );
        assert( '$this->hasPath()', vs(isset($this), get_defined_vars()) );

        return ( !$decode ) ? $this->m_path : self::leaveTdNew($this->m_path);
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
     * @param  bool $decode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the path
     * string should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's path string after normalization.
     */

    public function normalizedPathString ($decode = false)
    {
        assert( 'is_bool($decode)', vs(isset($this), get_defined_vars()) );
        return ( !$decode ) ? $this->m_normPath : self::leaveTdNew($this->m_normPath);
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
        return clone $this->m_urlPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a query.
     *
     * @return bool `true` if the URL specifies a query, `false` otherwise.
     */

    public function hasQuery ()
    {
        return $this->m_hasQuery;
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
     * @param  bool $decode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the
     * query string should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's query string.
     *
     * @link   #method_hasQuery hasQuery
     * @link   #method_normalizedQueryString normalizedQueryString
     */

    public function queryString ($decode = false)
    {
        assert( '$this->hasQuery()', vs(isset($this), get_defined_vars()) );
        return ( !$decode ) ? $this->m_queryString : self::leaveTdOld($this->m_queryString);
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
     * @param  bool $decode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the
     * query string should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's query string after normalization.
     *
     * @link   #method_hasQuery hasQuery
     */

    public function normalizedQueryString ($decode = false)
    {
        assert( '$this->hasQuery()', vs(isset($this), get_defined_vars()) );
        return ( !$decode ) ? $this->m_normQueryString : self::leaveTdOld($this->m_normQueryString);
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
        return clone $this->m_urlQuery;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a fragment ID.
     *
     * @return bool `true` if the URL specifies a fragment ID, `false` otherwise.
     */

    public function hasFragmentId ()
    {
        return $this->m_hasFragmentId;
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
     * @param  bool $decode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the
     * fragment ID should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's fragment ID.
     *
     * @link   #method_hasFragmentId hasFragmentId
     * @link   #method_normalizedFragmentId normalizedFragmentId
     */

    public function fragmentId ($decode = false)
    {
        assert( 'is_bool($decode)', vs(isset($this), get_defined_vars()) );
        assert( '$this->hasFragmentId()', vs(isset($this), get_defined_vars()) );

        return ( !$decode ) ? $this->m_fragmentId : self::leaveTdNew($this->m_fragmentId);
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
     * @param  bool $decode **OPTIONAL. Default is** `false`. Tells whether all percent-encoded characters in the
     * fragment ID should be decoded into their literal representations.
     *
     * @return CUStringObject The URL's fragment ID after normalization.
     *
     * @link   #method_hasFragmentId hasFragmentId
     */

    public function normalizedFragmentId ($decode = false)
    {
        assert( 'is_bool($decode)', vs(isset($this), get_defined_vars()) );
        assert( '$this->hasFragmentId()', vs(isset($this), get_defined_vars()) );

        return ( !$decode ) ? $this->m_normFragmentId : self::leaveTdNew($this->m_normFragmentId);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a user name.
     *
     * @return bool `true` if the URL specifies a user name, `false` otherwise.
     */

    public function hasUser ()
    {
        return $this->m_hasUser;
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
        return $this->m_user;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL specifies a password.
     *
     * @return bool `true` if the URL specifies a password, `false` otherwise.
     */

    public function hasPassword ()
    {
        return $this->m_hasPassword;
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
        return $this->m_password;
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
        return $this->m_normUrlWithoutFragmentId;
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
        return $this->m_normUrl;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a URL is equal to another URL, comparing them by normalized URL strings.
     *
     * @param  CUrl $toUrl The second URL for comparison.
     * @param  bool $ignoreFragmentId **OPTIONAL. Default is** `true`. Tells whether the fragment IDs of the URLs, if
     * any, should be ignored during the comparison.
     *
     * @return bool `true` if *this* URL is equal to the second URL, `false` otherwise.
     */

    public function equals ($toUrl, $ignoreFragmentId = true)
    {
        assert( '$toUrl instanceof CUrl && is_bool($ignoreFragmentId)', vs(isset($this), get_defined_vars()) );

        if ( $ignoreFragmentId )
        {
            return CString::equals($this->m_normUrlWithoutFragmentId, $toUrl->m_normUrlWithoutFragmentId);
        }
        else
        {
            return CString::equals($this->m_normUrl, $toUrl->m_normUrl);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Assigns the default protocol to a specified URL string if it does not indicate any or leaves the URL string
     * unchanged if it does, and returns the URL string.
     *
     * @param  string $url The URL string.
     * @param  string $defaultProtocol **OPTIONAL. Default is** "http". The default protocol to be used if the URL
     * string doesn't indicate any.
     *
     * @return CUStringObject The processed URL string.
     */

    public static function ensureProtocol ($url, $defaultProtocol = self::DEFAULT_PROTOCOL)
    {
        assert( 'is_cstring($url) && is_cstring($defaultProtocol)', vs(isset($this), get_defined_vars()) );

        $parsedUrl = parse_url($url);
        if ( is_cmap($parsedUrl) && !CMap::hasKey($parsedUrl, "scheme") )
        {
            $url = "$defaultProtocol://$url";
        }
        return $url;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the URL in a specified string is valid.
     *
     * @param  string $url The URL string to be looked into.
     * @param  bool $ignoreProtocolAbsence **OPTIONAL. Default is** `false`. Tells whether the URL in the string may
     * still be considered valid even if it does not indicate any protocol.
     *
     * @return bool `true` if the URL in the string is valid, `false` otherwise.
     */

    public static function isValid ($url, $ignoreProtocolAbsence = false)
    {
        assert( 'is_cstring($url) && is_bool($ignoreProtocolAbsence)', vs(isset($this), get_defined_vars()) );

        $parsedUrl = parse_url($url);
        if ( !is_cmap($parsedUrl) )
        {
            return false;
        }
        if ( $ignoreProtocolAbsence && !CMap::hasKey($parsedUrl, "scheme") )
        {
            // No protocol seems to be specified, try with the default one.
            $url = self::DEFAULT_PROTOCOL . "://$url";
            $parsedUrl = parse_url($url);
            if ( !is_cmap($parsedUrl) )
            {
                return false;
            }
            if ( !CMap::hasKey($parsedUrl, "scheme") )
            {
                return false;
            }
        }
        if ( is_cstring(filter_var($url, FILTER_VALIDATE_URL)) )
        {
            return true;
        }
        else if ( CMap::hasKey($parsedUrl, "host") )
        {
            // The `filter_var` function could fail to recognize an IPv6 as the URL's host (enclosed in square
            // brackets), so, in case of a valid IPv6 being the host, replace it with an IPv4 and give the URL another
            // try.
            $host = $parsedUrl["host"];
            if ( CRegex::find($host, "/^\\[.*\\]\\z/") )
            {
                $host = CString::substr($host, 1, CString::length($host) - 2);
                if ( CIp::isValidV6($host) )
                {
                    // Should not influence the validity if the string is present anywhere else.
                    $url = CString::replace($url, "[$host]", "127.0.0.1");
                    if ( is_cstring(filter_var($url, FILTER_VALIDATE_URL)) && is_cmap(parse_url($url)) )
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
     * @param  string $url The string with the URL to be normalized.
     * @param  bool $keepFragmentId Tells whether the fragment ID, if the URL contains any, should be normalized too
     * and put into the normalized URL.
     *
     * @return CUStringObject A string with the normalized URL.
     */

    public static function normalize ($url, $keepFragmentId)
    {
        assert( 'is_cstring($url) && is_bool($keepFragmentId)', vs(isset($this), get_defined_vars()) );

        $objUrl = new self($url);
        return ( !$keepFragmentId ) ? $objUrl->normalizedUrlWithoutFragmentId() : $objUrl->normalizedUrl();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Composes a URL string from specified URL parts and returns it.
     *
     * If the URL string needs to be normalized after having been composed, you can use `normalize` static method for
     * that.
     *
     * @param  string $host The host part. This includes the domain and the subdomains, if any.
     * @param  string $protocol **OPTIONAL. Default is** "http". The protocol.
     * @param  string|CUrlPath $path **OPTIONAL. Default is** "/". The path. Should start with "/".
     * @param  string|CUrlQuery $query **OPTIONAL. Default is** *none*. The query.
     * @param  string $fragmentId **OPTIONAL. Default is** *none*. The fragment ID.
     * @param  int $port **OPTIONAL. Default is** *none*. The number of the destination port.
     * @param  string $user **OPTIONAL. Default is** *none*. The user name.
     * @param  string $password **OPTIONAL. Default is** *none*. The password.
     *
     * @return CUStringObject The composed URL string.
     *
     * @link   #method_normalize normalize
     */

    public static function makeUrlString ($host, $protocol = self::DEFAULT_PROTOCOL, $path = null, $query = null,
        $fragmentId = null, $port = null, $user = null, $password = null)
    {
        assert( 'is_cstring($host) && is_cstring($protocol) && ' .
                '(!isset($path) || is_cstring($path) || $path instanceof CUrlPath) && ' .
                '(!isset($query) || is_cstring($query) || $query instanceof CUrlQuery) && ' .
                '(!isset($fragmentId) || is_cstring($fragmentId)) && (!isset($port) || is_int($port)) && ' .
                '(!isset($user) || is_cstring($user)) && (!isset($password) || is_cstring($password))',
            vs(isset($this), get_defined_vars()) );

        $url = "";

        // Protocol.
        $url .= "$protocol://";

        // User and password.
        if ( isset($user) )
        {
            $url .= $user;
        }
        if ( isset($password) )
        {
            $url .= ":$password";
        }
        if ( isset($user) || isset($password) )
        {
            $url .= "@";
        }

        // Host.
        if ( !CIp::isValidV6($host) )
        {
            $url .= $host;
        }
        else
        {
            $url .= "[$host]";
        }

        // Port.
        if ( isset($port) )
        {
            $url .= ":$port";
        }

        // Path.
        if ( !isset($path) )
        {
            $url .= "/";
        }
        else if ( is_cstring($path) )
        {
            assert( 'CString::startsWith($path, "/")', vs(isset($this), get_defined_vars()) );
            $url .= $path;
        }
        else  // a CUrlPath
        {
            $url .= $path->pathString();
        }

        // Query string.
        if ( isset($query) )
        {
            $queryString;
            if ( is_cstring($query) )
            {
                $queryString = $query;
            }
            else  // a CUrlQuery
            {
                $queryString = $query->queryString(false);
            }
            $url .= "?$queryString";
        }

        // Fragment ID.
        if ( isset($fragmentId) )
        {
            $url .= "#$fragmentId";
        }

        return $url;
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
     * @param  string $string The string to be translated into the URL text domain.
     *
     * @return CUStringObject The translated string.
     */

    public static function enterTdNew ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return rawurlencode($string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, removes percent-encoding (URL encoding) from all characters encoded according to the newer flavor
     * of percent-encoding and returns the new string.
     *
     * According to the newer flavor of percent-encoding, the space character is decoded from "%20" only (literal
     * spaces are not permitted in a valid URL), "+" from either "%2B" or "+", and "~" from either "~" or "%7E".
     *
     * @param  string $string The string to be translated out of the URL text domain.
     *
     * @return CUStringObject The translated string.
     */

    public static function leaveTdNew ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return rawurldecode($string);
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
     * @param  string $string The string to be translated into the URL text domain.
     *
     * @return CUStringObject The translated string.
     */

    public static function enterTdOld ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return urlencode($string);
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
     * @param  string $string The string to be translated out of the URL text domain.
     *
     * @return CUStringObject The translated string.
     */

    public static function leaveTdOld ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return urldecode($string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_url;
    protected $m_hasProtocol;
    protected $m_protocol;
    protected $m_normProtocol;
    protected $m_hostIsInBrackets;
    protected $m_host;
    protected $m_normHost;
    protected $m_hasPort;
    protected $m_port;
    protected $m_hasPath;
    protected $m_path;
    protected $m_normPath;
    protected $m_urlPath;
    protected $m_hasQuery;
    protected $m_queryString;
    protected $m_normQueryString;
    protected $m_urlQuery;
    protected $m_hasFragmentId;
    protected $m_fragmentId;
    protected $m_normFragmentId;
    protected $m_hasUser;
    protected $m_user;
    protected $m_hasPassword;
    protected $m_password;
    protected $m_normUrlWithoutFragmentId;
    protected $m_normUrl;

    // Used by port normalization.
    protected static $ms_knownProtocolToDefaultPort = [
        "http" => 80,
        "https" => 443,
        "ftp" => 21];

    // The reserved characters that are commonly used as delimiters in URLs.
    // "gen-delims": :/?#[]@
    // "sub-delims": !$&'()*+,;=
    // Reference:
    // http://www.apps.ietf.org/rfc/rfc3986.html#sec-2.2
    protected static $ms_delimiters = ":/?#[]@!$&'()*+,;=";
}
