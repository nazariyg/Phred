<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014  Nazariy Gorpynyuk
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
        $byData = "The quick brown fox jumps over the lazy dog.";

        $byCompressedData = CGZip::compress($byData, CGZip::FASTEST);
        $bSuccess;
        $byDecompressedData = CGZip::decompress($byCompressedData, $bSuccess);
        $this->assertTrue( $bSuccess && $byDecompressedData->equals($byData) );

        $byCompressedData = CGZip::compress($byData, CGZip::FAST);
        $bSuccess;
        $byDecompressedData = CGZip::decompress($byCompressedData, $bSuccess);
        $this->assertTrue( $bSuccess && $byDecompressedData->equals($byData) );

        $byCompressedData = CGZip::compress($byData, CGZip::NORMAL);
        $bSuccess;
        $byDecompressedData = CGZip::decompress($byCompressedData, $bSuccess);
        $this->assertTrue( $bSuccess && $byDecompressedData->equals($byData) );

        $byCompressedData = CGZip::compress($byData, CGZip::MAXIMUM);
        $bSuccess;
        $byDecompressedData = CGZip::decompress($byCompressedData, $bSuccess);
        $this->assertTrue( $bSuccess && $byDecompressedData->equals($byData) );

        $byCompressedData = "BEEF";
        $bSuccess;
        $byDecompressedData = CGZip::decompress($byCompressedData, $bSuccess);
        $this->assertTrue( !$bSuccess && $byDecompressedData->equals("") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
