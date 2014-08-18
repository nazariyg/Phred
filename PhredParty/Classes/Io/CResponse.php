<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   static void setCode ($codeString)
//   static void addHeader ($headerName, $value)
//   static void addHeaderPlural ($headerName, $value)
//   static void addCookie (CCookie $cookie)
//   static void setCookieForDeletion ($cookieName)
//   static void setRedirect ($targetUrl, $codeString = self::FOUND)
//   static void setRefresh ($inNumSeconds, $targetUrl = null)
//   static void disableCaching ()
//   static void setFileDownload ($fileName, $fileSize)
//   static bool hasHeader ($headerName)
//   static CUStringObject header ($headerName)
//   static void removeHeader ($headerName)

class CResponse extends CHttpResponse
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the response code of the response.
     *
     * @param  string $codeString The response code's string, e.g. "100 Continue" (see [Summary](#summary)).
     *
     * @return void
     */

    public static function setCode ($codeString)
    {
        assert( 'is_cstring($codeString)', vs(isset($this), get_defined_vars()) );

        $foundString;
        $res = CRegex::find($codeString, "/^\\d+/", $foundString);
        assert( '$res', vs(isset($this), get_defined_vars()) );
        $code = CString::toInt($foundString);
        http_response_code($code);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP header to the response.
     *
     * If there has already been a header added to the response by the same name, the new value does not override any
     * of the existing ones and the header will be sent with all of the added values being comma-separated.
     *
     * @param  string $headerName The name of the header.
     * @param  string $value The value of the header.
     *
     * @return void
     */

    public static function addHeader ($headerName, $value)
    {
        assert( 'is_cstring($headerName) && is_cstring($value)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($headerName, "/^[\\\\w\\\\-]+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( '!CRegex::find($value, "/\\\\v/")', vs(isset($this), get_defined_vars()) );

        $headerLine;
        $value = CString::trim($value);
        if ( !self::hasHeader($headerName) )
        {
            $headerLine = "$headerName: $value";
        }
        else
        {
            // The header already exists. Combine the header values, removing duplicates based on case-insensitive
            // equality.
            $currValue = self::header($headerName);
            $values = CString::split("$currValue, $value", ",");
            $len = CArray::length($values);
            for ($i = 0; $i < $len; $i++)
            {
                $values[$i] = CString::trim($values[$i]);
            }
            $values = CArray::filter($values, function ($element)
                {
                    return !CString::isEmpty($element);
                });
            $values = CArray::unique($values, function ($element0, $element1)
                {
                    return CString::equalsCi($element0, $element1);
                });
            $combinedValue = CArray::join($values, ", ");
            $headerLine = "$headerName: $combinedValue";
        }
        header($headerLine, true);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP header to the response so that it is allowed to appear repeated in the outgoing header list if the
     * list already contains a header with the same name.
     *
     * @param  string $headerName The name of the header.
     * @param  string $value The value of the header.
     *
     * @return void
     */

    public static function addHeaderPlural ($headerName, $value)
    {
        assert( 'is_cstring($headerName) && is_cstring($value)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($headerName, "/^[\\\\w\\\\-]+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( '!CRegex::find($value, "/\\\\v/")', vs(isset($this), get_defined_vars()) );

        header("$headerName: $value", false);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP cookie to the response for the cookie to be set on the client side when the response is received.
     *
     * @param  CCookie $cookie The cookie to be added.
     *
     * @return void
     */

    public static function addCookie (CCookie $cookie)
    {
        $res = setcookie($cookie->name(), $cookie->value(), $cookie->expireUTime(), $cookie->path(),
            $cookie->domain(), $cookie->secure(), $cookie->httpOnly());
        assert( '$res', vs(isset($this), get_defined_vars()) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Marks an HTTP cookie for deletion to take place on the client side when the response is received.
     *
     * @param  string $cookieName The name of the cookie to be deleted.
     *
     * @return void
     */

    public static function setCookieForDeletion ($cookieName)
    {
        assert( 'is_cstring($cookieName)', vs(isset($this), get_defined_vars()) );

        $res = setcookie($cookieName, "");
        assert( '$res', vs(isset($this), get_defined_vars()) );
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
     * @param  string $targetUrl The location to which the user's agent should be redirected (can be absolute or
     * relative).
     * @param  string $codeString **OPTIONAL. Default is** `FOUND` ("302 Found"). The response code's string of the
     * response. This is usually `FOUND`, `MOVED_PERMANENTLY`, or `SEE_OTHER`.
     *
     * @return void
     */

    public static function setRedirect ($targetUrl, $codeString = self::FOUND)
    {
        assert( 'is_cstring($targetUrl) && is_cstring($codeString)', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::LOCATION)', vs(isset($this), get_defined_vars()) );

        self::setCode($codeString);
        self::addHeader(self::LOCATION, $targetUrl);
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
     * @param  int $inNumSeconds The number of seconds for the user's agent to wait before refreshing.
     * @param  string $targetUrl **OPTIONAL.** The location to which the user's agent should be redirected instead of
     * refreshing to the same page (can be absolute or relative).
     *
     * @return void
     */

    public static function setRefresh ($inNumSeconds, $targetUrl = null)
    {
        assert( 'is_int($inNumSeconds) && (!isset($targetUrl) || is_cstring($targetUrl))',
            vs(isset($this), get_defined_vars()) );
        assert( '$inNumSeconds >= 0', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::REFRESH)', vs(isset($this), get_defined_vars()) );

        $value = CString::fromInt($inNumSeconds);
        if ( isset($targetUrl) )
        {
            $value .= "; url=$targetUrl";
        }
        self::addHeader(self::REFRESH, $value);
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
     * @param  string $fileName The name by which the response's contents should be downloaded by the user's agent as
     * a file.
     * @param  int $fileSize The size of the data to be downloaded by the user's agent, in bytes.
     *
     * @return void
     */

    public static function setFileDownload ($fileName, $fileSize)
    {
        assert( 'is_cstring($fileName) && is_int($fileSize)', vs(isset($this), get_defined_vars()) );
        assert( '$fileSize >= 0', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::CONTENT_TYPE)', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::CONTENT_DISPOSITION)', vs(isset($this), get_defined_vars()) );
        assert( '!self::hasHeader(self::CONTENT_LENGTH)', vs(isset($this), get_defined_vars()) );

        self::addHeader(self::CONTENT_TYPE, CMimeType::OCTET_STREAM);

        $fnPart;
        if ( CString::isValid($fileName) )
        {
            $fnPart = "filename=\"$fileName\"";
        }
        else
        {
            // The file's name contains one or more Unicode characters. Use percent-encoding.
            $fileNamePe = CUrl::enterTdNew($fileName);
            $fnPart = "filename*=UTF-8''$fileNamePe";
        }
        self::addHeader(self::CONTENT_DISPOSITION, "attachment; $fnPart");

        self::addHeader(self::CONTENT_LENGTH, CString::fromInt($fileSize));

        self::disableCaching();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the response includes an HTTP header with a specified name.
     *
     * @param  string $headerName The name of the header to be looked for.
     *
     * @return bool `true` if the response includes a header with the name specified, `false` otherwise.
     */

    public static function hasHeader ($headerName)
    {
        assert( 'is_cstring($headerName)', vs(isset($this), get_defined_vars()) );

        $headers = self::headersLcKeys();
        $headerNameLc = CString::toLowerCase($headerName);
        return CMap::hasKey($headers, $headerNameLc);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of an HTTP header included in the response.
     *
     * @param  string $headerName The name of the header.
     *
     * @return CUStringObject The value of the header.
     */

    public static function header ($headerName)
    {
        assert( 'is_cstring($headerName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasHeader($headerName)', vs(isset($this), get_defined_vars()) );

        $headers = self::headersLcKeys();
        $headerNameLc = CString::toLowerCase($headerName);
        return $headers[$headerNameLc];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes an HTTP header from the response.
     *
     * @param  string $headerName The name of the header to be removed.
     *
     * @return void
     */

    public static function removeHeader ($headerName)
    {
        assert( 'is_cstring($headerName)', vs(isset($this), get_defined_vars()) );
        assert( 'self::hasHeader($headerName)', vs(isset($this), get_defined_vars()) );

        header_remove($headerName);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function headersLcKeys ()
    {
        $paHeaders = CMap::make();
        $headers = CArray::fromPArray(headers_list());
        $len = CArray::length($headers);
        for ($i = 0; $i < $len; $i++)
        {
            $foundGroups;
            $res = CRegex::findGroups($headers[$i], "/^\\s*(.+?)\\h*:\\h*(.+?)\\s*\\z/", $foundGroups);
            assert( '$res', vs(isset($this), get_defined_vars()) );
            $headerNameLc = CString::toLowerCase($foundGroups[0]);
            $paHeaders[$headerNameLc] = $foundGroups[1];
        }
        return $paHeaders;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
