<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CUrlTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $oUrl = new CUrl("http://www.example.com");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/"));

        $oUrl = new CUrl("WWW.EXAMPLE.COM");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/"));

        $oUrl = new CUrl("93.184.216.119");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://93.184.216.119/"));

        $oUrl = new CUrl("[0:0:0:0:0:FFFF:5DB8:D877]");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://[0:0:0:0:0:ffff:5db8:d877]/"));

        $oUrl = new CUrl("http://www.example.com:443");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com:443/"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/path/to/some%20item"));

        $oUrl = new CUrl("http://www.example.com/?name=value");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/?name=value"));

        $oUrl = new CUrl("http://www.example.com/?name=value0&name=value1");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/?name=value1"));

        $oUrl = new CUrl("http://www.example.com/?name1&name0=value");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/?name0=value&name1="));

        $oUrl = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/#fragment_id"));

        $oUrl = new CUrl("http://user@www.example.com");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://user@www.example.com/"));

        $oUrl = new CUrl("http://user:password@www.example.com");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://user:password@www.example.com/"));

        $oUrl = new CUrl("HTTP://www.example.com/path/to/some%3dite%6D");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/path/to/some%3Ditem"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUrl ()
    {
        $oUrl = new CUrl("example.com");
        $this->assertTrue($oUrl->url()->equals("example.com"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasProtocol ()
    {
        $oUrl = new CUrl("http://www.example.com/");
        $this->assertTrue($oUrl->hasProtocol());

        $oUrl = new CUrl("www.example.com");
        $this->assertFalse($oUrl->hasProtocol());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testProtocol ()
    {
        $oUrl = new CUrl("http://www.example.com/");
        $this->assertTrue($oUrl->protocol()->equals("http"));

        $oUrl = new CUrl("https://www.example.com/");
        $this->assertTrue($oUrl->protocol()->equals("https"));

        $oUrl = new CUrl("FTP://WWW.EXAMPLE.COM/");
        $this->assertTrue($oUrl->protocol()->equals("FTP"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedProtocol ()
    {
        $oUrl = new CUrl("http://www.example.com/");
        $this->assertTrue($oUrl->normalizedProtocol()->equals("http"));

        $oUrl = new CUrl("HTTPS://WWW.EXAMPLE.COM/");
        $this->assertTrue($oUrl->normalizedProtocol()->equals("https"));

        $oUrl = new CUrl("FTP://WWW.EXAMPLE.COM/");
        $this->assertTrue($oUrl->normalizedProtocol()->equals("ftp"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHost ()
    {
        $oUrl = new CUrl("http://www.example.com/");
        $this->assertTrue($oUrl->host()->equals("www.example.com"));

        $oUrl = new CUrl("HTTP://WWW.EXAMPLE.COM/");
        $this->assertTrue($oUrl->host()->equals("WWW.EXAMPLE.COM"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedHost ()
    {
        $oUrl = new CUrl("http://www.example.com/");
        $this->assertTrue($oUrl->normalizedHost()->equals("www.example.com"));

        $oUrl = new CUrl("HTTP://WWW.EXAMPLE.COM/");
        $this->assertTrue($oUrl->normalizedHost()->equals("www.example.com"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPort ()
    {
        $oUrl = new CUrl("http://www.example.com:443/");
        $this->assertTrue($oUrl->hasPort());

        $oUrl = new CUrl("http://www.example.com/");
        $this->assertFalse($oUrl->hasPort());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPort ()
    {
        $oUrl = new CUrl("http://www.example.com:443/");
        $this->assertTrue( $oUrl->port() === 443 );

        $oUrl = new CUrl("http://www.example.com:8080/");
        $this->assertTrue( $oUrl->port() === 8080 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPath ()
    {
        $oUrl = new CUrl("http://www.example.com/");  // "/" is the path
        $this->assertTrue($oUrl->hasPath());

        $oUrl = new CUrl("http://www.example.com/path/to/item");
        $this->assertTrue($oUrl->hasPath());

        $oUrl = new CUrl("http://www.example.com");
        $this->assertFalse($oUrl->hasPath());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPathString ()
    {
        $oUrl = new CUrl("http://www.example.com/");
        $this->assertTrue($oUrl->pathString()->equals("/"));

        $oUrl = new CUrl("http://www.example.com/path/to/item");
        $this->assertTrue($oUrl->pathString()->equals("/path/to/item"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20item");
        $this->assertTrue($oUrl->pathString()->equals("/path/to/some%20item"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($oUrl->pathString()->equals("/path/to/some%20ite%6D"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($oUrl->pathString(true)->equals("/path/to/some item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedPathString ()
    {
        $oUrl = new CUrl("http://www.example.com");  // path normalization makes an empty path be "/"
        $this->assertTrue($oUrl->normalizedPathString()->equals("/"));

        $oUrl = new CUrl("http://www.example.com/path/to/item");
        $this->assertTrue($oUrl->normalizedPathString()->equals("/path/to/item"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20item");
        $this->assertTrue($oUrl->normalizedPathString()->equals("/path/to/some%20item"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($oUrl->normalizedPathString()->equals("/path/to/some%20item"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($oUrl->normalizedPathString(true)->equals("/path/to/some item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPath ()
    {
        $oUrl = new CUrl("http://www.example.com");  // path normalization makes an empty path be "/"
        $this->assertTrue($oUrl->path()->pathString()->equals("/"));

        $oUrl = new CUrl("http://www.example.com/path/to/item");
        $oUrlPath = $oUrl->path();
        $this->assertTrue( $oUrlPath->pathString()->equals("/path/to/item") &&
            $oUrlPath->component(0)->equals("path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("item") );

        $oUrl = new CUrl("http://www.example.com/path/to/so%6De%20item");
        $this->assertTrue($oUrl->path()->pathString()->equals("/path/to/some%20item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasQuery ()
    {
        $oUrl = new CUrl("http://www.example.com/?name0=value0&name1=value1");
        $this->assertTrue($oUrl->hasQuery());

        $oUrl = new CUrl("http://www.example.com/");
        $this->assertFalse($oUrl->hasQuery());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testQueryString ()
    {
        $oUrl = new CUrl("http://www.example.com/?name0=value0&name1=value1");
        $this->assertTrue($oUrl->queryString()->equals("name0=value0&name1=value1"));

        $oUrl = new CUrl("http://www.example.com/?name0=value%200&name1=value%201");
        $this->assertTrue($oUrl->queryString(true)->equals("name0=value 0&name1=value 1"));

        $oUrl = new CUrl("http://www.example.com/?na%6De0=value0&na%6De1=value1");
        $this->assertTrue($oUrl->queryString()->equals("na%6De0=value0&na%6De1=value1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedQueryString ()
    {
        $oUrl = new CUrl("http://www.example.com/?name0=value0&name1=value1");
        $this->assertTrue($oUrl->normalizedQueryString()->equals("name0=value0&name1=value1"));

        $oUrl = new CUrl("http://www.example.com/?name0=value%200&name1=value%201");
        $this->assertTrue($oUrl->normalizedQueryString(true)->equals("name0=value 0&name1=value 1"));

        $oUrl = new CUrl("http://www.example.com/?na%6De0=value0&na%6De1=value1");
        $this->assertTrue($oUrl->normalizedQueryString()->equals("name0=value0&name1=value1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testQuery ()
    {
        $oUrl = new CUrl("http://www.example.com/?name0=value0&name1=value1");
        $this->assertTrue($oUrl->query()->queryString()->equals("name0=value0&name1=value1"));

        $oUrl = new CUrl("http://www.example.com/?na%6De0=value0&na%6De1=value1");
        $this->assertTrue($oUrl->query()->queryString()->equals("name0=value0&name1=value1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasFragmentId ()
    {
        $oUrl = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($oUrl->hasFragmentId());

        $oUrl = new CUrl("http://www.example.com/");
        $this->assertFalse($oUrl->hasFragmentId());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFragmentId ()
    {
        $oUrl = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($oUrl->fragmentId()->equals("fragment_id"));

        $oUrl = new CUrl("http://www.example.com/#frag%6Dent%20id");
        $this->assertTrue($oUrl->fragmentId()->equals("frag%6Dent%20id"));

        $oUrl = new CUrl("http://www.example.com/#frag%6Dent%20id");
        $this->assertTrue($oUrl->fragmentId(true)->equals("fragment id"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedFragmentId ()
    {
        $oUrl = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($oUrl->normalizedFragmentId()->equals("fragment_id"));

        $oUrl = new CUrl("http://www.example.com/#frag%6Dent%20id");
        $this->assertTrue($oUrl->normalizedFragmentId()->equals("fragment%20id"));

        $oUrl = new CUrl("http://www.example.com/#frag%6Dent%20id");
        $this->assertTrue($oUrl->normalizedFragmentId(true)->equals("fragment id"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasUser ()
    {
        $oUrl = new CUrl("http://user@www.example.com/");
        $this->assertTrue($oUrl->hasUser());

        $oUrl = new CUrl("http://www.example.com/");
        $this->assertFalse($oUrl->hasUser());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUser ()
    {
        $oUrl = new CUrl("http://user@www.example.com/");
        $this->assertTrue($oUrl->user()->equals("user"));

        $oUrl = new CUrl("http://USER@www.example.com/");
        $this->assertTrue($oUrl->user()->equals("USER"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPassword ()
    {
        $oUrl = new CUrl("http://user:password@www.example.com/");
        $this->assertTrue($oUrl->hasPassword());

        $oUrl = new CUrl("http://user@www.example.com/");
        $this->assertFalse($oUrl->hasPassword());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPassword ()
    {
        $oUrl = new CUrl("http://user:password@www.example.com/");
        $this->assertTrue($oUrl->password()->equals("password"));

        $oUrl = new CUrl("http://USER:PASSWORD@www.example.com/");
        $this->assertTrue($oUrl->password()->equals("PASSWORD"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedUrlWithoutFragmentId ()
    {
        $oUrl = new CUrl("http://www.example.com");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/"));

        $oUrl = new CUrl("WWW.EXAMPLE.COM");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/"));

        $oUrl = new CUrl("93.184.216.119");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://93.184.216.119/"));

        $oUrl = new CUrl("[0:0:0:0:0:FFFF:5DB8:D877]");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://[0:0:0:0:0:ffff:5db8:d877]/"));

        $oUrl = new CUrl("http://www.example.com:443");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://www.example.com:443/"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals(
            "http://www.example.com/path/to/some%20item"));

        $oUrl = new CUrl("http://www.example.com/?name=value");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/?name=value"));

        $oUrl = new CUrl("http://www.example.com/?name=value0&name=value1");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/?name=value1"));

        $oUrl = new CUrl("http://www.example.com/?name1&name0=value");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals(
            "http://www.example.com/?name0=value&name1="));

        $oUrl = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/"));

        $oUrl = new CUrl("http://user@www.example.com");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://user@www.example.com/"));

        $oUrl = new CUrl("http://user:password@www.example.com");
        $this->assertTrue($oUrl->normalizedUrlWithoutFragmentId()->equals("http://user:password@www.example.com/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedUrl ()
    {
        $oUrl = new CUrl("http://www.example.com");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/"));

        $oUrl = new CUrl("WWW.EXAMPLE.COM");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/"));

        $oUrl = new CUrl("93.184.216.119");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://93.184.216.119/"));

        $oUrl = new CUrl("[0:0:0:0:0:FFFF:5DB8:D877]");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://[0:0:0:0:0:ffff:5db8:d877]/"));

        $oUrl = new CUrl("http://www.example.com:443");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com:443/"));

        $oUrl = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/path/to/some%20item"));

        $oUrl = new CUrl("http://www.example.com/?name=value");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/?name=value"));

        $oUrl = new CUrl("http://www.example.com/?name=value0&name=value1");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/?name=value1"));

        $oUrl = new CUrl("http://www.example.com/?name1&name0=value");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/?name0=value&name1="));

        $oUrl = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://www.example.com/#fragment_id"));

        $oUrl = new CUrl("http://user@www.example.com");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://user@www.example.com/"));

        $oUrl = new CUrl("http://user:password@www.example.com");
        $this->assertTrue($oUrl->normalizedUrl()->equals("http://user:password@www.example.com/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $oUrl0 = new CUrl("http://www.example.com");
        $oUrl1 = new CUrl("HTTP://www.example.com/");
        $this->assertTrue($oUrl0->equals($oUrl1));

        $oUrl0 = new CUrl("http://www.example.com/?path/to/item");
        $oUrl1 = new CUrl("HTTP://www.example.com/?path/to/ite%6D");
        $this->assertTrue($oUrl0->equals($oUrl1));

        $oUrl0 = new CUrl("http://www.example.com/?path/to/item");
        $oUrl1 = new CUrl("http://www.example.com/?path/t0/item");
        $this->assertFalse($oUrl0->equals($oUrl1));

        $oUrl0 = new CUrl("http://www.example.com");
        $oUrl1 = new CUrl("HTTP://www.example.com/#fragment_id");
        $this->assertTrue($oUrl0->equals($oUrl1));

        $oUrl0 = new CUrl("http://www.example.com");
        $oUrl1 = new CUrl("HTTP://www.example.com/#fragment_id");
        $this->assertFalse($oUrl0->equals($oUrl1, false));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEnsureProtocol ()
    {
        $this->assertTrue(CUrl::ensureProtocol("www.example.com")->equals("http://www.example.com"));
        $this->assertTrue(CUrl::ensureProtocol("https://www.example.com")->equals("https://www.example.com"));
        $this->assertTrue(CUrl::ensureProtocol("ftp://www.example.com")->equals("ftp://www.example.com"));
        $this->assertTrue(CUrl::ensureProtocol("www.example.com/", "https")->equals("https://www.example.com/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsValid ()
    {
        $this->assertTrue(CUrl::isValid("http://www.example.com"));
        $this->assertTrue(CUrl::isValid("www.example.com", true));
        $this->assertTrue(CUrl::isValid("93.184.216.119", true));
        $this->assertTrue(CUrl::isValid("[0:0:0:0:0:ffff:5db8:d877]", true));

        $this->assertFalse(CUrl::isValid("Hello there!"));
        $this->assertFalse(CUrl::isValid(" ", true));
        $this->assertFalse(CUrl::isValid("http://www.example.com "));
        $this->assertFalse(CUrl::isValid("http:/www.example.com"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalize ()
    {
        $this->assertTrue(CUrl::normalize("http://www.example.com", false)->equals("http://www.example.com/"));

        $this->assertTrue(CUrl::normalize("WWW.EXAMPLE.COM", false)->equals("http://www.example.com/"));

        $this->assertTrue(CUrl::normalize("93.184.216.119", false)->equals("http://93.184.216.119/"));

        $this->assertTrue(CUrl::normalize("[0:0:0:0:0:FFFF:5DB8:D877]", false)->equals(
            "http://[0:0:0:0:0:ffff:5db8:d877]/"));

        $this->assertTrue(CUrl::normalize("http://www.example.com:443", false)->equals("http://www.example.com:443/"));

        $this->assertTrue(CUrl::normalize("http://www.example.com/path/to/some%20ite%6D", false)->equals(
            "http://www.example.com/path/to/some%20item"));

        $this->assertTrue(CUrl::normalize("http://www.example.com/?name=value", false)->equals(
            "http://www.example.com/?name=value"));

        $this->assertTrue(CUrl::normalize("http://www.example.com/?name=value0&name=value1", false)->equals(
            "http://www.example.com/?name=value1"));

        $this->assertTrue(CUrl::normalize("http://www.example.com/?name1&name0=value", false)->equals(
            "http://www.example.com/?name0=value&name1="));

        $this->assertTrue(CUrl::normalize("http://www.example.com/#fragment_id", false)->equals(
            "http://www.example.com/"));

        $this->assertTrue(CUrl::normalize("http://www.example.com/#fragment_id", true)->equals(
            "http://www.example.com/#fragment_id"));

        $this->assertTrue(CUrl::normalize("http://user@www.example.com", false)->equals(
            "http://user@www.example.com/"));

        $this->assertTrue(CUrl::normalize("http://user:password@www.example.com", false)->equals(
            "http://user:password@www.example.com/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMakeUrlString ()
    {
        $sUrl = CUrl::makeUrlString("www.example.com");
        $this->assertTrue($sUrl->equals("http://www.example.com/"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https");
        $this->assertTrue($sUrl->equals("https://www.example.com/"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https", "/path/to/item");
        $this->assertTrue($sUrl->equals("https://www.example.com/path/to/item"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https", new CUrlPath("/path/to/item"));
        $this->assertTrue($sUrl->equals("https://www.example.com/path/to/item"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1");
        $this->assertTrue($sUrl->equals("https://www.example.com/path/to/item?name0=value0&name1=value1"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https", "/path/to/item",
            new CUrlQuery("name0=value0&name1=value1"));
        $this->assertTrue($sUrl->equals("https://www.example.com/path/to/item?name0=value0&name1=value1"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1",
            "fragment_id");
        $this->assertTrue($sUrl->equals("https://www.example.com/path/to/item?name0=value0&name1=value1#fragment_id"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1",
            "fragment_id", 443);
        $this->assertTrue($sUrl->equals(
            "https://www.example.com:443/path/to/item?name0=value0&name1=value1#fragment_id"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1",
            "fragment_id", 443, "user");
        $this->assertTrue($sUrl->equals(
            "https://user@www.example.com:443/path/to/item?name0=value0&name1=value1#fragment_id"));

        $sUrl = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1",
            "fragment_id", 443, "user", "password");
        $this->assertTrue($sUrl->equals(
            "https://user:password@www.example.com:443/path/to/item?name0=value0&name1=value1#fragment_id"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEnterTdNew ()
    {
        $this->assertTrue(CUrl::enterTdNew("Hello there+~")->equals("Hello%20there%2B~"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLeaveTdNew ()
    {
        $this->assertTrue(CUrl::leaveTdNew("Hello+%20there+%2B~%7E")->equals("Hello+ there++~~"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEnterTdOld ()
    {
        $this->assertTrue(CUrl::enterTdOld("Hello there+~")->equals("Hello+there%2B%7E"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLeaveTdOld ()
    {
        $this->assertTrue(CUrl::leaveTdOld("Hello+%20there+%2B~%7E")->equals("Hello  there +~~"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
