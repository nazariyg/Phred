<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that represents the HTTP response to be sent back and that lets you add HTTP headers and HTTP cookies to
 * the response, make the response redirect the user's agent to another location, refresh the page, control the caching
 * of the response, make the user's agent download a file, and more.
 *
 * **You can refer to this class by its alias, which is** `Res`.
 */

// Method signatures:
//   static void setCode ($sCodeString)
//   static void addHeader ($sHeaderName, $sValue)
//   static void addHeaderPlural ($sHeaderName, $sValue)
//   static void addCookie (CCookie $oCookie)
//   static void setCookieForDeletion ($sCookieName)
//   static void setRedirect ($sTargetUrl, $sCodeString = self::FOUND)
//   static void setRefresh ($iInNumSeconds, $sTargetUrl = null)
//   static void disableCaching ()
//   static void setFileDownload ($sFileName, $iFileSize)
//   static bool hasHeader ($sHeaderName)
//   static CUStringObject header ($sHeaderName)
//   static void removeHeader ($sHeaderName)

class CResponse extends CHttpResponse
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the response code of the response.
     *
     * @param  string $sCodeString The response code's string, e.g. "100 Continue" (see [Summary](#summary)).
     *
     * @return void
     */

    public static function setCode ($sCodeString)
    {
        assert( 'is_cstring($sCodeString)', vs(isset($this), get_defined_vars()) );

        $sFoundString;
        $bRes = CRegex::find($sCodeString, "/^\\d+/", $sFoundString);
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
        $iCode = CString::toInt($sFoundString);
        http_response_code($iCode);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP header to the response.
     *
     * If there has already been a header added to the response by the same name, the new value does not override any
     * of the existing ones and the header will be sent with all of the added values being comma-separated.
     *
     * @param  string $sHeaderName The name of the header.
     * @param  string $sValue The value of the header.
     *
     * @return void
     */

    public static function addHeader ($sHeaderName, $sValue)
    {
        assert( 'is_cstring($sHeaderName) && is_cstring($sValue)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sHeaderName, "/^[\\\\w\\\\-]+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( '!CRegex::find($sValue, "/\\\\v/")', vs(isset($this), get_defined_vars()) );

        $sHeaderLine;
        $sValue = CString::trim($sValue);
        if ( !self::hasHeader($sHeaderName) )
        {
            $sHeaderLine = "$sHeaderName: $sValue";
        }
        else
        {
            // The header already exists. Combine the header values, removing duplicates based on case-insensitive
            // equality.
            $sCurrValue = self::header($sHeaderName);
            $aValues = CString::split("$sCurrValue, $sValue", ",");
            $iLen = CArray::length($aValues);
            for ($i = 0; $i < $iLen; $i++)
            {
                $aValues[$i] = CString::trim($aValues[$i]);
            }
            $aValues = CArray::filter($aValues, function ($sElement)
                {
                    return !CString::isEmpty($sElement);
                });
            $aValues = CArray::unique($aValues, function ($sElement0, $sElement1)
                {
                    return CString::equalsCi($sElement0, $sElement1);
                });
            $sCombinedValue = CArray::join($aValues, ", ");
            $sHeaderLine = "$sHeaderName: $sCombinedValue";
        }
        header($sHeaderLine, true);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP header to the response so that it is allowed to appear repeated in the outgoing header list if the
     * list already contains a header with the same name.
     *
     * @param  string $sHeaderName The name of the header.
     * @param  string $sValue The value of the header.
     *
     * @return void
     */

    public static function addHeaderPlural ($sHeaderName, $sValue)
    {
        assert( 'is_cstring($sHeaderName) && is_cstring($sValue)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sHeaderName, "/^[\\\\w\\\\-]+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( '!CRegex::find($sValue, "/\\\\v/")', vs(isset($this), get_defined_vars()) );

        header("$sHeaderName: $sValue", false);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP cookie to the response for the cookie to be set on the client side when the response is received.
     *
     * @param  CCookie $oCookie The cookie to be added.
     *
     * @return void
     */

    public static function addCookie (CCookie $oCookie)
    {
        $bRes = setcookie($oCookie->name(), $oCookie->value(), $oCookie->expireUTime(), $oCookie->path(),
            $oCookie->domain(), $oCookie->secure(), $oCookie->httpOnly());
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Marks an HTTP cookie for deletion to take place on the client side when the response is received.
     *
     * @param  string $sCookieName The name of the cookie to be deleted.
     *
     * @return void
     */

    public static function setCookieForDeletion ($sCookieName)
    {
        assert( 'is_cstring($sCookieName)', vs(isset($this), get_defined_vars()) );

        $bRes = setcookie($sCookieName, "");
        assert( '$bRes', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes the response redirect the user's agent to a specified location.
     *
     * With this method, the HTTP response takes the following form:
     *
     * ---
     *
     * *Response line with the specified response code*
     *
     * Location: *target URL*
     *
     * ---
     *
     * @param  string $sTargetUrl The location to which the user's agent should be redirected (can be absolute or
     * relative).
     * @param  string $sCodeString **OPTIONAL. Default is** `FOUND` ("302 Found"). The response code's string of the
     * response. This is usually `FOUND`, `MOVED_PERMANENTLY`, or `SEE_OTHER`.
     *
     * @return void
     */

    public static function setRedirect ($sTargetUrl, $sCodeString = self::FOUND)
    {
        assert( 'is_cstring($sTargetUrl) && is_cstring($sCodeString)', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::LOCATION)', vs(isset($this), get_defined_vars()) );

        self::setCode($sCodeString);
        self::addHeader(self::LOCATION, $sTargetUrl);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes the response refresh the page in the user's agent after displaying it for a specified amount of time.
     *
     * With this method, the HTTP response takes the following form:
     *
     * ---
     *
     * Refresh: *seconds*[; url=*target URL*]
     *
     * ---
     *
     * @param  int $iInNumSeconds The number of seconds for the user's agent to wait before refreshing.
     * @param  string $sTargetUrl **OPTIONAL.** The location to which the user's agent should be redirected instead of
     * refreshing to the same page (can be absolute or relative).
     *
     * @return void
     */

    public static function setRefresh ($iInNumSeconds, $sTargetUrl = null)
    {
        assert( 'is_int($iInNumSeconds) && (!isset($sTargetUrl) || is_cstring($sTargetUrl))',
            vs(isset($this), get_defined_vars()) );
        assert( '$iInNumSeconds >= 0', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::REFRESH)', vs(isset($this), get_defined_vars()) );

        $sValue = CString::fromInt($iInNumSeconds);
        if ( isset($sTargetUrl) )
        {
            $sValue .= "; url=$sTargetUrl";
        }
        self::addHeader(self::REFRESH, $sValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Disables any caching of the response.
     *
     * With this method, the HTTP response takes the following form:
     *
     * ---
     *
     * Cache-Control: no-cache, no-store, must-revalidate
     *
     * Pragma: no-cache
     *
     * Expires: 0
     *
     * ---
     *
     * @return void
     */

    public static function disableCaching ()
    {
        assert( '!self::hasHeader(self::EXPIRES)', vs(isset($this), get_defined_vars()) );

        self::addHeader(self::CACHE_CONTROL, "no-cache, no-store, must-revalidate");
        self::addHeader(self::PRAGMA, "no-cache");
        self::addHeader(self::EXPIRES, "0");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes the response trigger file downloading on the client side for the response's contents.
     *
     * With this method, the HTTP response takes the following form:
     *
     * ---
     *
     * Content-Type: application/octet-stream
     *
     * Content-Disposition: attachment; filename="*file's name*"
     *
     * Content-Length: *file's size*
     *
     * *The headers/values to disable caching*
     *
     * ---
     *
     * @param  string $sFileName The name by which the response's contents should be downloaded by the user's agent as
     * a file.
     * @param  int $iFileSize The size of the data to be downloaded by the user's agent, in bytes.
     *
     * @return void
     */

    public static function setFileDownload ($sFileName, $iFileSize)
    {
        assert( 'is_cstring($sFileName) && is_int($iFileSize)', vs(isset($this), get_defined_vars()) );
        assert( '$iFileSize >= 0', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::CONTENT_TYPE)', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::CONTENT_DISPOSITION)', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::CONTENT_LENGTH)', vs(isset($this), get_defined_vars()) );

        self::addHeader(self::CONTENT_TYPE, CMimeType::OCTET_STREAM);

        $sFnPart;
        if ( CString::isValid($sFileName) )
        {
            $sFnPart = "filename=\"$sFileName\"";
        }
        else
        {
            // The file's name contains one or more Unicode characters. Use percent-encoding.
            $sFileNamePe = CUrl::enterTdNew($sFileName);
            $sFnPart = "filename*=UTF-8''$sFileNamePe";
        }
        self::addHeader(self::CONTENT_DISPOSITION, "attachment; $sFnPart");

        self::addHeader(self::CONTENT_LENGTH, CString::fromInt($iFileSize));

        self::disableCaching();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the response includes an HTTP header with a specified name.
     *
     * @param  string $sHeaderName The name of the header to be looked for.
     *
     * @return bool `true` if the response includes a header with the name specified, `false` otherwise.
     */

    public static function hasHeader ($sHeaderName)
    {
        assert( 'is_cstring($sHeaderName)', vs(isset($this), get_defined_vars()) );

        $mHeaders = self::headersLcKeys();
        $sHeaderNameLc = CString::toLowerCase($sHeaderName);
        return CMap::hasKey($mHeaders, $sHeaderNameLc);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of an HTTP header included in the response.
     *
     * @param  string $sHeaderName The name of the header.
     *
     * @return CUStringObject The value of the header.
     */

    public static function header ($sHeaderName)
    {
        assert( 'is_cstring($sHeaderName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasHeader($sHeaderName)', vs(isset($this), get_defined_vars()) );

        $mHeaders = self::headersLcKeys();
        $sHeaderNameLc = CString::toLowerCase($sHeaderName);
        return $mHeaders[$sHeaderNameLc];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an HTTP header from the response.
     *
     * @param  string $sHeaderName The name of the header to be removed.
     *
     * @return void
     */

    public static function removeHeader ($sHeaderName)
    {
        assert( 'is_cstring($sHeaderName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasHeader($sHeaderName)', vs(isset($this), get_defined_vars()) );

        header_remove($sHeaderName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function headersLcKeys ()
    {
        $mHeaders = CMap::make();
        $aHeaders = CArray::fromPArray(headers_list());
        $iLen = CArray::length($aHeaders);
        for ($i = 0; $i < $iLen; $i++)
        {
            $aFoundGroups;
            $bRes = CRegex::findGroups($aHeaders[$i], "/^\\s*(.+?)\\h*:\\h*(.+?)\\s*\\z/", $aFoundGroups);
            assert( '$bRes', vs(isset($this), get_defined_vars()) );
            $sHeaderNameLc = CString::toLowerCase($aFoundGroups[0]);
            $mHeaders[$sHeaderNameLc] = $aFoundGroups[1];
        }
        return $mHeaders;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
