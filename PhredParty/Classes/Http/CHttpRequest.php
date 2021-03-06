<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that enumerates the HTTP methods and HTTP request headers. This is also the base class for the CRequest
 * class.
 */

class CHttpRequest extends CRootClass
{
    // Methods.
    /**
     * `enum` GET method.
     *
     * @var enum
     */
    const GET = 0;
    /**
     * `enum` HEAD method.
     *
     * @var enum
     */
    const HEAD = 1;
    /**
     * `enum` POST method.
     *
     * @var enum
     */
    const POST = 2;
    /**
     * `enum` PUT method.
     *
     * @var enum
     */
    const PUT = 3;
    /**
     * `enum` DELETE method.
     *
     * @var enum
     */
    const DELETE = 4;
    /**
     * `enum` OPTIONS method.
     *
     * @var enum
     */
    const OPTIONS = 5;
    /**
     * `enum` TRACE method.
     *
     * @var enum
     */
    const TRACE = 6;
    /**
     * `enum` CONNECT method.
     *
     * @var enum
     */
    const CONNECT = 7;
    /**
     * `enum` UNKNOWN method.
     *
     * @var enum
     */
    const UNKNOWN = 8;

    // Headers.
    /**
     * `string` "Accept" Types that are acceptable,
     * e.g. "text/html,application/xhtml+xml,application/xml;q=0.9,text/*;q=0.8".
     *
     * @var string
     */
    const ACCEPT = "Accept";
    /**
     * `string` "Accept-Charset" Character sets that are acceptable, e.g. "utf-8".
     *
     * @var string
     */
    const ACCEPT_CHARSET = "Accept-Charset";
    /**
     * `string` "Accept-Datetime" Acceptable version in time, e.g. "Thu, 31 May 2007 20:35:00 GMT".
     *
     * @var string
     */
    const ACCEPT_DATETIME = "Accept-Datetime";
    /**
     * `string` "Accept-Encoding" List of acceptable encodings, e.g. "gzip, deflate".
     *
     * @var string
     */
    const ACCEPT_ENCODING = "Accept-Encoding";
    /**
     * `string` "Accept-Language" List of acceptable human languages for response, e.g. "en-US,en;q=0.5".
     *
     * @var string
     */
    const ACCEPT_LANGUAGE = "Accept-Language";
    /**
     * `string` "Authorization" Authentication credentials for HTTP authentication,
     * e.g. "Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==".
     *
     * @var string
     */
    const AUTHORIZATION = "Authorization";
    /**
     * `string` "Cache-Control" Directives that must be obeyed along the request/response chain, e.g. "no-cache".
     *
     * @var string
     */
    const CACHE_CONTROL = "Cache-Control";
    /**
     * `string` "Connection" What type of connection the user-agent would prefer, e.g. "keep-alive".
     *
     * @var string
     */
    const CONNECTION = "Connection";
    /**
     * `string` "Content-Length" The length of the request body in bytes, e.g. "348".
     *
     * @var string
     */
    const CONTENT_LENGTH = "Content-Length";
    /**
     * `string` "Content-MD5" A Base64-encoded binary MD5 sum of the content of the request body,
     * e.g. "Q2hlY2sgSW50ZWdyaXR5IQ==".
     *
     * @var string
     */
    const CONTENT_MD5 = "Content-MD5";
    /**
     * `string` "Content-Type" The MIME type of the body of the request (for POST and PUT),
     * e.g. "application/x-www-form-urlencoded".
     *
     * @var string
     */
    const CONTENT_TYPE = "Content-Type";
    /**
     * `string` "Cookie" An HTTP cookie previously sent by the server with Set-Cookie, e.g. "version=1; skin=new".
     *
     * @var string
     */
    const COOKIE = "Cookie";
    /**
     * `string` "Date" The date and time that the message was sent, e.g. "Thu, 31 May 2007 20:35:00 GMT".
     *
     * @var string
     */
    const DATE = "Date";
    /**
     * `string` "DNT" Requests a web application to disable their tracking of a user, e.g. "1".
     *
     * @var string
     */
    const DNT = "DNT";
    /**
     * `string` "Expect" Indicates that particular server behaviors are required by the client, e.g. "100-continue".
     *
     * @var string
     */
    const EXPECT = "Expect";
    /**
     * `string` "From" The email address of the user making the request, e.g. "user@example.com".
     *
     * @var string
     */
    const FROM = "From";
    /**
     * `string` "Host" The target domain name, with or without the port number, e.g. "www.example.com:80"; required.
     *
     * @var string
     */
    const HOST = "Host";
    /**
     * `string` "If-Match" Ignore the request if the ETag doesn't match (for PUT),
     * e.g. ""737060cd8c284d8af7ad3082f209582d"".
     *
     * @var string
     */
    const IF_MATCH = "If-Match";
    /**
     * `string` "If-Modified-Since" Allows 304 Not Modified to be returned if content is same,
     * e.g. "Thu, 31 May 2007 20:35:00 GMT".
     *
     * @var string
     */
    const IF_MODIFIED_SINCE = "If-Modified-Since";
    /**
     * `string` "If-None-Match" Allows 304 Not Modified to be returned if content is same,
     * e.g. ""737060cd8c284d8af7ad3082f209582d"".
     *
     * @var string
     */
    const IF_NONE_MATCH = "If-None-Match";
    /**
     * `string` "If-Range" If unchanged, send the range, or entire entity otherwise,
     * e.g. ""737060cd8c284d8af7ad3082f209582d"".
     *
     * @var string
     */
    const IF_RANGE = "If-Range";
    /**
     * `string` "If-Unmodified-Since" Only respond if the entity hasn't been modified since this time,
     * e.g. "Thu, 31 May 2007 20:35:00 GMT".
     *
     * @var string
     */
    const IF_UNMODIFIED_SINCE = "If-Unmodified-Since";
    /**
     * `string` "Max-Forwards" Maximum number of times the message can be forwarded through proxies or gateways,
     * e.g. "10".
     *
     * @var string
     */
    const MAX_FORWARDS = "Max-Forwards";
    /**
     * `string` "Origin" Initiates a request for cross-origin resource sharing,
     * e.g. "http://www.example-social-network.com".
     *
     * @var string
     */
    const ORIGIN = "Origin";
    /**
     * `string` "Pragma" Implementation-specific values for anywhere along the request-response chain, e.g. "no-cache".
     *
     * @var string
     */
    const PRAGMA = "Pragma";
    /**
     * `string` "Proxy-Authorization" Authorization credentials for connecting to a proxy,
     * e.g. "Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==".
     *
     * @var string
     */
    const PROXY_AUTHORIZATION = "Proxy-Authorization";
    /**
     * `string` "Range" Request only a part of the entity, e.g. "bytes=500-999".
     *
     * @var string
     */
    const RANGE = "Range";
    /**
     * `string` "Referer" The URL from which the request was followed, e.g. "http://example.com/links".
     *
     * @var string
     */
    const REFERER = "Referer";
    /**
     * `string` "TE" The transfer encodings the user agent is willing to accept, e.g. "trailers, deflate".
     *
     * @var string
     */
    const TE = "TE";
    /**
     * `string` "Upgrade" Ask the server to upgrade to another protocol, e.g. "HTTP/1.1".
     *
     * @var string
     */
    const UPGRADE = "Upgrade";
    /**
     * `string` "User-Agent" The user agent string of the user agent, e.g. "Mozilla/5.0 Gecko/20100101 Firefox/25.0".
     *
     * @var string
     */
    const USER_AGENT = "User-Agent";
    /**
     * `string` "Via" The proxies through which the request was sent, e.g. "1.0 fred, 1.1 example.com".
     *
     * @var string
     */
    const VIA = "Via";
    /**
     * `string` "Warning" A general warning, e.g. "199 Miscellaneous warning".
     *
     * @var string
     */
    const WARNING = "Warning";
    /**
     * `string` "X-Forwarded-For" The originating IP address if the client is connecting through a proxy/balancer,
     * e.g. "129.78.138.66".
     *
     * @var string
     */
    const X_FORWARDED_FOR = "X-Forwarded-For";
    /**
     * `string` "X-Forwarded-Proto" The originating protocol of a request, e.g. "https".
     *
     * @var string
     */
    const X_FORWARDED_PROTO = "X-Forwarded-Proto";
    /**
     * `string` "X-Requested-With" Mainly used to identify Ajax requests, e.g. "XMLHttpRequest".
     *
     * @var string
     */
    const X_REQUESTED_WITH = "X-Requested-With";

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
