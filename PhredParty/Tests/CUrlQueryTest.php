<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CUrlQueryTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $this->assertTrue( $urlQuery->hasField("name0") && $urlQuery->field("name0")->equals("value0") &&
            $urlQuery->hasField("name1") && $urlQuery->field("name1")->equals("value1") );

        $urlQuery = new CUrlQuery("arr[]=value0;arr[]=value1");
        $this->assertTrue( $urlQuery->hasField("arr") && $urlQuery->field("arr")->equals(a("value0", "value1")) );

        $urlQuery = new CUrlQuery("map[key0]=value0&map[key1]=value1");
        $this->assertTrue( $urlQuery->hasField("map") &&
            $urlQuery->field("map")->equals(m(["key0" => "value0", "key1" => "value1"])) );

        $urlQuery = new CUrlQuery("name0=value%200&name1=value%201");
        $this->assertTrue( $urlQuery->hasField("name0") && $urlQuery->field("name0")->equals("value 0") &&
            $urlQuery->hasField("name1") && $urlQuery->field("name1")->equals("value 1") );

        $urlQuery = new CUrlQuery("name0=value 0&name1=value 1");
        $this->assertTrue( $urlQuery->hasField("name0") && $urlQuery->field("name0")->equals("value 0") &&
            $urlQuery->hasField("name1") && $urlQuery->field("name1")->equals("value 1") );

        $urlQuery = new CUrlQuery("");
        $this->assertTrue($urlQuery->queryString()->equals(""));

        $urlQuery = new CUrlQuery();
        $this->assertTrue($urlQuery->queryString()->equals(""));

        $parsingWasFruitful;
        $urlQuery = new CUrlQuery("name0=value0&name1=value1", $parsingWasFruitful);
        $this->assertTrue( $urlQuery->queryString()->equals("name0=value0&name1=value1") && $parsingWasFruitful );

        $parsingWasFruitful;
        $urlQuery = new CUrlQuery(" ", $parsingWasFruitful);
        $this->assertTrue( $urlQuery->queryString()->equals("") && !$parsingWasFruitful );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasField ()
    {
        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $this->assertTrue( $urlQuery->hasField("name0") && $urlQuery->hasField("name1") );

        $urlQuery = new CUrlQuery("arr0[]=value0;arr0[]=value1;arr1[]=value0");
        $this->assertTrue( $urlQuery->hasField("arr0") && $urlQuery->hasField("arr1") );

        $urlQuery = new CUrlQuery("map0[key0]=value0&map0[key1]=value1&map1[key1]=value1");
        $this->assertTrue( $urlQuery->hasField("map0") && $urlQuery->hasField("map1") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testField ()
    {
        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $this->assertTrue( $urlQuery->field("name0")->equals("value0") &&
            $urlQuery->field("name1")->equals("value1") );

        $urlQuery = new CUrlQuery("arr[]=value0;arr[]=value1");
        $this->assertTrue( $urlQuery->field("arr")->length() == 2 &&
            $urlQuery->field("arr")->equals(a("value0", "value1")) );

        $urlQuery = new CUrlQuery("map[key0]=value0&map[key1]=value1");
        $this->assertTrue( $urlQuery->field("map")->length() == 2 &&
            $urlQuery->field("map")->equals(m(["key0" => "value0", "key1" => "value1"])) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAddField ()
    {
        $urlQuery = new CUrlQuery();
        $urlQuery->addField("name0", "value");
        $urlQuery->addField("name1", true);
        $urlQuery->addField("name2", 1234);
        $urlQuery->addField("name3", 56.78);
        $this->assertTrue($urlQuery->queryString()->equals("name0=value&name1=1&name2=1234&name3=56.78"));

        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $urlQuery->addField("name2", "value2");
        $this->assertTrue($urlQuery->queryString()->equals("name0=value0&name1=value1&name2=value2"));

        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $urlQuery->addField("name2", a("elem0", "elem1"));
        $this->assertTrue($urlQuery->queryString()->equals("name0=value0&name1=value1&name2[]=elem0&name2[]=elem1"));

        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $urlQuery->addField("name2", m(["key0" => "value0", "key1" => "value1"]));
        $this->assertTrue($urlQuery->queryString()->equals(
            "name0=value0&name1=value1&name2[key0]=value0&name2[key1]=value1"));

        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $urlQuery->addField("name2", a(true, false));
        $this->assertTrue($urlQuery->queryString()->equals("name0=value0&name1=value1&name2[]=1&name2[]=0"));

        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $urlQuery->addField("name2", a(1234, 5678));
        $this->assertTrue($urlQuery->queryString()->equals("name0=value0&name1=value1&name2[]=1234&name2[]=5678"));

        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $urlQuery->addField("name2", a(12.34, 56.78));
        $this->assertTrue($urlQuery->queryString()->equals("name0=value0&name1=value1&name2[]=12.34&name2[]=56.78"));

        $urlQuery = new CUrlQuery();
        $urlQuery->addField("arr", a("one", "two"));
        $urlQuery->addField("map", m(["key0" => "one", "key1" => a("one", "two")]));
        $this->assertTrue($urlQuery->queryString()->equals(
            "arr[]=one&arr[]=two&map[key0]=one&map[key1][]=one&map[key1][]=two"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testQueryString ()
    {
        $urlQuery = new CUrlQuery("name0=value0&name1=value1");
        $this->assertTrue($urlQuery->queryString()->equals("name0=value0&name1=value1"));

        $urlQuery = new CUrlQuery("arr[]=value0;arr[]=value1");
        $this->assertTrue($urlQuery->queryString()->equals("arr[]=value0&arr[]=value1"));

        $urlQuery = new CUrlQuery("map[key0]=value0&map[key1]=value1");
        $this->assertTrue($urlQuery->queryString()->equals("map[key0]=value0&map[key1]=value1"));

        $urlQuery = new CUrlQuery("name1=value1&name0=value0");
        $this->assertTrue($urlQuery->queryString(true)->equals("name0=value0&name1=value1"));

        $urlQuery = new CUrlQuery("name=value0&name=value1");
        $this->assertTrue($urlQuery->queryString()->equals("name=value1"));

        $urlQuery = new CUrlQuery("name1&name0=value");
        $this->assertTrue($urlQuery->queryString(true)->equals("name0=value&name1="));

        $urlQuery = new CUrlQuery("");
        $this->assertTrue($urlQuery->queryString()->equals(""));

        $urlQuery = new CUrlQuery();
        $this->assertTrue($urlQuery->queryString()->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
