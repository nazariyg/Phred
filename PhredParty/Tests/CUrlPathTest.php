<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CUrlPathTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $urlPath = new CUrlPath("/path/to/item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("item") );
        $this->assertTrue($urlPath->pathString()->equals("/path/to/item"));

        $urlPath = new CUrlPath("/the%20path/to/an%20item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("the path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("an item") );
        $this->assertTrue($urlPath->pathString()->equals("/the%20path/to/an%20item"));

        $urlPath = new CUrlPath("/the path/to/an item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("the path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("an item") );
        $this->assertTrue($urlPath->pathString()->equals("/the%20path/to/an%20item"));

        $urlPath = new CUrlPath("/path/to/item/");
        $this->assertTrue( $urlPath->numComponents() == 4 );
        $this->assertTrue( $urlPath->component(0)->equals("path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("item") && $urlPath->component(3)->equals("") );
        $this->assertTrue($urlPath->pathString()->equals("/path/to/item/"));

        $urlPath = new CUrlPath();
        $urlPath->addComponent("path");
        $urlPath->addComponent("to");
        $urlPath->addComponent("item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("item") );
        $this->assertTrue($urlPath->pathString()->equals("/path/to/item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNextComponent ()
    {
        $urlPath = new CUrlPath("/path/to/item");
        $this->assertTrue($urlPath->nextComponent()->equals("path"));
        $this->assertTrue($urlPath->nextComponent()->equals("to"));
        $this->assertTrue($urlPath->nextComponent()->equals("item"));

        $urlPath = new CUrlPath("/the%20path/to/an%20item");
        $this->assertTrue($urlPath->nextComponent()->equals("the path"));
        $this->assertTrue($urlPath->nextComponent()->equals("to"));
        $this->assertTrue($urlPath->nextComponent()->equals("an item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsPosPastEnd ()
    {
        $urlPath = new CUrlPath("/path/to/item");
        $this->assertTrue($urlPath->nextComponent()->equals("path"));
        $this->assertFalse($urlPath->isPosPastEnd());
        $this->assertTrue($urlPath->nextComponent()->equals("to"));
        $this->assertFalse($urlPath->isPosPastEnd());
        $this->assertTrue($urlPath->nextComponent()->equals("item"));
        $this->assertTrue($urlPath->isPosPastEnd());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumComponents ()
    {
        $urlPath = new CUrlPath("/path/to/item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponent ()
    {
        $urlPath = new CUrlPath("/path/to/item");
        $this->assertTrue( $urlPath->component(0)->equals("path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("item") );

        $urlPath = new CUrlPath("/the%20path/to/an%20item");
        $this->assertTrue( $urlPath->component(0)->equals("the path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("an item") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAddComponent ()
    {
        $urlPath = new CUrlPath();
        $urlPath->addComponent("path");
        $urlPath->addComponent("to");
        $urlPath->addComponent("item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("item") );
        $this->assertTrue($urlPath->pathString()->equals("/path/to/item"));

        $urlPath = new CUrlPath();
        $urlPath->addComponent("the path");
        $urlPath->addComponent("to");
        $urlPath->addComponent("an item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("the path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("an item") );
        $this->assertTrue($urlPath->pathString()->equals("/the%20path/to/an%20item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPathString ()
    {
        $urlPath = new CUrlPath("/path/to/item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("item") );
        $this->assertTrue($urlPath->pathString()->equals("/path/to/item"));

        $urlPath = new CUrlPath("/the%20path/to/an%20item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("the path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("an item") );
        $this->assertTrue($urlPath->pathString()->equals("/the%20path/to/an%20item"));

        $urlPath = new CUrlPath("/the path/to/an item");
        $this->assertTrue( $urlPath->numComponents() == 3 );
        $this->assertTrue( $urlPath->component(0)->equals("the path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("an item") );
        $this->assertTrue($urlPath->pathString()->equals("/the%20path/to/an%20item"));

        $urlPath = new CUrlPath("/path/to/item/");
        $this->assertTrue( $urlPath->numComponents() == 4 );
        $this->assertTrue( $urlPath->component(0)->equals("path") && $urlPath->component(1)->equals("to") &&
            $urlPath->component(2)->equals("item") && $urlPath->component(3)->equals("") );
        $this->assertTrue($urlPath->pathString()->equals("/path/to/item/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClone ()
    {
        $urlPath0 = new CUrlPath("/path/to/item");
        $urlPath1 = clone $urlPath0;
        $urlPath0->addComponent("id");
        $this->assertTrue(
            $urlPath0->pathString()->equals("/path/to/item/id") &&
            $urlPath1->pathString()->equals("/path/to/item") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
