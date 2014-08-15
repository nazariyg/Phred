<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CJsonTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $sEncJson = <<<'NDOC'
{
    "prop": "value"
}
NDOC;
        $oJson = new CJson($sEncJson);
        $bSuccess;
        $mDecMap = $oJson->decode($bSuccess);
        $this->assertTrue( $bSuccess && $mDecMap->equals(
            m(["prop" => "value"])) );

        $sEncJson = <<<'NDOC'
{
    // comment
    "prop0": "value",  // comment
    "prop1": /*comment*/ ["value0", /*comment*/ "value1"]
}
NDOC;
        $oJson = new CJson($sEncJson, CJson::STRICT_WITH_COMMENTS);
        $bSuccess;
        $mDecMap = $oJson->decode($bSuccess);
        $this->assertTrue( $bSuccess && $mDecMap->equals(
            m(["prop0" => "value", "prop1" => a("value0", "value1")])) );

        $mValue =
            m(["prop0" => "value", "prop1" => a("value0", "value1")]);
        $oJson = new CJson($mValue);
        $sEncJson = $oJson->encode();
        $oJson = new CJson($sEncJson);
        $bSuccess;
        $mDecMap = $oJson->decode($bSuccess);
        $this->assertTrue( $bSuccess && $mDecMap->equals($mValue) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetEscapeNonAsciiChars ()
    {
        $aValue = a("Hola señor");
        $oJson = new CJson($aValue);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("Hola señor"));

        $aValue = a("Hola señor");
        $oJson = new CJson($aValue);
        $oJson->setEscapeNonAsciiChars(true);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("Hola se\u00f1or"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetEscapeForwardSlashes ()
    {
        $aValue = a("/");
        $oJson = new CJson($aValue);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("/"));

        $aValue = a("/");
        $oJson = new CJson($aValue);
        $oJson->setEscapeForwardSlashes(true);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("\\/"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetEscapeAngleBrackets ()
    {
        $aValue = a("<>");
        $oJson = new CJson($aValue);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("<>"));

        $aValue = a("<>");
        $oJson = new CJson($aValue);
        $oJson->setEscapeAngleBrackets(true);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("\u003C\u003E"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetEscapeAmpersands ()
    {
        $aValue = a("&");
        $oJson = new CJson($aValue);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("&"));

        $aValue = a("&");
        $oJson = new CJson($aValue);
        $oJson->setEscapeAmpersands(true);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("\u0026"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetEscapeSingleQuotes ()
    {
        $aValue = a("'");
        $oJson = new CJson($aValue);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("'"));

        $aValue = a("'");
        $oJson = new CJson($aValue);
        $oJson->setEscapeSingleQuotes(true);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("\u0027"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetEscapeDoubleQuotes ()
    {
        $aValue = a("\"");
        $oJson = new CJson($aValue);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("\""));

        $aValue = a("\"");
        $oJson = new CJson($aValue);
        $oJson->setEscapeDoubleQuotes(true);
        $sEncJson = $oJson->encode();
        $this->assertTrue($sEncJson->find("\u0022"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetPrettyPrint ()
    {
        $mValue =
            m(["prop0" => "value",
               "prop1" => a("value0", "value1"),
               "prop2" => m(["subProp0" => "value0", "subProp1" => "value1"]),
               "prop3" => a(true, 1234, 56.78)]);
        $oJson = new CJson($mValue);
        $oJson->setPrettyPrint(true);
        $sEncJson = $oJson->encode();
        $oJson = new CJson($sEncJson);
        $bSuccess;
        $mDecMap = $oJson->decode($bSuccess);
        $this->assertTrue( $bSuccess && $mDecMap->equals($mValue) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDecode ()
    {
        $sEncJson = <<<'NDOC'
{
    "prop": "value"
}
NDOC;
        $oJson = new CJson($sEncJson);
        $bSuccess;
        $mDecMap = $oJson->decode($bSuccess);
        $this->assertTrue( $bSuccess && $mDecMap->equals(
            m(["prop" => "value"])) );

        $sEncJson = <<<'NDOC'
{
    // comment
    "prop0": "value",  // comment
    "prop1": /*comment*/ ["value0", /*comment*/ "value1"]
}
NDOC;
        $oJson = new CJson($sEncJson, CJson::STRICT_WITH_COMMENTS);
        $bSuccess;
        $mDecMap = $oJson->decode($bSuccess);
        $this->assertTrue( $bSuccess && $mDecMap->equals(
            m(["prop0" => "value", "prop1" => a("value0", "value1")])) );

        $sEncJson = <<<'NDOC'
{
    // comment
    "prop0": "value",  // comment
    'prop1': /*comment*/ ["value0", /*comment*/ 'value1', ],  // a trailing comma
    prop2: {'subProp0': "value0", subProp1: 'value1', },  // a trailing comma
    prop3: [true, 1234, 56.78],  // a trailing comma
}
NDOC;
        $oJson = new CJson($sEncJson, CJson::LENIENT);
        $bSuccess;
        $mDecMap = $oJson->decode($bSuccess);
        $this->assertTrue( $bSuccess && $mDecMap->equals(
            m(["prop0" => "value",
               "prop1" => a("value0", "value1"),
               "prop2" => m(["subProp0" => "value0", "subProp1" => "value1"]),
               "prop3" => a(true, 1234, 56.78)])) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEncode ()
    {
        $mValue =
            m(["prop0" => "value",
               "prop1" => a("value0", "value1"),
               "prop2" => m(["subProp0" => "value0", "subProp1" => "value1"]),
               "prop3" => a(true, 1234, 56.78)]);
        $oJson = new CJson($mValue);
        $sEncJson = $oJson->encode();
        $oJson = new CJson($sEncJson);
        $bSuccess;
        $mDecMap = $oJson->decode($bSuccess);
        $this->assertTrue( $bSuccess && $mDecMap->equals($mValue) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
