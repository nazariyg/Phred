<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
//   __construct ($url, $type = self::HTTP_GET, $downloadDestinationFp = null)
//   CUStringObject errorMessage ()
//   void setPort ($port)
//   void setRequestTimeout ($numSeconds)
//   void setConnectionTimeout ($numSeconds)
//   void removeConnectionTimeout ()
//   void setDnsCacheTimeout ($numSeconds)
//   void setRedirection ($enable)
//   void setMaxNumRedirections ($maxNumRedirections)
//   void setRedirectionAutoReferer ($enable)
//   void setFailOn400ResponseCodeOrGreater ($enable)
//   void addHeader ($headerName, $value)
//   void addHeaderPlural ($headerName, $value)
//   void addPostField ($fieldName, $value)
//   void addCookie ($cookieName, $value)
//   void setUploadFile ($filePath, $mimeType = null, $fileName = null, $fileId = null)
//   void setUploadData ($data, $mimeType = null, $fileName = null, $fileId = null)
//   void setSendDefaultAcceptEncodingHeader ($enable)
//   void setSendDefaultUserAgentHeader ($enable)
//   void setAllow304ResponseCodeIfNotModifiedSince (CTime $time)
//   void setRequestedByteRange ($byteRangeOrRanges)
//   void setCertificateVerification ($enable, $alternateCertificateFpOrDp = null)
//   void setHostVerification ($enable)
//   void setUserAndPassword ($user, $password)
//   void setRedirectionKeepAuth ($enable)
//   void setFtpOptions ($createMissingDirectoriesForUpload, $appendUpload = false, $quoteCommands = null,
//     $postQuoteCommands = null, $useEpsv = true, $activeModeBackAddress = null, $useEprt = true)
//   void setSslOptions ($certificateFp, $privateKeyFp = null, $certificateFormat = "PEM",
//     $privateKeyFormat = "PEM", $certificatePassphrase = null, $privateKeyPassphrase = null, $sslVersion = null,
//     $cipherList = null, $engine = null, $defaultEngine = null)
//   void setKerberos ($enable, $level = null)
//   void setProxy ($proxyAddress, $user, $password, $proxyType = self::PROXY_SOCKS_5, $proxyPort = null,
//     $proxyTunneling = false, $connectOnly = false)
//   void setOutgoingInterface ($interface)
//   void setEchoResponse ($enable)
//   void setVerboseOutput ($enable)
//   void setMaxDownloadSpeed ($bytesPerSecond)
//   void setMaxUploadSpeed ($bytesPerSecond)
//   CUStringObject send (&$success)
//   int responseCode ()
//   bool responseHasHeader ($headerName)
//   CUStringObject responseHeader ($headerName)
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
//   static bool downloadFile ($url, $destinationFp, $timeoutSeconds = null)
//   static CUStringObject read ($url, &$success = null, $timeoutSeconds = null)
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
     * @param  string $url The URL to which the request is to be sent. If no protocol is indicated in the URL, the
     * HTTP protocol is assumed.
     * @param  enum $type **OPTIONAL. Default is** `HTTP_GET`. The type of the request (see [Summary](#summary)).
     * @param  string $downloadDestinationFp **OPTIONAL.** For `HTTP_DOWNLOAD`, `FTP_DOWNLOAD`, and `ANY_DOWNLOAD`
     * request types, this is the file path to which the downloaded data should be saved.
     */

    public function __construct ($url, $type = self::HTTP_GET, $downloadDestinationFp = null)
    {
        assert( 'is_cstring($url) && is_enum($type) && ' .
                '(!isset($downloadDestinationFp) || is_cstring($downloadDestinationFp))',
            vs(isset($this), get_defined_vars()) );
        assert( 'CUrl::isValid($url, true)', vs(isset($this), get_defined_vars()) );
        assert( '!(($type == self::HTTP_DOWNLOAD || $type == self::FTP_DOWNLOAD || ' .
                '$type == self::ANY_DOWNLOAD) && !isset($downloadDestinationFp))',
            vs(isset($this), get_defined_vars()) );

        // Find out the basic protocol from the request type.
        switch ( $type )
        {
        case self::HTTP_GET:
        case self::HTTP_DOWNLOAD:
        case self::HTTP_POST:
        case self::HTTP_UPLOAD:
        case self::HTTP_PUT:
        case self::HTTP_DELETE:
        case self::HTTP_HEAD:
            $this->m_requestBasicProtocol = "http";
            break;
        case self::FTP_LIST:
        case self::FTP_DOWNLOAD:
        case self::FTP_UPLOAD:
            $this->m_requestBasicProtocol = "ftp";
            break;
        case self::ANY_DOWNLOAD:
            // Look into the URL.
            $objUrl = new CUrl(CUrl::ensureProtocol($url));
            $protocolFromUrl = $objUrl->normalizedProtocol();
            $this->m_requestBasicProtocol = self::basicProtocol($protocolFromUrl);
            break;
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
            break;
        }

        // If the URL does not indicate any protocol, which prevents it from being fully qualified, make the URL
        // indicate the protocol that was derived from the request type.
        $url = CUrl::ensureProtocol($url, $this->m_requestBasicProtocol);

        if ( CDebug::isDebugModeOn() )
        {
            $objUrl = new CUrl($url);
            assert( '$objUrl->hasProtocol()', vs(isset($this), get_defined_vars()) );
            $basicProtocolFromUrl = self::basicProtocol($objUrl->normalizedProtocol());
            assert( 'CString::equals($basicProtocolFromUrl, $this->m_requestBasicProtocol)',
                vs(isset($this), get_defined_vars()) );
        }

        $this->m_url = $url;
        $this->m_type = $type;
        $this->m_downloadDestinationFp = CFilePath::frameworkPath($downloadDestinationFp);
        $this->m_requestSummary = CMap::make();
        $this->m_responseHeaders = CArray::make();
        $this->m_responseHeadersLcKeys = CMap::make();

        $this->m_curl = curl_init();
        if ( !is_resource($this->m_curl) || !CString::isEmpty(curl_error($this->m_curl)) )
        {
            $this->m_hasError = true;
            $curlError = ( !is_resource($this->m_curl) ) ? "" : curl_error($this->m_curl);
            $this->m_errorMessage = ( CString::isEmpty($curlError) ) ? "cURL initialization failed." : $curlError;
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
        assert( '$this->m_hasError', vs(isset($this), get_defined_vars()) );
        return $this->m_errorMessage;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the number of the port to which a request needs to be sent.
     *
     * A port number set with this method overrides the port number specified in the URL from which the object was
     * constructed, if any.
     *
     * @param  int $port The number of the destination port.
     *
     * @return void
     */

    public function setPort ($port)
    {
        assert( 'is_int($port)', vs(isset($this), get_defined_vars()) );
        $this->m_port = $port;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets an overall timeout for a request.
     *
     * @param  int $numSeconds The number of seconds after which the request should time out.
     *
     * @return void
     */

    public function setRequestTimeout ($numSeconds)
    {
        assert( 'is_int($numSeconds)', vs(isset($this), get_defined_vars()) );
        assert( '$numSeconds > 0', vs(isset($this), get_defined_vars()) );

        $this->m_requestTimeoutSeconds = $numSeconds;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets a timeout only for the connection establishment stage of a request.
     *
     * @param  int $numSeconds The number of seconds after which the request should time out.
     *
     * @return void
     */

    public function setConnectionTimeout ($numSeconds)
    {
        assert( 'is_int($numSeconds)', vs(isset($this), get_defined_vars()) );
        assert( '$numSeconds > 0', vs(isset($this), get_defined_vars()) );

        $this->m_connectionTimeoutSeconds = $numSeconds;
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
        $this->m_connectionTimeoutSeconds = 0;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets a timeout for the entries in the DNS cache for a request.
     *
     * @param  int $numSeconds The number of seconds after which an entry should time out.
     *
     * @return void
     */

    public function setDnsCacheTimeout ($numSeconds)
    {
        assert( 'is_int($numSeconds)', vs(isset($this), get_defined_vars()) );
        assert( '$numSeconds > 0', vs(isset($this), get_defined_vars()) );

        $this->m_dnsCacheTimeoutSeconds = $numSeconds;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to follow the URL in the "Location" header if it was sent with the response(s) to a request.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $enable `true` to follow the URL in a received "Location" header, `false` otherwise.
     *
     * @return void
     */

    public function setRedirection ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_redirection = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the maximum number of redirections that are allowed to take place for a request.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  int $maxNumRedirections The maximum number of redirections allowed.
     *
     * @return void
     */

    public function setMaxNumRedirections ($maxNumRedirections)
    {
        assert( 'is_int($maxNumRedirections)', vs(isset($this), get_defined_vars()) );
        assert( '$maxNumRedirections > 0', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_maxNumRedirections = $maxNumRedirections;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to specify the "Referer" header automatically for every URL that a request is being redirected to.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $enable `true` to specify the "Referer" header automatically when being redirected, `false`
     * otherwise.
     *
     * @return void
     */

    public function setRedirectionAutoReferer ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_redirectionAutoReferer = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether a request should fail on any 4xx ("Client Error") or 5xx ("Server Error") HTTP response code.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $enable `true` if the request should fail on 4xx and 5xx HTTP response codes, `false` if not.
     *
     * @return void
     */

    public function setFailOn400ResponseCodeOrGreater ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_failOn400ResponseCodeOrGreater = $enable;
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
     * @param  string $headerName The name of the header.
     * @param  string $value The value of the header.
     *
     * @return void
     */

    public function addHeader ($headerName, $value)
    {
        assert( 'is_cstring($headerName) && is_cstring($value)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($headerName, "/^[\\\\w\\\\-]+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( '!CRegex::find($value, "/\\\\v/")', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_requestHeaders) )
        {
            $this->m_requestHeaders = CArray::make();
        }
        $this->addHeaderWithoutOverriding($this->m_requestHeaders, $headerName, $value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP header to a request so that it is allowed to appear repeated in the outgoing header list if the
     * list already contains a header with the same name.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  string $headerName The name of the header.
     * @param  string $value The value of the header.
     *
     * @return void
     */

    public function addHeaderPlural ($headerName, $value)
    {
        assert( 'is_cstring($headerName) && is_cstring($value)', vs(isset($this), get_defined_vars()) );
        assert( 'CRegex::find($headerName, "/^[\\\\w\\\\-]+\\\\z/")', vs(isset($this), get_defined_vars()) );
        assert( '!CRegex::find($value, "/\\\\v/")', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_requestHeaders) )
        {
            $this->m_requestHeaders = CArray::make();
        }
        $headerLine = "$headerName: $value";
        CArray::push($this->m_requestHeaders, $headerLine);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds a POST field to a request.
     *
     * This method has an effect on HTTP POST requests only.
     *
     * @param  string $fieldName The name of the POST field.
     * @param  mixed $value The value of the POST field. This can be a string (the most common case), a `bool`, an
     * `int`, a `float`, an array, or a map.
     *
     * @return void
     */

    public function addPostField ($fieldName, $value)
    {
        assert( 'is_cstring($fieldName) && (is_cstring($value) || is_collection($value))',
            vs(isset($this), get_defined_vars()) );
        assert( '$this->m_type == self::HTTP_POST', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_postQuery) )
        {
            $this->m_postQuery = new CUrlQuery();
        }
        $this->m_postQuery->addField($fieldName, $value);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds an HTTP cookie to a request.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  string $cookieName The name of the cookie.
     * @param  string $value The value of the cookie.
     *
     * @return void
     */

    public function addCookie ($cookieName, $value)
    {
        assert( 'is_cstring($cookieName) && is_cstring($value)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        if ( !isset($this->m_requestCookies) )
        {
            $this->m_requestCookies = CArray::make();
        }
        $cookie = "$cookieName=$value";
        CArray::push($this->m_requestCookies, $cookie);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For any request that needs to upload a file, specifies the file to be uploaded and, for HTTP requests, the
     * file's metadata.
     *
     * @param  string $filePath The path to the file to be uploaded.
     * @param  string $mimeType **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The MIME type of the file.
     * @param  string $fileName **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The custom name of the file.
     * @param  string $fileId **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The ID under which the file is
     * to arrive to the destination server.
     *
     * @return void
     */

    public function setUploadFile ($filePath, $mimeType = null, $fileName = null, $fileId = null)
    {
        assert( 'is_cstring($filePath) && (!isset($mimeType) || is_cstring($mimeType)) && (!isset($fileName) || ' .
                'is_cstring($fileName)) && (!isset($fileId) || is_cstring($fileId))',
            vs(isset($this), get_defined_vars()) );
        assert( 'CFilePath::isAbsolute($filePath)', vs(isset($this), get_defined_vars()) );
        assert( '$this->m_type == self::HTTP_UPLOAD || $this->m_type == self::HTTP_PUT || ' .
                '$this->m_type == self::FTP_UPLOAD', vs(isset($this), get_defined_vars()) );
        assert( '!($this->m_type == self::HTTP_UPLOAD && (!isset($mimeType) || !isset($fileName) || ' .
                '!isset($fileId)))', vs(isset($this), get_defined_vars()) );

        $filePath = CFilePath::frameworkPath($filePath);

        if ( $this->m_type == self::HTTP_UPLOAD )
        {
            // Via HTTP POST.
            $curlFile = new CURLFile($filePath, $mimeType, $fileName);
            $this->m_postFileUploadRecord = [$fileId => $curlFile];
        }
        else  // $this->m_type = self::HTTP_PUT or $this->m_type = self::FTP_UPLOAD
        {
            $this->m_nonPostFileUploadFp = $filePath;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * For any request that needs to upload a file, specifies the data to be uploaded and, for HTTP requests, the
     * file's metadata.
     *
     * For large data, `setUploadFile` method would be preferred to upload an already existing file.
     *
     * @param  data $data The data to be uploaded.
     * @param  string $mimeType **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The MIME type of the file.
     * @param  string $fileName **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The custom name of the file.
     * @param  string $fileId **OPTIONAL.** *Required for `HTTP_UPLOAD` requests only.* The ID under which the file is
     * to arrive to the destination server.
     *
     * @return void
     *
     * @link   #method_setUploadFile setUploadFile
     */

    public function setUploadData ($data, $mimeType = null, $fileName = null, $fileId = null)
    {
        assert( 'is_cstring($data) && (!isset($mimeType) || is_cstring($mimeType)) && ' .
                '(!isset($fileName) || is_cstring($fileName)) && (!isset($fileId) || is_cstring($fileId))',
            vs(isset($this), get_defined_vars()) );

        $this->m_fileUploadTempFp = CFile::createTemporary();
        CFile::write($this->m_fileUploadTempFp, $data);
        $this->setUploadFile($this->m_fileUploadTempFp, $mimeType, $fileName, $fileId);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to send the default "Accept-Encoding" HTTP header.
     *
     * The header's default value is determined by the capabilities of the cURL library.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $enable `true` to send the default "Accept-Encoding" HTTP header, `false` otherwise.
     *
     * @return void
     */

    public function setSendDefaultAcceptEncodingHeader ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_sendDefaultAcceptEncodingHeader = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to send the default "User-Agent" HTTP header.
     *
     * The header's default value is determined by the respective static property of the class.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $enable `true` to send the default "User-Agent" HTTP header, `false` otherwise.
     *
     * @return void
     */

    public function setSendDefaultUserAgentHeader ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_sendDefaultUserAgentHeader = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Allows or disallows 304 response code to be returned by the destination server if the requested entity has not
     * been modified since a specified moment in time.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  CTime $time The modification time of the requested entity by which its contents are already known to
     * the client.
     *
     * @return void
     */

    public function setAllow304ResponseCodeIfNotModifiedSince (CTime $time)
    {
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        if ( isset($this->m_requestHeaders) )
        {
            $this->removeHeader(CHttpRequest::IF_MODIFIED_SINCE);
        }
        $strTime = $time->toStringUtc(CTime::PATTERN_HTTP_HEADER_GMT);
        $this->addHeader(CHttpRequest::IF_MODIFIED_SINCE, $strTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the byte range(s) of interest in the requested entity.
     *
     * @param  array $byteRangeOrRanges The byte range(s) of interest. This is either an array with two integer values
     * specifying the byte range to be retrieved or, for HTTP requests only, an array of such arrays specifying
     * multiple byte ranges. The second value in a pair is allowed to be `null`, in which case the range is considered
     * to be open-ended and the server is expected to respond with data starting from the byte indicated by the first
     * value in the pair and up to the very last byte available.
     *
     * @return void
     */

    public function setRequestedByteRange ($byteRangeOrRanges)
    {
        assert( 'is_carray($byteRangeOrRanges) && !CArray::isEmpty($byteRangeOrRanges)',
            vs(isset($this), get_defined_vars()) );

        if ( !is_carray(CArray::first($byteRangeOrRanges)) )
        {
            assert( 'CArray::length($byteRangeOrRanges) == 2', vs(isset($this), get_defined_vars()) );
            $rangeBPosLow = $byteRangeOrRanges[0];
            $rangeBPosHigh = $byteRangeOrRanges[1];
            assert( 'is_int($rangeBPosLow) && (is_null($rangeBPosHigh) || is_int($rangeBPosHigh))',
                vs(isset($this), get_defined_vars()) );
            assert( '$rangeBPosLow >= 0 && (is_null($rangeBPosHigh) || $rangeBPosHigh >= 0)',
                vs(isset($this), get_defined_vars()) );
            assert( 'is_null($rangeBPosHigh) || $rangeBPosHigh >= $rangeBPosLow',
                vs(isset($this), get_defined_vars()) );
            $this->m_requestedByteRange = ( !is_null($rangeBPosHigh) ) ? "$rangeBPosLow-$rangeBPosHigh" :
                "$rangeBPosLow-";
        }
        else
        {
            assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );
            $preOutString = CArray::make();
            $len = CArray::length($byteRangeOrRanges);
            $lenMinusOne = $len - 1;
            for ($i = 0; $i < $len; $i++)
            {
                $range = $byteRangeOrRanges[$i];

                assert( 'is_carray($range)', vs(isset($this), get_defined_vars()) );
                assert( 'CArray::length($range) == 2', vs(isset($this), get_defined_vars()) );
                $rangeBPosLow = $range[0];
                $rangeBPosHigh = $range[1];
                if ( CDebug::isDebugModeOn() )
                {
                    // The high byte position can be specified as `null` for the last range only.
                    if ( $i != $lenMinusOne )
                    {
                        assert( 'is_int($rangeBPosLow) && is_int($rangeBPosHigh)',
                            vs(isset($this), get_defined_vars()) );
                        assert( '$rangeBPosLow >= 0 && $rangeBPosHigh >= 0', vs(isset($this), get_defined_vars()) );
                        assert( '$rangeBPosHigh >= $rangeBPosLow', vs(isset($this), get_defined_vars()) );
                    }
                    else
                    {
                        assert( 'is_int($rangeBPosLow) && (is_null($rangeBPosHigh) || is_int($rangeBPosHigh))',
                            vs(isset($this), get_defined_vars()) );
                        assert( '$rangeBPosLow >= 0 && (is_null($rangeBPosHigh) || $rangeBPosHigh >= 0)',
                            vs(isset($this), get_defined_vars()) );
                        assert( 'is_null($rangeBPosHigh) || $rangeBPosHigh >= $rangeBPosLow',
                            vs(isset($this), get_defined_vars()) );
                    }
                }
                $rbr = ( !is_null($rangeBPosHigh) ) ? "$rangeBPosLow-$rangeBPosHigh" : "$rangeBPosLow-";
                CArray::push($preOutString, $rbr);
            }
            $this->m_requestedByteRange = CArray::join($preOutString, ",");
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables the verification of the destination server's certificate when trying to connect via HTTPS or
     * any other SSL-enabled protocol.
     *
     * @param  bool $enable `true` to enable the verification of the destination server's certificate, `false`
     * otherwise.
     * @param  string $alternateCertificateFpOrDp **OPTIONAL. Default is** *use the default certificate bundle*. The
     * path to a file or directory with alternate certificate(s) to be used in substitute of the default certificate
     * bundle. Providing this parameter makes sense only if the first parameter is `true`.
     *
     * @return void
     */

    public function setCertificateVerification ($enable, $alternateCertificateFpOrDp = null)
    {
        assert( 'is_bool($enable) && (!isset($alternateCertificateFpOrDp) || ' .
                'is_cstring($alternateCertificateFpOrDp))', vs(isset($this), get_defined_vars()) );
        assert( '!(!$enable && isset($alternateCertificateFpOrDp))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($alternateCertificateFpOrDp) || CFilePath::isAbsolute($alternateCertificateFpOrDp)',
            vs(isset($this), get_defined_vars()) );

        $this->m_certificateVerification = $enable;
        $this->m_alternateCertificateFpOrDp = CFilePath::frameworkPath($alternateCertificateFpOrDp);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables the verification of the destination server's hostname when trying to connect via HTTPS or
     * any other SSL-enabled protocol.
     *
     * @param  bool $enable `true` to enable the verification of the destination server's hostname, `false` otherwise.
     *
     * @return void
     */

    public function setHostVerification ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        $this->m_hostVerification = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the username and the password to be sent along with a request.
     *
     * @param  string $user The username.
     * @param  string $password The password.
     *
     * @return void
     */

    public function setUserAndPassword ($user, $password)
    {
        assert( 'is_cstring($user) && is_cstring($password)', vs(isset($this), get_defined_vars()) );
        $this->m_userAndPassword = "$user:$password";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets whether to keep sending the username and password to any URL to which a request may be redirected,
     * regardless of hostname changes.
     *
     * This method has an effect on HTTP requests only.
     *
     * @param  bool $enable `true` to keep sending the username and password when being redirected, `false` otherwise.
     *
     * @return void
     */

    public function setRedirectionKeepAuth ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp()', vs(isset($this), get_defined_vars()) );

        $this->m_redirectionKeepAuth = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the options for an FTP request.
     *
     * @param  bool $createMissingDirectoriesForUpload Tells whether to try creating missing directories when
     * uploading a file.
     * @param  bool $appendUpload **OPTIONAL. Default is** `false`. Tells whether the data from the file being
     * uploaded should be appended to the data of the existing file, if any.
     * @param  array $quoteCommands **OPTIONAL. Default is** *none*. The (S)FTP commands to be run before the transfer.
     * @param  array $postQuoteCommands **OPTIONAL. Default is** *none*. The (S)FTP commands to be run after the
     * transfer.
     * @param  bool $useEpsv **OPTIONAL. Default is** `true`. Tells whether to use EPSV.
     * @param  string $activeModeBackAddress **OPTIONAL. Default is** *cURL's default*. The client-side address to
     * which the destination server should try connecting back.
     * @param  bool $useEprt **OPTIONAL. Default is** `true`. Tells whether to use EPRT.
     *
     * @return void
     */

    public function setFtpOptions ($createMissingDirectoriesForUpload, $appendUpload = false, $quoteCommands = null,
        $postQuoteCommands = null, $useEpsv = true, $activeModeBackAddress = null, $useEprt = true)
    {
        assert( 'is_bool($createMissingDirectoriesForUpload) && is_bool($appendUpload) && ' .
                '(!isset($quoteCommands) || is_carray($quoteCommands)) && (!isset($postQuoteCommands) || ' .
                'is_carray($postQuoteCommands)) && is_bool($useEpsv) && (!isset($activeModeBackAddress) || ' .
                'is_cstring($activeModeBackAddress)) && is_bool($useEprt)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isFtp()', vs(isset($this), get_defined_vars()) );
        if ( CDebug::isDebugModeOn() )
        {
            if ( isset($quoteCommands) )
            {
                $len = CArray::length($quoteCommands);
                for ($i = 0; $i < $len; $i++)
                {
                    assert( 'is_cstring($quoteCommands[$i])', vs(isset($this), get_defined_vars()) );
                }
            }
            if ( isset($postQuoteCommands) )
            {
                $len = CArray::length($postQuoteCommands);
                for ($i = 0; $i < $len; $i++)
                {
                    assert( 'is_cstring($postQuoteCommands[$i])', vs(isset($this), get_defined_vars()) );
                }
            }
        }

        $this->m_ftpCreateMissingDirectoriesForUpload = $createMissingDirectoriesForUpload;
        $this->m_ftpAppendUpload = $appendUpload;
        $this->m_ftpQuoteCommands = $quoteCommands;
        $this->m_ftpPostQuoteCommands = $postQuoteCommands;
        $this->m_ftpUseEpsv = $useEpsv;
        $this->m_ftpActiveModeBackAddress = $activeModeBackAddress;
        $this->m_ftpUseEprt = $useEprt;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the SSL options for a request.
     *
     * @param  string $certificateFp The path to the certificate's file.
     * @param  string $privateKeyFp **OPTIONAL. Default is** *none*. The path to the private key's file.
     * @param  string $certificateFormat **OPTIONAL. Default is** "PEM". The format used by the certificate.
     * @param  string $privateKeyFormat **OPTIONAL. Default is** "PEM". The format used by the private key.
     * @param  string $certificatePassphrase **OPTIONAL. Default is** *none*. The passphrase used by the certificate.
     * @param  string $privateKeyPassphrase **OPTIONAL. Default is** *none*. The passphrase used by the private key.
     * @param  int $sslVersion **OPTIONAL. Default is** *cURL's default*. The SSL version to be used in the
     * communication.
     * @param  string $cipherList **OPTIONAL. Default is** *cURL's default*. The ciphers to be used.
     * @param  string $engine **OPTIONAL. Default is** *cURL's default*. The SSL engine identifier.
     * @param  string $defaultEngine **OPTIONAL. Default is** *cURL's default*. The identifier of the actual crypto
     * engine to be used as the default engine for (asymmetric) crypto operations.
     *
     * @return void
     */

    public function setSslOptions ($certificateFp, $privateKeyFp = null, $certificateFormat = "PEM",
        $privateKeyFormat = "PEM", $certificatePassphrase = null, $privateKeyPassphrase = null, $sslVersion = null,
        $cipherList = null, $engine = null, $defaultEngine = null)
    {
        assert( 'is_cstring($certificateFp) && (!isset($privateKeyFp) || is_cstring($privateKeyFp)) && ' .
                'is_cstring($certificateFormat) && is_cstring($privateKeyFormat) && ' .
                '(!isset($certificatePassphrase) || is_cstring($certificatePassphrase)) && ' .
                '(!isset($privateKeyPassphrase) || is_cstring($privateKeyPassphrase)) && ' .
                '(!isset($sslVersion) || is_int($sslVersion)) && ' .
                '(!isset($cipherList) || is_cstring($cipherList)) && (!isset($engine) || is_cstring($engine)) && ' .
                '(!isset($defaultEngine) || is_cstring($defaultEngine))', vs(isset($this), get_defined_vars()) );
        assert( 'CFilePath::isAbsolute($certificateFp)', vs(isset($this), get_defined_vars()) );
        assert( '!isset($privateKeyFp) || CFilePath::isAbsolute($privateKeyFp)',
            vs(isset($this), get_defined_vars()) );

        $this->m_sslCertificateFp = CFilePath::frameworkPath($certificateFp);
        $this->m_sslPrivateKeyFp = CFilePath::frameworkPath($privateKeyFp);
        $this->m_sslCertificateFormat = $certificateFormat;
        $this->m_sslPrivateKeyFormat = $privateKeyFormat;
        $this->m_sslCertificatePassphrase = $certificatePassphrase;
        $this->m_sslPrivateKeyPassphrase = $privateKeyPassphrase;
        $this->m_sslVersion = $sslVersion;
        $this->m_sslCipherList = $cipherList;
        $this->m_sslEngine = $engine;
        $this->m_sslDefaultEngine = $defaultEngine;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables Kerberos security protocol and, if enabled, sets the Kerberos security level.
     *
     * @param  bool $enable `true` to enable the use of the Kerberos security protocol, `false` otherwise.
     * @param  string $level **OPTIONAL. Default is** *cURL's default*. The Kerberos security level to be used.
     *
     * @return void
     */

    public function setKerberos ($enable, $level = null)
    {
        assert( 'is_bool($enable) && (!isset($level) || is_cstring($level))', vs(isset($this), get_defined_vars()) );
        assert( '!(!$enable && isset($level))', vs(isset($this), get_defined_vars()) );

        $this->m_useKerberos = $enable;
        if ( $enable )
        {
            if ( !isset($level) )
            {
                // Will make it be the default level.
                $level = "";
            }
            $this->m_kerberosLevel = $level;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets proxy options for a request.
     *
     * @param  string $proxyAddress The address of the proxy to be used.
     * @param  string $user The username for the proxy.
     * @param  string $password The password for the proxy.
     * @param  enum $proxyType **OPTIONAL. Default is** `PROXY_SOCKS_5`. The type of the proxy
     * (see [Summary](#summary)).
     * @param  int $proxyPort **OPTIONAL. Default is** *determine automatically*. The proxy's port number.
     * @param  bool $proxyTunneling **OPTIONAL. Default is** `false`. Tells whether proxy tunneling should be used.
     * @param  bool $connectOnly **OPTIONAL. Default is** `false`. Tells whether to disconnect from the proxy right
     * after successfully making a connection.
     *
     * @return void
     */

    public function setProxy ($proxyAddress, $user, $password, $proxyType = self::PROXY_SOCKS_5, $proxyPort = null,
        $proxyTunneling = false, $connectOnly = false)
    {
        assert( 'is_cstring($proxyAddress) && is_cstring($user) && is_cstring($password) && ' .
                'is_enum($proxyType) && (!isset($proxyPort) || is_int($proxyPort)) && ' .
                'is_bool($proxyTunneling) && is_bool($connectOnly)',
            vs(isset($this), get_defined_vars()) );

        $this->m_proxyAddress = $proxyAddress;
        $this->m_proxyUserAndPassword = "$user:$password";
        $this->m_proxyType = $proxyType;
        $this->m_proxyPort = $proxyPort;
        $this->m_proxyTunneling = $proxyTunneling;
        $this->m_proxyConnectOnly = $connectOnly;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the name of the outgoing network interface to be used for a request.
     *
     * @param  string $interface The name of the outgoing network interface. This can be an interface name as well as
     * an IP address or a hostname.
     *
     * @return void
     */

    public function setOutgoingInterface ($interface)
    {
        assert( 'is_cstring($interface)', vs(isset($this), get_defined_vars()) );
        $this->m_outgoingInterface = $interface;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables the echoing of the response into the standard output by cURL.
     *
     * @param  bool $enable `true` to enable the echoing of the response, `false` otherwise.
     *
     * @return void
     */

    public function setEchoResponse ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        $this->m_echoResponse = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Enables or disables cURL's verbose output for a request.
     *
     * @param  bool $enable `true` to enable cURL's verbose output, `false` otherwise.
     *
     * @return void
     */

    public function setVerboseOutput ($enable)
    {
        assert( 'is_bool($enable)', vs(isset($this), get_defined_vars()) );
        $this->m_verboseOutput = $enable;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the download speed limit for a request.
     *
     * @param  int $bytesPerSecond The download speed limit, in bytes per second.
     *
     * @return void
     */

    public function setMaxDownloadSpeed ($bytesPerSecond)
    {
        assert( 'is_int($bytesPerSecond)', vs(isset($this), get_defined_vars()) );
        assert( '$bytesPerSecond > 0', vs(isset($this), get_defined_vars()) );

        $this->m_maxDownloadSpeed = $bytesPerSecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sets the upload speed limit for a request.
     *
     * @param  int $bytesPerSecond The upload speed limit, in bytes per second.
     *
     * @return void
     */

    public function setMaxUploadSpeed ($bytesPerSecond)
    {
        assert( 'is_int($bytesPerSecond)', vs(isset($this), get_defined_vars()) );
        assert( '$bytesPerSecond > 0', vs(isset($this), get_defined_vars()) );

        $this->m_maxUploadSpeed = $bytesPerSecond;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Sends a request out and returns the response after it is received.
     *
     * @param  reference $success **OUTPUT.** After the method is called, the value of this parameter tells whether
     * the request was successful.
     *
     * @return CUStringObject The response that was received for the request.
     */

    public function send (&$success)
    {
        $success = true;

        if ( $this->m_hasError )
        {
            // Already finalized.
            $success = false;
            return "";
        }

        // Set options for the cURL handle.
        $this->setInternalOptions($success);
        if ( !$success )
        {
            // Already finalized.
            return "";
        }

        // Disable the script's execution timeout before sending the request.
        $timeoutPause = new CTimeoutPause();

        // Send the request out and wait for a response.
        $res = curl_exec($this->m_curl);

        // Set the script's execution time limit like the request has never happened.
        $timeoutPause->end();

        // According to some testimonials, when trying to retrieve a resource that turns out to be empty, `curl_exec`
        // function could return a `true` instead of an empty string.
        if ( $res === true && !$this->m_echoResponse && CString::isEmpty(curl_error($this->m_curl)) )
        {
            $res = "";
        }
        else if ( $res === false || (!$this->m_echoResponse && !is_cstring($res)) ||
                  !CString::isEmpty(curl_error($this->m_curl)) )
        {
            // An error occurred.
            $this->m_hasError = true;
            $curlError = curl_error($this->m_curl);
            $this->m_errorMessage = ( !CString::isEmpty($curlError) ) ? $curlError :
                "The 'curl_exec' function failed.";
            $success = false;
            $this->finalize();
            return "";
        }

        // Collect summary information for the request and the response and finalize.
        $this->onRequestCompleteOk();

        return ( !$this->m_echoResponse ) ? $res : "";
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "http_code";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_int($value) ) ? $value : CString::toInt($value);
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
     * @param  string $headerName The name of the header.
     *
     * @return bool `true` if the response includes a header with the name specified, `false` otherwise.
     */

    public function responseHasHeader ($headerName)
    {
        assert( 'is_cstring($headerName)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $headerNameLc = CString::toLowerCase($headerName);
        return CMap::hasKey($this->m_responseHeadersLcKeys, $headerNameLc);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the value of a specified HTTP header in the response.
     *
     * This method can be called for HTTP requests only.
     *
     * @param  string $headerName The name of the header.
     *
     * @return CUStringObject The value of the header.
     */

    public function responseHeader ($headerName)
    {
        assert( 'is_cstring($headerName)', vs(isset($this), get_defined_vars()) );
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );
        assert( '$this->responseHasHeader($headerName)', vs(isset($this), get_defined_vars()) );

        $headerNameLc = CString::toLowerCase($headerName);
        return $this->m_responseHeadersLcKeys[$headerNameLc];
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );
        assert( '$this->responseHasTimestamp()', vs(isset($this), get_defined_vars()) );

        $timestamp = $this->responseHeader(CHttpResponse::DATE);
        return CTime::fromString($timestamp);
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );
        assert( '$this->responseHasModificationTime()', vs(isset($this), get_defined_vars()) );

        $modificationTime = $this->responseHeader(CHttpResponse::LAST_MODIFIED);
        return CTime::fromString($modificationTime);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the total number of bytes received with the response.
     *
     * @return int The total number of bytes received.
     */

    public function responseDownloadSize ()
    {
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "size_download";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (int)$value : CString::toInt($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "speed_download";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (float)$value : CString::toFloat($value);
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "header_size";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_int($value) || is_float($value) ) ? (int)$value : CString::toInt($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "certinfo";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            return oop_x($this->m_requestSummary[$key]);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "request_size";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_int($value) || is_float($value) ) ? (int)$value : CString::toInt($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "size_upload";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (int)$value : CString::toInt($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "speed_upload";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (float)$value : CString::toFloat($value);
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "upload_content_length";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (int)$value : CString::toInt($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "total_time";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (float)$value : CString::toFloat($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "namelookup_time";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (float)$value : CString::toFloat($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "connect_time";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (float)$value : CString::toFloat($value);
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "redirect_time";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (float)$value : CString::toFloat($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "pretransfer_time";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (float)$value : CString::toFloat($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "starttransfer_time";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_float($value) || is_int($value) ) ? (float)$value : CString::toFloat($value);
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
        assert( '$this->isHttp() && $this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "redirect_count";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            return ( is_int($value) || is_float($value) ) ? (int)$value : CString::toInt($value);
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
        assert( '$this->m_done && !$this->m_hasError', vs(isset($this), get_defined_vars()) );

        $key = "ssl_verify_result";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            $value = $this->m_requestSummary[$key];
            if ( !is_bool($value) )
            {
                if ( !is_cstring($value) )
                {
                    $value = CString::fromInt($value);
                }
                return CString::toBool($value);
            }
            else
            {
                return $value;
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
        assert( '$this->isHttp() && $this->m_done', vs(isset($this), get_defined_vars()) );

        $key = "request_header";
        if ( CMap::hasKey($this->m_requestSummary, $key) )
        {
            return $this->m_requestSummary[$key];
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
     * @param  string $url The URL to which the request is to be sent.
     * @param  string $destinationFp The destination file path to which the response should be saved.
     * @param  int $timeoutSeconds **OPTIONAL. Default is** *no timeout*. The number of seconds after which the
     * request should time out.
     *
     * @return bool `true` if the request was successful, `false` otherwise.
     */

    public static function downloadFile ($url, $destinationFp, $timeoutSeconds = null)
    {
        assert( 'is_cstring($url) && is_cstring($destinationFp) && ' .
                '(!isset($timeoutSeconds) || is_int($timeoutSeconds))', vs(isset($this), get_defined_vars()) );

        $inetRequest = new self($url, self::ANY_DOWNLOAD, $destinationFp);
        if ( isset($timeoutSeconds) )
        {
            $inetRequest->setRequestTimeout($timeoutSeconds);
        }
        $success;
        $inetRequest->send($success);
        return $success;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Makes a request to a specified URL and returns the response.
     *
     * @param  string $url The URL to which the request is to be sent.
     * @param  reference $success **OPTIONAL. OUTPUT.** After the method is called, the value of this parameter tells
     * whether the request was successful.
     * @param  int $timeoutSeconds **OPTIONAL. Default is** *no timeout*. The number of seconds after which the
     * request should time out.
     *
     * @return CUStringObject The response that was received for the request.
     */

    public static function read ($url, &$success = null, $timeoutSeconds = null)
    {
        assert( 'is_cstring($url) && (!isset($timeoutSeconds) || is_int($timeoutSeconds))',
            vs(isset($this), get_defined_vars()) );

        $inetRequest = new self($url);
        if ( isset($timeoutSeconds) )
        {
            $inetRequest->setRequestTimeout($timeoutSeconds);
        }
        $content = $inetRequest->send($success);
        if ( !$success )
        {
            $content = "";
        }
        return $content;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the cURL library used, as a string.
     *
     * @return CUStringObject The cURL's version.
     */

    public static function curlVersion ()
    {
        $verInfo = curl_version();
        return $verInfo["version"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the cURL library used, as an integer number.
     *
     * @return int The cURL's version.
     */

    public static function curlVersionInt ()
    {
        $verInfo = curl_version();
        return $verInfo["version_number"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the SSL security layer used, as a string.
     *
     * @return CUStringObject The SSL's version.
     */

    public static function openSslVersion ()
    {
        $verInfo = curl_version();
        return $verInfo["ssl_version"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the version of the SSL security layer used, as an integer number.
     *
     * @return int The SSL's version.
     */

    public static function openSslVersionInt ()
    {
        $verInfo = curl_version();
        return $verInfo["ssl_version_number"];
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function isHttp ()
    {
        return CString::equals($this->m_requestBasicProtocol, "http");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function isFtp ()
    {
        return CString::equals($this->m_requestBasicProtocol, "ftp");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function setInternalOptions (&$success, $cookiesFp = null, $newCookieSession = null)
    {
        $success = true;

        if ( $this->m_hasError )
        {
            $success = false;
            $this->finalize();
            return;
        }

        $options = CMap::make();

        // How to deal with the response.
        if ( !$this->m_echoResponse )
        {
            $options[CURLOPT_RETURNTRANSFER] = true;
            $this->m_isReturnTransferSet = true;
        }
        else
        {
            $options[CURLOPT_RETURNTRANSFER] = false;
            $this->m_isReturnTransferSet = false;
        }

        if ( isset($this->m_verboseOutput) && $this->m_verboseOutput )
        {
            $options[CURLOPT_VERBOSE] = true;
        }

        // The destination URL and port.
        $options[CURLOPT_URL] = $this->m_url;
        if ( isset($this->m_port) )
        {
            $options[CURLOPT_PORT] = $this->m_port;
        }

        // Avoid response caching and reuse, which might happen because of any unconventional caching strategies used
        // by cURL.
        $options[CURLOPT_FORBID_REUSE] = true;
        $options[CURLOPT_FRESH_CONNECT] = true;

        if ( $this->m_type != self::HTTP_HEAD )
        {
            $options[CURLOPT_HEADER] = false;
        }

        if ( $this->isHttp() )
        {
            if ( $this->m_type == self::HTTP_GET )
            {
                $options[CURLOPT_HTTPGET] = true;
            }
            else if ( $this->m_type == self::HTTP_DOWNLOAD ||
                      $this->m_type == self::ANY_DOWNLOAD )
            {
                $options[CURLOPT_HTTPGET] = true;
                assert( 'isset($this->m_downloadDestinationFp)', vs(isset($this), get_defined_vars()) );
                $this->m_downloadFile = new CFile($this->m_downloadDestinationFp, CFile::WRITE_NEW);
                $options[CURLOPT_FILE] = $this->m_downloadFile->systemResource();
            }
            else if ( $this->m_type == self::HTTP_POST )
            {
                // POST.
                $options[CURLOPT_POST] = true;

                // At least one POST variable needs to be set in order to make a POST request.
                assert( 'isset($this->m_postQuery)', vs(isset($this), get_defined_vars()) );

                // POST variables use the same format as the query string (application/x-www-form-urlencoded).
                $options[CURLOPT_POSTFIELDS] = $this->m_postQuery->queryString();
            }
            else if ( $this->m_type == self::HTTP_UPLOAD )
            {
                // File upload via POST and using the CURLFile class.
                $options[CURLOPT_POST] = true;
                assert( 'isset($this->m_postFileUploadRecord)', vs(isset($this), get_defined_vars()) );
                $options[CURLOPT_POSTFIELDS] = $this->m_postFileUploadRecord;
            }
            else if ( $this->m_type == self::HTTP_PUT )
            {
                // PUT.
                assert( 'isset($this->m_nonPostFileUploadFp)', vs(isset($this), get_defined_vars()) );
                $options[CURLOPT_PUT] = true;
                $this->m_uploadFile = new CFile($this->m_nonPostFileUploadFp, CFile::READ);
                $options[CURLOPT_INFILE] = $this->m_uploadFile->systemResource();
                $options[CURLOPT_INFILESIZE] = CFile::size($this->m_nonPostFileUploadFp);
            }
            else if ( $this->m_type == self::HTTP_DELETE )
            {
                // DELETE.
                $options[CURLOPT_CUSTOMREQUEST] = "DELETE";
            }
            else if ( $this->m_type == self::HTTP_HEAD )
            {
                // HEAD.
                $options[CURLOPT_HEADER] = true;
                $options[CURLOPT_NOBODY] = true;
            }

            // HTTP redirections.
            $options[CURLOPT_FOLLOWLOCATION] = $this->m_redirection;
            if ( $this->m_redirection )
            {
                if ( isset($this->m_maxNumRedirections) )
                {
                    $options[CURLOPT_MAXREDIRS] = $this->m_maxNumRedirections;
                }
                if ( isset($this->m_redirectionAutoReferer) )
                {
                    $options[CURLOPT_AUTOREFERER] = $this->m_redirectionAutoReferer;
                }
                if ( isset($this->m_redirectionKeepAuth) )
                {
                    $options[CURLOPT_UNRESTRICTED_AUTH] = $this->m_redirectionKeepAuth;
                }
            }

            // HTTP response code treatment.
            $options[CURLOPT_FAILONERROR] = $this->m_failOn400ResponseCodeOrGreater;

            // HTTP headers.
            if ( $this->m_sendDefaultAcceptEncodingHeader &&
                 !(isset($this->m_requestHeaders) && $this->hasHeader(CHttpRequest::ACCEPT_ENCODING)) )
            {
                $options[CURLOPT_ENCODING] = "";
            }
            if ( $this->m_sendDefaultUserAgentHeader &&
                 !(isset($this->m_requestHeaders) && $this->hasHeader(CHttpRequest::USER_AGENT)) )
            {
                $userAgent = self::$ms_defaultUserAgent;
                $curlVersion = self::curlVersion();
                $sslVersion = self::openSslVersion();
                $sslVersion = CRegex::remove($sslVersion, "/OpenSSL(\\/|\\h+)/i");
                $userAgent = CString::replaceCi($userAgent, "curl/x.x.x", "curl/$curlVersion");
                $userAgent = CString::replaceCi($userAgent, "libcurl x.x.x", "libcurl $curlVersion");
                $userAgent = CString::replaceCi($userAgent, "OpenSSL x.x.x", "OpenSSL $sslVersion");
                $this->addHeader(CHttpRequest::USER_AGENT, $userAgent);
            }
            if ( isset($this->m_requestHeaders) && !CArray::isEmpty($this->m_requestHeaders) )
            {
                $options[CURLOPT_HTTPHEADER] = CArray::toPArray($this->m_requestHeaders);
            }

            if ( isset($this->m_requestCookies) && !CArray::isEmpty($this->m_requestCookies) )
            {
                // Custom HTTP cookies.
                $cookieHeaderValue = CArray::join($this->m_requestCookies, "; ");
                $options[CURLOPT_COOKIE] = $cookieHeaderValue;
            }
            if ( isset($cookiesFp) )
            {
                $options[CURLOPT_COOKIEFILE] = $cookiesFp;
                $options[CURLOPT_COOKIEJAR] = $cookiesFp;
            }
            if ( isset($newCookieSession) && $newCookieSession )
            {
                $options[CURLOPT_COOKIESESSION] = true;
            }

            // Needed for the retrieval of information regarding the data transfer after it is complete.
            $options[CURLINFO_HEADER_OUT] = true;

            // Needed for the retrieval of response headers.
            $options[CURLOPT_HEADERFUNCTION] = [$this, "headerFunction"];

            if ( isset($this->m_userAndPassword) )
            {
                // HTTP authentication. Let cURL pick any authentication method it finds suitable (it will
                // automatically select the one it finds most secure).
                $options[CURLOPT_HTTPAUTH] = CURLAUTH_ANY;
            }
        }
        else  // FTP
        {
            if ( $this->m_type == self::FTP_LIST )
            {
                $options[CURLOPT_FTPLISTONLY] = true;
            }
            else if ( $this->m_type == self::FTP_DOWNLOAD ||
                      $this->m_type == self::ANY_DOWNLOAD )
            {
                assert( 'isset($this->m_downloadDestinationFp)', vs(isset($this), get_defined_vars()) );
                $this->m_downloadFile = new CFile($this->m_downloadDestinationFp, CFile::WRITE_NEW);
                $options[CURLOPT_FILE] = $this->m_downloadFile->systemResource();
            }
            else if ( $this->m_type == self::FTP_UPLOAD )
            {
                // File upload via FTP.

                assert( 'isset($this->m_nonPostFileUploadFp)', vs(isset($this), get_defined_vars()) );
                $this->m_uploadFile = new CFile($this->m_nonPostFileUploadFp, CFile::READ);
                $options[CURLOPT_UPLOAD] = true;
                $options[CURLOPT_INFILE] = $this->m_uploadFile->systemResource();
                $options[CURLOPT_INFILESIZE] = CFile::size($this->m_nonPostFileUploadFp);

                if ( $this->m_ftpCreateMissingDirectoriesForUpload )
                {
                    $options[CURLOPT_FTP_CREATE_MISSING_DIRS] = true;
                }

                if ( isset($this->m_ftpAppendUpload) && $this->m_ftpAppendUpload )
                {
                    $options[CURLOPT_FTPAPPEND] = true;
                }
            }

            if ( isset($this->m_ftpQuoteCommands) && !CArray::isEmpty($this->m_ftpQuoteCommands) )
            {
                $options[CURLOPT_QUOTE] = CArray::toPArray($this->m_ftpQuoteCommands);
            }
            if ( isset($this->m_ftpPostQuoteCommands) && !CArray::isEmpty($this->m_ftpPostQuoteCommands) )
            {
                $options[CURLOPT_POSTQUOTE] = CArray::toPArray($this->m_ftpPostQuoteCommands);
            }

            if ( isset($this->m_ftpUseEpsv) && !$this->m_ftpUseEpsv )
            {
                $options[CURLOPT_FTP_USE_EPSV] = false;
            }

            if ( isset($this->m_ftpActiveModeBackAddress) )
            {
                $options[CURLOPT_FTPPORT] = $this->m_ftpActiveModeBackAddress;
            }

            if ( isset($this->m_ftpUseEprt) && !$this->m_ftpUseEprt )
            {
                $options[CURLOPT_FTP_USE_EPRT] = false;
            }
        }

        // Timeouts.
        if ( isset($this->m_requestTimeoutSeconds) )
        {
            $options[CURLOPT_TIMEOUT] = $this->m_requestTimeoutSeconds;
        }
        if ( isset($this->m_connectionTimeoutSeconds) )
        {
            $options[CURLOPT_CONNECTTIMEOUT] = $this->m_connectionTimeoutSeconds;
        }
        if ( isset($this->m_dnsCacheTimeoutSeconds) )
        {
            $options[CURLOPT_DNS_CACHE_TIMEOUT] = $this->m_dnsCacheTimeoutSeconds;
        }

        // The byte range(s) of interest.
        if ( isset($this->m_requestedByteRange) )
        {
            $options[CURLOPT_RANGE] = $this->m_requestedByteRange;
        }

        // SSL certificate verification options.
        $options[CURLOPT_SSL_VERIFYPEER] = $this->m_certificateVerification;
        if ( isset($this->m_alternateCertificateFpOrDp) )
        {
            if ( CFile::isFile($this->m_alternateCertificateFpOrDp) )
            {
                $options[CURLOPT_CAINFO] = $this->m_alternateCertificateFpOrDp;
            }
            else if ( CFile::isDirectory($this->m_alternateCertificateFpOrDp) )
            {
                $options[CURLOPT_CAPATH] = $this->m_alternateCertificateFpOrDp;
            }
            else
            {
                assert( 'false', vs(isset($this), get_defined_vars()) );
            }
        }
        if ( !$this->m_hostVerification )
        {
            // The default should be the highest setting, so only set this option to `0` if host verification is
            // disabled.
            $options[CURLOPT_SSL_VERIFYHOST] = 0;
        }

        if ( isset($this->m_userAndPassword) )
        {
            $options[CURLOPT_USERPWD] = $this->m_userAndPassword;
        }

        // SSL options.
        if ( isset($this->m_sslCertificateFp) )
        {
            $options[CURLOPT_SSLCERT] = $this->m_sslCertificateFp;
        }
        if ( isset($this->m_sslPrivateKeyFp) )
        {
            $options[CURLOPT_SSLKEY] = $this->m_sslPrivateKeyFp;
        }
        if ( isset($this->m_sslCertificateFormat) )
        {
            $options[CURLOPT_SSLCERTTYPE] = $this->m_sslCertificateFormat;
        }
        if ( isset($this->m_sslPrivateKeyFormat) )
        {
            $options[CURLOPT_SSLKEYTYPE] = $this->m_sslPrivateKeyFormat;
        }
        if ( isset($this->m_sslCertificatePassphrase) )
        {
            $options[CURLOPT_SSLCERTPASSWD] = $this->m_sslCertificatePassphrase;
        }
        if ( isset($this->m_sslPrivateKeyPassphrase) )
        {
            $options[CURLOPT_SSLKEYPASSWD] = $this->m_sslPrivateKeyPassphrase;
        }
        if ( isset($this->m_sslVersion) )
        {
            $options[CURLOPT_SSLVERSION] = $this->m_sslVersion;
        }
        if ( isset($this->m_sslCipherList) )
        {
            $options[CURLOPT_SSL_CIPHER_LIST] = $this->m_sslCipherList;
        }
        if ( isset($this->m_sslEngine) )
        {
            $options[CURLOPT_SSLENGINE] = $this->m_sslEngine;
        }
        if ( isset($this->m_sslDefaultEngine) )
        {
            $options[CURLOPT_SSLENGINE_DEFAULT] = $this->m_sslDefaultEngine;
        }

        if ( isset($this->m_useKerberos) && $this->m_useKerberos )
        {
            $options[CURLOPT_KRBLEVEL] = $this->m_kerberosLevel;
        }

        if ( isset($this->m_proxyAddress) )
        {
            // Use a proxy.

            $options[CURLOPT_PROXY] = $this->m_proxyAddress;

            if ( isset($this->m_proxyUserAndPassword) )
            {
                $options[CURLOPT_PROXYUSERPWD] = $this->m_proxyUserAndPassword;
            }

            if ( isset($this->m_proxyType) )
            {
                $proxyType;
                switch ( $this->m_proxyType )
                {
                case self::PROXY_HTTP:
                    $proxyType = CURLPROXY_HTTP;
                    break;
                case self::PROXY_SOCKS_5:
                    $proxyType = CURLPROXY_SOCKS5;
                    break;
                default:
                    assert( 'false', vs(isset($this), get_defined_vars()) );
                    break;
                }
                $options[CURLOPT_PROXYTYPE] = $proxyType;
            }

            if ( isset($this->m_proxyPort) )
            {
                $options[CURLOPT_PROXYPORT] = $this->m_proxyPort;
            }

            if ( isset($this->m_proxyTunneling) && $this->m_proxyTunneling )
            {
                $options[CURLOPT_HTTPPROXYTUNNEL] = true;
            }

            if ( isset($this->m_proxyConnectOnly) && $this->m_proxyConnectOnly )
            {
                $options[CURLOPT_CONNECT_ONLY] = true;
            }
        }

        if ( isset($this->m_outgoingInterface) )
        {
            $options[CURLOPT_INTERFACE] = $this->m_outgoingInterface;
        }

        // Speed limits.
        if ( isset($this->m_maxDownloadSpeed) )
        {
            $options[CURLOPT_MAX_RECV_SPEED_LARGE] = $this->m_maxDownloadSpeed;
        }
        if ( isset($this->m_maxUploadSpeed) )
        {
            $options[CURLOPT_MAX_SEND_SPEED_LARGE] = $this->m_maxUploadSpeed;
        }

        // Set cURL options.
        $res = curl_setopt_array($this->m_curl, $options);
        if ( !$res || !CString::isEmpty(curl_error($this->m_curl)) )
        {
            // Should never get in here as long as cURL options are being set correctly, hence the assertion.
            assert( 'false', vs(isset($this), get_defined_vars()) );
            $this->m_hasError = true;
            $curlError = curl_error($this->m_curl);
            $this->m_errorMessage = ( !CString::isEmpty($curlError) ) ? $curlError :
                "The 'curl_setopt_array' function failed.";
            $success = false;
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
        $requestSummary = curl_getinfo($this->m_curl);
        if ( is_cmap($requestSummary) )
        {
            $this->m_requestSummary = $requestSummary;
        }

        if ( $this->isHttp() )
        {
            // Put the response's HTTP headers into an associative array.
            $len = CArray::length($this->m_responseHeaders);
            for ($i = 0; $i < $len; $i++)
            {
                $foundGroups;
                CRegex::findGroups($this->m_responseHeaders[$i], "/^(.+?):\\h*(.*)/", $foundGroups);  // internal
                $headerNameLc = CString::toLowerCase($foundGroups[0]);
                $this->m_responseHeadersLcKeys[$headerNameLc] = $foundGroups[1];
            }
        }

        // Finalize.
        $this->finalize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function onRequestCompleteWithError ($errorMessage)
    {
        // To be used by "friend" classes only.
        $this->m_hasError = true;
        $this->m_errorMessage = $errorMessage;
        $this->finalize();
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function curl ()
    {
        // To be used by "friend" classes only.
        return $this->m_curl;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function isReturnTransferSet ()
    {
        // To be used by "friend" classes only.
        assert( 'isset($this->m_isReturnTransferSet)', vs(isset($this), get_defined_vars()) );
        return $this->m_isReturnTransferSet;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function headerFunction ($curl, $headerLine)
    {
        $ret = CString::length($headerLine);
        $foundGroups;
        if ( CRegex::findGroups($headerLine, "/^\\s*(.+?)\\h*:\\h*(.*?)\\s*\\z/", $foundGroups) )
        {
            $this->addHeaderWithoutOverriding($this->m_responseHeaders, $foundGroups[0], $foundGroups[1]);
        }
        return $ret;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __destruct ()
    {
        if ( !$this->m_done )
        {
            $this->finalize();
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function addHeaderWithoutOverriding ($headers, $headerName, $value)
    {
        $headerLine;
        $headerName = CString::trim($headerName);
        $value = CString::trim($value);
        $foundHeaderPos;
        $alreadyExists = CArray::find($headers, $headerName, function ($element0, $element1)
            {
                return CRegex::find($element0, "/^\\h*" . CRegex::enterTd($element1) . "\\h*:/i");
            },
            $foundHeaderPos);
        if ( !$alreadyExists )
        {
            $headerLine = "$headerName: $value";
        }
        else
        {
            // The header already exists. Combine the header values, removing duplicates based on case-insensitive
            // equality.
            $currValue = CRegex::remove($headers[$foundHeaderPos], "/^.*?:\\h*/");
            CArray::remove($headers, $foundHeaderPos);
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
        CArray::push($headers, $headerLine);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function hasHeader ($headerName)
    {
        $headerName = CString::trim($headerName);
        return CArray::find($this->m_requestHeaders, $headerName, function ($element0, $element1)
            {
                return CRegex::find($element0, "/^\\h*" . CRegex::enterTd($element1) . "\\h*:/i");
            });
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function removeHeader ($headerName)
    {
        $headerName = CString::trim($headerName);
        CArray::removeByValue($this->m_requestHeaders, $headerName, function ($element0, $element1)
            {
                return CRegex::find($element0, "/^\\h*" . CRegex::enterTd($element1) . "\\h*:/i");
            });
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected function finalize ()
    {
        if ( $this->m_done )
        {
            return;
        }

        if ( is_resource($this->m_curl) )
        {
            curl_close($this->m_curl);
        }

        if ( isset($this->m_downloadFile) )
        {
            $this->m_downloadFile->done();
        }

        if ( isset($this->m_uploadFile) )
        {
            $this->m_uploadFile->done();
        }

        if ( isset($this->m_fileUploadTempFp) && CFile::exists($this->m_fileUploadTempFp) )
        {
            CFile::delete($this->m_fileUploadTempFp);
        }

        $this->m_done = true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function basicProtocol ($protocol)
    {
        $protocol = CString::toLowerCase($protocol);
        if ( CString::equals($protocol, "https") )
        {
            $protocol = "http";
        }
        else if ( CString::equals($protocol, "ftps") )
        {
            $protocol = "ftp";
        }
        return $protocol;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    // Properties and defaults.
    protected $m_url;
    protected $m_requestBasicProtocol;
    protected $m_type;
    protected $m_downloadDestinationFp;
    protected $m_curl;
    protected $m_done = false;
    protected $m_hasError = false;
    protected $m_errorMessage;
    protected $m_port;
    protected $m_requestTimeoutSeconds;
    protected $m_connectionTimeoutSeconds;
    protected $m_dnsCacheTimeoutSeconds;
    protected $m_redirection = true;
    protected $m_maxNumRedirections;
    protected $m_redirectionAutoReferer = true;
    protected $m_failOn400ResponseCodeOrGreater = true;
    protected $m_requestHeaders;
    protected $m_postQuery;
    protected $m_requestCookies;
    protected $m_downloadFile;
    protected $m_postFileUploadRecord;
    protected $m_nonPostFileUploadFp;
    protected $m_uploadFile;
    protected $m_fileUploadTempFp;
    protected $m_sendDefaultAcceptEncodingHeader = true;
    protected $m_sendDefaultUserAgentHeader = false;
    protected $m_requestedByteRange;
    protected $m_certificateVerification = true;
    protected $m_alternateCertificateFpOrDp;
    protected $m_hostVerification = true;
    protected $m_userAndPassword;
    protected $m_redirectionKeepAuth = false;
    protected $m_ftpCreateMissingDirectoriesForUpload = true;
    protected $m_ftpAppendUpload;
    protected $m_ftpQuoteCommands;
    protected $m_ftpPostQuoteCommands;
    protected $m_ftpUseEpsv;
    protected $m_ftpActiveModeBackAddress;
    protected $m_ftpUseEprt;
    protected $m_sslCertificateFp;
    protected $m_sslPrivateKeyFp;
    protected $m_sslCertificateFormat;
    protected $m_sslPrivateKeyFormat;
    protected $m_sslCertificatePassphrase;
    protected $m_sslPrivateKeyPassphrase;
    protected $m_sslVersion;
    protected $m_sslCipherList;
    protected $m_sslEngine;
    protected $m_sslDefaultEngine;
    protected $m_useKerberos;
    protected $m_kerberosLevel;
    protected $m_proxyAddress;
    protected $m_proxyUserAndPassword;
    protected $m_proxyType;
    protected $m_proxyPort;
    protected $m_proxyTunneling;
    protected $m_proxyConnectOnly;
    protected $m_outgoingInterface;
    protected $m_echoResponse = false;
    protected $m_verboseOutput;
    protected $m_maxDownloadSpeed;
    protected $m_maxUploadSpeed;
    protected $m_requestSummary;
    protected $m_responseHeaders;
    protected $m_responseHeadersLcKeys;
    protected $m_isReturnTransferSet;

    // If the default user agent string is used, each "x.x.x" will be replaced with actual versions of curl/libcurl
    // and OpenSSL.
    protected static $ms_defaultUserAgent = "curl/x.x.x (unknown) libcurl x.x.x (OpenSSL x.x.x) (ipv6 enabled)";
}
