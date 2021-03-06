<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CHashTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompute ()
    {
        $data = "The quick brown fox jumps over the lazy dog.";

        $this->assertTrue(CHash::compute($data, CHash::MD5)->equals(
            "e4d909c290d0fb1ca068ffaddf22cbd0"));

        $this->assertTrue(CHash::compute($data, CHash::SHA256)->equals(
            "ef537f25c895bfa782526529a9b63d97aa631564d5d789c2b765448c8635fb6c"));

        $this->assertTrue(CHash::compute($data, CHash::SHA512)->equals(
            "91ea1245f20d46ae9a037a989f54f1f790f0a47607eeb8a14d12890cea77a1bb" .
            "c6c7ed9cf205e67b7f2b8fd4c7dfd3a7a8617e45f3c463d481c7e586c39ac1ed"));

        $this->assertTrue(CHash::compute($data, CHash::WHIRLPOOL)->equals(
            "87a7ff096082e3ffeb86db10feb91c5af36c2c71bc426fe310ce662e0338223e" .
            "217def0eab0b02b80eecf875657802bc5965e48f5c0a05467756f0d3f396faba"));

        $this->assertTrue(CHash::compute($data, CHash::CRC32)->equals(
            "bd2cf7ab") );

        $this->assertTrue(CHash::compute($data, CHash::HAVAL256_4)->equals(
            "ed02fda803b70c3966cbb9bc101abfc320cf45f8ad2d9cc37c3763415f0818b9"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComputeFromFile ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "The quick brown fox jumps over the lazy dog.");

        $this->assertTrue(CHash::computeFromFile($filePath, CHash::MD5)->equals(
            "e4d909c290d0fb1ca068ffaddf22cbd0"));

        $this->assertTrue(CHash::computeFromFile($filePath, CHash::SHA256)->equals(
            "ef537f25c895bfa782526529a9b63d97aa631564d5d789c2b765448c8635fb6c"));

        $this->assertTrue(CHash::computeFromFile($filePath, CHash::SHA512)->equals(
            "91ea1245f20d46ae9a037a989f54f1f790f0a47607eeb8a14d12890cea77a1bb" .
            "c6c7ed9cf205e67b7f2b8fd4c7dfd3a7a8617e45f3c463d481c7e586c39ac1ed"));

        $this->assertTrue(CHash::computeFromFile($filePath, CHash::WHIRLPOOL)->equals(
            "87a7ff096082e3ffeb86db10feb91c5af36c2c71bc426fe310ce662e0338223e" .
            "217def0eab0b02b80eecf875657802bc5965e48f5c0a05467756f0d3f396faba"));

        $this->assertTrue(CHash::computeFromFile($filePath, CHash::CRC32)->equals(
            "bd2cf7ab") );

        $this->assertTrue(CHash::computeFromFile($filePath, CHash::HAVAL256_4)->equals(
            "ed02fda803b70c3966cbb9bc101abfc320cf45f8ad2d9cc37c3763415f0818b9"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComputeHmac ()
    {
        $data = "The quick brown fox jumps over the lazy dog.";
        $key = "key";

        $this->assertTrue(CHash::computeHmac($data, CHash::MD5, $key)->equals(
            "120a17985a1e97bf8f0e38a52fb9fe79"));

        $this->assertTrue(CHash::computeHmac($data, CHash::SHA256, $key)->equals(
            "e98139c39d76eb80d8db982552b44b251b94f312987f91ee72d12ef673caa813"));

        $this->assertTrue(CHash::computeHmac($data, CHash::SHA512, $key)->equals(
            "451b681c334a8a24fe5baf00880443c7898d1c523db1d83bed03b2a46a960aec" .
            "a5bce23efba258225f5606fdb2c93a4b99d84b4bfd09f119a0971a2dff61db1e"));

        $this->assertTrue(CHash::computeHmac($data, CHash::WHIRLPOOL, $key)->equals(
            "03047f3652094b2749b42a4cf4698030f08f0c52cda87a2a124134844411a54a" .
            "a4e80e02357a42df483b79ad0380dfd7188e00ac87396e59f17a09bf7ab4f834"));

        $this->assertTrue(CHash::computeHmac($data, CHash::CRC32, $key)->equals(
            "d6132f08") );

        $this->assertTrue(CHash::computeHmac($data, CHash::HAVAL256_4, $key)->equals(
            "45168e6656e7dfd2be8acde7b4520f7d95d5ba05a90a30aa028876e9e20fdc08"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComputeHmacFromFile ()
    {
        $filePath = CFile::createTemporary();

        CFile::write($filePath, "The quick brown fox jumps over the lazy dog.");
        $key = "key";

        $this->assertTrue(CHash::computeHmacFromFile($filePath, CHash::MD5, $key)->equals(
            "120a17985a1e97bf8f0e38a52fb9fe79"));

        $this->assertTrue(CHash::computeHmacFromFile($filePath, CHash::SHA256, $key)->equals(
            "e98139c39d76eb80d8db982552b44b251b94f312987f91ee72d12ef673caa813"));

        $this->assertTrue(CHash::computeHmacFromFile($filePath, CHash::SHA512, $key)->equals(
            "451b681c334a8a24fe5baf00880443c7898d1c523db1d83bed03b2a46a960aec" .
            "a5bce23efba258225f5606fdb2c93a4b99d84b4bfd09f119a0971a2dff61db1e"));

        $this->assertTrue(CHash::computeHmacFromFile($filePath, CHash::WHIRLPOOL, $key)->equals(
            "03047f3652094b2749b42a4cf4698030f08f0c52cda87a2a124134844411a54a" .
            "a4e80e02357a42df483b79ad0380dfd7188e00ac87396e59f17a09bf7ab4f834"));

        $this->assertTrue(CHash::computeHmacFromFile($filePath, CHash::CRC32, $key)->equals(
            "d6132f08") );

        $this->assertTrue(CHash::computeHmacFromFile($filePath, CHash::HAVAL256_4, $key)->equals(
            "45168e6656e7dfd2be8acde7b4520f7d95d5ba05a90a30aa028876e9e20fdc08"));

        CFile::delete($filePath);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComputePbkdf2 ()
    {
        $pass = "The quick brown fox jumps over the lazy dog.";
        $salt = "salt";

        $this->assertTrue(CHash::computePbkdf2($pass, CHash::MD5, $salt, 2)->equals(
            "d6e6e5b3a605ac3f6e9cd339c20b4d00"));

        $this->assertTrue(CHash::computePbkdf2($pass, CHash::SHA256, $salt, 2)->equals(
            "bdd1f5ef34809ab32393d0afdcf06690702a8f4b40c4470e4e2f8533e00d6a5a"));

        $this->assertTrue(CHash::computePbkdf2($pass, CHash::SHA512, $salt, 2)->equals(
            "c2b3a8b66b84011227ab6478b48dd363e05c9f6f6a0cb5e066386f1620fc1d15" .
            "ce7d33b3e059d6f71330df4d081daf51a99409f3db3711587bdf538fbc92e881"));

        $this->assertTrue(CHash::computePbkdf2($pass, CHash::WHIRLPOOL, $salt, 2)->equals(
            "46fc707a9c7c6a81132f9932ac726dc53b1286674903356054cd3208869fce63" .
            "bb82744903b591fe6deec460323c6b9add0c761a6b972e2356244953644423f6"));

        $this->assertTrue(CHash::computePbkdf2($pass, CHash::CRC32, $salt, 2)->equals(
            "1a5a17f6"));

        $this->assertTrue(CHash::computePbkdf2($pass, CHash::HAVAL256_4, $salt, 2)->equals(
            "3c9eb349c15c80e5f18f457bc39ed5bb12b9653246fb58435a499b8363737f85"));

        $this->assertTrue(CHash::computePbkdf2($pass, CHash::HAVAL256_4, $salt, 2, 32)->equals(
            "3c9eb349c15c80e5f18f457bc39ed5bb"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMatchPasswordWithHash ()
    {
        $pass = "The quick brown fox jumps over the lazy dog.";
        $passHash = CHash::computeForPassword($pass);
        $this->assertTrue(CHash::matchPasswordWithHash($pass, $passHash));

        $pass = "tHE QUICK BROWN FOX JUMPS OVER THE LAZY DOG.";
        $passHash = CHash::computeForPassword($pass);
        $this->assertTrue(CHash::matchPasswordWithHash($pass, $passHash));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $hash = new CHash(CHash::SHA256);
        $hash->computeMore("The quick");
        $hash->computeMore(" brown fox");
        $hash->computeMore(" jumps over");
        $hash->computeMore(" the lazy dog.");
        $this->assertTrue($hash->finalize()->equals(
            "ef537f25c895bfa782526529a9b63d97aa631564d5d789c2b765448c8635fb6c"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComputeMore ()
    {
        $hash = new CHash(CHash::SHA256);
        $hash->computeMore("The quick");
        $hash->computeMore(" brown fox");
        $hash->computeMore(" jumps over");
        $hash->computeMore(" the lazy dog.");
        $this->assertTrue($hash->finalize()->equals(
            "ef537f25c895bfa782526529a9b63d97aa631564d5d789c2b765448c8635fb6c"));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testComputeMoreFromFile ()
    {
        $filePath0 = CFile::createTemporary();
        $filePath1 = CFile::createTemporary();
        $filePath2 = CFile::createTemporary();
        $filePath3 = CFile::createTemporary();

        CFile::write($filePath0, "The quick");
        CFile::write($filePath1, " brown fox");
        CFile::write($filePath2, " jumps over");
        CFile::write($filePath3, " the lazy dog.");

        $hash = new CHash(CHash::SHA256);
        $hash->computeMoreFromFile($filePath0);
        $hash->computeMoreFromFile($filePath1);
        $hash->computeMoreFromFile($filePath2);
        $hash->computeMoreFromFile($filePath3);
        $this->assertTrue($hash->finalize()->equals(
            "ef537f25c895bfa782526529a9b63d97aa631564d5d789c2b765448c8635fb6c"));

        CFile::delete($filePath0);
        CFile::delete($filePath1);
        CFile::delete($filePath2);
        CFile::delete($filePath3);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testClone ()
    {
        $filePath0 = CFile::createTemporary();
        $filePath1 = CFile::createTemporary();
        $filePath2 = CFile::createTemporary();
        $filePath3 = CFile::createTemporary();

        CFile::write($filePath0, "The quick");
        CFile::write($filePath1, " brown fox");
        CFile::write($filePath2, " jumps over");
        CFile::write($filePath3, " the lazy dog.");

        $hash0 = new CHash(CHash::SHA256);
        $hash0->computeMoreFromFile($filePath0);
        $hash0->computeMoreFromFile($filePath1);
        $hash0->computeMoreFromFile($filePath2);
        $hash0->computeMoreFromFile($filePath3);
        $hash1 = clone $hash0;
        $hash1->computeMoreFromFile($filePath0);
        $this->assertTrue($hash0->finalize()->equals(
            "ef537f25c895bfa782526529a9b63d97aa631564d5d789c2b765448c8635fb6c"));
        $this->assertTrue($hash1->finalize()->equals(
            "14dc40e99be202c4e59a0c6d1a8854bb50253624080435ed8c65bd6e5e880c95"));

        CFile::delete($filePath0);
        CFile::delete($filePath1);
        CFile::delete($filePath2);
        CFile::delete($filePath3);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
