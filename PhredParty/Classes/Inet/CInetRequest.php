<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that lets you make HTTP, HTTPS, FTP, and FTPS requests over the Internet, as a part of a cookies-enabled
 * session if used together with the CInetSession class.
 *
 * **You can refer to this class by its alias, which is** `InetReq`.
 *
 * For the HTTP and HTTPS protocols, the fully supported methods are GET, POST, PUT, DELETE, HEAD, and LIST. With
 * HTTP/HTTPS, you can upload a file to a remote location via POST method, which makes a special case of POST method
 * that is referred to as `HTTP_UPLOAD`, or via PUT method. The PUT and DELETE methods can be used to communicate with
 * RESTful APIs among other purposes.
 *
 * If a created request is not going to be a part of a session, which is represented by the CInetSession class, it is
 * sent to the destination URL by calling `send` method. Since some requests may take considerable amount of time to
 * complete, with a request that downloads a large file being a typical example, requests do not time out by default,
 * and neither does the PHP script while a request is being processed. Therefore, it is recommended to specify the
 * timeout value for every particular request before sending it out.
 *
 * For such a common task as downloading a response to a file, `downloadFile` static method provides the convenience of
 * downloading responses over any protocol without the need for creating an object or specifying the request type.
 *
 * When multiple requests need to be sent or when the destination server is expected to set cookies that would need to
 * be sent back later with subsequent requests, you can use the [CInetSession](CInetSession.html) class to improve
 * performance by letting the requests be processed in parallel, to enable full cookie support, or both.
 *
 * **Defaults:**
 *
 * * Apart from the connection phase that times out by default, the request itself does not time out.
 * * The default timeout for the connection phase is 300 seconds.
 * * The default timeout for an entry in the DNS cache is 60 or 120 seconds, depending on the cURL's version.
 * * Redirecting an HTTP request based on the value of the "Location" header in the response is `true`.
 * * The number of redirections to be followed by an HTTP request is not limited.
 * * Automatically set the value of the "Referer" header for an HTTP request while it is being redirected is `true`.
 * * Consider an HTTP request unsuccessful if the received response code is 400 or greater (4xx for "Client Error" or
 *     5xx for "Server Error") is `true`.
 * * Send the default "Accept-Encoding" HTTP header, the value of which is determined by the capabilities of cURL, is
 *     `true`.
 * * Send the default "User-Agent" HTTP header, the value of which is determined by the corresponding static property
 *     of the class, is `false`.
 * * Verify the destination server's certificate when trying to connect via HTTPS or any other SSL-enabled protocol is
 *     `true`.
 * * Verify the destination server's hostname when trying to connect via HTTPS or any other SSL-enabled protocol is
 *     `true`.
 * * Keep sending the username and password for HTTP authentication while being redirected is `false`.
 * * Try to create missing directories when uploading via FTP protocol is `true`.
 * * Echo the response out instead of returning it is `false`.
 */

// Method signatures:
//   __construct ($sUrl, $eType = self::HTTP_GET, $sDownloadDestinationFp = null)
//   CUStringObject errorMessage ()
//   void setPort ($iPort)
//   void setRequestTimeout ($iNumSeconds)
//   void setConnectionTimeout ($iNumSeconds)
//   void removeConnectionTimeout ()
//   void setDnsCacheTimeout ($iNumSeconds)
//   void setRedirection ($bEnable)
//   void setMaxNumRedirections ($iMaxNumRedirections)
//   void setRedirectionAutoReferer ($bEnable)
//   void setFailOn400ResponseCodeOrGreater ($bEnable)
//   void addHeader ($sHeaderName, $sValue)
//   void addHeaderPlural ($sHeaderName, $sValue)
//   void addPostField ($sFieldName, $xValue)
//   void addCookie ($sCookieName, $sValue)
//   void setUploadFile ($sFilePath, $sMimeType = null, $sFileName = null, $sFileId = null)
//   void setUploadData ($byData, $sMimeType = null, $sFileName = null, $sFileId = null)
//   void setSendDefaultAcceptEncodingHeader ($bEnable)
//   void setSendDefaultUserAgentHeader ($bEnable)
//   void setAllow304ResponseCodeIfNotModifiedSince (CTime $oTime)
//   void setRequestedByteRange ($aByteRangeOrRanges)
//   void setCertificateVerification ($bEnable, $sAlternateCertificateFpOrDp = null)
//   void setHostVerification ($bEnable)
//   void setUserAndPassword ($sUser, $sPassword)
//   void setRedirectionKeepAuth ($bEnable)
//   void setFtpOptions ($bCreateMissingDirectoriesForUpload, $bAppendUpload = false, $aQuoteCommands = null,
//     $aPostQuoteCommands = null, $bUseEpsv = true, $sActiveModeBackAddress = null, $bUseEprt = true)
//   void setSslOptions ($sCertificateFp, $sPrivateKeyFp = null, $sCertificateFormat = "PEM",
//     $sPrivateKeyFormat = "PEM", $sCertificatePassphrase = null, $sPrivateKeyPassphrase = null, $iSslVersion = null,
//     $sCipherList = null, $sEngine = null, $sDefaultEngine = null)
//   void setKerberos ($bEnable, $sLevel = null)
//   void setProxy ($sProxyAddress, $sUser, $sPassword, $eProxyType = self::PROXY_SOCKS_5, $iProxyPort = null,
//     $bProxyTunneling = false, $bConnectOnly = false)
//   void setOutgoingInterface ($sInterface)
//   void setEchoResponse ($bEnable)
//   void setVerboseOutput ($bEnable)
//   void setMaxDownloadSpeed ($iBytesPerSecond)
//   void setMaxUploadSpeed ($iBytesPerSecond)
//   CUStringObject send (&$rbSuccess)
//   int responseCode ()
//   bool responseHasHeader ($sHeaderName)
//   CUStringObject responseHeader ($sHeaderName)
//   bool responseHasTimestamp ()
//   CTime responseTimestamp ()
//   bool responseHasModificationTime ()
//   CTime responseModificationTime ()
//   int responseDownloadSize ()
//   float responseDownloadSpeed ()
//   int responseHeadersSize ()
//   CMapObject responseCertificateInfo ()
//   int requestSize ()
//   int requestUploadSize ()
//   float requestUploadSpeed ()
//   int requestUploadContentLength ()
//   float requestTotalTime ()
//   float requestDnsLookupTime ()
//   float requestConnectionEstablishmentTime ()
//   float requestRedirectionTime ()
//   float requestPreTransferTime ()
//   float requestStartTransferTime ()
//   int requestNumRedirections ()
//   bool requestSslVerificationResult ()
//   CUStringObject requestHeadersString ()
//   static bool downloadFile ($sUrl, $sDestinationFp, $iTimeoutSeconds = null)
//   static CUStringObject read ($sUrl, &$rbSuccess = null, $iTimeoutSeconds = null)
//   static CUStringObject curlVersion ()
//   static int curlVersionInt ()
//   static CUStringObject openSslVersion ()
//   static int openSslVersionInt ()

class CInetRequest extends CRootClass
{
    // Request types.
    /**
     * `enum` HTTP GET request.
     *
     * @var enum
     */
    const HTTP_GET = 0;
    /**
     * `enum` Any download over HTTP.
     *
     * @var enum
     */
    const HTTP_DOWNLOAD = 1;
    /**
     * `enum` HTTP POST request.
     *
     * @var enum
     */
    const HTTP_POST = 2;
    /**
     * `enum` An upload over HTTP, using POST method.
     *
     * @var enum
     */
    const HTTP_UPLOAD = 3;
    /**
     * `enum` An upload over HTTP, using PUT method.
     *
     * @var enum
     */
    const HTTP_PUT = 4;
    /**
     * `enum` HTTP DELETE request.
     *
     * @var enum
     */
    const HTTP_DELETE = 5;
    /**
     * `enum` HTTP HEAD request.
     *
     * @var enum
     */
    const HTTP_HEAD = 6;
    /**
     * `enum` FTP LIST request.
     *
     * @var enum
     */
    const FTP_LIST = 7;
    /**
     * `enum` Any download over FTP.
     *
     * @var enum
     */
    const FTP_DOWNLOAD = 8;
    /**
     * `enum` Any upload over FTP.
     *
     * @var enum
     */
    const FTP_UPLOAD = 9;
    /**
     * `enum` Any download with any of the supported protocols.
     *
     * @var enum
     */
    const ANY_DOWNLOAD = 10;

    // Proxy types.
    /**
     * `enum` HTTP proxy.
     *
     * @var enum
     */
    const PROXY_HTTP = 0;
    /**
     * `enum` SOCKS5 proxy.
     *
     * @var enum
     */
    const PROXY_SOCKS_5 = 1;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates a request to be sent over the Internet.
     *
     * For the request to be sent over HTTPS instead of regular HTTP, the input URL should explicitly indicate "https"
     * as the communication protocol to be used. The same applies to FTP/FTPS.
     *
     * When making an `HTTP_POST` request, at least one POST field should be specified with `addPostField` method
     * before sending the request. Likewise, if the request type is `HTTP_UPLOAD`, `HTTP_PUT`, or `FTP_UPLOAD`, the
     * outgoing file's path or outgoing data needs to be provided before sending the request, using either
     * `setUploadFile` or `setUploadData` method.
     *
     * The number of the destination port can be specified in the URL or with `setPort` method.
     *
     * @param  string $sUrl The URL to which the request is to be sent. If no protocol is indicated in the URL, the
     * HTTP protocol is assumed.
     * @param  enum $eType **OPTIONAL. Default is** `HTTP_GET`. The type of the request (see [Summary](#summary)).
     * @param  string $sDownloadDestinationFp **OPTIONAL.** For `HTTP_DOWNLOAD`, `FTP_DOWNLOAD`, and `ANY_DOWNLOAD`
     * request types, this is the file path to which the downloaded data should be saved.
     */

    public function __construct ($sUrl, $eType = self::HTTP_GET, $sDownloadDestinationFp = null)
    {
        assert( 'is_cstring($sUrl) && is_enum($eType) && ' .
                '(!isset($sDownloadDestinationFp) || is_cstring($sDownloadDestinationFp))',
            vs(isset($this), get_defined_vars()) );
        assert( 'CUrl::isValid($sUrl, true)', vs(isset($this), get_defined_vars()) );
        assert( '!(($eType == self::HTTP_DOWNLOAD || $eType == self::FTP_DOWNLOAD || ' .
                '$eType == self::ANY_DOWNLOAD) && !isset($sDownloadDestinationFp))',
            vs(isset($this), get_defined_vars()) );

        // Find out the basic protocol from the request type.
        switch ( $eType )
        {
        case self::HTTP_GET:
        case self::HTTP_DOWNLOAD:
        case self::HTTP_POST:
        case self::HTTP_UPLOAD:
        case self::HTTP_PUT:
        case self::HTTP_DELETE:
        case self::HTTP_HEAD:
            $this->m_sRequestBasicProtocol = "http";
            break;
        case self::FTP_LIST:
        case self::FTP_DOWNLOAD:
        case self::FTP_UPLOAD:
            $this->m_sRequestBasicProtocol = "ftp";
            break;
        case self::ANY_DOWNLOAD:
            // Look into the URL.
            $oUrl = new CUrl(CUrl::ensureProtocol($sUrl));
            $sProtocolFromUrl = $oUrl->normalizedProtocol();
            $this->m_sRequestBasicProtocol = self::basicProtocol($sProtocolFromUrl);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        // If the URL does not indicate any protocol, which prevents it from being fully qualified, make the URL
        // indicate the protocol that was derived from the request type.
        $sUrl = CUrl::ensureProtocol($sUrl, $this->m_sRequestBasicProtocol);

        if ( CDebug::isDebugModeOn() )
        {
            $oUrl = new CUrl($sUrl);
            assert( '$oUrl->hasProtocol()', vs(isset($this), get_defined_vars()) );
            $sBasicProtocolFromUrl = self::basicProtocol($oUrl->normalizedProtocol());
            assert( 'CString::equals($sBasicProtocolFromUrl, $this->m_sRequestBasicProtocol)',
                vs(isset($this), get_defined_vars()) );
        }

        $this->m_sUrl = $sUrl;
        $this->m_eType = $eType;
        $this->m_sDownloadDestinationFp = CFilePath::frameworkPath($sDownloadDestinationFp);
        $this->m_mRequestSummary = CMap::make();
        $this->m_aResponseHeaders = CArray::make();
        $this->m_mResponseHeadersLcKeys = CMap::make();

        $this->m_rcCurl = curl_init();
        if ( !is_resource($this->m_rcCurl) || !CString::isEmpty(curl_error($this->m_rcCurl)) )
        {
            $this->m_bHasError = true;
            $sCurlError = ( !is_resource($this->m_rcCurl) ) ? "" : curl_error($this->m_rcCurl);
            $this->m_sErrorMessage = ( CString::isEmpty($sCurlError) ) ? "cURL initialization failed." : $sCurlError;
            $this->finalize();
            return;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * If a request did not succeed, returns the human-readable error message that is describing the problem.
     *
     * @return CUStringObject The error message.
     */

    public function errorMessage ()
    {
        assert( '$this->m_bHasError', vs(isset($this), get_defined_vars()) );
        return $this->m_sErrorMessage;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the number of the port to which a request needs to be sent.
     *
     * A port number set with this method overrides the port number specified in the URL from which the object was
     * constructed, if any.
     *
     * @param  int $iPort The number of the destination port.
     *
     * @return void
     */

    public function setPort ($iPort)
    {
        assert( 'is_int($iPort)', vs(isset($this), get_defined_vars()) );
        $this->m_iPort = $iPort;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets an overall timeout for a request.
     *
     * @param  int $iNumSeconds The number of seconds after which the request should time out.
     *
     * @return void
     */

    public function setRequestTimeout ($iNumSeconds)
    {
        assert( 'is_int($iNumSeconds)', vs(isset($this), get_defined_vars()) );
        assert( '$iNumSeconds > 0', vs(isset($this), get_defined_vars()) );

        $this->m_iRequestTimeoutSeconds = $iNumSeconds;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets a timeout only for the connection establishment stage of a request.
     *
     * @param  int $iNumSeconds The number of seconds after which the request should time out.
     *
     * @return void
     */

    public function setConnectionTimeout ($iNumSeconds)
    {
        assert( 'is_int($iNumSeconds)', vs(isset($this), get_defined_vars()) );
        assert( '$iNumSeconds > 0', vs(isset($this), get_defined_vars()) );

        $this->m_iConnectionTimeoutSeconds = $iNumSeconds;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes the default timeout for the connection establishment stage of a request so that this stage never times
     * out.
     *
     * @return void
     */

    public function removeConnectionTimeout ()
    {
        $this->m_iConnectionTimeoutSeconds = 0;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets a timeout for the entries in the DNS cache for a request.
     *
     * @param  int $iNumSeconds The number of seconds after which an entry should time out.
     *
     * @return void
     */

    public function setDnsCacheTimeout ($iNumSeconds)
    {
        assert( 'is_int($iNumSeconds)', vs(isset($this), get_defined_vars()) );
        assert( '$iNumSeconds > 0', vs(isset($this), get_defined_vars()) );

        $this->m_iDnsCacheTimeoutSeconds = $iNumSeconds;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to follow the URL in the "Location" header if it was sent with the response(s) to a request.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $bEnable `true` to follow the URL in a received "Location" header, `false` otherwise.
     *
     * @return void
     */

    public function setRedirection ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_bRedirection = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the maximum number of redirections that are allowed to take place for a request.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  int $iMaxNumRedirections The maximum number of redirections allowed.
     *
     * @return void
     */

    public function setMaxNumRedirections ($iMaxNumRedirections)
    {
        assert( 'is_int($iMaxNumRedirections)', vs(isset($this), get_defined_vars()) );
        assert( '$iMaxNumRedirections > 0', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_iMaxNumRedirections = $iMaxNumRedirections;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to specify the "Referer" header automatically for every URL that a request is being redirected to.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $bEnable `true` to specify the "Referer" header automatically when being redirected, `false`
     * otherwise.
     *
     * @return void
     */

    public function setRedirectionAutoReferer ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_bRedirectionAutoReferer = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether a request should fail on any 4xx ("Client Error") or 5xx ("Server Error") HTTP response code.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $bEnable `true` if the request should fail on 4xx and 5xx HTTP response codes, `false` if not.
     *
     * @return void
     */

    public function setFailOn400ResponseCodeOrGreater ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_bFailOn400ResponseCodeOrGreater = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP header to a request.
     *
     * If there has already been a header added to the request by the same name, the new value does not override any of
     * the existing ones and the header will be sent with all of the added values being comma-separated.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  string $sHeaderName The name of the header.
     * @param  string $sValue The value of the header.
     *
     * @return void
     */

    public function addHeader ($sHeaderName, $sValue)
    {
        assert( 'is_cstring($sHeaderName) && is_cstring($sValue)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sHeaderName, "/^[\\\\w\\\\-]+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( '!CRegex::find($sValue, "/\\\\v/")', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_aRequestHeaders) )
        {
            $this->m_aRequestHeaders = CArray::make();
        }
        $this->addHeaderWithoutOverriding($this->m_aRequestHeaders, $sHeaderName, $sValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP header to a request so that it is allowed to appear repeated in the outgoing header list if the
     * list already contains a header with the same name.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  string $sHeaderName The name of the header.
     * @param  string $sValue The value of the header.
     *
     * @return void
     */

    public function addHeaderPlural ($sHeaderName, $sValue)
    {
        assert( 'is_cstring($sHeaderName) && is_cstring($sValue)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($sHeaderName, "/^[\\\\w\\\\-]+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( '!CRegex::find($sValue, "/\\\\v/")', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_aRequestHeaders) )
        {
            $this->m_aRequestHeaders = CArray::make();
        }
        $sHeaderLine = "$sHeaderName: $sValue";
        CArray::push($this->m_aRequestHeaders, $sHeaderLine);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds a POST field to a request.
     *
     * This method has an effect on HTTP POST requests only.
     *
     * @param  string $sFieldName The name of the POST field.
     * @param  mixed $xValue The value of the POST field. This can be a string (the most common case), a `bool`, an
     * `int`, a `float`, an array, or a map.
     *
     * @return void
     */

    public function addPostField ($sFieldName, $xValue)
    {
        assert( 'is_cstring($sFieldName) && (is_cstring($xValue) || is_collection($xValue))',
            vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eType == self::HTTP_POST', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_oPostQuery) )
        {
            $this->m_oPostQuery = new CUrlQuery();
        }
        $this->m_oPostQuery->addField($sFieldName, $xValue);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP cookie to a request.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  string $sCookieName The name of the cookie.
     * @param  string $sValue The value of the cookie.
     *
     * @return void
     */

    public function addCookie ($sCookieName, $sValue)
    {
        assert( 'is_cstring($sCookieName) && is_cstring($sValue)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_aRequestCookies) )
        {
            $this->m_aRequestCookies = CArray::make();
        }
        $sCookie = "$sCookieName=$sValue";
        CArray::push($this->m_aRequestCookies, $sCookie);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For any request that needs to upload a file, specifies the file to be uploaded and, for HTTP requests, the
     * file's metadata.
     *
     * @param  string $sFilePath The path to the file to be uploaded.
     * @param  string $sMimeType **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The MIME type of the file.
     * @param  string $sFileName **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The custom name of the file.
     * @param  string $sFileId **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The ID under which the file is
     * to arrive to the destination server.
     *
     * @return void
     */

    public function setUploadFile ($sFilePath, $sMimeType = null, $sFileName = null, $sFileId = null)
    {
        assert( 'is_cstring($sFilePath) && (!isset($sMimeType) || is_cstring($sMimeType)) && (!isset($sFileName) || ' .
                'is_cstring($sFileName)) && (!isset($sFileId) || is_cstring($sFileId))',
            vs(isset($this), get_defined_vars()) );
        assert( 'CFilePath::isAbsolute($sFilePath)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_eType == self::HTTP_UPLOAD || $this->m_eType == self::HTTP_PUT || ' .
                '$this->m_eType == self::FTP_UPLOAD', vs(isset($this), get_defined_vars()) );
        assert( '!($this->m_eType == self::HTTP_UPLOAD && (!isset($sMimeType) || !isset($sFileName) || ' .
                '!isset($sFileId)))', vs(isset($this), get_defined_vars()) );

        $sFilePath = CFilePath::frameworkPath($sFilePath);

        if ( $this->m_eType == self::HTTP_UPLOAD )
        {
            // Via HTTP POST.
            $oCurlFile = new CURLFile($sFilePath, $sMimeType, $sFileName);
            $this->m_mPostFileUploadRecord = [$sFileId => $oCurlFile];
        }
        else  // $this->m_eType = self::HTTP_PUT or $this->m_eType = self::FTP_UPLOAD
        {
            $this->m_sNonPostFileUploadFp = $sFilePath;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For any request that needs to upload a file, specifies the data to be uploaded and, for HTTP requests, the
     * file's metadata.
     *
     * For large data, `setUploadFile` method would be preferred to upload an already existing file.
     *
     * @param  data $byData The data to be uploaded.
     * @param  string $sMimeType **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The MIME type of the file.
     * @param  string $sFileName **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The custom name of the file.
     * @param  string $sFileId **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The ID under which the file is
     * to arrive to the destination server.
     *
     * @return void
     *
     * @link   #method_setUploadFile setUploadFile
     */

    public function setUploadData ($byData, $sMimeType = null, $sFileName = null, $sFileId = null)
    {
        assert( 'is_cstring($byData) && (!isset($sMimeType) || is_cstring($sMimeType)) && ' .
                '(!isset($sFileName) || is_cstring($sFileName)) && (!isset($sFileId) || is_cstring($sFileId))',
            vs(isset($this), get_defined_vars()) );

        $this->m_sFileUploadTempFp = CFile::createTemporary();
        CFile::write($this->m_sFileUploadTempFp, $byData);
        $this->setUploadFile($this->m_sFileUploadTempFp, $sMimeType, $sFileName, $sFileId);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to send the default "Accept-Encoding" HTTP header.
     *
     * The header's default value is determined by the capabilities of the cURL library.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $bEnable `true` to send the default "Accept-Encoding" HTTP header, `false` otherwise.
     *
     * @return void
     */

    public function setSendDefaultAcceptEncodingHeader ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_bSendDefaultAcceptEncodingHeader = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to send the default "User-Agent" HTTP header.
     *
     * The header's default value is determined by the respective static property of the class.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $bEnable `true` to send the default "User-Agent" HTTP header, `false` otherwise.
     *
     * @return void
     */

    public function setSendDefaultUserAgentHeader ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_bSendDefaultUserAgentHeader = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Allows or disallows 304 response code to be returned by the destination server if the requested entity has not
     * been modified since a specified moment in time.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  CTime $oTime The modification time of the requested entity by which its contents are already known to
     * the client.
     *
     * @return void
     */

    public function setAllow304ResponseCodeIfNotModifiedSince (CTime $oTime)
    {
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        if ( isset($this->m_aRequestHeaders) )
        {
            $this->removeHeader(CHttpRequest::IF_MODIFIED_SINCE);
        }
        $sTime = $oTime->toStringUtc(CTime::PATTERN_HTTP_HEADER_GMT);
        $this->addHeader(CHttpRequest::IF_MODIFIED_SINCE, $sTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the byte range(s) of interest in the requested entity.
     *
     * @param  array $aByteRangeOrRanges The byte range(s) of interest. This is either an array with two integer values
     * specifying the byte range to be retrieved or, for HTTP requests only, an array of such arrays specifying
     * multiple byte ranges. The second value in a pair is allowed to be `null`, in which case the range is considered
     * to be open-ended and the server is expected to respond with data starting from the byte indicated by the first
     * value in the pair and up to the very last byte available.
     *
     * @return void
     */

    public function setRequestedByteRange ($aByteRangeOrRanges)
    {
        assert( 'is_carray($aByteRangeOrRanges) && !CArray::isEmpty($aByteRangeOrRanges)',
            vs(isset($this), get_defined_vars()) );

        if ( !is_carray(CArray::first($aByteRangeOrRanges)) )
        {
            assert( 'CArray::length($aByteRangeOrRanges) == 2', vs(isset($this), get_defined_vars()) );
            $iRangeBPosLow = $aByteRangeOrRanges[0];
            $iRangeBPosHigh = $aByteRangeOrRanges[1];
            assert( 'is_int($iRangeBPosLow) && (is_null($iRangeBPosHigh) || is_int($iRangeBPosHigh))',
                vs(isset($this), get_defined_vars()) );
            assert( '$iRangeBPosLow >= 0 && (is_null($iRangeBPosHigh) || $iRangeBPosHigh >= 0)',
                vs(isset($this), get_defined_vars()) );
            assert( 'is_null($iRangeBPosHigh) || $iRangeBPosHigh >= $iRangeBPosLow',
                vs(isset($this), get_defined_vars()) );
            $this->m_sRequestedByteRange = ( !is_null($iRangeBPosHigh) ) ? "$iRangeBPosLow-$iRangeBPosHigh" :
                "$iRangeBPosLow-";
        }
        else
        {
            assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );
            $aPreOutString = CArray::make();
            $iLen = CArray::length($aByteRangeOrRanges);
            $iLenMinusOne = $iLen - 1;
            for ($i = 0; $i < $iLen; $i++)
            {
                $aRange = $aByteRangeOrRanges[$i];

                assert( 'is_carray($aRange)', vs(isset($this), get_defined_vars()) );
                assert( 'CArray::length($aRange) == 2', vs(isset($this), get_defined_vars()) );
                $iRangeBPosLow = $aRange[0];
                $iRangeBPosHigh = $aRange[1];
                if ( CDebug::isDebugModeOn() )
                {
                    // The high byte position can be specified as `null` for the last range only.
                    if ( $i != $iLenMinusOne )
                    {
                        assert( 'is_int($iRangeBPosLow) && is_int($iRangeBPosHigh)',
                            vs(isset($this), get_defined_vars()) );
                        assert( '$iRangeBPosLow >= 0 && $iRangeBPosHigh >= 0', vs(isset($this), get_defined_vars()) );
                        assert( '$iRangeBPosHigh >= $iRangeBPosLow', vs(isset($this), get_defined_vars()) );
                    }
                    else
                    {
                        assert( 'is_int($iRangeBPosLow) && (is_null($iRangeBPosHigh) || is_int($iRangeBPosHigh))',
                            vs(isset($this), get_defined_vars()) );
                        assert( '$iRangeBPosLow >= 0 && (is_null($iRangeBPosHigh) || $iRangeBPosHigh >= 0)',
                            vs(isset($this), get_defined_vars()) );
                        assert( 'is_null($iRangeBPosHigh) || $iRangeBPosHigh >= $iRangeBPosLow',
                            vs(isset($this), get_defined_vars()) );
                    }
                }
                $sRbr = ( !is_null($iRangeBPosHigh) ) ? "$iRangeBPosLow-$iRangeBPosHigh" : "$iRangeBPosLow-";
                CArray::push($aPreOutString, $sRbr);
            }
            $this->m_sRequestedByteRange = CArray::join($aPreOutString, ",");
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables the verification of the destination server's certificate when trying to connect via HTTPS or
     * any other SSL-enabled protocol.
     *
     * @param  bool $bEnable `true` to enable the verification of the destination server's certificate, `false`
     * otherwise.
     * @param  string $sAlternateCertificateFpOrDp **OPTIONAL. Default is** *use the default certificate bundle*. The
     * path to a file or directory with alternate certificate(s) to be used in substitute of the default certificate
     * bundle. Providing this parameter makes sense only if the first parameter is `true`.
     *
     * @return void
     */

    public function setCertificateVerification ($bEnable, $sAlternateCertificateFpOrDp = null)
    {
        assert( 'is_bool($bEnable) && (!isset($sAlternateCertificateFpOrDp) || ' .
                'is_cstring($sAlternateCertificateFpOrDp))', vs(isset($this), get_defined_vars()) );
        assert( '!(!$bEnable && isset($sAlternateCertificateFpOrDp))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($sAlternateCertificateFpOrDp) || CFilePath::isAbsolute($sAlternateCertificateFpOrDp)',
            vs(isset($this), get_defined_vars()) );

        $this->m_bCertificateVerification = $bEnable;
        $this->m_sAlternateCertificateFpOrDp = CFilePath::frameworkPath($sAlternateCertificateFpOrDp);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables the verification of the destination server's hostname when trying to connect via HTTPS or
     * any other SSL-enabled protocol.
     *
     * @param  bool $bEnable `true` to enable the verification of the destination server's hostname, `false` otherwise.
     *
     * @return void
     */

    public function setHostVerification ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bHostVerification = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the username and the password to be sent along with a request.
     *
     * @param  string $sUser The username.
     * @param  string $sPassword The password.
     *
     * @return void
     */

    public function setUserAndPassword ($sUser, $sPassword)
    {
        assert( 'is_cstring($sUser) && is_cstring($sPassword)', vs(isset($this), get_defined_vars()) );
        $this->m_sUserAndPassword = "$sUser:$sPassword";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to keep sending the username and password to any URL to which a request may be redirected,
     * regardless of hostname changes.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $bEnable `true` to keep sending the username and password when being redirected, `false` otherwise.
     *
     * @return void
     */

    public function setRedirectionKeepAuth ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_bRedirectionKeepAuth = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the options for an FTP request.
     *
     * @param  bool $bCreateMissingDirectoriesForUpload Tells whether to try creating missing directories when
     * uploading a file.
     * @param  bool $bAppendUpload **OPTIONAL. Default is** `false`. Tells whether the data from the file being
     * uploaded should be appended to the data of the existing file, if any.
     * @param  array $aQuoteCommands **OPTIONAL. Default is** *none*. The (S)FTP commands to be run before the transfer.
     * @param  array $aPostQuoteCommands **OPTIONAL. Default is** *none*. The (S)FTP commands to be run after the
     * transfer.
     * @param  bool $bUseEpsv **OPTIONAL. Default is** `true`. Tells whether to use EPSV.
     * @param  string $sActiveModeBackAddress **OPTIONAL. Default is** *cURL's default*. The client-side address to
     * which the destination server should try connecting back.
     * @param  bool $bUseEprt **OPTIONAL. Default is** `true`. Tells whether to use EPRT.
     *
     * @return void
     */

    public function setFtpOptions ($bCreateMissingDirectoriesForUpload, $bAppendUpload = false, $aQuoteCommands = null,
        $aPostQuoteCommands = null, $bUseEpsv = true, $sActiveModeBackAddress = null, $bUseEprt = true)
    {
        assert( 'is_bool($bCreateMissingDirectoriesForUpload) && is_bool($bAppendUpload) && ' .
                '(!isset($aQuoteCommands) || is_carray($aQuoteCommands)) && (!isset($aPostQuoteCommands) || ' .
                'is_carray($aPostQuoteCommands)) && is_bool($bUseEpsv) && (!isset($sActiveModeBackAddress) || ' .
                'is_cstring($sActiveModeBackAddress)) && is_bool($bUseEprt)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isFtp()', vs(isset($this), get_defined_vars()) );
        if ( CDebug::isDebugModeOn() )
        {
            if ( isset($aQuoteCommands) )
            {
                $iLen = CArray::length($aQuoteCommands);
                for ($i = 0; $i < $iLen; $i++)
                {
                    assert( 'is_cstring($aQuoteCommands[$i])', vs(isset($this), get_defined_vars()) );
                }
            }
            if ( isset($aPostQuoteCommands) )
            {
                $iLen = CArray::length($aPostQuoteCommands);
                for ($i = 0; $i < $iLen; $i++)
                {
                    assert( 'is_cstring($aPostQuoteCommands[$i])', vs(isset($this), get_defined_vars()) );
                }
            }
        }

        $this->m_bFtpCreateMissingDirectoriesForUpload = $bCreateMissingDirectoriesForUpload;
        $this->m_bFtpAppendUpload = $bAppendUpload;
        $this->m_aFtpQuoteCommands = $aQuoteCommands;
        $this->m_aFtpPostQuoteCommands = $aPostQuoteCommands;
        $this->m_bFtpUseEpsv = $bUseEpsv;
        $this->m_sFtpActiveModeBackAddress = $sActiveModeBackAddress;
        $this->m_bFtpUseEprt = $bUseEprt;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the SSL options for a request.
     *
     * @param  string $sCertificateFp The path to the certificate's file.
     * @param  string $sPrivateKeyFp **OPTIONAL. Default is** *none*. The path to the private key's file.
     * @param  string $sCertificateFormat **OPTIONAL. Default is** "PEM". The format used by the certificate.
     * @param  string $sPrivateKeyFormat **OPTIONAL. Default is** "PEM". The format used by the private key.
     * @param  string $sCertificatePassphrase **OPTIONAL. Default is** *none*. The passphrase used by the certificate.
     * @param  string $sPrivateKeyPassphrase **OPTIONAL. Default is** *none*. The passphrase used by the private key.
     * @param  int $iSslVersion **OPTIONAL. Default is** *cURL's default*. The SSL version to be used in the
     * communication.
     * @param  string $sCipherList **OPTIONAL. Default is** *cURL's default*. The ciphers to be used.
     * @param  string $sEngine **OPTIONAL. Default is** *cURL's default*. The SSL engine identifier.
     * @param  string $sDefaultEngine **OPTIONAL. Default is** *cURL's default*. The identifier of the actual crypto
     * engine to be used as the default engine for (asymmetric) crypto operations.
     *
     * @return void
     */

    public function setSslOptions ($sCertificateFp, $sPrivateKeyFp = null, $sCertificateFormat = "PEM",
        $sPrivateKeyFormat = "PEM", $sCertificatePassphrase = null, $sPrivateKeyPassphrase = null, $iSslVersion = null,
        $sCipherList = null, $sEngine = null, $sDefaultEngine = null)
    {
        assert( 'is_cstring($sCertificateFp) && (!isset($sPrivateKeyFp) || is_cstring($sPrivateKeyFp)) && ' .
                'is_cstring($sCertificateFormat) && is_cstring($sPrivateKeyFormat) && ' .
                '(!isset($sCertificatePassphrase) || is_cstring($sCertificatePassphrase)) && ' .
                '(!isset($sPrivateKeyPassphrase) || is_cstring($sPrivateKeyPassphrase)) && ' .
                '(!isset($iSslVersion) || is_int($iSslVersion)) && ' .
                '(!isset($sCipherList) || is_cstring($sCipherList)) && (!isset($sEngine) || is_cstring($sEngine)) && ' .
                '(!isset($sDefaultEngine) || is_cstring($sDefaultEngine))', vs(isset($this), get_defined_vars()) );
        assert( 'CFilePath::isAbsolute($sCertificateFp)', vs(isset($this), get_defined_vars()) );
        assert( '!isset($sPrivateKeyFp) || CFilePath::isAbsolute($sPrivateKeyFp)',
            vs(isset($this), get_defined_vars()) );

        $this->m_sSslCertificateFp = CFilePath::frameworkPath($sCertificateFp);
        $this->m_sSslPrivateKeyFp = CFilePath::frameworkPath($sPrivateKeyFp);
        $this->m_sSslCertificateFormat = $sCertificateFormat;
        $this->m_sSslPrivateKeyFormat = $sPrivateKeyFormat;
        $this->m_sSslCertificatePassphrase = $sCertificatePassphrase;
        $this->m_sSslPrivateKeyPassphrase = $sPrivateKeyPassphrase;
        $this->m_iSslVersion = $iSslVersion;
        $this->m_sSslCipherList = $sCipherList;
        $this->m_sSslEngine = $sEngine;
        $this->m_sSslDefaultEngine = $sDefaultEngine;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables Kerberos security protocol and, if enabled, sets the Kerberos security level.
     *
     * @param  bool $bEnable `true` to enable the use of the Kerberos security protocol, `false` otherwise.
     * @param  string $sLevel **OPTIONAL. Default is** *cURL's default*. The Kerberos security level to be used.
     *
     * @return void
     */

    public function setKerberos ($bEnable, $sLevel = null)
    {
        assert( 'is_bool($bEnable) && (!isset($sLevel) || is_cstring($sLevel))', vs(isset($this), get_defined_vars()) );
        assert( '!(!$bEnable && isset($sLevel))', vs(isset($this), get_defined_vars()) );

        $this->m_bUseKerberos = $bEnable;
        if ( $bEnable )
        {
            if ( !isset($sLevel) )
            {
                // Will make it be the default level.
                $sLevel = "";
            }
            $this->m_sKerberosLevel = $sLevel;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets proxy options for a request.
     *
     * @param  string $sProxyAddress The address of the proxy to be used.
     * @param  string $sUser The username for the proxy.
     * @param  string $sPassword The password for the proxy.
     * @param  enum $eProxyType **OPTIONAL. Default is** `PROXY_SOCKS_5`. The type of the proxy
     * (see [Summary](#summary)).
     * @param  int $iProxyPort **OPTIONAL. Default is** *determine automatically*. The proxy's port number.
     * @param  bool $bProxyTunneling **OPTIONAL. Default is** `false`. Tells whether proxy tunneling should be used.
     * @param  bool $bConnectOnly **OPTIONAL. Default is** `false`. Tells whether to disconnect from the proxy right
     * after successfully making a connection.
     *
     * @return void
     */

    public function setProxy ($sProxyAddress, $sUser, $sPassword, $eProxyType = self::PROXY_SOCKS_5, $iProxyPort = null,
        $bProxyTunneling = false, $bConnectOnly = false)
    {
        assert( 'is_cstring($sProxyAddress) && is_cstring($sUser) && is_cstring($sPassword) && ' .
                'is_enum($eProxyType) && (!isset($iProxyPort) || is_int($iProxyPort)) && ' .
                'is_bool($bProxyTunneling) && is_bool($bConnectOnly)',
            vs(isset($this), get_defined_vars()) );

        $this->m_sProxyAddress = $sProxyAddress;
        $this->m_sProxyUserAndPassword = "$sUser:$sPassword";
        $this->m_eProxyType = $eProxyType;
        $this->m_iProxyPort = $iProxyPort;
        $this->m_bProxyTunneling = $bProxyTunneling;
        $this->m_bProxyConnectOnly = $bConnectOnly;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the name of the outgoing network interface to be used for a request.
     *
     * @param  string $sInterface The name of the outgoing network interface. This can be an interface name as well as
     * an IP address or a hostname.
     *
     * @return void
     */

    public function setOutgoingInterface ($sInterface)
    {
        assert( 'is_cstring($sInterface)', vs(isset($this), get_defined_vars()) );
        $this->m_sOutgoingInterface = $sInterface;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables the echoing of the response into the standard output by cURL.
     *
     * @param  bool $bEnable `true` to enable the echoing of the response, `false` otherwise.
     *
     * @return void
     */

    public function setEchoResponse ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bEchoResponse = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables cURL's verbose output for a request.
     *
     * @param  bool $bEnable `true` to enable cURL's verbose output, `false` otherwise.
     *
     * @return void
     */

    public function setVerboseOutput ($bEnable)
    {
        assert( 'is_bool($bEnable)', vs(isset($this), get_defined_vars()) );
        $this->m_bVerboseOutput = $bEnable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the download speed limit for a request.
     *
     * @param  int $iBytesPerSecond The download speed limit, in bytes per second.
     *
     * @return void
     */

    public function setMaxDownloadSpeed ($iBytesPerSecond)
    {
        assert( 'is_int($iBytesPerSecond)', vs(isset($this), get_defined_vars()) );
        assert( '$iBytesPerSecond > 0', vs(isset($this), get_defined_vars()) );

        $this->m_iMaxDownloadSpeed = $iBytesPerSecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the upload speed limit for a request.
     *
     * @param  int $iBytesPerSecond The upload speed limit, in bytes per second.
     *
     * @return void
     */

    public function setMaxUploadSpeed ($iBytesPerSecond)
    {
        assert( 'is_int($iBytesPerSecond)', vs(isset($this), get_defined_vars()) );
        assert( '$iBytesPerSecond > 0', vs(isset($this), get_defined_vars()) );

        $this->m_iMaxUploadSpeed = $iBytesPerSecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sends a request out and returns the response after it is received.
     *
     * @param  reference $rbSuccess **OUTPUT.** After the method is called, the value of this parameter tells whether
     * the request was successful.
     *
     * @return CUStringObject The response that was received for the request.
     */

    public function send (&$rbSuccess)
    {
        $rbSuccess = true;

        if ( $this->m_bHasError )
        {
            // Already finalized.
            $rbSuccess = false;
            return "";
        }

        // Set options for the cURL handle.
        $this->setInternalOptions($rbSuccess);
        if ( !$rbSuccess )
        {
            // Already finalized.
            return "";
        }

        // Disable the script's execution timeout before sending the request.
        $oTimeoutPause = new CTimeoutPause();

        // Send the request out and wait for a response.
        $xRes = curl_exec($this->m_rcCurl);

        // Set the script's execution time limit like the request has never happened.
        $oTimeoutPause->end();

        // According to some testimonials, when trying to retrieve a resource that turns out to be empty, `curl_exec`
        // function could return a `true` instead of an empty string.
        if ( $xRes === true && !$this->m_bEchoResponse && CString::isEmpty(curl_error($this->m_rcCurl)) )
        {
            $xRes = "";
        }
        else if ( $xRes === false || (!$this->m_bEchoResponse && !is_cstring($xRes)) ||
                  !CString::isEmpty(curl_error($this->m_rcCurl)) )
        {
            // An error occurred.
            $this->m_bHasError = true;
            $sCurlError = curl_error($this->m_rcCurl);
            $this->m_sErrorMessage = ( !CString::isEmpty($sCurlError) ) ? $sCurlError :
                "The 'curl_exec' function failed.";
            $rbSuccess = false;
            $this->finalize();
            return "";
        }

        // Collect summary information for the request and the response and finalize.
        $this->onRequestCompleteOk();

        return ( !$this->m_bEchoResponse ) ? $xRes : "";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the HTTP status code of the response.
     *
     * This method can be called for HTTP requests only.
     *
     * @return int The HTTP status code of the response.
     */

    public function responseCode ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "http_code";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_int($xValue) ) ? $xValue : CString::toInt($xValue);
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the response includes a specified HTTP header.
     *
     * This method can be called for HTTP requests only.
     *
     * @param  string $sHeaderName The name of the header.
     *
     * @return bool `true` if the response includes a header with the name specified, `false` otherwise.
     */

    public function responseHasHeader ($sHeaderName)
    {
        assert( 'is_cstring($sHeaderName)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sHeaderNameLc = CString::toLowerCase($sHeaderName);
        return CMap::hasKey($this->m_mResponseHeadersLcKeys, $sHeaderNameLc);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified HTTP header in the response.
     *
     * This method can be called for HTTP requests only.
     *
     * @param  string $sHeaderName The name of the header.
     *
     * @return CUStringObject The value of the header.
     */

    public function responseHeader ($sHeaderName)
    {
        assert( 'is_cstring($sHeaderName)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );
        assert( '$this->responseHasHeader($sHeaderName)', vs(isset($this), get_defined_vars()) );

        $sHeaderNameLc = CString::toLowerCase($sHeaderName);
        return $this->m_mResponseHeadersLcKeys[$sHeaderNameLc];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the response includes a "Date" HTTP header.
     *
     * This method can be called for HTTP requests only.
     *
     * @return bool `true` if the response includes a "Date" HTTP header, `false` otherwise.
     */

    public function responseHasTimestamp ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );
        return $this->responseHasHeader(CHttpResponse::DATE);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the timestamp of the response, according to the value of the "Date" HTTP header.
     *
     * This method can be called for HTTP requests only.
     *
     * @return CTime The timestamp of the response, according to the value of the "Date" HTTP header.
     */

    public function responseTimestamp ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );
        assert( '$this->responseHasTimestamp()', vs(isset($this), get_defined_vars()) );

        $sTimestamp = $this->responseHeader(CHttpResponse::DATE);
        return CTime::fromString($sTimestamp);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the response includes a "Last-Modified" HTTP header.
     *
     * This method can be called for HTTP requests only.
     *
     * @return bool `true` if the response includes a "Last-Modified" HTTP header, `false` otherwise.
     */

    public function responseHasModificationTime ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );
        return $this->responseHasHeader(CHttpResponse::LAST_MODIFIED);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the point in time when the response's entity was last modified, according to the value of the
     * "Last-Modified" HTTP header.
     *
     * This method can be called for HTTP requests only.
     *
     * @return CTime The point in time when the response's entity was last modified, according to the value of the
     * "Last-Modified" HTTP header.
     */

    public function responseModificationTime ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );
        assert( '$this->responseHasModificationTime()', vs(isset($this), get_defined_vars()) );

        $sModificationTime = $this->responseHeader(CHttpResponse::LAST_MODIFIED);
        return CTime::fromString($sModificationTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the total number of bytes received with the response.
     *
     * @return int The total number of bytes received.
     */

    public function responseDownloadSize ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "size_download";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (int)$xValue : CString::toInt($xValue);
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the average download speed at which the response was received.
     *
     * @return float The average download speed, in bytes per second.
     */

    public function responseDownloadSpeed ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "speed_download";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (float)$xValue : CString::toFloat($xValue);
        }
        else
        {
            return 0.0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the total size of all HTTP headers received.
     *
     * This method can be called for HTTP requests only.
     *
     * @return int The total size of all HTTP headers received, in bytes.
     */

    public function responseHeadersSize ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "header_size";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_int($xValue) || is_float($xValue) ) ? (int)$xValue : CString::toInt($xValue);
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the certificate information associated with the response.
     *
     * @return CMapObject The certificate information associated with the response.
     */

    public function responseCertificateInfo ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "certinfo";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            return oop_x($this->m_mRequestSummary[$sKey]);
        }
        else
        {
            return oop_m(CMap::make());
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the total size of the sent request.
     *
     * @return int The total size of the sent request, in bytes.
     */

    public function requestSize ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "request_size";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_int($xValue) || is_float($xValue) ) ? (int)$xValue : CString::toInt($xValue);
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the total size of data uploaded with the request, excluding headers.
     *
     * @return int The total size of the uploaded data, in bytes, excluding headers.
     */

    public function requestUploadSize ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "size_upload";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (int)$xValue : CString::toInt($xValue);
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the average speed at which the request was uploaded.
     *
     * @return float The average upload speed, in bytes per second.
     */

    public function requestUploadSpeed ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "speed_upload";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (float)$xValue : CString::toFloat($xValue);
        }
        else
        {
            return 0.0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the size of the uploaded data as it was declared in the "Content-Length" HTTP header.
     *
     * This method can be called for HTTP requests only.
     *
     * @return int The size of the uploaded data as it was declared in the "Content-Length" HTTP header, in bytes.
     */

    public function requestUploadContentLength ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "upload_content_length";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (int)$xValue : CString::toInt($xValue);
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the total transaction time of the request.
     *
     * @return float The total transaction time of the request, in seconds.
     */

    public function requestTotalTime ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "total_time";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (float)$xValue : CString::toFloat($xValue);
        }
        else
        {
            return 0.0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of time spent on domain name resolution.
     *
     * @return float The amount of time spent on domain name resolution, in seconds.
     */

    public function requestDnsLookupTime ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "namelookup_time";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (float)$xValue : CString::toFloat($xValue);
        }
        else
        {
            return 0.0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of time spent on connection establishment.
     *
     * @return float The amount of time spent on connection establishment, in seconds.
     */

    public function requestConnectionEstablishmentTime ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "connect_time";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (float)$xValue : CString::toFloat($xValue);
        }
        else
        {
            return 0.0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of time spent on all redirections.
     *
     * This method can be called for HTTP requests only.
     *
     * @return float The amount of time spent on all redirections, in seconds.
     */

    public function requestRedirectionTime ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "redirect_time";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (float)$xValue : CString::toFloat($xValue);
        }
        else
        {
            return 0.0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of time that elapsed since the very start and up to the moment when the transfer began.
     *
     * @return float The amount of time that elapsed until the transfer began, in seconds.
     */

    public function requestPreTransferTime ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "pretransfer_time";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (float)$xValue : CString::toFloat($xValue);
        }
        else
        {
            return 0.0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the amount of time that elapsed since the very start and up to the moment when the first byte was sent
     * out.
     *
     * @return float The amount of time that elapsed until the first byte was sent out, in seconds.
     */

    public function requestStartTransferTime ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "starttransfer_time";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_float($xValue) || is_int($xValue) ) ? (float)$xValue : CString::toFloat($xValue);
        }
        else
        {
            return 0.0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the number of redirections that took place while processing the request.
     *
     * This method can be called for HTTP requests only.
     *
     * @return int The number of redirections that took place.
     */

    public function requestNumRedirections ()
    {
        assert( '$this->isHttp() && $this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "redirect_count";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            return ( is_int($xValue) || is_float($xValue) ) ? (int)$xValue : CString::toInt($xValue);
        }
        else
        {
            return 0;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the result of the verification of the SSL certificate of the remote server.
     *
     * @return bool `true` if the SSL certificate was verified successfully, `false` otherwise.
     */

    public function requestSslVerificationResult ()
    {
        assert( '$this->m_bDone && !$this->m_bHasError', vs(isset($this), get_defined_vars()) );

        $sKey = "ssl_verify_result";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            $xValue = $this->m_mRequestSummary[$sKey];
            if ( !is_bool($xValue) )
            {
                if ( !is_cstring($xValue) )
                {
                    $xValue = CString::fromInt($xValue);
                }
                return CString::toBool($xValue);
            }
            else
            {
                return $xValue;
            }
        }
        else
        {
            return false;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the HTTP request line and the headers that were sent out with the request.
     *
     * @return CUStringObject The HTTP request line and the headers that were sent out with the request.
     */

    public function requestHeadersString ()
    {
        assert( '$this->isHttp() && $this->m_bDone', vs(isset($this), get_defined_vars()) );

        $sKey = "request_header";
        if ( CMap::hasKey($this->m_mRequestSummary, $sKey) )
        {
            return $this->m_mRequestSummary[$sKey];
        }
        else
        {
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes a request to a specified URL and saves the received response to a file.
     *
     * @param  string $sUrl The URL to which the request is to be sent.
     * @param  string $sDestinationFp The destination file path to which the response should be saved.
     * @param  int $iTimeoutSeconds **OPTIONAL. Default is** *no timeout*. The number of seconds after which the
     * request should time out.
     *
     * @return bool `true` if the request was successful, `false` otherwise.
     */

    public static function downloadFile ($sUrl, $sDestinationFp, $iTimeoutSeconds = null)
    {
        assert( 'is_cstring($sUrl) && is_cstring($sDestinationFp) && ' .
                '(!isset($iTimeoutSeconds) || is_int($iTimeoutSeconds))', vs(isset($this), get_defined_vars()) );

        $oInetRequest = new self($sUrl, self::ANY_DOWNLOAD, $sDestinationFp);
        if ( isset($iTimeoutSeconds) )
        {
            $oInetRequest->setRequestTimeout($iTimeoutSeconds);
        }
        $bSuccess;
        $oInetRequest->send($bSuccess);
        return $bSuccess;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes a request to a specified URL and returns the response.
     *
     * @param  string $sUrl The URL to which the request is to be sent.
     * @param  reference $rbSuccess **OPTIONAL. OUTPUT.** After the method is called, the value of this parameter tells
     * whether the request was successful.
     * @param  int $iTimeoutSeconds **OPTIONAL. Default is** *no timeout*. The number of seconds after which the
     * request should time out.
     *
     * @return CUStringObject The response that was received for the request.
     */

    public static function read ($sUrl, &$rbSuccess = null, $iTimeoutSeconds = null)
    {
        assert( 'is_cstring($sUrl) && (!isset($iTimeoutSeconds) || is_int($iTimeoutSeconds))',
            vs(isset($this), get_defined_vars()) );

        $oInetRequest = new self($sUrl);
        if ( isset($iTimeoutSeconds) )
        {
            $oInetRequest->setRequestTimeout($iTimeoutSeconds);
        }
        $sContent = $oInetRequest->send($rbSuccess);
        if ( !$rbSuccess )
        {
            $sContent = "";
        }
        return $sContent;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the cURL library used, as a string.
     *
     * @return CUStringObject The cURL's version.
     */

    public static function curlVersion ()
    {
        $mVerInfo = curl_version();
        return $mVerInfo["version"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the cURL library used, as an integer number.
     *
     * @return int The cURL's version.
     */

    public static function curlVersionInt ()
    {
        $mVerInfo = curl_version();
        return $mVerInfo["version_number"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the SSL security layer used, as a string.
     *
     * @return CUStringObject The SSL's version.
     */

    public static function openSslVersion ()
    {
        $mVerInfo = curl_version();
        return $mVerInfo["ssl_version"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the SSL security layer used, as an integer number.
     *
     * @return int The SSL's version.
     */

    public static function openSslVersionInt ()
    {
        $mVerInfo = curl_version();
        return $mVerInfo["ssl_version_number"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function isHttp ()
    {
        return CString::equals($this->m_sRequestBasicProtocol, "http");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function isFtp ()
    {
        return CString::equals($this->m_sRequestBasicProtocol, "ftp");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function setInternalOptions (&$rbSuccess, $sCookiesFp = null, $bNewCookieSession = null)
    {
        $rbSuccess = true;

        if ( $this->m_bHasError )
        {
            $rbSuccess = false;
            $this->finalize();
            return;
        }

        $mOptions = CMap::make();

        // How to deal with the response.
        if ( !$this->m_bEchoResponse )
        {
            $mOptions[CURLOPT_RETURNTRANSFER] = true;
            $this->m_bIsReturnTransferSet = true;
        }
        else
        {
            $mOptions[CURLOPT_RETURNTRANSFER] = false;
            $this->m_bIsReturnTransferSet = false;
        }

        if ( isset($this->m_bVerboseOutput) && $this->m_bVerboseOutput )
        {
            $mOptions[CURLOPT_VERBOSE] = true;
        }

        // The destination URL and port.
        $mOptions[CURLOPT_URL] = $this->m_sUrl;
        if ( isset($this->m_iPort) )
        {
            $mOptions[CURLOPT_PORT] = $this->m_iPort;
        }

        // Avoid response caching and reuse, which might happen because of any unconventional caching strategies used
        // by cURL.
        $mOptions[CURLOPT_FORBID_REUSE] = true;
        $mOptions[CURLOPT_FRESH_CONNECT] = true;

        if ( $this->m_eType != self::HTTP_HEAD )
        {
            $mOptions[CURLOPT_HEADER] = false;
        }

        if ( $this->isHttp() )
        {
            if ( $this->m_eType == self::HTTP_GET )
            {
                $mOptions[CURLOPT_HTTPGET] = true;
            }
            else if ( $this->m_eType == self::HTTP_DOWNLOAD ||
                      $this->m_eType == self::ANY_DOWNLOAD )
            {
                $mOptions[CURLOPT_HTTPGET] = true;
                assert( 'isset($this->m_sDownloadDestinationFp)', vs(isset($this), get_defined_vars()) );
                $this->m_oDownloadFile = new CFile($this->m_sDownloadDestinationFp, CFile::WRITE_NEW);
                $mOptions[CURLOPT_FILE] = $this->m_oDownloadFile->systemResource();
            }
            else if ( $this->m_eType == self::HTTP_POST )
            {
                // POST.
                $mOptions[CURLOPT_POST] = true;

                // At least one POST variable needs to be set in order to make a POST request.
                assert( 'isset($this->m_oPostQuery)', vs(isset($this), get_defined_vars()) );

                // POST variables use the same format as the query string (application/x-www-form-urlencoded).
                $mOptions[CURLOPT_POSTFIELDS] = $this->m_oPostQuery->queryString();
            }
            else if ( $this->m_eType == self::HTTP_UPLOAD )
            {
                // File upload via POST and using the CURLFile class.
                $mOptions[CURLOPT_POST] = true;
                assert( 'isset($this->m_mPostFileUploadRecord)', vs(isset($this), get_defined_vars()) );
                $mOptions[CURLOPT_POSTFIELDS] = $this->m_mPostFileUploadRecord;
            }
            else if ( $this->m_eType == self::HTTP_PUT )
            {
                // PUT.
                assert( 'isset($this->m_sNonPostFileUploadFp)', vs(isset($this), get_defined_vars()) );
                $mOptions[CURLOPT_PUT] = true;
                $this->m_oUploadFile = new CFile($this->m_sNonPostFileUploadFp, CFile::READ);
                $mOptions[CURLOPT_INFILE] = $this->m_oUploadFile->systemResource();
                $mOptions[CURLOPT_INFILESIZE] = CFile::size($this->m_sNonPostFileUploadFp);
            }
            else if ( $this->m_eType == self::HTTP_DELETE )
            {
                // DELETE.
                $mOptions[CURLOPT_CUSTOMREQUEST] = "DELETE";
            }
            else if ( $this->m_eType == self::HTTP_HEAD )
            {
                // HEAD.
                $mOptions[CURLOPT_HEADER] = true;
                $mOptions[CURLOPT_NOBODY] = true;
            }

            // HTTP redirections.
            $mOptions[CURLOPT_FOLLOWLOCATION] = $this->m_bRedirection;
            if ( $this->m_bRedirection )
            {
                if ( isset($this->m_iMaxNumRedirections) )
                {
                    $mOptions[CURLOPT_MAXREDIRS] = $this->m_iMaxNumRedirections;
                }
                if ( isset($this->m_bRedirectionAutoReferer) )
                {
                    $mOptions[CURLOPT_AUTOREFERER] = $this->m_bRedirectionAutoReferer;
                }
                if ( isset($this->m_bRedirectionKeepAuth) )
                {
                    $mOptions[CURLOPT_UNRESTRICTED_AUTH] = $this->m_bRedirectionKeepAuth;
                }
            }

            // HTTP response code treatment.
            $mOptions[CURLOPT_FAILONERROR] = $this->m_bFailOn400ResponseCodeOrGreater;

            // HTTP headers.
            if ( $this->m_bSendDefaultAcceptEncodingHeader &&
                 !(isset($this->m_aRequestHeaders) && $this->hasHeader(CHttpRequest::ACCEPT_ENCODING)) )
            {
                $mOptions[CURLOPT_ENCODING] = "";
            }
            if ( $this->m_bSendDefaultUserAgentHeader &&
                 !(isset($this->m_aRequestHeaders) && $this->hasHeader(CHttpRequest::USER_AGENT)) )
            {
                $sUserAgent = self::$ms_sDefaultUserAgent;
                $sCurlVersion = self::curlVersion();
                $sSslVersion = self::openSslVersion();
                $sSslVersion = CRegex::remove($sSslVersion, "/OpenSSL(\\/|\\h+)/i");
                $sUserAgent = CString::replaceCi($sUserAgent, "curl/x.x.x", "curl/$sCurlVersion");
                $sUserAgent = CString::replaceCi($sUserAgent, "libcurl x.x.x", "libcurl $sCurlVersion");
                $sUserAgent = CString::replaceCi($sUserAgent, "OpenSSL x.x.x", "OpenSSL $sSslVersion");
                $this->addHeader(CHttpRequest::USER_AGENT, $sUserAgent);
            }
            if ( isset($this->m_aRequestHeaders) && !CArray::isEmpty($this->m_aRequestHeaders) )
            {
                $mOptions[CURLOPT_HTTPHEADER] = CArray::toPArray($this->m_aRequestHeaders);
            }

            if ( isset($this->m_aRequestCookies) && !CArray::isEmpty($this->m_aRequestCookies) )
            {
                // Custom HTTP cookies.
                $sCookieHeaderValue = CArray::join($this->m_aRequestCookies, "; ");
                $mOptions[CURLOPT_COOKIE] = $sCookieHeaderValue;
            }
            if ( isset($sCookiesFp) )
            {
                $mOptions[CURLOPT_COOKIEFILE] = $sCookiesFp;
                $mOptions[CURLOPT_COOKIEJAR] = $sCookiesFp;
            }
            if ( isset($bNewCookieSession) && $bNewCookieSession )
            {
                $mOptions[CURLOPT_COOKIESESSION] = true;
            }

            // Needed for the retrieval of information regarding the data transfer after it is complete.
            $mOptions[CURLINFO_HEADER_OUT] = true;

            // Needed for the retrieval of response headers.
            $mOptions[CURLOPT_HEADERFUNCTION] = [$this, "headerFunction"];

            if ( isset($this->m_sUserAndPassword) )
            {
                // HTTP authentication. Let cURL pick any authentication method it finds suitable (it will
                // automatically select the one it finds most secure).
                $mOptions[CURLOPT_HTTPAUTH] = CURLAUTH_ANY;
            }
        }
        else  // FTP
        {
            if ( $this->m_eType == self::FTP_LIST )
            {
                $mOptions[CURLOPT_FTPLISTONLY] = true;
            }
            else if ( $this->m_eType == self::FTP_DOWNLOAD ||
                      $this->m_eType == self::ANY_DOWNLOAD )
            {
                assert( 'isset($this->m_sDownloadDestinationFp)', vs(isset($this), get_defined_vars()) );
                $this->m_oDownloadFile = new CFile($this->m_sDownloadDestinationFp, CFile::WRITE_NEW);
                $mOptions[CURLOPT_FILE] = $this->m_oDownloadFile->systemResource();
            }
            else if ( $this->m_eType == self::FTP_UPLOAD )
            {
                // File upload via FTP.

                assert( 'isset($this->m_sNonPostFileUploadFp)', vs(isset($this), get_defined_vars()) );
                $this->m_oUploadFile = new CFile($this->m_sNonPostFileUploadFp, CFile::READ);
                $mOptions[CURLOPT_UPLOAD] = true;
                $mOptions[CURLOPT_INFILE] = $this->m_oUploadFile->systemResource();
                $mOptions[CURLOPT_INFILESIZE] = CFile::size($this->m_sNonPostFileUploadFp);

                if ( $this->m_bFtpCreateMissingDirectoriesForUpload )
                {
                    $mOptions[CURLOPT_FTP_CREATE_MISSING_DIRS] = true;
                }

                if ( isset($this->m_bFtpAppendUpload) && $this->m_bFtpAppendUpload )
                {
                    $mOptions[CURLOPT_FTPAPPEND] = true;
                }
            }

            if ( isset($this->m_aFtpQuoteCommands) && !CArray::isEmpty($this->m_aFtpQuoteCommands) )
            {
                $mOptions[CURLOPT_QUOTE] = CArray::toPArray($this->m_aFtpQuoteCommands);
            }
            if ( isset($this->m_aFtpPostQuoteCommands) && !CArray::isEmpty($this->m_aFtpPostQuoteCommands) )
            {
                $mOptions[CURLOPT_POSTQUOTE] = CArray::toPArray($this->m_aFtpPostQuoteCommands);
            }

            if ( isset($this->m_bFtpUseEpsv) && !$this->m_bFtpUseEpsv )
            {
                $mOptions[CURLOPT_FTP_USE_EPSV] = false;
            }

            if ( isset($this->m_sFtpActiveModeBackAddress) )
            {
                $mOptions[CURLOPT_FTPPORT] = $this->m_sFtpActiveModeBackAddress;
            }

            if ( isset($this->m_bFtpUseEprt) && !$this->m_bFtpUseEprt )
            {
                $mOptions[CURLOPT_FTP_USE_EPRT] = false;
            }
        }

        // Timeouts.
        if ( isset($this->m_iRequestTimeoutSeconds) )
        {
            $mOptions[CURLOPT_TIMEOUT] = $this->m_iRequestTimeoutSeconds;
        }
        if ( isset($this->m_iConnectionTimeoutSeconds) )
        {
            $mOptions[CURLOPT_CONNECTTIMEOUT] = $this->m_iConnectionTimeoutSeconds;
        }
        if ( isset($this->m_iDnsCacheTimeoutSeconds) )
        {
            $mOptions[CURLOPT_DNS_CACHE_TIMEOUT] = $this->m_iDnsCacheTimeoutSeconds;
        }

        // The byte range(s) of interest.
        if ( isset($this->m_sRequestedByteRange) )
        {
            $mOptions[CURLOPT_RANGE] = $this->m_sRequestedByteRange;
        }

        // SSL certificate verification options.
        $mOptions[CURLOPT_SSL_VERIFYPEER] = $this->m_bCertificateVerification;
        if ( isset($this->m_sAlternateCertificateFpOrDp) )
        {
            if ( CFile::isFile($this->m_sAlternateCertificateFpOrDp) )
            {
                $mOptions[CURLOPT_CAINFO] = $this->m_sAlternateCertificateFpOrDp;
            }
            else if ( CFile::isDirectory($this->m_sAlternateCertificateFpOrDp) )
            {
                $mOptions[CURLOPT_CAPATH] = $this->m_sAlternateCertificateFpOrDp;
            }
            else
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
            }
        }
        if ( !$this->m_bHostVerification )
        {
            // The default should be the highest setting, so only set this option to `0` if host verification is
            // disabled.
            $mOptions[CURLOPT_SSL_VERIFYHOST] = 0;
        }

        if ( isset($this->m_sUserAndPassword) )
        {
            $mOptions[CURLOPT_USERPWD] = $this->m_sUserAndPassword;
        }

        // SSL options.
        if ( isset($this->m_sSslCertificateFp) )
        {
            $mOptions[CURLOPT_SSLCERT] = $this->m_sSslCertificateFp;
        }
        if ( isset($this->m_sSslPrivateKeyFp) )
        {
            $mOptions[CURLOPT_SSLKEY] = $this->m_sSslPrivateKeyFp;
        }
        if ( isset($this->m_sSslCertificateFormat) )
        {
            $mOptions[CURLOPT_SSLCERTTYPE] = $this->m_sSslCertificateFormat;
        }
        if ( isset($this->m_sSslPrivateKeyFormat) )
        {
            $mOptions[CURLOPT_SSLKEYTYPE] = $this->m_sSslPrivateKeyFormat;
        }
        if ( isset($this->m_sSslCertificatePassphrase) )
        {
            $mOptions[CURLOPT_SSLCERTPASSWD] = $this->m_sSslCertificatePassphrase;
        }
        if ( isset($this->m_sSslPrivateKeyPassphrase) )
        {
            $mOptions[CURLOPT_SSLKEYPASSWD] = $this->m_sSslPrivateKeyPassphrase;
        }
        if ( isset($this->m_iSslVersion) )
        {
            $mOptions[CURLOPT_SSLVERSION] = $this->m_iSslVersion;
        }
        if ( isset($this->m_sSslCipherList) )
        {
            $mOptions[CURLOPT_SSL_CIPHER_LIST] = $this->m_sSslCipherList;
        }
        if ( isset($this->m_sSslEngine) )
        {
            $mOptions[CURLOPT_SSLENGINE] = $this->m_sSslEngine;
        }
        if ( isset($this->m_sSslDefaultEngine) )
        {
            $mOptions[CURLOPT_SSLENGINE_DEFAULT] = $this->m_sSslDefaultEngine;
        }

        if ( isset($this->m_bUseKerberos) && $this->m_bUseKerberos )
        {
            $mOptions[CURLOPT_KRBLEVEL] = $this->m_sKerberosLevel;
        }

        if ( isset($this->m_sProxyAddress) )
        {
            // Use a proxy.

            $mOptions[CURLOPT_PROXY] = $this->m_sProxyAddress;

            if ( isset($this->m_sProxyUserAndPassword) )
            {
                $mOptions[CURLOPT_PROXYUSERPWD] = $this->m_sProxyUserAndPassword;
            }

            if ( isset($this->m_eProxyType) )
            {
                $iProxyType;
                switch ( $this->m_eProxyType )
                {
                case self::PROXY_HTTP:
                    $iProxyType = CURLPROXY_HTTP;
                    break;
                case self::PROXY_SOCKS_5:
                    $iProxyType = CURLPROXY_SOCKS5;
                    break;
                default:
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                    break;
                }
                $mOptions[CURLOPT_PROXYTYPE] = $iProxyType;
            }

            if ( isset($this->m_iProxyPort) )
            {
                $mOptions[CURLOPT_PROXYPORT] = $this->m_iProxyPort;
            }

            if ( isset($this->m_bProxyTunneling) && $this->m_bProxyTunneling )
            {
                $mOptions[CURLOPT_HTTPPROXYTUNNEL] = true;
            }

            if ( isset($this->m_bProxyConnectOnly) && $this->m_bProxyConnectOnly )
            {
                $mOptions[CURLOPT_CONNECT_ONLY] = true;
            }
        }

        if ( isset($this->m_sOutgoingInterface) )
        {
            $mOptions[CURLOPT_INTERFACE] = $this->m_sOutgoingInterface;
        }

        // Speed limits.
        if ( isset($this->m_iMaxDownloadSpeed) )
        {
            $mOptions[CURLOPT_MAX_RECV_SPEED_LARGE] = $this->m_iMaxDownloadSpeed;
        }
        if ( isset($this->m_iMaxUploadSpeed) )
        {
            $mOptions[CURLOPT_MAX_SEND_SPEED_LARGE] = $this->m_iMaxUploadSpeed;
        }

        // Set cURL options.
        $bRes = curl_setopt_array($this->m_rcCurl, $mOptions);
        if ( !$bRes || !CString::isEmpty(curl_error($this->m_rcCurl)) )
        {
            // Should never get in here as long as cURL options are being set correctly, hence the assertion.
            assert( 'false', vs(isset($this), get_defined_vars()) );
            $this->m_bHasError = true;
            $sCurlError = curl_error($this->m_rcCurl);
            $this->m_sErrorMessage = ( !CString::isEmpty($sCurlError) ) ? $sCurlError :
                "The 'curl_setopt_array' function failed.";
            $rbSuccess = false;
            $this->finalize();
            return;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function onRequestCompleteOk ()
    {
        // Collect summary information for the request and the response.
        $xRequestSummary = curl_getinfo($this->m_rcCurl);
        if ( is_cmap($xRequestSummary) )
        {
            $this->m_mRequestSummary = $xRequestSummary;
        }

        if ( $this->isHttp() )
        {
            // Put the response's HTTP headers into an associative array.
            $iLen = CArray::length($this->m_aResponseHeaders);
            for ($i = 0; $i < $iLen; $i++)
            {
                $aFoundGroups;
                CRegex::findGroups($this->m_aResponseHeaders[$i], "/^(.+?):\\h*(.*)/", $aFoundGroups);  // internal
                $sHeaderNameLc = CString::toLowerCase($aFoundGroups[0]);
                $this->m_mResponseHeadersLcKeys[$sHeaderNameLc] = $aFoundGroups[1];
            }
        }

        // Finalize.
        $this->finalize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function onRequestCompleteWithError ($sErrorMessage)
    {
        // To be used by "friend" classes only.
        $this->m_bHasError = true;
        $this->m_sErrorMessage = $sErrorMessage;
        $this->finalize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function curl ()
    {
        // To be used by "friend" classes only.
        return $this->m_rcCurl;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function isReturnTransferSet ()
    {
        // To be used by "friend" classes only.
        assert( 'isset($this->m_bIsReturnTransferSet)', vs(isset($this), get_defined_vars()) );
        return $this->m_bIsReturnTransferSet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function headerFunction ($rcCurl, $sHeaderLine)
    {
        $iRet = CString::length($sHeaderLine);
        $aFoundGroups;
        if ( CRegex::findGroups($sHeaderLine, "/^\\s*(.+?)\\h*:\\h*(.*?)\\s*\\z/", $aFoundGroups) )
        {
            $this->addHeaderWithoutOverriding($this->m_aResponseHeaders, $aFoundGroups[0], $aFoundGroups[1]);
        }
        return $iRet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __destruct ()
    {
        if ( !$this->m_bDone )
        {
            $this->finalize();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function addHeaderWithoutOverriding ($aHeaders, $sHeaderName, $sValue)
    {
        $sHeaderLine;
        $sHeaderName = CString::trim($sHeaderName);
        $sValue = CString::trim($sValue);
        $iFoundHeaderPos;
        $bAlreadyExists = CArray::find($aHeaders, $sHeaderName, function ($sElement0, $sElement1)
            {
                return CRegex::find($sElement0, "/^\\h*" . CRegex::enterTd($sElement1) . "\\h*:/i");
            },
            $iFoundHeaderPos);
        if ( !$bAlreadyExists )
        {
            $sHeaderLine = "$sHeaderName: $sValue";
        }
        else
        {
            // The header already exists. Combine the header values, removing duplicates based on case-insensitive
            // equality.
            $sCurrValue = CRegex::remove($aHeaders[$iFoundHeaderPos], "/^.*?:\\h*/");
            CArray::remove($aHeaders, $iFoundHeaderPos);
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
        CArray::push($aHeaders, $sHeaderLine);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function hasHeader ($sHeaderName)
    {
        $sHeaderName = CString::trim($sHeaderName);
        return CArray::find($this->m_aRequestHeaders, $sHeaderName, function ($sElement0, $sElement1)
            {
                return CRegex::find($sElement0, "/^\\h*" . CRegex::enterTd($sElement1) . "\\h*:/i");
            });
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function removeHeader ($sHeaderName)
    {
        $sHeaderName = CString::trim($sHeaderName);
        CArray::removeByValue($this->m_aRequestHeaders, $sHeaderName, function ($sElement0, $sElement1)
            {
                return CRegex::find($sElement0, "/^\\h*" . CRegex::enterTd($sElement1) . "\\h*:/i");
            });
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function finalize ()
    {
        if ( $this->m_bDone )
        {
            return;
        }

        if ( is_resource($this->m_rcCurl) )
        {
            curl_close($this->m_rcCurl);
        }

        if ( isset($this->m_oDownloadFile) )
        {
            $this->m_oDownloadFile->done();
        }

        if ( isset($this->m_oUploadFile) )
        {
            $this->m_oUploadFile->done();
        }

        if ( isset($this->m_sFileUploadTempFp) && CFile::exists($this->m_sFileUploadTempFp) )
        {
            CFile::delete($this->m_sFileUploadTempFp);
        }

        $this->m_bDone = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function basicProtocol ($sProtocol)
    {
        $sProtocol = CString::toLowerCase($sProtocol);
        if ( CString::equals($sProtocol, "https") )
        {
            $sProtocol = "http";
        }
        else if ( CString::equals($sProtocol, "ftps") )
        {
            $sProtocol = "ftp";
        }
        return $sProtocol;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected $m_sUrl;
    protected $m_sRequestBasicProtocol;
    protected $m_eType;
    protected $m_sDownloadDestinationFp;
    protected $m_rcCurl;
    protected $m_bDone = false;
    protected $m_bHasError = false;
    protected $m_sErrorMessage;
    protected $m_iPort;
    protected $m_iRequestTimeoutSeconds;
    protected $m_iConnectionTimeoutSeconds;
    protected $m_iDnsCacheTimeoutSeconds;
    protected $m_bRedirection = true;
    protected $m_iMaxNumRedirections;
    protected $m_bRedirectionAutoReferer = true;
    protected $m_bFailOn400ResponseCodeOrGreater = true;
    protected $m_aRequestHeaders;
    protected $m_oPostQuery;
    protected $m_aRequestCookies;
    protected $m_oDownloadFile;
    protected $m_mPostFileUploadRecord;
    protected $m_sNonPostFileUploadFp;
    protected $m_oUploadFile;
    protected $m_sFileUploadTempFp;
    protected $m_bSendDefaultAcceptEncodingHeader = true;
    protected $m_bSendDefaultUserAgentHeader = false;
    protected $m_sRequestedByteRange;
    protected $m_bCertificateVerification = true;
    protected $m_sAlternateCertificateFpOrDp;
    protected $m_bHostVerification = true;
    protected $m_sUserAndPassword;
    protected $m_bRedirectionKeepAuth = false;
    protected $m_bFtpCreateMissingDirectoriesForUpload = true;
    protected $m_bFtpAppendUpload;
    protected $m_aFtpQuoteCommands;
    protected $m_aFtpPostQuoteCommands;
    protected $m_bFtpUseEpsv;
    protected $m_sFtpActiveModeBackAddress;
    protected $m_bFtpUseEprt;
    protected $m_sSslCertificateFp;
    protected $m_sSslPrivateKeyFp;
    protected $m_sSslCertificateFormat;
    protected $m_sSslPrivateKeyFormat;
    protected $m_sSslCertificatePassphrase;
    protected $m_sSslPrivateKeyPassphrase;
    protected $m_iSslVersion;
    protected $m_sSslCipherList;
    protected $m_sSslEngine;
    protected $m_sSslDefaultEngine;
    protected $m_bUseKerberos;
    protected $m_sKerberosLevel;
    protected $m_sProxyAddress;
    protected $m_sProxyUserAndPassword;
    protected $m_eProxyType;
    protected $m_iProxyPort;
    protected $m_bProxyTunneling;
    protected $m_bProxyConnectOnly;
    protected $m_sOutgoingInterface;
    protected $m_bEchoResponse = false;
    protected $m_bVerboseOutput;
    protected $m_iMaxDownloadSpeed;
    protected $m_iMaxUploadSpeed;
    protected $m_mRequestSummary;
    protected $m_aResponseHeaders;
    protected $m_mResponseHeadersLcKeys;
    protected $m_bIsReturnTransferSet;

    // If the default user agent string is used, each "x.x.x" will be replaced with actual versions of curl/libcurl
    // and OpenSSL.
    protected static $ms_sDefaultUserAgent = "curl/x.x.x (unknown) libcurl x.x.x (OpenSSL x.x.x) (ipv6 enabled)";
}
