<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
        $oUrlPath = new CUrlPath("/path/to/item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/path/to/item"));

        $oUrlPath = new CUrlPath("/the%20path/to/an%20item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("the path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("an item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/the%20path/to/an%20item"));

        $oUrlPath = new CUrlPath("/the path/to/an item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("the path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("an item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/the%20path/to/an%20item"));

        $oUrlPath = new CUrlPath("/path/to/item/");
        $this->assertTrue( $oUrlPath->numComponents() == 4 );
        $this->assertTrue( $oUrlPath->component(0)->equals("path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("item") && $oUrlPath->component(3)->equals("") );
        $this->assertTrue($oUrlPath->pathString()->equals("/path/to/item/"));

        $oUrlPath = new CUrlPath();
        $oUrlPath->addComponent("path");
        $oUrlPath->addComponent("to");
        $oUrlPath->addComponent("item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/path/to/item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNextComponent ()
    {
        $oUrlPath = new CUrlPath("/path/to/item");
        $this->assertTrue($oUrlPath->nextComponent()->equals("path"));
        $this->assertTrue($oUrlPath->nextComponent()->equals("to"));
        $this->assertTrue($oUrlPath->nextComponent()->equals("item"));

        $oUrlPath = new CUrlPath("/the%20path/to/an%20item");
        $this->assertTrue($oUrlPath->nextComponent()->equals("the path"));
        $this->assertTrue($oUrlPath->nextComponent()->equals("to"));
        $this->assertTrue($oUrlPath->nextComponent()->equals("an item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsPosPastEnd ()
    {
        $oUrlPath = new CUrlPath("/path/to/item");
        $this->assertTrue($oUrlPath->nextComponent()->equals("path"));
        $this->assertFalse($oUrlPath->isPosPastEnd());
        $this->assertTrue($oUrlPath->nextComponent()->equals("to"));
        $this->assertFalse($oUrlPath->isPosPastEnd());
        $this->assertTrue($oUrlPath->nextComponent()->equals("item"));
        $this->assertTrue($oUrlPath->isPosPastEnd());
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testNumComponents ()
    {
        $oUrlPath = new CUrlPath("/path/to/item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComponent ()
    {
        $oUrlPath = new CUrlPath("/path/to/item");
        $this->assertTrue( $oUrlPath->component(0)->equals("path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("item") );

        $oUrlPath = new CUrlPath("/the%20path/to/an%20item");
        $this->assertTrue( $oUrlPath->component(0)->equals("the path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("an item") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testAddComponent ()
    {
        $oUrlPath = new CUrlPath();
        $oUrlPath->addComponent("path");
        $oUrlPath->addComponent("to");
        $oUrlPath->addComponent("item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/path/to/item"));

        $oUrlPath = new CUrlPath();
        $oUrlPath->addComponent("the path");
        $oUrlPath->addComponent("to");
        $oUrlPath->addComponent("an item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("the path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("an item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/the%20path/to/an%20item"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPathString ()
    {
        $oUrlPath = new CUrlPath("/path/to/item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/path/to/item"));

        $oUrlPath = new CUrlPath("/the%20path/to/an%20item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("the path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("an item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/the%20path/to/an%20item"));

        $oUrlPath = new CUrlPath("/the path/to/an item");
        $this->assertTrue( $oUrlPath->numComponents() == 3 );
        $this->assertTrue( $oUrlPath->component(0)->equals("the path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("an item") );
        $this->assertTrue($oUrlPath->pathString()->equals("/the%20path/to/an%20item"));

        $oUrlPath = new CUrlPath("/path/to/item/");
        $this->assertTrue( $oUrlPath->numComponents() == 4 );
        $this->assertTrue( $oUrlPath->component(0)->equals("path") && $oUrlPath->component(1)->equals("to") &&
            $oUrlPath->component(2)->equals("item") && $oUrlPath->component(3)->equals("") );
        $this->assertTrue($oUrlPath->pathString()->equals("/path/to/item/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClone ()
    {
        $oUrlPath0 = new CUrlPath("/path/to/item");
        $oUrlPath1 = clone $oUrlPath0;
        $oUrlPath0->addComponent("id");
        $this->assertTrue(
            $oUrlPath0->pathString()->equals("/path/to/item/id") &&
            $oUrlPath1->pathString()->equals("/path/to/item") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
