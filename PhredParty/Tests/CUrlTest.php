<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
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
        $url = new CUrl("http://www.example.com");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/"));

        $url = new CUrl("WWW.EXAMPLE.COM");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/"));

        $url = new CUrl("93.184.216.119");
        $this->assertTrue($url->normalizedUrl()->equals("http://93.184.216.119/"));

        $url = new CUrl("[0:0:0:0:0:FFFF:5DB8:D877]");
        $this->assertTrue($url->normalizedUrl()->equals("http://[0:0:0:0:0:ffff:5db8:d877]/"));

        $url = new CUrl("http://www.example.com:443");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com:443/"));

        $url = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/path/to/some%20item"));

        $url = new CUrl("http://www.example.com/?name=value");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/?name=value"));

        $url = new CUrl("http://www.example.com/?name=value0&name=value1");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/?name=value1"));

        $url = new CUrl("http://www.example.com/?name1&name0=value");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/?name0=value&name1="));

        $url = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/#fragment_id"));

        $url = new CUrl("http://user@www.example.com");
        $this->assertTrue($url->normalizedUrl()->equals("http://user@www.example.com/"));

        $url = new CUrl("http://user:password@www.example.com");
        $this->assertTrue($url->normalizedUrl()->equals("http://user:password@www.example.com/"));

        $url = new CUrl("HTTP://www.example.com/path/to/some%3dite%6D");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/path/to/some%3Ditem"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUrl ()
    {
        $url = new CUrl("example.com");
        $this->assertTrue($url->url()->equals("example.com"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasProtocol ()
    {
        $url = new CUrl("http://www.example.com/");
        $this->assertTrue($url->hasProtocol());

        $url = new CUrl("www.example.com");
        $this->assertFalse($url->hasProtocol());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testProtocol ()
    {
        $url = new CUrl("http://www.example.com/");
        $this->assertTrue($url->protocol()->equals("http"));

        $url = new CUrl("https://www.example.com/");
        $this->assertTrue($url->protocol()->equals("https"));

        $url = new CUrl("FTP://WWW.EXAMPLE.COM/");
        $this->assertTrue($url->protocol()->equals("FTP"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedProtocol ()
    {
        $url = new CUrl("http://www.example.com/");
        $this->assertTrue($url->normalizedProtocol()->equals("http"));

        $url = new CUrl("HTTPS://WWW.EXAMPLE.COM/");
        $this->assertTrue($url->normalizedProtocol()->equals("https"));

        $url = new CUrl("FTP://WWW.EXAMPLE.COM/");
        $this->assertTrue($url->normalizedProtocol()->equals("ftp"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHost ()
    {
        $url = new CUrl("http://www.example.com/");
        $this->assertTrue($url->host()->equals("www.example.com"));

        $url = new CUrl("HTTP://WWW.EXAMPLE.COM/");
        $this->assertTrue($url->host()->equals("WWW.EXAMPLE.COM"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedHost ()
    {
        $url = new CUrl("http://www.example.com/");
        $this->assertTrue($url->normalizedHost()->equals("www.example.com"));

        $url = new CUrl("HTTP://WWW.EXAMPLE.COM/");
        $this->assertTrue($url->normalizedHost()->equals("www.example.com"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPort ()
    {
        $url = new CUrl("http://www.example.com:443/");
        $this->assertTrue($url->hasPort());

        $url = new CUrl("http://www.example.com/");
        $this->assertFalse($url->hasPort());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPort ()
    {
        $url = new CUrl("http://www.example.com:443/");
        $this->assertTrue( $url->port() === 443 );

        $url = new CUrl("http://www.example.com:8080/");
        $this->assertTrue( $url->port() === 8080 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPath ()
    {
        $url = new CUrl("http://www.example.com/");  // "/" is the path
        $this->assertTrue($url->hasPath());

        $url = new CUrl("http://www.example.com/path/to/item");
        $this->assertTrue($url->hasPath());

        $url = new CUrl("http://www.example.com");
        $this->assertFalse($url->hasPath());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPathString ()
    {
        $url = new CUrl("http://www.example.com/");
        $this->assertTrue($url->pathString()->equals("/"));

        $url = new CUrl("http://www.example.com/path/to/item");
        $this->assertTrue($url->pathString()->equals("/path/to/item"));

        $url = new CUrl("http://www.example.com/path/to/some%20item");
        $this->assertTrue($url->pathString()->equals("/path/to/some%20item"));

        $url = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($url->pathString()->equals("/path/to/some%20ite%6D"));

        $url = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($url->pathString(true)->equals("/path/to/some item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedPathString ()
    {
        $url = new CUrl("http://www.example.com");  // path normalization makes an empty path be "/"
        $this->assertTrue($url->normalizedPathString()->equals("/"));

        $url = new CUrl("http://www.example.com/path/to/item");
        $this->assertTrue($url->normalizedPathString()->equals("/path/to/item"));

        $url = new CUrl("http://www.example.com/path/to/some%20item");
        $this->assertTrue($url->normalizedPathString()->equals("/path/to/some%20item"));

        $url = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($url->normalizedPathString()->equals("/path/to/some%20item"));

        $url = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($url->normalizedPathString(true)->equals("/path/to/some item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPath ()
    {
        $url = new CUrl("http://www.example.com");  // path normalization makes an empty path be "/"
        $this->assertTrue($url->path()->pathString()->equals("/"));

        $url = new CUrl("http://www.example.com/path/to/item");
        $urlPath = $url->path();
        $this->assertTrue( $urlPath->pathString()->equals("/path/to/item") &&
            $urlPath->component(0)->equals("path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("item") );

        $url = new CUrl("http://www.example.com/path/to/so%6De%20item");
        $this->assertTrue($url->path()->pathString()->equals("/path/to/some%20item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasQuery ()
    {
        $url = new CUrl("http://www.example.com/?name0=value0&name1=value1");
        $this->assertTrue($url->hasQuery());

        $url = new CUrl("http://www.example.com/");
        $this->assertFalse($url->hasQuery());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testQueryString ()
    {
        $url = new CUrl("http://www.example.com/?name0=value0&name1=value1");
        $this->assertTrue($url->queryString()->equals("name0=value0&name1=value1"));

        $url = new CUrl("http://www.example.com/?name0=value%200&name1=value%201");
        $this->assertTrue($url->queryString(true)->equals("name0=value 0&name1=value 1"));

        $url = new CUrl("http://www.example.com/?na%6De0=value0&na%6De1=value1");
        $this->assertTrue($url->queryString()->equals("na%6De0=value0&na%6De1=value1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedQueryString ()
    {
        $url = new CUrl("http://www.example.com/?name0=value0&name1=value1");
        $this->assertTrue($url->normalizedQueryString()->equals("name0=value0&name1=value1"));

        $url = new CUrl("http://www.example.com/?name0=value%200&name1=value%201");
        $this->assertTrue($url->normalizedQueryString(true)->equals("name0=value 0&name1=value 1"));

        $url = new CUrl("http://www.example.com/?na%6De0=value0&na%6De1=value1");
        $this->assertTrue($url->normalizedQueryString()->equals("name0=value0&name1=value1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testQuery ()
    {
        $url = new CUrl("http://www.example.com/?name0=value0&name1=value1");
        $this->assertTrue($url->query()->queryString()->equals("name0=value0&name1=value1"));

        $url = new CUrl("http://www.example.com/?na%6De0=value0&na%6De1=value1");
        $this->assertTrue($url->query()->queryString()->equals("name0=value0&name1=value1"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasFragmentId ()
    {
        $url = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($url->hasFragmentId());

        $url = new CUrl("http://www.example.com/");
        $this->assertFalse($url->hasFragmentId());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFragmentId ()
    {
        $url = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($url->fragmentId()->equals("fragment_id"));

        $url = new CUrl("http://www.example.com/#frag%6Dent%20id");
        $this->assertTrue($url->fragmentId()->equals("frag%6Dent%20id"));

        $url = new CUrl("http://www.example.com/#frag%6Dent%20id");
        $this->assertTrue($url->fragmentId(true)->equals("fragment id"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedFragmentId ()
    {
        $url = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($url->normalizedFragmentId()->equals("fragment_id"));

        $url = new CUrl("http://www.example.com/#frag%6Dent%20id");
        $this->assertTrue($url->normalizedFragmentId()->equals("fragment%20id"));

        $url = new CUrl("http://www.example.com/#frag%6Dent%20id");
        $this->assertTrue($url->normalizedFragmentId(true)->equals("fragment id"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasUser ()
    {
        $url = new CUrl("http://user@www.example.com/");
        $this->assertTrue($url->hasUser());

        $url = new CUrl("http://www.example.com/");
        $this->assertFalse($url->hasUser());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUser ()
    {
        $url = new CUrl("http://user@www.example.com/");
        $this->assertTrue($url->user()->equals("user"));

        $url = new CUrl("http://USER@www.example.com/");
        $this->assertTrue($url->user()->equals("USER"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasPassword ()
    {
        $url = new CUrl("http://user:password@www.example.com/");
        $this->assertTrue($url->hasPassword());

        $url = new CUrl("http://user@www.example.com/");
        $this->assertFalse($url->hasPassword());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPassword ()
    {
        $url = new CUrl("http://user:password@www.example.com/");
        $this->assertTrue($url->password()->equals("password"));

        $url = new CUrl("http://USER:PASSWORD@www.example.com/");
        $this->assertTrue($url->password()->equals("PASSWORD"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedUrlWithoutFragmentId ()
    {
        $url = new CUrl("http://www.example.com");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/"));

        $url = new CUrl("WWW.EXAMPLE.COM");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/"));

        $url = new CUrl("93.184.216.119");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://93.184.216.119/"));

        $url = new CUrl("[0:0:0:0:0:FFFF:5DB8:D877]");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://[0:0:0:0:0:ffff:5db8:d877]/"));

        $url = new CUrl("http://www.example.com:443");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://www.example.com:443/"));

        $url = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals(
            "http://www.example.com/path/to/some%20item"));

        $url = new CUrl("http://www.example.com/?name=value");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/?name=value"));

        $url = new CUrl("http://www.example.com/?name=value0&name=value1");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/?name=value1"));

        $url = new CUrl("http://www.example.com/?name1&name0=value");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals(
            "http://www.example.com/?name0=value&name1="));

        $url = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://www.example.com/"));

        $url = new CUrl("http://user@www.example.com");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://user@www.example.com/"));

        $url = new CUrl("http://user:password@www.example.com");
        $this->assertTrue($url->normalizedUrlWithoutFragmentId()->equals("http://user:password@www.example.com/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNormalizedUrl ()
    {
        $url = new CUrl("http://www.example.com");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/"));

        $url = new CUrl("WWW.EXAMPLE.COM");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/"));

        $url = new CUrl("93.184.216.119");
        $this->assertTrue($url->normalizedUrl()->equals("http://93.184.216.119/"));

        $url = new CUrl("[0:0:0:0:0:FFFF:5DB8:D877]");
        $this->assertTrue($url->normalizedUrl()->equals("http://[0:0:0:0:0:ffff:5db8:d877]/"));

        $url = new CUrl("http://www.example.com:443");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com:443/"));

        $url = new CUrl("http://www.example.com/path/to/some%20ite%6D");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/path/to/some%20item"));

        $url = new CUrl("http://www.example.com/?name=value");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/?name=value"));

        $url = new CUrl("http://www.example.com/?name=value0&name=value1");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/?name=value1"));

        $url = new CUrl("http://www.example.com/?name1&name0=value");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/?name0=value&name1="));

        $url = new CUrl("http://www.example.com/#fragment_id");
        $this->assertTrue($url->normalizedUrl()->equals("http://www.example.com/#fragment_id"));

        $url = new CUrl("http://user@www.example.com");
        $this->assertTrue($url->normalizedUrl()->equals("http://user@www.example.com/"));

        $url = new CUrl("http://user:password@www.example.com");
        $this->assertTrue($url->normalizedUrl()->equals("http://user:password@www.example.com/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        $url0 = new CUrl("http://www.example.com");
        $url1 = new CUrl("HTTP://www.example.com/");
        $this->assertTrue($url0->equals($url1));

        $url0 = new CUrl("http://www.example.com/?path/to/item");
        $url1 = new CUrl("HTTP://www.example.com/?path/to/ite%6D");
        $this->assertTrue($url0->equals($url1));

        $url0 = new CUrl("http://www.example.com/?path/to/item");
        $url1 = new CUrl("http://www.example.com/?path/t0/item");
        $this->assertFalse($url0->equals($url1));

        $url0 = new CUrl("http://www.example.com");
        $url1 = new CUrl("HTTP://www.example.com/#fragment_id");
        $this->assertTrue($url0->equals($url1));

        $url0 = new CUrl("http://www.example.com");
        $url1 = new CUrl("HTTP://www.example.com/#fragment_id");
        $this->assertFalse($url0->equals($url1, false));
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
        $url = CUrl::makeUrlString("www.example.com");
        $this->assertTrue($url->equals("http://www.example.com/"));

        $url = CUrl::makeUrlString("www.example.com", "https");
        $this->assertTrue($url->equals("https://www.example.com/"));

        $url = CUrl::makeUrlString("www.example.com", "https", "/path/to/item");
        $this->assertTrue($url->equals("https://www.example.com/path/to/item"));

        $url = CUrl::makeUrlString("www.example.com", "https", new CUrlPath("/path/to/item"));
        $this->assertTrue($url->equals("https://www.example.com/path/to/item"));

        $url = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1");
        $this->assertTrue($url->equals("https://www.example.com/path/to/item?name0=value0&name1=value1"));

        $url = CUrl::makeUrlString("www.example.com", "https", "/path/to/item",
            new CUrlQuery("name0=value0&name1=value1"));
        $this->assertTrue($url->equals("https://www.example.com/path/to/item?name0=value0&name1=value1"));

        $url = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1",
            "fragment_id");
        $this->assertTrue($url->equals("https://www.example.com/path/to/item?name0=value0&name1=value1#fragment_id"));

        $url = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1",
            "fragment_id", 443);
        $this->assertTrue($url->equals(
            "https://www.example.com:443/path/to/item?name0=value0&name1=value1#fragment_id"));

        $url = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1",
            "fragment_id", 443, "user");
        $this->assertTrue($url->equals(
            "https://user@www.example.com:443/path/to/item?name0=value0&name1=value1#fragment_id"));

        $url = CUrl::makeUrlString("www.example.com", "https", "/path/to/item", "name0=value0&name1=value1",
            "fragment_id", 443, "user", "password");
        $this->assertTrue($url->equals(
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
