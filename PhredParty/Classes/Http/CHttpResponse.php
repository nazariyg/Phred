<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that enumerates the HTTP response codes and HTTP response headers. This is also the base class for the
 * CResponse class.
 */

class CHttpResponse extends CRootClass
{
    // Response code strings.
    // Informational.
    /**
     * `string` "100 Continue"
     *
     * @var string
     */
    const CONTINUE_WITH_BODY = "100 Continue";
    /**
     * `string` "101 Switching Protocols"
     *
     * @var string
     */
    const SWITCHING_PROTOCOLS = "101 Switching Protocols";
    /**
     * `string` "102 Processing"
     *
     * @var string
     */
    const PROCESSING = "102 Processing";
    // Success.
    /**
     * `string` "200 OK"
     *
     * @var string
     */
    const OK = "200 OK";
    /**
     * `string` "201 Created"
     *
     * @var string
     */
    const CREATED = "201 Created";
    /**
     * `string` "202 Accepted"
     *
     * @var string
     */
    const ACCEPTED = "202 Accepted";
    /**
     * `string` "203 Non-Authoritative Information"
     *
     * @var string
     */
    const NON_AUTHORITATIVE_INFORMATION = "203 Non-Authoritative Information";
    /**
     * `string` "204 No Content"
     *
     * @var string
     */
    const NO_CONTENT = "204 No Content";
    /**
     * `string` "205 Reset Content"
     *
     * @var string
     */
    const RESET_CONTENT = "205 Reset Content";
    /**
     * `string` "206 Partial Content"
     *
     * @var string
     */
    const PARTIAL_CONTENT = "206 Partial Content";
    /**
     * `string` "207 Multi-Status"
     *
     * @var string
     */
    const MULTI_STATUS = "207 Multi-Status";
    /**
     * `string` "208 Already Reported"
     *
     * @var string
     */
    const ALREADY_REPORTED = "208 Already Reported";
    /**
     * `string` "226 IM Used"
     *
     * @var string
     */
    const IM_USED = "226 IM Used";
    // Redirection.
    /**
     * `string` "300 Multiple Choices"
     *
     * @var string
     */
    const MULTIPLE_CHOICES = "300 Multiple Choices";
    /**
     * `string` "301 Moved Permanently"
     *
     * @var string
     */
    const MOVED_PERMANENTLY = "301 Moved Permanently";
    /**
     * `string` "302 Found"
     *
     * @var string
     */
    const FOUND = "302 Found";
    /**
     * `string` "303 See Other"
     *
     * @var string
     */
    const SEE_OTHER = "303 See Other";
    /**
     * `string` "304 Not Modified"
     *
     * @var string
     */
    const NOT_MODIFIED = "304 Not Modified";
    /**
     * `string` "305 Use Proxy"
     *
     * @var string
     */
    const USE_PROXY = "305 Use Proxy";
    /**
     * `string` "306 Switch Proxy"
     *
     * @var string
     */
    const SWITCH_PROXY = "306 Switch Proxy";
    /**
     * `string` "307 Temporary Redirect"
     *
     * @var string
     */
    const TEMPORARY_REDIRECT = "307 Temporary Redirect";
    /**
     * `string` "308 Permanent Redirect"
     *
     * @var string
     */
    const PERMANENT_REDIRECT = "308 Permanent Redirect";
    // Client Error.
    /**
     * `string` "400 Bad Request"
     *
     * @var string
     */
    const BAD_REQUEST = "400 Bad Request";
    /**
     * `string` "401 Unauthorized"
     *
     * @var string
     */
    const UNAUTHORIZED = "401 Unauthorized";
    /**
     * `string` "402 Payment Required"
     *
     * @var string
     */
    const PAYMENT_REQUIRED = "402 Payment Required";
    /**
     * `string` "403 Forbidden"
     *
     * @var string
     */
    const FORBIDDEN = "403 Forbidden";
    /**
     * `string` "404 Not Found"
     *
     * @var string
     */
    const NOT_FOUND = "404 Not Found";
    /**
     * `string` "405 Method Not Allowed"
     *
     * @var string
     */
    const METHOD_NOT_ALLOWED = "405 Method Not Allowed";
    /**
     * `string` "406 Not Acceptable"
     *
     * @var string
     */
    const NOT_ACCEPTABLE = "406 Not Acceptable";
    /**
     * `string` "407 Proxy Authentication Required"
     *
     * @var string
     */
    const PROXY_AUTHENTICATION_REQUIRED = "407 Proxy Authentication Required";
    /**
     * `string` "408 Request Timeout"
     *
     * @var string
     */
    const REQUEST_TIMEOUT = "408 Request Timeout";
    /**
     * `string` "409 Conflict"
     *
     * @var string
     */
    const CONFLICT = "409 Conflict";
    /**
     * `string` "410 Gone"
     *
     * @var string
     */
    const GONE = "410 Gone";
    /**
     * `string` "411 Length Required"
     *
     * @var string
     */
    const LENGTH_REQUIRED = "411 Length Required";
    /**
     * `string` "412 Precondition Failed"
     *
     * @var string
     */
    const PRECONDITION_FAILED = "412 Precondition Failed";
    /**
     * `string` "413 Request Entity Too Large"
     *
     * @var string
     */
    const REQUEST_ENTITY_TOO_LARGE = "413 Request Entity Too Large";
    /**
     * `string` "414 Request-URI Too Long"
     *
     * @var string
     */
    const REQUEST_URI_TOO_LONG = "414 Request-URI Too Long";
    /**
     * `string` "415 Unsupported Media Type"
     *
     * @var string
     */
    const UNSUPPORTED_MEDIA_TYPE = "415 Unsupported Media Type";
    /**
     * `string` "416 Requested Range Not Satisfiable"
     *
     * @var string
     */
    const REQUESTED_RANGE_NOT_SATISFIABLE = "416 Requested Range Not Satisfiable";
    /**
     * `string` "417 Expectation Failed"
     *
     * @var string
     */
    const EXPECTATION_FAILED = "417 Expectation Failed";
    /**
     * `string` "418 I'm a teapot"
     *
     * @var string
     */
    const I_M_A_TEAPOT = "418 I'm a teapot";
    /**
     * `string` "419 Authentication Timeout"
     *
     * @var string
     */
    const AUTHENTICATION_TIMEOUT = "419 Authentication Timeout";
    /**
     * `string` "420 Enhance Your Calm"
     *
     * @var string
     */
    const ENHANCE_YOUR_CALM = "420 Enhance Your Calm";
    /**
     * `string` "422 Unprocessable Entity"
     *
     * @var string
     */
    const UNPROCESSABLE_ENTITY = "422 Unprocessable Entity";
    /**
     * `string` "423 Locked"
     *
     * @var string
     */
    const LOCKED = "423 Locked";
    /**
     * `string` "424 Failed Dependency"
     *
     * @var string
     */
    const FAILED_DEPENDENCY = "424 Failed Dependency";
    /**
     * `string` "424 Method Failure"
     *
     * @var string
     */
    const METHOD_FAILURE = "424 Method Failure";
    /**
     * `string` "425 Unordered Collection"
     *
     * @var string
     */
    const UNORDERED_COLLECTION = "425 Unordered Collection";
    /**
     * `string` "426 Upgrade Required"
     *
     * @var string
     */
    const UPGRADE_REQUIRED = "426 Upgrade Required";
    /**
     * `string` "428 Precondition Required"
     *
     * @var string
     */
    const PRECONDITION_REQUIRED = "428 Precondition Required";
    /**
     * `string` "429 Too Many Requests"
     *
     * @var string
     */
    const TOO_MANY_REQUESTS = "429 Too Many Requests";
    /**
     * `string` "431 Request Header Fields Too Large"
     *
     * @var string
     */
    const REQUEST_HEADER_FIELDS_TOO_LARGE = "431 Request Header Fields Too Large";
    /**
     * `string` "440 Login Timeout"
     *
     * @var string
     */
    const LOGIN_TIMEOUT = "440 Login Timeout";
    /**
     * `string` "444 No Response"
     *
     * @var string
     */
    const NO_RESPONSE = "444 No Response";
    /**
     * `string` "449 Retry With"
     *
     * @var string
     */
    const RETRY_WITH = "449 Retry With";
    /**
     * `string` "450 Blocked by Windows Parental Controls"
     *
     * @var string
     */
    const BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS = "450 Blocked by Windows Parental Controls";
    /**
     * `string` "451 Redirect"
     *
     * @var string
     */
    const REDIRECT = "451 Redirect";
    /**
     * `string` "451 Unavailable For Legal Reasons"
     *
     * @var string
     */
    const UNAVAILABLE_FOR_LEGAL_REASONS = "451 Unavailable For Legal Reasons";
    /**
     * `string` "494 Request Header Too Large"
     *
     * @var string
     */
    const REQUEST_HEADER_TOO_LARGE = "494 Request Header Too Large";
    /**
     * `string` "495 Cert Error"
     *
     * @var string
     */
    const CERT_ERROR = "495 Cert Error";
    /**
     * `string` "496 No Cert"
     *
     * @var string
     */
    const NO_CERT = "496 No Cert";
    /**
     * `string` "497 HTTP to HTTPS"
     *
     * @var string
     */
    const HTTP_TO_HTTPS = "497 HTTP to HTTPS";
    /**
     * `string` "499 Client Closed Request"
     *
     * @var string
     */
    const CLIENT_CLOSED_REQUEST = "499 Client Closed Request";
    // Server Error.
    /**
     * `string` "500 Internal Server Error"
     *
     * @var string
     */
    const INTERNAL_SERVER_ERROR = "500 Internal Server Error";
    /**
     * `string` "501 Not Implemented"
     *
     * @var string
     */
    const NOT_IMPLEMENTED = "501 Not Implemented";
    /**
     * `string` "502 Bad Gateway"
     *
     * @var string
     */
    const BAD_GATEWAY = "502 Bad Gateway";
    /**
     * `string` "503 Service Unavailable"
     *
     * @var string
     */
    const SERVICE_UNAVAILABLE = "503 Service Unavailable";
    /**
     * `string` "504 Gateway Timeout"
     *
     * @var string
     */
    const GATEWAY_TIMEOUT = "504 Gateway Timeout";
    /**
     * `string` "505 HTTP Version Not Supported"
     *
     * @var string
     */
    const HTTP_VERSION_NOT_SUPPORTED = "505 HTTP Version Not Supported";
    /**
     * `string` "506 Variant Also Negotiates"
     *
     * @var string
     */
    const VARIANT_ALSO_NEGOTIATES = "506 Variant Also Negotiates";
    /**
     * `string` "507 Insufficient Storage"
     *
     * @var string
     */
    const INSUFFICIENT_STORAGE = "507 Insufficient Storage";
    /**
     * `string` "508 Loop Detected"
     *
     * @var string
     */
    const LOOP_DETECTED = "508 Loop Detected";
    /**
     * `string` "509 Bandwidth Limit Exceeded"
     *
     * @var string
     */
    const BANDWIDTH_LIMIT_EXCEEDED = "509 Bandwidth Limit Exceeded";
    /**
     * `string` "510 Not Extended"
     *
     * @var string
     */
    const NOT_EXTENDED = "510 Not Extended";
    /**
     * `string` "511 Network Authentication Required"
     *
     * @var string
     */
    const NETWORK_AUTHENTICATION_REQUIRED = "511 Network Authentication Required";
    /**
     * `string` "520 Origin Error"
     *
     * @var string
     */
    const ORIGIN_ERROR = "520 Origin Error";
    /**
     * `string` "522 Connection timed out"
     *
     * @var string
     */
    const CONNECTION_TIMED_OUT = "522 Connection timed out";
    /**
     * `string` "523 Proxy Declined Request"
     *
     * @var string
     */
    const PROXY_DECLINED_REQUEST = "523 Proxy Declined Request";
    /**
     * `string` "524 A timeout occurred"
     *
     * @var string
     */
    const A_TIMEOUT_OCCURRED = "524 A timeout occurred";
    /**
     * `string` "598 Network read timeout error"
     *
     * @var string
     */
    const NETWORK_READ_TIMEOUT_ERROR = "598 Network read timeout error";
    /**
     * `string` "599 Network connect timeout error"
     *
     * @var string
     */
    const NETWORK_CONNECT_TIMEOUT_ERROR = "599 Network connect timeout error";

    // Headers.
    /**
     * `string` "Accept-Ranges" What partial content range types this server supports, e.g. "bytes".
     *
     * @var string
     */
    const ACCEPT_RANGES = "Accept-Ranges";
    /**
     * `string` "Access-Control-Allow-Origin" Which web sites can participate in cross-origin resource sharing,
     * e.g. "*".
     *
     * @var string
     */
    const ACCESS_CONTROL_ALLOW_ORIGIN = "Access-Control-Allow-Origin";
    /**
     * `string` "Age" The age the object has been in a proxy cache, in seconds, e.g. "12".
     *
     * @var string
     */
    const AGE = "Age";
    /**
     * `string` "Allow" Valid actions for a specified resource, e.g. "GET, HEAD".
     *
     * @var string
     */
    const ALLOW = "Allow";
    /**
     * `string` "Cache-Control" How the entity can be cached along the response chain or caching age, e.g. "no-cache".
     *
     * @var string
     */
    const CACHE_CONTROL = "Cache-Control";
    /**
     * `string` "Connection" Options that are desired for the connection, e.g. "keep-alive".
     *
     * @var string
     */
    const CONNECTION = "Connection";
    /**
     * `string` "Content-Disposition" For the client to begin downloading the file,
     * e.g. "attachment; filename="fname.ext"".
     *
     * @var string
     */
    const CONTENT_DISPOSITION = "Content-Disposition";
    /**
     * `string` "Content-Encoding" The type of encoding used on the data, e.g. "gzip".
     *
     * @var string
     */
    const CONTENT_ENCODING = "Content-Encoding";
    /**
     * `string` "Content-Language" The language the content is in, e.g. "en".
     *
     * @var string
     */
    const CONTENT_LANGUAGE = "Content-Language";
    /**
     * `string` "Content-Length" The length of the response body in bytes, e.g. "348".
     *
     * @var string
     */
    const CONTENT_LENGTH = "Content-Length";
    /**
     * `string` "Content-Location" An alternate location for the returned data, e.g. "/index.html".
     *
     * @var string
     */
    const CONTENT_LOCATION = "Content-Location";
    /**
     * `string` "Content-MD5" A Base64-encoded MD5 sum of the response's body, e.g. "Q2hlY2sgSW50ZWdyaXR5IQ==".
     *
     * @var string
     */
    const CONTENT_MD5 = "Content-MD5";
    /**
     * `string` "Content-Range" Where in the full body this partial message belongs, e.g. "bytes 21010-47021/47022".
     *
     * @var string
     */
    const CONTENT_RANGE = "Content-Range";
    /**
     * `string` "Content-Security-Policy" Content Security Policy definition, e.g. "default-src 'self'".
     *
     * @var string
     */
    const CONTENT_SECURITY_POLICY = "Content-Security-Policy";
    /**
     * `string` "X-WebKit-CSP" (Older header name) Content Security Policy definition, e.g. "default-src 'self'".
     *
     * @var string
     */
    const CONTENT_SECURITY_POLICY_ALT = "X-WebKit-CSP";
    /**
     * `string` "Content-Type" The MIME type of this content, e.g. "text/html; charset=utf-8".
     *
     * @var string
     */
    const CONTENT_TYPE = "Content-Type";
    /**
     * `string` "Date" The date and time that the message was sent, e.g. "Thu, 31 May 2007 20:35:00 GMT".
     *
     * @var string
     */
    const DATE = "Date";
    /**
     * `string` "ETag" A message digest or a version ID, e.g. ""737060cd8c284d8af7ad3082f209582d"".
     *
     * @var string
     */
    const ETAG = "ETag";
    /**
     * `string` "Expires" The time for the response to become stale, e.g. "Thu, 31 May 2007 20:35:00 GMT".
     *
     * @var string
     */
    const EXPIRES = "Expires";
    /**
     * `string` "Last-Modified" The time when the response was last modified, e.g. "Thu, 31 May 2007 20:35:00 GMT".
     *
     * @var string
     */
    const LAST_MODIFIED = "Last-Modified";
    /**
     * `string` "Link" A typed relationship with another resource, e.g. "</feed>; rel="alternate"".
     *
     * @var string
     */
    const LINK = "Link";
    /**
     * `string` "Location" For redirection or when a new resource has been created, e.g. "http://example.com".
     *
     * @var string
     */
    const LOCATION = "Location";
    /**
     * `string` "P3P" A P3P policy.
     *
     * @var string
     */
    const P3P = "P3P";
    /**
     * `string` "Pragma" Implementation-specific values for anywhere along the chain, e.g. "no-cache".
     *
     * @var string
     */
    const PRAGMA = "Pragma";
    /**
     * `string` "Proxy-Authenticate" Request authentication to access the proxy, e.g. "Basic".
     *
     * @var string
     */
    const PROXY_AUTHENTICATE = "Proxy-Authenticate";
    /**
     * `string` "Refresh" For redirection or when a new resource has been created, e.g. "5; url=http://go.com".
     *
     * @var string
     */
    const REFRESH = "Refresh";
    /**
     * `string` "Retry-After" Instructs the client to try again later, as number of seconds or as time, e.g. "120".
     *
     * @var string
     */
    const RETRY_AFTER = "Retry-After";
    /**
     * `string` "Server" A name for the server, e.g. "nginx/1.0.0".
     *
     * @var string
     */
    const SERVER = "Server";
    /**
     * `string` "Set-Cookie" Set an HTTP cookie, e.g. "version=1; Path=/; Expires=Wed, 09 Jun 2021 10:18:14 GMT".
     *
     * @var string
     */
    const SET_COOKIE = "Set-Cookie";
    /**
     * `string` "Status" The HTTP status of the response, e.g. "200 OK".
     *
     * @var string
     */
    const STATUS = "Status";
    /**
     * `string` "Strict-Transport-Security" An HSTS Policy, e.g. "max-age=16070400; includeSubDomains".
     *
     * @var string
     */
    const STRICT_TRANSPORT_SECURITY = "Strict-Transport-Security";
    /**
     * `string` "Trailer" Details on a message encoded with chunked transfer-coding, e.g. "Max-Forwards".
     *
     * @var string
     */
    const TRAILER = "Trailer";
    /**
     * `string` "Transfer-Encoding" The form of encoding used in the transfer, e.g. "chunked".
     *
     * @var string
     */
    const TRANSFER_ENCODING = "Transfer-Encoding";
    /**
     * `string` "Vary" Tells downstream proxies about header-based caching policy, e.g. "*".
     *
     * @var string
     */
    const VARY = "Vary";
    /**
     * `string` "Via" The proxies through which the response was sent, e.g. "1.0 fred, 1.1 example.com".
     *
     * @var string
     */
    const VIA = "Via";
    /**
     * `string` "Warning" A general warning about the response's body, e.g. "199 Miscellaneous warning".
     *
     * @var string
     */
    const WARNING = "Warning";
    /**
     * `string` "WWW-Authenticate" The authentication scheme that should be used, e.g. "Basic".
     *
     * @var string
     */
    const WWW_AUTHENTICATE = "WWW-Authenticate";
    /**
     * `string` "X-Content-Type-Options" Prevents IE from sniffing the response away from the Content-Type,
     * e.g. "nosniff".
     *
     * @var string
     */
    const X_CONTENT_TYPE_OPTIONS = "X-Content-Type-Options";
    /**
     * `string` "X-Frame-Options" Protection from clickjacking via use of frames, e.g. "deny".
     *
     * @var string
     */
    const X_FRAME_OPTIONS = "X-Frame-Options";
    /**
     * `string` "X-Powered-By" Specifies the technology supporting the web application, e.g. "PHP/5.9.0".
     *
     * @var string
     */
    const X_POWERED_BY = "X-Powered-By";
    /**
     * `string` "X-UA-Compatible" Recommends the preferred rendering engine to be used by the client, e.g. "IE=edge".
     *
     * @var string
     */
    const X_UA_COMPATIBLE = "X-UA-Compatible";
    /**
     * `string` "X-XSS-Protection" Cross-site scripting (XSS) filter, e.g. "1; mode=block".
     *
     * @var string
     */
    const X_XSS_PROTECTION = "X-XSS-Protection";

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Currently no-op.
     */

    public function __construct ()
    {
        // Empty.
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
