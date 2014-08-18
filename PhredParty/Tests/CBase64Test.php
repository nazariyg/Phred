<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CBase64Test extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDecode ()
    {
        $data = "RkxWAQUAAAAJAAAAABIAA0sAAAAAAAAAAgAKb25NZXRhRGF0YQgAAAAPAAg=";
        $success;
        $decodedData = CBase64::decode($data, $success);
        $this->assertTrue( $success && $decodedData->equals(
            "\x46\x4C\x56\x01\x05\x00\x00\x00\x09\x00\x00\x00\x00\x12\x00\x03\x4B\x00\x00\x00\x00\x00\x00\x00\x02\x00" .
            "\x0A\x6F\x6E\x4D\x65\x74\x61\x44\x61\x74\x61\x08\x00\x00\x00\x0F\x00\x08") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEncode ()
    {
        $data =
            "\x46\x4C\x56\x01\x05\x00\x00\x00\x09\x00\x00\x00\x00\x12\x00\x03\x4B\x00\x00\x00\x00\x00\x00\x00\x02\x00" .
            "\x0A\x6F\x6E\x4D\x65\x74\x61\x44\x61\x74\x61\x08\x00\x00\x00\x0F\x00\x08";
        $encodedData = CBase64::encode($data);
        $this->assertTrue($encodedData->equals("RkxWAQUAAAAJAAAAABIAA0sAAAAAAAAAAgAKb25NZXRhRGF0YQgAAAAPAAg="));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
