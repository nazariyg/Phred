<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The class that lets you compute hashes on data and files in one piece as well as incremental hashes.
 */

// Method signatures:
//   static CUStringObject compute ($data, $hashType, $asBinary = false)
//   static CUStringObject computeFromFile ($dataFp, $hashType, $asBinary = false)
//   static CUStringObject computeHmac ($data, $hashType, $key, $asBinary = false)
//   static CUStringObject computeHmacFromFile ($dataFp, $hashType, $key, $asBinary = false)
//   static CUStringObject computePbkdf2 ($password, $hashType, $salt, $numIterations, $outputLength = null,
//     $asBinary = false)
//   static CUStringObject computeForPassword ($password)
//   static bool matchPasswordWithHash ($password, $hash)
//   static bool doesPasswordNeedRehashing ($passwordHash)
//   __construct ($hashType)
//   void computeMore ($data)
//   void computeMoreFromFile ($dataFp)
//   int computeMoreFromStream ($stream, $maxNumInputBytes = null)
//   CUStringObject finalize ($asBinary = false)

class CHash extends CRootClass
{
    /**
     * `enum`
     *
     * @var enum
     */
    const MD2 = 0;
    /**
     * `enum`
     *
     * @var enum
     */
    const MD4 = 1;
    /**
     * `enum` 32 characters.
     *
     * @var enum
     */
    const MD5 = 2;
    /**
     * `enum` 40 characters.
     *
     * @var enum
     */
    const SHA1 = 3;
    /**
     * `enum` 56 characters.
     *
     * @var enum
     */
    const SHA224 = 4;
    /**
     * `enum` 64 characters.
     *
     * @var enum
     */
    const SHA256 = 5;
    /**
     * `enum` 96 characters.
     *
     * @var enum
     */
    const SHA384 = 6;
    /**
     * `enum` 128 characters.
     *
     * @var enum
     */
    const SHA512 = 7;
    /**
     * `enum`
     *
     * @var enum
     */
    const RIPEMD128 = 8;
    /**
     * `enum`
     *
     * @var enum
     */
    const RIPEMD160 = 9;
    /**
     * `enum`
     *
     * @var enum
     */
    const RIPEMD256 = 10;
    /**
     * `enum`
     *
     * @var enum
     */
    const RIPEMD320 = 11;
    /**
     * `enum`
     *
     * @var enum
     */
    const WHIRLPOOL = 12;
    /**
     * `enum`
     *
     * @var enum
     */
    const TIGER128_3 = 13;
    /**
     * `enum`
     *
     * @var enum
     */
    const TIGER160_3 = 14;
    /**
     * `enum`
     *
     * @var enum
     */
    const TIGER192_3 = 15;
    /**
     * `enum`
     *
     * @var enum
     */
    const TIGER128_4 = 16;
    /**
     * `enum`
     *
     * @var enum
     */
    const TIGER160_4 = 17;
    /**
     * `enum`
     *
     * @var enum
     */
    const TIGER192_4 = 18;
    /**
     * `enum`
     *
     * @var enum
     */
    const SNEFRU = 19;
    /**
     * `enum`
     *
     * @var enum
     */
    const SNEFRU256 = 20;
    /**
     * `enum`
     *
     * @var enum
     */
    const GOST = 21;
    /**
     * `enum`
     *
     * @var enum
     */
    const ADLER32 = 22;
    /**
     * `enum` 8 characters.
     *
     * @var enum
     */
    const CRC32 = 23;
    /**
     * `enum`
     *
     * @var enum
     */
    const CRC32B = 24;
    /**
     * `enum`
     *
     * @var enum
     */
    const FNV132 = 25;
    /**
     * `enum`
     *
     * @var enum
     */
    const FNV164 = 26;
    /**
     * `enum`
     *
     * @var enum
     */
    const JOAAT = 27;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL128_3 = 28;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL160_3 = 29;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL192_3 = 30;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL224_3 = 31;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL256_3 = 32;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL128_4 = 33;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL160_4 = 34;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL192_4 = 35;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL224_4 = 36;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL256_4 = 37;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL128_5 = 38;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL160_5 = 39;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL192_5 = 40;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL224_5 = 41;
    /**
     * `enum`
     *
     * @var enum
     */
    const HAVAL256_5 = 42;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a data, computes a hash string using a specified hashing algorithm and returns it.
     *
     * @param  data $data The source data.
     * @param  enum $hashType The hashing algorithm to be used (see [Summary](#summary)).
     * @param  bool $asBinary **OPTIONAL. Default is** `false`. Tells whether the hash should be returned as a raw
     * binary data.
     *
     * @return CUStringObject The computed hash.
     */

    public static function compute ($data, $hashType, $asBinary = false)
    {
        assert( 'is_cstring($data) && is_enum($hashType) && is_bool($asBinary)',
            vs(isset($this), get_defined_vars()) );

        $strHashType = self::hashTypeToString($hashType);
        return hash($strHashType, $data, $asBinary);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a file, computes a hash string using a specified hashing algorithm and returns it.
     *
     * @param  string $dataFp The path to the source file.
     * @param  enum $hashType The hashing algorithm to be used (see [Summary](#summary)).
     * @param  bool $asBinary **OPTIONAL. Default is** `false`. Tells whether the hash should be returned as a raw
     * binary data.
     *
     * @return CUStringObject The computed hash.
     */

    public static function computeFromFile ($dataFp, $hashType, $asBinary = false)
    {
        assert( 'is_cstring($dataFp) && is_enum($hashType) && is_bool($asBinary)',
            vs(isset($this), get_defined_vars()) );

        $dataFp = CFilePath::frameworkPath($dataFp);

        $strHashType = self::hashTypeToString($hashType);
        return hash_file($strHashType, $dataFp, $asBinary);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a data, computes a keyed hash string using a specified hashing algorithm combined with the HMAC method and
     * returns it.
     *
     * @param  data $data The source data.
     * @param  enum $hashType The hashing algorithm to be used (see [Summary](#summary)).
     * @param  string $key The shared secret key.
     * @param  bool $asBinary **OPTIONAL. Default is** `false`. Tells whether the hash should be returned as a raw
     * binary data.
     *
     * @return CUStringObject The computed hash.
     */

    public static function computeHmac ($data, $hashType, $key, $asBinary = false)
    {
        assert( 'is_cstring($data) && is_enum($hashType) && is_cstring($key) && is_bool($asBinary)',
            vs(isset($this), get_defined_vars()) );

        $strHashType = self::hashTypeToString($hashType);
        return hash_hmac($strHashType, $data, $key, $asBinary);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a file, computes a keyed hash string using a specified hashing algorithm combined with the HMAC method and
     * returns it.
     *
     * @param  string $dataFp The path to the source file.
     * @param  enum $hashType The hashing algorithm to be used (see [Summary](#summary)).
     * @param  string $key The shared secret key.
     * @param  bool $asBinary **OPTIONAL. Default is** `false`. Tells whether the hash should be returned as a raw
     * binary data.
     *
     * @return CUStringObject The computed hash.
     */

    public static function computeHmacFromFile ($dataFp, $hashType, $key, $asBinary = false)
    {
        assert( 'is_cstring($dataFp) && is_enum($hashType) && is_cstring($key) && is_bool($asBinary)',
            vs(isset($this), get_defined_vars()) );

        $dataFp = CFilePath::frameworkPath($dataFp);

        $strHashType = self::hashTypeToString($hashType);
        return hash_hmac_file($strHashType, $dataFp, $key, $asBinary);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * From a password, computes a salted hash string that can be safely stored in a database or other places and
     * returns it.
     *
     * @param  string $password The password to be hashed.
     *
     * @return CUStringObject The computed hash.
     */

    public static function computeForPassword ($password)
    {
        assert( 'is_cstring($password)', vs(isset($this), get_defined_vars()) );
        return password_hash($password, PASSWORD_DEFAULT);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a password matches a salted hash.
     *
     * @param  string $password The password to be verified.
     * @param  string $hash The salted hash against which the password is to be verified.
     *
     * @return bool `true` if the password matches the hash, `false` otherwise.
     */

    public static function matchPasswordWithHash ($password, $hash)
    {
        assert( 'is_cstring($password) && is_cstring($hash)', vs(isset($this), get_defined_vars()) );
        return password_verify($password, $hash);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells whether a salted hash that was computed from a password needs to be refreshed due to an update to the
     * hash computing algorithm.
     *
     * @param  string $passwordHash The salted hash to be looked into.
     *
     * @return bool `true` if the password that is associated with the hash needs to be rehashed, `false` otherwise.
     */

    public static function doesPasswordNeedRehashing ($passwordHash)
    {
        assert( 'is_cstring($passwordHash)', vs(isset($this), get_defined_vars()) );
        return password_needs_rehash($passwordHash, PASSWORD_DEFAULT);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Computes a hash string that is a PBKDF2 key derivation of a password, using a specified hashing algorithm, and
     * returns it.
     *
     * @param  string $password The password to be used for derivation.
     * @param  enum $hashType The hashing algorithm to be used (see [Summary](#summary)).
     * @param  string $salt The salt to be used for derivation.
     * @param  int $numIterations The number of internal iterations to be performed in the process of derivation.
     * @param  int $outputLength **OPTIONAL. Default is** *unlimited*. The length of the resulting string.
     * @param  bool $asBinary **OPTIONAL. Default is** `false`. Tells whether the hash should be returned as a raw
     * binary data.
     *
     * @return CUStringObject The computed hash.
     */

    public static function computePbkdf2 ($password, $hashType, $salt, $numIterations, $outputLength = null,
        $asBinary = false)
    {
        assert( 'is_cstring($password) && is_enum($hashType) && is_cstring($salt) && is_int($numIterations) && ' .
                '(!isset($outputLength) || is_int($outputLength)) && is_bool($asBinary)',
            vs(isset($this), get_defined_vars()) );
        assert( '$numIterations > 0', vs(isset($this), get_defined_vars()) );
        assert( '!isset($outputLength) || $outputLength > 0', vs(isset($this), get_defined_vars()) );

        $strHashType = self::hashTypeToString($hashType);
        if ( !isset($outputLength) )
        {
            $outputLength = 0;
        }
        return hash_pbkdf2($strHashType, $password, $salt, $numIterations, $outputLength, $asBinary);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Creates an object for incremental hashing.
     *
     * @param  enum $hashType The hashing algorithm to be used (see [Summary](#summary)).
     */

    public function __construct ($hashType)
    {
        assert( 'is_enum($hashType)', vs(isset($this), get_defined_vars()) );

        $strHashType = self::hashTypeToString($hashType);
        $this->m_hashingContext = hash_init($strHashType);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Computes more of the hash from a data.
     *
     * @param  data $data The source data.
     *
     * @return void
     */

    public function computeMore ($data)
    {
        assert( 'is_cstring($data)', vs(isset($this), get_defined_vars()) );
        hash_update($this->m_hashingContext, $data);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Computes more of the hash from a file.
     *
     * @param  string $dataFp The path to the source file.
     *
     * @return void
     */

    public function computeMoreFromFile ($dataFp)
    {
        assert( 'is_cstring($dataFp)', vs(isset($this), get_defined_vars()) );

        $dataFp = CFilePath::frameworkPath($dataFp);

        hash_update_file($this->m_hashingContext, $dataFp);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Computes more of the hash from a stream.
     *
     * @param  resource $stream The handle of an open stream.
     * @param  int $maxNumInputBytes **OPTIONAL. Default is** *determine automatically*. The maximum number of bytes
     * to be processed from the stream.
     *
     * @return int The actual number of bytes that were processed from the stream.
     */

    public function computeMoreFromStream ($stream, $maxNumInputBytes = null)
    {
        assert( 'is_resource($stream) && (!isset($maxNumInputBytes) || is_int($maxNumInputBytes))',
            vs(isset($this), get_defined_vars()) );

        if ( !isset($maxNumInputBytes) )
        {
            return hash_update_stream($this->m_hashingContext, $stream);
        }
        else
        {
            return hash_update_stream($this->m_hashingContext, $stream, $maxNumInputBytes);
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Finalizes the incremental hashing and returns the computed hash.
     *
     * @param  bool $asBinary **OPTIONAL. Default is** `false`. Tells whether the hash should be returned as a raw
     * binary data.
     *
     * @return CUStringObject The computed hash.
     */

    public function finalize ($asBinary = false)
    {
        assert( 'is_bool($asBinary)', vs(isset($this), get_defined_vars()) );
        return hash_final($this->m_hashingContext, $asBinary);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * @ignore
     */

    public function __clone ()
    {
        $this->m_hashingContext = hash_copy($this->m_hashingContext);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function hashTypeToString ($hashType)
    {
        switch ( $hashType )
        {
        case self::MD2:
            return "md2";
        case self::MD4:
            return "md4";
        case self::MD5:
            return "md5";
        case self::SHA1:
            return "sha1";
        case self::SHA224:
            return "sha224";
        case self::SHA256:
            return "sha256";
        case self::SHA384:
            return "sha384";
        case self::SHA512:
            return "sha512";
        case self::RIPEMD128:
            return "ripemd128";
        case self::RIPEMD160:
            return "ripemd160";
        case self::RIPEMD256:
            return "ripemd256";
        case self::RIPEMD320:
            return "ripemd320";
        case self::WHIRLPOOL:
            return "whirlpool";
        case self::TIGER128_3:
            return "tiger128,3";
        case self::TIGER160_3:
            return "tiger160,3";
        case self::TIGER192_3:
            return "tiger192,3";
        case self::TIGER128_4:
            return "tiger128,4";
        case self::TIGER160_4:
            return "tiger160,4";
        case self::TIGER192_4:
            return "tiger192,4";
        case self::SNEFRU:
            return "snefru";
        case self::SNEFRU256:
            return "snefru256";
        case self::GOST:
            return "gost";
        case self::ADLER32:
            return "adler32";
        case self::CRC32:
            return "crc32";
        case self::CRC32B:
            return "crc32b";
        case self::FNV132:
            return "fnv132";
        case self::FNV164:
            return "fnv164";
        case self::JOAAT:
            return "joaat";
        case self::HAVAL128_3:
            return "haval128,3";
        case self::HAVAL160_3:
            return "haval160,3";
        case self::HAVAL192_3:
            return "haval192,3";
        case self::HAVAL224_3:
            return "haval224,3";
        case self::HAVAL256_3:
            return "haval256,3";
        case self::HAVAL128_4:
            return "haval128,4";
        case self::HAVAL160_4:
            return "haval160,4";
        case self::HAVAL192_4:
            return "haval192,4";
        case self::HAVAL224_4:
            return "haval224,4";
        case self::HAVAL256_4:
            return "haval256,4";
        case self::HAVAL128_5:
            return "haval128,5";
        case self::HAVAL160_5:
            return "haval160,5";
        case self::HAVAL192_5:
            return "haval192,5";
        case self::HAVAL224_5:
            return "haval224,5";
        case self::HAVAL256_5:
            return "haval256,5";
        default:
            assert( 'false', vs(isset($this), get_defined_vars()) );
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected $m_hashingContext;
}
