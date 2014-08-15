<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $this->assertTrue( $oUrlQuery->hasField("name0") && $oUrlQuery->field("name0")->equals("value0") &&
            $oUrlQuery->hasField("name1") && $oUrlQuery->field("name1")->equals("value1") );

        $oUrlQuery = new CUrlQuery("arr[]=value0;arr[]=value1");
        $this->assertTrue( $oUrlQuery->hasField("arr") && $oUrlQuery->field("arr")->equals(a("value0", "value1")) );

        $oUrlQuery = new CUrlQuery("map[key0]=value0&map[key1]=value1");
        $this->assertTrue( $oUrlQuery->hasField("map") &&
            $oUrlQuery->field("map")->equals(m(["key0" => "value0", "key1" => "value1"])) );

        $oUrlQuery = new CUrlQuery("name0=value%200&name1=value%201");
        $this->assertTrue( $oUrlQuery->hasField("name0") && $oUrlQuery->field("name0")->equals("value 0") &&
            $oUrlQuery->hasField("name1") && $oUrlQuery->field("name1")->equals("value 1") );

        $oUrlQuery = new CUrlQuery("name0=value 0&name1=value 1");
        $this->assertTrue( $oUrlQuery->hasField("name0") && $oUrlQuery->field("name0")->equals("value 0") &&
            $oUrlQuery->hasField("name1") && $oUrlQuery->field("name1")->equals("value 1") );

        $oUrlQuery = new CUrlQuery("");
        $this->assertTrue($oUrlQuery->queryString()->equals(""));

        $oUrlQuery = new CUrlQuery();
        $this->assertTrue($oUrlQuery->queryString()->equals(""));

        $bParsingWasFruitful;
        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1", $bParsingWasFruitful);
        $this->assertTrue( $oUrlQuery->queryString()->equals("name0=value0&name1=value1") && $bParsingWasFruitful );

        $bParsingWasFruitful;
        $oUrlQuery = new CUrlQuery(" ", $bParsingWasFruitful);
        $this->assertTrue( $oUrlQuery->queryString()->equals("") && !$bParsingWasFruitful );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testHasField ()
    {
        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $this->assertTrue( $oUrlQuery->hasField("name0") && $oUrlQuery->hasField("name1") );

        $oUrlQuery = new CUrlQuery("arr0[]=value0;arr0[]=value1;arr1[]=value0");
        $this->assertTrue( $oUrlQuery->hasField("arr0") && $oUrlQuery->hasField("arr1") );

        $oUrlQuery = new CUrlQuery("map0[key0]=value0&map0[key1]=value1&map1[key1]=value1");
        $this->assertTrue( $oUrlQuery->hasField("map0") && $oUrlQuery->hasField("map1") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testField ()
    {
        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $this->assertTrue( $oUrlQuery->field("name0")->equals("value0") &&
            $oUrlQuery->field("name1")->equals("value1") );

        $oUrlQuery = new CUrlQuery("arr[]=value0;arr[]=value1");
        $this->assertTrue( $oUrlQuery->field("arr")->length() == 2 &&
            $oUrlQuery->field("arr")->equals(a("value0", "value1")) );

        $oUrlQuery = new CUrlQuery("map[key0]=value0&map[key1]=value1");
        $this->assertTrue( $oUrlQuery->field("map")->length() == 2 &&
            $oUrlQuery->field("map")->equals(m(["key0" => "value0", "key1" => "value1"])) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAddField ()
    {
        $oUrlQuery = new CUrlQuery();
        $oUrlQuery->addField("name0", "value");
        $oUrlQuery->addField("name1", true);
        $oUrlQuery->addField("name2", 1234);
        $oUrlQuery->addField("name3", 56.78);
        $this->assertTrue($oUrlQuery->queryString()->equals("name0=value&name1=1&name2=1234&name3=56.78"));

        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $oUrlQuery->addField("name2", "value2");
        $this->assertTrue($oUrlQuery->queryString()->equals("name0=value0&name1=value1&name2=value2"));

        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $oUrlQuery->addField("name2", a("elem0", "elem1"));
        $this->assertTrue($oUrlQuery->queryString()->equals("name0=value0&name1=value1&name2[]=elem0&name2[]=elem1"));

        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $oUrlQuery->addField("name2", m(["key0" => "value0", "key1" => "value1"]));
        $this->assertTrue($oUrlQuery->queryString()->equals(
            "name0=value0&name1=value1&name2[key0]=value0&name2[key1]=value1"));

        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $oUrlQuery->addField("name2", a(true, false));
        $this->assertTrue($oUrlQuery->queryString()->equals("name0=value0&name1=value1&name2[]=1&name2[]=0"));

        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $oUrlQuery->addField("name2", a(1234, 5678));
        $this->assertTrue($oUrlQuery->queryString()->equals("name0=value0&name1=value1&name2[]=1234&name2[]=5678"));

        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $oUrlQuery->addField("name2", a(12.34, 56.78));
        $this->assertTrue($oUrlQuery->queryString()->equals("name0=value0&name1=value1&name2[]=12.34&name2[]=56.78"));

        $oUrlQuery = new CUrlQuery();
        $oUrlQuery->addField("arr", a("one", "two"));
        $oUrlQuery->addField("map", m(["key0" => "one", "key1" => a("one", "two")]));
        $this->assertTrue($oUrlQuery->queryString()->equals(
            "arr[]=one&arr[]=two&map[key0]=one&map[key1][]=one&map[key1][]=two"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testQueryString ()
    {
        $oUrlQuery = new CUrlQuery("name0=value0&name1=value1");
        $this->assertTrue($oUrlQuery->queryString()->equals("name0=value0&name1=value1"));

        $oUrlQuery = new CUrlQuery("arr[]=value0;arr[]=value1");
        $this->assertTrue($oUrlQuery->queryString()->equals("arr[]=value0&arr[]=value1"));

        $oUrlQuery = new CUrlQuery("map[key0]=value0&map[key1]=value1");
        $this->assertTrue($oUrlQuery->queryString()->equals("map[key0]=value0&map[key1]=value1"));

        $oUrlQuery = new CUrlQuery("name1=value1&name0=value0");
        $this->assertTrue($oUrlQuery->queryString(true)->equals("name0=value0&name1=value1"));

        $oUrlQuery = new CUrlQuery("name=value0&name=value1");
        $this->assertTrue($oUrlQuery->queryString()->equals("name=value1"));

        $oUrlQuery = new CUrlQuery("name1&name0=value");
        $this->assertTrue($oUrlQuery->queryString(true)->equals("name0=value&name1="));

        $oUrlQuery = new CUrlQuery("");
        $this->assertTrue($oUrlQuery->queryString()->equals(""));

        $oUrlQuery = new CUrlQuery();
        $this->assertTrue($oUrlQuery->queryString()->equals(""));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
