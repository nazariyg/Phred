<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CGZipTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDecompress ()
    {
        $data = "The quick brown fox jumps over the lazy dog.";

        $compressedData = CGZip::compress($data, CGZip::FASTEST);
        $success;
        $decompressedData = CGZip::decompress($compressedData, $success);
        $this->assertTrue( $success && $decompressedData->equals($data) );

        $compressedData = CGZip::compress($data, CGZip::FAST);
        $success;
        $decompressedData = CGZip::decompress($compressedData, $success);
        $this->assertTrue( $success && $decompressedData->equals($data) );

        $compressedData = CGZip::compress($data, CGZip::NORMAL);
        $success;
        $decompressedData = CGZip::decompress($compressedData, $success);
        $this->assertTrue( $success && $decompressedData->equals($data) );

        $compressedData = CGZip::compress($data, CGZip::MAXIMUM);
        $success;
        $decompressedData = CGZip::decompress($compressedData, $success);
        $this->assertTrue( $success && $decompressedData->equals($data) );

        $compressedData = "BEEF";
        $success;
        $decompressedData = CGZip::decompress($compressedData, $success);
        $this->assertTrue( !$success && $decompressedData->equals("") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
