<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that represents the received HTTP request and that lets you retrieve the values of HTTP headers sent with
 * the request, the values sent in the query string of the requested URL, the values sent with POST fields, the values
 * of sent HTTP cookies, and more.
 *
 * **You can refer to this class by its alias, which is** `Req`.
 */

// Method signatures:
//   static CUStringObject ip ()
//   static CUStringObject userAgentProperty ($sPropertyName)
//   static bool isSecure ()
//   static int serverPort ()
//   static enum method ()
//   static CUStringObject methodString ()
//   static CUStringObject protocolVersion ()
//   static CUStringObject uri ()
//   static CUStringObject pathString ()
//   static CUrlPath path ()
//   static CUStringObject queryString ()
//   static CUStringObject header ($sHeaderName, &$rbSuccess = null)
//   static mixed fieldGet ($sFieldName, CInputFilter $oInputFilter, &$rbSuccess = null)
//   static mixed fieldPost ($sFieldName, CInputFilter $oInputFilter, &$rbSuccess = null)
//   static CUStringObject rawPost ()
//   static mixed cookie ($sCookieName, CInputFilter $oInputFilter, &$rbSuccess = null)
//   static bool hasAuthType ()
//   static CUStringObject authType ()
//   static bool hasAuthUser ()
//   static CUStringObject authUser ()
//   static bool hasAuthPassword ()
//   static CUStringObject authPassword ()
//   static CTime time ()

class CRequest extends CHttpRequest
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the IP address from which the request was sent.
     *
     * @return CUStringObject The IP address from which the request was sent.
     */

    public static function ip ()
    {
        return $_SERVER["REMOTE_ADDR"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a property of the user's agent, according to the BrowsCap project.
     *
     * The names of the properties and their meanings are defined by the
     * [Browser Capabilities Project](http://browscap.org/).
     *
     * @param  string $sPropertyName The name of the BrowsCap property.
     *
     * @return CUStringObject The value of the BrowsCap property.
     */

    public static function userAgentProperty ($sPropertyName)
    {
        assert( 'is_cstring($sPropertyName)', vs(isset($this), get_defined_vars()) );

        if ( !isset(self::$ms_mBrowsCap) )
        {
            self::$ms_mBrowsCap = get_browser(null, true);
        }
        return (string)self::$ms_mBrowsCap[$sPropertyName];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the request was sent over an encrypted connection, such as HTTPS.
     *
     * @return bool `true` if the request was sent over an encrypted connection, `false` otherwise.
     */

    public static function isSecure ()
    {
        if ( CMap::hasKey($_SERVER, "HTTPS") )
        {
            $sValue = $_SERVER["HTTPS"];
            if ( isset($sValue) )
            {
                return ( !CString::isEmpty($sValue) && !CString::equalsCi($sValue, "off") );
            }
        }
        return false;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the number of the port to which the request was addressed.
     *
     * @return int The number of the port to which the request was addressed.
     */

    public static function serverPort ()
    {
        return (int)$_SERVER["SERVER_PORT"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the HTTP method of the request, as an enumerand.
     *
     * @return enum The HTTP method of the request (see [Summary](#summary)).
     */

    public static function method ()
    {
        $sMethod = $_SERVER["REQUEST_METHOD"];
        $sMethod = CString::toUpperCase($sMethod);
        if ( CString::equals($sMethod, "GET") )
        {
            return self::GET;
        }
        if ( CString::equals($sMethod, "HEAD") )
        {
            return self::HEAD;
        }
        if ( CString::equals($sMethod, "POST") )
        {
            return self::POST;
        }
        if ( CString::equals($sMethod, "PUT") )
        {
            return self::PUT;
        }
        if ( CString::equals($sMethod, "DELETE") )
        {
            return self::DELETE;
        }
        if ( CString::equals($sMethod, "OPTIONS") )
        {
            return self::OPTIONS;
        }
        if ( CString::equals($sMethod, "TRACE") )
        {
            return self::TRACE;
        }
        if ( CString::equals($sMethod, "CONNECT") )
        {
            return self::CONNECT;
        }
        return self::UNKNOWN;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the HTTP method of the request, as a string.
     *
     * @return CUStringObject The HTTP method of the request.
     */

    public static function methodString ()
    {
        return $_SERVER["REQUEST_METHOD"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the HTTP protocol used by the request.
     *
     * @return CUStringObject The version of the HTTP protocol used by the request.
     */

    public static function protocolVersion ()
    {
        $sHttpAndVer = $_SERVER["SERVER_PROTOCOL"];
        $sFoundString;
        $bRes = CRegex::find($sHttpAndVer, "/(?<=\\/).*\\z/", $sFoundString);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        return $sFoundString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the URI of the request.
     *
     * @return CUStringObject The URI of the request.
     */

    public static function uri ()
    {
        return $_SERVER["REQUEST_URI"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path part of the URL of the request, as a string.
     *
     * @return CUStringObject The path part of the URL of the request.
     */

    public static function pathString ()
    {
        $sUri = $_SERVER["REQUEST_URI"];
        $sPath = CRegex::remove($sUri, "/\\?.*/");
        return $sPath;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the path part of the URL of the request, as a CUrlPath object.
     *
     * @return CUrlPath The path part of the URL of the request.
     */

    public static function path ()
    {
        return new CUrlPath(self::pathString());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the query string of the URL of the request.
     *
     * @return CUStringObject The query string of the URL of the request.
     */

    public static function queryString ()
    {
        return $_SERVER["QUERY_STRING"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of an HTTP header that was sent with the request.
     *
     * @param  string $sHeaderName The name of the HTTP header.
     * @param  reference $rbSuccess **OPTIONAL.** After the method is called with this parameter provided, the
     * parameter's value tells whether the request contained a header with the name specified.
     *
     * @return CUStringObject The value of the header.
     */

    public static function header ($sHeaderName, &$rbSuccess = null)
    {
        assert( 'is_cstring($sHeaderName)', vs(isset($this), get_defined_vars()) );

        $rbSuccess = true;
        $sKey = CString::replace($sHeaderName, "-", "_");
        $sKey = CString::toUpperCase($sKey);
        $sKey = "HTTP_$sKey";
        if ( !CMap::hasKey($_SERVER, $sKey) )
        {
            $rbSuccess = false;
            return "";
        }
        return $_SERVER[$sKey];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a field from the URL's query string if the request's HTTP method is GET.
     *
     * Different from raw PHP, you can use "." within field names as much as you like.
     *
     * @param  string $sFieldName The name of the field.
     * @param  CInputFilter $oInputFilter The input filter to be used to validate and sanitize the field's value.
     * @param  reference $rbSuccess **OPTIONAL.** After the method is called with this parameter provided, the
     * parameter's value tells whether the query string contained a field with the name specified and the field's value
     * could be successfully retrieved and filtered.
     *
     * @return mixed The value of the query string's field. This can be an `CUStringObject` (the most common case), an
     * `CArrayObject` if the field is e.g. "name[]=value0&name[]=value1", or an `CMapObject` if the field is
     * e.g. "name[key0]=value0&name[key1]=value1".
     */

    public static function fieldGet ($sFieldName, CInputFilter $oInputFilter, &$rbSuccess = null)
    {
        assert( 'is_cstring($sFieldName)', vs(isset($this), get_defined_vars()) );
        return oop_x(self::requestField($_GET, $sFieldName, $oInputFilter, $rbSuccess));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a field from the POST fields, which are key-value pairs that can arrive with a request if
     * the request's HTTP method is POST.
     *
     * Different from raw PHP, you can use "." within field names as much as you like.
     *
     * @param  string $sFieldName The name of the field.
     * @param  CInputFilter $oInputFilter The input filter to be used to validate and sanitize the field's value.
     * @param  reference $rbSuccess **OPTIONAL.** After the method is called with this parameter provided, the
     * parameter's value tells whether the POST fields included a field with the name specified and the field's value
     * could be successfully retrieved and filtered.
     *
     * @return mixed The value of the POST field. This can be an `CUStringObject` (the most common case), an
     * `CArrayObject` if the field is e.g. "name[]=value0&name[]=value1", or an `CMapObject` if the field is
     * e.g. "name[key0]=value0&name[key1]=value1".
     */

    public static function fieldPost ($sFieldName, CInputFilter $oInputFilter, &$rbSuccess = null)
    {
        assert( 'is_cstring($sFieldName)', vs(isset($this), get_defined_vars()) );
        return oop_x(self::requestField($_POST, $sFieldName, $oInputFilter, $rbSuccess));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the data that was sent with the request if the request's HTTP method is POST.
     *
     * @return CUStringObject The raw POST data.
     */

    public static function rawPost ()
    {
        return CFile::read("php://input");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of an HTTP cookie that was sent with the request.
     *
     * Different from raw PHP, you can use "." within cookie names as much as you like.
     *
     * @param  string $sCookieName The name of the cookie.
     * @param  CInputFilter $oInputFilter The input filter to be used to validate and sanitize the cookie's value.
     * @param  reference $rbSuccess **OPTIONAL.** After the method is called with this parameter provided, the
     * parameter's value tells whether the request contained a cookie with the name specified and the cookie's value
     * could be successfully retrieved and filtered.
     *
     * @return mixed The value of the cookie. This can be an `CUStringObject` (the most common case), an `CArrayObject`
     * if the cookie is e.g. "name[]=value0&name[]=value1", or an `CMapObject` if the cookie is
     * e.g. "name[key0]=value0&name[key1]=value1".
     */

    public static function cookie ($sCookieName, CInputFilter $oInputFilter, &$rbSuccess = null)
    {
        assert( 'is_cstring($sCookieName)', vs(isset($this), get_defined_vars()) );
        return oop_x(self::requestField($_COOKIE, $sCookieName, $oInputFilter, $rbSuccess));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the request specifies an HTTP authentication type.
     *
     * @return bool `true` if the request specifies an HTTP authentication type, `false` otherwise.
     */

    public static function hasAuthType ()
    {
        return CMap::hasKey($_SERVER, "AUTH_TYPE");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the HTTP authentication type specified by the request.
     *
     * @return CUStringObject The HTTP authentication type specified by the request.
     */

    public static function authType ()
    {
        assert( 'self::hasAuthType()', vs(isset($this), get_defined_vars()) );
        return $_SERVER["AUTH_TYPE"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the request specifies a username for authentication.
     *
     * @return bool `true` if the request specifies a username for authentication, `false` otherwise.
     */

    public static function hasAuthUser ()
    {
        return CMap::hasKey($_SERVER, "PHP_AUTH_USER");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the username specified by the request for authentication.
     *
     * @return CUStringObject The username for authentication.
     */

    public static function authUser ()
    {
        assert( 'self::hasAuthUser()', vs(isset($this), get_defined_vars()) );
        return $_SERVER["PHP_AUTH_USER"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the request specifies a password for authentication.
     *
     * @return bool `true` if the request specifies a password for authentication, `false` otherwise.
     */

    public static function hasAuthPassword ()
    {
        return CMap::hasKey($_SERVER, "PHP_AUTH_PW");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the password specified by the request for authentication.
     *
     * @return CUStringObject The password for authentication.
     */

    public static function authPassword ()
    {
        assert( 'self::hasAuthPassword()', vs(isset($this), get_defined_vars()) );
        return $_SERVER["PHP_AUTH_PW"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the point in time when the request was received.
     *
     * @return CTime The point in time when the request was received.
     */

    public static function time ()
    {
        if ( CMap::hasKey($_SERVER, "REQUEST_TIME_FLOAT") )
        {
            $fFTime = $_SERVER["REQUEST_TIME_FLOAT"];
            if ( isset($fFTime) )
            {
                return CTime::fromFTime($fFTime);
            }
        }
        return new CTime($_SERVER["REQUEST_TIME"]);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function requestField ($mMap, $sFieldName, CInputFilter $oInputFilter, &$rbSuccess)
    {
        $rbSuccess = true;

        // Account for the fact that, with PHP, GET and POST (and cookie) fields arrive having "." replaced with "_"
        // in their names.
        $sFieldName = CString::replace($sFieldName, ".", "_");

        $xValue;

        $bHasField = false;
        if ( CMap::hasKey($mMap, $sFieldName) )
        {
            $xValue = $mMap[$sFieldName];
            if ( isset($xValue) )
            {
                if ( !is_cstring($xValue) && !is_cmap($xValue) )
                {
                    // Should not happen in the known versions of PHP.
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                    $rbSuccess = false;
                    return $oInputFilter->defaultValue();
                }

                if ( !self::$ms_bTreatEmptyRequestValuesAsAbsent )
                {
                    $bHasField = true;
                }
                else
                {
                    if ( is_cstring($xValue) )
                    {
                        $bHasField = !CString::isEmpty($xValue);
                    }
                    else  // a CMap
                    {
                        $bHasField = ( !CMap::isEmpty($xValue) &&
                                       !(CMap::length($xValue) == 1 && CMap::hasKey($xValue, 0) &&
                                       is_cstring($xValue[0]) && CString::isEmpty($xValue[0])) );
                    }
                }
            }
        }
        if ( !$bHasField )
        {
            $rbSuccess = false;
            return $oInputFilter->defaultValue();
        }

        $xInputFilterOrFilterCollection;
        if ( $oInputFilter->expectedType() != CInputFilter::CARRAY &&
             $oInputFilter->expectedType() != CInputFilter::CMAP )
        {
            $xInputFilterOrFilterCollection = $oInputFilter;
        }
        else
        {
            $xInputFilterOrFilterCollection = $oInputFilter->collectionInputFilters();
        }

        // Recursively convert any PHP array that has sequential keys and for which CArray type is expected into a
        // CArray, while leaving PHP arrays for which CMap type is expected untouched.
        $xValue = self::recurseValueBeforeFiltering($xValue, $xInputFilterOrFilterCollection, $rbSuccess, 0);
        if ( !$rbSuccess )
        {
            return $oInputFilter->defaultValue();
        }

        return $oInputFilter->filter($xValue, $rbSuccess);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function recurseValueBeforeFiltering ($xValue, $xInputFilterOrFilterCollection, &$rbSuccess,
        $iCurrDepth)
    {
        assert( '$xInputFilterOrFilterCollection instanceof CInputFilter || ' .
                'is_collection($xInputFilterOrFilterCollection)', vs(isset($this), get_defined_vars()) );

        if ( $iCurrDepth == self::$ms_iMaxRecursionDepth )
        {
            $rbSuccess = false;
            return;
        }
        $iCurrDepth++;

        if ( !is_cmap($xValue) )
        {
            // Only interested in PHP arrays.
            return $xValue;
        }

        if ( is_carray($xInputFilterOrFilterCollection) )
        {
            // The output value is expected to be a CArray; the keys in the arrived PHP array should be sequential.
            if ( !CMap::areKeysSequential($xValue) )
            {
                $rbSuccess = false;
                return;
            }
            $xValue = CArray::fromPArray($xValue);

            $iLen = CArray::length($xValue);
            if ( $iLen != CArray::length($xInputFilterOrFilterCollection) )
            {
                $rbSuccess = false;
                return;
            }
            for ($i = 0; $i < $iLen; $i++)
            {
                $xInputValue = $xValue[$i];
                $xInputFilterElement = $xInputFilterOrFilterCollection[$i];
                $xInputValue = self::recurseValueBeforeFiltering($xInputValue, $xInputFilterElement, $rbSuccess,
                    $iCurrDepth);
                if ( !$rbSuccess )
                {
                    return;
                }
                $xValue[$i] = $xInputValue;
            }
        }
        else if ( is_cmap($xInputFilterOrFilterCollection) )
        {
            // The output value is expected to be a CMap; already got one.
            foreach ($xValue as $xInputKey => &$rxInputValue)
            {
                if ( !CMap::hasKey($xInputFilterOrFilterCollection, $xInputKey) )
                {
                    $rbSuccess = false;
                    return;
                }
                $xInputFilterElement = $xInputFilterOrFilterCollection[$xInputKey];
                $rxInputValue = self::recurseValueBeforeFiltering($rxInputValue, $xInputFilterElement, $rbSuccess,
                    $iCurrDepth);
                if ( !$rbSuccess )
                {
                    return;
                }
            } unset($rxInputValue);
        }
        else
        {
            $rbSuccess = false;
            return;
        }
        return $xValue;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_mBrowsCap;

    // When this constant is `true`, a GET/POST/cookie field set to an empty string (or an empty associative array)
    // is considered absent from the request.
    protected static $ms_bTreatEmptyRequestValuesAsAbsent = false;

    protected static $ms_iMaxRecursionDepth = CSystem::DEFAULT_MAX_RECURSION_DEPTH;
}
